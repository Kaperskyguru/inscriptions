<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

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

        $this->url = urlencode($baseurl);
    }

    public function create_customer(Request $request)
    {
 
        // ************** TEST DATA *****************
        $request['city'] = 'Port Harcourt';
        $request['country'] = 'Nigeria';
        $request['display_name'] = 'Solomon Eseme';
        $request['title'] = 'Mr';
        $request['given_name'] = 'Solomon Eseme';
        $request['middle_name'] = 'Eseme';
        $request['family_name'] = 'Adaada';
        $request['suffix'] = 'Jr';
        $request['email'] = 'kaperskyguru@gmail.com';
        $request['company_name'] = 'Test Company';
        $request['zip_code'] = '500272';
        $request['phone_number'] = '+2348145655380';
        
        // ************** TEST DATA *****************


        // Run validation
        $request->validate([
            'title' => 'string',
            'city' => 'string',
            'country' => 'string',
            'display_name' => 'required|string',
            'given_name' => 'required|string',
            'email' => 'required|email',
            'company_name' => 'string',
            'zip_code' => 'string',
            'phone_number' => 'string'
        ]);
        

       

        $address = [];
        $address['City'] = $request->city;
        $address['Country'] = $request->country;
        $address['PostalCode'] = $request->zip_code;

        // $data = [];
        $data['FullyQualifiedName'] = $request->given_name;
        $data['DisplayName'] = $request->display_name;
        $data['Title'] = $request->title;
        $data['GivenName'] = $request->given_name;
        $data['PrimaryEmailAddr']['Address'] = $request->email;
        $data['PrimaryPhone']['FreeFormNumber'] = $request->phone_number;
        $data['CompanyName'] = $request->company_name;
        $data['BillAddr'] = $address;

        return response()->json($data); // Return Fake Json

        $this->url .= '/customer?minorversion=54';
        $response = Http::post($this->url, $data);
        return $response->json();
    }

    public function create_invoice(Request $request)
    {
        


        // ************** TEST DATA *****************

        $line = new \stdClass;
        $line->amount = 60;
        $line->entries = 4;
        $line->unit_price = 60;
        $line->description = "this is a fake product";
        $line->name = "Test name";
        $ar = array($line, $line, $line);

        $request['lines'] = $ar;
        $request['customer'] = 'Test Customer';

        // ************** TEST DATA *****************


        // Query all Items into array
        

        // Run validation
        $request->validate([
            'customer' => 'required|string',
            'lines' => 'array',
        ]);
        

        $this->url .= '/customer?minorversion=54';
        $lines = [];
        foreach ($request->lines as $line) {
            $lineItem = [];
            $lineItem['DetailType'] = 'SalesItemLineDetail';
            $lineItem['Amount'] = $line->amount;
            $lineItem['SalesItemLineDetail']['Qty'] = $line->entries;
            $lineItem['SalesItemLineDetail']['UnitPrice'] = $line->unit_price;
            $lineItem['SalesItemLineDetail']['Description'] = $line->description;
            $lineItem['SalesItemLineDetail']['TaxCodeRef']['value'] = "NON";

            $item = [];
            $item['name'] = $line->name;
            $item['value'] = $line->name; //$this->itemLookupArray($line->name);
            $lineItem['SalesItemLineDetail']['ItemRef'] = $item;
            \array_push($lines, $lineItem);
        }

        $data['CustomerRef']['value'] = $request->customer; //$this->customerLookupArray($request->customer);
        $data['Line'] = $lines;

        return response()->json($data); // Return Fake Json

        $this->url .= '/invoice?minorversion=54';
        $response = Http::post($this->url, $data);
        return $response->json();
    }
}
