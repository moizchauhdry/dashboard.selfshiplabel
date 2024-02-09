<?php

use App\Models\Address;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\Payment;
use App\Models\ShippingService;
use App\Models\SiteSetting;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use GuzzleHttp\Client;

function format_number($number)
{
    if ($number > 0) {
        return number_format((float)$number, 2, '.', '');
    } else {
        return 0;
    }
}

function calulate_storage($package)
{
    $storage_days_exceeded = 0;

    $boxes_weight = $package->boxes->sum('weight');
    $fee = (float) SiteSetting::where('name', 'storage_fee')->first()->value;

    $createdAt = Carbon::parse($package->created_at);
    $now = Carbon::now();
    $days_exceeded = $now->diffInDays($createdAt) - 75;
    $storage_days = $now->diffInDays($createdAt);

    if ($days_exceeded > 0) {
        $storage_fee = $fee * $boxes_weight * $days_exceeded;
    } else {
        $storage_fee = 0;
    }

    if ($days_exceeded > 0) {
        $storage_days_exceeded = $days_exceeded;
    }

    $package->update([
        'storage_fee' => (float) $storage_fee,
        'storage_days' => (float) $storage_days,
        'storage_days_exceeded' => (float) $storage_days_exceeded,
    ]);

    return true;
}

function shipping_service_markup($type)
{
    $percentage = 0;
    $service = ShippingService::where('service_code', $type)->first();
    if ($service) {
        $percentage = $service->markup_percentage;
    }

    return $percentage;
}

function commercialInvoiceForLabel($id)
{
    $package = Package::find($id);

    $ship_from = Address::find($package->ship_from);
    $ship_to = Address::find($package->ship_to);

    $package_weight = 0;
    if (isset($package->boxes)) {
        $package_weight = $package->boxes->sum('weight');
    }

    view()->share([
        'package' => $package,
        'package_weight' => $package_weight,
        'ship_from' => $ship_from,
        'ship_to' => $ship_to
    ]);

    $pdf = PDF::loadView('pdfs.commercial-invoice');
    $pdf->setPaper('A4', 'portrait');

    $filename = $package->id . '.pdf';
    Storage::disk('commercial-invoices')->put($filename, $pdf->output());
    return response()->download('storage/commercial-invoices/' . $filename);
}

