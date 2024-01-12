<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Invoice - {{ $payment->id }}</title>

    <style>
        body {
            font-family: 'Archivo Narrow', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            vertical-align: top;
            border: 1px solid #8a8a8a;
            /* border-collapse: collapse; */
            padding: 0.3em;
            caption-side: bottom;
            font-size: 10px;
            text-wrap: inherit;
        }

        th {
            font-weight: bolder;
            text-align: center;
        }



        caption {
            padding: 0.3em;
        }
    </style>
</head>

<body>

    <h5 style="text-align:center;"> PAYMENT INVOICE </h5>

    <h5><strong>INVOICE NUMBER: {{ $payment->id }} </strong></h5>
    <table class="border" style="width: 100%">
        <tr>
            <td colspan="4">
                <h3>Invoiced From</h3>
                <strong>ShippingXPS</strong><br>
                {{ $package->warehouse->name ?? $order->warehouse->name ?? $warehouse->name ?? '- -' }}<br>
                Address:
                @if(isset($package->warehouse))
                {{ $package->warehouse->address}},{{ $package->warehouse->city }},{{ $package->warehouse->state }}{{
                ','.$package->warehouse->zip ?? '' }}
                @elseif(isset($order->warehouse))
                {{ $order->warehouse->address}},{{ $order->warehouse->city }},{{ $order->warehouse->state }}{{
                ','.$order->warehouse->zip ?? '' }}
                @elseif(isset($warehouse))
                {{ $warehouse->address}},{{ $warehouse->city }},{{ $warehouse->state }}{{ ','.$warehouse->zip ?? '' }}
                @else
                - -
                @endif
                <br>
                Phone: {{ $package->warehouse->phone ?? $order->warehouse->phone ?? $warehouse->phone ?? '- -' }}<br>
                Email : {{ $package->warehouse->email ?? $order->warehouse->email ?? $warehouse->email ?? '- -' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h3>Invoice To:</h3>
                <strong>{{ $address->fullname ?? '' }}</strong><br>
                Address:{{ $address->address ?? '' }}<br>
                Phone: {{ $address->phone ?? '' }}<br>
                Email: {{ $address->email ?? $customer->email ?? '' }}
            </td>
            <td colspan="2">
                @if ($billing)
                <h3>Bill To:</h3>
                <strong>{{ $billing->fullname ?? '' }}</strong><br>
                Address:{{ $billing->address ?? '' }}<br>
                Phone: {{ $billing->phone ?? '' }}<br>
                Email: {{ $billing->email ?? '' }}
                @endif
            </td>
        </tr>
    </table>

    <br>

    @isset($order)
    <span style="font-size:10px"><b>ITEMS:</b></span>
    <table class="border" style="width: 100%">
        @foreach ($order_items as $oi)
        <tr>
            <td>{{$oi->name}} - {{$oi->description}} x {{$oi->quantity}}</td>
            <td style="width: 100px">${{ format_number($oi->unit_price) }}</td>
        </tr>
        @endforeach
    </table>
    @endisset
    
    <br>

    <span style="font-size:10px"><b>CHARGES:</b></span>

    @isset($package)
    <table class="border" style="width: 100%">

        @if (isset($service_requests))
        @foreach($service_requests as $item)
        <tr>
            <td style="width:85%">{{ $item->service->title }}</td>
            <td style="width:15%">
                @if($item->service->id == 1)
                ${{ $item->service->price + $package->orders->count() * 1.5 }}
                @else
                ${{ format_number($item->service->price) }}
                @endif
            </td>
        </tr>
        @endforeach
        @endif

        @if ($package->consolidation_fee > 0)
        <tr>
            <td>Consolidation</td>
            <td>${{ format_number($package->consolidation_fee) }}</td>
        </tr>
        @endif

        <tr>
            <td style="width:85%">Mail Fee</td>
            <td style="width:15%">${{format_number($mailout_fee)}}</td>
        </tr>

        @if ($package->storage_fee > 0)
        <tr>
            <td style="width:85%">Storage Fee</td>
            <td style="width:15%">${{ format_number($package->storage_fee) }}</td>
        </tr>
        @endif

        @if ($package->shipping_charges > 0)
        <tr>
            <td style="width:85%">Shipping Service - {{ $package->service_label }}</td>
            <td style="width:15%">${{ format_number($package->shipping_charges) }}</td>
        </tr>
        @endif
    </table>
    @endisset

    @isset($order)
    <table class="border" style="width: 100%">
        <tr>
            <td>
                Order Items Total
            </td>
            <td style="width: 100px">${{ format_number($order->sub_total) }}</td>
        </tr>
        <tr>
            <td>
                Service Charges
            </td>
            <td style="width: 100px">${{ format_number($order->service_charges) }}</td>
        </tr>
        <tr>
            <td>
                Shipping From Shop
            </td>
            <td style="width: 100px">${{ format_number($order->shipping_from_shop) }}</td>
        </tr>
        <tr>
            <td>
                Pickup Charges
            </td>

            <td style="width: 100px">${{ format_number($order->pickup_charges) ?? 0.00 }}</td>
        </tr>
    </table>
    @endisset


    @isset($additionalRequest)
    <table class="border" style="width: 100%">
        <tr>
            <td>
                Additional Charges For Request - {{ $additionalRequest->message ?? '- -' }}
            </td>

            <td style="width: 100px">${{ format_number($additionalRequest->price) }}</td>
        </tr>
    </table>
    @endisset


    @isset($insuranceRequest)
    <table class="border" style="width: 100%">
        <tr>
            <td>
                Charges For Insurance Request with Shipping
            </td>

            <td style="width: 100px">${{ format_number($insuranceRequest->amount) }}</td>
        </tr>
    </table>
    @endisset

    @isset($giftCard)
    <table class="border" style="width: 100%">
        <tr>
            <td>
                <span style="text-transform:uppercase">{{$giftCard->type}} Gift Card</span> x {{$giftCard->qty}}
            </td>
            <td style="width: 100px; padding:10px;">${{ format_number($giftCard->amount) }}</td>
        </tr>
    </table>
    @endisset

    <br>
    <table style="width: 100%">
        <tr>
            <th colspan="2" style="text-align: right">
                Subtotal :
                ${{ format_number($payment->charged_amount + $payment->discount - $payment->paypal_fee)}}
                <br>
                @if ($payment->discount > 0)
                Discount : ${{ format_number($payment->discount) }} <br>
                @endif
                @if ($payment->paypal_fee > 0)
                Paypal Fee : ${{ format_number($payment->paypal_fee) }} <br>
                @endif
                Grand Total : ${{format_number($payment->charged_amount) }}
            </th>
        </tr>
    </table>

    <br>
    <table class="border" style="width:100%;">
        <tr>
            <th>Invoice</th>
            <th>Date</th>
            <th>Status</th>
            <th>Amount</th>
        </tr>
        <tr>
            <td>
                @if (isset($payment->package->boxes[0]->tracking_out) && $payment->package->pkg_type == 'single')
                Tracking Number: {{$payment->package->boxes[0]->tracking_out}} <br>
                @endif
                Transaction ID: {{$payment->transaction_id}} <br>
                Payment Type: {{$payment->payment_type}} <br>
                Payment Method: Card <br>
            </td>
            <td>{{ date('d-m-Y',strtotime($payment->charged_at)) }}</td>
            <td>Paid</td>
            <td>${{ format_number($payment->charged_amount) }}</td>
        </tr>
    </table>

    <br>

    <p style="font-size: 10px">
        I possess an electronic signature confirming both the initiation and authorization of the payment, which was made by me.
    </p>
</body>

</html>