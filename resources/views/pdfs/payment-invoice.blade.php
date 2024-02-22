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
            /* text-align: center; */
        }



        caption {
            padding: 0.3em;
        }
    </style>
</head>

<body>

    <div style="text-align:center;">
        <img src="{{asset('images/logo.png')}}" alt="" width="85px">
        <h5>PAYMENT INVOICE </h5>
    </div>

    <h5><strong>INVOICE NUMBER: {{ $payment->id }} </strong></h5>
    <table class="border" style="width: 100%">
        <tr>
            <td colspan="4">
                <h3>Ship From:</h3>
                <strong>Name:</strong> {{$ship_from->fullname}}<br>
                @if ($ship_from->company_name)
                <strong>Company:</strong> {{$ship_from->company_name}}<br>
                @endif
                <strong>Phone</strong>: {{ $ship_from->phone ?? ''}}<br>
                <strong>E-mail</strong>: {{ $ship_from->email ?? ''}}<br>
                <strong>Address</strong>:<br>
                {{ $ship_from->address ?? ''}} <br>
                @if ($ship_from->address_2)
                {{ $ship_from->address_2 ?? ''}} <br>
                @endif
                @if ($ship_from->address_3)
                {{ $ship_from->address_3 ?? ''}} <br>
                @endif
                <strong>City</strong>: {{ $ship_from->city ?? ''}} <br>
                <strong>State</strong>: {{ $ship_from->state ?? ''}} <br>
                <strong>ZIP Code</strong>: {{ $ship_from->zip_code ?? ''}} <br>
                <strong>Country</strong>: {{ $ship_from->country->name ?? '' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h3>Ship To:</h3>
                <strong>Name:</strong> {{$ship_to->fullname}}<br>
                @if ($ship_to->company_name)
                <strong>Company:</strong> {{$ship_to->company_name}}<br>
                @endif
                <strong>Phone</strong>: {{ $ship_to->phone ?? ''}}<br>
                <strong>E-mail</strong>: {{ $ship_to->email ?? ''}}<br>
                <strong>Address</strong>:<br>
                {{ $ship_to->address ?? ''}} <br>
                @if ($ship_to->address_2)
                {{ $ship_to->address_2 ?? ''}} <br>
                @endif
                @if ($ship_to->address_3)
                {{ $ship_to->address_3 ?? ''}} <br>
                @endif
                <strong>City</strong>: {{ $ship_to->city ?? ''}} <br>
                <strong>State</strong>: {{ $ship_to->state ?? ''}} <br>
                <strong>ZIP Code</strong>: {{ $ship_to->zip_code ?? ''}} <br>
                <strong>Country</strong>: {{ $ship_to->country->name ?? '' }}
            </td>
            <td colspan="2">
                <h3>Bill To:</h3>

                <strong>Name:</strong> {{$ship_to->fullname}}<br>
                @if ($ship_to->company_name)
                <strong>Company:</strong> {{$ship_to->company_name}}<br>
                @endif
                <strong>Phone</strong>: {{ $ship_to->phone ?? ''}}<br>
                <strong>E-mail</strong>: {{ $ship_to->email ?? ''}}<br>
                <strong>Address</strong>:<br>
                {{ $ship_to->address ?? ''}} <br>
                @if ($ship_to->address_2)
                {{ $ship_to->address_2 ?? ''}} <br>
                @endif
                @if ($ship_to->address_3)
                {{ $ship_to->address_3 ?? ''}} <br>
                @endif
                <strong>City</strong>: {{ $ship_to->city ?? ''}} <br>
                <strong>State</strong>: {{ $ship_to->state ?? ''}} <br>
                <strong>ZIP Code</strong>: {{ $ship_to->zip_code ?? ''}} <br>
                <strong>Country</strong>: {{ $ship_to->country->name ?? '' }}
            </td>
        </tr>
    </table>

    <span style="font-size:10px"><b>CHARGES:</b></span>

    @isset($package)
    <table class="border" style="width: 100%">
        @if ($package->shipping_charges > 0)
        <tr>
            @if ($payment->recharged == 1)
            <td style="width:85%">
                Recharged {{$package->tracking_number_out ? 'Tracking Number: '.$package->tracking_number_out : ""}} <br>
                Reason: {{$payment->charged_reason}}
            </td>
            <td style="width:15%">${{ format_number($payment->charged_amount) }}</td>
            @else
            <td style="width:85%">Shipping Service - {{ $package->service_label }}</td>
            <td style="width:15%">${{ format_number($package->shipping_charges) }}</td>
            @endif
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
                @if (isset($package_box->tracking_out))
                Tracking Number: {{$package_box->tracking_out}} <br>
                @endif
                Transaction ID: {{$payment->transaction_id}} <br>
            </td>
            <td>
                @if ($payment->charged_at)
                {{ date('d-m-Y',strtotime($payment->charged_at)) }}
                @endif
            </td>
            <td>{{$payment->charged_at ? 'Paid': "Unpaid"}}</td>
            <td>${{ format_number($payment->charged_amount) }}</td>
        </tr>
    </table>

    <br>

    <ul style="font-size: 10px">
        <li>
            I possess an electronic signature confirming both the initiation and authorization of the payment, which was
            made by me.
        </li>
        <li>
            I hereby acknowledge and accept the terms and conditions of Self Ship Label for my purchase.
        </li>
        <li>
            I confirm there are no restricted, prohibited, hazardous materials, or dangerous goods in this shipment.
        </li>
        <li>
            I understand that if an item in my shipment is undeclared, restricted, prohibited, hazardous or a dangerous
            good, it will be discarded without reimbursement.
        </li>
        <li>
            I understand I may be billed more if the package exceeds the weight and dimensions I provided.
        </li>
        <li>
            I understand that the recipient may be charged for duties and taxes upon delivery. Final costs are
            determined by customs authorities at time of import into the destination country.
        </li>
    </ul>
</body>

</html>