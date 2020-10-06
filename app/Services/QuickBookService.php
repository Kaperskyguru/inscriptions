<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class QuickBookService
{
    private static $instance = null;
    private $url;

    private function __construct()
    {
        $this->buildURL();
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function buildURL($sandbox = true)
    {
        $baseurl = env('QB_BASE_URL');
        if ($sandbox) {
            $baseurl = env('QB_SANDBOX_BASE_URL');
        }

        $realmID = env('QB_REALMID');
        $baseurl.= "/v3/company/$realmID";

        $this->url = $baseurl; //urlencode($baseurl);
    }

    public function create_customer(Request $request)
    {
 
        // ************** TEST DATA *****************
        $request['city'] = 'Port Harcourt';
        $request['address'] = '22 wokenkoro';
        $request['name'] = 'Solomon Eseme';
        $request['email'] = 'kaperskyguru@gmail.com';
        $request['company_name'] = 'Test Company';
        $request['zip_code'] = '500272';
        $request['phone_number'] = '+2348145655380';
        
        // ************** TEST DATA *****************


        // Run validation
        $request->validate([
            'city' => 'string',
            'address' => 'string',
            'name' => 'required|string',
            'email' => 'required|email',
            'company_name' => 'string',
            'zip_code' => 'string',
            'phone_number' => 'string'
        ]);
        

       
        $names = explode(' ', $request->name);

        $address = [];
        $address['City'] = $request->city;
        $address['Line1'] = $request->address;
        $address['PostalCode'] = $request->zip_code;

        $data['FullyQualifiedName'] = $request->name;
        $data['FamilyName'] = $names[1];
        $data['GivenName'] = $names[0];
        $data['PrimaryEmailAddr']['Address'] = $request->email;
        $data['PrimaryPhone']['FreeFormNumber'] = $request->phone_number;
        $data['CompanyName'] = $request->company_name;
        $data['BillAddr'] = $address;

        // return response()->json($data); // Return Fake Json

        $url = $this->url . '/customer?minorversion=54';
        $response = Http::withToken(env('QB_TOKEN'))->post($url, $data);
        dd($response->json());
        return $response->json();
    }

    public function findOrCreateCustomer()
    {
        dd($this->findCustomer('Solomon Eseme'));
    }

    public function findCustomer($name)
    {
        $url = $this->url . "/query/query?query=SELECT*from Customer where GiveName=$name&minorversion=54?minorversion=54";
        $response = Http::withToken(env('QB_TOKEN'))->get($url);
        return $response;
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

        $this->findOrCreateCustomer();
        // Query all Items into array
        
        $validatedData = $v->validated();
        $lines = [];
        foreach ($validatedData['invoices']['data'] as $line) {
            $lineItem = [];
            $routine = $line['routines_count'];
            $lineItem['DetailType'] = 'SalesItemLineDetail';
            $lineItem['Amount'] = $line['total'];
            $lineItem['Description'] = $line['name'] . " ( Routine: $routine )";
            $lineItem['SalesItemLineDetail']['Qty'] = $line['entries'];
            $lineItem['SalesItemLineDetail']['UnitPrice'] = $line['formatted_rebate_price'];
            $lineItem['SalesItemLineDetail']['TaxCodeRef']['value'] = "TAX";

            $item = [];
            // $this->create_item($line['name']);

            $item['name'] = $line['name'];
            $item['value'] =  '2'; //$line->name; //$this->itemLookupArray($line->name);
            $lineItem['SalesItemLineDetail']['ItemRef'] = $item;
            \array_push($lines, $lineItem);
        }
        

        $data['CustomerRef']['value'] = '58'; //$this->customerLookupArray($request->customer);
        $data['Line'] = $lines;

        // dd($data);

        $url = $this->url . '/invoice?minorversion=54';
        $response = Http::withToken(env('QB_TOKEN'))->post($url, $data);
        dd($response);
        return $response->json();
    }

    public function create_item($item_name)
    {
        $data = [];
        $data['Name'] = $item_name;
        $data['Type'] = 'Service';
        $data['IncomeAccountRef']['name'] = 'Service';
        $url = $this->url . '/item?minorversion=54';
        $response = Http::withToken(env('QB_TOKEN'))->post($url, $data);
        dd($response->json());
        return $response->json();
    }
}