function generateLabelFedex($id)
{
    $package = Package::where('id', $id)->first();
    $ship_from = Address::where('id', $package->ship_from)->first();
    $ship_to = Address::where('id', $package->ship_to)->first();

    $commodities = [];
    if ($package->pkg_ship_type == 'international') {
        $items = OrderItem::with('originCountry')->where('package_id', $package->id)->get();
        foreach ($items as $key => $item) {
            $commodities[] = [
                "description" => $item->description,
                "countryOfManufacture" => $item->originCountry->iso,
                "quantity" => $item->quantity,
                "quantityUnits" => "PCS",
                "unitPrice" => [
                    "amount" => $item->unit_price,
                    "currency" => "USD"
                ],
                "customsValue" => [
                    "amount" => $item->unit_price * $item->quantity,
                    "currency" => "USD"
                ],
                "weight" => [
                    "units" => "LB",
                    "value" => 0
                ]
            ];
        }
    }

    $requestedPackageLineItems = [];
    foreach ($package->boxes as $key => $box) {
        $requestedPackageLineItems[] = [
            "sequenceNumber" => ++$key,
            "weight" => [
                "units" => "LB",
                "value" => $box->weight
            ],
            "dimensions" => [
                "length" => $box->length,
                "width" => $box->width,
                "height" => $box->height,
                "units" => "IN"
            ]
        ];
    }

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

    $body = [
        "mergeLabelDocOption" => "LABELS_ONLY",
        "requestedShipment" => [
            "shipDatestamp" => Carbon::parse(Carbon::now())->format('Y-m-d'),
            "pickupType" => "USE_SCHEDULED_PICKUP",
            "serviceType" => $package->service_code,
            "packagingType" => "YOUR_PACKAGING",
            "shippingChargesPayment" => [
                "paymentType" => "SENDER"
            ],
            "shipper" => [
                "address" => [
                    "streetLines" => [
                        $ship_from->address,
                        $ship_from->address_2,
                        $ship_from->address_3
                    ],
                    "city" => $ship_from->city,
                    "stateOrProvinceCode" => $ship_from->state,
                    "postalCode" => $ship_from->zip_code,
                    "countryCode" => "US",
                    "residential" =>  $ship_from->is_residential
                ],
                "contact" => [
                    "personName" => $ship_from->fullname,
                    "emailAddress" => $ship_from->email,
                    "phoneExtension" => "",
                    "phoneNumber" => $ship_from->phone,
                    "companyName" => $ship_from->company_name,
                ]
            ],
            "recipients" => [
                [
                    "address" => [
                        "streetLines" => [
                            $ship_to->address,
                            $ship_to->address_2,
                            $ship_to->address_3
                        ],
                        "city" => $ship_to->city,
                        "stateOrProvinceCode" => $package->pkg_ship_type == 'domestic' ? $ship_to->state : NULL,
                        "postalCode" => $ship_to->zip_code,
                        "countryCode" => $ship_to->country->iso,
                        "residential" => $ship_to->is_residential
                    ],
                    "contact" => [
                        "personName" => $ship_to->fullname,
                        "emailAddress" => $ship_to->email,
                        // "phoneExtension" => "91",
                        "phoneNumber" => $ship_to->phone,
                        "companyName" => $ship_to->company_name
                    ]
                ]
            ],
            "requestedPackageLineItems" => $requestedPackageLineItems,
            "labelSpecification" => [
                "imageType" => "PDF",
                "labelStockType" => "PAPER_85X11_TOP_HALF_LABEL",
                "returnedDispositionDetail" => true,
                "customerSpecifiedDetail" => [
                    "maskedData" => [
                        "DUTIES_AND_TAXES_PAYOR_ACCOUNT_NUMBER",
                        "TRANSPORTATION_CHARGES_PAYOR_ACCOUNT_NUMBER"
                    ]
                ]
            ],
            "customsClearanceDetail" => $package->pkg_ship_type == 'international' ? [
                "isDocumentOnly" => true,
                "commodities" => $commodities,
                "dutiesPayment" => [
                    "paymentType" => "RECIPIENT"
                ],
                "exportDetail" => $package->shipping_total > 2500 ? [
                    "exportComplianceStatement" => 'AES ' . $package->itn
                ] : NULL
            ] : NULL
        ],
        "labelResponseOptions" => "LABEL",
        "accountNumber" => [
            "value" => "695684150"
        ],
        "shipAction" => "CONFIRM",
        "processingOptionType" => "ALLOW_ASYNCHRONOUS",
        // "oneLabelAtATime" => true
    ];

    $request = $client->post('https://apis.fedex.com/ship/v1/shipments', [
        'headers' => $headers,
        'body' => json_encode($body)
    ]);

    $response = $request->getBody()->getContents();
    $response = json_decode($response);

    $encoded_labels = $response->output->transactionShipments[0]->pieceResponses;

    if ($package->pkg_ship_type == 'international') {
        commercialInvoiceForLabel($package->id);
    }

    $oMerger = PDFMerger::init();
    $filename1 = $package->id;
    $count = 1;
    foreach ($encoded_labels as $key => $encoded_label) {
        $filename2 = $filename1 . '-' . $count . '.pdf';
        Storage::disk('fedex-labels')->put($filename2, base64_decode($encoded_label->packageDocuments[0]->encodedLabel));
        $oMerger->addPDF('storage/fedex-labels/' . $filename2, 'all');
        $count++;
    }

    if ($package->pkg_ship_type == 'international') {
        $oMerger->addPDF('storage/commercial-invoices/' . $filename1 . '.pdf', 'all');
    }

    $oMerger->merge();
    $label_url = 'storage/labels/' . $filename1 . '.pdf';
    $oMerger->save($label_url);

    // Master Tracking Number
    $master_tracking_no = $response->output->transactionShipments[0]->masterTrackingNumber;

    $package->update([
        'label_generated_at' => Carbon::now(),
        'label_generated_by' => auth()->id(),
        'label_url' => $label_url,
        'tracking_number_out' => $master_tracking_no
    ]);

    // DELETE ADDITIONAL FILES
    $count = 1;
    foreach ($package->boxes as $key => $box) {
        Storage::disk('commercial-invoices')->delete($box->package_id . '.pdf');
        Storage::disk('fedex-labels')->delete($box->package_id . '-' . $count . '.pdf');
        Storage::disk('labels')->delete($box->package_id . '-' . $count . '.pdf');
        $count++;
    }
}

