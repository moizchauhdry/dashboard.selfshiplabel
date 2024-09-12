<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\SiteSetting;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RateController extends BaseController
{
    public function index(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'ship_from_country_code' => 'required',
                'ship_to_country_code' => 'required',
                'insurance_amount' => 'nullable',
                'ship_from_postal_code' => 'nullable',
                'ship_to_postal_code' => 'nullable',
                'dimensions' => 'required|array',
                'dimensions.*.weight' => 'required',
                'dimensions.*.length' => 'required',
                'dimensions.*.width' => 'required',
                'dimensions.*.height' => 'required',
            ],
            [
                'dimensions.*.weight.required' => 'The weight field is required.',
                'dimensions.*.length.required' => 'The length field is required.',
                'dimensions.*.width.required' => 'The width field is required.',
                'dimensions.*.height.required' => 'The height field is required.',
                'ship_from_postal_code.required' => 'The field is required.',
                'ship_to_postal_code.required' => 'The field is required.',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('validation error', $validator->errors());
        }

        try {

            if ($request->ship_from_postal_code) {
                $ship_from_postal_code = $request->ship_from_postal_code;
            } else {
                $ship_from_postal_code = '92804'; // DEFAULT US ZIP CODE
            }

            if ($request->ship_to_postal_code) {
                $ship_to_postal_code = $request->ship_to_postal_code;
            } else {
                $ship_to_postal_code = '00000';
            }

            $weight_units = 'LB';
            $dimension_units = 'IN';
            $measurement_unit = 'imperial';

            $data = [
                'ship_from_postal_code' => $ship_from_postal_code,
                'ship_from_country_code' => $request->ship_from_country_code,
                'insurance_amount' => $request->insurance_amount,
                'ship_from_city' => "Anaheim",
                'ship_from_state' => "CA",
                'ship_to_postal_code' => $ship_to_postal_code,
                'ship_to_country_code' => $request->ship_to_country_code,
                'ship_to_city' => $request->ship_to_city,
                'weight_units' => $weight_units,
                'dimension_units' => $dimension_units,
                'measurement_unit' => $measurement_unit,
                'customs_value' => $request->customs_value,
                'residential' => $request->is_residential,
                'dimensions' => $request->dimensions,
            ];

            $fedex_rates = $this->fedex($data, 1);
            $dhl_rates = $this->dhl($data, 1);
            $ups_rates = $this->ups($data, 1);
            // $usps_rates = $this->usps($data, 1);
            $usps_rates = [];

            $rates = array_merge($fedex_rates, $dhl_rates, $ups_rates, $usps_rates);

            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $rates,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ]);
        }
    }

    public function index2(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'ship_from_country_code' => 'required',
                'ship_to_country_code' => 'required',
                'insurance_amount' => 'required',
                'ship_from_postal_code' => 'required',
                'ship_to_postal_code' => 'required',
                'is_residential' => 'required',
                'dimensions' => 'required|array',
                'dimensions.*.weight' => 'required',
                'dimensions.*.length' => 'required',
                'dimensions.*.width' => 'required',
                'dimensions.*.height' => 'required',
            ],
            [
                'dimensions.*.weight.required' => 'The weight field is required.',
                'dimensions.*.length.required' => 'The length field is required.',
                'dimensions.*.width.required' => 'The width field is required.',
                'dimensions.*.height.required' => 'The height field is required.',
                'ship_from_postal_code.required' => 'The field is required.',
                'ship_to_postal_code.required' => 'The field is required.',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('validation error', $validator->errors());
        }

        try {

            $weight_units = 'LB';
            $dimension_units = 'IN';
            $measurement_unit = 'imperial';

            $data = [
                'ship_from_postal_code' => $request->ship_from_postal_code,
                'ship_from_country_code' => $request->ship_from_country_code,
                'ship_from_city' => $request->ship_from_city,
                'ship_from_state' => $request->ship_from_state,

                'ship_to_postal_code' => $request->ship_to_postal_code,
                'ship_to_country_code' => $request->ship_to_country_code,
                'ship_to_city' => $request->ship_to_city,

                'weight_units' => $weight_units,
                'dimension_units' => $dimension_units,
                'measurement_unit' => $measurement_unit,
                'dimensions' => $request->dimensions,
                'customs_value' => $request->customs_value,
                'residential' => $request->is_residential,
                'insurance_amount' => $request->insurance_amount,
            ];

            $fedex_rates = $this->fedex($data, 2);
            $dhl_rates = $this->dhl($data, 2);
            $ups_rates = $this->ups($data, 2);

            $rates = array_merge($fedex_rates, $dhl_rates, $ups_rates);

            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $rates,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ]);
        }
    }

    public function fedex($data, $project_id)
    {
        $client = new Client();

        $result = $client->post('https://apis.fedex.com/oauth/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => 'l7ef7275cc94544aaabf802ef4308bb66a',
                'client_secret' => '48b51793-fd0d-426d-8bf0-3ecc62d9c876',
            ]
        ]);

        $authorization = $result->getBody()->getContents();
        $authorization = json_decode($authorization);

        $headers = [
            'X-locale' => 'en_US',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $authorization->access_token
        ];

        $requested_package_line_items = [];
        foreach ($data['dimensions'] as $key => $dimension) {
            $requested_package_line_items[] =  [
                "weight" => [
                    "units" => $data['weight_units'],
                    "value" => $dimension['weight']
                ],
                "dimensions" => [
                    "length" => $dimension['length'],
                    "width" => $dimension['width'],
                    "height" => $dimension['height'],
                    "units" => $data['dimension_units']
                ]
            ];
        }

        $body = [
            "accountNumber" => [
                "value" => "695684150"
            ],
            "requestedShipment" => [
                "shipper" => [
                    "address" => [
                        "postalCode" => $data['ship_from_postal_code'],
                        "countryCode" => $data['ship_from_country_code'],
                        "residential" => false
                    ]
                ],
                "recipient" => [
                    "address" => [
                        "postalCode" => $data['ship_to_postal_code'],
                        "countryCode" => $data['ship_to_country_code'],
                        "residential" => $data['residential']
                    ]
                ],
                "pickupType" => "DROPOFF_AT_FEDEX_LOCATION",
                "rateRequestType" => [
                    "ACCOUNT"
                ],
                "requestedPackageLineItems" => $requested_package_line_items
            ]
        ];

        $request = $client->post('https://apis.fedex.com/rate/v1/rates/quotes', [
            'headers' => $headers,
            'body' => json_encode($body)
        ]);

        $response = $request->getBody()->getContents();
        $response = json_decode($response);
        $rates = [];
        foreach ($response->output->rateReplyDetails as $key => $fedex) {
            $price = $fedex->ratedShipmentDetails[0]->totalNetFedExCharge;
            $markup = shipping_service_markup($fedex->serviceType, $project_id);
            $markup_amount = $price * ((float)$markup / 100);

            $total = $price + $markup_amount;
            $total = number_format((float)$total, 2, '.', '');

            $rates[] = [
                'code' => 'fedex',
                'type' => $fedex->serviceType,
                'name' => $fedex->serviceName,
                'pkg_type' => $fedex->packagingType,
                'price' => $price,
                'markup' => $markup_amount,
                'total' => $total,
            ];
        }

        return $rates;
    }

    public function dhl($data, $project_id)
    {
        try {
            $packages = [];
            foreach ($data['dimensions'] as $key => $dimension) {
                $packages[] =  [
                    "weight" => (float) $dimension['weight'],
                    "dimensions" => [
                        "length" => (float) $dimension['length'],
                        "width" => (float) $dimension['width'],
                        "height" => (float) $dimension['height']
                    ]
                ];
            }

            $client = new Client();

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic YXBHN3RWNGNSMWFVOGQ6Wl40c0ckMXlSQDZ4VSM5Yw=='
            ];

            $body = [
                "customerDetails" => [
                    "shipperDetails" => [
                        "postalCode" => $data['ship_from_postal_code'],
                        "cityName" => $data['ship_from_city'],
                        "countryCode" => $data['ship_from_country_code']
                    ],
                    "receiverDetails" => [
                        "postalCode" => $data['ship_to_postal_code'],
                        "cityName" => $data['ship_to_city'],
                        "countryCode" => $data['ship_to_country_code']
                    ]
                ],
                "accounts" => [
                    [
                        "typeCode" => "shipper",
                        "number" => "849192247"
                    ]
                ],
                "productsAndServices" => [
                    [
                        "productCode" => "P",
                        "localProductCode" => "P"
                    ]
                ],
                "payerCountryCode" => "US",
                "plannedShippingDateAndTime" => Carbon::now(),
                "unitOfMeasurement" => $data['measurement_unit'],
                "isCustomsDeclarable" => true,
                "monetaryAmount" => [
                    [
                        "typeCode" => "declaredValue",
                        "value" => (float) $data['customs_value'],
                        "currency" => "USD"
                    ]
                ],
                "estimatedDeliveryDate" => [
                    "isRequested" => true,
                    "typeCode" => "QDDC"
                ],
                "getAdditionalInformation" => [
                    [
                        "typeCode" => "allValueAddedServices",
                        "isRequested" => true
                    ]
                ],
                "returnStandardProductsOnly" => false,
                "nextBusinessDay" => true,
                "productTypeCode" => "all",
                "packages" => $packages
            ];

            $request = $client->post('https://express.api.dhl.com/mydhlapi/test/rates', [
                'headers' => $headers,
                'body' => json_encode($body)
            ]);

            $response = $request->getBody()->getContents();
            $response = json_decode($response);

            $markup = shipping_service_markup('EXPRESS_WORLDWIDE', $project_id);
            $price = $response->products[0]->totalPrice[0]->price;
            $markup_amount = $response->products[0]->totalPrice[0]->price * ((int)$markup / 100);
            $total = $price + $markup_amount;
            $total = number_format((float)$total, 2, '.', '');

            $rates = [];
            $rates[] = [
                'code' => 'dhl',
                'type' => 'EXPRESS_WORLDWIDE',
                'name' => 'DHL Express Worldwide',
                'pkg_type' => 'YOUR_PACKAGING',
                'price' => $price,
                'markup' => $markup_amount,
                'total' => $total,
            ];

            return $rates;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function ups($data, $project_id)
    {
        try {

            // Authorization
            $curl = curl_init();

            $payload = "grant_type=client_credentials";

            curl_setopt_array($curl, [
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/x-www-form-urlencoded",
                    "x-merchant-id: string",
                    "Authorization: Basic " . base64_encode("gXAcE3M3RxNagMZFVdpjqJWNzsanoEozN9NvsJbyzWkkve5N:kshGQ5SwpG7utyqDtV3zHd1JtyqhTzczSSGq1nFByluEZHgkx1ywY1inudXA2JMH")
                ],
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_URL => "https://wwwcie.ups.com/security/v1/oauth/token",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
            ]);

            $authorization_response = curl_exec($curl);
            $authorization_response = json_decode($authorization_response);
            $access_token = $authorization_response->access_token;

            $packages = [];
            foreach ($data['dimensions'] as $key => $dimension) {
                $packages[] =   [
                    "PackagingType" => [
                        "Code" => "02",
                        "Description" => "Packaging"
                    ],
                    "Dimensions" => [
                        "UnitOfMeasurement" => [
                            "Code" => $data['dimension_units'],
                        ],
                        "Length" => (string) $dimension['length'],
                        "Width" => (string) $dimension['width'],
                        "Height" => (string) $dimension['height']
                    ],
                    "PackageWeight" => [
                        "UnitOfMeasurement" => [
                            "Code" => "LBS",
                            "Description" => "Ounces"
                        ],
                        "Weight" => (string) $dimension['weight']
                    ],
                    "OversizeIndicator" => "X",
                    "MinimumBillableWeightIndicator" => "X"
                ];
            }

            // Package Rating 
            $payload = array(
                "RateRequest" => array(
                    "Request" => array(
                        "TransactionReference" => array(
                            "CustomerContext" => "Verify Success response",
                            "TransactionIdentifier" => "?"
                        )
                    ),
                    "Shipment" => array(
                        "Shipper" => array(
                            "Name" => "Aman",
                            "ShipperNumber" => "WY2291",
                            "Address" => array(
                                "City" => "Bear",
                                "StateProvinceCode" => "DE",
                                "PostalCode" => "19701",
                                "CountryCode" => "US"
                            )
                        ),
                        "ShipTo" => array(
                            "Address" => array(
                                "City" => $data['ship_to_city'],
                                "StateProvinceCode" => "",
                                "PostalCode" => $data['ship_to_postal_code'],
                                "CountryCode" => $data['ship_to_country_code']
                            )
                        ),
                        "ShipFrom" => array(
                            "Name" => "ShippingXPS",
                            "Address" => array(
                                "City" => $data['ship_from_city'],
                                "StateProvinceCode" => $data['ship_from_state'],
                                "PostalCode" => $data['ship_from_postal_code'],
                                "CountryCode" => $data['ship_from_country_code']
                            )
                        ),
                        "PaymentDetails" => array(
                            "ShipmentCharge" => array(
                                "Type" => "01",
                                "BillShipper" => array(
                                    "AttentionName" => "Aman",
                                    "Name" => "Aman",
                                    "AccountNumber" => "WY2291",
                                    "Address" => array(
                                        "ResidentialAddressIndicator" => "Y",
                                        "AddressLine" => "AdressLine",
                                        "City" => "NEW YORK",
                                        "StateProvinceCode" => "NY",
                                        "PostalCode" => "21093",
                                        "CountryCode" => "US"
                                    )
                                )
                            )
                        ),
                        "ShipmentRatingOptions" => array(
                            "TPFCNegotiatedRatesIndicator" => "Y",
                            "NegotiatedRatesIndicator" => "Y"
                        ),
                        "NumOfPieces" => "10",
                        "Package" => $packages
                    )
                )
            );

            curl_setopt_array($curl, [
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer " . $access_token,
                    "Content-Type: application/json",
                    "transId: string",
                    "transactionSrc: testing"
                ],
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_URL => "https://onlinetools.ups.com/api/rating/v1/shop",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
            ]);

            $rating_response = curl_exec($curl);
            $rating_response = json_decode($rating_response);
            $error = curl_error($curl);

            $markup = SiteSetting::getByName('markup');
            $rates = [];
            foreach ($rating_response->RateResponse->RatedShipment as $key => $ups) {
                $price = $ups->NegotiatedRateCharges->TotalCharge->MonetaryValue;
                $markup = shipping_service_markup($ups->Service->Code, $project_id);
                $markup_amount = $price * ((int)$markup / 100);
                $total = $price + $markup_amount;
                $total = number_format((float)$total, 2, '.', '');

                $rates[] = [
                    'code' => 'ups',
                    'type' => $ups->Service->Code,
                    'name' => $this->upsServiceCode($ups->Service->Code),
                    'pkg_type' => 'YOUR_PACKAGING',
                    'price' => $price,
                    'markup' => $markup_amount,
                    'total' => $total,
                ];
            }

            curl_close($curl);

            return $rates;
        } catch (\Throwable $th) {
            return [];
        }
    }

    private function upsServiceCode($code)
    {
        switch ($code) {
            case $code == '01':
                $name = 'UPS Next Day Air';
                break;

            case $code == '02':
                $name = 'UPS 2nd Day Air';
                break;

            case $code == '03':
                $name = 'UPS Ground';
                break;

            case $code == '12':
                $name = 'UPS 3 Day Select';
                break;

            case $code == '13':
                $name = 'UPS Next Day Air Saver';
                break;

            case $code == '14':
                $name = 'UPS UPS Next Day Air Early';
                break;

            case $code == '59':
                $name = 'UPS 2nd Day Air A.M. Valid international values';
                break;

            case $code == '07':
                $name = 'UPS Worldwide Express';
                break;

            case $code == '08':
                $name = 'UPS Worldwide Expedited';
                break;

            case $code == '11':
                $name = 'UPS Standard';
                break;

            case $code == '54':
                $name = 'UPS Worldwide Express Plus';
                break;

            case $code == '65':
                $name = 'UPS Worldwide Saver';
                break;

            case $code == '96':
                $name = 'UPS UPS Worldwide Express Freight';
                break;

            case $code == '71':
                $name = 'UPS UPS Worldwide Express Freight Midday Required for Rating and ignored for Shopping';
                break;

            default:
                $name = 'UPS Default';
                break;
        }


        return $name;
    }

    public function usps($data, $project_id)
    {
        $client_id = "IflzdSXAAtl33158BLidVum089HXVWR9";
        $client_secret = "HAa6nqXQgP1zkNvS";

        // Authorization API
        $token_url = "https://api.usps.com/oauth2/v3/token";
        $params = [
            "client_id" => $client_id,
            "client_secret" => $client_secret,
            "grant_type" => "client_credentials"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $token_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response, true);
        $access_token = $response['access_token'];

        // Domestic Rates API
        $headers = [
            'X-locale' => 'en_US',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token,
        ];

        if ($data['ship_from_country_code'] == $data['ship_to_country_code']) {
            $rates_url = "https://api.usps.com/prices/v3/total-rates/search";
            $body = [
                "originZIPCode" =>  $data['ship_from_postal_code'],
                "destinationZIPCode" =>  $data['ship_to_postal_code'],
                "weight" =>  1,
                "length" =>  1,
                "width" =>  1,
                "height" =>  1,
                "mailClass" =>  "PARCEL_SELECT",
                "mailClasses" =>  [
                    "ALL"
                ],
                "priceType" =>  "RETAIL",
                "mailingDate" =>  Carbon::now()->format('Y-m-d'),
                "accountType" =>  "EPS",
                "accountNumber" =>  "1000123621",
                "itemValue" =>  0,
                "extraServices" =>  [
                    415
                ]
            ];
        } else {
            $rates_url = "https://api.usps.com/international-prices/v3/total-rates/search";
            $body = [
                "originZIPCode" => $data['ship_from_postal_code'],
                "weight" => 1,
                "length" => 1,
                "width" => 1,
                "height" => 1,
                "mailClass" => "ALL",
                "processingCategory" => "FLATS",
                "rateIndicator" => "E4",
                "destinationEntryFacilityType" => "NONE",
                "priceType" => "RETAIL",
                "mailingDate" =>  Carbon::now()->format('Y-m-d'),
                "foreignPostalCode" => $data['ship_to_postal_code'],
                "destinationCountryCode" => "PK",
                "accountType" => "EPS",
                "accountNumber" => "1000123621"
            ];
        }

        $client = new Client();

        $request = $client->post($rates_url, [
            'headers' => $headers,
            'body' => json_encode($body)
        ]);

        $response = $request->getBody()->getContents();
        $rating_response = json_decode($response);

        $markup = SiteSetting::getByName('markup');
        $rates = [];
        foreach ($rating_response->rateOptions as $key => $usps) {
            $price = $usps->rates[0]->price;
            $markup = shipping_service_markup($usps->rates[0]->mailClass, $project_id);
            $markup_amount = $price * ((int)$markup / 100);
            $total = $price + $markup_amount;
            $total = number_format((float)$total, 2, '.', '');

            $rates[] = [
                'code' => 'usps',
                'type' => $usps->rates[0]->mailClass,
                'name' => $usps->rates[0]->description,
                'pkg_type' => 'YOUR_PACKAGING',
                'price' => $price,
                'markup' => $markup_amount,
                'total' => $total,
            ];
        }
        
        return $rates;
    }
}
