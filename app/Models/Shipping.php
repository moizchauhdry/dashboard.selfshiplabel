<?php

namespace App\Models;


class Shipping
{

    public $token = 'm99PyQIqoq5GmAKjs1wOTAbhQ0ozkc0s';
    public $cookie = 'PHPSESSID=3rf796ooctiic30gq0e54bpg45';
    public $customer_id = '12339140';
    public $integration_id = '59690';

    public static function getShippingServices()
    {
        return [
            [
                "service_id" => 0,
                "carrierCode" => "dhl",
                "carrierLabel" => "dhl",
                "serviceLabel" => "DHL Intl Express",
                "serviceCode" => "dhl_express_worldwide",
                'packageTypeCode' => 'dhl_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/dhl-logo.png"
            ],
            [
                "service_id" => 1,
                "carrierCode" => "fedex",
                "carrierLabel" => "fedex",
                "serviceLabel" => "FedEx International Economy®",
                "serviceCode" => "fedex_international_economy",
                'packageTypeCode' => 'fedex_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/fedex-logo.png"
            ],
            [
                "service_id" => 2,
                "carrierCode" => "fedex",
                "carrierLabel" => "fedex",
                "serviceLabel" => "FedEx International Ground®",
                "serviceCode" => "fedex_ground_canada",
                'packageTypeCode' => 'fedex_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/fedex-logo.png"
            ],
            [
                "service_id" => 3,
                "carrierCode" => "fedex",
                "carrierLabel" => "fedex",
                "serviceLabel" => "FedEx Standard Overnight®",
                "serviceCode" => "fedex_standard_overnight",
                'packageTypeCode' => 'fedex_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/fedex-logo.png"
            ],
            [
                "service_id" => 4,
                "carrierCode" => "fedex",
                "carrierLabel" => "fedex",
                "serviceLabel" => "FedEx 2Day®",
                "serviceCode" => "fedex_two_day",
                'packageTypeCode' => 'fedex_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/fedex-logo.png"
            ],
            [
                "service_id" => 5,
                "carrierCode" => "fedex",
                "carrierLabel" => "fedex",
                "serviceLabel" => "FedEx Express Saver®",
                "serviceCode" => "fedex_express_saver",
                'packageTypeCode' => 'fedex_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/fedex-logo.png"
            ],
            [
                "service_id" => 6,
                "carrierCode" => "fedex",
                "carrierLabel" => "fedex",
                "serviceLabel" => "FedEx Ground®",
                "serviceCode" => "fedex_ground",
                'packageTypeCode' => 'fedex_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/fedex-logo.png"
            ],
            [
                "service_id" => 7,
                "carrierCode" => "fedex",
                "carrierLabel" => "fedex",
                "serviceLabel" => "FedEx Home Delivery®",
                "serviceCode" => "fedex_ground_home_delivery",
                'packageTypeCode' => 'fedex_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/fedex-logo.png"
            ],
            [
                "service_id" => 8,
                "carrierCode" => null,
                "carrierLabel" => null,
                "serviceLabel" => "FedEx International Priority®",
                "serviceCode" => "fedex_international_priority",
                'packageTypeCode' => 'fedex_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/fedex-logo.png"
            ],
            [
                "service_id" => 9,
                "carrierCode" => "ups",
                "carrierLabel" => "ups",
                "serviceLabel" => "UPS® Standard",
                "serviceCode" => "ups_standard",
                'packageTypeCode' => 'ups_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/ups-logo.png"
            ],
            [
                "service_id" => 10,
                "carrierCode" => "ups",
                "carrierLabel" => "ups",
                "serviceLabel" => "UPS Worldwide Express®",
                "serviceCode" => "ups_worldwide_express",
                'packageTypeCode' => 'ups_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/ups-logo.png"
            ],
            [
                "service_id" => 11,
                "carrierCode" => "ups",
                "carrierLabel" => "ups",
                "serviceLabel" => "UPS Worldwide Express Plus®",
                "serviceCode" => "ups_express_plus",
                'packageTypeCode' => 'ups_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/ups-logo.png"
            ],
            [
                "service_id" => 12,
                "carrierCode" => "ups",
                "carrierLabel" => "ups",
                "serviceLabel" => "UPS Worldwide Saver®",
                "serviceCode" => "ups_worldwide_saver",
                'packageTypeCode' => 'ups_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/ups-logo.png"
            ],
            [
                "service_id" => 13,
                "carrierCode" => "ups",
                "carrierLabel" => "ups",
                "serviceLabel" => "UPS Next Day Air®",
                "serviceCode" => "ups_next_day_air",
                'packageTypeCode' => 'ups_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/ups-logo.png"
            ],
            [
                "service_id" => 14,
                "carrierCode" => "ups",
                "carrierLabel" => "ups",
                "serviceLabel" => "UPS 2nd Day Air®",
                "serviceCode" => "ups_second_day_air",
                'packageTypeCode' => 'ups_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/ups-logo.png"
            ],
            [
                "service_id" => 15,
                "carrierCode" => "ups",
                "carrierLabel" => "ups",
                "serviceLabel" => "UPS® Ground",
                "serviceCode" => "ups_ground",
                'packageTypeCode' => 'ups_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/ups-logo.png"
            ],
            [
                "service_id" => 16,
                "carrierCode" => "ups",
                "carrierLabel" => "ups",
                "serviceLabel" => "UPS Next Day Air Saver®",
                "serviceCode" => "ups_next_day_air_saver",
                'packageTypeCode' => 'ups_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/ups-logo.png"
            ],
            [
                "service_id" => 17,
                "carrierCode" => "ups",
                "carrierLabel" => "ups",
                "serviceLabel" => "UPS 2nd Day Air A.M.®",
                "serviceCode" => "ups_second_day_air_am",
                'packageTypeCode' => 'ups_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/ups-logo.png"
            ],
            [
                "service_id" => 18,
                "carrierCode" => "ups",
                "carrierLabel" => "ups",
                "serviceLabel" => "UPS 3 Day Select®",
                "serviceCode" => "ups_three_day_select",
                'packageTypeCode' => 'ups_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/ups-logo.png"
            ],
            [
                "service_id" => 19,
                "carrierCode" => "ups",
                "carrierLabel" => "ups",
                "serviceLabel" => "UPS Worldwide Expedited®",
                "serviceCode" => "ups_worldwide_expedited",
                'packageTypeCode' => 'ups_custom_package',
                "currency" => "USD",
                "totalAmount" => 0,
                "baseAmount" => 0,
                "isReady" => false,
                "logo" => "/partner-imgs/ups-logo.png"
            ],


        ];
    }
}


