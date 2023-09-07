<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Food',
            'Veterinary Diets',
            'Treats',
            'Medicines',
            'Accessories',
            'Toys',
            'Other'
        ];

        foreach($categories as $category) {
            Category::create([
                'uuid' => Str::uuid(),
                'title' => $category,
                'slug' => Str::slug($category)
            ]);
        }

    }
}
