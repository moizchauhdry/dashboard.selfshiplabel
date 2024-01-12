<?php

namespace App\Http\Controllers;

use App\Events\OrderCreatedEvent;
use App\Events\OrderUpdatedEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderImage;
use App\Models\Package;
use App\Models\PackageBox;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class OrderController extends Controller
{
    public $status_list = ['arrived', 'labeled', 'shipped', 'delivered', 'rejected'];

    public function index(Request $request)
    {
        $order_status = $request->has('order_status') ? $request->order_status : 'arrived';

        $query = Order::with('customer', 'warehouse')->where('status', $order_status);

        if (Auth::user()->type == 'customer') {
            $query->where('customer_id', Auth::user()->id);
        }

        if (!empty($request->suite_no)) {
            $suite_no = intval($request->suite_no) - 4000;
            $query->whereHas('customer', function ($query) use ($suite_no) {
                $query->where('id', $suite_no);
            });
        }

        $orders = $query->orderBy('id', 'desc')->get();

        return Inertia::render('Orders/OrdersList', [
            'orders' => $orders,
            'filter' => [
                'order_status' => $order_status
            ]
        ]);
    }

    public function searchResults(Request $request, $orders)
    {
        $suite_number = intval($request->search) - 4000;

        $orders->join('users', 'orders.customer_id', '=', 'users.id');
        $orders->join('warehouses', 'orders.warehouse_id', '=', 'warehouses.id');
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $search = trim($search);
            $orders->where('users.id', $suite_number)
                ->orWhere('users.name', 'LIKE', '%' . $search . '%')
                ->orWhere('orders.received_from', 'LIKE', '%' . $search . '%')
                ->orWhere('warehouses.name', 'LIKE', '%' . $search . '%');
        }
        return $orders->with(['customer', 'warehouse']);
    }

    public function show($id)
    {

        $order = Order::with(['customer', 'items', 'warehouse', 'images', 'package'])->findOrFail($id);
        return Inertia::render('Orders/OrderDetail', ['order' => $order]);
    }

    public function create(Request $request)
    {

        $selected_customer = $request->get('customer_id', '');

        $customers = User::orderBy('id', 'asc')->where('type', '=', 'customer')->select(['id', 'name'])->get()->toArray();

        $warehouses = Warehouse::select(['id', 'name', 'sale_tax'])->get()->toArray();

        return Inertia::render('Orders/CreateOrder', [
            'customers' => $customers,
            'selected_customer' => $selected_customer,
            'warehouses' => $warehouses,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required',
            'tracking_number_in' => 'required|min:3|max:100|string|unique:orders,tracking_number_in',
            'warehouse_id' => 'required',
            'weight_unit' => 'required',
            'dim_unit' => 'required',
            'package_weight' => 'required|numeric|gt:0',
            'package_length' => 'required|numeric|gt:0',
            'package_width' => 'required|numeric|gt:0',
            'package_height' => 'required|numeric|gt:0',
        ]);

        try {
            $package = Package::create([
                'customer_id' => $validated['customer_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'tracking_number_in' => $validated['tracking_number_in'],
                'received_from' => 'NIL',
                'notes' => 'NIL',
                'status' => 'open',
                'pkg_type' => 'single',
                'pkg_dim_status' => 'done',
                'admin_status' => 'accepted',
            ]);

            $package->update([
                'package_no' => $package->id,
                'package_handler_id' => $package->id,
            ]);

            PackageBox::create([
                'package_id' => $package->id,
                'pkg_type' => $package->pkg_type,
                'weight_unit' => $validated['weight_unit'],
                'dim_unit' => $validated['dim_unit'],
                'weight' => $validated['package_weight'],
                'length' => $validated['package_length'],
                'width' => $validated['package_width'],
                'height' => $validated['package_height'],
            ]);

            $order = new Order();
            $order->package_id = $package->id;
            $order->customer_id = $validated['customer_id'];
            $order->warehouse_id = $validated['warehouse_id'];
            $order->tracking_number_in = $validated['tracking_number_in'];
            $order->weight_unit = $validated['weight_unit'];
            $order->dim_unit = $validated['dim_unit'];
            $order->package_weight = $validated['package_weight'];
            $order->package_length = $validated['package_length'];
            $order->package_width = $validated['package_width'];
            $order->package_height = $validated['package_height'];
            $order->received_from = 'NIL';
            $order->notes = 'NIL';
            $order->arrived_at = Carbon::now();
            $order->created_at = Carbon::now();
            $order->save();

            $files = $request->file();
            if (isset($files['images'])) {
                foreach ($files['images'] as $key => $file) {
                    $image_object = $file['image'];
                    $file_name = time() . '_' . $order->package_id;
                    $image_object->storeAs('uploads', $file_name);
                    if ($_SERVER['HTTP_HOST'] == 'localhost:8000') {
                        File::move(storage_path('app/uploads/' . $file_name), public_path('/public/uploads/' . $file_name));
                    } else {
                        File::move(storage_path('app/uploads/' . $file_name), public_path('../public/uploads/' . $file_name));
                    }
                    $order_image = new OrderImage();
                    $order_image->image = $file_name;
                    $order_image->order_id = $order->id;
                    if ($key == 0) {
                        $order_image->display = 1;
                    } else {
                        $order_image->display = 0;
                    }
                    $order_image->save();
                }
            }

            try {
                event(new OrderCreatedEvent($order));
            } catch (\Throwable $th) {
                throw $th;
            }

            return redirect()->route('packages.index')->with('success', 'The package has been added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('packages.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function edit($id)
    {

        $model = order::find($id);

        $items = [];

        foreach ($model->items as $item) {
            $items[] = [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'price' => $item->unit_price,
                'price_with_tax' => $item->price_with_tax,
                'sub_total' => $item->sub_total,
                'url' => $item->url
            ];
        }

        $images = [];

        foreach ($model->images as $image) {
            $images[] = [
                'id' => $image->id,
                'image' => $image->image,
            ];
        }

        $order = [
            'id' => $model->id,
            'customer_id' => $model->customer_id,
            'tracking_number_in' => $model->tracking_number_in,
            'shipping_total' => $model->shipping_total,
            'package_weight' => $model->package_weight,
            'package_length' => $model->package_length,
            'package_width' => $model->package_width,
            'package_height' => $model->package_height,
            'declared_value' => $model->declared_value,
            'warehouse_id' => $model->warehouse_id,
            'weight_unit' => $model->weight_unit,
            'dim_unit' => $model->dim_unit,
            'notes' => $model->notes,
            'status' => $model->status,
            'received_from' => $model->received_from,
            'items' => $items,
            'images' => $images,
        ];

        $warehouses = Warehouse::select(['id', 'name'])->get()->toArray();


        $customers = User::where('type', '!=', 'admin')->select(['id', 'name'])->get()->toArray();

        return Inertia::render('Orders/EditOrder', [
            'order' => $order,
            'customers' => $customers,
            'status_list' => $this->status_list,
            'warehouses' => $warehouses
        ]);
    }

    public function update(Request $request)
    {

        $id = $request->input('id');
        $order = Order::find($id);

        $validated = $request->validate([
            'customer_id' => 'required',
            'tracking_number_in' => 'required|min:3|max:100|unique:orders,tracking_number_in,' . $id,
            'warehouse_id' => 'required',
            'weight_unit' => 'required',
            'dim_unit' => 'required',
            'package_weight' => 'required|numeric|gt:0',
            'package_length' => 'required|numeric|gt:0',
            'package_width' => 'required|numeric|gt:0',
            'package_height' => 'required|numeric|gt:0',
        ]);

        try {

            $order->tracking_number_in = $validated['tracking_number_in'];
            $order->warehouse_id = $validated['warehouse_id'];
            $order->weight_unit = $validated['weight_unit'];
            $order->dim_unit = $validated['dim_unit'];
            $order->package_weight = $validated['package_weight'];
            $order->package_length = $validated['package_length'];
            $order->package_width = $validated['package_width'];
            $order->package_height = $validated['package_height'];
            $order->update();

            $files = $request->file();
            if (isset($files['images'])) {
                foreach ($files['images'] as $key => $file) {
                    $image_object = $file['image'];
                    $file_name = time() . '_' . $order->package_id;
                    $image_object->storeAs('uploads', $file_name);
                    if ($_SERVER['HTTP_HOST'] == 'localhost:8000') {
                        File::move(storage_path('app/uploads/' . $file_name), public_path('/public/uploads/' . $file_name));
                    } else {
                        File::move(storage_path('app/uploads/' . $file_name), public_path('../public/uploads/' . $file_name));
                    }
                    $order_image = new OrderImage();
                    $order_image->image = $file_name;
                    $order_image->order_id = $order->id;
                    if ($key == 0) {
                        $order_image->display = 1;
                    } else {
                        $order_image->display = 0;
                    }
                    $order_image->save();
                }
            }

            $package = Package::find($order->package_id);

            PackageBox::updateOrCreate(['package_id' => $package->id], [
                'package_id' => $package->id,
                'pkg_type' => $package->pkg_type,
                'weight_unit' => $validated['weight_unit'],
                'dim_unit' => $validated['dim_unit'],
                'weight' => $validated['package_weight'],
                'length' => $validated['package_length'],
                'width' => $validated['package_width'],
                'height' => $validated['package_height'],
            ]);

            event(new OrderUpdatedEvent($order));

            return redirect()->route('packages.show', $order->package->package_handler_id)->with('success', 'The package has been updated successfully.');
        } catch (\Exception $e) {
            return redirect('orders')->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {

        $order = Order::find($id);

        $order->images()->delete();
        $order->items()->delete();

        $order->delete();

        return back()->with('success', 'Order deleted!');
    }

    public function removeItem(Request $request)
    {

        $item_id = $request->input('item_id');
        OrderItem::find($item_id)->delete();
    }

    public function deleteImage(Request $request)
    {
        $image_id = $request->input('id');
        OrderImage::find($image_id)->delete();
    }
}
