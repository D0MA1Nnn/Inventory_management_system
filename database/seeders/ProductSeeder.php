<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ======================
        // PROCESSORS
        // ======================

        Product::create([
            'name' => 'Intel Core i5-12400F',
            'brand' => 'Intel',
            'model_number' => 'i5-12400F',
            'price' => 11000,
            'quantity' => 15,
            'category_id' => 1,
            'performance' => 'Base: 2.5GHz • Boost: 4.4GHz • Cache: 18MB • TDP: 65W • No iGPU • Cooler Included',
            'dynamic_fields' => [
                'socket' => 'LGA 1700',
                'cores_threads' => '6 / 12',
                'base_boost_clock' => '2.5 / 4.4 GHz',
                'benchmark' => '12000'
            ]
        ]);

        Product::create([
            'name' => 'Intel Core i7-13700K',
            'brand' => 'Intel',
            'model_number' => 'i7-13700K',
            'price' => 23500,
            'quantity' => 8,
            'category_id' => 1,
            'performance' => 'Base: 3.4GHz • Boost: 5.4GHz • Cache: 30MB • TDP: 125W • UHD 770 iGPU • No Cooler',
            'dynamic_fields' => [
                'socket' => 'LGA 1700',
                'cores_threads' => '16 / 24',
                'base_boost_clock' => '3.4 / 5.4 GHz',
                'benchmark' => '30000+'
            ]
        ]);

        Product::create([
            'name' => 'AMD Ryzen 5 5600X',
            'brand' => 'AMD',
            'model_number' => '5600X',
            'price' => 9500,
            'quantity' => 12,
            'category_id' => 1,
            'performance' => 'Base: 3.7GHz • Boost: 4.6GHz • Cache: 32MB • TDP: 65W • No iGPU • Wraith Cooler',
            'dynamic_fields' => [
                'socket' => 'AM4',
                'cores_threads' => '6 / 12',
                'base_boost_clock' => '3.7 / 4.6 GHz',
                'benchmark' => '11000'
            ]
        ]);

        Product::create([
            'name' => 'AMD Ryzen 7 5800X',
            'brand' => 'AMD',
            'model_number' => '5800X',
            'price' => 15500,
            'quantity' => 10,
            'category_id' => 1,
            'performance' => 'Base: 3.8GHz • Boost: 4.7GHz • Cache: 32MB • TDP: 105W • No iGPU • No Cooler',
            'dynamic_fields' => [
                'socket' => 'AM4',
                'cores_threads' => '8 / 16',
                'base_boost_clock' => '3.8 / 4.7 GHz',
                'benchmark' => '15000'
            ]
        ]);

        Product::create([
            'name' => 'Intel Core i3-12100',
            'brand' => 'Intel',
            'model_number' => 'i3-12100',
            'price' => 6800,
            'quantity' => 20,
            'category_id' => 1,
            'performance' => 'Base: 3.3GHz • Boost: 4.3GHz • Cache: 12MB • TDP: 60W • UHD 730 iGPU • Cooler Included',
            'dynamic_fields' => [
                'socket' => 'LGA 1700',
                'cores_threads' => '4 / 8',
                'base_boost_clock' => '3.3 / 4.3 GHz',
                'benchmark' => '8000'
            ]
        ]);

        Product::create([
            'name' => 'Intel Core i9-13900K',
            'brand' => 'Intel',
            'model_number' => 'i9-13900K',
            'price' => 32000,
            'quantity' => 5,
            'category_id' => 1,
            'performance' => '24 cores • Boost 5.8GHz • 36MB Cache',
            'dynamic_fields' => [
                'socket' => 'LGA1700',
                'cores_threads' => '24 / 32'
            ]
        ]);

        Product::create([
            'name' => 'AMD Ryzen 9 5900X',
            'brand' => 'AMD',
            'model_number' => '5900X',
            'price' => 21000,
            'quantity' => 7,
            'category_id' => 1,
            'performance' => '12 cores • Boost 4.8GHz',
            'dynamic_fields' => [
                'socket' => 'AM4',
                'cores_threads' => '12 / 24'
            ]
        ]);

        Product::create([
            'name' => 'Intel Core i5-13400',
            'brand' => 'Intel',
            'model_number' => 'i5-13400',
            'price' => 12500,
            'quantity' => 10,
            'category_id' => 1,
            'performance' => '10 cores • Boost 4.6GHz',
            'dynamic_fields' => [
                'socket' => 'LGA1700'
            ]
        ]);

        Product::create([
            'name' => 'AMD Ryzen 5 7600',
            'brand' => 'AMD',
            'model_number' => '7600',
            'price' => 14000,
            'quantity' => 9,
            'category_id' => 1,
            'performance' => '6 cores • Boost 5.1GHz',
            'dynamic_fields' => [
                'socket' => 'AM5'
            ]
        ]);

        Product::create([
            'name' => 'Intel Pentium Gold G6400',
            'brand' => 'Intel',
            'model_number' => 'G6400',
            'price' => 3500,
            'quantity' => 20,
            'category_id' => 1,
            'performance' => '2 cores • Budget CPU',
            'dynamic_fields' => [
                'socket' => 'LGA1200'
            ]
        ]);

        // ======================
        // MOTHERBOARDS
        // ======================

        Product::create([
            'name' => 'ASUS PRIME B660M-K',
            'brand' => 'ASUS',
            'model_number' => 'B660M-K',
            'price' => 6500,
            'quantity' => 10,
            'category_id' => 2,
            'performance' => 'Chipset: B660 • RAM Slots: 2 • Max RAM: 64GB • PCIe 4.0 • M.2 Slot • Basic VRM',
            'dynamic_fields' => [
                'socket' => 'LGA 1700',
                'form_factor' => 'mATX',
                'chipset' => 'B660',
                'ram_support' => 'DDR4 3200'
            ]
        ]);

        Product::create([
            'name' => 'MSI B550M PRO-VDH WIFI',
            'brand' => 'MSI',
            'model_number' => 'B550M PRO-VDH',
            'price' => 7500,
            'quantity' => 12,
            'category_id' => 2,
            'performance' => 'Chipset: B550 • RAM Slots: 4 • Max RAM: 128GB • PCIe 4.0 • WiFi • M.2 Slots',
            'dynamic_fields' => [
                'socket' => 'AM4',
                'form_factor' => 'mATX',
                'chipset' => 'B550',
                'ram_support' => 'DDR4 4400'
            ]
        ]);

        Product::create([
            'name' => 'Gigabyte B450M DS3H',
            'brand' => 'Gigabyte',
            'model_number' => 'B450M DS3H',
            'price' => 5200,
            'quantity' => 15,
            'category_id' => 2,
            'performance' => 'Chipset: B450 • RAM Slots: 4 • Max RAM: 64GB • PCIe 3.0 • Budget Board',
            'dynamic_fields' => [
                'socket' => 'AM4',
                'form_factor' => 'mATX',
                'chipset' => 'B450',
                'ram_support' => 'DDR4 3600'
            ]
        ]);

        Product::create([
            'name' => 'ASUS ROG STRIX Z690-E',
            'brand' => 'ASUS',
            'model_number' => 'Z690-E',
            'price' => 18000,
            'quantity' => 5,
            'category_id' => 2,
            'performance' => 'Chipset: Z690 • RAM Slots: 4 • Max RAM: 128GB • PCIe 5.0 • WiFi 6 • High-end VRM',
            'dynamic_fields' => [
                'socket' => 'LGA 1700',
                'form_factor' => 'ATX',
                'chipset' => 'Z690',
                'ram_support' => 'DDR5 6400'
            ]
        ]);

        Product::create([
            'name' => 'MSI A320M-A PRO',
            'brand' => 'MSI',
            'model_number' => 'A320M-A PRO',
            'price' => 3800,
            'quantity' => 20,
            'category_id' => 2,
            'performance' => 'Chipset: A320 • RAM Slots: 2 • Max RAM: 32GB • PCIe 3.0 • Entry-level',
            'dynamic_fields' => [
                'socket' => 'AM4',
                'form_factor' => 'mATX',
                'chipset' => 'A320',
                'ram_support' => 'DDR4 3200'
            ]
        ]);

        Product::create([
            'name' => 'ASRock B550 Steel Legend',
            'brand' => 'ASRock',
            'model_number' => 'B550 Steel Legend',
            'price' => 8500,
            'quantity' => 10,
            'category_id' => 2,
            'performance' => 'B550 • PCIe 4.0',
            'dynamic_fields' => ['socket' => 'AM4']
        ]);

        Product::create([
            'name' => 'Gigabyte Z790 AORUS Elite',
            'brand' => 'Gigabyte',
            'model_number' => 'Z790',
            'price' => 16000,
            'quantity' => 6,
            'category_id' => 2,
            'performance' => 'Z790 • DDR5',
            'dynamic_fields' => ['socket' => 'LGA1700']
        ]);

        Product::create([
            'name' => 'MSI MAG B660 Tomahawk',
            'brand' => 'MSI',
            'model_number' => 'B660 Tomahawk',
            'price' => 9500,
            'quantity' => 8,
            'category_id' => 2,
            'performance' => 'B660 Gaming',
            'dynamic_fields' => ['socket' => 'LGA1700']
        ]);

        Product::create([
            'name' => 'ASUS TUF Gaming B550',
            'brand' => 'ASUS',
            'model_number' => 'B550 TUF',
            'price' => 9000,
            'quantity' => 9,
            'category_id' => 2,
            'performance' => 'Durable board',
            'dynamic_fields' => ['socket' => 'AM4']
        ]);

        Product::create([
            'name' => 'Biostar A520MH',
            'brand' => 'Biostar',
            'model_number' => 'A520MH',
            'price' => 3500,
            'quantity' => 15,
            'category_id' => 2,
            'performance' => 'Budget motherboard',
            'dynamic_fields' => ['socket' => 'AM4']
        ]);

        // ======================
        // RAM PRODUCTS
        // ======================

        Product::create([
            'name' => 'Corsair Vengeance 8GB DDR4',
            'brand' => 'Corsair',
            'model_number' => '8GB DDR4',
            'price' => 1800,
            'quantity' => 25,
            'category_id' => 3,
            'performance' => 'DDR4 3200MHz',
            'dynamic_fields' => ['capacity' => '8GB']
        ]);

        Product::create([
            'name' => 'Corsair Vengeance 16GB DDR4',
            'brand' => 'Corsair',
            'model_number' => '16GB DDR4',
            'price' => 3200,
            'quantity' => 20,
            'category_id' => 3,
            'performance' => 'DDR4 3200MHz',
            'dynamic_fields' => ['capacity' => '16GB']
        ]);

        Product::create([
            'name' => 'G.Skill Ripjaws 16GB DDR4',
            'brand' => 'G.Skill',
            'model_number' => 'Ripjaws 16GB',
            'price' => 3400,
            'quantity' => 15,
            'category_id' => 3,
            'performance' => 'DDR4 3600MHz',
            'dynamic_fields' => ['capacity' => '16GB']
        ]);

        Product::create([
            'name' => 'Kingston Fury 8GB DDR4',
            'brand' => 'Kingston',
            'model_number' => 'Fury 8GB',
            'price' => 1700,
            'quantity' => 30,
            'category_id' => 3,
            'performance' => 'DDR4 3200MHz',
            'dynamic_fields' => ['capacity' => '8GB']
        ]);

        Product::create([
            'name' => 'Kingston Fury 16GB DDR4',
            'brand' => 'Kingston',
            'model_number' => 'Fury 16GB',
            'price' => 3000,
            'quantity' => 20,
            'category_id' => 3,
            'performance' => 'DDR4 3200MHz',
            'dynamic_fields' => ['capacity' => '16GB']
        ]);

        Product::create([
            'name' => 'Team Elite 8GB DDR4',
            'brand' => 'TeamGroup',
            'model_number' => 'Elite 8GB',
            'price' => 1600,
            'quantity' => 25,
            'category_id' => 3,
            'performance' => 'DDR4 2666MHz',
            'dynamic_fields' => ['capacity' => '8GB']
        ]);

        Product::create([
            'name' => 'Team Elite 16GB DDR4',
            'brand' => 'TeamGroup',
            'model_number' => 'Elite 16GB',
            'price' => 2900,
            'quantity' => 18,
            'category_id' => 3,
            'performance' => 'DDR4 3200MHz',
            'dynamic_fields' => ['capacity' => '16GB']
        ]);

        Product::create([
            'name' => 'ADATA XPG 16GB DDR4',
            'brand' => 'ADATA',
            'model_number' => 'XPG 16GB',
            'price' => 3500,
            'quantity' => 14,
            'category_id' => 3,
            'performance' => 'DDR4 3600MHz RGB',
            'dynamic_fields' => ['capacity' => '16GB']
        ]);

        Product::create([
            'name' => 'Crucial 8GB DDR4',
            'brand' => 'Crucial',
            'model_number' => '8GB DDR4',
            'price' => 1500,
            'quantity' => 35,
            'category_id' => 3,
            'performance' => 'DDR4 2666MHz',
            'dynamic_fields' => ['capacity' => '8GB']
        ]);

        Product::create([
            'name' => 'Crucial 16GB DDR4',
            'brand' => 'Crucial',
            'model_number' => '16GB DDR4',
            'price' => 2800,
            'quantity' => 22,
            'category_id' => 3,
            'performance' => 'DDR4 3200MHz',
            'dynamic_fields' => ['capacity' => '16GB']
        ]);
    }
}