<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Configuration::insert([
            [
                "id" => 1,
                "slug" =>'promotional_message',
                "description" => 'Shopping and Shipping for you with Ease'
            ],
        ]);
    }
}
