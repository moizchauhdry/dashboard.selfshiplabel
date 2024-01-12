<?php

namespace App\Http\Controllers;

use App\Models\InsuranceRequestComment;
use App\Models\Package;
use App\Models\Shipping;
use App\Models\User;
use App\Notifications\AdditionalRequestNotification;
use Illuminate\Http\Request;
use App\Models\InsuranceRequest;
use Inertia\Inertia;

class InsuranceController extends Controller
{
    //

    public function index()
    {
        $user = \Auth::user();
        if ($user->type == 'admin') {
            $insurance = InsuranceRequest::with('package', 'customer')->get();
        } else {
            $insurance = InsuranceRequest::with('package', 'customer')->where('customer_id', $user->id)->get();
        }

        return Inertia::render('Insurance/Index', [
            'insurances' => $insurance,
        ]);
    }


    public function create(Request $request)
    {
        $user = \Auth::user();

        if ($request->isMethod('POST')) {


            $validatedData = $request->validate([
                'package_id' => 'required',
                'shipping_service' => 'required',
                'insurance_amount' => 'required',
                'message' => 'required',
            ]);

            $insuranceRequest = InsuranceRequest::create([
                'package_id' => $validatedData['package_id'],
                'customer_id' => $user->id,
                'shipping_service' => $validatedData['shipping_service'],
                'insurance_amount' => $validatedData['insurance_amount'],
                'message' => $validatedData['message'],
            ]);
            /*$url = \URL::route('insurance-request.edit', $insuranceRequest->id);
            $data = [
                'url' => \URL::route('insurance-request.edit', $insuranceRequest->id),
                'message' => 'Customer has created an insurance request. <a href="' . $url . '">Click Here</a>',
            ];
            $admins = User::where('type', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdditionalRequestNotification($data));
            }*/
            return redirect()->route('insurance.index')->with('success', 'Successfully Requested');
        }

        if ($user->type == 'admin') {
            $packages = Package::all();
        } else {
            $packages = Package::where('customer_id', $user->id)->get();
        }

        $shippingServices  = collect(Shipping::getShippingServices())->pluck('serviceLabel');
        return Inertia::render('Insurance/Create', [
            'shipping_services' => $shippingServices,
            'packages' => $packages,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $user = \Auth::user();
        $insuranceRequest = InsuranceRequest::find($id);
        if ($request->isMethod('POST')) {

            if ($request->has('approve') && $request->approve == 1) {
                \Session::forget(['order_id', 'package_id', 'additional_request_id']);
                \Session::put('insurance_id', $id);
                return redirect()->route('payment.index')->with('amount',$insuranceRequest->amount);
            }

            $validatedData = $request->validate([
                'amount' => 'required',
                'shipping_service' => 'required',
                'insurance_amount' => 'required',
                'package_id' => 'required',
                'message' => 'nullable',
            ]);


            $insuranceRequest->update([
                'package_id' => $validatedData['package_id'],
                'message' => $validatedData['message'],
                'shipping_service' => $validatedData['shipping_service'],
                'insurance_amount' => $validatedData['insurance_amount'],
                'amount' => $validatedData['amount'],
            ]);

            $url = \URL::route('insurance.edit', $insuranceRequest->id);
            $auser = $user->type == 'admin' ? 'Admin' : 'User';
            $data = [
                'url' => \URL::route('insurance.edit', $insuranceRequest->id),
                'message' => $auser . ' has modified an insurance request. <a href="' . $url . '">Click Here</a>',
            ];

            if ($user->type == 'admin') {
                $customer = User::find($insuranceRequest->customer_id);
                $customer->notify(new AdditionalRequestNotification($data));
            } else {
                $admins = User::where('type', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new AdditionalRequestNotification($data));
                }
            }
            return redirect()->route('insurance.index')->with('success', 'Successfully Modified');
        }


        if ($user->type == 'admin') {
            $packages = Package::all();
        } else {
            $packages = Package::where('customer_id', $user->id)->get();
        }
        $insuranceRequest = InsuranceRequest::find($id);
        $comments = InsuranceRequestComment::where('insurance_request_id', $insuranceRequest->id)->with('user')->orderBy('id', 'desc')->get();
        $shippingServices  = collect(Shipping::getShippingServices())->pluck('serviceLabel');
        return Inertia::render('Insurance/Edit', [
            'insuranceRequest' => $insuranceRequest,
            'shipping_services' => $shippingServices,
            'packages' => $packages,
            'comments' => $comments,
        ]);
    }

    public function storeComment(Request $request, $id)
    {
        $user = \Auth::user();
        $insuranceRequest = InsuranceRequest::find($id);
        $validatedData = $request->validate([
            'message' => 'required',
        ]);

        InsuranceRequestComment::create([
            'insurance_request_id' => $id,
            'user_id' => $user->id,
            'message' => $validatedData['message'],
        ]);

        $url = \URL::route('insurance.edit', $insuranceRequest->id);
        $auser = $user->type == 'admin' ? 'Admin' : 'User';
        $data = [
            'url' => \URL::route('insurance.edit', $insuranceRequest->id),
            'message' => $auser . ' has commented on an insurance request. <a href="' . $url . '">Click Here</a>',
        ];
        if ($user->type == 'admin') {
            $packages = Package::all();
            $customer = User::find($insuranceRequest->customer_id);
            $customer->notify(new AdditionalRequestNotification($data));
        } else {
            $admins = User::where('type', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdditionalRequestNotification($data));
            }
            $packages = Package::where('customer_id', $user->id)->get();
        }
        $insuranceRequest = InsuranceRequest::find($id);
        $comments = InsuranceRequestComment::where('insurance_request_id', $insuranceRequest->id)->with('user')->orderBy('id', 'desc')->get();

        return redirect()->route('insurance.edit', $insuranceRequest->id)->with('success', 'Comment Added Successfully');
    }

    public function loadComments($id)
    {
        $comments = InsuranceRequestComment::where('insurance_request_id', $id)->get();
        return response()->json([
            'comments' => $comments,
        ]);
    }
}
