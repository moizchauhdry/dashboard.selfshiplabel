<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Store::query()->truncate();

        Store::firstOrCreate([
            'warehouse_id' => 1,
            'pickup_charges' => 25,
            'name' => "Los Cerritos Center",
            'country_id' => 226,
            'city' => 'Cerritos',
            'zip' => 90703,
            'state' => 'CA',
            'address' => '239 Los Cerritos Center, Cerritos, CA 90703'
        ]);

        Store::firstOrCreate([
            'warehouse_id' => 1,
            'pickup_charges' => 25,
            'name' => "Citadel Outlets",
            'country_id' => 226,
            'city' => 'Commerce',
            'zip' => 90040,
            'state' => 'CA',
            'address' => '100 Citadel Dr, Commerce, CA 90040'
        ]);

        Store::firstOrCreate([
            'warehouse_id' => 1,
            'pickup_charges' => 25,
            'name' => "Best Buy",
            'country_id' => 226,
            'city' => 'Cerritos',
            'zip' => 90703,
            'state' => 'CA',
            'address' => '12989 Park Plaza Dr, Cerritos, CA 90703'
        ]);

        Store::firstOrCreate([
            'warehouse_id' => 2,
            'pickup_charges' => 25,
            'name' => "Christiana Mall",
            'country_id' => 226,
            'city' => 'Newark',
            'zip' => 19702,
            'state' => 'DE',
            'address' => '132 Christiana Mall, Newark, DE 19702'
        ]);
    }
}
