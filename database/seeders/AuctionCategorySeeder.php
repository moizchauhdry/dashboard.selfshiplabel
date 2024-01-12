<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuctionCategory;

class AuctionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AuctionCategory::insert([
            [
                "id" => 1,
                "name" =>'Electronic',
            ],
            [
                "id" => 2,
                "name" =>'Toys',
            ],
            [
                "id" => 3,
                "name" =>'Garments',
            ],
            [
                "id" => 4,
                "name" =>'Mobile',
            ],
        ]);
    }
}
