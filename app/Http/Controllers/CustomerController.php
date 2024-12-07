<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Lead;
use App\Models\ShippingService;
use App\Models\User;
use App\Models\UserShippingService;
use PDF;

class CustomerController extends Controller
{

    // public function showPDF(Request $request)
    // {
    //     $data = array('idd' => 'balack');
    //     $pdf = PDF::loadView('pdf.invoice', $data);
    //     return $pdf->download('invoice.pdf');
    // }

    public function users(Request $request)
    {
        $users = User::where('type', '!=', 'customer');
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = intval($_GET['search']) - 4000;
            $users = $users->where('id', $search);
        }
        $users = $users->paginate(25);
        return Inertia::render('Users/Users', compact('users'));
    }

    public function createUser(Request $request)
    {
        return Inertia::render('Users/Create');
    }

    public function editUser(Request $request, $id)
    {
        $user = User::findOrfail($id);
        return Inertia::render('Users/Edit', compact('user'));
    }

    public function saveUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'phone_no' => 'required|string|max:255',
            'email' => 'email|string|max:255',
            'password' => 'required|string|min:8|max:20'
        ]);

        $user = User::create([
            'name' => $request->name,
            'address1' => $request->address1,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'type' => $request->type,
        ]);

        if ($user == null)
            return redirect('manage-users')->with('error', 'User Created Failed');
        else
            return redirect('manage-users')->with('success', 'User Created Successfully');
    }

    public function updateUser(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'address1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'phone_no' => 'required|string|max:255',
            'email' => 'email|string|max:255',
        ]);


        $user = User::where('id', $request->id)->update([
            'name' => $request->name,
            'address1' => $request->address1,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            'type' => $request->type,
        ]);

        if ($user == null)
            return redirect('manage-users')->with('error', 'User Created Failed');
        else
            return redirect('manage-users')->with('success', 'User Created Successfully');
    }

    // public function deleteUser($id)
    // {
    //     $user = User::find($id);
    //     $user->delete();

    //     return redirect('manage-users')->with('success', 'User Deleted Successfully');
    // }

    public function index(Request $request)
    {
        // dd($request->all());

        $page = $request->has('page') ? $request->page : 10;
        $query = User::where('type', 'customer');

        if (!empty($request->suite_no)) {
            $suite_no = intval($request->suite_no) - 4000;
            $query->where('id', $suite_no);
        }

        $customers = $query->orderBy('id', 'desc')->paginate(10)
            ->through(fn($customer) => [
                'id' => $customer->id,
                'suite_no' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'city' => $customer->city,
                'country' => $customer->country,
                'phone' => $customer->phone_no,
                'status' => $customer->status,
                'account_type' => $customer->account_type,
                'created_at' => isset($customer->created_at) ? $customer->created_at->format('F d, Y') : NULL,
                'updated_at' => isset($customer->created_at) ? $customer->updated_at->format('F d, Y') : NULL,
            ]);

        return Inertia::render('Customer/Index', [
            'customers' => $customers,
            'filter' => [
                'page' => $page,
                'suite_no' => $request->suite_no,
            ]
        ]);
    }

    // public function create()
    // {
    //     return Inertia::render('CreateLead');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'first_name' => 'required|string|max:255'
    //     ]);

    //     $user = Lead::create([
    //         'first_name' => $request->first_name
    //     ]);
    //     return redirect('leads');
    // }

    public function show($id)
    {
        $customer = User::find($id);
        return Inertia::render('CustomerDetail', ['customer' => $customer]);
    }

    public function edit($id)
    {
        $customer = User::find($id);
        return Inertia::render('EditCustomer', ['customer' => $customer]);
    }

    public function update(Request $request, $id)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'status' => $request->status,
            'account_type' => $request->account_type,
        ];

        $user = User::findOrFail($id);
        $user->update($data);

        return redirect()->route('customers.index')->with('success', 'The customer data have been updated successfully.');
    }

    public function destroy($id)
    {
        $lead = User::find($id);
        $lead->delete();
        return redirect('customers.index')->with('success', 'Customer Deleted Successfully.');
    }

    public function markup($customer_id)
    {
        $user = User::find($customer_id);
     
        if ($user->account_type == 2) {
            $records = UserShippingService::query()
                ->from('user_shipping_services as us')
                ->select(
                    'us.user_id as us_user_id',
                    'us.shipping_service_id as us_service_id',
                    'us.markup_percentage as us_percentage',
                    's.service_name as s_name',
                )
                ->join('shipping_services as s', 's.id', 'us.shipping_service_id')
                ->where('user_id', $customer_id)
                ->get();

            return Inertia::render('Customer/Markup', [
                'records' => $records,
                'customer_id' => $customer_id,
            ]);
        } else {
            abort(403);
        }
    }

    public function updateMarkup(Request $request)
    {
        $services = $request->input('records');
        $customer_id = $request->input('customer_id');

        UserShippingService::where('user_id', $customer_id)->delete();

        foreach ($services as $service) {
            UserShippingService::create([
                'user_id' => $service['us_user_id'],
                'shipping_service_id' => $service['us_service_id'],
                'markup_percentage' => $service['us_percentage'],
            ]);
        }

        return redirect()->back()->with('success', 'The markup percentage update successfully.');
    }
}
