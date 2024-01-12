<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('warehouses')->insert([            
            [
                'name' =>'California USA',
                'country_id' => 226,
                'zip' => '90011',
                'state' => 'CA',
                'city' => 'ANAHEIM',
                'phone' => '657-201-7881',
                'address' => '3578 W SAVANNA ST',
                'contact_person' => 'Admin',
                'email' => 'info@shippingxps.com',
                'sale_tax' => '7.75',
            ],
            [
                'name' => 'Tax Free Delaware USA',
                'country_id' => 226,
                'zip' => '19706',
                'state' => 'DE',
                'city' => 'NEWARK',
                'phone' => '657-201-7881',
                'address' => '1217 OLD COOCH BRIDGE RD',
                'contact_person' => 'Admin',
                'email' => 'info@shippingxps.com',
                'sale_tax' => '0.00',
            ]
        ]);
    }
}
