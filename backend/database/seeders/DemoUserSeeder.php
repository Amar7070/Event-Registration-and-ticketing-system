<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@smartevent.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Organizer
        $organizer = User::updateOrCreate(
            ['email' => 'organizer@smartevent.com'],
            [
                'name' => 'John Organizer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_approved' => true,
            ]
        );
        $organizer->assignRole('organizer');

        // Attendee
        $attendee = User::updateOrCreate(
            ['email' => 'attendee@smartevent.com'],
            [
                'name' => 'Alice Attendee',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $attendee->assignRole('attendee');
    }
}
