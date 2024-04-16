<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::truncate();

        $projects = [
            [
                "id" => 1,
                "name" => 'Self Ship Label',
            ],
            [
                "id" => 2,
                "name" => 'Self Ship Label - External',
            ],
        ];

        foreach ($projects as $key => $project) {
            Project::updateOrCreate(['id' => $project['id']], [
                'id' => $project['id'],
                'name' => $project['name'],
            ]);
        }
    }
}
