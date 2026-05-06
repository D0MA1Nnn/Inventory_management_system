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
            'fields_schema' => [
                ['name' => 'socket', 'label' => 'Socket', 'type' => 'text', 'required' => false],
                ['name' => 'cores_threads', 'label' => 'Cores / Threads', 'type' => 'text', 'required' => false],
                ['name' => 'base_boost_clock', 'label' => 'Base / Boost Clock', 'type' => 'text', 'required' => false],
                ['name' => 'benchmark', 'label' => 'Benchmark', 'type' => 'text', 'required' => false],
            ],
        ]);

        Category::create([
            'name' => 'Motherboard',
            'image' => 'categories/motherboards.jpg',
            'fields_schema' => [
                ['name' => 'socket', 'label' => 'Socket', 'type' => 'text', 'required' => false],
                ['name' => 'form_factor', 'label' => 'Form Factor', 'type' => 'text', 'required' => false],
                ['name' => 'chipset', 'label' => 'Chipset', 'type' => 'text', 'required' => false],
                ['name' => 'ram_support', 'label' => 'RAM Support', 'type' => 'text', 'required' => false],
            ],
        ]);


        Category::create([
            'name' => 'Graphics Card',
            'image' => 'categories/graphics_cards.jpg',
            'fields_schema' => [
                ['name' => 'vram', 'label' => 'VRAM', 'type' => 'text', 'required' => false],
                ['name' => 'clock_speed', 'label' => 'Clock Speed', 'type' => 'text', 'required' => false],
                ['name' => 'power_consumption', 'label' => 'Power Consumption', 'type' => 'text', 'required' => false],
            ],
        ]);
    }
}
