<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Custom Form - {{$record->id}} </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@400;500;600;700&display=swap"
        rel="stylesheet">
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
    <table style="border:none;">
        <tr>
            <td style="width:50%" style="border:none;font-size:7px;text-align:left;">
                <p>Commercial Invoice</p>
            </td>
            <td style="width:50%" style="border:none;font-size:7px;text-align:right;">
                <p>{{ date('Y-m-d') }}</p>
                <p>{{ $record->ship_from_address }}</p>
                <p>{{ $record->ship_from_city }},{{ $record->ship_from_state }},{{ $record->ship_from_zipcode }}</p>
                <p>{{ $record->ship_from_country }}</p>
            </td>
        </tr>
    </table>
    <h5 style="text-align:center;"> COMMERCIAL INVOICE </h5>
    <table style="margin-bottom: 30px;">
        <tr>
            <td style="padding: 5px" width="50%">
                <h4>{{ strtoupper('Shipped From') }}:</h4><br>
                <strong>Contact Name</strong> : {{ $record->ship_from_person}}<br>
                <strong>EORI:</strong><br>
                <strong>Telephone No.</strong> : {{ $record->ship_from_contact}}<br>
                <strong>E-mail</strong> : {{ $record->ship_from_email}}<br>
                <strong>Company / Address</strong> :<br>
                {{ $record->ship_from_address}},{{ $record->ship_from_city }}, <br>
                {{ $record->ship_from_state }},{{ $record->ship_from_zipcode}}
                <br><br>
                <strong>Country</strong> : {{ $record->ship_from_country }}<br>
                <strong>Incoterms</strong> : {{$record->ship_from_incoterms}} <br>
                <strong>Reason For Export</strong> : {{ $record->package_type}}<br>

            </td>
            <td style="padding: 5px" width="50%">
                {{-- <strong>Tracking Number</strong>:<br> {{ $record->tracking_number}}<br><br> --}}
                <strong>Date</strong> : {{ $record->package_date}}<br>
                {{-- <strong>Package ID</strong>: {{ $record->package_no}} <br> --}}
            </td>
        </tr>
        <tr>
            <td style="padding: 5px" width="50%">
                <h4>{{ strtoupper('Shipped To') }}:</h4><br>
                <strong>Contact Name</strong> : {{$record->ship_to_person}}<br>
                <strong>Telephone No.</strong> : {{ $record->ship_to_contact ?? ''}}<br>
                <strong>E-mail</strong> : {{ $record->ship_to_email ?? ''}}<br>
                <strong>Company / Address</strong> :<br>
                {{ $record->ship_to_address1 ?? ''}} <br>

                @if ($record->ship_to_address2)
                {{ $record->ship_to_address2 ?? ''}} <br>
                @endif

                @if ($record->ship_to_address3)
                {{ $record->ship_to_address3 ?? ''}} <br>
                @endif
                <br>
                <strong>Tax ID</strong> : {{$record->ship_to_tax_no}} <br>
                <strong>City</strong> : {{ $record->ship_to_city ?? ''}} <br>
                @if ($record->ship_to_state)
                <strong>State/Province</strong> : {{ $record->ship_to_state ?? ''}} <br>
                @endif
                @if ($record->ship_to_zipcode)
                <strong>Zip code</strong> : {{ $record->ship_to_zipcode ?? ''}} <br> <br>
                @endif
                <strong>Country</strong> : {{ $record->ship_to_country ?? '' }}
            </td>

            <td style="padding: 5px" width="50%">
                <strong>SOLD TO</strong> : {{$record->sold_to}}
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

        @forelse($custom_items as $item)
        <tr>
            <td colspan="3">{{ $item->desc}}</td>
            <td style="text-align: center;">{{ $item->code ?? '-'}}</td>
            <td style="text-align: center;">{{ $item->origin ?? '-'}}</td>
            <td style="text-align: center;">{{ $item->price}}</td>
            <td style="text-align: center;">{{ $item->qty}}</td>
            <td style="text-align: center;">PCS</td>
            <td style="text-align: center;">{{ $item->price * $item->qty}}</td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align: center">There are no items added yet.</td>
        </tr>
        @endforelse

        <tr>
            <td colspan="6"><strong>Total Packages</strong>: {{$custom_items->count()}}</td>
            <td colspan="2"><strong>Sub Total</strong></td>
            <td colspan="1">${{$record->shipping_total}}</td>
        </tr>

        <tr>
            <td colspan="6"><strong>Total Number items</strong>: {{$custom_items->count()}}</td>
            <td colspan="2"></td>
            <td colspan="1"></td>
        </tr>

        <tr>
            <td colspan="6"><strong>Total Weight</strong>: {{$record->package_weight}} LB
            </td>
            <td colspan="2"></td>
            <td colspan="1">
            </td>
        </tr>

        <tr>
            <td colspan="6">
                <strong>Special Instructions:</strong>
                {{$record->special_instructions}}
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
            <td colspan="1">${{$record->shipping_total}}</td>
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
                <div><b>Signature:</b></div> <br>
                <div style="font-size:10px;">
                    <b>{{ $record->ship_from_person }}</b> ________________________________
                </div>
            </td>
            <td colspan="7" style="height:80px;border-left:none;text-align: right">
                {{-- <img style="height: 75px; width: auto;margin-right:10px"
                    src="{{ asset('storage/'.$record->signature) }}" alt=""> --}}
            </td>
        </tr>
    </table>
</body>

</html>