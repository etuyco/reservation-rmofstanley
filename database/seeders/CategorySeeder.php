<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Park',
                'slug' => 'park',
                'description' => 'Outdoor recreational facilities including parks, playgrounds, and green spaces.',
                'icon' => 'bi-tree',
                'color' => '#10b981',
                'is_active' => true,
            ],
            [
                'name' => 'Conference Room',
                'slug' => 'conference-room',
                'description' => 'Meeting rooms and conference facilities for business and community events.',
                'icon' => 'bi-people',
                'color' => '#3b82f6',
                'is_active' => true,
            ],
            [
                'name' => 'Equipment',
                'slug' => 'equipment',
                'description' => 'Rental equipment including tools, machinery, and specialized gear.',
                'icon' => 'bi-tools',
                'color' => '#f59e0b',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
