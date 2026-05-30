<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventCategory;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'icon' => 'cpu', 'color' => '#4F46E5'],
            ['name' => 'Music', 'icon' => 'music', 'color' => '#E11D48'],
            ['name' => 'Business', 'icon' => 'briefcase', 'color' => '#0891B2'],
            ['name' => 'Design', 'icon' => 'palette', 'color' => '#7C3AED'],
            ['name' => 'Sports', 'icon' => 'trophy', 'color' => '#059669'],
        ];

        foreach ($categories as $category) {
            EventCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'icon' => $category['icon'],
                    'color' => $category['color'],
                    'is_active' => true,
                ]
            );
        }
    }
}
