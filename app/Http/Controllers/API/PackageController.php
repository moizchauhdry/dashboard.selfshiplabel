<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Address;
use App\Models\Country;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PackageBox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PackageController extends BaseController
{
    public function index(Request $request)
    {
        $package_status_id = $request->package_status_id;

        $user = Auth::user();

        $packages = Package::query()
            ->with('boxes', 'payments', 'shipFrom', 'shipTo')
            ->where('project_id', 1)
            ->where('customer_id', $user->id)
            ->when($package_status_id, function ($query) use ($package_status_id) {
                $query->whereIn('package_status_id', $package_status_id);
            })
            ->orderBy('id', 'desc')
            ->paginate(100);

        $data['packages'] = $packages;

        return $this->sendResponse($data, 'success');
    }

    // public function createPackage()
    // {
    //     $customer = Auth::user();

    //     $package = Package::query()
    //         ->where('customer_id', $customer->id)
    //         ->where('package_status_id', 1)
    //         ->first();

    //     if ($package == NULL) {
    //         $data = [
    //             'customer_id' => $customer->id,
    //             'status' => 'open',
    //             'pkg_type' => 'single',
    //             'warehouse_id' => 1,
    //             'currency' => "USD",
    //             'pkg_dim_status' => "done",
    //             'project_id' => 1,
    //             'cart' => 1,
    //             'package_status_id' => 1, // Create Shipment
    //         ];

    //         Package::create($data);
    //     }

    //     return $this->sendResponse($data, 'success');
    // }

    public function setRate(Request $request)
    {
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
            'grand_total' => $request->rate['total'],
            'currency' => "USD",
            'pkg_dim_status' => "done",
            'project_id' => 1,
            'cart' => true,
            // 'ship_to' => NULL,
            'insurance_amount' => $request->insurance_amount,
        ];

        $package = Package::updateOrCreate(['id' => $request->package_id, 'customer_id' => $user->id, 'cart' => 1], $data);

        PackageBox::where('package_id', $package->id)->delete();
        foreach ($request->dimensions as $key => $dimension) {
            PackageBox::create([
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

    public function getPackage($id)
    {
        $package = Package::query()
            ->where('id', $id)
            ->with('shipTo', 'shipFrom', 'boxes', 'packageItems')
            ->where('customer_id', auth()->id())
            ->first();

        $data['package'] = $package;

        return $this->sendResponse($data, 'success');
    }

    public function setAddress(Request $request)
    {
        try {
            $customer = Auth::user();

            $package = Package::query()
                ->where('id', $request->package_id)
                ->where('customer_id', $customer->id)
                ->where('package_status_id', 1)
                ->orderBy('id', 'desc')
                ->first();

            if ($package == NULL) {
                $data = [
                    'customer_id' => $customer->id,
                    'status' => 'open',
                    'pkg_type' => 'single',
                    'warehouse_id' => 1,
                    'currency' => "USD",
                    'pkg_dim_status' => "done",
                    'project_id' => 1,
                    'cart' => 1,
                    'package_status_id' => 1,
                ];

                $package = Package::create($data);
            }

            if ($request->type == 'ship_from') {
                $package->update(['ship_from' => $request->id]);
            }

            if ($request->type == 'ship_to') {
                $package->update(['ship_to' => $request->id]);
            }

            $ship_from = Address::find($package->ship_from);
            $ship_to = Address::find($package->ship_to);

            if ($package->ship_from && $package->ship_to) {
                if ($ship_from->country_id == $ship_to->country_id) {
                    $package->update(['pkg_ship_type' => 'domestic']);
                } else {
                    $package->update(['pkg_ship_type' => 'international']);
                }
            }

            return $this->sendResponse([
                'package_id' => $package->id
            ], 'success');
        } catch (\Throwable $th) {
            $package->update(['ship_to' => NULL]);
            return $this->error($th->getMessage());
        }
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
                'itn' => [Rule::requiredIf($request->shipping_total > 2500)],
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

            // $grand_total =  $package->shipping_charges;

            $package->update([
                'custom_form_status' => true,
                'status' => "filled",
                'package_type' => $request->package_type,
                'shipping_total' => $request->shipping_total, // Note: shipping total is actually customs value
                // 'grand_total' => $grand_total,
                'itn' => $request->itn,
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

            // if ($package->carrier_code == 'fedex') {
            //     $data['fedex_label'] = generateLabelFedex($package->id);
            // }

            // if ($package->carrier_code == 'ups') {
            //     $data['ups_label'] = generateLabelUps($package->id);
            // }

            // if ($package->carrier_code == 'dhl') {
            //     $data['dhl_label'] = generateLabelDhl($package->id);
            // }

            $data = [];
            return $this->sendResponse($data, 'The custom decration form filled successfully.');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

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

    public function createPackageForExternal(Request $request)
    {
        $ship_from_country = Country::where('iso', $request->ship_from['country'])->first();
        if (!$ship_from_country) {
            return $this->error('The ship from country code is invalid');
        }

        $ship_to_country = Country::where('iso', $request->ship_to['country'])->first();
        if (!$ship_to_country) {
            return $this->error('The ship to country code is invalid');
        }

        $rules = [
            'ship_date' => 'required',
            'carrier_code' => 'required',
            'service_code' => 'required',
            'itn' => 'nullable',
            'insurance_amount' => 'nullable',

            'ship_from.company' => 'nullable|max:100',
            'ship_from.name' => 'required|regex:/^[A-Za-z0-9\s]+$/',
            'ship_from.residential' => 'required|boolean',
            'ship_from.country' => 'required',
            'ship_from.city' => 'required|regex:/^[A-Za-z0-9\s]+$/|',
            'ship_from.zip' => 'required|regex:/^[A-Za-z0-9\s]+$/|',
            'ship_from.state' => 'required',
            'ship_from.email' => 'required|email|string',
            'ship_from.phone' => 'required',
            'ship_from.address1' => 'required|string|max:35|regex:/^[A-Za-z0-9\s]+$/',
            'ship_from.address2' => 'nullable|string|max:35',
            'ship_from.address3' => 'nullable|string|max:35',
            'ship_from.tax_no' => 'nullable|max:100',
            'ship_from.signature_type_id' => 'required',

            'ship_to.company' => 'nullable|max:100',
            'ship_to.name' => 'required|regex:/^[A-Za-z0-9\s]+$/',
            'ship_to.residential' => 'required|boolean',
            'ship_to.country' => 'required',
            'ship_to.city' => 'required|regex:/^[A-Za-z0-9\s]+$/|',
            'ship_to.zip' => 'required|regex:/^[A-Za-z0-9\s]+$/|',
            'ship_to.state' => 'required',
            'ship_to.email' => 'required|email|string',
            'ship_to.phone' => 'required',
            'ship_to.address1' => 'required|string|max:35|regex:/^[A-Za-z0-9\s]+$/',
            'ship_to.address2' => 'nullable|string|max:35',
            'ship_to.address3' => 'nullable|string|max:35',
            'ship_to.tax_no' => 'nullable|max:100',
            'ship_to.signature_type_id' => 'required',

            'dimensions' => 'required|array',
            'dimensions.*.weight' => 'required',
            'dimensions.*.length' => 'required',
            'dimensions.*.width' => 'required',
            'dimensions.*.height' => 'required',
        ];

        if ($ship_from_country != $ship_to_country) {
            $rules += [
                'items' => 'required|array',
                'items.*.description' => 'required',
                'items.*.quantity' => 'required|gt:0',
                'items.*.unit_price' => 'required|gt:0|numeric',
                'items.*.origin_country' => 'required',
                'items.*.batteries' => 'nullable',
                'items.*.hs_code' => 'nullable',
            ];
        }

        $messages = [
            'regex' => 'The :attribute must only contain letters (english) and numbers.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError('Validation Failed!', $validator->errors());
        }

        try {
            DB::beginTransaction();

            $ship_from_data = [
                'user_id' => 3,
                'type' => 'ship_from',
                'company_name' => $request->ship_from['company'],
                'fullname' => $request->ship_from['name'],
                'country_id' => $ship_from_country->id,
                'country_code' => $request->ship_from['country'],
                'state' => $request->ship_from['state'],
                'city' => $request->ship_from['city'],
                'zip_code' => $request->ship_from['zip'],
                'phone' => $request->ship_from['phone'],
                'email' => $request->ship_from['email'],
                'address' => $request->ship_from['address1'],
                'address_2' => $request->ship_from['address2'],
                'address_3' =>  $request->ship_from['address3'],
                'is_residential' => $request->ship_from['residential'],
                'tax_no' => $request->ship_from['tax_no'],
                'signature_type_id' => $request->ship_from['signature_type_id']
            ];
            $ship_from_address = Address::create($ship_from_data);

            $ship_to_data = [
                'user_id' => 3,
                'type' => 'ship_to',
                'company_name' => $request->ship_to['company'],
                'fullname' => $request->ship_to['name'],
                'country_id' => $ship_to_country->id,
                'country_code' => $request->ship_to['country'],
                'state' => $request->ship_to['state'],
                'city' => $request->ship_to['city'],
                'zip_code' => $request->ship_to['zip'],
                'phone' => $request->ship_to['phone'],
                'email' => $request->ship_to['email'],
                'address' => $request->ship_to['address1'],
                'address_2' => $request->ship_to['address2'],
                'address_3' =>  $request->ship_to['address3'],
                'is_residential' => $request->ship_to['residential'],
                'tax_no' => $request->ship_to['tax_no'],
                'signature_type_id' => $request->ship_to['signature_type_id'],
            ];
            $ship_to_address = Address::create($ship_to_data);

            $package_data = [
                'customer_id' => 3,
                'status' => 'open',
                'pkg_type' => 'single',
                'warehouse_id' => 1,
                'carrier_code' => $request->carrier_code,
                'service_code' => $request->service_code,
                'package_type_code' => "YOUR_PACKAGING",
                'service_label' => "TEST",
                'markup_fee' => 0,
                'shipping_charges' => 0,
                'grand_total' => 0,
                'currency' => "USD",
                'pkg_dim_status' => "done",
                'project_id' => 2,
                'cart' => true,
                'ship_to' => NULL,
                'insurance_amount' => $request->insurance_amount,
                'itn' => $request->itn,
                'ship_from' => $ship_from_address->id,
                'ship_to' => $ship_to_address->id,
            ];

            $package = Package::create($package_data);

            if ($ship_from_country->id == $ship_to_country->id) {
                $package->update(['pkg_ship_type' => 'domestic']);
            } else {
                $package->update(['pkg_ship_type' => 'international']);
            }

            PackageBox::where('package_id', $package->id)->delete();
            foreach ($request->dimensions as $key => $dimension) {
                PackageBox::create([
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

            if ($package->pkg_ship_type == 'international') {
                OrderItem::where('package_id', $package->id)->delete();
                foreach ($request->items as $key => $item) {
                    $origin_country = Country::where('iso', $item['origin_country'])->first();
                    $order_item = new OrderItem();
                    $order_item->package_id = $package->id;
                    $order_item->origin_country = $origin_country->id;
                    $order_item->hs_code = isset($item['hs_code']) ? $item['hs_code'] : NULL;
                    $order_item->description = $item['description'];
                    $order_item->quantity = $item['quantity'];
                    $order_item->unit_price = $item['unit_price'];
                    $order_item->sub_total = $item['unit_price'] * $item['quantity'];
                    $order_item->batteries = isset($item['batteries']) ? $item['batteries'] : NULL;
                    $order_item->save();
                }

                $custom_items = OrderItem::where('package_id', $package->id)->get();
                if ($custom_items) {
                    $custom_total = $custom_items->sum('sub_total');
                    if ($custom_total > 0) {
                        $package->update([
                            'shipping_total' => $custom_total
                        ]);
                    }
                }
            }

            if ($package->carrier_code == 'fedex') {
                $fedex = generateLabelFedex($package->id, 2); // Package ID, Project ID
                $data['fedex_label'] = [
                    'label_url' => config('app.url') . '/' . $fedex['label_url'],
                    'package_id' => $fedex['id'],
                    'grand_total' => $fedex['grand_total']
                ];
            }

            if ($package->carrier_code == 'ups') {
                $ups = generateLabelUps($package->id, 2);  // Package ID, Project ID
                $data['ups_label'] = [
                    'label_url' => config('app.url') . '/' . $ups['label_url'],
                    'package_id' => $ups['id'],
                    'grand_total' => $ups['grand_total']
                ];
            }

            if ($package->carrier_code == 'dhl') {
                $dhl = generateLabelDhl($package->id, 2);  // Package ID, Project ID
                $data['dhl_label'] = [
                    'label_url' => config('app.url') . '/' . $dhl['label_url'],
                    'package_id' => $dhl['id'],
                    'grand_total' => $dhl['grand_total']
                ];
            }

            if ($package->carrier_code == 'usps') {
                $usps = generateLabelUsps($package->id, 2);  // Package ID, Project ID
                dd($usps);
                // $data['usps_label'] = [
                //     'label_url' => config('app.url') . '/' . $usps['label_url'],
                //     'package_id' => $usps['id'],
                //     'grand_total' => $usps['grand_total']
                // ];
            }

            DB::commit();
            return $this->sendResponse($data, 'SUCCESS');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage());
        }
    }

    public function processLabel(Request $request)
    {
        dd($request->all());
        // Get label metadata and label image from the request
        $labelMetadata = json_decode($request->input('labelMetadata'), true);
        $labelImage = $request->file('labelImage');

        // Extract relevant information from label metadata
        $trackingNumber = $labelMetadata['trackingNumber'];
        $commitmentDate = $labelMetadata['commitment']['scheduleDeliveryDate'];

        // Save the label image to a folder in your Laravel app
        // You may need to adjust the path and file name according to your requirements
        $labelImagePath = 'path/to/save/' . $trackingNumber . '_' . $commitmentDate . '.pdf';
        $labelImage->move(public_path('labels'), $labelImagePath);

        // Process the label image further if needed
        // For example, you can generate a download link or display it in your application

        return response()->json(['message' => 'Label image processed successfully']);
    }

    public function updateSignature(Request $request)
    {
        $package = Package::where('id', $request->package_id)->first();

        if ($package) {
            $package->update(['signature_type_id' => $request->signature_type_id]);
        }

        $data = [
            'signature_type_id' => $package->signature_type_id
        ];

        return $this->sendResponse($data, 'The signature updated successfully.');
    }

    public function saveAsDraft(Request $request)
    {
        $package = Package::where('id', $request->package_id)->first();
        $package->update(['package_status_id' => 2]);
        return $this->sendResponse([], 'The package saved as draft successfully.');
    }

    public function payLater(Request $request)
    {
        $package = Package::where('id', $request->package_id)->first();
        $package->update(['package_status_id' => 3]);
        return $this->sendResponse([], 'The package saved as draft successfully.');
    }

    public function editPackage(Request $request)
    {
        $package = Package::where('id', $request->package_id)->first();
        $package->update(['package_status_id' => 1]);
        return $this->sendResponse([
            'package_id' => $package->id
        ], 'The package edit successfully.');
    }

    public function reship(Request $request)
    {
        $existing_package = Package::query()
            ->with(['boxes', 'shipTo', 'shipFrom'])
            ->where('id', $request->package_id)
            ->first();

        if ($existing_package) {
            $new_package = $existing_package->replicate();
            $new_package->save();

            if ($existing_package->shipTo) {
                $newShipTo = $existing_package->shipTo->replicate();
                $newShipTo->save();
                $new_package->ship_to = $newShipTo->id;
            }

            if ($existing_package->shipFrom) {
                $newShipFrom = $existing_package->shipFrom->replicate();
                $newShipFrom->save();
                $new_package->ship_from = $newShipFrom->id;
            }

            $new_package->save();

            foreach ($existing_package->boxes as $box) {
                $newBox = $box->replicate();
                $newBox->package_id = $new_package->id;
                $newBox->save();
            }
        }

        return $this->sendResponse([
            'package_id' => $new_package->id
        ], 'The package edit successfully.');
    }
}
