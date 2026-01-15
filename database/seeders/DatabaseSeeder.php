<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
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
        Property::create([
            'name' => 'Community Park',
            'type' => 'Park',
            'description' => 'A beautiful community park with playground equipment, picnic areas, and walking trails.',
            'location' => 'Main Street, Stanley',
            'capacity' => 100,
            'price_per_hour' => 0.00,
            'is_active' => true,
        ]);

        Property::create([
            'name' => 'Conference Room A',
            'type' => 'Conference Room',
            'description' => 'A spacious conference room equipped with projector, whiteboard, and seating for up to 30 people.',
            'location' => 'Community Center, 2nd Floor',
            'capacity' => 30,
            'price_per_hour' => 25.00,
            'is_active' => true,
        ]);

        Property::create([
            'name' => 'Conference Room B',
            'type' => 'Conference Room',
            'description' => 'A smaller conference room perfect for meetings and presentations.',
            'location' => 'Community Center, 2nd Floor',
            'capacity' => 15,
            'price_per_hour' => 15.00,
            'is_active' => true,
        ]);

        Property::create([
            'name' => 'Projector Equipment',
            'type' => 'Equipment',
            'description' => 'High-quality projector with screen and sound system.',
            'location' => 'Equipment Storage',
            'capacity' => null,
            'price_per_hour' => 10.00,
            'is_active' => true,
        ]);

        Property::create([
            'name' => 'Sound System',
            'type' => 'Equipment',
            'description' => 'Professional sound system with microphones and speakers.',
            'location' => 'Equipment Storage',
            'capacity' => null,
            'price_per_hour' => 15.00,
            'is_active' => true,
        ]);
    }
}
