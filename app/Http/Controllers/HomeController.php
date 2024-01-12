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
use App\Models\Payment;
use App\Models\Warehouse;
use App\Models\SiteSetting;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Svg\Tag\Rect;

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
        $response['warehouses'] = Warehouse::where('id', 2)->get();

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

    public function decodeLabel($id)
    {
        $package = Package::find($id);
        $base64EncodedPDF = $package->encoded_label;

        // Decode the Base64-encoded PDF
        // $decodedPDF = base64_decode($base64EncodedPDF);

        Storage::disk('public')->put('file001.pdf', base64_decode($base64EncodedPDF));
    }

    public function stripe($client_secret)
    {
        return view('stripe.index', compact('client_secret'));
    }

    public function stripeSuccess(Request $request)
    {
        try {
            $stripe = new \Stripe\StripeClient(config('app.stripe_secret_key'));
            $payment_intent = $stripe->paymentIntents->retrieve(
                $request->payment_intent
            )->toArray();

            $charge = $stripe->charges->retrieve(
                $payment_intent['latest_charge'],
            )->toArray();

            $payment = Payment::where('stripe_payment_id', $charge['payment_intent'])->first();
            $package = Package::where('id', $payment->package_id)->first();

            if ($charge['paid'] == true && $payment->charged_at == NULL && $package->payment_status == 'Pending') {
                $payment->update([
                    'invoice_id' => $payment->id,
                    'transaction_id' => $charge['balance_transaction'],
                    'charged_amount' => $charge['amount'] / 100,
                    'charged_at' => Carbon::now(),
                ]);

                $package->update([
                    'payment_status' => 'Paid',
                    'status' => 'complete',
                    'cart' => 0,
                ]);
            }

            $externalLink = 'https://self.shippingxps.com';
            return redirect()->away($externalLink);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
