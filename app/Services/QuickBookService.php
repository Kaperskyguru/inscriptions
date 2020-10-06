<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\Line;
use Illuminate\Support\Facades\Validator;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\DataService\DataService;

class QuickBookService
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function create_customer(array $validatedData)
    {
        $names = explode(' ', $validatedData['user']['name']);

        $address = [];
        $address['City'] = $validatedData['city'];
        $address['Line1'] = $validatedData['address'];
        $address['PostalCode'] = $validatedData['zipcode'];

        $data['FullyQualifiedName'] = $validatedData['user']['name'];
        $data['FamilyName'] = $names[1];
        $data['GivenName'] = $names[0];
        $data['PrimaryEmailAddr']['Address'] = $validatedData['user']['email'];
        $data['PrimaryPhone']['FreeFormNumber'] = $validatedData['phone'];
        $data['CompanyName'] = $validatedData['name'];
        $data['BillAddr'] = $address;

        // Create Customer
        $customerRequestObj = Customer::create($data);
        $customerResponseObj = $this->getDataService()->Add($customerRequestObj);
        $error = $this->getDataService()->getLastError();
        $res = [];

        if ($error) {
            $res['status'] = false;
            $res['error'] = $error;
            return $res;
        }
        $res['status'] = true;
        $res['data'] = $customerResponseObj;
        return $res;
    }

    public function findOrCreateCustomer(Request $request)
    {
       
        // Run validation
        $v = Validator::make($request->all(), [
            'invoices.customer.city' => 'string',
            'invoices.customer.address' => 'string',
            'invoices.customer.user.name' => 'required|string',
            'invoices.customer.user.email' => 'required|email',
            'invoices.customer.name' => 'string',
            'invoices.customer.zipcode' => 'string',
            'invoices.customer.phone' => 'string'
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
       
        $validatedData = $v->validated();

        if ($customer = $this->findCustomer($validatedData['invoices']['customer']['user']['name'])) {
            return $customer;
        }

        return $this->create_customer($validatedData['invoices']['customer']);
    }


    private function getDataService()
    {
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => env('QB_CLIENT_ID'),
            'ClientSecret' =>  env('QB_CLIENT_SECRET'),
            'RedirectURI' => env('QB_OAUTH_REDIRECT_URI'),
            'scope' => env('QB_OAUTH_SCOPE'),
            'baseUrl' => env('QB_ENV'),
            'QBORealmID' => env('QB_REALMID'),
            'refreshTokenKey' => session()->get('QB_REFRESH_TOKEN', env('QB_REFRESH_TOKEN'))
        ));
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
        session(['QB_REFRESH_TOKEN' => $refreshedAccessTokenObj->getRefreshToken()]);

        $error = $OAuth2LoginHelper->getLastError();
        if ($error) {
        } else {
            //Refresh Token is called successfully
            $dataService->updateOAuth2Token($refreshedAccessTokenObj);
        }
        return $dataService;
    }

    private function findCustomer($name)
    {
        $customerArray = $this->getDataService()->Query("select * from Customer where DisplayName='" . $name . "'");
        $error = $this->getDataService()->getLastError();

        $res = [];
        if ($error) {
            $res['status'] = false;
            $res['error'] = $error;
            return $res;
        }
        if (is_array($customerArray) && sizeof($customerArray) > 0) {
            $res['status'] = true;
            $res['data'] = current($customerArray);
            return $res;
        }
    }

    public function create_invoice(Request $request)
    {

        // Run validation
        $v = Validator::make($request->all(), [
            'invoices' => 'required|array',
            'invoices.data' => 'required|array',
            'invoices.customer' => 'required|array',
            'invoices.event_name'=> 'required|string'
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        
        $invoiceObj = $this->generateInvoiceData($request, $v);
        $resultingInvoiceObj = $this->getDataService()->Add($invoiceObj);

        $error = $this->getDataService()->getLastError();
        if ($error || \is_null($resultingInvoiceObj)) {
            $res['success'] = false;
            $res['message'] =  __("messages.global.fail");
            $res['error'] = $error;
            return $res;
        }
        
        $res['success'] = true;
        $res['message'] = __("messages.global.QBO_created");
        $res['data'] = $resultingInvoiceObj;
        return $res;
    }

    public function findOrCreateItem($name)
    {
        if (!\is_null($item = $this->getItem($name))) {
            return $item;
        }
        return $this->create_item($name);
    }
    
    private function create_item($name)
    {
        $data = [];
        $data['Name'] = $name;
        $data['Type'] = 'Service';
        $data['IncomeAccountRef']['Name'] = 'Service';
        $data['IncomeAccountRef']['Value'] = 1;

        $itemObj = Item::create($data);
        $resultingItemObj = $this->getDataService()->Add($itemObj);
        $error = $this->getDataService()->getLastError();
        if ($error) {
            return null;
        }
        return $resultingItemObj;
    }

    private function getItem($name)
    {
        $itemArray = $this->getDataService()->Query("select * from Item WHERE Name='" . $name . "'");
        $error = $this->getDataService()->getLastError();
        if ($error) {
            return null;
        } else {
            if (is_array($itemArray) && sizeof($itemArray) > 0) {
                return current($itemArray);
            }
            return null;
        }
    }

    private function generateInvoiceData($request, $v)
    {
        $customerObj = $this->findOrCreateCustomer($request);
        $res = [];


        if ($customerObj['status']) {
            $customer = $customerObj['data'];

            $validatedData = $v->validated()['invoices'];
            $lines = [];
            foreach ($validatedData['data'] as $line) {
                $item = [];
                $itemObj = $this->findOrCreateItem($line['name']);
                $item['name'] = $itemObj->Name;
                $item['value'] =  $itemObj->Id;
                
                $routine = $line['routines_count'];

                $lineItem = Line::create([
                'DetailType' => 'SalesItemLineDetail',
                'Amount' => $line['total'],
                'Description' => $line['name'] . " ( Routine: $routine )",
                'SalesItemLineDetail' => [
                    'Qty' => $line['entries'],
                    'UnitPrice' => $line['formatted_rebate_price'],
                    'TaxCodeRef' => ['value' => "TAX"],
                    'ItemRef' => $item
                    ],
                ]);
                \array_push($lines, $lineItem);
            }

            $data['CustomerRef']['value'] = $customer->Id;
            $data["BillEmail"]["Address"] = $validatedData['customer']['user']['email'];
            $data['Line'] = $lines;

            // Add Custom Field
            $customField = [
                "DefinitionId" => "1",
                "StringValue" =>  $validatedData['event_name'],
                "Type" => "StringType",
                "Name" => "Crew #"
            ];
            $data["CustomField"] = $customField;

            // Add Tax
            $data["TxnTaxDetail"]['TxnTaxCodeRef']['value'] = '7';
            $data['TxnTaxDetail']['TxnTaxCodeRef']['name'] = 'GST/QST QC - 9.975';

            $invoiceObj = Invoice::create($data);

            return $invoiceObj;
        }

        // Customer not found and cannot create new
        return response()->json([
            'status' => 'error',
            'error' => $customerObj['error'],
            'msg' => __('messages.global.fail')
        ], 400);
    }
}
