<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

        'labels' => [
            'driver' => 'local',
            'root' => storage_path('app/public/labels'),
            'url' => env('APP_URL') . '/storage/labels',
            'visibility' => 'public',
        ],

        'commercial-invoices' => [
            'driver' => 'local',
            'root' => storage_path('app/public/commercial-invoices'),
            'url' => env('APP_URL') . '/storage/commercial-invoices',
            'visibility' => 'public',
        ],

        'payment-invoices' => [
            'driver' => 'local',
            'root' => storage_path('app/public/payment-invoices'),
            'url' => env('APP_URL') . '/storage/payment-invoices',
            'visibility' => 'public',
        ],

        'ups-labels' => [
            'driver' => 'local',
            'root' => storage_path('app/public/ups-labels'),
            'url' => env('APP_URL') . '/storage/ups-labels',
            'visibility' => 'public',
        ],

        'fedex-labels' => [
            'driver' => 'local',
            'root' => storage_path('app/public/fedex-labels'),
            'url' => env('APP_URL') . '/storage/fedex-labels',
            'visibility' => 'public',
        ],

        'dhl-labels' => [
            'driver' => 'local',
            'root' => storage_path('app/public/dhl-labels'),
            'url' => env('APP_URL') . '/storage/dhl-labels',
            'visibility' => 'public',
        ],

        'usps-labels' => [
            'driver' => 'local',
            'root' => storage_path('app/public/usps-labels'),
            'url' => env('APP_URL') . '/storage/usps-labels',
            'visibility' => 'public',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
