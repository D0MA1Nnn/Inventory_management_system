<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Electronics']);
        Category::create(['name' => 'Food']);
        Category::create(['name' => 'Clothing']);
        Category::create(['name' => 'Office Supplies']);
    }
}