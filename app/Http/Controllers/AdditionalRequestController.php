<?php

namespace App\Http\Controllers;

use App\Models\AdditionalRequest;
use App\Models\AdditionalRequestComment;
use App\Models\Package;
use App\Models\User;
use App\Notifications\AdditionalRequestNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdditionalRequestController extends Controller
{
    //
    public function index()
    {

        $user = \Auth::user();
        if ($user->type == 'admin') {
            $additionalRequests = AdditionalRequest::with('package', 'customer')->get();
        } else {
            $additionalRequests = AdditionalRequest::with('package', 'customer')->where('user_id', $user->id)->get();
        }
        return Inertia::render('AdditionalRequest/Index', [
            'additionalRequests' => $additionalRequests,
        ]);
    }

    public function create(Request $request)
    {
        $user = \Auth::user();
        if ($request->isMethod('POST')) {


            $validatedData = $request->validate([
                'package_id' => 'required',
                'message' => 'required',
                'tracking_no' => 'required',
                'serial_no' => 'required',
            ]);

            $additionalRequest = AdditionalRequest::create([
                'package_id' => $validatedData['package_id'],
                'user_id' => $user->id,
                'message' => $validatedData['message'],
                'tracking_no' => $validatedData['tracking_no'],
                'serial_no' => $validatedData['serial_no'],
            ]);
            $url = \URL::route('additional-request.edit', $additionalRequest->id);
            $customer = $additionalRequest->customer;
            $customerDetailURL = 'Customer <strong><a href="'.route('customers.show',$customer->id).'">'.$customer->name_with_suite_no.'</strong></a>';

            $data = [
                'url' => \URL::route('additional-request.edit', $additionalRequest->id),
                'message' => $customerDetailURL . ' has created an additional request. <a href="' . $url . '">Click Here</a>',
            ];
            $admins = User::where('type', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdditionalRequestNotification($data));
            }
            return redirect()->route('additional-request.index')->with('success', 'Successfully Requested');
        }


        if ($user->type == 'admin') {
            $packages = Package::all();
        } else {
            $packages = Package::where('customer_id', $user->id)->get();
        }


        return Inertia::render('AdditionalRequest/Create', [
            'additionalRequest' => [],
            'packages' => $packages,
        ]);
    }

    public function store(Request $request)
    {
    }

    public function edit(Request $request, $id)
    {
        $user = \Auth::user();
        $additionalRequest = AdditionalRequest::find($id);
        if ($request->isMethod('POST')) {

            if ($request->has('approve') && $request->approve == 1) {
                \Session::forget(['order_id', 'package_id', 'insurance_id']);
                \Session::put('additional_request_id', $id);
                return redirect()->route('payment.index')->with('amount',$additionalRequest->price);
            }

            $validatedData = $request->validate([
                'package_id' => 'required',
                'message' => 'required',
                'tracking_no' => 'required',
                'serial_no' => 'required',
                'price' => 'required',
            ]);


            $additionalRequest->update([
                'package_id' => $validatedData['package_id'],
                'message' => $validatedData['message'],
                'tracking_no' => $validatedData['tracking_no'],
                'serial_no' => $validatedData['serial_no'],
                'price' => $validatedData['price'],
            ]);

            $url = \URL::route('additional-request.edit', $additionalRequest->id);
            $customer = $additionalRequest->customer;
            $customerDetailURL = 'Customer <strong><a href="'.route('customers.show',$customer->id).'">'.$customer->name_with_suite_no.'</strong></a>';

            $auser = $user->type == 'admin' ? 'Admin' : $customerDetailURL;
            $data = [
                'url' => \URL::route('additional-request.edit', $additionalRequest->id),
                'message' => $auser . ' has modified an additional request. <a href="' . $url . '">Click Here</a>',
            ];

            if ($user->type == 'admin') {
                $customer = User::find($additionalRequest->user_id);
                $customer->notify(new AdditionalRequestNotification($data));
            } else {
                $admins = User::where('type', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new AdditionalRequestNotification($data));
                }
            }
            return redirect()->route('additional-request.index')->with('success', 'Successfully Modified');
        }


        if ($user->type == 'admin') {
            $packages = Package::all();
        } else {
            $packages = Package::where('customer_id', $user->id)->get();
        }
        $additionalRequest = AdditionalRequest::find($id);
        $comments = AdditionalRequestComment::where('additional_request_id', $additionalRequest->id)->with('user')->orderBy('id', 'desc')->get();
        return Inertia::render('AdditionalRequest/Edit', [
            'additionalRequest' => $additionalRequest,
            'packages' => $packages,
            'comments' => $comments,
        ]);
    }

    public function update(Request $request, $id)
    {
    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $additionalRequest = AdditionalRequest::find($id);
        $additionalRequest->status = $status;
        $additionalRequest->save();

        return response()->json(['status' => '1', 'additionalRequest' => $additionalRequest]);
    }

    public function storeComment(Request $request, $id)
    {
        $user = \Auth::user();
        $additionalRequest = AdditionalRequest::find($id);
        $validatedData = $request->validate([
            'message' => 'required',
        ]);

        AdditionalRequestComment::create([
            'additional_request_id' => $id,
            'user_id' => $user->id,
            'message' => $validatedData['message'],
        ]);

        $url = \URL::route('additional-request.edit', $additionalRequest->id);
        $customer = $additionalRequest->customer;
        $customerDetailURL = 'Customer <strong><a href="'.route('customers.show',$customer->id).'">'.$customer->name_with_suite_no.'</strong></a>';

        $auser = $user->type == 'admin' ? 'Admin' : $customerDetailURL;
        $data = [
            'url' => \URL::route('additional-request.edit', $additionalRequest->id),
            'message' => $auser . ' has commented on an additional request. <a href="' . $url . '">Click Here</a>',
        ];
        if ($user->type == 'admin') {
            $packages = Package::all();
            $customer = User::find($additionalRequest->user_id);
            $customer->notify(new AdditionalRequestNotification($data));
        } else {
            $admins = User::where('type', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdditionalRequestNotification($data));
            }
            $packages = Package::where('customer_id', $user->id)->get();
        }
        $additionalRequest = AdditionalRequest::find($id);
        $comments = AdditionalRequestComment::where('additional_request_id', $additionalRequest->id)->with('user')->orderBy('id', 'desc')->get();

        return Inertia::render('AdditionalRequest/Edit', [
            'additionalRequest' => $additionalRequest,
            'packages' => $packages,
            'comments' => $comments,
        ]);
    }

    public function loadComments($id)
    {
        $comments = AdditionalRequestComment::where('additional_request_id', $id)->get();
        return response()->json([
            'comments' => $comments,
        ]);
    }
}
