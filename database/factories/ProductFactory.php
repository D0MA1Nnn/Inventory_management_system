<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category; // ✅ ADD THIS

class ProductFactory extends Factory
{
    public function definition()
    {
        // Ensure categories exist first
        if (Category::count() === 0) {
            Category::factory()->count(4)->create();
        }
        
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'quantity' => fake()->numberBetween(1, 100),
            'category_id' => Category::inRandomOrder()->first()?->id,
        ];
    }
}