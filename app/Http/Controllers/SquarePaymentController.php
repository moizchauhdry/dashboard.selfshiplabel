<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SquarePaymentController extends Controller
{
    public function payment(Request $request)
    {
        $package = Package::find($request->package_id);
        $grand_total = 0;

        if ($package->grand_total > 0) {
            $grand_total = $package->grand_total;
        } else {
            return $this->error('The value must be greater then 0',);
        }

        $data = [
            'package_id' => $package->id,
            'customer_id' => $package->customer_id,
            'payment_type' => 'stripe',
            'charged_amount' => 0,
            'transaction_id' => 0
        ];

        return $this->sendResponse($data, 'The payment intent created successfully.');
    }

    public function index($id)
    {
        $package = Package::find($id);
        return view('square.index', compact('package'));
    }

    public function squarePaymentSuccess(Request $request)
    {        
        $package = Package::find($request->package_id);
        $grand_total = $package->grand_total * 100;
        $grand_total_array = explode(".", $grand_total);
        $amount = (int) $grand_total_array[0];

        // $url = 'https://connect.squareup.com/v2/payments';
        $url = 'https://connect.squareupsandbox.com/v2/payments';

        $body = [
            'amount_money' => [
                'amount' => $amount,
                'currency' => 'USD',
            ],
            'idempotency_key' => (string) Str::uuid(),
            'source_id' => $request->payment_token,
        ];

        $headers = [
            'Authorization' => 'Bearer EAAAEPcP7wW7hp68oZHTLDGY4E7XjEAQWGFzLHVrIFpElBcX6CTDSSkk0UsEKx4e'
        ];

        $response = Http::withHeaders($headers)->post($url, $body);

        $status_code = $response->status();
        $response_body = json_decode($response->getBody(), true);

        $package->update([
            'payment_status' => 'Paid',
            'cart' => 0,
        ]);

        return response()->json([
            'status' => true,
            'code' => $status_code,
            'message' => 'success',
        ]);
    }

    public function complete()
    {
        return view('square.success');
    }
}
