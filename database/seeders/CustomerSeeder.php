<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Juan Dela Cruz', 'email' => 'juan.delacruz@gmail.com', 'phone' => '09171230001', 'address' => 'Quezon City, Metro Manila'],
            ['name' => 'Maria Santos', 'email' => 'maria.santos@gmail.com', 'phone' => '09171230002', 'address' => 'Makati City, Metro Manila'],
            ['name' => 'Jose Reyes', 'email' => 'jose.reyes@gmail.com', 'phone' => '09171230003', 'address' => 'Pasig City, Metro Manila'],
            ['name' => 'Ana Garcia', 'email' => 'ana.garcia@gmail.com', 'phone' => '09171230004', 'address' => 'Taguig City, Metro Manila'],
            ['name' => 'Carlo Mendoza', 'email' => 'carlo.mendoza@gmail.com', 'phone' => '09171230005', 'address' => 'Caloocan City, Metro Manila'],
            ['name' => 'Liza Ramos', 'email' => 'liza.ramos@gmail.com', 'phone' => '09171230006', 'address' => 'Manila City, Metro Manila'],
            ['name' => 'Mark Villanueva', 'email' => 'mark.villanueva@gmail.com', 'phone' => '09171230007', 'address' => 'Marikina City, Metro Manila'],
            ['name' => 'Paolo Castillo', 'email' => 'paolo.castillo@gmail.com', 'phone' => '09171230008', 'address' => 'Las Pinas City, Metro Manila'],
            ['name' => 'Nina Flores', 'email' => 'nina.flores@gmail.com', 'phone' => '09171230009', 'address' => 'Paranaque City, Metro Manila'],
            ['name' => 'Rico Navarro', 'email' => 'rico.navarro@gmail.com', 'phone' => '09171230010', 'address' => 'Mandaluyong City, Metro Manila'],
        ];

        foreach ($customers as $customer) {
            User::updateOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'customer',
                    'phone' => $customer['phone'],
                    'address' => $customer['address'],
                    'status' => 'active',
                ]
            );
        }
    }
}
