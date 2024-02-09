<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Commercial Invoice - {{$package->id}} </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        h5,
        table,
        th,
        td {
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
            font-size: 9px;
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
    {{-- <h5 style="text-align:center;"> COMMERCIAL INVOICE </h5> --}}
    <div style="text-align:center;">
        <img src="https://dashboard.selfshiplabel.com/images/logo.png" alt="" width="85px">
        <h5>COMMERCIAL INVOICE </h5>
    </div>

    <table style="margin-bottom: 30px; text-transform: capitalize;">
        <tr>
            <td style="padding: 5px" width="50%">
                <h4>{{ strtoupper('Shipped From') }}:</h4><br>
                <strong>Contact Name</strong> : {{ $ship_from->fullname}}<br>
                @if ($ship_from->company_name)
                <strong>Company Name</strong> : {{$ship_to->company_name}}<br>
                @endif
                <strong>EORI:</strong><br>
                <strong>Phone</strong> : {{ $ship_from->phone}}<br>
                <strong>E-mail</strong> : {{ $ship_from->email}}<br>
                <strong>Address</strong> :<br>
                {{ $ship_from->address}},{{ $ship_from->city }},<br>
                {{ $ship_from->state }}, {{ $ship_from->zip_code}} <br><br>
                <strong>Country</strong> : {{ $ship_from->country->nicename }}<br>
                <strong>Incoterms</strong> : DDU/DAP <br>
                <strong>Reason For Export</strong> : {{ $package->package_type}}<br>

            </td>
            <td style="padding: 5px" width="50%">
                <strong>Tracking Number</strong>:<br> {{ $package->tracking_number_out}}<br><br>
                <strong>Date</strong> : {{ date('Y-m-d') }}<br>
                <strong>Package ID</strong>: {{ $package->id}} <br>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px" width="50%">
                <h4>{{ strtoupper('Shipped To') }}:</h4><br>
                <strong>Contact Name</strong> : {{$ship_to->fullname}}<br>
                @if ($ship_to->company_name)
                <strong>Company Name</strong> : {{$ship_to->company_name}}<br>
                @endif
                <strong>Phone</strong> : {{ $ship_to->phone ?? ''}}<br>
                <strong>E-mail</strong> : {{ $ship_to->email ?? ''}}<br>
                <strong>Address</strong> :<br>
                {{ $ship_to->address ?? ''}} <br>
                {{ $ship_to->address_2 ?? ''}} <br>
                {{ $ship_to->address_3 ?? ''}} <br>
                @if (isset($ship_to->tax_no))
                <strong>Tax ID</strong> : {{ isset($ship_to->tax_no) ? $ship_to->tax_no : ''}} <br>
                @endif
                <strong>City</strong> : {{ $ship_to->city ?? ''}} <br>
                <strong>State/Province</strong> : {{ $ship_to->state ?? ''}} <br>
                <strong>ZIP code</strong> : {{ $ship_to->zip_code ?? ''}} <br> <br>
                <strong>Country</strong> : {{ $ship_to->country->name ?? '' }}
            </td>

            <td style="padding: 5px" width="50%">
                <strong>SOLD TO</strong> : Same as SHIPPED TO
            </td>
        </tr>
    </table>
    <table style="width:100%;" style="margin-top:5px;">
        <tr class="header-row">
            <th colspan="3"><strong>Description of Goods</strong></th>
            <th><strong>HS Code</strong></th>
            <th><strong>Country of Origin</strong></th>
            <th><strong>Price per Unit (USD)</strong></th>
            <th><strong>No. of Units</strong></th>
            <th><strong>Unit of measure</strong></th>
            <th><strong>Total Value (USD)</strong></th>
        </tr>

        @php
        $total = 0;
        $package_count = 1;
        $items_count = count($package->packageItems);
        @endphp

        @forelse($package->packageItems as $item)
        @php
        $total += $item->unit_price * $item->quantity;
        $country = $item->originCountry;
        @endphp
        <tr>
            <td colspan="3">{{ $item->description}}</td>
            <td style="text-align: center;">{{ $item->hs_code ?? '-'}}</td>
            <td style="text-align: center;">{{ $country->nicename ?? '-'}}</td>
            <td style="text-align: center;">{{ $item->unit_price}}</td>
            <td style="text-align: center;">{{ $item->quantity}}</td>
            <td style="text-align: center;">PCS</td>
            <td style="text-align: center;">{{ $item->unit_price*$item->quantity}}</td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align: center">There are no items added yet.</td>
        </tr>
        @endforelse

        <tr>
            <td colspan="6"><strong>Total Packages</strong>: {{ $package_count }}</td>
            <td colspan="2"><strong>Sub Total</strong></td>
            <td colspan="1">${{ $total}}</td>
        </tr>

        <tr>
            <td colspan="6"><strong>Total Number items</strong>: {{ $items_count}}</td>
            <td colspan="2"></td>
            <td colspan="1"></td>
        </tr>

        <tr>
            <td colspan="6"><strong>Total Weight</strong>: {{ $package_weight }} lb
            </td>
            <td colspan="2"></td>
            <td colspan="1">
            </td>
        </tr>

        <tr>
            <td colspan="6">
                <strong>Special Instructions:</strong>
                {{$package->special_instructions}}
            </td>
            <td colspan="2"></td>
            <td colspan="1"></td>
        </tr>

        <tr>
            <td colspan="6"><strong>Declaration Statement</strong></td>
            <td colspan="2"></td>
            <td colspan="1"></td>
        </tr>
        <tr>
            <td colspan="6"></td>
            <td colspan="2"><strong>Invoice Total</strong></td>
            <td colspan="1">${{ $total}}</td>
        </tr>

        <tr style="height:80px;">
            <td colspan="6">These comodities, technology
                or software, were exported from us in accordance with exports administration regulations.
                Diversion contrary to US law is prohebited. I admit that all information contained in this invoice are
                true
                and correct.
            </td>
            <td colspan="2"> <strong>Currency Code</strong>
            </td>
            <td colspan="1"> USD</td>
        </tr>

        <tr>
            <td colspan="2" style="height:80px; padding: 10px;border-right:none">
                <strong>Signature</strong>:<br> <b>{{ $ship_from->fullname }}</b>
            </td>
            <td colspan="7" style="height:80px;border-left:none;text-align: right">
                {{-- <img style="height: 75px; width: auto;margin-right:10px"
                    src="{{ asset('storage/'.$ship_from->signature) }}" alt=""> --}}
            </td>
        </tr>
    </table>


    <script>
        window.print();
    </script>
</body>

</html>