function generateLabelUps($id)
{
    $package = Package::where('id', $id)->first();
    $ship_from = Address::where('id', $package->ship_from)->first();
    $ship_to = Address::where('id', $package->ship_to)->first();

    $curl = curl_init();
    $payload = "grant_type=client_credentials";

    curl_setopt_array($curl, [
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/x-www-form-urlencoded",
            "x-merchant-id: string",
            "Authorization: Basic " . base64_encode("rkdfbUA5bskZhKkbV7Nhk7tB0Y2wZYMpeiXEIf3W9r92wGBG:X9Gitfsn0A3p00aFPmg3gmE7xV1QRnIOIxygI1ouI6kHueJnHMfDCrwgWpB5na3y")
        ],
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_URL => "https://onlinetools.ups.com/security/v1/oauth/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
    ]);

    $authorization_response = curl_exec($curl);
    $authorization_response = json_decode($authorization_response);
    $access_token = $authorization_response->access_token;


    $package_boxes = [];
    foreach ($package->boxes as $key => $box) {
        $package_boxes[] = [
            "Description" => " ",
            "Packaging" => [
                "Code" => "02",
                "Description" => "Nails"
            ],
            "Dimensions" => [
                "UnitOfMeasurement" => [
                    "Code" => "IN",
                    "Description" => "Inches"
                ],
                "Length" => (string)  $box->length,
                "Width" =>  (string) $box->width,
                "Height" =>  (string) $box->height
            ],
            "PackageWeight" => [
                "UnitOfMeasurement" => [
                    "Code" => "LBS",
                    "Description" => "Pounds"
                ],
                "Weight" => (string) $box->weight
            ]
        ];
    }

    $body = [
        "ShipmentRequest" => [
            "Shipment" => [
                "Description" => "SELF_SHIP_LABEL",
                "Shipper" => [
                    "Name" => $ship_from->company_name,
                    "AttentionName" => $ship_from->fullname,
                    "ShipperNumber" => "WY2291",
                    "Phone" => [
                        "Number" => $ship_from->phone,
                        "Extension" => " "
                    ],
                    "Address" => [
                        "AddressLine" => [
                            $ship_from->address
                        ],
                        "City" => $ship_from->city,
                        "StateProvinceCode" => $ship_from->state,
                        "PostalCode" => $ship_from->zip_code,
                        "CountryCode" => $ship_from->country_code
                    ]
                ],
                "ShipTo" => [
                    "Name" => $ship_to->company_name,
                    "AttentionName" => $ship_to->fullname,
                    "Phone" => [
                        "Number" => $ship_to->phone
                    ],
                    "Address" => [
                        "AddressLine" => [
                            $ship_to->address,
                            $ship_to->address_2,
                            $ship_to->address_3,
                        ],
                        "City" => $ship_to->city,
                        "StateProvinceCode" => $package->pkg_ship_type == 'domestic' ? $ship_to->state : $ship_to->state,
                        "PostalCode" => $ship_to->zip_code,
                        "CountryCode" => $ship_to->country->iso
                    ],
                    "Residential" => $ship_to->is_residential
                ],
                "ShipFrom" => [
                    "Name" => $ship_from->fullname,
                    "AttentionName" => $ship_from->fullname,
                    "Phone" => [
                        "Number" => $ship_from->phone
                    ],
                    "FaxNumber" => NULL,
                    "Address" => [
                        "AddressLine" => [
                            $ship_from->address,
                            $ship_from->address_2,
                            $ship_from->address_3
                        ],
                        "City" => $ship_from->city,
                        "StateProvinceCode" => $ship_from->state,
                        "PostalCode" => $ship_from->zip_code,
                        "CountryCode" => $ship_from->country_code
                    ]
                ],
                "PaymentInformation" => [
                    "ShipmentCharge" => [
                        "Type" => "01",
                        "BillShipper" => [
                            "AccountNumber" => "WY2291"
                        ]
                    ]
                ],
                "Service" => [
                    "Code" => $package->service_code,
                    "Description" => $package->service_label
                ],
                "Package" => $package_boxes,
                "InvoiceLineTotal" => [
                    "MonetaryValue" => "10",
                    "CurrencyCode" => "USD"
                ]
            ],
            "LabelSpecification" => [
                "LabelImageFormat" => [
                    "Code" => "PNG",
                    "Description" => "PNG"
                ],
                "HTTPUserAgent" => "Mozilla/4.5"
            ]
        ]
    ];

    curl_setopt_array($curl, [
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json",
            "transId: string",
            "transactionSrc: testing"
        ],
        CURLOPT_POSTFIELDS => json_encode($body),
        CURLOPT_URL => "https://onlinetools.ups.com/api/shipments/v1/ship",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
    ]);

    $response = curl_exec($curl);
    $response = json_decode($response);
    $results = $response->ShipmentResponse->ShipmentResults->PackageResults;

    if ($package->pkg_ship_type == 'international') {
        commercialInvoiceForLabel($package->id);
    }

    $oMerger = PDFMerger::init();
    $filename1 = $package->id;
    $count = 1;
    // foreach ($results as $key => $result) {
    // return $result;
    $filename2 = $filename1 . '-' . $count . '.png';
    Storage::disk('labels')->put($filename2, base64_decode($results->ShippingLabel->GraphicImage));

    $pdf = PDF::loadView('pdfs.label', ['imagePath' => 'storage/labels/' . $filename2]);
    $pdf->setPaper('A4', 'portrait');

    $filename2_pdf = $filename1 . '-' . $count . '.pdf';
    Storage::disk('ups-labels')->put($filename2_pdf, $pdf->output());
    response()->download('storage/ups-labels/' . $filename2_pdf);
    $oMerger->addPDF('storage/ups-labels/' . $filename2_pdf, 'all');
    $count++;
    // }

    if ($package->pkg_ship_type == 'international') {
        $oMerger->addPDF('storage/commercial-invoices/' . $filename1 . '.pdf', 'all');
    }

    $oMerger->merge();
    $label_url = 'storage/labels/' . $filename1 . '.pdf';
    $oMerger->save($label_url);

    // Master Tracking Number
    $master_tracking_no = $response->ShipmentResponse->ShipmentResults->PackageResults->TrackingNumber;

    $package->update([
        'label_generated_at' => Carbon::now(),
        'label_generated_by' => auth()->id(),
        'label_url' => $label_url,
        'tracking_number_out' => $master_tracking_no,
    ]);


    // DELETE ADDITIONAL FILES
    $count = 1;
    foreach ($package->boxes as $key => $box) {
        Storage::disk('commercial-invoices')->delete($box->package_id . '.pdf');
        Storage::disk('ups-labels')->delete($box->package_id . '-' . $count . '.pdf');
        Storage::disk('labels')->delete($box->package_id . '-' . $count . '.png');
        $count++;
    }
}

