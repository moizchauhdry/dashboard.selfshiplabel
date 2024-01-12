<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert([            
            [
                "name" =>'New Jersy US', 
                'zip' => '07303',
                'country_code_2' => 'US'
            ],
            [
                "name" =>'Tax Free Delaware (USA) Warehouse', 
                'zip' => '19706',
                'country_code_2' => 'US'
            ],
            [
                "name" =>'Islamabad Pakistan', 
                'zip' => '44000',
                'country_code_2' => 'PK'
            ],
            [
                "name" =>'London (United Kingdom) Warehouse', 
                'zip' => '25126',
                'country_code_2' => 'UK'
            ],            
            [
                "name" =>'Istanbul Turkey', 
                'zip' => '34000',
                'country_code_2' => 'TR'
            ],
        ]);
    }
}