<?php

namespace Database\Seeders;

use App\Models\ShippingService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shipping_services = [
            // FEDEX 
            [
                "service_name" => 'FEDEX_GROUND',
                "service_code" => 'FEDEX_GROUND',
            ],
            [
                "service_name" => 'FEDEX_INTERNATIONAL_CONNECT_PLUS',
                "service_code" => 'FEDEX_INTERNATIONAL_CONNECT_PLUS',
            ],
            [
                "service_name" => 'FEDEX_INTERNATIONAL_PRIORITY_EXPRESS',
                "service_code" => 'FEDEX_INTERNATIONAL_PRIORITY_EXPRESS',
            ],
            [
                "service_name" => 'GROUND_HOME_DELIVERY',
                "service_code" => 'GROUND_HOME_DELIVERY',
            ],
            [
                "service_name" => 'INTERNATIONAL_ECONOMY',
                "service_code" => 'INTERNATIONAL_ECONOMY',
            ],
            [
                "service_name" => 'INTERNATIONAL_PRIORITY_FREIGHT',
                "service_code" => 'INTERNATIONAL_PRIORITY_FREIGHT',
            ],
            [
                "service_name" => 'STANDARD_OVERNIGHT',
                "service_code" => 'STANDARD_OVERNIGHT',
            ],
            [
                "service_name" => 'FIRST_OVERNIGHT',
                "service_code" => 'FIRST_OVERNIGHT',
            ],
            [
                "service_name" => 'FEDEX_INTERNATIONAL_PRIORITY',
                "service_code" => 'FEDEX_INTERNATIONAL_PRIORITY',
            ],
            [
                "service_name" => 'PRIORITY_OVERNIGHT',
                "service_code" => 'PRIORITY_OVERNIGHT',
            ],
            [
                "service_name" => 'FEDEX_2_DAY_AM',
                "service_code" => 'FEDEX_2_DAY_AM',
            ],
            [
                "service_name" => 'FEDEX_2_DAY',
                "service_code" => 'FEDEX_2_DAY',
            ],
            [
                "service_name" => 'FEDEX_EXPRESS_SAVER',
                "service_code" => 'FEDEX_EXPRESS_SAVER',
            ],
            [
                "service_name" => 'INTERNATIONAL_FIRST',
                "service_code" => 'INTERNATIONAL_FIRST',
            ],

            // DHL
            [
                "service_name" => 'EXPRESS_WORLDWIDE',
                "service_code" => 'EXPRESS_WORLDWIDE',
            ],

            // UPS
            [
                "service_name" => 'UPS Next Day Air',
                "service_code" => '01',
            ],
            [
                "service_name" => 'UPS 2nd Day Air',
                "service_code" => '02',
            ],
            [
                "service_name" => 'UPS Ground',
                "service_code" => '03',
            ],
            [
                "service_name" => 'UPS 3 Day Select',
                "service_code" => '12',
            ],
            [
                "service_name" => 'UPS Next Day Air Saver',
                "service_code" => '13',
            ],
            [
                "service_name" => 'UPS UPS Next Day Air Early',
                "service_code" => '14',
            ],
            [
                "service_name" => 'UPS 2nd Day Air A.M. Valid international values',
                "service_code" => '59',
            ],
            [
                "service_name" => 'UPS Worldwide Express',
                "service_code" => '07',
            ],
            [
                "service_name" => 'UPS Worldwide Expedited',
                "service_code" => '08',
            ],
            [
                "service_name" => 'UPS Standard',
                "service_code" => '11',
            ],
            [
                "service_name" => 'UPS Worldwide Express Plus',
                "service_code" => '54',
            ],
            [
                "service_name" => 'UPS Worldwide Saver',
                "service_code" => '65',
            ],
            [
                "service_name" => 'UPS UPS Worldwide Express Freight',
                "service_code" => '96',
            ],
            [
                "service_name" => 'UPS UPS Worldwide Express Freight Midday Required for Rating and ignored for Shopping',
                "service_code" => '71',
            ],

            // USPS
            [
                "service_name" => 'USPS International First Class',
                "service_code" => 'usps_international_first_class',
            ],
            [
                "service_name" => 'USPS International Priority',
                "service_code" => 'usps_international_priority',
            ],
            [
                "service_name" => 'USPS International Express',
                "service_code" => 'usps_international_express',
            ],
            [
                "service_name" => 'USPS Priority (1-3 Days)',
                "service_code" => 'usps_priority',
            ],
            [
                "service_name" => 'USPS Priority Mail Express',
                "service_code" => 'usps_express',
            ],
            [
                "service_name" => 'USPS Parcel Select Ground',
                "service_code" => 'usps_parcel_post',
            ],
            [
                "service_name" => 'USPS First Class',
                "service_code" => 'usps_first_class',
            ],
            [
                "service_name" => 'USPS Ground Advantage',
                "service_code" => 'usps_ground_advantage',
            ],
        ];

        foreach ($shipping_services as $key => $service) {
            ShippingService::updateOrCreate(['service_code' => $service['service_code']], [
                'service_name' => $service['service_name'],
                'service_code' => $service['service_code']
            ]);
        }
    }
}
