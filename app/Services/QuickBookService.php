<?php

namespace App\Services;

use App\CategoryCredit;
use App\Event;
use App\Credit;
use App\Payment;
use App\Organization;
use App\Subscription;
use App\DancerRoutine;
use App\CategoryInvoice;
use Illuminate\Http\Request;
use App\Invoice as InvoiceModel;
use App\Token;
use Exception;
use Illuminate\Support\Facades\Http;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\Line;
use Illuminate\Support\Facades\Validator;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\CreditMemo;
use QuickBooksOnline\API\Facades\QuickBookClass;
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


    private function create_class($name)
    {
        $data = [];
        $data['name'] = $name;
        $classObj = QuickBookClass::create($data);
        $classResObj = $this->getDataService()->Add($classObj);
        $error = $this->getDataService()->getLastError();
        if ($error) {
            return null;
        }
        return $classResObj;
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



        if ($customer = $this->findCustomerByName($validatedData['invoices']['customer']['name'])) {
            return $customer;
        }

        return $this->create_customer($validatedData['invoices']['customer']);
    }


    private function getDataService()
    {

        // If Refresh token in DB
        $token = Token::findOrCreate(1);
        if ($token && $token->refresh_token) {
            $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => env('QB_CLIENT_ID'),
                'ClientSecret' =>  env('QB_CLIENT_SECRET'),
                'RedirectURI' => env('QB_OAUTH_REDIRECT_URI'),
                'scope' => env('QB_OAUTH_SCOPE'),
                'baseUrl' => env('QB_ENV'),
                'QBORealmID' => env('QB_REALMID'),
                'refreshTokenKey' => $token->refresh_token
            ));
        }
        //Else
        else {
            $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => env('QB_CLIENT_ID'),
                'ClientSecret' =>  env('QB_CLIENT_SECRET'),
                'RedirectURI' => env('QB_OAUTH_REDIRECT_URI'),
                'scope' => env('QB_OAUTH_SCOPE'),
                'baseUrl' => env('QB_ENV'),
                'QBORealmID' => env('QB_REALMID'),
                'refreshTokenKey' => env('QB_REFRESH_TOKEN')
            ));
        }

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        try {
            $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
        } catch (Exception $err) {
            $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken(env('QB_REFRESH_TOKEN'));
        }
        $dataService->throwExceptionOnError(true);
        $error = $OAuth2LoginHelper->getLastError();
        $token->refresh_token = $refreshedAccessTokenObj->getRefreshToken();
        $token->access_token = $refreshedAccessTokenObj->getAccessToken();
        $token->refresh_token_expires = $refreshedAccessTokenObj->getRefreshTokenExpiresAt();
        $token->access_token_expires = $refreshedAccessTokenObj->getAccessTokenExpiresAt();
        $token->save();

        $dataService->throwExceptionOnError(true);
        $error = $OAuth2LoginHelper->getLastError();

        if ($error) {
        } else {
            // Refresh Token is called successfully
            $dataService->updateOAuth2Token($refreshedAccessTokenObj);
        }
        return $dataService;
    }

    private function findCustomerByName($name)
    {
        $customerArray = $this->getDataService()->Query("select * from Customer where DisplayName='" . $name . "'");
        $error = $this->getDataService()->getLastError();

        // dd($error);
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
            'invoices.event_name' => 'required|string',
            'subscription_id' => 'required'
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        // dd();

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

        $total = array_reduce($v->validated()['invoices']['data'], function ($sum, $line) {
            return $sum += $line['total'];
        });

        $newInvoice = new InvoiceModel;
        $newInvoice->doc_number = $resultingInvoiceObj->DocNumber;
        $newInvoice->amount = $resultingInvoiceObj->TotalAmt;
        $newInvoice->subscription_id = $request->subscription_id;
        $newInvoice->total = $total;
        $newInvoice->save();


        $validatedData = $v->validated()['invoices'];

        foreach ($validatedData['data'] as $line) {
            CategoryInvoice::create([
                'invoice_id' => $newInvoice->id,
                'category_id' => $line['id'],
                'factured' => $line['entries'],
                'entries' => $line['entries'],
                'routine_count' => $line['routines_count'],
                'subscription_id' => $newInvoice->subscription_id
            ]);
        }


        $routines = Subscription::find($request->subscription_id)->routines;
        foreach ($routines as $routine) {
            if (!$routine->doc_number) {
                $routine->doc_number = $resultingInvoiceObj->DocNumber;
                $routine->save();
            }

            $dancerRoutines = DancerRoutine::where('routine_id', $routine->id)->get();
            foreach ($dancerRoutines as $dancerRoutine) {
                $dancerRoutine->doc_number = $resultingInvoiceObj->DocNumber;
                $dancerRoutine->save();
            }
        }

        $res['success'] = true;
        $res['message'] = __("messages.global.QBO_created");
        $res['data'] = $resultingInvoiceObj;
        return $res;
    }

    public function create_creditmemo(Request $request)
    {
        // Run validation
        $v = Validator::make($request->all(), [
            'invoices' => 'required|array',
            'invoices.data' => 'required|array',
            'invoices.customer' => 'required|array',
            'invoices.event_name' => 'required|string',
            'subscription_id' => 'required'
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }


        // Get ABS(credit)

        $creditmemoObj = $this->generateCreditData($request, $v);
        $resultingCreditmemoObj = $this->getDataService()->Add($creditmemoObj);
        $error = $this->getDataService()->getLastError();
        if ($error || \is_null($resultingCreditmemoObj)) {
            $res['success'] = false;
            $res['message'] =  __("messages.global.fail");
            $res['error'] = $error;
            return $res;
        }


        $validatedData = $v->validated()['invoices'];
        foreach ($validatedData['data'] as $line) {
            $categoryInvoice = CategoryInvoice::groupBy('category_id')->where('category_id', $line['id'])->where('subscription_id', $request->subscription_id)->first();
            $categoryInvoice->factured -= \abs($line['credit']);
            $categoryInvoice->save();
        }
        // Store this new Invoice and the data some where and display

        $total = array_reduce($v->validated()['invoices']['data'], function ($sum, $line) {
            return $sum += ($line['formatted_rebate_price'] * \abs($line['credit']));
        });

        $credit = new Credit;
        $credit->doc_number = $resultingCreditmemoObj->DocNumber;
        $credit->amount = $resultingCreditmemoObj->TotalAmt;
        $credit->subscription_id = $request->subscription_id;
        $credit->total = $total;
        $credit->save();


        $validatedData = $v->validated()['invoices'];
        foreach ($validatedData['data'] as $line) {
            $category_credit = new CategoryCredit();
            $category_credit->credit_id = $credit->id;
            $category_credit->category_id = $line['id'];
            $category_credit->entries = \abs($line['credit']);
            $category_credit->routines = $line['routines_count'];
            $category_credit->save();
        }

        $res['success'] = true;
        $res['message'] = __("messages.global.QBO_created");
        $res['data'] =  $resultingCreditmemoObj;
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

    private function findOrCreateClass($name)
    {
        $classRes = $this->findClass(null, $name);
        if (is_null($classRes)) {
            $classRes = $this->create_class($name);
        }
        return $classRes;
    }

    private function generateCreditData($request, $v)
    {
        $customerObj = $this->findOrCreateCustomer($request);
        // $res = [];
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

                $credit = \abs($line['credit']);
                $total = $line['formatted_rebate_price'] * $credit;

                $lineItem = Line::create([
                    'DetailType' => 'SalesItemLineDetail',
                    'Amount' => $total,
                    'Description' => $line['name'] . " ( Routine: $credit )",
                    'SalesItemLineDetail' => [
                        'Qty' => $credit,
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


            //CreateOrRead Class
            $class = [];
            $classObj = $this->findOrCreateClass($events[$validatedData['event_name']]);
            $class['name'] = $classObj->Name;
            $class['value'] =  $classObj->Id;

            $data['ClassRef'] = $class;


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

            $creditObj = CreditMemo::create($data);

            return $creditObj;
        }

        // Customer not found and cannot create new
        return response()->json([
            'status' => 'error',
            'error' => $customerObj['error'],
            'msg' => __('messages.global.fail')
        ], 400);
    }

    private function generateInvoiceData($request, $v)
    {
        $customerObj = $this->findOrCreateCustomer($request);
        // $res = [];
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
        // dd($error, $paymentArray);
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
            if (isset($payment->Line) && is_array($payment->Line)) {
                foreach ($payment->Line as $line) {
                    if (isset($line->LinkedTxn) && $line->LinkedTxn->TxnType == "Invoice") {
                        $this->createOrUpdatePayment($payment, 'invoice', $line);
                    } else {
                        $this->createOrUpdatePayment($payment, 'creditnote', $line);
                    }
                }
            } else if (isset($payment->Line->LinkedTxn) && $payment->Line->LinkedTxn->TxnType == "Invoice") {
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

    private function findClass($id, $name = null)
    {
        if (!is_null($name)) {
            $classArray = $this->getDataService()->Query("select * from Class WHERE Name='" . $name . "'");
            $error = $this->getDataService()->getLastError();
            if ($error) {
                return null;
            } else {
                if (is_array($classArray) && sizeof($classArray) > 0) {
                    return current($classArray);
                }
                return null;
            }
        } else {

            $class = $this->getDataService()->FindById("Class", $id);
            $error = $this->getDataService()->getLastError();
            if ($error) {
                return null;
            } else {
                return $class;
            }
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

    private function createOrUpdatePayment($payment, $type, $line = null)
    {
        $note = '';
        $subscription_id = null;

        if ($type == 'invoice') {
            if (!is_null($line)) {
                $subscription_id = $this->findSubscriptionIDFromInvoice($line->LinkedTxn->TxnId);
            } else {
                $subscription_id = $this->findSubscriptionIDFromInvoice($payment->Line->LinkedTxn->TxnId);
            }
            // set note here
            $note = 'PAYMENT QBO ' . $payment->DocNumber;
        } else {
            if (isset($payment->ClassRef)) {
                $customer = $this->findCustomer($payment->CustomerRef);
                $event = $this->findClass($payment->ClassRef);
                if ($customer && $event) {
                    $subscription_id = $this->findSubscriptionIDFromEventAndOrganization($this->findIDEventByName($event->Name), $this->findOrganizationIDByName($customer->CompanyName));
                }
                // Set Note here
                $note = 'CREDIT QBO ' . $payment->DocNumber;
            }
        }

        if (!is_null($subscription_id)) {
            $oldPayment = Payment::where('subscription_id', $subscription_id)->first();

            if (!$oldPayment) {
                if (Subscription::find($subscription_id)) {
                    $newpayment = new Payment;
                    $newpayment->payment_type_id = 3; // Transfer
                    $newpayment->subscription_id = $subscription_id;
                    $newpayment->receive_on = $payment->TxnDate;
                    $newpayment->amount = $payment->TotalAmt;
                    $newpayment->note = $note;
                    $newpayment->save();
                }
                return;
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
