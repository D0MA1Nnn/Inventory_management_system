<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{

    public function run()
    {
        Supplier::create([
            'name' => 'TechSource PH',
            'email' => 'techsource@gmail.com',
            'contact_number' => '09171234567',
            'address' => 'Manila, Philippines'
        ]);

        Supplier::create([
            'name' => 'PC Express Supply',
            'email' => 'pcexpress@gmail.com',
            'contact_number' => '09181234567',
            'address' => 'Quezon City, Philippines'
        ]);

        Supplier::create([
            'name' => 'Digital World Trading',
            'email' => 'digitalworld@gmail.com',
            'contact_number' => '09191234567',
            'address' => 'Cebu, Philippines'
        ]);

        Supplier::create([
            'name' => 'Hardware Hub',
            'email' => 'hardwarehub@gmail.com',
            'contact_number' => '09201234567',
            'address' => 'Davao, Philippines'
        ]);

        Supplier::create([
            'name' => 'NextGen Components',
            'email' => 'nextgen@gmail.com',
            'contact_number' => '09211234567',
            'address' => 'Laguna, Philippines'
        ]);
    }
}
