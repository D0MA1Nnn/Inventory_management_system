<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'name' => 'Processor',
            'image' => 'categories/processors.jpg',
        ]);

        Category::create([
            'name' => 'Motherboard',
            'image' => 'categories/motherboards.jpg',
        ]);


        Category::create([
            'name' => 'Graphics Card',
            'image' => 'categories/graphics_cards.jpg',
        ]);
    }
}
