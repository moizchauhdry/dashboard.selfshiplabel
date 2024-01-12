<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BatteriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('batteries')->insert([            
            [
                'description' => 'No Batteries',                
            ],
            [
                'description' => 'Simple Batteries (Shipped on on Fedex',                
            ],
            [
                'description' => 'Batteries Packaed with Equipment',                
            ],
            [
                'description' => 'Batteries Contained in Equipment',                
            ],
        ]);
    }
}