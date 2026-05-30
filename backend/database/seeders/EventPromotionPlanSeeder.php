<?php

namespace Database\Seeders;

use App\Models\EventPromotionPlan;
use Illuminate\Database\Seeder;

class EventPromotionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EventPromotionPlan::create([
            'name' => 'Elite Showcase Banner',
            'description' => 'Featured banner placement in the dedicated Home page slideshow, showcasing your experience with rich image grids.',
            'price' => 500.00,
            'duration_days' => 3,
            'is_active' => true,
        ]);

        EventPromotionPlan::create([
            'name' => 'Premium Spotlights',
            'description' => 'Medium-term spotlight exposure in the featured slideshow. Ideal for standard experience campaigns.',
            'price' => 800.00,
            'duration_days' => 5,
            'is_active' => true,
        ]);

        EventPromotionPlan::create([
            'name' => 'Ecosystem Megacast',
            'description' => 'Ultimate promotional visibility for 7 full days. High conversion banner showcase on the homepage slider.',
            'price' => 1500.00,
            'duration_days' => 7,
            'is_active' => true,
        ]);
    }
}
