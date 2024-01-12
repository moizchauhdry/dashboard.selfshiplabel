<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use BatteriesTable;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            CitiesSeeder::class,
            SettingsSeeder::class,
            CountrySeeder::class,
            WarehouseSeeder::class,
            ServiceSeeder::class,
            BatteriesSeeder::class,
            StoreSeeder::class,
            CMSPageSeeder::class,
            ConfigurationSeeder::class,
            ServicePageSeeder::class,
        ]);
    }
}
