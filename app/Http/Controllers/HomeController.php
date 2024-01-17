<?php

namespace App\Http\Controllers;

use App\Models\ServicePage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\City;
use App\Models\Order;
use App\Models\Country;
use App\Models\Package;
use App\Models\Warehouse;
use App\Models\SiteSetting;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{

    public $token = 'm99PyQIqoq5GmAKjs1wOTAbhQ0ozkc0s';
    public $cookie = 'PHPSESSID=3rf796ooctiic30gq0e54bpg45';
    public $customer_id = '12339140';
    public $integration_id = '59690';



    public $service_list = [

        //use only fedex and dhl.
        //use logo as well.
        [
            "service_id" => 0,
            "carrierCode" => "dhl",
            "carrierLabel" => "dhl",
            "serviceLabel" => "DHL Intl Express",
            "serviceCode" => "dhl_express_worldwide",
            'packageTypeCode' => 'dhl_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/dhl-logo.png"
        ],
        [
            "service_id" => 1,
            "carrierCode" => "fedex",
            "carrierLabel" => "fedex",
            "serviceLabel" => "FedEx International Economy®",
            "serviceCode" => "fedex_international_economy",
            'packageTypeCode' => 'fedex_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/fedex-logo.png"
        ],
        [
            "service_id" => 2,
            "carrierCode" => "fedex",
            "carrierLabel" => "fedex",
            "serviceLabel" => "FedEx International Ground®",
            "serviceCode" => "fedex_ground_canada",
            'packageTypeCode' => 'fedex_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/fedex-logo.png"
        ],
        [
            "service_id" => 3,
            "carrierCode" => "fedex",
            "carrierLabel" => "fedex",
            "serviceLabel" => "FedEx Standard Overnight®",
            "serviceCode" => "fedex_standard_overnight",
            'packageTypeCode' => 'fedex_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/fedex-logo.png"
        ],
        [
            "service_id" => 4,
            "carrierCode" => "fedex",
            "carrierLabel" => "fedex",
            "serviceLabel" => "FedEx 2Day®",
            "serviceCode" => "fedex_two_day",
            'packageTypeCode' => 'fedex_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/fedex-logo.png"
        ],
        [
            "service_id" => 5,
            "carrierCode" => "fedex",
            "carrierLabel" => "fedex",
            "serviceLabel" => "FedEx Express Saver®",
            "serviceCode" => "fedex_express_saver",
            'packageTypeCode' => 'fedex_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/fedex-logo.png"
        ],
        [
            "service_id" => 6,
            "carrierCode" => "fedex",
            "carrierLabel" => "fedex",
            "serviceLabel" => "FedEx Ground®",
            "serviceCode" => "fedex_ground",
            'packageTypeCode' => 'fedex_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/fedex-logo.png"
        ],
        [
            "service_id" => 7,
            "carrierCode" => "fedex",
            "carrierLabel" => "fedex",
            "serviceLabel" => "FedEx Home Delivery®",
            "serviceCode" => "fedex_ground_home_delivery",
            'packageTypeCode' => 'fedex_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/fedex-logo.png"
        ],
        [
            "service_id" => 8,
            "carrierCode" => "fedex",
            "carrierLabel" => null,
            "serviceLabel" => "FedEx International Priority®",
            "serviceCode" => "fedex_international_priority",
            'packageTypeCode' => 'fedex_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/fedex-logo.png"
        ],
        [
            "service_id" => 9,
            "carrierCode" => "fedex",
            "carrierLabel" => null,
            "serviceLabel" => "FedEx International Priority Connect Plus",
            "serviceCode" => "fedex_international_connect_plus",
            'packageTypeCode' => 'fedex_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/fedex-logo.png"
        ],
        [
            "service_id" => 10,
            "carrierCode" => "ups",
            "carrierLabel" => "ups",
            "serviceLabel" => "UPS® Standard",
            "serviceCode" => "ups_standard",
            'packageTypeCode' => 'ups_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/ups-logo.png"
        ],
        [
            "service_id" => 11,
            "carrierCode" => "ups",
            "carrierLabel" => "ups",
            "serviceLabel" => "UPS Worldwide Express®",
            "serviceCode" => "ups_worldwide_express",
            'packageTypeCode' => 'ups_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/ups-logo.png"
        ],
        [
            "service_id" => 12,
            "carrierCode" => "ups",
            "carrierLabel" => "ups",
            "serviceLabel" => "UPS Worldwide Express Plus®",
            "serviceCode" => "ups_express_plus",
            'packageTypeCode' => 'ups_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/ups-logo.png"
        ],
        [
            "service_id" => 13,
            "carrierCode" => "ups",
            "carrierLabel" => "ups",
            "serviceLabel" => "UPS Worldwide Saver®",
            "serviceCode" => "ups_worldwide_saver",
            'packageTypeCode' => 'ups_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/ups-logo.png"
        ],
        [
            "service_id" => 14,
            "carrierCode" => "ups",
            "carrierLabel" => "ups",
            "serviceLabel" => "UPS Next Day Air®",
            "serviceCode" => "ups_next_day_air",
            'packageTypeCode' => 'ups_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/ups-logo.png"
        ],
        [
            "service_id" => 15,
            "carrierCode" => "ups",
            "carrierLabel" => "ups",
            "serviceLabel" => "UPS 2nd Day Air®",
            "serviceCode" => "ups_second_day_air",
            'packageTypeCode' => 'ups_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/ups-logo.png"
        ],
        [
            "service_id" => 16,
            "carrierCode" => "ups",
            "carrierLabel" => "ups",
            "serviceLabel" => "UPS® Ground",
            "serviceCode" => "ups_ground",
            'packageTypeCode' => 'ups_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/ups-logo.png"
        ],
        [
            "service_id" => 17,
            "carrierCode" => "ups",
            "carrierLabel" => "ups",
            "serviceLabel" => "UPS Next Day Air Saver®",
            "serviceCode" => "ups_next_day_air_saver",
            'packageTypeCode' => 'ups_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/ups-logo.png"
        ],
        [
            "service_id" => 18,
            "carrierCode" => "ups",
            "carrierLabel" => "ups",
            "serviceLabel" => "UPS 2nd Day Air A.M.®",
            "serviceCode" => "ups_second_day_air_am",
            'packageTypeCode' => 'ups_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/ups-logo.png"
        ],
        [
            "service_id" => 19,
            "carrierCode" => "ups",
            "carrierLabel" => "ups",
            "serviceLabel" => "UPS 3 Day Select®",
            "serviceCode" => "ups_three_day_select",
            'packageTypeCode' => 'ups_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/ups-logo.png"
        ],
        [
            "service_id" => 20,
            "carrierCode" => "ups",
            "carrierLabel" => "ups",
            "serviceLabel" => "UPS Worldwide Expedited®",
            "serviceCode" => "ups_worldwide_expedited",
            'packageTypeCode' => 'ups_custom_package',
            "currency" => "USD",
            "totalAmount" => 0,
            "baseAmount" => 0,
            "isReady" => false,
            "logo" => "/partner-imgs/ups-logo.png"
        ]
    ];

    public function checkAuth()
    {
        if (Auth::check()) {
            if (Auth::user()->type != 'admin') {
                return response()->json([
                    'init' => true,
                ]);
            } else {
                return response()->json([
                    'init' => false,
                ]);
            }
        } else {
            return response()->json([
                'init' => true,
            ]);
        }
    }

    public function dashboard(Request $request)
    {
        $status = $request->has('status') ? $request->status : 'packages';

        $query = Package::with('customer', 'warehouse', 'child_packages');

        if ($status == 'rejected') {
            $query->where('status', 'rejected');
        } else {
            $query->where('status', '<>', 'rejected');
        }

        if (Auth::user()->type == 'customer') {
            $query->where('customer_id', Auth::user()->id);
        }

        if (!empty($request->suite_no)) {
            $suite_no = intval($request->suite_no) - 4000;
            $query->whereHas('customer', function ($query) use ($suite_no) {
                $query->where('id', $suite_no);
            });
        }

        $packages = $query->orderBy('id', 'desc')->paginate(25);

        return Inertia::render('Dashboard', [
            'pkgs' => $packages,
            'filter' => [
                'status' => $status
            ]
        ]);
    }

    public function pricingTable()
    {
        $services = ServicePage::all();

        return Inertia::render('PricingTable', ['services' => $services]);
    }

    public function pricing()
    {

        $countries = Country::all(['id', 'nicename as name', 'iso'])->toArray();

        $warehouses = Warehouse::all()->toArray();

        return Inertia::render('Pricing', [
            'countries' => $countries,
            'warehouses' => $warehouses,
            'services' => $this->service_list
        ]);
    }

    public function getServicesList()
    {
        return response()->json(['services' => $this->service_list]);
    }


    public function shopping()
    {


        $user = Auth::user();

        $profile_complete = TRUE;
        //check if profile is complete
        if (
            empty($user->first_name) ||
            empty($user->last_name) ||
            empty($user->address1) ||
            empty($user->country) ||
            empty($user->state) ||
            empty($user->city) ||
            empty($user->postal_code) ||
            empty($user->phone_no) ||
            empty($user->email)
        ) {
            $profile_complete = FALSE;
        }

        $cities_from = City::where('country_code_2', 'US')->get()->toArray();
        $cities_to = City::where('country_code_2', '!=', 'US')->get()->toArray();


        return Inertia::render('Shopping', [
            'cities_from' => $cities_from,
            'cities_to' => $cities_to,
            'profile_complete' => $profile_complete
        ]);
    }

    public function getQuote(Request $request)
    {
        $service = $request->get('service');

        $service = json_decode($service);
        // dd($service);

        $rules = [
            'weight' => ['required', 'gt:0'],
            'length' => ['required', 'gt:0'],
            'width' => ['required', 'gt:0'],
            'height' => ['required', 'gt:0'],
        ];

        $validator = Validator::make($request->all(), $rules, $message = []);

        if ($validator->fails()) {
            return response()->json([
                'status' => TRUE,
                'service' => [],
                'errors' => $validator->errors()->all(),
            ]);
        }


        $markup = SiteSetting::getByName('markup');

        $ship_from = $request->input('ship_from');
        $ship_to = $request->input('ship_to');
        $weight = $request->input('weight');
        $units = $request->input('weight_unit');
        $length = $request->input('length');
        $width = $request->input('width');
        $height = $request->input('height');
        $zipcode = $request->input('zipcode');
        $cityName = $request->has('city') ? $request->city : null;
        $is_residential = $request->input('is_residential') == 1 ? true : false;


        //$declared_value = $request->input('declared_value');

        $declared_value = '10.0';

        $warehouse = Warehouse::where('id', $ship_from)->first();

        $country = Country::where('id', $ship_to)->first();

        $units = explode('_', $units);

        $weight_unit = isset($units[0]) ? $units[0] : 'lb';
        $dimention_unit = isset($units[1]) ? $units[1] : 'in';

        $headers = [
            'cache-control' => 'no-cache',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];

        $errors = [];

        $sender = [
            "country" =>  'US',
            "zip" => '92804',
        ];

        $receiver = [
            "country" => $country->iso,
            "zip" => empty($zipcode) ? '40050' : $zipcode,
        ];

        if ($cityName != null) {
            $receiver['city'] = $cityName;
        } elseif ($country->iso == 'PK') {
            $receiver['city'] = "Lahore";
        }

        $pieces = [
            0 => [
                "weight" => $weight,
                "length" => $length,
                "width" => $width,
                "height" => $height,
                "insuranceAmount" => null,
                "declaredValue" => $declared_value
            ]
        ];

        $post_params = [
            "carrierCode" => $service->carrierCode,
            "serviceCode" =>  $service->serviceCode,
            "packageTypeCode" => $service->packageTypeCode,
            "sender" => $sender,
            "receiver" => $receiver,
            "residential" => $is_residential,
            "signatureOptionCode" => null,
            "contentDescription" => "stuff and things",
            "weightUnit" => $weight_unit,
            "dimUnit" => $dimention_unit,
            "currency" => "USD",
            "customsCurrency" => "USD",
            "pieces"  => $pieces,
            "billing" => [
                "party" => "sender"
            ]
        ];
        $service_rate = [];
        try {

            $client = new Client();

            $request = $client->post('https://xpsshipper.com/restapi/v1/customers/' . $this->customer_id . '/quote', [
                'headers' => $headers,
                'body' => json_encode($post_params),
                'http_errors' => true,
            ]);

            $response = $request ? $request->getBody()->getContents() : null;
            \Log::info($post_params);
            \Log::info($response);
            $response = json_decode($response);

            $markup_amount = $response->totalAmount * ((int)$markup / 100);

            $total = $response->totalAmount + $markup_amount;

            $total = number_format($total, 2);

            $service_rate = [
                "service_id" => $service->service_id,
                "carrierCode" => $service->carrierCode,
                'serviceLabel' => $service->serviceLabel,
                'serviceCode' => $service->serviceCode,
                "packageTypeCode" => $service->packageTypeCode,
                'currency' => $response->currency,
                'totalAmount' => $total,
                'baseAmount' => $response->baseAmount,
                'isReady' => true,
                'logo' => $service->logo,
                'markup_fee' => $markup_amount,
            ];
        } catch (\Exception $ex) {

            $ex_message = $ex->getMessage();

            $pos = strpos($ex_message, '{"error":"');

            $pos1 = strpos($ex_message, '"errorCategory"');
            $length = $pos1 - ($pos + 12);

            $message = substr($ex_message, $pos + 10, $length);

            $errors[] = [
                'label' => $service->serviceLabel,
                'serviceCode' => $service->serviceCode,
                'message' => $message,
                'details' => $ex_message,
            ];
        }

        return response()->json([
            'status' => TRUE,
            'service' => $service_rate,
            'errors' => $errors,
        ]);
    }

    /**
     * Not used now, using getQuote for single service at atime.
     */

    public function getQuotes(Request $request)
    {

        $markup = SiteSetting::getByName('markup');
        $ship_from = $request->input('ship_from');
        $ship_to = $request->input('ship_to');
        $weight = $request->input('weight');
        $units = $request->input('weight_unit', 'lb_in');
        $length = $request->input('length');
        $width = $request->input('width');
        $height = $request->input('height');
        $zipcode = $request->input('zipcode');
        //$declared_value = $request->input('declared_value');
        $declared_value = '1.0';
        $warehouse = Warehouse::where('id', $ship_from)->first();
        $country = Country::where('id', $ship_to)->first();
        $units = explode('_', $units);
        $weight_unit = isset($units[0]) ? $units[0] : 'lb';
        $dimention_unit = isset($units[1]) ? $units[1] : 'in';
        $headers = [
            'cache-control' => 'no-cache',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];
        $client = new Client();

        $request = $client->get('https://xpsshipper.com/restapi/v1/customers/' . $this->customer_id . '/services', [
            'headers' => $headers,
            'http_errors' => true,
        ]);

        $response = $request ? $request->getBody()->getContents() : null;
        $response = json_decode($response);

        echo '<pre>';
        print_r($response->services);
        exit;

        $service_rates = [];
        $errors = [];
        $sender = [
            "country" =>  'US',
            "zip" =>  $warehouse->zip
        ];
        $receiver = [
            "country" => $country->iso,
            "zip" => $zipcode,
        ];
        $pieces = [
            0 => [
                "weight" => $weight,
                "length" => $length,
                "width" => $width,
                "height" => $height,
                "insuranceAmount" => null,
                "declaredValue" => $declared_value
            ]
        ];
        foreach ($response->services as $service) {
            if (!empty($service->carrierCode) && strpos($service->serviceCode, 'international') !== false) {

                $post_params = [
                    "carrierCode" => $service->carrierCode,
                    "serviceCode" =>  $service->serviceCode,
                    "packageTypeCode" => $this->getPackageTypeCode($service),
                    "sender" => $sender,
                    "receiver" => $receiver,
                    "residential" => true,
                    "signatureOptionCode" => "DIRECT",
                    "contentDescription" => "stuff and things",
                    "weightUnit" => $weight_unit,
                    "dimUnit" => $dimention_unit,
                    "currency" => "USD",
                    "customsCurrency" => "USD",
                    "pieces"  => $pieces,
                    "billing" => [
                        "party" => "receiver"
                    ]
                ];
                try {
                    $request = $client->post('https://xpsshipper.com/restapi/v1/customers/' . $this->customer_id . '/quote', [
                        'headers' => $headers,
                        'body' => json_encode($post_params),
                        'http_errors' => true,
                    ]);

                    $response = $request ? $request->getBody()->getContents() : null;
                    $response = json_decode($response);
                    $markup_amount = $response->totalAmount * ((int)$markup / 100);
                    $total = $response->totalAmount + $markup_amount;
                    $total = number_format($total, 2);
                    $service_rates[] = [
                        'service_id' => $service->service_id,
                        'label' => $service->serviceLabel,
                        'serviceCode' => $service->serviceCode,
                        'currency' => $response->currency,
                        //'totalAmount' => $response->totalAmount,
                        'totalAmount' => $total,
                        'baseAmount' => $response->baseAmount,
                    ];
                } catch (\Exception $ex) {

                    $ex_message = $ex->getMessage();
                    $pos = strpos($ex_message, '{"error":"');

                    $pos1 = strpos($ex_message, '"errorCategory"');
                    $length = $pos1 - ($pos + 12);

                    $message = substr($ex_message, $pos + 10, $length);

                    $errors[] = [
                        'label' => $service->serviceLabel,
                        'serviceCode' => $service->serviceCode,
                        'message' => $message,
                    ];
                }
            }
        }

        return response()->json([
            'status' => TRUE,
            'services' => $service_rates,
            'errors' => $errors,
        ]);
    }


    public function getQuoteByOrders(Request $request)
    {
        $service = $request->get('service');

        $service = json_decode($service);


        $piecesStrings = $request->pieces;
        $pieces = [];
        foreach ($piecesStrings as $strValue) {
            $pieces[] = json_decode($strValue, true);
        }



        $markup = SiteSetting::getByName('markup');

        $ship_from = $request->input('ship_from');
        $ship_to = $request->input('ship_to');
        $units = $request->input('weight_unit');
        $length = $request->input('length');
        $zipcode = $request->input('zipcode');
        $cityName = $request->has('city') ? $request->city : null;
        $is_residential = $request->input('is_residential') == 1 ? true : false;


        //$declared_value = $request->input('declared_value');

        $declared_value = '10.0';

        $warehouse = Warehouse::where('id', $ship_from)->first();

        $country = Country::where('id', $ship_to)->first();

        $units = explode('_', $units);

        $weight_unit = isset($units[0]) ? $units[0] : 'lb';
        $dimention_unit = isset($units[1]) ? $units[1] : 'in';

        $headers = [
            'cache-control' => 'no-cache',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];

        $errors = [];

        $sender = [
            "country" =>  'US',
            "zip" => '92804',
        ];

        $receiver = [
            "country" => $country->iso,
            "zip" => empty($zipcode) ? '40050' : $zipcode,
        ];

        if ($cityName != null) {
            $receiver['city'] = $cityName;
        } elseif ($country->iso == 'PK') {
            $receiver['city'] = "Lahore";
        }


        $post_params = [
            "carrierCode" => $service->carrierCode,
            "serviceCode" =>  $service->serviceCode,
            "packageTypeCode" => $service->packageTypeCode,
            "sender" => $sender,
            "receiver" => $receiver,
            "residential" => $is_residential,
            "signatureOptionCode" => null,
            "contentDescription" => "stuff and things",
            "weightUnit" => $weight_unit,
            "dimUnit" => $dimention_unit,
            "currency" => "USD",
            "customsCurrency" => "USD",
            "pieces"  => $pieces,
            "billing" => [
                "party" => "sender"
            ]
        ];
        $service_rate = [];
        try {

            $client = new Client();

            $request = $client->post('https://xpsshipper.com/restapi/v1/customers/' . $this->customer_id . '/quote', [
                'headers' => $headers,
                'body' => json_encode($post_params),
                'http_errors' => true,
            ]);

            $response = $request ? $request->getBody()->getContents() : null;
            \Log::info($post_params);
            \Log::info($response);
            $response = json_decode($response);

            $markup_amount = $response->totalAmount * ((int)$markup / 100);

            $total = $response->totalAmount + $markup_amount;

            $total = number_format($total, 2);

            $service_rate = [
                "service_id" => $service->service_id,
                "carrierCode" => $service->carrierCode,
                'serviceLabel' => $service->serviceLabel,
                'serviceCode' => $service->serviceCode,
                "packageTypeCode" => $service->packageTypeCode,
                'currency' => $response->currency,
                'totalAmount' => $total,
                'baseAmount' => $response->baseAmount,
                'isReady' => true,
                'logo' => $service->logo,
                'markup_fee' => $markup_amount,
            ];
        } catch (\Exception $ex) {

            $ex_message = $ex->getMessage();

            $pos = strpos($ex_message, '{"error":"');

            $pos1 = strpos($ex_message, '"errorCategory"');
            $length = $pos1 - ($pos + 12);

            $message = substr($ex_message, $pos + 10, $length);

            $errors[] = [
                'label' => $service->serviceLabel,
                'serviceCode' => $service->serviceCode,
                'message' => $message,
                'details' => $ex_message,
            ];
        }

        return response()->json([
            'status' => TRUE,
            'service' => $service_rate,
            'errors' => $errors,
        ]);
    }

    public function putTestOrder()
    {
        $headers = [
            'cache-control' => 'no-cache',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];

        $post_params = array(
            'orderId' => 'OrderTest005',
            'orderDate' => '2022-10-14',
            'orderNumber' => 'Test Order 005',
            'fulfillmentStatus' => 'pending',
            'shippingService' => 'fedex custom package',
            'shippingTotal' => '8.24',
            'weightUnit' => 'lb',
            'dimUnit' => 'in',
            'dueByDate' => '2022-10-26',
            'orderGroup' => 'Workstation 1',
            'contentDescription' => 'Stuff and things',
            'sender' =>
            array(
                'name' => 'Habibur Haseeb',
                'company' => 'Aman & Baida Enterprise',
                'address1' => '3578 West Savanna St',
                'address2' => '',
                'city' => 'Anaheim',
                'state' => 'CA',
                'zip' => '92804',
                'country' => 'US',
                'phone' => '2097517988',
                'email' => 'habib362@gmail.com',
            ),
            'receiver' =>
            array(
                'name' => 'Samia Khan',
                'company' => '',
                'address1' => 'Multan Road',
                'address2' => 'Dummy House',
                'city' => 'Lahore',
                'state' => '',
                'zip' => '84115',
                'country' => 'PK',
                'phone' => '+923004556435',
                'email' => 'samia@yopmail.coom',
            ),
            'items' =>
            array(
                0 =>
                array(
                    'productId' => '856673',
                    'sku' => 'ade3-fe21-bb9a',
                    'title' => 'Socks',
                    'price' => '3.99',
                    'quantity' => 2,
                    'weight' => '0.5',
                    'imgUrl' => 'http://sockstore.egg/img/856673',
                    'htsNumber' => '555555',
                    'countryOfOrigin' => 'US',
                    'lineId' => '1',
                ),
                1 =>
                array(
                    'productId' => '856673s',
                    'sku' => 'ade3-fe21-bb9aa',
                    'title' => 'Socsks',
                    'price' => '3.99',
                    'quantity' => 2,
                    'weight' => '1.0',
                    'imgUrl' => 'http://sockstore.egg/img/856673',
                    'htsNumber' => '555555',
                    'countryOfOrigin' => 'US',
                    'lineId' => '1',
                ),
            ),
            'packages' =>
            array(
                0 =>
                array(
                    'weight' => '0.5',
                    'length' => '6',
                    'width' => '5',
                    'height' => '2.5',
                    'insuranceAmount' => NULL,
                    'declaredValue' => NULL,
                ),
                1 =>
                array(
                    'weight' => '1.0',
                    'length' => '8',
                    'width' => '6',
                    'height' => '4',
                    'insuranceAmount' => NULL,
                    'declaredValue' => NULL,
                ),
                2 =>
                array(
                    'weight' => '3.5',
                    'length' => '10',
                    'width' => '20',
                    'height' => '15',
                    'insuranceAmount' => NULL,
                    'declaredValue' => NULL,
                ),
            ),
        );


        try {

            $client = new Client();

            $request = $client->put('https://xpsshipper.com/restapi/v1/customers/' . $this->customer_id . '/integrations/' . $this->integration_id . '/orders/' . $post_params['orderId'], [
                'headers' => $headers,
                'body' => json_encode($post_params),
                'http_errors' => true,
            ]);

            $response = $request ? $request->getBody()->getContents() : null;

            \Log::info($response);

            return $response;
        } catch (\Exception $ex) {

            $ex_message = $ex->getMessage();

            return $ex_message;
        }
    }


    private function getPackageTypeCode($service)
    {

        $type = "";

        switch ($service->carrierCode) {
            case "dhl":
                $type =  "dhl_custom_package";
                break;
            case "fedex":
                $type = "fedex_custom_package";
                break;
            case "usps":
                $type = "usps_custom_package";
                break;
        }

        return $type;
    }

    public function notifications(Request $request)
    {

        $data = [];
        $currentPage = $request->current_page ?? 1;
        $take = 50 * $currentPage;

        $user = \auth()->user();
        $notifications = \auth()->user()->notifications()->select(DB::raw('DATE(created_at) as date'));
        $totalPage = ceil($notifications->count() / 50);


        $notifications = $notifications->take($take)->pluck('date')->toArray();


        $notifications = array_unique($notifications);

        foreach ($notifications as $date) {
            $notifies = $user->notifications()->whereDate('created_at', $date)->orderby('created_at', 'desc')->get();
            $notifications = [];

            foreach ($notifies as $notification) {
                $notifications[] = [
                    'id' => $notification->id,
                    'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($notification->created_at)))->diffForHumans(),
                    'read_at' => $notification->read_at != NULL ? date('d-m-Y h:m a', strtotime($notification->read_at)) : NULL,
                    'content' => $notification->data['message'] ?? null
                ];
            }

            $data[] = [
                'date' => ($date == Carbon::now()->format('Y-m-d')) ? "Today" : date('F  j, Y', strtotime($date)),
                'notifications' => $notifications
            ];
        }

        $notificationData = collect($data);

        if ($request->has('current_page')) {
            return response()->json([
                'notifications' => $notificationData,
                'totalPage' => $totalPage ?? 1,
                'currentPage' => $currentPage ?? 1
            ]);
        }

        return Inertia::render('Notifications', [
            'notifications' => $notificationData,
            'totalPage' => $totalPage ?? 1,
            'currentPageProp' => $currentPage ?? 1
        ]);
    }

    public function markAllRead()
    {
        \auth()->user()->unreadNotifications->markAsRead();
        return redirect('notifications');
    }


    public function markRead(Request $request)
    {

        $id = $request->input('notification_id');

        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'status' => 1,
            'message' => "Read successfully",
        ]);
    }

    public function getMailingAddress()
    {
        $response['warehouses'] = Warehouse::where('id',2)->get();

        return \response()->json([
            'status' => true,
            'data' => $response['warehouses'],
        ]);
    }

    public function announcement()
    {
        $customers = User::orderBy('id', 'asc')->where('type', 'customer')->get();
        Notification::send($customers, new AnnouncementNotification());
    }

    public function decodePdf()
    {
        $base64EncodedPDF = "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";

        // Decode the Base64-encoded PDF
        // $decodedPDF = base64_decode($base64EncodedPDF);

        Storage::disk('public')->put('file001.png',base64_decode($base64EncodedPDF));

    }
}
