<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Package;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SquarePaymentController extends Controller
{
    private function paymentRules($package)
    {
        if ($package->pkg_ship_type == 'international') {
            if ($package->custom_form_status == 0) {
                abort('403', 'The PKG #' . $package->id . ' type is international, but the customs form has not been submitted.');
            }

            if ($package->custom_form_status == 1) {
                $oi_count = OrderItem::where('package_id', $package->id)->count();

                if ($oi_count == 0) {
                    abort('403', 'At least one item is required in the custom form.');
                }
            }
        }

        if ($package->ship_from == NULL || $package->ship_to == NULL) {
            abort('403', 'The package ship from address & ship to address is required.');
        }
    }

    public function payment(Request $request)
    {
        try {

            $package = Package::find($request->package_id);
            $this->paymentRules($package);

            if ($package->grand_total > 0) {
                $data = [
                    'package_id' => $package->id,
                    'package_status_id' => $request->package_status_id,
                    'customer_id' => $package->customer_id,
                    'payment_type' => 'stripe',
                    'charged_amount' => 0,
                    'transaction_id' => 0
                ];

                if ($package->carrier_code == 'fedex') {
                    $data['fedex_label'] = generateLabelFedex($package->id, 1);
                }

                if ($package->carrier_code == 'ups') {
                    $data['ups_label'] = generateLabelUps($package->id, 1);
                }

                if ($package->carrier_code == 'dhl') {
                    $data['dhl_label'] = generateLabelDhl($package->id, 1);
                }

                return $this->sendResponse($data, 'success');
            } else {
                // return $this->error('The value must be greater then 0',);
            }
        } catch (\Throwable $th) {

            Log::info($th);

            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];

            $response['errors'] = $th->getMessage();

            return response()->json($response, 403);
        }
    }

    public function index($id)
    {
        $package = Package::find($id);
        $this->paymentRules($package);

        return view('square.index', compact('package'));
    }

    public function squarePaymentSuccess(Request $request)
    {
        try {
            $package = Package::find($request->package_id);
            $this->paymentRules($package);

            if ($package->payment_status == 'Pending') {
                $grand_total = $package->grand_total * 100;
                $grand_total_array = explode(".", $grand_total);
                $amount = (int) $grand_total_array[0];

                $SQUARE_API_URL = config('services.square.api_url');

                $headers = [
                    'Authorization' => 'Bearer ' . config('services.square.access_token')
                ];

                // CREATE CUSTOMER
                $customer_url = $SQUARE_API_URL . '/customers';

                $customer_body = [
                    "company_name" => $package->customer->name,
                    'idempotency_key' => (string) Str::uuid(),
                ];

                $customer_response = Http::withHeaders($headers)->post($customer_url, $customer_body);
                $customer_response = json_decode($customer_response->getBody(), true);

                // CREATE CARD
                $card_url =  $SQUARE_API_URL . '/cards';

                $card_body = [
                    "card" => [
                        "cardholder_name" => $package->customer->name,
                        "customer_id" => $customer_response['customer']['id']
                    ],
                    'idempotency_key' => (string) Str::uuid(),
                    'source_id' => $request->payment_token,
                ];

                $card_response = Http::withHeaders($headers)->post($card_url, $card_body);
                $card_response = json_decode($card_response->getBody(), true);

                // CREATE PAYMENT
                $payment_url =  $SQUARE_API_URL . '/payments';

                $payment_body = [
                    'amount_money' => [
                        'amount' => $amount,
                        'currency' => 'USD',
                    ],
                    'idempotency_key' => (string) Str::uuid(),
                    'source_id' => $card_response['card']['id'],
                    'customer_id' => $customer_response['customer']['id'],
                ];

                $payment_response = Http::withHeaders($headers)->post($payment_url, $payment_body);
                $payment_response = json_decode($payment_response->getBody(), true);

                $data = [
                    'payment_module' => 'package',
                    'payment_module_id' => $package->id,
                    'customer_id' => $package->customer_id,
                    'transaction_id' => $payment_response['payment']['id'],
                    'payment_method' => 'square',
                    'charged_amount' => $payment_response['payment']['amount_money']['amount'] / 100,
                    'charged_at' => Carbon::now(),

                    'sq_customer_id' => $customer_response['customer']['id'],
                    'sq_customer_response' => json_encode($customer_response),
                    'sq_card_id' => $card_response['card']['id'],
                    'sq_card_response' => json_encode($card_response),
                    'sq_payment_id' => $payment_response['payment']['id'],
                    'sq_payment_response' => json_encode($payment_response),
                ];

                $payment = Payment::updateOrCreate([
                    'payment_module' => 'package',
                    'payment_module_id' => $package->id,
                ], $data);

                if ($payment_response['payment']['status'] === 'COMPLETED') {

                    paymentInvoiceForLabel($payment->id);

                    $package->update([
                        'payment_status' => 'Paid',
                        'package_status_id' => 5,
                        'cart' => 0,
                    ]);


                    if ($package->carrier_code == 'usps' && $package->payment_status == 'Paid') {
                        $data['usps_label'] = generateLabelUsps($package->id, 1);
                    }

                    return response()->json([
                        'status' => true,
                        'code' => 200,
                        'message' => 'success',
                    ]);
                } else {
                    // $package->update(['payment_status' => 'failed']);
                    return response()->json([
                        'status' => false,
                        'code' => 403,
                        'message' => $payment_response,
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'code' => 403,
                    'message' => 'already-paid',
                ]);
            }
        } catch (\Throwable $th) {

            Log::error($th->getMessage());
            abort(403);

            // return response()->json([
            //     'status' => false,
            //     'code' => 403,
            //     'message' => $th->getMessage(),
            // ]);
        }
    }

    public function complete()
    {
        return view('square.success');
    }

    public function laterCharge(Request $request)
    {
        try {

            $payment = Payment::where('payment_module', 'package')->where('payment_module_id', $request->package_id)->first();

            // CREATE PAYMENT
            // $payment_url = 'https://connect.squareupsandbox.com/v2/payments';
            $payment_url = 'https://connect.squareup.com/v2/payments';

            $payment_body = [
                'amount_money' => [
                    'amount' => (float) $request->amount * 100,
                    'currency' => 'USD',
                ],
                'idempotency_key' => (string) Str::uuid(),
                'source_id' => $payment->sq_card_id,
                'customer_id' => $payment->sq_customer_id,
            ];

            $headers = [
                'Authorization' => 'Bearer EAAAFIT1m3W_vYnBwzTr1M2OktU_vMDVT2tTm1OcNIcFSPa1X5oABXlHYx2P4kxN'
            ];

            $payment_response = Http::withHeaders($headers)->post($payment_url, $payment_body);
            $payment_response = json_decode($payment_response->getBody(), true);

            $data = [
                'payment_module' => 'package',
                'payment_module_id' => $payment->payment_module_id,
                'customer_id' => $payment->customer_id,
                'transaction_id' => $payment_response['payment']['id'],
                'payment_method' => 'square',
                'charged_amount' => $payment_response['payment']['amount_money']['amount'] / 100,
                'charged_at' => Carbon::now(),
                'charged_reason' => $request->charged_reason,
                'sq_payment_id' => $payment_response['payment']['id'],
                'sq_payment_response' => json_encode($payment_response),
                'recharged' => true,
                'recharged_by' => auth()->id(),
            ];

            Payment::create($data);

            return redirect()->back()->with('success', 'charge success');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function bulkPayment(Request $request)
    {
        try {
            $packages = Package::where('package_status_id', 3)->get();

            foreach ($packages as $key => $package) {
                $this->paymentRules($package);

                if ($package->grand_total > 0) {
                    $data = [
                        'package_id' => $package->id,
                        'package_status_id' => $request->package_status_id,
                        'customer_id' => $package->customer_id,
                        'payment_type' => 'stripe',
                        'charged_amount' => 0,
                        'transaction_id' => 0
                    ];
                } else {
                    abort(403);
                }
            }

            foreach ($packages as $key => $package) {

                if ($package->carrier_code == 'fedex') {
                    $data['fedex_label'] = generateLabelFedex($package->id, 1);
                }

                if ($package->carrier_code == 'ups') {
                    $data['ups_label'] = generateLabelUps($package->id, 1);
                }

                if ($package->carrier_code == 'dhl') {
                    $data['dhl_label'] = generateLabelDhl($package->id, 1);
                }
            }

            return $this->sendResponse($data, 'success');
        } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];

            $response['errors'] = $th->getMessage();

            return response()->json($response, 403);
        }
    }

    public function squareBulkPayment()
    {
        $grand_total = Package::where('package_status_id', 3)->where('payment_status', 'Pending')->get()->sum('grand_total');
        return view('square.bulk-payment', compact('grand_total'));
    }

    public function squareBulkPaymentSuccess(Request $request)
    {
        try {
            $packages = Package::where('package_status_id', 3)->where('payment_status', 'Pending')->get();
            $packages_sum = $packages->sum('grand_total');

            $grand_total = $packages_sum * 100;
            $grand_total_array = explode(".", $grand_total);
            $amount = (int) $grand_total_array[0];

            $SQUARE_API_URL = config('services.square.api_url');

            $headers = [
                'Authorization' => 'Bearer ' . config('services.square.access_token')
            ];

            // CREATE CUSTOMER
            $customer_url = $SQUARE_API_URL . '/customers';

            $customer_body = [
                "company_name" => $packages[0]->customer->name,
                'idempotency_key' => (string) Str::uuid(),
            ];

            $customer_response = Http::withHeaders($headers)->post($customer_url, $customer_body);
            $customer_response = json_decode($customer_response->getBody(), true);

            // CREATE CARD
            $card_url =  $SQUARE_API_URL . '/cards';

            $card_body = [
                "card" => [
                    "cardholder_name" => $packages[0]->customer->name,
                    "customer_id" => $customer_response['customer']['id']
                ],
                'idempotency_key' => (string) Str::uuid(),
                'source_id' => $request->payment_token,
            ];

            $card_response = Http::withHeaders($headers)->post($card_url, $card_body);
            $card_response = json_decode($card_response->getBody(), true);

            // CREATE PAYMENT
            $payment_url =  $SQUARE_API_URL . '/payments';

            $payment_body = [
                'amount_money' => [
                    'amount' => $amount,
                    'currency' => 'USD',
                ],
                'idempotency_key' => (string) Str::uuid(),
                'source_id' => $card_response['card']['id'],
                'customer_id' => $customer_response['customer']['id'],
            ];

            $payment_response = Http::withHeaders($headers)->post($payment_url, $payment_body);
            $payment_response = json_decode($payment_response->getBody(), true);

            if ($payment_response['payment']['status'] === 'COMPLETED') {

                foreach ($packages as $key => $package) {

                    $data = [
                        'payment_module' => 'package',
                        'payment_module_id' => $package->id,
                        'customer_id' => $package->customer_id,
                        'transaction_id' => $payment_response['payment']['id'],
                        'payment_method' => 'square',
                        'charged_amount' => $payment_response['payment']['amount_money']['amount'] / 100,
                        'charged_at' => Carbon::now(),

                        'sq_customer_id' => $customer_response['customer']['id'],
                        'sq_customer_response' => json_encode($customer_response),
                        'sq_card_id' => $card_response['card']['id'],
                        'sq_card_response' => json_encode($card_response),
                        'sq_payment_id' => $payment_response['payment']['id'],
                        'sq_payment_response' => json_encode($payment_response),
                    ];

                    $payment = Payment::updateOrCreate([
                        'payment_module' => 'package',
                        'payment_module_id' => $package->id,
                    ], $data);

                    paymentInvoiceForLabel($payment->id);

                    $package->update([
                        'payment_status' => 'Paid',
                        'package_status_id' => 5,
                        'cart' => 0,
                    ]);
                }
            }

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'success',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'code' => 403,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
