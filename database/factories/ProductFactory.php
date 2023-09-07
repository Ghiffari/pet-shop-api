<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_uuid' => $this->generateRandomCategory(),
            'uuid' => Str::uuid(),
            'title' => fake()->words(rand(3, 5), true),
            'price' => fake()->randomFloat(2, 10, 500),
            'description' => fake()->text(rand(100, 500)),
            'metadata' => []
        ];
    }

    private function generateRandomCategory(): string
    {
        return Category::inRandomOrder()->first()->uuid;
    }
}
