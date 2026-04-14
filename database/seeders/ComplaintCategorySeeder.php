<?php

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use Illuminate\Database\Seeder;

class ComplaintCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electrical Issues',
                'description' => 'Problems with lights, fans, power outlets, or electrical appliances',
            ],
            [
                'name' => 'Plumbing & Sanitation',
                'description' => 'Water leakage, toilet issues, drainage problems, or water supply',
            ],
            [
                'name' => 'Furniture & Fixtures',
                'description' => 'Broken or damaged furniture, beds, tables, chairs, wardrobes',
            ],
            [
                'name' => 'Cleanliness',
                'description' => 'Hygiene issues, pest control, garbage disposal, common area cleanliness',
            ],
            [
                'name' => 'Safety & Security',
                'description' => 'Door locks, window safety, fire safety equipment, CCTV issues',
            ],
            [
                'name' => 'Internet & Utilities',
                'description' => 'WiFi connectivity, water heater, air conditioning issues',
            ],
            [
                'name' => 'General Maintenance',
                'description' => 'Wall damage, ceiling issues, floor problems, painting needs',
            ],
        ];

        foreach ($categories as $category) {
            ComplaintCategory::create($category);
        }
    }
}
