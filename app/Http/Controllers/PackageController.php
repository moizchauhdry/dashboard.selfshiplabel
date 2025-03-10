<?php

namespace App\Http\Controllers;

use App\Events\CustomFormFilled;
use App\Events\PackageConsolidated;
use App\Events\PackageShippingServiceSelected;
use App\Events\ServiceRequestedEvent;
use App\Events\ServiceRequestUpdatedEvent;
use App\Models\Address;
use Illuminate\Http\Request;
use Inertia\Inertia;
use GuzzleHttp\Client;
use App\Models\Order;
use App\Models\Package;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\CouponPackage;
use App\Models\OrderItem;
use App\Models\PackageBox;
use App\Models\PackageFile;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\Shipping;
use App\Models\Warehouse;
use App\Notifications\CustomerPackageRequestNotification;
use App\Notifications\ReturnPackageNotification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use PDF;

class PackageController extends Controller
{
    // private function calculate_storage_fee($id)
    // {
    //     $package = Package::find($id);
    //     calulate_storage($package);
    // }

    public function index(Request $request)
    {
        $suit_no = $request->suit_no;

        $query = Package::with('customer', 'boxes')
            ->when(Auth::user()->type == 'customer', function ($qry) {
                $qry->where('customer_id', Auth::user()->id);
            })
            ->when($request->pkg_id && !empty($request->pkg_id), function ($qry) use ($request) {
                $qry->where('id', $request->pkg_id);
            })
            ->when($suit_no && !empty($suit_no), function ($qry) use ($suit_no) {
                $qry->where('customer_id', $suit_no);
            })
            ->when($request->payment_status && !empty($request->payment_status), function ($qry) use ($request) {
                $qry->where('payment_status', $request->payment_status);
            })
            ->when($request->tracking_out && !empty($request->tracking_out), function ($qry) use ($request) {
                $qry->where('tracking_number_out', $request->tracking_out);
            })
            ->when($request->date_range && !empty($request->date_range), function ($qry) use ($request) {
                $range = explode(' - ', $request->date_range);
                $from = date("Y-m-d", strtotime($range[0]));
                $to = date("Y-m-d", strtotime($range[1]));
                $qry->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
            });

        $packages_count = $query->count();
        $packages = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        $open_pkgs_count = Package::where('status', 'open')->where('pkg_type', 'single')->count();
        $projects = Project::active()->get();

        return Inertia::render('Packages/Index', [
            'pkgs' => $packages,
            'open_pkgs_count' => $open_pkgs_count,
            'packages_count' => $packages_count,
            'projects' => $projects,
            'filters' => [
                'pkg_id' => $request->pkg_id ?? "",
                'suit_no' => $request->suit_no ?? "",
                'payment_status' => $request->payment_status ?? "",
                'date_range' => $request->date_range ?? "",
                'tracking_out' => $request->tracking_out ?? "",
            ]
        ]);
    }

    public function show($id)
    {
        $package = Package::query()
            ->select(
                'u.name as u_name',
                'packages.id as pkg_id',
                'packages.customer_id as pkg_customer_id',
                'packages.label_url as pkg_label_url',
                'packages.tracking_number_out as pkg_tracking_out',

                'pb.weight as pb_weight',
                'pb.weight_unit as pb_weight_unit',
                'pb.length as pb_length',
                'pb.width as pb_width',
                'pb.height as pb_height',
                'pb.dim_unit as pb_dim_unit',

                'ship_from.company_name as from_company',
                'ship_from.fullname as from_name',
                'ship_from.address as from_address',
                'ship_from.address_2 as from_address_2',
                'ship_from.address_3 as from_address_3',
                'ship_from.city as from_city',
                'ship_from.state as from_state',
                'ship_from.country_code as from_country_code',
                'ship_from.zip_code as from_zip_code',
                'ship_from.phone as from_phone',
                'ship_from.email as from_email',

                'ship_to.company_name as to_company',
                'ship_to.fullname as to_name',
                'ship_to.address as to_address',
                'ship_to.address_2 as to_address_2',
                'ship_to.address_3 as to_address_3',
                'ship_to.city as to_city',
                'ship_to.state as to_state',
                'ship_to.country_code as to_country_code',
                'ship_to.zip_code as to_zip_code',
                'ship_to.phone as to_phone',
                'ship_to.email as to_email',
            )
            ->leftJoin('package_boxes as pb', 'pb.package_id', 'packages.id')
            ->join('users as u', 'u.id', 'packages.customer_id')
            ->leftJoin('addresses as ship_from', 'ship_from.id', 'packages.ship_from')
            ->leftJoin('addresses as ship_to', 'ship_to.id', 'packages.ship_to')
            ->find($id);

        $payments  = Payment::where('payment_module', 'package')->where('payment_module_id', $package->pkg_id)->get();
        $package_files  = PackageFile::where('package_id', $package->pkg_id)->get();

        return Inertia::render('Packages/Show', [
            'record' => $package,
            'payments' => $payments,
            'package_files' => $package_files,
        ]);
    }

