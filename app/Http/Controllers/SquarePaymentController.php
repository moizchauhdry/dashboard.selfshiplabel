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

                // CREATE CUSTOMER
                $customer_url = 'https://connect.squareupsandbox.com/v2/customers';

                $customer_body = [
                    "company_name" => "Moiz Chauhdry v1",
                    'idempotency_key' => (string) Str::uuid(),
                ];

                $customer_headers = [
                    'Authorization' => 'Bearer EAAAEPcP7wW7hp68oZHTLDGY4E7XjEAQWGFzLHVrIFpElBcX6CTDSSkk0UsEKx4e'
                ];

                $customer_response = Http::withHeaders($customer_headers)->post($customer_url, $customer_body);
                $customer_response = json_decode($customer_response->getBody(), true);

                // CREATE CARD
                $card_url = 'https://connect.squareupsandbox.com/v2/cards';

                $card_body = [
                    "card" => [
                        "cardholder_name" => "Moiz Chauhdry",
                        "customer_id" => $customer_response['customer']['id']
                    ],
                    'idempotency_key' => (string) Str::uuid(),
                    'source_id' => $request->payment_token,
                ];

                $card_headers = [
                    'Authorization' => 'Bearer EAAAEPcP7wW7hp68oZHTLDGY4E7XjEAQWGFzLHVrIFpElBcX6CTDSSkk0UsEKx4e'
                ];

                $card_response = Http::withHeaders($card_headers)->post($card_url, $card_body);
                $card_response = json_decode($card_response->getBody(), true);

                // $payment->update([
                //     'square_card_id' => $card_response['card']['id'],
                //     'square_card_response' => json_encode($card_response),
                // ]);

                // $data = [
                //     'square_customer_id' => $customer_response['customer']['id'],
                //     'square_customer_response' => json_encode($customer_response),
                // ];

                // $payment = Payment::updateOrCreate([
                //     'payment_module' => 'package',
                //     'payment_module_id' => $package->id,
                // ], $data);


                // $url = 'https://connect.squareup.com/v2/payments';
                $url = 'https://connect.squareupsandbox.com/v2/payments';

                $body = [
                    'amount_money' => [
                        'amount' => $amount,
                        'currency' => 'USD',
                    ],
                    'idempotency_key' => (string) Str::uuid(),
                    'source_id' => $card_response['card']['id'],
                    'customer_id' => $customer_response['customer']['id'],
                ];

                $headers = [
                    'Authorization' => 'Bearer EAAAEPcP7wW7hp68oZHTLDGY4E7XjEAQWGFzLHVrIFpElBcX6CTDSSkk0UsEKx4e'
                ];

                $response = Http::withHeaders($headers)->post($url, $body);

                $status_code = $response->status();
                $response = json_decode($response->getBody(), true);

                dd($response);

                // $data = [
                //     'payment_module' => 'package',
                //     'payment_module_id' => $package->id,
                //     'customer_id' => $package->customer_id,
                //     // 'transaction_id' => $response['payment']['id'],
                //     'payment_method' => 'square',
                //     // 'charged_amount' => $response['payment']['amount_money']['amount'],
                //     'charged_at' => Carbon::now(),
                //     // 'payment_response' => json_encode($response),
                // ];

                // $payment = Payment::updateOrCreate([
                //     'payment_module' => 'package',
                //     'payment_module_id' => $package->id,
                // ], $data);

                // if ($response['payment']['status'] === 'COMPLETED') {
                //     $package->update([
                //         'payment_status' => 'Paid',
                //         'cart' => 0,
                //     ]);
                // } else {
                //     // $package->update(['payment_status' => 'failed']);
                // }

                // return response()->json([
                //     'status' => true,
                //     'code' => $status_code,
                //     'message' => 'success',
                // ]);
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
