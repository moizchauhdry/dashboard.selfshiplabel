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

        $packages = $query->orderBy('id', 'desc')->paginate(10);

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
        $base64EncodedPDF = "JVBERi0xLjQKJaqrrK0KMSAwIG9iago8PAovUHJvZHVjZXIgKEFwYWNoZSBGT1AgVmVyc2lvbiBTVk46IFBERiBUcmFuc2NvZGVyIGZvciBCYXRpaykKL0NyZWF0aW9uRGF0ZSAoRDoyMDI0MDUwNjEyMTgzN1opCj4+CmVuZG9iagoyIDAgb2JqCjw8CiAgL04gMwogIC9MZW5ndGggMyAwIFIKICAvRmlsdGVyIC9GbGF0ZURlY29kZQo+PgpzdHJlYW0KeJztmWdQVFkWgO97nRMN3U2ToclJooQGJOckQbKoQHeTaaHJQVFkcARGEBFJiiCigAOODkFGURHFgCgooKJOI4OAMg6OIioqS+OP2a35sbVVW/tn+/x476tzT71z7qtb9b6qB4AMMZ6VkAzrA5DATeH5OtsxgoJDGJgHAAtIgAgoAB3OSk609fb2AKshqAV/i/djABLc7+sI1nPPkaKLPugYHptxefx2onnL3+v/JYjsBC4bAIi2yrFsTjJrlXetcjQ7gS3Izwo4PSUxBQDYe5VpvNUBV5kt4IhvnCHgqG9cvFbj52u/yscAwBKj1hh/WsARa0zpFjArmpcAgHT/ar0KK5G3+nxpQS/FbzOshahgP4woDpfDC0/hsBn/Ziv/efxTL1Ty6sv/rzf4H/cRnJ1v9NZy7UxA9Mq/ctvLAWC+BgBR+ldO5QgA5D0AdPb+lYs4AUBXKQCSz1ipvLRvOeTa7AAPyIAGpIA8UAYaQAcYAlNgAWyAI3ADXsAPBIOtgAWiQQLggXSQA3aDAlAESsEhUA3qQCNoBm3gLOgCF8AVcB3cBvfAKJgAfDANXoEF8B4sQxCEgUgQFZKCFCBVSBsyhJiQFeQIeUC+UDAUBkVBXCgVyoH2QEVQGVQN1UPN0E/QeegKdBMahh5Bk9Ac9Cf0CUbARJgGy8FqsB7MhG1hd9gP3gJHwUlwFpwP74cr4Qb4NNwJX4Fvw6MwH34FLyIAgoCgIxQROggmwh7hhQhBRCJ4iJ2IQkQFogHRhuhBDCDuI/iIecRHJBpJRTKQOkgLpAvSH8lCJiF3IouR1chTyE5kP/I+chK5gPyKIqFkUdooc5QrKggVhUpHFaAqUE2oDtQ11ChqGvUejUbT0epoU7QLOhgdi85GF6OPoNvRl9HD6Cn0IgaDkcJoYywxXphwTAqmAFOFOY25hBnBTGM+YAlYBawh1gkbguVi87AV2BZsL3YEO4NdxoniVHHmOC8cG5eJK8E14npwd3HTuGW8GF4db4n3w8fid+Mr8W34a/gn+LcEAkGJYEbwIcQQdhEqCWcINwiThI9EClGLaE8MJaYS9xNPEi8THxHfkkgkNZINKYSUQtpPaiZdJT0jfRChiuiKuIqwRXJFakQ6RUZEXpNxZFWyLXkrOYtcQT5HvkueF8WJqonai4aL7hStET0vOi66KEYVMxDzEksQKxZrEbspNkvBUNQojhQ2JZ9ynHKVMkVFUJWp9lQWdQ+1kXqNOk1D09RprrRYWhHtR9oQbUGcIm4kHiCeIV4jflGcT0fQ1eiu9Hh6Cf0sfYz+SUJOwlaCI7FPok1iRGJJUkbSRpIjWSjZLjkq+UmKIeUoFSd1QKpL6qk0UlpL2kc6Xfqo9DXpeRmajIUMS6ZQ5qzMY1lYVkvWVzZb9rjsoOyinLycs1yiXJXcVbl5ebq8jXysfLl8r/ycAlXBSiFGoVzhksJLhjjDlhHPqGT0MxYUZRVdFFMV6xWHFJeV1JX8lfKU2pWeKuOVmcqRyuXKfcoLKgoqnio5Kq0qj1VxqkzVaNXDqgOqS2rqaoFqe9W61GbVJdVd1bPUW9WfaJA0rDWSNBo0HmiiNZmacZpHNO9pwVrGWtFaNVp3tWFtE+0Y7SPaw+tQ68zWcdc1rBvXIerY6qTptOpM6tJ1PXTzdLt0X+up6IXoHdAb0Puqb6wfr9+oP2FAMXAzyDPoMfjTUMuQZVhj+GA9ab3T+tz13evfGGkbcYyOGj00php7Gu817jP+YmJqwjNpM5kzVTENM601HWfSmN7MYuYNM5SZnVmu2QWzj+Ym5inmZ83/sNCxiLNosZjdoL6Bs6Fxw5SlkmW4Zb0l34phFWZ1zIpvrWgdbt1g/dxG2YZt02QzY6tpG2t72va1nb4dz67Dbsne3H6H/WUHhIOzQ6HDkCPF0d+x2vGZk5JTlFOr04KzsXO282UXlIu7ywGXcVc5V5Zrs+uCm6nbDrd+d6L7Jvdq9+ceWh48jx5P2NPN86Dnk42qG7kbu7yAl6vXQa+n3ureSd6/+KB9vH1qfF74Gvjm+A5som7atqll03s/O78Svwl/Df9U/74AckBoQHPAUqBDYFkgP0gvaEfQ7WDp4Jjg7hBMSEBIU8jiZsfNhzZPhxqHFoSObVHfkrHl5lbprfFbL24jbwvfdi4MFRYY1hL2OdwrvCF8McI1ojZigWXPOsx6xbZhl7PnOJacMs5MpGVkWeRslGXUwai5aOvoiuj5GPuY6pg3sS6xdbFLcV5xJ+NW4gPj2xOwCWEJ57kUbhy3f7v89oztw4naiQWJ/CTzpENJCzx3XlMylLwluTuFtvqRHkzVSP0udTLNKq0m7UN6QPq5DLEMbsZgplbmvsyZLKesE9nIbFZ2X45izu6cyR22O+p3QjsjdvblKufm507vct51ajd+d9zuO3n6eWV57/YE7unJl8vflT/1nfN3rQUiBbyC8b0We+u+R34f8/3QvvX7qvZ9LWQX3irSL6oo+lzMKr71g8EPlT+s7I/cP1RiUnK0FF3KLR07YH3gVJlYWVbZ1EHPg53ljPLC8neHth26WWFUUXcYfzj1ML/So7K7SqWqtOpzdXT1aI1dTXutbO2+2qUj7CMjR22OttXJ1RXVfToWc+xhvXN9Z4NaQ8Vx9PG04y8aAxoHTjBPNDdJNxU1fTnJPck/5Xuqv9m0ublFtqWkFW5NbZ07HXr63o8OP3a36bTVt9Pbi86AM6lnXv4U9tPYWfezfeeY59p+Vv25toPaUdgJdWZ2LnRFd/G7g7uHz7ud7+ux6On4RfeXkxcUL9RcFL9Y0ovvze9duZR1afFy4uX5K1FXpvq29U1cDbr6oN+nf+ia+7Ub152uXx2wHbh0w/LGhZvmN8/fYt7qum1yu3PQeLDjjvGdjiGToc67pne775nd6xneMNw7Yj1y5b7D/esPXB/cHt04OjzmP/ZwPHSc/5D9cPZR/KM3j9MeL0/seoJ6UvhU9GnFM9lnDb9q/trON+FfnHSYHHy+6fnEFGvq1W/Jv32ezn9BelExozDTPGs4e2HOae7ey80vp18lvlqeL/hd7Pfa1xqvf/7D5o/BhaCF6Te8Nyt/Fr+VenvyndG7vkXvxWfvE94vLxV+kPpw6iPz48CnwE8zy+mfMZ8rv2h+6fnq/vXJSsLKitAFhC4gdAGhCwhdQOgCQhcQuoDQBYQuIHQBoQsIXUDoAkIX+D92gbX/OKuBEFyOjwPglw2Axx0AqqoBUIsEgByawslIEaxytzNY2xMzeTFR0SnrGKnJHEYkj8OJzxSs/QPXexMOCmVuZHN0cmVhbQplbmRvYmoKMyAwIG9iagoyNDcyCmVuZG9iago0IDAgb2JqClsvSUNDQmFzZWQgMiAwIFJdCmVuZG9iago1IDAgb2JqCjw8CiAgL05hbWUgL0ltMQogIC9UeXBlIC9YT2JqZWN0CiAgL0xlbmd0aCA2IDAgUgogIC9GaWx0ZXIgL0ZsYXRlRGVjb2RlCiAgL1N1YnR5cGUgL0ltYWdlCiAgL1dpZHRoIDIzMgogIC9IZWlnaHQgNTAKICAvQml0c1BlckNvbXBvbmVudCAxCiAgL0NvbG9yU3BhY2UgWy9JbmRleGVkIC9EZXZpY2VHcmF5IDEgPDAwRkY+XQo+PgpzdHJlYW0KeJz7mcylKTt78mQpkRDrzTLLfG63BKv5HOmYHvP6zKP5P0clRyVHJUclRyUHXhIAz3+kSwplbmRzdHJlYW0KZW5kb2JqCjYgMCBvYmoKNTIKZW5kb2JqCjcgMCBvYmoKPDwKICAvTiAzCiAgL0xlbmd0aCA4IDAgUgogIC9GaWx0ZXIgL0ZsYXRlRGVjb2RlCj4+CnN0cmVhbQp4nO2ZZ1BUWRaA73udEw3dTZOhyUmihAYk5yRBsqhAd5NpoclBUWRwBEYQEUmKIKKAA44OQUZREcWAKCigok4jg4AyDo4iKipL44/ZrfmxtVVb+2f7/Hjvq3NPvXPuq1v1vqoHgAwxnpWQDOsDkMBN4fk62zGCgkMYmAcAC0iACCgAHc5KTrT19vYAqyGoBX+L92MAEtzv6wjWc8+Roos+6Bgem3F5/Haiecvf6/8liOwELhsAiLbKsWxOMmuVd61yNDuBLcjPCjg9JTEFANh7lWm81QFXmS3giG+cIeCob1y8VuPna7/KxwDAEqPWGH9awBFrTOkWMCualwCAdP9qvQorkbf6fGlBL8VvM6yFqGA/jCgOl8MLT+GwGf9mK/95/FMvVPLqy/+vN/gf9xGcnW/01nLtTED0yr9y28sBYL4GAFH6V07lCADkPQB09v6VizgBQFcpAJLPWKm8tG855NrsAA/IgAakgDxQBhpABxgCU2ABbIAjcANewA8Eg62ABaJBAuCBdJADdoMCUARKwSFQDepAI2gGbeAs6AIXwBVwHdwG98AomAB8MA1egQXwHixDEISBSBAVkoIUIFVIGzKEmJAV5Ah5QL5QMBQGRUFcKBXKgfZARVAZVA3VQ83QT9B56Ap0ExqGHkGT0Bz0J/QJRsBEmAbLwWqwHsyEbWF32A/eAkfBSXAWnA/vhyvhBvg03AlfgW/DozAffgUvIgCCgKAjFBE6CCbCHuGFCEFEIniInYhCRAWiAdGG6EEMIO4j+Ih5xEckGklFMpA6SAukC9IfyUImIXcii5HVyFPITmQ/8j5yErmA/IoioWRR2ihzlCsqCBWFSkcVoCpQTagO1DXUKGoa9R6NRtPR6mhTtAs6GB2LzkYXo4+g29GX0cPoKfQiBoORwmhjLDFemHBMCqYAU4U5jbmEGcFMYz5gCVgFrCHWCRuC5WLzsBXYFmwvdgQ7g13GieJUceY4Lxwbl4krwTXienB3cdO4ZbwYXh1viffDx+J34yvxbfhr+Cf4twQCQYlgRvAhxBB2ESoJZwg3CJOEj0QKUYtoTwwlphL3E08SLxMfEd+SSCQ1kg0phJRC2k9qJl0lPSN9EKGK6Iq4irBFckVqRDpFRkRek3FkVbIteSs5i1xBPke+S54XxYmqidqLhovuFK0RPS86LrooRhUzEPMSSxArFmsRuyk2S8FQ1CiOFDYln3KccpUyRUVQlan2VBZ1D7WReo06TUPT1GmutFhaEe1H2hBtQZwibiQeIJ4hXiN+UZxPR9DV6K70eHoJ/Sx9jP5JQk7CVoIjsU+iTWJEYklSRtJGkiNZKNkuOSr5SYoh5SgVJ3VAqkvqqTRSWkvaRzpd+qj0Nel5GZqMhQxLplDmrMxjWVhWS9ZXNlv2uOyg7KKcvJyzXKJcldxVuXl5uryNfKx8uXyv/JwCVcFKIUahXOGSwkuGOMOWEc+oZPQzFhRlFV0UUxXrFYcUl5XUlfyV8pTalZ4q45WZypHK5cp9ygsqCiqeKjkqrSqPVXGqTNVo1cOqA6pLaupqgWp71brUZtUl1V3Vs9Rb1Z9okDSsNZI0GjQeaKI1mZpxmkc072nBWsZa0Vo1Wne1YW0T7RjtI9rD61DrzNZx1zWsG9ch6tjqpOm06kzq0nU9dPN0u3Rf66nohegd0BvQ+6pvrB+v36g/YUAxcDPIM+gx+NNQy5BlWGP4YD1pvdP63PXd698YaRtxjI4aPTSmGnsa7zXuM/5iYmrCM2kzmTNVMQ0zrTUdZ9KY3sxi5g0zlJmdWa7ZBbOP5ibmKeZnzf+w0LGIs2ixmN2gvoGzoXHDlKWSZbhlvSXfimEVZnXMim+taB1u3WD93EbZhm3TZDNjq2kba3va9rWdvh3PrsNuyd7cfof9ZQeEg7NDocOQI8XR37Ha8ZmTklOUU6vTgrOxc7bzZReUi7vLAZdxVzlXlmuz64KbqdsOt353ovsm92r35x5aHjyPHk/Y083zoOeTjaobuRu7vICXq9dBr6fe6t5J3r/4oH28fWp8Xvga+Ob4Dmyibtq2qWXTez87vxK/CX8N/1T/vgByQGhAc8BSoENgWSA/SC9oR9DtYOngmODuEExIQEhTyOJmx82HNk+HGocWhI5tUd+SseXmVumt8VsvbiNvC992LgwVFhjWEvY53Cu8IXwxwjWiNmKBZc86zHrFtmGXs+c4lpwyzkykZWRZ5GyUZdTBqLlo6+iK6PkY+5jqmDexLrF1sUtxXnEn41biA+PbE7AJYQnnuRRuHLd/u/z2jO3DidqJBYn8JPOkQ0kLPHdeUzKUvCW5O4W2+pEeTNVI/S51Ms0qrSbtQ3pA+rkMsQxuxmCmVua+zJksp6wT2chsVnZfjmLO7pzJHbY76ndCOyN29uUq5+bnTu9y3nVqN3533O47efp5ZXnv9gTu6cmXy9+VP/Wd83etBSIFvILxvRZ7675Hfh/z/dC+9fuq9n0tZBfeKtIvqij6XMwqvvWDwQ+VP6zsj9w/VGJScrQUXcotHTtgfeBUmVhZVtnUQc+DneWM8sLyd4e2HbpZYVRRdxh/OPUwv9KjsrtKpaq06nN1dPVojV1Ne61s7b7apSPsIyNHbY621cnVFdV9OhZz7GG9c31ng1pDxXH08bTjLxoDGgdOME80N0k3FTV9Ock9yT/le6q/2bS5uUW2paQVbk1tnTsdevrejw4/drfptNW309uLzoAzqWde/hT209hZ97N955jn2n5W/bm2g9pR2Al1ZnYudEV38buDu4fPu53v67Ho6fhF95eTFxQv1FwUv1jSi+/N7125lHVp8XLi5fkrUVem+rb1TVwNuvqg36d/6Jr7tRvXna5fHbAduHTD8saFm+Y3z99i3uq6bXK7c9B4sOOO8Z2OIZOhzrumd7vvmd3rGd4w3DtiPXLlvsP96w9cH9we3Tg6POY/9nA8dJz/kP1w9lH8ozeP0x4vT+x6gnpS+FT0acUz2WcNv2r+2s434V+cdJgcfL7p+cQUa+rVb8m/fZ7Of0F6UTGjMNM8azh7Yc5p7t7LzS+nXyW+Wp4v+F3s99rXGq9//sPmj8GFoIXpN7w3K38Wv5V6e/Kd0bu+Re/FZ+8T3i8vFX6Q+nDqI/PjwKfATzPL6Z8xnyu/aH7p+er+9clKwsqK0AWELiB0AaELCF1A6AJCFxC6gNAFhC4gdAGhCwhdQOgCQhf4P3aBtf84q4EQXI6PA+CXDYDHHQCqqgFQiwSAHJrCyUgRrHK3M1jbEzN5MVHRKesYqckcRiSPw4nPFKz9A9d7Ew4KZW5kc3RyZWFtCmVuZG9iago4IDAgb2JqCjI0NzIKZW5kb2JqCjkgMCBvYmoKWy9JQ0NCYXNlZCA3IDAgUl0KZW5kb2JqCjEwIDAgb2JqCjw8CiAgL05hbWUgL0ltMgogIC9UeXBlIC9YT2JqZWN0CiAgL0xlbmd0aCAxMSAwIFIKICAvRmlsdGVyIC9GbGF0ZURlY29kZQogIC9TdWJ0eXBlIC9JbWFnZQogIC9XaWR0aCA0MAogIC9IZWlnaHQgNDAKICAvQml0c1BlckNvbXBvbmVudCAxCiAgL0NvbG9yU3BhY2UgWy9JbmRleGVkIC9EZXZpY2VHcmF5IDEgPDAwRkY+XQo+PgpzdHJlYW0KeJw9zrEKwDAIBFAha8BfKXQN+OuCa8BfCbgGrLnS3vAm5S7zy6w4sB3eXtjdX6JTA8prCZhGGcCSmYEOiQC2R70BHdaA9hYCTPy6QTX2BHbP6jh4H8RgSp0BW7EdKFHNOPzj8wGlXHQdCmVuZHN0cmVhbQplbmRvYmoKMTEgMCBvYmoKMTA1CmVuZG9iagoxMiAwIG9iago8PCAvTGVuZ3RoIDEzIDAgUiAvRmlsdGVyIC9GbGF0ZURlY29kZSA+PgpzdHJlYW0KeJztXduuHcdxfT9fsYG8UAHOdnf1/VGKFF0iS3JEwxAcPwTbUeiAjOIYgX8/a1X1zHT3niMePyQSCdEwObtqpi/VdVlV3TP684O/OPzvkf/EIJfbmwd3LUmJ+4USJXZivwDxzw/xKkGqi6kobfgZ5dov5RLCtWaJIYOOxx49OqyXNw8hl2vDn9xiJeX1TEmp6o9QWwaPj52Q9LlXD797+M8Hd/n3h9//AeP4Iy6/wP//A9P760O8/Prho5cPv/rHcMHYX35/zNlXuYhcXr65/P7Fbz+4PLqrc85fXnz7wUUv5fLim+NyoF76Je79cHhuuPnz446/fPCHy8svHj55+fAbiMyna/PBpZCrDsNnCC2KhFIujyHJ1dfkcyuQiLhyjT4031yk4CReS/OlVZ8uPlKm4mIJl5kj/up8yzUGSianK4RaIbjwI5yxtVGa7vLpQ/TUA1+u2fFP4Mrp6mYuVLZnPVlcEtydW8CP0u453qMN78PUGvtDexg/f5e1p3CNEtETlm5+CpxaK7UBHMylel4XcuI1V8cf+Z5TXeqceXRyTRn91BzuOTELHol3/YSrJDxTJC2cVzoGJ5wt5FCvJQjHnTgjrJVQDiWSI549oW3rqSZ26+85oQqnKlNrOgY01OUzcyBTDynUFC/iro0Nu5LtmRA57ujvOdVBptX7pTXMqFyLw8pKC9Po3pDjKn55qeu48zV5ji60tacKDUr2644TpaC1lsiJ0sCpOqMCeS+0V+wjpooOV81B3/yZuAIYYeEzUdctXYV9U8wrI3DhS260y9DUF4lNJFKtCxV+5cCCwKmeStCy+idvrcVtWnecVDE2CV1xqKypWGtmBzmvHIxUKH70SgVNzcxPW3MZHQkVdOZEKBREI/AmmGlqNBcnKrYMMTYsbwxtERw4iXPNZqiDfFLDAqC94tI9J7SINclxkQ9bo7pXSnvlZDqEeicftBYrW6MTWTmZVsWRzvJBay1G2FtuKwduLxU8k9MiH7SGu8DBHBZOxagFnHvBJViPg7ijx0I0+sY3SmuZ7ktpwVsQY0NylRzJSfecSJmnsFouWisCXuaAV04NsI2UZbG05K+Vy5FiuOcEeqKEADNbGlqTQnW/8xCcTwlcgLDYG0YN2TvXcrznwBNhASR0uaiaBXiAQDXbhQVSpiOJMS8iyRAJBwTndM/JUDuHvxaRoDWoBsYd2sqJV7SEZ0JaRILWHO0P6rdy/DU5TCmnRSLoJhbMu8oqRc4QDNdiXiSCbgQeQJX1joNoweCQB1lB6SobhvtbPCw4wdPH5dVjExAlTDnEunIaVBg9Br/GmVcPAU9lWpVbI3uA2OExWzEDyyX3MAgG1wLAZA3fEUCRjSFArRyoHvxLK+0u3I7PrJxAOwQ8grtASCWeijaAwliSQ1we4WxS5ujknhNK6xF6wRzQ1woBoaOwyADaH2tW7zE/BAYE3Kj7c7xPiOmRbQFN3nEqJ5pLWcaG/rNgpimv8wEnCcQW44oraJc+EQasyAbzgbRi4ajzOh/ASN4Z87p04LTIMYSw9kRTYmv+bgyVWLFz5nFnzIj9uHUdEnEAQUq966dgRvgl+QRDIU7zd8l1RRxwIRvqWdADcpBWaGd3iANBl8AUMmdrVcFBM0YJkVBtNWDCRQIy8auzAyfR3UpYzQ4RPLBpjPx+ANB3cGT1Oq+oJtExNoY7ZYQR0uG6qHEtOcU0DEXg1Oh3Ix6jcYStBm3inpMrxSN3ug2Ew0DlThjwiLTIdVG5XBsAnzkIYDS7arhjMrsANRVCzG5dzWIgXQ/UtAQLtCMHRuxaoqGqYkfGAegeyIjQnZxpy4a2b7T6zHZS6jbn+ED3Bw4AAZZVVw46pKsKQf2OBiiXo3FSQfdAPuQ0p8K8gByogTHrqKAiKjtOj27SFxW9sB2PCd048YgekLOQ4Ym9PFwZyTSaIDaNkgh9vdINdQTSI3v1MITbLkG3yf2YREa7GJS4u4lDV4JwEomtJQpXfDKOui7Lr6CcxPGYB8icdQjmOSsfaO0ydT7Qb7RXIHfPdZfZ12mGIIqu7pKrDKjDUYqssaOgo25hC6fSmVCsfuJEhC4iZG96IsSjCP6kJ1qQOEOg7Nwj0bw9RIZeYmpf1PkQpwpgHVuivEP3Y5QJMi+bH8F5mOh97rupoxOf2EvTPAS+xlMdvUU0NbKgrUlgDE5zGsKJc+V8mvOWG0USmbcREi2JC55xbEt896Kbk2CeVdF7reWek9V8fZ04mLzjWjFvBD2q84HicYbWeZpmqLMHyHdlz08TwbtP6qnhNplxV1FcClVjOG8Xhnm/5dvskVoAmwE903eUpBAwEsBRUW8KJhgpsuEshXaSgsEMrREk9A6OV+UGh08QFzDu0X1HZ0kSKxHM9Yvfvbpg2W4cLCZFvCITp6OfyjAedq8uuVjnjgaRkr/nhFwMFIwc0DU5CUWjSqWLCMhuQN+DMegpxX1cxKveAA44mn4IlnqhO0aUQOF6s/bMcgp0vPS8UYXomeAXgiiInWhbWsddjV62Bg22jaseQmZbjqqV/ZGMMIKBDmdCiUxkHazQC9T5fitibIh+i39ipQqibvo9V1YO5EM7VlsfOFQT372IZ8GAlqtkhe9iXex6SB1NEBwdXU37zL3T5M0hkHBcKe4z94BepEdqQ5iLIaCrZy41T/QbU62oOCfnNXHztp6urrkIWit0NiJ3iRu0UWr3TgMHBlpZWFCf2ecOMdCZRaqSdZG0kqSeGZwcPcOZrRUNAR5JHSOtWEwirGLAeZLMCqX5S5og++LKYkAp0jPFYBaooaraUHOyuEgDJMAggw8wzkktZoCUOYAZl6Jxzs4SI4Vl0GcdKiIOdXJivCZDAp176WbmzVVr35KDAaQ7zl6DGzlYDJY9AE/EDLAbGui+dIinBpi2YTGHZoBnpFMDpBSyPgE8bl5FDZCTRWRkDznbZNUAo032ZnmHI35uG0KI5sXfaAZNsfiggaqxtgiXflnoOWutzbOXMeZEBj/Yv41X9hojIrlQddQpcY66uMXyO11zmB3prDm4lKZowBnSffg5sNw42G2l5rjCugZrFDCdHYSw1KZ9s8jsUsz3HHVeKU4M2gZLgTokV3q5kMvKyRWnU1C1cGVTUBb2mrf0SEtpxSyWd2UbbFEIoGpYWLVJohHNKWSOfb2j/RCV+FbRNkNWK3M9P63G4RNF9cViM3GRiVVoV9GSMscSgsFBqi1RTEp14pjd04R6ZS5x6TG2vniRAMSXe06k2EKtE4cqQh/iFa1FxchAxaqDXZ9nlPrqIfcpd6DxhsUK7+k4W+4puXFYRSEcqTaaLUnICD6po2dDKabFN5boCuXiY544Vgqc0xJmD8EKMsVPlYqdg+7TVssfOCwiNSIIqTbnbl8sIYUNCand7eMSWChVLmazO0Wq+sRIz8R7MGvSEzMPQKdudxwFPS1Hu2WfaneGbW0eWaNRE7O7bi+kb6Ie89/stZyptY+RrqNt9D/i1ipEDldahQulLjCbS0gdZv1o4UCfib1imrN50IVKFLLZHk0SLgdkzwlmCWZ70eg6dUdvVKs5EPp9p/OjK7C6ZDpCYlZw1JVcbY+rl1UDkWOUSpnswZLXVEVkJZHSqvsehHEA0JDyyuG71dc7jRx4JqnetzsO+qV7cVYzLYRMzbYh0Y3vW1UL41itg8GkKPY4x9KCKjZ8ECeyq0MD6FRv6y2HK0QKtaPcbGAEZNFac1F6Zq2nIcxjDi1s9tGsPG+eipzSvTs4e8zQsQqhXjOEPXMSM49qNemdg5RWCg29auAvW1Rlqmu3etHBacc0rdDzeKYSxUIaG7ESfZyWSBe1p4ADWdebZXjaRinreqNhetXgZk1AJ6VtXhj0lM0GOKjYE2vKVX2CKzZcTZC4ckLw2B0aKwKuMiGz/dEtiwlMMgsVxdu+qWZKVlxo0odEhmihAzrQLLUjjiOo2LbrmFOEXkPCkCobqhqkNTXaDBnKtG0lstBZ1Wntcy7MvE+kVNouQU3p1Vu00XmDE7VLwzqxdKCLaZTSdZ9YJ+veR7V5Mw2oimiSaji3nznxfbeTLen9rq+FrotlX6xpIo9Ryaq6hNBhJCWYeku598GJb9eh54cu6apmtYSoE9+Wjrer51UwxWlPJIrOHUrZ6Btr1CZS7pF6lM/Nqu6e/sHFsGhfgs7QT4vBWmE91A4esNK5FXDGtVCO32pHd5ykVTG/JYpUBG81UHEdxy2cOuT1U2uvWH6TuBX55nHHqwqkxrJ4yRSwopBPTnXxhXjGp56n33GyNbA4QwxgFNzEiOxT/cvBIFl3SrxlmNu2PuuIhN0+y+JjyGE6p0ETmQEN1SUgQDj72pMBqhbNvyL0swy/uYKRfrNJdFQ5cnSwrkQD14vwEaaZRLFWdMc5Kk1QOa1n+j71o9YERdPSCxY80U1rQdMUkFMvGD2Lq/QmPqkMGxFZ0TBPzp4mYBFZMuDicFQKOrdl70cTVB240TiTb7bV62Qrx3IDNXdFyW6oBnDzyZkhgl55dKBY1q01p1oVFJQ9QEyWSM7gs7J5y76f5swh+HqoGHFE2GrHcBQaPYL2HOOW6ugRAZP5jdtZJQ2pPSu8SA4uus21gbF2HIbgHrLvhSCGTHVLGgq4V03XnryuXi0mjo1Mm+ZyM/WvmByxKRNXyWaduijqwCkmdAcz67sQCSPKit08cRjLHqQjqUQsrYp5Qj8nwRARu42q+OKG9phfbgVyFSD8jgQkspYRS6yQmcKqXHKx7Ui4dQwROJwQW7Fas8xaj7RIssEm7rb3aeyc1I4kkl3nHhhVaQ73nRnnqlY5EG6I/FWZNVcIKn5NYQNrPNKBSj7cQODOrus1CzhCZ9gH5BQ3vEOErXUxenZC780uwFDXC6cVAgDvVhzNA0Dill3Zc5TM6Fsz4WTg9mzr24bAIHELW2E4JATQz1JoBaTi/UNaEajvzbYVApPCnbN7GtD3oOd5fq0DVMyh1m3nph5FVZsdFcSZbe8cisNt9WhYi2qmV3oM3RPRvFi84Am524Mky/sAyk1pLWS+1mkz6w8W9wdGtCQsSd3PPFRqmhCFM3tocWa8evj+79/No2uU84ayi+3pstquaiq5n0tiurw5kEDbUwXR7Yg9Cko7hE6H2I4FqLZvZKngvh/3Wm3EbYfDVo5Xx75wgh6toHopvWmJijtETAWp287oBBE8mAE9YqqdDeIwodZAj5wleMPjpeo0XK57S0nlYeWALWPXhiIH2zfsPFGoH1KtcoAfts0yainahugxQKnWSGGLOSXbEdNtfE6r7eXSwqohVyGo2egdtknpt11/NTQPc0xFvG3kVFgLWwIdURzeWDuIObPsH0l3NOUSrXqwFYa1Jed2BSi+u242RIOo0i15A/0BQCwdDW3FQG1I6BlzPrZHWeqhTyAo7gojLAbq5uRMd3YQrE/u4Oh+nzq1fSOX+2ut52PjMAe6OpCoGYy1EW0dX5teb8gabrZtqgVF4VZEtXqWOgOsIOmh7zIqXdN5261kkU2HEzQ95BEhQFuufDP9DGo0tFqk9Rs6GU943Wjbe76jobMfR3xNv5W0LYtf6vfhbNCrMCyWDgt61kU7Ju7NSaVZpO8SqyBG5SquYzTSacmxWuFr21fePEJy3SVQWN6iWgYEpgDEttdqofBKtLLDkV4NHCsubCVxOoytHz195Pcdiolz+NN41dp9qnbOGr9TLJXw4FF4jsDDAjyixyMuuNNTEuyG3pQ7EzCemNT/eYChXL1604Fj2WnmhpC6TMQDIOiQnmaMbR2+9NOzg9Ws8vez1WhBt/iKwRr1CESIb1YOzCykDLcJOkACFct2wBSLQJchs5K1dJcn+o3SjBxaS219YvelUw8zfR+TtnR4X6I/ICHWecYeiBcVDwCWjGMa6beHcRbTE8Ospx6ekBN179t3SSEsuEIwQAIsRnvb9Om7D2/I2YvEI0eNQsNdKXXiZK08ZoDkvoOriZ7RC0Oi3W7n7JKtIoIR0HSxLiJr3CwrRh7QCEFMxmrPHqiO9O1oAunbQXRtCUQ8kWxbWbf/qnbddLtSdHpOiyXtns5St4+9oZEhCBYsja8daEK7DIlVuK3gaC05oNdkaWxTKdmQEBVBt+ORTXfcmh+lRPKWP5mUMvdH7Khq4z4jNEcfiEgEorMnQi9Mjws30m/TYo+c1yun6n5v3hZ72xy757C46NP8jE5cca6Ot2raVdSa27Z3SDonHjSXIwcJT9YzxLQ1AljqQWJ+xNnaTHSPnWVt0KXnRzoPLfFVs2YX9cBMNqGzHeS/pG/5wLR8C/3Qg4XRiu22jh2oamIlS51GtB1e4KkVa2ibA83YYSVDNB3f5qx+RKsBfpKSmr3W7NumUF2u5OgRlFQu4zqQrorW9WPY1tzpt9Xod85ri5/H6wtc1b6frA4hu+1Q6cB5PXHovxnl+6ntobWZoz2VYctXUq8wmOs5HMYOTtWrOaKhZBn23FFjITuWdNfR9++SW34iTiOL3wL1Lx77F4/9i8f+xWO/xx7bgPTT76Vu77PiH544Hl43dXrWd3z/NOfxZVN3T3CX5756yurQ9Opp8Ky9s17fKK1kL6H+8/Ay6cfD9T8M18O7p+5431Sml0x/gvmfRp9NBlt3Wy9svPQ2f+J1Ox133McdWh94YBD+eY3Sz6NsyQbJ87l66NDpvzb8nVLCRtGC6c/UXsKlrq9qX5Bsm5V881Pr+ltfM3fz2F25Gm7Ql5r8NovdeEezH14c//q4fOKOlwP5u+06Tz7i1xtZ5nfVh1a+3Mj18uJfAHqeKV5gJ5701e364AN8d0mh2kcEHgUyeNTimU/jW/5aNZtIscn0Tj+fPKPZo28rStXDJhRmX9I1259y4XvP/c9ILrz39tDyQXLHjdS68We0u9GwtV/O2y9T+2lpX+b2ZW6fd7/NLn/mwi8mnHYunDYJpyzCibNw4iycosJv1r735x2M9MK75x7y3EOee2jvvvi97/IJT8gnTPLRI2mjgOosoDoLSG9n472TJ0zMzzbmVyPzi5X5xcz0gXd9IbqX8E+4CT/7Cb86Cr94Cr+4Cn2AzfdunjA4P1ucX03OLzbnF6PTB971peg+Q57wGTL7DL86Db94Db+4DX2AzfdunjA9mU1PVtPzi+35xfj0gXd8KaR7DnnCc8jsOWT1HLJ4Dlk8h1iElm588oTxyWx8chel1zC9GJ+8B4FauueQJzyHzJ5DVs8hi+eQxXOIxWvpxheeML4wG5+sxieL8clifPKsoF3CNcXkkk884KsnY52wXKFrESC9JlyLOK6F1swmUixxljuePKPZo89NGrxMSQP/2T5M5S4vrvr3t8P1Rf/+Rv8eMoXhK1VjdjBC/0+Puz85/abVkJo8kTJ8/Nwc4Wct8rkk44EtB4l/O8j3213ih5R3sbwarv903PJfzxRRRESwSz0Xm3nSSL9j07+VhrHpNw9kzFcf9Q3BiZRk9gZ88oz2GOOzRSQAw7NSVhPR/6hA/jLOVgnXQRa3H5T25j2Qg1/kwO8C1S6KMcH+18Nw/jRI4vVw/W9Dmv7HU+v7/rj8735ZLi9+6M8B8UGkO3l4Lh+thePSn14+N8f/eS9KnRal8EtRPBXLY+fdebaxQrpfum0NIMB0ugYDtQ03u1Nhnldkh67L6eX5Y34a5snl8Jicrnh6W2/h9LG3Die/rd16Ol552yzOp3ne8fksznW8vgc6HhYdB4KSNUgN+wJH6Lby4fhFy/365Xl0n1DB8yRX4zWn7JrnW+mOr4lmiG7DtozGWn6eoG2zj45Msd3PcdydkezBZ++2uEVsLZjYvhil8tkx5a8mQZ59CXT080+UYD97/+TmXRdcVPXy+rfo3+5cZB8p8/OB8tW5ln02w9NDzu+fEH0X4ocDpnw57+nt1/49nL/0+RuO/vBMQ87safD2Xw7kIdv57Xl6cv6p3tGCv54d5/8hhNKvDIScYs0XUJSRa04WXQQSaro/ldOwUKS9nmmh5DmUyBmpP/nspZp32TLXqmdBXx5T/WQTXNSdqO3y64H83XA9CPSj802uQfq/Gxblq/dUpLFv+c2KZNMOKwDayZdzIX133P31uW4PhjC73vdTuikdzmXFNfFJabzNz4Sfi595LoylJITfb245pppt5/sx5LvNb6Ut+9/ilu3uO0J/6pnHJsbjHm8/YvD/OfC3luGWcweSrsKzh0E/bfY3fCve7mjTunfovWzojyh8POnzT+dK9wScGrr5u0ll+F4fBMzXnTmp4Sfrx/zSqL5wn83uSw3OqnP2qX2K0hrWb5/MdH4qin/sGyei3xBr9hHFH2EdrW3LAl0wgSf+nfTYxq8+f+MvH//wLitPC9ccM1+59QcGmksSZ0EgnQeBs5pEfS9rEu+gw/Nhc3m8ervT4/ekG/rkS81eGl/nTqHF6O2/tqEGwnOnj/066XurAyPatfelfxxZzYuvkz/NGRvbZhadaav+GzfLk7dbng/8aG0r/I8jQJ5evxjMDxlmPfu+9cW3CPu18J3jmbONzm/fINSRO/vcyROcsbVnzoJFmOBirMAbw/X55XRsTDI/XZpDzDxZO/4gfOFL5DH4xLdTc+ZnF/gxJf1GBIbi+EmipJ84mn78rUdH47wz9EhnGipftObblzVcfwQAWeFiiFA7ecyoPhnog1E+nprqx+dA/nIeoYY7tsJTWNHS2ain82mHQ/jNw/8CcPoF6QplbmRzdHJlYW0KZW5kb2JqCjEzIDAgb2JqCjY1MjMKZW5kb2JqCjE0IDAgb2JqCjw8CiAgL1Jlc291cmNlcyAxNSAwIFIKICAvVHlwZSAvUGFnZQogIC9NZWRpYUJveCBbMCAwIDI4OCA0MzJdCiAgL0Nyb3BCb3ggWzAgMCAyODggNDMyXQogIC9CbGVlZEJveCBbMCAwIDI4OCA0MzJdCiAgL1RyaW1Cb3ggWzAgMCAyODggNDMyXQogIC9QYXJlbnQgMTYgMCBSCiAgL0NvbnRlbnRzIDEyIDAgUgo+PgplbmRvYmoKMTcgMCBvYmoKPDwKICAvVHlwZSAvRm9udAogIC9TdWJ0eXBlIC9UeXBlMQogIC9CYXNlRm9udCAvSGVsdmV0aWNhCiAgL0VuY29kaW5nIC9XaW5BbnNpRW5jb2RpbmcKPj4KZW5kb2JqCjE4IDAgb2JqCjw8CiAgL1R5cGUgL0ZvbnQKICAvU3VidHlwZSAvVHlwZTEKICAvQmFzZUZvbnQgL0hlbHZldGljYS1PYmxpcXVlCiAgL0VuY29kaW5nIC9XaW5BbnNpRW5jb2RpbmcKPj4KZW5kb2JqCjE5IDAgb2JqCjw8CiAgL1R5cGUgL0ZvbnQKICAvU3VidHlwZSAvVHlwZTEKICAvQmFzZUZvbnQgL0hlbHZldGljYS1Cb2xkCiAgL0VuY29kaW5nIC9XaW5BbnNpRW5jb2RpbmcKPj4KZW5kb2JqCjE2IDAgb2JqCjw8IC9UeXBlIC9QYWdlcwovQ291bnQgMQovS2lkcyBbMTQgMCBSIF0gPj4KZW5kb2JqCjIwIDAgb2JqCjw8CiAgL1R5cGUgL0NhdGFsb2cKICAvUGFnZXMgMTYgMCBSCiAgL0xhbmcgKHgtdW5rbm93bikKPj4KZW5kb2JqCjE1IDAgb2JqCjw8CiAgL0ZvbnQgPDwKICAvRjEgMTcgMCBSCiAgL0YyIDE4IDAgUgogIC9GMyAxOSAwIFIKPj4KICAvUHJvY1NldCBbL1BERiAvSW1hZ2VCIC9JbWFnZUMgL1RleHRdCiAgL1hPYmplY3QgPDwgL0ltMSA1IDAgUiAvSW0yIDEwIDAgUiA+PgogIC9Db2xvclNwYWNlIDw8IC9JQ0MyIDQgMCBSIC9JQ0M3IDkgMCBSID4+Cj4+CmVuZG9iagp4cmVmCjAgMjEKMDAwMDAwMDAwMCA2NTUzNSBmIAowMDAwMDAwMDE1IDAwMDAwIG4gCjAwMDAwMDAxMzEgMDAwMDAgbiAKMDAwMDAwMjY4OCAwMDAwMCBuIAowMDAwMDAyNzA4IDAwMDAwIG4gCjAwMDAwMDI3NDEgMDAwMDAgbiAKMDAwMDAwMzAxMyAwMDAwMCBuIAowMDAwMDAzMDMxIDAwMDAwIG4gCjAwMDAwMDU1ODggMDAwMDAgbiAKMDAwMDAwNTYwOCAwMDAwMCBuIAowMDAwMDA1NjQxIDAwMDAwIG4gCjAwMDAwMDU5NjcgMDAwMDAgbiAKMDAwMDAwNTk4NyAwMDAwMCBuIAowMDAwMDEyNTg2IDAwMDAwIG4gCjAwMDAwMTI2MDcgMDAwMDAgbiAKMDAwMDAxMzI2NyAwMDAwMCBuIAowMDAwMDEzMTMyIDAwMDAwIG4gCjAwMDAwMTI4MDEgMDAwMDAgbiAKMDAwMDAxMjkwNyAwMDAwMCBuIAowMDAwMDEzMDIxIDAwMDAwIG4gCjAwMDAwMTMxOTIgMDAwMDAgbiAKdHJhaWxlcgo8PAogIC9Sb290IDIwIDAgUgogIC9JbmZvIDEgMCBSCiAgL0lEIFs8NjAzQkZGMDVEMEMzRTFFODkzQjlDNzkyMDJDRDYxOUI+IDw2MDNCRkYwNUQwQzNFMUU4OTNCOUM3OTIwMkNENjE5Qj5dCiAgL1NpemUgMjEKPj4Kc3RhcnR4cmVmCjEzNDY2CiUlRU9GCg==";

        // Decode the Base64-encoded PDF
        // $decodedPDF = base64_decode($base64EncodedPDF);
        
        Storage::disk('public')->put('file001.pdf',base64_decode($base64EncodedPDF));

    }
}