    public function custom($id, $mode = NULL)
    {
        $packag = Package::with('orders', 'warehouse', 'customer', 'packageItems', 'address')
            ->when(Auth::user()->type == 'customer', function ($qry) {
                $qry->where('customer_id', Auth::user()->id);
            })->findOrFail($id);

        $user_id = Auth::user()->id;
        $addresses = Address::where('user_id', $user_id)->get();
        $warehouse = $packag->warehouse;

        $package_items = [];
        foreach ($packag->packageItems as $pkg_item) {
            $package_items[] = [
                'id' => $pkg_item->id,
                'hs_code' => $pkg_item->hs_code,
                'description' => $pkg_item->description,
                'quantity' => $pkg_item->quantity,
                'price' => $pkg_item->unit_price,
                'origin_country' => $pkg_item->origin_country,
                'batteries' => $pkg_item->batteries,
            ];
        }

        $tracking_numbers = [];
        foreach ($packag->orders as $order) {
            $tracking_numbers[] = $order->tracking_number_in;
        }


        $address_book = [];
        $selected_address = '';
        $address_book_id = 0;

        foreach ($addresses as $address) {

            $full_address = $address->fullname . " " . $address->address . "<br>" .
                $address->city . " " . $address->state . " " . $address->zip_code . " " . $address->country->nicename . "<br>" .
                $address->phone;

            if ($selected_address == '') {
                $selected_address = $full_address;
                $address_book_id = $address->id;
            }

            $address_book[$address->id] = [
                'id' => $address->id,
                'label' => $address->fullname . ", " . $address->city . ", " . $address->state . ", " . $address->zip_code,
                'full_address' => $full_address
            ];
        }

        $countries = Country::all(['id', 'nicename as name'])->toArray();

        return Inertia::render('Packages/Customs/Create', [
            'countries' => $countries,
            'package_items' => $package_items,
            'address_book' => $address_book,
            'address_book_id' => $address_book_id,
            'selected_address' => $selected_address,
            'packag' => $packag,
            'warehouse' => $warehouse,
            'tracking_numbers' => $tracking_numbers,
            'package_date' => date('Y-m-d'),
            'mode' => $mode,
        ]);
    }

    public function store(Request $request)
    {
        $package = Package::with('order', 'packageItems')->where('id', $request->package_id)->first();

        $validated = $request->validate([
            'package_items.*.description' => 'required',
            'package_items.*.quantity' => 'required',
            'package_items.*.price' => 'required|gt:0|numeric',
            'package_items.*.origin_country' => 'required',
            'package_items.*.batteries' => 'nullable',
            'package_items.*.hs_code' => 'nullable',
            'shipping_total' => 'required',
            'package_type' => 'required',
            'special_instructions' => 'nullable',
        ], [
            'package_items.*.description.required' => 'The package items description field is required.',
            'package_items.*.quantity.required' => 'The package items quantity field is required.',
            'package_items.*.price.required' => 'The package items price field is required.',
            'package_items.*.price.gt' => 'The package items price must be greater than 0.',
            'package_items.*.origin_country.required' => 'The package items origin country field is required.',
        ]);

        $package->update([
            'status' => 'filled',
            'custom_form_status' => true,
            'shipping_total' => $validated['shipping_total'],
            'package_type' => $validated['package_type'],
            'special_instructions' => $request->special_instructions,
        ]);

        OrderItem::where('package_id', $package->id)->delete();
        foreach ($request->package_items as $key => $pkg_item) {
            $order_item = new OrderItem();
            $order_item->package_id = $package->id;
            $order_item->hs_code = $pkg_item['hs_code'] ?? null;
            $order_item->description = $pkg_item['description'];
            $order_item->quantity = $pkg_item['quantity'];
            $order_item->unit_price = $pkg_item['price'];
            $order_item->origin_country = $pkg_item['origin_country'];
            $order_item->batteries = $pkg_item['batteries'] ?? null;
            $order_item->save();
        }

        event(new CustomFormFilled($package));

        return redirect()->route('packages.show', $package->id)->with('success', 'The custom decration form filled successfully.');
    }

