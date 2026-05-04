<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'TechSource PH',
                'email' => 'techsource@gmail.com',
                'contact_number' => '09171234567',
                'address' => 'Manila, Philippines'
            ],
            [
                'name' => 'PC Express Supply',
                'email' => 'pcexpress@gmail.com',
                'contact_number' => '09181234567',
                'address' => 'Quezon City, Philippines'
            ],
            [
                'name' => 'Digital World Trading',
                'email' => 'digitalworld@gmail.com',
                'contact_number' => '09191234567',
                'address' => 'Cebu, Philippines'
            ],
            [
                'name' => 'Hardware Hub',
                'email' => 'hardwarehub@gmail.com',
                'contact_number' => '09201234567',
                'address' => 'Davao, Philippines'
            ],
            [
                'name' => 'NextGen Components',
                'email' => 'nextgen@gmail.com',
                'contact_number' => '09211234567',
                'address' => 'Laguna, Philippines'
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}