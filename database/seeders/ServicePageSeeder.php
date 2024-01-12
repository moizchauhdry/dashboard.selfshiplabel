<?php

namespace Database\Seeders;

use App\Models\ServicePage;
use Illuminate\Database\Seeder;

class ServicePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ServicePage::Insert([
            [
                'name' => 'Account Fee',
                'description' => 'Free',
            ],
            [
                'name' => 'Tax free shopping',
                'description' => 'Yes',
            ],
            [
                'name' => 'Mail out fee',
                'description' => 'Free',
            ],
            [
                'name' => 'Storage fee',
                'description' => 'Free for 75 days',
            ],
            [
                'name' => 'Additional storage',
                'description' => '$0.01 per lbs (Dimensional weight)',
            ],
            [
                'name' => 'Consolidation',
                'description' => '$5+$1.50 per each consolidation package',
            ],
            [
                'name' => 'Trash',
                'description' => 'Free',
            ],
            [
                'name' => 'Incoming Mail and product picture',
                'description' => 'Free, no serial number or model number',
            ],
            [
                'name' => 'Additional photos',
                'description' => 'additional picture such as serial number, model number etc $2 each or $5 for 3',
            ],
            [
                'name' => 'Purchase from Costco and Sam club',
                'description' => '5% of your total bill',
            ],
            [
                'name' => 'Purchase from outlets in USA',
                'description' => '$20 for a trip to each store for California, tory Burch is $25, 5% of the total invoice (Tax included)',
            ],
            [
                'name' => 'Trip to the store includes 1 hour of shopping for you,after an hour another charge is added',
                'description' => '$25 for Tax free store trip',
            ],
            [
                'name' => 'Special request',
                'description' => '$2 per request <br> Ex: <br> bubble wrap- $2 <br> Shrink wrap - $2 <br> Fragile sticker- $2',
            ],
            [
                'name' => 'Online Shopping assistant',
                'description' => '$5 or 5% whichever is higher for online purchases',
            ],
            [
                'name' => 'Overweight handling',
                'description' => 'Surcharge above 50Lbs',
            ],
            [
                'name' => 'Can purchase Gift Cards',
                'description' => '6% of your total bill if electronic available and $20 per visit if physical store visit is required',
            ],
        ]);
    }
}