function generateLabelDhl($id)
{
    $package = Package::where('id', $id)->first();
    $ship_from = Address::where('id', $package->ship_from)->first();
    $ship_to = Address::where('id', $package->ship_to)->first();

    $client = new Client();

    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Basic YXBHN3RWNGNSMWFVOGQ6Wl40c0ckMXlSQDZ4VSM5Yw=='
    ];

    $line_items = [];
    $order_items = OrderItem::with('originCountry')->where('package_id', $package->id)->get();
    $count = 1;
    foreach ($order_items as $key => $oitem) {
        $line_items[] = [
            "number" => $count,
            "description" => $oitem->description,
            "price" => $oitem->unit_price,
            "priceCurrency" => "USD",
            "manufacturerCountry" => "US",
            "weight" => [
                "netValue" => 1,
                "grossValue" => 1
            ],
            "quantity" => [
                "value" => $oitem->quantity,
                "unitOfMeasurement" => "EA"
            ],
            "commodityCodes" => [
                [
                    "typeCode" => "outbound",
                    "value" => "4204.00"
                ]
            ]
        ];

        $count++;
    }

    $package_boxes = [];
    foreach ($package->boxes as $key => $box) {
        $package_boxes[] =    [
            "description" => 'Items',
            "weight" => $box->weight,
            "dimensions" => [
                "length" => $box->length,
                "width" => $box->width,
                "height" => $box->height
            ]
        ];
    }

    $shipment_date = Carbon::now();
    $shipment_date = $shipment_date->addDays(1);
    $shipment_date = $shipment_date->format('Y-m-d');

    $shipper_postal_address = [
        "postalCode" => $ship_from->zip_code,
        "cityName" => $ship_from->city,
        "countryCode" => $ship_from->country_code,
        "provinceCode" => $ship_from->state,
        "addressLine1" => $ship_from->address,
    ];

    if ($ship_from->address_2) {
        $shipper_postal_address += [
            "addressLine2" => $ship_from->address_2,
        ];
    }

    if ($ship_from->address_3) {
        $shipper_postal_address += [
            "addressLine3" => $ship_from->address_3,
        ];
    }


    $receiver_postal_address = [
        "postalCode" => $ship_to->zip_code,
        "cityName" => $ship_to->city,
        "countryCode" => $ship_to->country->iso,
        "addressLine1" =>  $ship_to->address,
    ];


    if ($ship_to->address_2) {
        $receiver_postal_address += [
            "addressLine2" => $ship_to->address_2,
        ];
    }

    if ($ship_to->address_3) {
        $receiver_postal_address += [
            "addressLine3" => $ship_to->address_3,
        ];
    }

    $body = [
        "plannedShippingDateAndTime" => $shipment_date . "T11:00:00GMT-08:00",
        "productCode" => "P",
        "customerDetails" => [
            "shipperDetails" => [
                "postalAddress" => $shipper_postal_address,
                "contactInformation" => [
                    "email" => $ship_from->email,
                    "phone" => $ship_from->phone,
                    "companyName" => $ship_from->company_name,
                    "fullName" => $ship_from->fullname
                ]
            ],
            "receiverDetails" => [
                "postalAddress" => $receiver_postal_address,
                "contactInformation" => [
                    "email" => $ship_to->email,
                    "phone" =>  $ship_to->phone,
                    "companyName" => $ship_to->company_name,
                    "fullName" =>  $ship_to->fullname,
                ]
            ]
        ],
        "content" => [
            "isCustomsDeclarable" => true,
            "description" => "Items",
            "declaredValue" => 14,
            "declaredValueCurrency" => "USD",
            "incoterm" => "DAP",
            "unitOfMeasurement" => "imperial",
            "packages" => $package_boxes,
            "exportDeclaration" => [
                "invoice" => [
                    "number" => "1",
                    "date" => "2023-09-29"
                ],
                "lineItems" => $line_items
            ]
        ],
        "pickup" => [
            "isRequested" => false
        ],
        "getRateEstimates" => false,
        "accounts" => [
            [
                "typeCode" => "shipper",
                "number" => "849192247"
            ]
        ],
        // "valueAddedServices" => [
        //     [
        //         "serviceCode" => "WY"
        //     ]
        // ],
        "outputImageProperties" => [
            "printerDPI" => 300,
            "encodingFormat" => "pdf",
            "imageOptions" => [
                [
                    "typeCode" => "invoice",
                    "templateName" => "COMMERCIAL_INVOICE_P_10",
                    "isRequested" => true,
                    "invoiceType" => "commercial",
                    "languageCode" => "eng"
                ]
            ],
            "splitTransportAndWaybillDocLabels" => false,
            "allDocumentsInOneImage" => false,
            "splitDocumentsByPages" => false,
            "splitInvoiceAndReceipt" => true
        ],
        "customerReferences" => [
            [
                "value" => "Customer reference",
                "typeCode" => "CU"
            ]
        ],
        "requestOndemandDeliveryURL" => false,
        "getOptionalInformation" => false
    ];

    $request = $client->post('https://express.api.dhl.com/mydhlapi/shipments', [
        'headers' => $headers,
        'body' => json_encode($body)
    ]);

    $response = $request->getBody()->getContents();
    $response = json_decode($response);

    $results = $response->documents;

    commercialInvoiceForLabel($package->id);
    $oMerger = PDFMerger::init();
    $filename1 = $package->id;
    $count = 1;
    foreach ($results as $key => $result) {
        $filename2 = $filename1 . '-' . $count . '.pdf';
        Storage::disk('dhl-labels')->put($filename2, base64_decode($result->content));
        $oMerger->addPDF('storage/dhl-labels/' . $filename2, 'all');
        $count++;
    }

    $oMerger->addPDF('storage/commercial-invoices/' . $filename1 . '.pdf', 'all');
    $oMerger->merge();
    $label_url = 'storage/labels/' . $filename1 . '.pdf';
    $oMerger->save($label_url);

    $package->update([
        'label_generated_at' => Carbon::now(),
        'label_generated_by' => auth()->id(),
        'label_url' => $label_url,
    ]);

    // DELETE ADDITIONAL FILES
    $count = 1;
    foreach ($package->boxes as $key => $box) {
        Storage::disk('commercial-invoices')->delete($box->package_id . '.pdf');
        Storage::disk('dhl-labels')->delete($box->package_id . '-' . $count . '.pdf');
        Storage::disk('labels')->delete($box->package_id . '-' . $count . '.pdf');
        $count++;
    }
}

function paymentInvoiceForLabel($id)
{
    $payment = Payment::find($id);
    $package = Package::where('id', $payment->payment_module_id)->first();
    $ship_from = Address::find($package->ship_from);
    $ship_to = Address::find($package->ship_to);

    view()->share([
        'payment' => $payment,
        'package' => $package,
        'ship_from' => $ship_from,
        'ship_to' => $ship_to
    ]);

    $pdf = PDF::loadView('pdfs.payment-invoice');
    $pdf->setPaper('A4', 'portrait');

    $filename = $payment->id . '.pdf';
    Storage::disk('payment-invoices')->put($filename, $pdf->output());
    return response()->download('storage/payment-invoices/' . $filename);
}
