<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'mandrill' => [
        'secret' => 'Z4iK1qN7xFG7FUCTAAanqw',
    ],

    'authorizeAnet' => [
        'merchant_login_id' => env('MERCHANT_LOGIN_ID'),
        'merchant_transaction_key' => env('MERCHANT_TRANSACTION_KEY'),
    ],

    'recaptcha' => [
        'secretkey' => env('CAPTCHA_SECRET_KEY'),
    ],

    'square' => [
        'application_id' => env('SQUARE_APPLICATION_ID'),
        'location_id' => env('SQUARE_LOCATION_ID'),
        'access_token' => env('SQUARE_ACCESS_TOKEN'),
        'api_url' => env('SQUARE_API_URL'),
        'web_url' => env('SQUARE_WEB_URL'),
        'redirect_url' => env('SQUARE_REDIRECT_URL'),
    ],

    'usps' => [
        'client_id' => env('USPS_CLIENT_ID'),
        'client_secret' => env('USPS_CLIENT_SECRET'),
        'api_url' => env('USPS_API_URL'),
        'crid' => env('USPS_CRID'),
        'mid' => env('USPS_MID'),
        'manifest_mid' => env('USPS_MANIFEST_MID'),
        'account_no' => env('USPS_ACCOUNT_NO'),
    ],

];
