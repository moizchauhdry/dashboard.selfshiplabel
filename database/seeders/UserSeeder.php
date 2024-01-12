<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@shippingxps.com',
                'password' => Hash::make('password'),
                'type' => 'admin',
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@shippingxps.com',
                'password' => Hash::make('password'),
                'type' => 'customer',
            ],
        ]);
    }
}
