<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SquarePaymentController extends Controller
{
    public function squarePayment($package_id)
    {
        $package = Package::find($package_id);
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

        dd($response_body);

        $package->update([
            'payment_status' => 'Paid',
        ]);

        return response()->json([
            'status' => true,
            'code' => $status_code,
            'message' => 'success',
        ]);
    }

    public function success()
    {
        return view('frontend.success');
    }
}
