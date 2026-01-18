<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed categories first
        $this->call(CategorySeeder::class);

        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@stanley.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Owner User
        User::create([
            'name' => 'John Doe',
            'email' => 'owner@stanley.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // Create Sample Properties
        $parkCategory = Category::where('name', 'Park')->first();
        $conferenceCategory = Category::where('name', 'Conference Room')->first();
        $equipmentCategory = Category::where('name', 'Equipment')->first();

        Property::create([
            'name' => 'Community Park',
            'category_id' => $parkCategory->id,
            'description' => 'A beautiful community park with playground equipment, picnic areas, and walking trails.',
            'location' => 'Main Street, Stanley',
            'capacity' => 100,
            'price_per_hour' => 0.00,
            'is_active' => true,
        ]);

        Property::create([
            'name' => 'Conference Room A',
            'category_id' => $conferenceCategory->id,
            'description' => 'A spacious conference room equipped with projector, whiteboard, and seating for up to 30 people.',
            'location' => 'Community Center, 2nd Floor',
            'capacity' => 30,
            'price_per_hour' => 25.00,
            'is_active' => true,
        ]);

        Property::create([
            'name' => 'Conference Room B',
            'category_id' => $conferenceCategory->id,
            'description' => 'A smaller conference room perfect for meetings and presentations.',
            'location' => 'Community Center, 2nd Floor',
            'capacity' => 15,
            'price_per_hour' => 15.00,
            'is_active' => true,
        ]);

        Property::create([
            'name' => 'Projector Equipment',
            'category_id' => $equipmentCategory->id,
            'description' => 'High-quality projector with screen and sound system.',
            'location' => 'Equipment Storage',
            'capacity' => null,
            'price_per_hour' => 10.00,
            'is_active' => true,
        ]);

        Property::create([
            'name' => 'Sound System',
            'category_id' => $equipmentCategory->id,
            'description' => 'Professional sound system with microphones and speakers.',
            'location' => 'Equipment Storage',
            'capacity' => null,
            'price_per_hour' => 15.00,
            'is_active' => true,
        ]);
    }
}
