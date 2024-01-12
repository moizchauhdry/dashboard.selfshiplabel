<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([            
            [
                'title' =>'Consolidation',
                'description' => 'Merge multiple orders into one package',
                'price' => 5
            ],
            [
                'title' => 'Request Item Images',
                'description' => 'Open any parcel and ask for images.',
                'price' => 2
            ],
            [
                'title' => 'Get Item Attributes',
                'description' => 'Open any parcel and provide serial number or item IMEI number, or any other specifications.',
                'price' => 2
            ]
        ]);
    }
}
