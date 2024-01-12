<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('site_settings')->insert([            
            [
                "name" =>'markup', 
                'value' => '15'
            ],
            [
                "name" =>'mailout_fee',
                'value' => '5'
            ],
            [
                "name" =>'storage_fee',
                'value' => '3'
            ],

        ]);
    }
}
