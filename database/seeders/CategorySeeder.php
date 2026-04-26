<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Processor',
            'Motherboard',
            'RAM',
            'Graphics Card',
            'Power Supply',
            'SSD',
            'HDD',
            'CPU Cooler',
            'Liquid Cooling',
            'PC Case',
            'Keyboard',
            'Mouse',
            'Monitor',
            'UPS',
            'WiFi Adapter'
        ];

        foreach ($categories as $cat) {
            Category::create(['name' => $cat]);
        }
    }
}