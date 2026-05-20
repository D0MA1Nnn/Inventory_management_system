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
                'address' => 'Manila, Philippines',
                'image' => 'suppliers/techsource.jpg'
            ],
            [
                'name' => 'PC Express Supply',
                'email' => 'pcexpress@gmail.com',
                'contact_number' => '09181234567',
                'address' => 'Quezon City, Philippines',
                'image' => 'suppliers/pcexpress.jpg'
            ],
            [
                'name' => 'Digital World Trading',
                'email' => 'digitalworld@gmail.com',
                'contact_number' => '09191234567',
                'address' => 'Cebu, Philippines',
                'image' => 'suppliers/digitalworld.jpg'
            ],
            [
                'name' => 'Hardware Hub',
                'email' => 'hardwarehub@gmail.com',
                'contact_number' => '09201234567',
                'address' => 'Davao, Philippines',
                'image' => 'suppliers/hardwarehub.jpg'
            ],
            [
                'name' => 'NextGen Components',
                'email' => 'nextgen@gmail.com',
                'contact_number' => '09211234567',
                'address' => 'Laguna, Philippines',
                'image' => 'suppliers/nextgen.jpg'
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}