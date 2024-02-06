<?php

namespace App\Http\Controllers;

use App\Events\PaymentEventHandler;
use App\Models\AdditionalRequest;
use App\Models\Address;
use App\Models\Auction;
use App\Models\Coupon;
use App\Models\CustomerCoupon;
use App\Models\GiftCard;
use App\Models\InsuranceRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\Payment;
use App\Models\SiteSetting;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payment_module = $request->payment_module;

        if (!in_array($payment_module, ['package', 'gift_card', 'order', 'auction'])) {
            return redirect()->back()->with('error', 'PAYMENT DENIED!');
        }

        $shipping_address = [];
        $status = NULL;
        $user = Auth::user();

        if ($payment_module == 'package') {
            $package = Package::query()
                ->where('payment_status', 'Pending')
                ->where('id', $request->package_id)
                ->where('customer_id', $user->id)
                // ->where('grand_total', '>', 0)
                ->firstOrFail();

            if ($package->grand_total <= 0) {
                abort(403, 'The amount is less then 0');
            }

            $amount = $package->grand_total;
            $payment_module_id = $package->id;
        }

        if ($payment_module == 'gift_card') {
            $gift_card = GiftCard::query()
                ->where('payment_status', 'Pending')
                ->where('id', $request->payment_module_id)
                ->where('user_id', $user->id)
                ->where('final_amount', '>', 0)
                ->firstOrFail();
            $amount = $gift_card->final_amount;
            $payment_module_id = $gift_card->id;
        }

        if ($payment_module == 'order') {
            $order = Order::query()
                ->where('payment_status', 'Pending')
                ->where('id', $request->payment_module_id)
                ->where('customer_id', $user->id)
                ->where('grand_total', '>', 0)
                ->firstOrFail();
            $amount = $order->grand_total;
            $payment_module_id = $order->id;
        }

        if ($payment_module != 'package') {
            $addresss = Address::where('user_id', $user->id)->get();
            foreach ($addresss as $address) {
                $shipping_address[$address->id] = [
                    'id' => $address->id,
                    'label' => $address->fullname . ", " . $address->city . ", " . $address->state . ", " . $address->zip_code,
                ];
            }
        }

        if ($payment_module == 'auction') {
            $auction = Auction::query()
                ->where('bought_at', NULL)
                ->where('id', $request->auction_id)
                ->where('winner_id', $user->id)
                ->firstOrFail();

            $amount = $auction->final_price;
            $payment_module_id = $auction->id;
        }


        if ($amount <= 0) {
            abort(403, 'The amount is less then 0');
        }

        $paypal_processing_fee = SiteSetting::where('name', 'paypal_processing_percentage')->first()->value ?? 0.00;
        $paypal_charged_amount = ($amount * $paypal_processing_fee / 100) + $amount;
        $paypal_charged_amount =  number_format((float)$paypal_charged_amount, 2, '.', '');

        return Inertia::render(
            'Payment/OrderPayment',
            [
                'status' => $status,
                'amount' => $amount,
                'payment_module' => $payment_module,
                'payment_module_id' => $payment_module_id,
                'shipping_address' => $shipping_address,
                'paypal_processing_fee' => $paypal_processing_fee,
                'paypal_charged_amount' => $paypal_charged_amount,
            ]
        );
    }

    // AUTHORIZE NET - PAYMENT SUCCESS
    public function pay(Request $request)
    {
        // dd($request->all());
        if (!in_array($request->payment_module_type, ['package', 'gift_card', 'order'])) {
            return redirect()->back()->with('error', 'PAYMENT DENIED!');
        }

        $date = $request->year . "-" . $request->month . "-1 00:00:00";
        $checkDate = new Carbon($date);

        if (strtotime($checkDate) < strtotime(Carbon::now())) {
            return response()->json([
                'status' => 0,
                'message' => 'CARD EXPIRED!'
            ]);
        }

        $amount = doubleval($request->amount);
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorizeAnet.merchant_login_id'));
        $merchantAuthentication->setTransactionKey(config('services.authorizeAnet.merchant_transaction_key'));
        $refId = 'ref' . time();
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($request->card_no);
        $creditCard->setExpirationDate($request->year . "-" . $request->month);
        $creditCard->setCardCode($request->cvv);
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        $user = Auth::user();
        $lastPayment = Payment::latest()->first();
        $invoiceID = sprintf("%05d", ++$lastPayment->id);

        // Create order information
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($invoiceID);
        $order->setDescription('SHIPPINGXPS_PAYMENT');

        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($request->first_name ?? '');
        $customerAddress->setLastName($request->last_name ?? '');
        $customerAddress->setCompany("");
        $customerAddress->setAddress($request->address ?? '');
        $customerAddress->setCity($request->city ?? '');
        $customerAddress->setState($request->state ?? '');
        $customerAddress->setZip($request->zip ?? 'None');
        $customerAddress->setCountry($request->country ?? '');

        $billing_address = [
            'email' => $request->email ?? $user->email ?? '',
            'fullname' => $request->first_name . ' ' . $request->last_name ?? '',
            'phone' => $request->phone_no ?? '',
            'address' => $request->address . ', ' . $request->city . ', ' . $request->zip . ', ' . $request->country ?? '',
        ];


        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId($user->id);
        $customerData->setEmail($request->email);

        // Add values for transaction settings
        $duplicateWindowSetting = new AnetAPI\SettingType();
        $duplicateWindowSetting->setSettingName("duplicateWindow");
        $duplicateWindowSetting->setSettingValue("60");

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);
        $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);

        // Assemble the complete transaction request
        $transaction = new AnetAPI\CreateTransactionRequest();
        $transaction->setMerchantAuthentication($merchantAuthentication);
        $transaction->setRefId($refId);
        $transaction->setTransactionRequest($transactionRequestType);


        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($transaction);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        // $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        // Check to see if the API request was successfully received and acted upon
        if ($response->getMessages()->getResultCode() == "Ok") {
            // Since the API request was successful, look for a transaction response
            // and parse it to display the results of authorizing the card
            $tresponse = $response->getTransactionResponse();

            if ($tresponse != null && $tresponse->getMessages() != null) {

                $payment = new Payment();
                $payment->customer_id = $user->id;

                $payment->order_id = $request->payment_module_type == 'order' ? $request->payment_module_id : null;
                $payment->package_id = $request->payment_module_type == 'package' ? $request->payment_module_id : null;
                $payment->additional_request_id = $request->payment_module_type == 'additional_request' ? $request->payment_module_id : null;
                $payment->insurance_id = $request->payment_module_type == 'insurance' ? $request->payment_module_id : null;
                $payment->gift_card_id = $request->payment_module_type == 'gift_card' ? $request->payment_module_id : null;

                $payment->transaction_id = $response->getTransactionResponse()->getTransId();
                $payment->charged_amount = $amount;
                $payment->discount = 0;
                $payment->charged_at = Carbon::now()->format('Y-m-d H:i:s');
                $payment->save();
                $payment->invoice_id = $invoiceID;
                $payment->billing_address = $billing_address;
                $payment->save();

                $shipping = [];
                if ($request->has('shipping_address_id') && $request->get('shipping_address_id') != null) {
                    $shippingAddress = Address::where('id', $request->shipping_address_id)->first();
                    $shipping['email'] = $user->email ?? '';
                    $shipping['fullname'] = $shippingAddress->fullname ?? '';
                    $shipping['phone'] = $shippingAddress->phone ?? '';
                    $shipping['address'] = $shippingAddress->address . ', ' . $shippingAddress->city . ', ' . $shippingAddress->state . ', ' . $shippingAddress->country_name ?? '';
                } else {
                    $shippingAddress = Address::where('user_id', $user->id)->first();
                    $shipping['email'] = $user->email ?? '';
                    $shipping['fullname'] = $shippingAddress->fullname ?? '';
                    $shipping['phone'] = $shippingAddress->phone ?? '';
                    $shipping['address'] = $shippingAddress->address . ', ' . $shippingAddress->city . ', ' . $shippingAddress->state . ', ' . $shippingAddress->country_name ?? '';
                }

                $payment_module_id = $request->payment_module_id;
                if ($request->payment_module_type == 'order') {
                    $order = Order::find($payment_module_id);
                    $order->payment_status = "Paid";
                    $order->save();
                }

                if ($request->payment_module_type == 'additional_request') {
                    $additionalRequest = AdditionalRequest::find($payment_module_id);
                    $additionalRequest->payment_status = "Paid";
                    $additionalRequest->save();
                }

                if ($request->payment_module_type == 'insurance') {
                    $insuranceRequest = InsuranceRequest::find($payment_module_id);
                    $insuranceRequest->payment_status = "Paid";
                    $insuranceRequest->save();
                    $package = Package::find($insuranceRequest->package_id);
                    $package->payment_status = "Paid";
                    $package->save();
                }

                if ($request->payment_module_type == 'gift_card') {
                    $gift_card = GiftCard::find($payment_module_id);
                    $gift_card->payment_status = "Paid";
                    $gift_card->save();
                }

                if ($request->payment_module_type == 'auction') {
                    $auction = Auction::find($payment_module_id);
                    $auction->payment_status = "Paid";
                    $auction->save();
                }

                if ($request->payment_module_type == 'package') {
                    $package = Package::find($payment_module_id);
                    $package->payment_status = "Paid";
                    $package->save();

                    if ($package->address_book_id != null) {
                        $shippingAddress = Address::where('id', $package->address_book_id)->first();
                        $shipping['email'] = $user->email ?? '';
                        $shipping['fullname'] = $shippingAddress->fullname ?? '';
                        $shipping['phone'] = $shippingAddress->phone ?? '';
                        $shipping['address'] = $shippingAddress->address . ', ' . $shippingAddress->city . ', ' . $shippingAddress->state . ', ' . $shippingAddress->country_name ?? '';
                    } else {
                        $shippingAddress = Address::where('user_id', $user->id)->first();
                        $shipping['email'] = $user->email ?? '';
                        $shipping['fullname'] = $shippingAddress->fullname ?? '';
                        $shipping['phone'] = $shippingAddress->phone ?? '';
                        $shipping['address'] = $shippingAddress->address . ', ' . $shippingAddress->city . ', ' . $shippingAddress->state . ', ' . $shippingAddress->country_name ?? '';
                    }

                    if ($request->has('coupon_code_id') && $request->has('coupon_code')) {
                        if ($request->coupon_code_id != null) {
                            $customerCoupon = new CustomerCoupon();
                            $customerCoupon->customer_id = $user->id;
                            $customerCoupon->coupon_id = $request->coupon_code_id;
                            $customerCoupon->save();
                        }
                    }
                }


                try {
                    event(new PaymentEventHandler($payment));
                } catch (\Throwable $th) {
                    //throw $th;
                }

                return response()->json([
                    'status' => 1,
                    'message' => 'PAYMENT_SUCCESS',
                    'payment_id' => $payment->id,
                ]);
            } else {
                if ($tresponse->getErrors() != null) {
                    return response()->json([
                        'status' => 0,
                        'error_code' => $tresponse->getErrors()[0]->getErrorCode(),
                        'message' => $tresponse->getErrors()[0]->getErrorText()
                    ]);
                }
            }
        } else {
            $tresponse = $response->getTransactionResponse();

            if ($tresponse != null && $tresponse->getErrors() != null) {
                return response()->json([
                    'status' => 0,
                    'error_code' => $tresponse->getErrors()[0]->getErrorCode(),
                    'message' => $tresponse->getErrors()[0]->getErrorText()
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'error_code' => $response->getMessages()->getMessage()[0]->getCode(),
                    'message' => $response->getMessages()->getMessage()[0]->getText()
                ]);
            }
        }
    }

    //  PayPal Payment Page
    public function payPalInit(Request $request)
    {
        $data = $request->all();
        if (isset($data['amount']) && $data['amount'] > 0) {
            return Inertia::render(
                'Payment/PayPalPayment',
                [
                    'dataResponse' => $data
                ]
            );
        } else {
            return redirect()->route('dashboard');
        }
    }

    // PAYPAL - PAYMENT SUCCESS
    public function payPalSuccess(Request $request)
    {
        $user = Auth::user();
        $description = json_decode($request->payment_detail, true);
        $shipping = $description['purchase_units'][0]['shipping'];
        $billing['email'] = $description['payer']['email_address'] ?? '';
        $billing['fullname'] = $shipping['name']['full_name'] ?? '';

        $billing['address'] = null;
        if (!empty($shipping['address'])) {
            foreach ($shipping['address'] as $key => $address) {
                $billing['address'] .= $address . (($key) == 'country_code' ? '' : ', ');
            }
        }

        $shipping = [];
        if ($request->has('shipping_address_id') && $request->get('shipping_address_id') != null) {
            $shippingAddress = Address::where('id', $request->shipping_address_id)->first();
            $shipping['email'] = $description['payer']['email_address'] ?? '';
            $shipping['fullname'] = $shippingAddress->fullname ?? '';
            $shipping['phone'] = $shippingAddress->phone ?? '';
            $shipping['address'] = $shippingAddress->address . ', ' . $shippingAddress->city . ', ' . $shippingAddress->state . ', ' . $shippingAddress->country_name ?? '';
        } else {
            $shippingAddress = Address::where('user_id', $user->id)->first();

            $shipping['email'] = $description['payer']['email_address'] ?? '';
            $shipping['fullname'] = $shippingAddress->fullname ?? '';
            $shipping['phone'] = $shippingAddress->phone ?? '';
            $shipping['address'] = $shippingAddress->address . ', ' . $shippingAddress->city . ', ' . $shippingAddress->state . ', ' . $shippingAddress->country_name ?? '';
        }

        $discount = 0.00;
        $amount = doubleval($request->amount);
        $discount = (float)number_format($discount, 2);
        $lastPayment = Payment::latest()->first();
        $invoiceID = sprintf("%05d", ++$lastPayment->id);
        $payment = new Payment();
        $payment->customer_id = $user->id;

        $payment->order_id = $request->payment_module_type == 'order' ? $request->payment_module_id : NULL;
        $payment->package_id = $request->payment_module_type == 'package' ? $request->payment_module_id : NULL;
        $payment->additional_request_id = $request->payment_module_type == 'additional-request' ? $request->payment_module_id : NULL;
        $payment->insurance_id = $request->payment_module_type == 'insurance' ? $request->payment_module_id : NULL;
        $payment->gift_card_id = $request->payment_module_type == 'gift-card' ? $request->payment_module_id : NULL;

        $payment->transaction_id = $request->transaction_id ?? $invoiceID;
        $payment->payment_type = 'PayPal';
        $paypal_pecentage = SiteSetting::where('name', 'paypal_processing_percentage')->first()->value ?? 0;
        $paypal_fee = ($amount * $paypal_pecentage / 100);
        $payment->paypal_fee = $paypal_fee;
        $payment->charged_amount = $amount;
        $payment->discount = $discount;
        $payment->charged_at = Carbon::now()->format('Y-m-d H:i:s');
        $payment->save();
        $invoiceID =  sprintf("%05d", $payment->id);
        $payment->invoice_id = $invoiceID;
        $payment->billing_address = $billing;
        $payment->save();

        $payment_module_id = $request->payment_module_id;
        if ($request->payment_module_type == 'order') {
            $order = Order::find($payment_module_id);
            $order->payment_status = "Paid";
            $order->save();
        }

        if ($request->payment_module_type == 'additional_request') {
            $additionalRequest = AdditionalRequest::find($payment_module_id);
            $additionalRequest->payment_status = "Paid";
            $additionalRequest->save();
        }

        if ($request->payment_module_type == 'insurance') {
            $insuranceRequest = InsuranceRequest::find($payment_module_id);
            $insuranceRequest->payment_status = "Paid";
            $insuranceRequest->save();
            $package = Package::find($insuranceRequest->package_id);
            $package->payment_status = "Paid";
            $package->save();
        }

        if ($request->payment_module_type == 'gift_card') {
            $gift_card = GiftCard::find($payment_module_id);
            $gift_card->payment_status = "Paid";
            $gift_card->save();
        }

        if ($request->payment_module_type == 'auction') {
            $auction = Auction::find($payment_module_id);
            $auction->payment_status = "Paid";
            $auction->save();
        }

        if ($request->payment_module_type == 'package') {
            $package = Package::find($payment_module_id);
            $package->payment_status = "Paid";
            $package->save();

            if ($package->address_book_id != null) {
                $shippingAddress = Address::where('id', $package->address_book_id)->first();
                $shipping['email'] = $description['payer']['email_address'] ?? '';
                $shipping['fullname'] = $shippingAddress->fullname ?? '';
                $shipping['phone'] = $shippingAddress->phone ?? '';
                $shipping['address'] = $shippingAddress->address . ', ' . $shippingAddress->city . ', ' . $shippingAddress->state . ', ' . $shippingAddress->country_name ?? '';
            } else {
                $shippingAddress = Address::where('user_id', $user->id)->first();
                $shipping['email'] = $description['payer']['email_address'] ?? '';
                $shipping['fullname'] = $shippingAddress->fullname ?? '';
                $shipping['phone'] = $shippingAddress->phone ?? '';
                $shipping['address'] = $shippingAddress->address . ', ' . $shippingAddress->city . ', ' . $shippingAddress->state . ', ' . $shippingAddress->country_name ?? '';
            }

            if ($request->has('coupon_code_id') && $request->has('coupon_code')) {
                if ($request->coupon_code_id != null) {
                    $customerCoupon = new CustomerCoupon();
                    $customerCoupon->customer_id = $user->id;
                    $customerCoupon->coupon_id = $request->coupon_code_id;
                    $customerCoupon->save();
                }
            }
        }


        try {
            event(new PaymentEventHandler($payment));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json([
            'status' => 1,
            'message' => 'Paypal',
            'payment_id' => $payment->id,
        ]);
    }

    public function getPayments(Request $request)
    {
        $user = Auth::user();

        $payments = Payment::with(['customer', 'package' => function ($query) {
            $query->with('address', function ($qry) {
                $qry->with('country');
            });
        }, 'order'])
            ->when($user->type == 'customer', function ($qry) use ($user) {
                $qry->where('customer_id', $user->id);
            })
            ->orderBy('id', 'desc');


        if ($request->isMethod('post')) {

            $payments = $this->searchPayments($request, $payments);

            $perPage = 10;

            if ($request->has('per_page') && $request->get('per_page') != NULL) {
                $perPage = $request->get('per_page');
            }

            return response([
                'payments' => $payments->paginate($perPage),
            ]);
        }


        return Inertia::render('Payment/Index', ['payments' => $payments->paginate(10)]);
    }

    // PAYMENT SUCCESS PAGE FOR BOTH PAYPAL & AUTHORIZE 
    public function paymentSuccess($id)
    {
        try {
            $payment = Payment::find($id);

            return Inertia::render('Payment/PaymentSuccess', [
                'payment' => $payment,
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('dashboard')->with('something went wrong');
        }
    }

    public function checkCoupon(Request $request)
    {
        // dd($request->code);
        $customer = Auth::user();
        $code = $request->code;

        $coupon = Coupon::where('code', $code)->first();

        if ($coupon != null) {
            $strCheck = strcmp($code, $coupon->code);
            if ($strCheck == 0) {
                $checkCode = CustomerCoupon::where('coupon_id', $coupon->id)->where('customer_id', $customer->id)->get();
                if ($checkCode->count() > 0) {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Coupon already used',
                    ]);
                } else {
                    if (\Session::has('amount')) {
                        $amount = \Session::get('amount');
                        $discount = $amount * ($coupon->discount / 100);
                        $newAmount = $amount - $discount;
                        \Session::put('amount', $newAmount);
                    }
                    return response()->json([
                        'status' => 1,
                        'message' => 'Coupon Applied',
                        'discount' => $coupon->discount,
                        'coupon_id' => $coupon->id,
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Coupon is invalid',
                ]);
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Coupon is invalid',
            ]);
        }
    }

    public function generateReport($paymentID)
    {
        $payment = Payment::where('id', $paymentID)
            ->with(['customer', 'package', 'order'])
            ->when(Auth::user()->type == 'customer', function ($qry) {
                $qry->where('customer_id', Auth::user()->id);
            })->firstOrFail();


        $html = view('pdfs.report', [
            'payment' => $payment,

        ])->render();


        try {
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetFooter('ShippingXPS||Payment Report');
            $mpdf->WriteHTML($html);
            $mpdf->Output($payment->customer->name . '_' . Carbon::now()->format('Ymdhis') . '.pdf', \Mpdf\Output\Destination::INLINE);
        } catch (\Throwable $e) {
            \Log::info($e);
        }
    }

    public function generateReportList()
    {
        $payments = Payment::all();


        $html = view('pdfs.reports', [
            'payments' => $payments,
        ])->render();

        try {
            $mpdf = new \Mpdf\Mpdf();
            // $mpdf->SetWatermarkImage('http://shippingxps.com/wp-content/uploads/2022/09/logo-1.png','0.2','50%');
            // $mpdf->showWatermarkImage = true;
            $mpdf->SetFooter('ShippingXPS|Payment Report|{PAGENO}');
            $mpdf->WriteHTML($html);
            $mpdf->Output('Payment_Report_' . Carbon::now()->format('Ymdhis') . '.pdf', \Mpdf\Output\Destination::INLINE);
        } catch (\Throwable $e) {
            \Log::info($e);
        }
    }

    public function searchPayments(Request $request, $payments)
    {
        $search_invoice_no = $request->search_invoice_no;
        $search_suit_no = $request->search_suit_no;

        $payments->when($search_invoice_no && !empty($search_invoice_no), function ($qry) use ($search_invoice_no) {
            $qry->where('id', $search_invoice_no);
        });

        $payments->when($search_suit_no && !empty($search_suit_no), function ($qry) use ($search_suit_no) {
            $suit_no = (int) $search_suit_no;
            $suit_no = $suit_no - 4000;
            $qry->whereHas('customer', function ($q) use ($suit_no) {
                $q->where('id', $suit_no);
            });
        });

        // $payments->where(function ($query) use ($search) {
        //     $query->where('id', 'LIKE', "%$search%")
        //         ->orWhere('transaction_id', 'LIKE', "%$search%")
        //         ->orWhere('package_id', 'LIKE', "%$search%")
        //         ->orWhere('invoice_id', 'LIKE', "%$search%")
        //         ->orWhere('charged_amount', 'LIKE', "%$search%");
        // })->orWhereHas('customer', function ($qry) use ($search) {
        //     $qry->where('name', 'LIKE', '%' . $search . '%');
        //     if (is_numeric($search)) {
        //         $s = (int)$search;
        //         $s = $s - 4000;
        //         $qry->orWhere('id', 'LIKE', '%' . $s . '%');
        //     }
        // })->orWhereHas('package', function ($qry) use ($search) {
        //     $qry->where('payment_status', 'LIKE', "%$search%")
        //         ->orWhere('service_label', 'LIKE', "%$search%")
        //         ->orWhere('shipping_charges', 'LIKE', "%$search%");
        // })->orWhereHas('order', function ($qry) use ($search) {
        //     $qry->where('id', 'LIKE', '%' . $search . '%');
        // });

        if ($request->has('date_selection') && $request->get('date_selection') != NULL) {
            if ($request->get('date_selection') == '1') {
                $payments->whereDate('created_at', Carbon::today());
            }
            if ($request->get('date_selection') == '2') {
                $payments->whereDate('created_at', Carbon::yesterday());
            }
            if ($request->get('date_selection') == '3') {
                $date = Carbon::now()->subDays(7);
                $payments->where('created_at', '>=', $date);
            }
            if ($request->get('date_selection') == '4') {
                $date = Carbon::now()->subDays(30);
                $payments->where('created_at', '>=', $date);
            }
            if ($request->date_selection == 5) {
                if ($request->get('date_range')) {
                    $dateRange = explode(' - ', $request->date_range);
                    $from = date("Y-m-d", strtotime($dateRange[0]));
                    $to = date("Y-m-d", strtotime($dateRange[1]));
                    $payments->whereBetween('created_at', [$from, $to]);
                }
            }
        }

        return $payments;
    }

    public function invoice($id)
    {
        $payment = Payment::find($id);
        $package = Package::where('id', $payment->payment_module_id)->first();
        $ship_from = Address::find($package->ship_from);
        $ship_to = Address::find($package->ship_to);

        view()->share([
            'payment' => $payment,
            'package' => $package,
            'ship_from' => $ship_from,
            'ship_to' => $ship_to
        ]);

        $pdf = PDF::loadView('pdfs.payment-invoice');
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('payment-invoice.pdf', array("Attachment" => false));
    }

    public function addPayment(Request $request)
    {

        // dd($request->all());

        $data = $request->validate([
            'transaction_id' => 'required',
            'payment_type' => 'required',
            'payment_module_id' => 'required',
            'payment_module' => 'required|in:package,order',
        ]);

        $pm = $request->payment_module;
        $pmi = $request->payment_module_id;

        if ($pm == 'package') {
            $package = Package::where('id', $pmi)->first();

            if ($package->payment_status != 'Paid') {
                Payment::create([
                    'customer_id' => $package->customer_id,
                    'package_id' => $package->id,
                    'transaction_id' => $data['transaction_id'],
                    'payment_type' => $data['payment_type'],
                    'charged_amount' => $package->grand_total,
                    'charged_at' => Carbon::now()
                ]);
            }

            $package->update(['payment_status' => 'Paid']);
        }

        if ($pm == 'order') {
            $order = Order::where('id', $pmi)->first();

            if ($order->payment_status != 'Paid') {
                Payment::create([
                    'customer_id' => $order->customer_id,
                    'order_id' => $order->id,
                    'transaction_id' => $data['transaction_id'],
                    'payment_type' => $data['payment_type'],
                    'charged_amount' => $order->grand_total,
                    'charged_at' => Carbon::now()
                ]);
            }

            $order->update(['payment_status' => 'Paid']);
        }

        return redirect()->back();
    }

    // public function stripeChargeLater(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $payment_module = $request->payment_module;

    //         if (!in_array($payment_module, ['package'])) {
    //             return redirect()->back()->with('error', 'PAYMENT DENIED!');
    //         }

    //         if ($payment_module == 'package') {
    //             $payment = Payment::where('package_id', $request->package_id)->first();
    //         }

    //         $stripe = new \Stripe\StripeClient(config('app.stripe_secret_key'));
    //         $method = $stripe->paymentMethods->all([
    //             'customer' => $payment->stripe_customer_id,
    //             'type' => 'card',
    //         ])->toArray();

    //         $pm_id = $method['data'][0]['id'];

    //         \Stripe\Stripe::setApiKey(config('app.stripe_secret_key'));

    //         try {
    //             $intent =  \Stripe\PaymentIntent::create([
    //                 'amount' => $request->amount * 100,
    //                 'currency' => 'usd',
    //                 'automatic_payment_methods' => ['enabled' => true],
    //                 'customer' => $payment->stripe_customer_id,
    //                 'payment_method' => $pm_id,
    //                 // 'return_url' => 'https://example.com/order/123/complete',
    //                 'off_session' => true,
    //                 'confirm' => true,
    //             ])->toArray();

    //             $data = [
    //                 'package_id' => $payment->package_id,
    //                 'customer_id' => $payment->customer_id,
    //                 'payment_type' => 'stripe',
    //                 'charged_amount' => $intent['amount'] / 100,
    //                 'charged_at' => Carbon::now(),
    //                 'transaction_id' => 0,
    //                 'stripe_customer_id' => $intent['customer'],
    //                 'stripe_payment_id' => $intent['id'],
    //                 'stripe_client_secret' => $intent['client_secret'],
    //             ];

    //             Payment::create($data);

    //             DB::commit();
    //             return redirect()->back()->with('success', 'charge success');
    //         } catch (\Stripe\Exception\CardException $e) {
    //             echo 'Error code is:' . $e->getError()->code;
    //             // $payment_intent_id = $e->getError()->payment_intent->id;
    //             // $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
    //         }
    //     } catch (\Throwable $th) {
    //         DB::rollback();
    //         return redirect()->back()->with('error', $th->getMessage());
    //     }
    // }

    public function squareChargeLater(Request $request)
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
            'sq_payment_id' => $payment_response['payment']['id'],
            'sq_payment_response' => json_encode($payment_response),
        ];

        Payment::create($data);

        return redirect()->back()->with('success', 'charge success');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'payment error');

        }
    }
}
