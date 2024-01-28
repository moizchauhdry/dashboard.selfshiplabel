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
                <strong>{{$ship_from->fullname}}</strong><br>
                <strong>Phone</strong>: {{ $ship_from->phone ?? ''}}<br>
                <strong>E-mail</strong>: {{ $ship_from->email ?? ''}}<br>
                <strong>Address</strong>:<br>
                {{ $ship_from->address ?? ''}} <br>
                {{ $ship_from->address_2 ?? ''}} <br>
                {{ $ship_from->address_3 ?? ''}} <br>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h3>Invoice To:</h3>
                <strong>{{$ship_to->fullname}}</strong><br>
                <strong>Phone</strong>: {{ $ship_to->phone ?? ''}}<br>
                <strong>E-mail</strong>: {{ $ship_to->email ?? ''}}<br>
                <strong>Address</strong>:<br>
                {{ $ship_to->address ?? ''}} <br>
                {{ $ship_to->address_2 ?? ''}} <br>
                {{ $ship_to->address_3 ?? ''}} <br>
            </td>
            <td colspan="2">
                <h3>Bill To:</h3>

                <strong>{{$ship_to->fullname}}</strong><br>
                <strong>Phone</strong>: {{ $ship_to->phone ?? ''}}<br>
                <strong>E-mail</strong>: {{ $ship_to->email ?? ''}}<br>
                <strong>Address</strong>:<br>
                {{ $ship_to->address ?? ''}} <br>
                {{ $ship_to->address_2 ?? ''}} <br>
                {{ $ship_to->address_3 ?? ''}} <br>
            </td>
        </tr>
    </table>

    <span style="font-size:10px"><b>CHARGES:</b></span>

    @isset($package)
    <table class="border" style="width: 100%">
        @if ($package->shipping_charges > 0)
        <tr>
            <td style="width:85%">Shipping Service - {{ $package->service_label }}</td>
            <td style="width:15%">${{ format_number($package->shipping_charges) }}</td>
        </tr>
        @endif
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
                @if (isset($payment->package->boxes[0]->tracking_out))
                Tracking Number: {{$payment->package->boxes[0]->tracking_out}} <br>
                @endif
                Transaction ID: {{$payment->transaction_id}} <br>
            </td>
            <td>{{ date('d-m-Y',strtotime($payment->charged_at)) }}</td>
            <td>Paid</td>
            <td>${{ format_number($payment->charged_amount) }}</td>
        </tr>
    </table>

    <br>

    <p style="font-size: 10px">
        I possess an electronic signature confirming both the initiation and authorization of the payment, which was
        made by me.
    </p>
</body>

</html>