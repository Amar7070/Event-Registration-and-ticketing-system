<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DemoUserSeeder::class,
            CategorySeeder::class,
            VenueSeeder::class,
            EventPromotionPlanSeeder::class,
            DemoEventSeeder::class,
            DemoBookingSeeder::class,
            PiyushSeeder::class
        ]);
    }
}