    public function edit($id)
    {

        $items = [];

        $user_id = Auth::user()->id;

        $order = Order::with(['items', 'images'])->find($id);
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'id' => $item->id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'value' => $item->unit_price,
                'origin_country' => $item->origin_country,
                'batteries' => $item->batteries,
            ];
        }

        $addresses = Address::where('user_id', $user_id)->get();

        $address_book = [];

        $first_address = '';
        $address_book_id = 0;

        foreach ($addresses as $address) {

            $full_address = $address->fullname . " " . $address->address . "<br>" . $address->city . " " . $address->state . " " . $address->country . "<br>" . $address->phone;
            if ($first_address == '') {
                $first_address = $full_address;
                $address_book_id = $address->id;
            }

            $address_book[$address->id] = [
                'id' => $address->id,
                'label' => $address->fullname . " " . $address->city . " " . $address->state,
                'full_address' => $full_address
            ];
        }

        $countries = Country::all(['id', 'nicename as name'])->toArray();

        return Inertia::render('Packages/EditPackage', [
            'countries' => $countries,
            'items' => $items,
            'address_book' => $address_book,
            'address_book_id' => $address_book_id,
            'first_address' => $first_address,
            'order' => $order
        ]);
    }

    public function update(Request $request)
    {

        return false;

        $id = $request->input('id');

        $package = Order::find($id);

        $validated = $request->validate([
            'address_book_id' => 'required'
        ]);

        $package->address_book_id = $validated['address_book_id'];


        $package->update();
        $items = $request->input('items');

        $files = $request->file();

        $batteries = [
            0 => 'No Batteries',
            1 => 'Simple Batteries (Shipped on on Fedex)',
            2 => 'Batteries Packaed with Equipment',
            3 => 'Batteries Contained in Equipment'
        ];


        foreach ($items as $key => $item) {

            $item_id = isset($item['id']) ? (int) $item['id'] : 0;

            $order_item = OrderItem::find($item_id);
            //update if existing, else creat new. 

            if (!is_object($order_item)) {
                $order_item = new OrderItem();
            }

            $order_item->name = $item['description'];
            $order_item->description = $item['description'];
            $order_item->quantity = $item['quantity'];
            $order_item->unit_price = $item['value'];
            $order_item->origin_country = $item['origin_country'];
            $order_item->batteries = $item['batteries'];

            $order_item->save();
        }

        return redirect('packages')->with('success', 'Package  Updated !');
    }

    public function commercialInvoice($id)
    {
        $package = Package::with(['packageItems', 'warehouse.country'])
            ->when(Auth::user()->type == 'customer', function ($qry) {
                $qry->where('customer_id', Auth::user()->id);
            })->findOrFail($id);

        $warehouse = $package->warehouse;
        $user = User::find($package->customer_id);
        $address = Address::find($package->address_book_id);

        $package_weight = 0;
        if (isset($package->boxes)) {
            $package_weight = $package->boxes->sum('weight');
        }


        $html = view('pdfs.commercial-invoice', [
            'package' => $package,
            'package_weight' => $package_weight,
            'warehouse' => $warehouse,
            'user' => $user,
            'address' => $address
        ])->render();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        //page 2
        $mpdf->AddPage();
        $mpdf->WriteHTML($html);
        //page 3
        $mpdf->AddPage();
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function commercialInvoiceForLabel($id)
    {
        $package = Package::with(['packageItems', 'warehouse.country'])
            ->when(Auth::user()->type == 'customer', function ($qry) {
                $qry->where('customer_id', Auth::user()->id);
            })->findOrFail($id);

        $warehouse = $package->warehouse;
        $user = User::find($package->customer_id);
        $address = Address::find($package->address_book_id);

        $package_weight = 0;
        if (isset($package->boxes)) {
            $package_weight = $package->boxes->sum('weight');
        }

        view()->share([
            'package' => $package,
            'package_weight' => $package_weight,
            'warehouse' => $warehouse,
            'user' => $user,
            'address' => $address
        ]);

        $pdf = PDF::loadView('pdfs.commercial-invoice');
        $pdf->setPaper('A4', 'portrait');

        $filename = Carbon::parse(Carbon::now())->format('dmyhis') . '-' . $package->id . '.pdf';
        Storage::disk('commercial-invoices')->put($filename, $pdf->output());
        return response()->download('storage/commercial-invoices/' . $filename);
    }

    public function serviceRequest(Request $request)
    {

        $service = $request->input('service');
        $service_request = new ServiceRequest();
        $service_request->service_id = $service['id'];
        $service_request->package_id = $request->input('package_id');
        $service_request->price = $service['price'];
        $service_request->status = 'pending';
        $service_request->customer_message = $request->input('customer_message');
        $service_request->save();

        if ($service['keyword'] == 'consolidation') {
            $package = Package::find($request->input('package_id'));
            $package->consolidation_request = 1;
            $package->update();
        }

        event(new ServiceRequestedEvent($service_request));

        //return redirect()->route('packages.show',['id'=>$request->input('package_id')])->with('success', 'Your service request sent to Admin.');
        return redirect()->back()->with('success', 'Your service request sent to Admin.');
    }

    public function serviceHandle(Request $request)
    {

        $service_request_data = $request->input('request');

        $service_reqeust = ServiceRequest::find($service_request_data['id']);
        $service_reqeust->status = $request->input('status');
        $service_reqeust->admin_message = $request->input('admin_message');
        $service_reqeust->price = $service_request_data['price'];
        $service_reqeust->save();

        event(new ServiceRequestUpdatedEvent($service_reqeust));

        return redirect()->back()->with('success', 'Service request updated, customer notfied.');
    }

    public function consolidatePackage(Request $request)
    {
        $package = Package::findOrFail($request->package_id);

        PackageBox::where('package_id', $package->id)->delete();
        foreach ($request->package_boxes as $pkg_box) {
            PackageBox::create([
                'package_id' => $package->id,
                'pkg_type' => 'consolidation',
                'weight_unit' => $pkg_box['weight_unit'],
                'dim_unit' => $pkg_box['dim_unit'],
                'weight' => $pkg_box['weight'],
                'length' => $pkg_box['length'],
                'width' => $pkg_box['width'],
                'height' => $pkg_box['height'],
            ]);
        }

        $package->status = 'consolidated';
        $package->pkg_dim_status = 'done';
        $package->admin_status = 'accepted';
        $package->consolidation_fee = (1.5 * count($package->child_packages)) + 5;
        $package->update();

        event(new PackageConsolidated($package));

        return redirect()->back();
    }

    public function shipPackage(Request $request)
    {
        $data = $request->validate([
            'tracking_out' => 'required',
            'box_id' => 'required'
        ]);

        $pkg_box = PackageBox::find($data['box_id']);
        $pkg_box->update(['tracking_out' => $data['tracking_out']]);

        // event(new PackageShipped($package));

        return redirect()->back()->with('success', 'Package set for shipment.');
    }

    public function setShippingService(Request $request)
    {
        $package = Package::find($request->package_id);
        $package->status = 'shipping_service_selected';

        $package->carrier_code = $request->code;
        $package->service_code = $request->type;
        $package->service_label = $request->name;
        $package->package_type_code = $request->pkg_type;
        $package->currency = "USD";
        $package->markup_fee = $request->markup;
        $package->shipping_charges = $request->total;
        $package->update();

        return redirect()->route('packages.show', $package->id)->with('success', 'The package has been set for shipment.');
    }

    public function removeItem(Request $request)
    {

        // $item_id = $request->input('item_id');
        // OrderItem::find($item_id)->delete();
    }

    public function destroy(Request $request)
    {
        $package = Package::find($request->package_id);
        $package->delete();
        return redirect()->route('packages.index')->with('error', 'The package have been deleted successfully.');
    }

    public function pushPackage($packageID)
    {
        $response = [];
        $package = Package::with(['warehouse', 'orders', 'address'])->find($packageID);
        $multiPieceRequestStatus = ServiceRequest::where('package_id', $package->id)->where('status', 'served')->where('service_id', 5)->first();
        $warehouse = $package->warehouse;
        $sender = [
            'name' => $warehouse->contact_person,
            'company' => $warehouse->name,
            'address1' => $warehouse->address,
            'address2' => ' ',
            'city' => $warehouse->city,
            'state' => $warehouse->state,
            'zip' => $warehouse->zip,
            'country' => $warehouse->country->iso,
            'phone' => $warehouse->phone,
            'email' => $warehouse->email,
        ];

        $address = $package->address;
        $reciever = [
            'name' => $address->fullname,
            'company' => '',
            'address1' => $address->address,
            'address2' => ' ',
            'city' => $address->city,
            'state' => $address->state,
            'zip' => $address->zip_code ?? ' ',
            'country' => $address->country->iso,
            'phone' => $address->phone,
            'email' => null,
        ];

        if ($multiPieceRequestStatus != null) {
            $orders =  $package->orders;
            foreach ($orders as $order) {
                $orderItems = $order->items;
                $items = [];
                foreach ($orderItems as $item) {
                    $items[] = [
                        'productId' => (string)$item->id,
                        'sku' => null,
                        'title' => $item->name,
                        'price' => (string)$item->unit_price ?? null,
                        'quantity' => $item->quantity,
                        'countryOfOrigin' => Country::find($item->origin_country)->iso ?? null,
                        'weight' => null,
                        'imgUrl' => null,
                        'htsNumber' => null,
                        'lineId' => null,
                    ];
                }
                $packageInfo = [
                    [
                        'weight' => (string)$order->package_weight,
                        'length' => (string)$order->package_length,
                        'width' => (string)$order->package_width,
                        'height' => (string)$order->package_height,
                        'insuranceAmount' => NULL,
                        'declaredValue' => $order->declared_value == 0 ? NULL : $order->declared_value,
                    ]
                ];
                $post_params = array(
                    'orderId' => $package->package_no . '-' . sprintf("%05d", $order->id),
                    'orderDate' => date('Y-m-d', strtotime($package->created_at)),
                    'orderNumber' => $package->package_no,
                    'fulfillmentStatus' => 'pending',
                    'shippingService' => $package->package_type_code . ' ' . $package->service_label,
                    'shippingTotal' => (string)$package->shipping_charges,
                    'weightUnit' => $order->weight_unit,
                    'dimUnit' => $order->dim_unit,
                    'dueByDate' => Carbon::now()->addDays(10)->format('Y-m-d'),
                    'orderGroup' => $package->package_no,
                    'contentDescription' => null,
                    'sender' => $sender,
                    'receiver' => $reciever,
                    'items' => $items,
                    'packages' => $packageInfo
                );

                $response[] = json_decode($this->hitApi($post_params), true);
            }
        } else {
            $orders =  $package->orders;
            $ordersIDs = $package->orders()->pluck('id')->toArray();
            $orderItems = OrderItem::whereIn('order_id', $ordersIDs)->get();
            $items = [];
            foreach ($orderItems as $item) {
                $items[] = [
                    'productId' => (string)$item->id,
                    'sku' => null,
                    'title' => $item->name,
                    'price' => (string)$item->unit_price ?? null,
                    'quantity' => $item->quantity,
                    'countryOfOrigin' => Country::find($item->origin_country)->iso ?? null,
                    'weight' => null,
                    'imgUrl' => null,
                    'htsNumber' => null,
                    'lineId' => null,
                ];
            }

            $packageInfo = [
                [
                    'weight' => (string)$package->package_weight,
                    'length' => (string)$package->package_length,
                    'width' => (string)$package->package_width,
                    'height' => (string)$package->package_height,
                    'insuranceAmount' => NULL,
                    'declaredValue' => $package->declared_value == 0 ? NULL : $package->declared_value,
                ]
            ];

            $post_params = array(
                'orderId' => $package->package_no,
                'orderDate' => date('Y-m-d', strtotime($package->created_at)),
                'orderNumber' => $package->package_no,
                'fulfillmentStatus' => 'pending',
                'shippingService' => $package->package_type_code . ' ' . $package->service_label,
                'shippingTotal' => (string)$package->shipping_charges,
                'weightUnit' => $package->weight_unit,
                'dimUnit' => $package->dim_unit,
                'dueByDate' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'orderGroup' => null,
                'contentDescription' => null,
                'sender' => $sender,
                'receiver' => $reciever,
                'items' => $items,
                'packages' => $packageInfo
            );

            $response[] =  json_decode($this->hitApi($post_params), true);
        }

        $count = 0;

        foreach ($response as $res) {
            if (isset($res['ok']) && $res['ok'] == true) {
                $count++;
            }
        }

        return response()->json([
            'okCount' => $count,
            'total' => count($response),
        ]);
    }

    public function consolidation(Request $request)
    {
        $query = Package::with('customer', 'warehouse')
            ->where('warehouse_id', $request->warehouse_id)
            ->where('payment_status', 'Pending')
            ->where('pkg_type', 'single');

        if (Auth::user()->type == 'customer') {
            $query->where('customer_id', Auth::user()->id);
        }

        $packages = $query->orderBy('id', 'desc')->get();

        $warehouses = Warehouse::get();

        return Inertia::render('Packages/Consolidation', [
            'pkgs' => $packages,
            'warehouses' => $warehouses,
        ]);
    }

    public function storeConsolidation(Request $request)
    {
        if ($request->package_consolidation == []) {
            return redirect()->back()->with('error', 'Please select package for consolidation.');
        }

        $user = Auth::user();

        $package = Package::create([
            'customer_id' => $user->id,
            'warehouse_id' => $request->warehouse_id,
            'pkg_type' => 'consolidation',
        ]);

        foreach ($request->package_consolidation as $key => $pkg) {
            $pkg = Package::find($pkg);
            $pkg->update([
                'package_handler_id' => $package->id,
                'pkg_type' => 'assigned',
            ]);
        }

        foreach ($package->child_packages as $child_pkg) {
            $service_requests = ServiceRequest::where('package_id', $child_pkg->id)->get();
            foreach ($service_requests as $key => $service_request) {
                ServiceRequest::create([
                    'service_id' => $service_request->service_id,
                    'package_id' => $package->id,
                    'child_package_id' => $service_request->package_id,
                    'price' => $service_request->price,
                    'status' => $service_request->status,
                    'admin_message' => $service_request->admin_message,
                    'customer_message' => $service_request->customer_message,
                ]);
            }
        }

        $users = User::where(['type' => 'admin'])->get();
        Notification::send($users, new CustomerPackageRequestNotification($package));

        return redirect()->route('packages.show', $package->id)->with('success', 'The package have consolidated successfully.');
    }

    public function multipiece(Request $request)
    {
        $query = Package::with('customer', 'warehouse')
            ->where('warehouse_id', $request->warehouse_id)
            ->where('status', 'open')
            ->where('pkg_type', 'single');

        if (Auth::user()->type == 'customer') {
            $query->where('customer_id', Auth::user()->id);
        }

        $packages = $query->orderBy('id', 'desc')->get();
        $warehouses = Warehouse::get();

        return Inertia::render('Packages/Multipiece', [
            'pkgs' => $packages,
            'warehouses' => $warehouses,
        ]);
    }

    public function storeMultipiece(Request $request)
    {
        if ($request->multipiece_package == []) {
            return redirect()->back()->with('error', 'Please select packages for multipiece.');
        }

        $user = Auth::user();

        $package = Package::with('order')->create([
            'customer_id' => $user->id,
            'warehouse_id' => $request->warehouse_id,
            'pkg_type' => 'multipiece',
            'admin_status' => 'accepted',
            'pkg_dim_status' => 'done',
        ]);

        foreach ($request->multipiece_package as $key => $pkg) {
            $pkg = Package::find($pkg);
            $pkg->update([
                'package_handler_id' => $package->id,
                'pkg_type' => 'assigned',
            ]);
        }


        foreach ($package->child_packages as $child_pkg) {
            PackageBox::create([
                'pkg_type' => 'multipiece',
                'package_id' => $package->id,
                'weight_unit' => $child_pkg->order->weight_unit,
                'dim_unit' => $child_pkg->order->dim_unit,
                'weight' => $child_pkg->order->package_weight,
                'length' => $child_pkg->order->package_length,
                'width' => $child_pkg->order->package_width,
                'height' => $child_pkg->order->package_height,
            ]);

            $service_requests = ServiceRequest::where('package_id', $child_pkg->id)->get();
            foreach ($service_requests as $key => $service_request) {
                ServiceRequest::create([
                    'service_id' => $service_request->service_id,
                    'package_id' => $package->id,
                    'child_package_id' => $service_request->package_id,
                    'price' => $service_request->price,
                    'status' => $service_request->status,
                    'admin_message' => $service_request->admin_message,
                    'customer_message' => $service_request->customer_message,
                ]);
            }
        }


        $users = User::where(['type' => 'admin'])->get();
        Notification::send($users, new CustomerPackageRequestNotification($package));

        return redirect()->route('packages.show', $package->id)->with('success', 'The package have multipiece successfully.');
    }

    public function updateAddress(Request $request)
    {
        $package = Package::find($request->package_id);
        $address = Address::find($request->address_book_id);
        if (isset($address)) {
            $address_type = $address->country_id == 226 ? 'domestic' : 'international';
            $package->update([
                'address_book_id' => $request->address_book_id,
                'address_type' => $address_type,
            ]);
        } else {
            $package->update([
                'address_book_id' => 0,
                'address_type' => NULL,
            ]);
        }

        return redirect()->route('packages.show', $package->id);
    }

    public function updateCharges(Request $request)
    {
        if ($request->type == 'service_request') {
            ServiceRequest::find($request->id)->update(['price' => $request->amount]);
        } else {
            $package = Package::find($request->package_id);

            if ($request->type == 'shipping_charges') {
                $package->update(['shipping_charges' => $request->amount]);
            }
        }

        return redirect()->back()->with('success', 'Charges Updated');
    }

    public function returnPackage(Request $request)
    {
        $request->validate([
            'return_label' => 'required',
            'return_label_file' => [Rule::requiredIf($request->return_label == 1), 'mimes:pdf'],
        ]);

        $package  = Package::find($request->package_id);

        if ($request->return_label == 1) {
            $file = $request->file('return_label_file');
            $filename = time() . '_' . $package->id . '.pdf';
            $file->storeAs('uploads', $filename);
            File::move(storage_path('app/uploads/' . $filename), public_path('../public/uploads/' . $filename));
        }

        $package->update([
            'return_package' => true,
            'return_label' => $request->return_label,
            'return_label_file' => $filename ?? NULL,
        ]);

        $admins = User::whereIn('type', ['admin', 'manager'])->get();
        Notification::send($admins, new ReturnPackageNotification($package));

        return redirect()->back()->with('success', 'SUCCESS!');
    }

    public function coupon(Request $request)
    {
        $coupon_package = CouponPackage::where('package_id', $request->package_id)->first();

        if ($coupon_package) {
            return redirect()->back()->with('error', 'The coupon is already applied!');
        }

        $coupon = Coupon::where('code', $request->code)->where('status', 1)->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'The coupon is invalid or expired!');
        }

        $package = Package::find($request->package_id);
        $package->update([
            'discount' => $coupon->discount
        ]);

        CouponPackage::create([
            'package_id' => $package->id,
            'coupon_id' => $coupon->id,
        ]);

        return redirect()->back()->with('success', 'The coupon applied successfully!');
    }

    public function removeCoupon(Request $request)
    {
        CouponPackage::where('package_id', $request->package_id)->delete();
        $package = Package::find($request->package_id);
        $package->update([
            'discount' => 0
        ]);

        return redirect()->back()->with('success', 'The coupon remove successfully!');
    }

    public function uploadFile(Request $request)
    {
        try {
            $path = $request->file('file')->store('package-files', 'public');
            PackageFile::create([
                'package_id' => $request->package_id,
                'path' => $path
            ]);

            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function generateLabel(Request $request)
    {
        try {

            $package = Package::where('id', $request->package_id)->first();

            if ($package->carrier_code == 'fedex') {
                generateLabelFedex($package->id, 1);
            }

            if ($package->carrier_code == 'ups') {
                generateLabelUps($package->id, 1);
            }

            if ($package->carrier_code == 'dhl') {
                generateLabelDhl($package->id, 1);
            }

            return redirect()->back()->with('success', 'The label has been generated successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
