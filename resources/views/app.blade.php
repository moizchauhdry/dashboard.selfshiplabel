<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css?v=1.0') }}">

    @env('production')
    <script
        src="https://www.paypal.com/sdk/js?client-id=Ad_mOnLAjPkl17HazcpuehUPrOIEP9rsM90Ta1BRuUSdvAe14-lcWx1ZWjCcESkSrqjJ_xjnogdy4ft6&enable-funding=venmo&currency=USD"
        data-sdk-integration-source="button-factory"></script>
    @endenv

    @env('staging')
    <script
        src="https://www.paypal.com/sdk/js?client-id=AZKXMPfJscqaryDzTCEnfpzP7CUT6rXYvS6EdQiX2FkCcSodMhqjYBmgBZvJLbRLonXetJ4BQClbYsJM&enable-funding=venmo&currency=USD"
        data-sdk-integration-source="button-factory"></script>
    @endenv

    @routes
    <link rel="stylesheet" href="{{ url(mix('css/app.css')) }}">
    <script src="{{ url(mix('js/app.js')) }}" defer></script>
</head>

<body>
    <div class="body">
        @inertia
    </div>
</body>

</html>