<?php
namespace App\Services;

use App\Event;
use App\Payment;
use App\Organization;
use App\Subscription;
use App\CategoryInvoice;
use App\Invoice as InvoiceModel;
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
        $data['DisplayName'] = $validatedData['name'];
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


        if ($customer = $this->findCustomerByName($validatedData['invoices']['customer']['user']['name'])) {
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
            'refreshTokenKey' =>env('QB_REFRESH_TOKEN')
        ));
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
        // session(['QB_REFRESH_TOKEN' => $refreshedAccessTokenObj->getRefreshToken()]);
        // dd($refreshedAccessTokenObj->getRefreshToken());
        $dataService->throwExceptionOnError(true);
        $error = $OAuth2LoginHelper->getLastError();
        if ($error) {
        } else {
            //Refresh Token is called successfully
            $dataService->updateOAuth2Token($refreshedAccessTokenObj);
        }
        return $dataService;
    }

    private function findCustomerByName($name)
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
        // dd(Subscription::find($request->subscription_id)->routines);
        // Run validation
        $v = Validator::make($request->all(), [
            'invoices' => 'required|array',
            'invoices.data' => 'required|array',
            'invoices.customer' => 'required|array',
            'invoices.event_name'=> 'required|string',
            'subscription_id' => 'required'
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        // dd($v->validated()['invoices']['data']);
        
        $invoiceObj = $this->generateInvoiceData($request, $v);
        $resultingInvoiceObj = $this->getDataService()->Add($invoiceObj);
        $error = $this->getDataService()->getLastError();
        if ($error || \is_null($resultingInvoiceObj)) {
            $res['success'] = false;
            $res['message'] =  __("messages.global.fail");
            $res['error'] = $error;
            return $res;
        }

        // Store this new Invoice and the data some where and display

        // dd($resultingInvoiceObj);

        $newInvoice = new InvoiceModel;
        $newInvoice->doc_number = $resultingInvoiceObj->DocNumber;
        $newInvoice->amount = $resultingInvoiceObj->TotalAmt;
        $newInvoice->subscription_id = $request->subscription_id;
        $newInvoice->save();

        // select * from `newinvoice` where subscription_id = $subscription_id.
        // loop through all `newinvoice`
        // select * `newCategory` where invoice_id = $newinvoice_id
        // with category
        // with routine where doc_number = newinvoice->qbo
            

        $validatedData = $v->validated()['invoices'];

        foreach ($validatedData['data'] as $line) {
            CategoryInvoice::create([
                'invoice_id' => $newInvoice->id,
                'category_id' => $line['id']

            ]) ;
        }


        $routines = Subscription::find($request->subscription_id)->routines;
        foreach ($routines as $routine) {
            if (!$routine->doc_number) {
                $routine->doc_number = $resultingInvoiceObj->DocNumber;
                $routine->save();
            }
        }
        
        $res['success'] = true;
        $res['message'] = __("messages.global.QBO_created");
        $res['data'] = $resultingInvoiceObj;
        return $res;
    }

    public function findOrCreateItem($name)
    {
        $item = $this->getItem($name);
        if (!\is_null($item)) {
            return $item;
        }
        $item = $this->create_item($name);
        return $item;
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
        $events = [
            'gatineau' => 'Gatineau',
            'levis' => 'Lévis',
            'saintehyacinthe' => 'Saint-Hyacinthe'
        ];

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
                    'TaxCodeRef' => ['value' => "8"],
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
                [
                    "DefinitionId" => "1",
                    "StringValue" =>  $events[$validatedData['event_name']],
                    "Type" => "StringType",
                    "Name" => "EventName"
                ],
                [
                    "DefinitionId" => "2",
                    "StringValue" =>  $request->subscription_id,
                    "Type" => "StringType",
                    "Name" => "SubscriptionID"
                ]
            ];
            $data["CustomField"] = $customField;

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

    public function get_daily_payments()
    {
        $paymentArray = $this->getDataService()->Query("select * from Payment WHERE TxnDate >='" . now()->toDateString() . "'");
        $error = $this->getDataService()->getLastError();
        if ($error || is_null($paymentArray)) {
            return null;
        } else {
            if (is_array($paymentArray) && sizeof($paymentArray) > 0) {
                // Store in Database
                return $this->storePayments($paymentArray);
            }
            return null;
        }
    }


    public function get_daily_creditNotes()
    {
        $paymentArray = $this->getDataService()->Query("select * from CreditMemo WHERE TxnDate >='" . now()->toDateString() . "'");
        $error = $this->getDataService()->getLastError();
        if ($error || is_null($paymentArray)) {
            return null;
        } else {
            if (is_array($paymentArray) && sizeof($paymentArray) > 0) {
                // Store in Database
                return $this->storePayments($paymentArray);
            }
            return null;
        }
    }

    private function storePayments($payments)
    {
        foreach ($payments as $payment) {
            if (isset($payment->Line) && isset($payment->Line->LinkedTxn) && $payment->Line->LinkedTxn->TxnType == "Invoice") {
                $this->createOrUpdatePayment($payment, 'invoice');
            } else {
                $this->createOrUpdatePayment($payment, 'creditnote');
            }
            continue;
        }
        return;
    }

    private function findCustomer($id)
    {
        $customer = $this->getDataService()->FindById("Customer", $id);
        $error = $this->getDataService()->getLastError();
        if ($error) {
            return null;
        } else {
            return $customer;
        }
    }

    private function findClass($id)
    {
        $class = $this->getDataService()->FindById("Class", $id);
        $error = $this->getDataService()->getLastError();
        if ($error) {
            return null;
        } else {
            return $class;
        }
    }

    private function findOrganizationIDByName($name)
    {
        $org = Organization::where('name', $name)->first();
        if ($org) {
            return $org->id;
        }
        return null;
    }

    private function findIDEventByName($name)
    {
        $event = Event::getEventInfos(\strtolower($name));
        if ($event) {
            return $event['id'];
        }
        return null;
    }

    private function createOrUpdatePayment($payment, $type)
    {
        $note = '';
        $subscription_id = null;

        if ($type == 'invoice') {
            $subscription_id = $this->findSubscriptionIDFromInvoice($payment->Line->LinkedTxn->TxnId);
            // set note here
            $note = 'PAYMENT QBO '.$payment->DocNumber;
        } else {
            $customer = $this->findCustomer($payment->CustomerRef);
            $event = $this->findClass($payment->ClassRef);
            if ($customer && $event) {
                $subscription_id = $this->findSubscriptionIDFromEventAndOrganization($this->findIDEventByName($event->Name), $this->findOrganizationIDByName($customer->CompanyName));
            }
            // Set Note here
            $note = 'CREDIT QBO '.$payment->DocNumber;
        }
        
        if (!is_null($subscription_id)) {
            $oldPayment = Payment::where('subscription_id', $subscription_id)->first();
        
            if (!$oldPayment) {
                $newpayment = new Payment;
                $newpayment->payment_type_id = 3; // Transfer
                $newpayment->subscription_id = $subscription_id;
                $newpayment->receive_on = $payment->TxnDate;
                $newpayment->amount = $payment->TotalAmt;
                $newpayment->note = $note;
                $newpayment->save();
            } else {
                $oldPayment->payment_type_id = 3; // Transfer
                $oldPayment->subscription_id = $subscription_id;
                $oldPayment->receive_on = $payment->TxnDate;
                $oldPayment->amount = $payment->TotalAmt;
                $oldPayment->note = $note;
                $oldPayment->save();
            }

            // Update Subscription Payment status
            $this->updateSubscriptionStatus($subscription_id, 4 /* Paid */);
        }
        return;
    }

    private function findSubscriptionIDFromEventAndOrganization($event, $organization)
    {
        $subscription = Subscription::where('event_id', $event)->where('organization_id', $organization)->first();
        // dd($subscription, $event, $organization);
        if ($subscription) {
            return $subscription->id;
        }
        return null;
    }

    private function updateSubscriptionStatus($id, $status)
    {
        if ($subscription = Subscription::find($id)) {
            $subscription->status_id = $status;
            $subscription->save();
        }
    }

    private function findSubscriptionIDFromInvoice($id)
    {
        $invoice = $this->findInvoice($id);
        if ($invoice) {
            return $this->findSubscriptionIDFromCustomField($invoice->CustomField);
        }
        return null;
    }

    private function findSubscriptionIDFromCustomField($customFields)
    {
        if (is_array($customFields)) {
            foreach ($customFields as $field) {
                if ($field->DefinitionId == 2) {
                    return $field->StringValue;
                }
                continue;
            }
        } else {
            if ($customFields->DefinitionId == 2) {
                return $customFields->StringValue;
            }
        }
        return null;
    }

    private function findInvoice($id)
    {
        $invoice = $this->getDataService()->FindById("Invoice", $id);
        $error = $this->getDataService()->getLastError();
        if ($error) {
            return null;
        } else {
            return $invoice;
        }
    }
}
