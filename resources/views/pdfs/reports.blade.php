<style>
    table.border,.border  th,.border  td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    th,td{
        padding:5px
    }
</style>
<table style="width: 100%;padding-bottom: 10px">
    <tr>
        <td colspan="4" style="text-align: center;border-bottom: #f04c23 solid 4px">
            <img style="width: auto;height:80px" src="{{ asset('public/logo.png') }}" alt="">

        </td>
    </tr>
</table>
<br>
<br>
<br>
<table class="border" style="width: 100%">
    <tr>
        <th>Invoice No.</th>
        <th>Transaction ID</th>
        <th>Payment Type</th>
        <th>Customer Name</th>
        <th>Suite No</th>
        <th>Tracking No</th>
        <th>Service Name</th>
        <th>Destination</th>
        <th>Shipping Charges (USD)</th>
        @if(Auth::user()->type == "admin")<th>Markup Fee (USD)</th>@endif
        <th>Service Charges (USD)</th>
        <th>Charged Amount (USD)</th>
        <th>Charged Date</th>
    </tr>
    @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->invoice_id  ?? 'N/A'}}</td>
            <td>{{ $payment->transaction_id  ?? 'N/A'}}</td>
            <td>{{ $payment->payment_type  ?? 'N/A'}}</td>
            <td>{{ $payment->customer->name   ?? 'N/A'}}</td>
            <td>{{ $payment->customer->suite_no   ?? 'N/A'}}</td>
            <td>{{ $payment->package->tracking_number_out  ?? 'N/A'}}</td>
            <td>{{ $payment->package->service_label  ?? 'N/A'}}</td>
            <td>{{ $payment->package->shipping_address  ?? 'N/A'}}</td>
            @if(Auth::user()->type != "admin")<td>{{ $payment->package->shipping_total  ?? 'N/A'}}</td>@endif
            @if(Auth::user()->type == "admin")<td>{{ $payment->package != NULL ?($payment->package->shipping_total - $payment->package->markup_fee) : 'N/A'}}</td>@endif
            @if(Auth::user()->type == "admin")<td>{{ $payment->package->markup_fee  ?? 'N/A'}}</td>@endif
            <td>{{ $payment->package->service_charges  ?? 'N/A'}}</td>
            <td>{{ $payment->charged_amount  ?? 'N/A'}}</td>
            <td>{{ date('d-m-y H:i:s',strtotime($payment->charged_at)) ?? 'N/A'}}</td>
        </tr>
    @endforeach
</table>

<br>
<br>
