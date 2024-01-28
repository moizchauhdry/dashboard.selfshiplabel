<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Address;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PackageBox;
use App\Models\Payment;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class PackageController extends BaseController
{
    public function index()
    {
        $user = Auth::user();
        $data['packages'] = Package::with('boxes', 'payments')->where('project_id', 2)->where('customer_id', $user->id)->orderBy('id', 'desc')->paginate(100);
        return $this->sendResponse($data, 'success');
    }

    public function setRate(Request $request)
    {
        // return $request;
        $user = Auth::user();

        $data = [
            'customer_id' => $user->id,
            'status' => 'open',
            'pkg_type' => 'single',
            'warehouse_id' => 1,
            'carrier_code' => $request->rate['code'],
            'service_code' => $request->rate['type'],
            'package_type_code' => $request->rate['pkg_type'],
            'service_label' => $request->rate['name'],
            'markup_fee' => $request->rate['markup'],
            'shipping_charges' => $request->rate['total'],
            'currency' => "USD",
            'pkg_dim_status' => "done",
            'project_id' => 2,
            'cart' => true,
        ];

        $package = Package::updateOrCreate(['customer_id' => $user->id, 'cart' => 1], $data);

        foreach ($request->dimensions as $key => $dimension) {
            PackageBox::updateOrCreate(['package_id' => $package->id], [
                'package_id' => $package->id,
                'pkg_type' => $package->pkg_type,
                'weight_unit' => 'lb',
                'dim_unit' => 'in',
                'weight' => $dimension['weight'],
                'length' => $dimension['length'],
                'width' => $dimension['width'],
                'height' => $dimension['height'],
            ]);
        }

        $data['package'] = $package;

        return $this->sendResponse($data, 'success');
    }

    public function updateRate(Request $request)
    {
        $package = Package::where('id', $request->package_id)->first();

        $data = [
            'carrier_code' => $request->rate['code'],
            'service_code' => $request->rate['type'],
            'package_type_code' => $request->rate['pkg_type'],
            'service_label' => $request->rate['name'],
            'markup_fee' => $request->rate['markup'],
            'shipping_charges' => $request->rate['total'],
        ];

        $package->update($data);

        return $this->sendResponse($data, 'success');
    }

    public function getPackage()
    {
        $data['package'] = Package::with('shipTo', 'shipFrom', 'boxes', 'packageItems')->cart()->first();

        return $this->sendResponse($data, 'success');
    }

    public function setAddress(Request $request)
    {
        $package = Package::cart()->first();

        if ($request->type == 'ship_from') {
            $package->update(['ship_from' => $request->id]);
        }

        if ($request->type == 'ship_to') {
            $package->update(['ship_to' => $request->id]);
        }

        return $this->sendResponse('success', 'success');
    }

    public function setCustom(Request $request)
    {
        try {
            $package = Package::cart()->first();

            $validator = Validator::make($request->all(), [
                'items.*.description' => 'required',
                'items.*.quantity' => 'required|gt:0',
                'items.*.unit_price' => 'required|gt:0|numeric',
                // 'items.*.origin_country' => 'required',
                'items.*.batteries' => 'nullable',
                'items.*.hs_code' => 'nullable',
                'shipping_total' => 'required',
                'package_type' => 'required',
                'country' => 'required',
            ],  [
                'items.*.description.required' => 'The package items description field is required.',
                'items.*.quantity.required' => 'The package items quantity field is required.',
                'items.*.price.required' => 'The package items price field is required.',
                'items.*.price.gt' => 'The package items price must be greater than 0.',
                // 'items.*.origin_country.required' => 'The package items origin country field is required.',
            ]);

            if ($validator->fails()) {
                return $this->sendError('validation error', $validator->errors());
            }

            $grand_total =  $package->shipping_charges;

            $package->update([
                'custom_form_status' => true,
                'status' => "filled",
                'package_type' => $request->package_type,
                'shipping_total' => $request->shipping_total,
                'grand_total' => $grand_total,
            ]);

            OrderItem::where('package_id', $package->id)->delete();
            foreach ($request->items as $key => $item) {
                $order_item = new OrderItem();
                $order_item->package_id = $package->id;
                $order_item->origin_country = $request->country;
                $order_item->hs_code = $item['hs_code'] ?? null;
                $order_item->description = $item['description'];
                $order_item->quantity = $item['quantity'];
                $order_item->unit_price = $item['unit_price'];
                $order_item->batteries = $item['batteries'] ?? null;
                $order_item->save();
            }

            if ($package->carrier_code == 'fedex') {
                $data['fedex_label'] = generateLabelFedex($package->id);
            }

            if ($package->carrier_code == 'ups') {
                $data['ups_label'] = generateLabelUps($package->id);
            }

            if ($package->carrier_code == 'dhl') {
                generateLabelDhl($package->id);
            }

            return $this->sendResponse($data, 'The custom decration form filled successfully.');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    // public function label($package)
    // {
    //     $ship_to = Address::where('id', $package->ship_to)->first();
    //     $items = OrderItem::with('originCountry')->where('package_id', $package->id)->get();

    //     $commodities = [];
    //     foreach ($items as $key => $item) {
    //         $commodities[] = [
    //             "description" => $item->description,
    //             "countryOfManufacture" => "US",
    //             "quantity" => $item->quantity,
    //             "quantityUnits" => "PCS",
    //             "unitPrice" => [
    //                 "amount" => $item->unit_price,
    //                 "currency" => "USD"
    //             ],
    //             "customsValue" => [
    //                 "amount" => $item->unit_price,
    //                 "currency" => "USD"
    //             ],
    //             "weight" => [
    //                 "units" => "LB",
    //                 "value" => 1
    //             ]
    //         ];
    //     }

    //     $client = new Client();

    //     $result = $client->post('https://apis.fedex.com/oauth/token', [
    //         'form_params' => [
    //             'grant_type' => 'client_credentials',
    //             'client_id' => 'l7ef7275cc94544aaabf802ef4308bb66a',
    //             'client_secret' => '48b51793-fd0d-426d-8bf0-3ecc62d9c876',
    //         ]
    //     ]);

    //     $authorization = $result->getBody()->getContents();
    //     $authorization = json_decode($authorization);

    //     $headers = [
    //         'X-locale' => 'en_US',
    //         'Content-Type' => 'application/json',
    //         'Authorization' => 'Bearer ' . $authorization->access_token
    //     ];


    //     $body = [
    //         "mergeLabelDocOption" => "LABELS_ONLY",
    //         "requestedShipment" => [
    //             "shipDatestamp" => Carbon::parse(Carbon::now())->format('Y-m-d'),
    //             "pickupType" => "USE_SCHEDULED_PICKUP",
    //             "serviceType" => $package->service_code,
    //             "packagingType" => "YOUR_PACKAGING",
    //             "shippingChargesPayment" => [
    //                 "paymentType" => "SENDER"
    //             ],
    //             "shipper" => [
    //                 "address" => [
    //                     "streetLines" => [
    //                         "3578 W savanna",
    //                         "st Anaheim"
    //                     ],
    //                     "city" => "Anaheim",
    //                     "stateOrProvinceCode" => "CA",
    //                     "postalCode" => "92804",
    //                     "countryCode" => "US",
    //                     "residential" => false
    //                 ],
    //                 "contact" => [
    //                     "personName" => "Habibur haseeb",
    //                     "emailAddress" => "habib10@me.com",
    //                     "phoneExtension" => "91",
    //                     "phoneNumber" => "1209717988",
    //                     "companyName" => "shippingxps"
    //                 ]
    //             ],
    //             "recipients" => [
    //                 [
    //                     "address" => [
    //                         "streetLines" => [
    //                             $ship_to->address,
    //                             $ship_to->address_1,
    //                             $ship_to->address_2
    //                         ],
    //                         "city" => $ship_to->city,
    //                         "stateOrProvinceCode" => $ship_to->state,
    //                         "postalCode" => $ship_to->zip_code,
    //                         "countryCode" => $ship_to->country->iso,
    //                         "residential" => false
    //                     ],
    //                     "contact" => [
    //                         "personName" => $ship_to->fullname,
    //                         "emailAddress" => $ship_to->email,
    //                         "phoneExtension" => "91",
    //                         "phoneNumber" => "16572101801",
    //                         "companyName" => $ship_to->fullname
    //                     ]
    //                 ]
    //             ],
    //             "requestedPackageLineItems" => [
    //                 [
    //                     "sequenceNumber" => "1",
    //                     "weight" => [
    //                         "units" => "LB",
    //                         "value" => 3
    //                     ],
    //                     "dimensions" => [
    //                         "length" => 1,
    //                         "width" => 1,
    //                         "height" => 1,
    //                         "units" => "IN"
    //                     ]
    //                 ],
    //             ],
    //             "labelSpecification" => [
    //                 "imageType" => "PDF",
    //                 "labelStockType" => "PAPER_85X11_TOP_HALF_LABEL",
    //                 "returnedDispositionDetail" => true,
    //                 "customerSpecifiedDetail" => [
    //                     "maskedData" => [
    //                         "DUTIES_AND_TAXES_PAYOR_ACCOUNT_NUMBER",
    //                         "TRANSPORTATION_CHARGES_PAYOR_ACCOUNT_NUMBER"
    //                     ]
    //                 ]
    //             ],
    //             "customsClearanceDetail" => [
    //                 "isDocumentOnly" => true,
    //                 "commodities" => $commodities,
    //                 "dutiesPayment" => [
    //                     "paymentType" => "RECIPIENT"
    //                 ]
    //             ]
    //         ],
    //         "labelResponseOptions" => "LABEL",
    //         "accountNumber" => [
    //             "value" => "695684150"
    //         ],
    //         "shipAction" => "CONFIRM",
    //         "processingOptionType" => "ALLOW_ASYNCHRONOUS",
    //         "oneLabelAtATime" => true
    //     ];

    //     $request = $client->post('https://apis.fedex.com/ship/v1/shipments', [
    //         'headers' => $headers,
    //         'body' => json_encode($body)
    //     ]);

    //     $response = $request->getBody()->getContents();
    //     return $response = json_decode($response);
    // }

    public function payment(Request $request)
    {
        $package = Package::find($request->package_id);

        return $package;
        $grand_total = 0;

        if ($package->grand_total > 0) {
            $grand_total = $package->grand_total;
        } else {
            return $this->error('The value must be greater then 0',);
        }

        $data = [];

        return $this->sendResponse($data, 'The payment intent created successfully.');
    }
}
