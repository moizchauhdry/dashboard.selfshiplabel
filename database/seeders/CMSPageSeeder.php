<?php

namespace Database\Seeders;

use App\Models\CMSPage;
use Carbon\Carbon;
use Faker\Provider\Lorem;
use Illuminate\Database\Seeder;

class CMSPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        CMSPage::Insert([
            [
                'title' => 'Services',
                'slug' => 'services',
                'meta_title' => 'Services',
                'meta_description' => Lorem::paragraph(),
                'description' => Lorem::paragraph(),
                'meta_keywords' => 'services',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => 'Shipping Calculator',
                'slug' => 'shipping_calculator',
                'meta_title' => 'Shipping Calculator',
                'meta_description' => Lorem::paragraph(),
                'description' => Lorem::paragraph(),
                'meta_keywords' => 'Shipping Calculator',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => 'How it works',
                'slug' => null,
                'meta_title' => 'How it works',
                'meta_description' => Lorem::paragraph(),
                'description' => Lorem::paragraph(),
                'meta_keywords' => 'How it works',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => 'Store & Forward',
                'slug' => null,
                'meta_title' => 'Store & Forward',
                'meta_description' => Lorem::paragraph(),
                'description' => Lorem::paragraph(),
                'meta_keywords' => 'Store & Forward',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => 'Consolidation',
                'slug' => null,
                'meta_title' => 'Consolidation',
                'meta_description' => Lorem::paragraph(),
                'description' => Lorem::paragraph(),
                'meta_keywords' => 'Consolidation',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => 'Online Shopping Assistant',
                'slug' => null,
                'meta_title' => 'Online Shopping Assistant',
                'meta_description' => Lorem::paragraph(),
                'description' => Lorem::paragraph(),
                'meta_keywords' => 'Online Shopping Assistant',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => 'Package returns',
                'slug' => null,
                'meta_title' => 'Package returns',
                'meta_description' => Lorem::paragraph(),
                'description' => Lorem::paragraph(),
                'meta_keywords' => 'Package returns',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => 'Customer Service',
                'slug' => null,
                'meta_title' => 'Customer Service',
                'meta_description' => Lorem::paragraph(),
                'description' => Lorem::paragraph(),
                'meta_keywords' => 'Customer Service',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => 'Service Updates',
                'slug' => null,
                'meta_title' => 'Service Updates',
                'meta_description' => Lorem::paragraph(),
                'description' => Lorem::paragraph(),
                'meta_keywords' => 'Service Updates',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => 'Unaccapted Items',
                'slug' => null,
                'meta_title' => 'Unaccapted Items',
                'meta_description' => Lorem::paragraph(),
                'description' => Lorem::paragraph(),
                'meta_keywords' => 'Unaccapted Items',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => 'Policy and Terms',
                'slug' => 'policies_and_terms',
                'meta_title' => 'Policy and Terms',
                'meta_description' => Lorem::paragraph(),
                'description' => Lorem::paragraph(),
                'meta_keywords' => 'Policy and Terms',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => 'Terms And Conditions',
                'slug' => 'terms_and_conditions',
                'meta_title' => 'Terms And Conditions',
                'meta_description' => Lorem::paragraph(),
                'description' => Lorem::paragraph(),
                'meta_keywords' => 'Terms And Conditions',
                'created_at' => Carbon::now(),
            ],

        ]);
    }
}
