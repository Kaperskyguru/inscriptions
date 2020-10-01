<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Line;



use Session;
use App\Category;
use App\Organization;

class QuickbooksController extends Controller
{
    //
    public function index(Request $request)
    {
       
        // $dataService = DataService::Configure(array(
        //     'auth_mode' => 'oauth2',
        //     'ClientID' => env('QB_CLIENT_ID'),
        //     'ClientSecret' =>  env('QB_CLIENT_SECRET'),
        //     'RedirectURI' => env('QB_OAUTH_REDIRECT_URI'),
        //     'scope' => env('QB_OAUTH_SCOPE'),
        //     'baseUrl' => "development"
        // ));
        
        // $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        
        // // Get the Authorization URL from the SDK
        // $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
       

        // return view('quickbooks', ['authUrl' => $authUrl]);
    }
    public function callback(Request $request)
    {
        $dataService = $this->getDataService();
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

        $organization_id = session('organization_id');
        $subscription_id = session('subscription_id');


        $organizations = Organization::orderBy('name')->whereHas('subscriptions', function ($query) use ($subscription_id) {
            $query->where('subscriptions.id', $subscription_id);
        })
        ->with('organizationType')
        ->with([
            'dancers' => function ($query) {
                $query->orderBy('first_name', 'ASC');
            },
        ])
        ->with('user')
        ->with(
            [
            'subscriptions' => function ($query) use ($subscription_id) {
                $query->where('subscriptions.id', $subscription_id)->withCount('routines');
            },
            'subscriptions.routines.category',
            'subscriptions.routines.level',
            'subscriptions.routines.style',
            'subscriptions.routines.dancers',
            'subscriptions.status',
            'subscriptions.payments.paymentType'
        ]
        )
        ->first();

        $categories = Category::groupBy('id')->where('id', '!=', 7)
        ->where('event_type_id', config('EVENT_TYPE_ID'))
        ->with([
            'routines' => function ($query) use ($subscription_id) {
                $query->where('subscription_id', $subscription_id);
            },
        ])
        ->withCount([
            'routines' => function ($query) use ($subscription_id) {
                $query->where('subscription_id', $subscription_id);
            },
            
        ])
        ->get();
        
        $parseUrl = $this->parseAuthRedirectUrl($_SERVER['QUERY_STRING']);
        
        $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($parseUrl['code'], $parseUrl['realmId']);
        $dataService->updateOAuth2Token($accessToken);

        
        session(['QB_TOKEN' => $accessToken]);

        $dataService->updateOAuth2Token($accessToken);


        /*
        * 1. Get CustomerRef and ItemRef
        */
        $customerRef = $this->getCustomerObj($dataService, $organizations);

        //var_dump($this->getTaxRate($dataService));
        //exit;
        // Run a query
        $entities = $dataService->Query("SELECT * FROM TaxRate");
        // Echo some formatted output
        if (count($entities) > 0) {
            $i = 0;
            foreach ($entities as $oneTaxRate) {
                echo "TaxRate[$i] Name: {$oneTaxRate->Name}	Rate {$oneTaxRate->RateValue} AgencyRef {$oneTaxRate->AgencyRef} (SpecialTaxType {$oneTaxRate->SpecialTaxType})\n";
                $i++;
            }
        }
        exit;

        $lines = [];

        foreach ($categories as $key => $category) {
            $entries = 0;
            foreach ($category->routines as $routine) {
                $entries += count($routine->dancers);
            }
            $total = $entries *  $category['rebate_price'];

            $itemRef = $this->getItemObj($dataService, $category->translate('en')->name);
            $LineObj = Line::create([
                    "Amount" => number_format(($total / 100), 2, '.', ','),
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => [
                        "Qty" => $entries,
                        "ItemRef" => [
                            "value" => $itemRef->Id
                        ],
                        
                        "UnitPrice" =>  $itemRef->UnitPrice,
                        //"TaxCodeRef" => ["value" => "3"]
                            
                    ]
            ]);
            $lines[] = $LineObj;
            //$categories[$key]['entries'] =  $entries;
            //$categories[$key]['entries'] =  $entries;
            // $categories[$key]['total'] =
        }
        $invoiceObj = Invoice::create([
                "Line" => $lines,
                "CustomerRef"=> [
                    "value"=> $customerRef->Id
                ],
                "BillEmail" => [
                    "Address" => $organizations->user->email
                ]
            ]);
        $resultingInvoiceObj = $dataService->Add($invoiceObj);
        $error = $dataService->getLastError();
        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
            exit;
        }
        //$invoiceId = $resultingInvoiceObj->Id;   // This needs to be passed in the Payment creation later
        // echo "Created invoice Id={$invoiceId}. Reconstructed response body below:\n";
        // $result = json_encode($resultingInvoiceObj, JSON_PRETTY_PRINT);
        // print_r($result . "\n\n\n");
        echo 'Facture Généré';
    }
    private function getTaxRate($dataService)
    {
        $taxesArray = $dataService->Query("Select * From TaxRate");
        $error = $dataService->getLastError();
        if ($error) {
            var_dump($error);
            exit;
        } else {
            if (is_array($taxesArray) && sizeof($taxesArray) > 0) {
                return current($taxesArray);
            }
        }
    }
    private function getCustomerObj($dataService, $organizations)
    {
        $customerName = $organizations->name;
        $customerArray = $dataService->Query("select * from Customer where DisplayName='" . $customerName . "'");
        $error = $dataService->getLastError();
        if ($error) {
            var_dump($error);
            exit;
        } else {
            if (is_array($customerArray) && sizeof($customerArray) > 0) {
                return current($customerArray);
            }
        }



        $splitName = explode(' ', $organizations->user->name, 2); // Restricts it to only 2 values, for names like Billy Bob Jones
        $customerData = [
            "BillAddr" => [
            "Line1"=>  $organizations->address,
            "City"=>  $organizations->city,
            "Country"=>  "CA",
            "CountrySubDivisionCode"=>  $organizations->state->code,
            "PostalCode"=>  $organizations->zipcode
        ],
        "Notes" =>  "",
        "Title"=>  "",
        "GivenName"=>  $splitName[0],
        "MiddleName"=>  '',
        "FamilyName"=>  $splitName[1],
        "Suffix"=>  "",
        "FullyQualifiedName"=>  $organizations->name,
        "CompanyName"=>  $organizations->name,
        "DisplayName"=>  $organizations->name,
        "PrimaryPhone"=>  [
            "FreeFormNumber"=>  $organizations->phone
        ],
        "PrimaryEmailAddr"=>  [
            "Address" => $organizations->user->email
        ]
       ];

        // Create Customer
        $customerRequestObj = Customer::create($customerData);
        $customerResponseObj = $dataService->Add($customerRequestObj);
        $error = $dataService->getLastError();
        if ($error) {
            var_dump($error);
            exit;
        } else {
            echo "Created Customer with Id={$customerResponseObj->Id}.\n\n";
            return $customerResponseObj;
        }
    }
    private function getItemObj($dataService, $itemName)
    {
        $itemArray = $dataService->Query("select * from Item WHERE Name='" . $itemName . "'");
        $error = $dataService->getLastError();
        if ($error) {
            var_dump($error);
            exit;
        } else {
            if (is_array($itemArray) && sizeof($itemArray) > 0) {
                return current($itemArray);
            }
        }
    }
   
    private function getDataService()
    {
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => env('QB_CLIENT_ID'),
            'ClientSecret' =>  env('QB_CLIENT_SECRET'),
            'RedirectURI' => env('QB_OAUTH_REDIRECT_URI'),
            'scope' => env('QB_OAUTH_SCOPE'),
            'baseUrl' => env('QB_ENV')
        ));
        return $dataService;
    }
    private function parseAuthRedirectUrl($url)
    {
        parse_str($url, $qsArray);
        return array(
            'code' => $qsArray['code'],
            'realmId' => $qsArray['realmId']
        );
    }
}
