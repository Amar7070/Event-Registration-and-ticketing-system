<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venue;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        Venue::updateOrCreate(
            ['name' => 'Grand Convention Center'],
            [
                'address' => '123 Main St, Sector 5',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'country' => 'India',
                'pincode' => '400001',
                'capacity' => 5000,
                'latitude' => 19.0760,
                'longitude' => 72.8777,
            ]
        );

        Venue::updateOrCreate(
            ['name' => 'Royal Garden Hotel'],
            [
                'address' => '456 Residency Road',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'country' => 'India',
                'pincode' => '560001',
                'capacity' => 1000,
                'latitude' => 12.9716,
                'longitude' => 77.5946,
            ]
        );
    }
}
