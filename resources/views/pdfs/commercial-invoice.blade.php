<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Commercial Invoice - {{$package->id}} </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@400;500;600;700&display=swap"
        rel="stylesheet"> --}}
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
    {{-- <table style="border:none;">
        <tr>
            <td style="width:50%" style="border:none;font-size:7px;text-align:left;">
                <p>Commercial Invoice</p>
            </td>
            <td style="width:50%" style="border:none;font-size:7px;text-align:right;">
                <p>{{ date('Y-m-d') }}</p>
                <p>{{ $warehouse->address }}</p>
                <p>{{ $warehouse->city }},{{ $warehouse->state }},{{ $warehouse->zip.',' ?? '' }}</p>
                <p>{{ $warehouse->country->nicename }}</p>
            </td>
        </tr>
    </table> --}}
    <h5 style="text-align:center;"> COMMERCIAL INVOICE </h5>
    <table style="margin-bottom: 30px;">
        <tr>
            <td style="padding: 5px" width="50%">
                <h4>{{ strtoupper('Shipped From') }}:</h4><br>
                <strong>Contact Name</strong> : {{ $warehouse->contact_person}}<br>
                <strong>EORI:</strong><br>
                <strong>Telephone No.</strong> : {{ $warehouse->phone}}<br>
                <strong>E-mail</strong> : {{ $warehouse->email}}<br>
                <strong>Company / Address</strong> :<br>
                {{ $warehouse->address}},{{ $warehouse->city }},<br>{{ $warehouse->state }}, {{ $warehouse->zip. ', ' ??
                ''
                }}{{ $warehouse->country->nicename }}
                <br><br>
                <strong>Country</strong> : {{ $warehouse->country->nicename }}<br>
                <strong>Incoterms</strong> : DDU/DAP <br>
                <strong>Reason For Export</strong> : {{ $package->package_type}}<br>

            </td>
            <td style="padding: 5px" width="50%">
                <strong>Tracking Number</strong>:<br> {{ $package->tracking_number_out}}<br><br>
                <strong>Date</strong> : {{ date('Y-m-d') }}<br>
                <strong>Package ID</strong>: {{ $package->package_no}} <br>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px" width="50%">
                <h4>{{ strtoupper('Shipped To') }}:</h4><br>
                <strong>Contact Name</strong> :
                {{ isset($address->fullname) ? strtoupper($address->fullname) : '' }}<br>
                <strong>Telephone No.</strong> : {{ $address->phone ?? ''}}<br>
                <strong>E-mail</strong> : {{ $user->email ?? ''}}<br>
                <strong>Company / Address</strong> :<br>
                {{ $address->address ?? ''}} <br>
                {{ $address->address_2 ?? ''}} <br>
                {{ $address->address_3 ?? ''}} <br>
                @if (isset($address->tax_no))
                <strong>Tax ID</strong> : {{ isset($address->tax_no) ? $address->tax_no : ''}} <br>
                @endif
                <strong>City</strong> : {{ $address->city ?? ''}} <br>
                <strong>State/Province</strong> : {{ $address->state ?? ''}} <br>
                <strong>Zip code</strong> : {{ $address->zip_code ?? ''}} <br> <br>
                <strong>Country</strong> : {{ $address->country->name ?? '' }}
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
                <strong>Signature</strong>:<br> <b>{{ $warehouse->contact_person }}</b>
            </td>
            <td colspan="7" style="height:80px;border-left:none;text-align: right">
                {{-- <img style="height: 75px; width: auto;margin-right:10px"
                    src="{{ asset('storage/'.$warehouse->signature) }}" alt=""> --}}
            </td>
        </tr>
    </table>


    <script>
        window.print();
    </script>
</body>

</html>