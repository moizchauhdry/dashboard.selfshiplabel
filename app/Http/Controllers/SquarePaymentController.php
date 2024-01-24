<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SquarePaymentController extends Controller
{
    public function payment(Request $request)
    {
        $package = Package::find($request->package_id);

        if ($package->grand_total > 0) {
            $data = [
                'package_id' => $package->id,
                'customer_id' => $package->customer_id,
                'payment_type' => 'stripe',
                'charged_amount' => 0,
                'transaction_id' => 0
            ];

            return $this->sendResponse($data, 'success');
        } else {
            return $this->error('The value must be greater then 0',);
        }
    }

    public function index($id)
    {
        $package = Package::find($id);
        return view('square.index', compact('package'));
    }

    public function squarePaymentSuccess(Request $request)
    {
        try {
            $package = Package::find($request->package_id);

            if ($package->payment_status == 'Pending') {
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
                $response = json_decode($response->getBody(), true);

                $data = [
                    'payment_module' => 'package',
                    'payment_module_id' => $package->id,
                    'customer_id' => $package->customer_id,
                    'transaction_id' => $response['payment']['id'],
                    'payment_method' => 'square',
                    'charged_amount' => $response['payment']['amount_money']['amount'],
                    'charged_at' => Carbon::now(),
                    'payment_response' => json_encode($response),
                ];

                $payment = Payment::updateOrCreate([
                    'payment_module' => 'package',
                    'payment_module_id' => $package->id,
                ], $data);

                // if ($response['payment']['status'] === 'COMPLETED') {
                //     $package->update([
                //         'payment_status' => 'Paid',
                //         'cart' => 0,
                //     ]);
                // } else {
                //     // $package->update(['payment_status' => 'failed']);
                // }


                $cc_url = 'https://connect.squareupsandbox.com/v2/customers';

                $cc_body = [
                    "company_name" => "Moiz Chauhdry v1",
                    'idempotency_key' => (string) Str::uuid(),
                ];

                $cc_headers = [
                    'Authorization' => 'Bearer EAAAEPcP7wW7hp68oZHTLDGY4E7XjEAQWGFzLHVrIFpElBcX6CTDSSkk0UsEKx4e'
                ];

                $cc_response = Http::withHeaders($cc_headers)->post($cc_url, $cc_body);
                $cc_response = json_decode($cc_response->getBody(), true);

                $payment->update([
                    'square_customer_id' => $cc_response['customer']['id'],
                    'square_customer_response' => json_encode($cc_response),
                ]);

                return response()->json([
                    'status' => true,
                    'code' => $status_code,
                    'message' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'code' => 403,
                    'message' => 'already-paid',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'code' => 403,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function complete()
    {
        return view('square.success');
    }
}
