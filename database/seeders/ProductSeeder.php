<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    private function createProduct(array $data): void
    {
        $data['image'] = $data['image'] ?? $this->formatImageName($data['name']);

        Product::create($data);
    }

    private function formatImageName($name)
    {
        return 'products/' . strtolower(
            preg_replace('/[^a-zA-Z0-9\-]/', '_', $name)
        ) . '.jpg';
    }

    public function run(): void
    {
        // ======================
        // PROCESSORS
        // ======================

        $this->createProduct([
            'name' => 'Intel Core i5-12400F',
            'brand' => 'Intel',
            'model_number' => 'i5-12400F',
            'price' => 11000,
            'quantity' => 15,
            'category_id' => 1,
            'image' => null,
            'performance' => 'Base: 2.5GHz • Boost: 4.4GHz • Cache: 18MB • TDP: 65W • No iGPU • Cooler Included',
            'dynamic_fields' => [
                'socket' => 'LGA 1700',
                'cores_threads' => '6 / 12',
                'base_boost_clock' => '2.5 / 4.4 GHz',
                'benchmark' => '12000'
            ]
        ]);

        $this->createProduct([
            'name' => 'Intel Core i7-13700K',
            'brand' => 'Intel',
            'model_number' => 'i7-13700K',
            'price' => 23500,
            'quantity' => 8,
            'category_id' => 1,
            'image' => null,
            'performance' => 'Base: 3.4GHz • Boost: 5.4GHz • Cache: 30MB • TDP: 125W • UHD 770 iGPU • No Cooler',
            'dynamic_fields' => [
                'socket' => 'LGA 1700',
                'cores_threads' => '16 / 24',
                'base_boost_clock' => '3.4 / 5.4 GHz',
                'benchmark' => '30000+'
            ]
        ]);

        $this->createProduct([
            'name' => 'AMD Ryzen 5 5600X',
            'brand' => 'AMD',
            'model_number' => '5600X',
            'price' => 9500,
            'quantity' => 12,
            'category_id' => 1,
            'image' => null,
            'performance' => 'Base: 3.7GHz • Boost: 4.6GHz • Cache: 32MB • TDP: 65W • No iGPU • Wraith Cooler',
            'dynamic_fields' => [
                'socket' => 'AM4',
                'cores_threads' => '6 / 12',
                'base_boost_clock' => '3.7 / 4.6 GHz',
                'benchmark' => '11000'
            ]
        ]);

        $this->createProduct([
            'name' => 'AMD Ryzen 7 5800X',
            'brand' => 'AMD',
            'model_number' => '5800X',
            'price' => 15500,
            'quantity' => 10,
            'category_id' => 1,
            'image' => null,
            'performance' => 'Base: 3.8GHz • Boost: 4.7GHz • Cache: 32MB • TDP: 105W • No iGPU • No Cooler',
            'dynamic_fields' => [
                'socket' => 'AM4',
                'cores_threads' => '8 / 16',
                'base_boost_clock' => '3.8 / 4.7 GHz',
                'benchmark' => '15000'
            ]
        ]);

        $this->createProduct([
            'name' => 'Intel Core i3-12100',
            'brand' => 'Intel',
            'model_number' => 'i3-12100',
            'price' => 6800,
            'quantity' => 20,
            'category_id' => 1,
            'image' => null,
            'performance' => 'Base: 3.3GHz • Boost: 4.3GHz • Cache: 12MB • TDP: 60W • UHD 730 iGPU • Cooler Included',
            'dynamic_fields' => [
                'socket' => 'LGA 1700',
                'cores_threads' => '4 / 8',
                'base_boost_clock' => '3.3 / 4.3 GHz',
                'benchmark' => '8000'
            ]
        ]);

        $this->createProduct([
            'name' => 'Intel Core i9-13900K',
            'brand' => 'Intel',
            'model_number' => 'i9-13900K',
            'price' => 32000,
            'quantity' => 5,
            'category_id' => 1,
            'image' => null,
            'performance' => '24 cores • Boost 5.8GHz • 36MB Cache',
            'dynamic_fields' => [
                'socket' => 'LGA1700',
                'cores_threads' => '24 / 32'
            ]
        ]);

        $this->createProduct([
            'name' => 'AMD Ryzen 9 5900X',
            'brand' => 'AMD',
            'model_number' => '5900X',
            'price' => 21000,
            'quantity' => 7,
            'category_id' => 1,
            'image' => null,
            'performance' => '12 cores • Boost 4.8GHz',
            'dynamic_fields' => [
                'socket' => 'AM4',
                'cores_threads' => '12 / 24'
            ]
        ]);

        $this->createProduct([
            'name' => 'Intel Core i5-13400',
            'brand' => 'Intel',
            'model_number' => 'i5-13400',
            'price' => 12500,
            'quantity' => 10,
            'category_id' => 1,
            'image' => null,
            'performance' => '10 cores • Boost 4.6GHz',
            'dynamic_fields' => [
                'socket' => 'LGA1700'
            ]
        ]);

        $this->createProduct([
            'name' => 'AMD Ryzen 5 7600',
            'brand' => 'AMD',
            'model_number' => '7600',
            'price' => 14000,
            'quantity' => 9,
            'category_id' => 1,
            'image' => null,
            'performance' => '6 cores • Boost 5.1GHz',
            'dynamic_fields' => [
                'socket' => 'AM5'
            ]
        ]);

        $this->createProduct([
            'name' => 'Intel Pentium Gold G6400',
            'brand' => 'Intel',
            'model_number' => 'G6400',
            'price' => 3500,
            'quantity' => 20,
            'category_id' => 1,
            'image' => null,
            'performance' => '2 cores • Budget CPU',
            'dynamic_fields' => [
                'socket' => 'LGA1200'
            ]
        ]);

        // ======================
        // MOTHERBOARDS
        // ======================

        $this->createProduct([
            'name' => 'ASUS PRIME B660M-K',
            'brand' => 'ASUS',
            'model_number' => 'B660M-K',
            'price' => 6500,
            'quantity' => 10,
            'category_id' => 2,
            'image' => null,
            'performance' => 'Chipset: B660 • RAM Slots: 2 • Max RAM: 64GB • PCIe 4.0 • M.2 Slot • Basic VRM',
            'dynamic_fields' => [
                'socket' => 'LGA 1700',
                'form_factor' => 'mATX',
                'chipset' => 'B660',
                'ram_support' => 'DDR4 3200'
            ]
        ]);

        $this->createProduct([
            'name' => 'MSI B550M PRO-VDH WIFI',
            'brand' => 'MSI',
            'model_number' => 'B550M PRO-VDH',
            'price' => 7500,
            'quantity' => 12,
            'category_id' => 2,
            'image' => null,
            'performance' => 'Chipset: B550 • RAM Slots: 4 • Max RAM: 128GB • PCIe 4.0 • WiFi • M.2 Slots',
            'dynamic_fields' => [
                'socket' => 'AM4',
                'form_factor' => 'mATX',
                'chipset' => 'B550',
                'ram_support' => 'DDR4 4400'
            ]
        ]);

        $this->createProduct([
            'name' => 'Gigabyte B450M DS3H',
            'brand' => 'Gigabyte',
            'model_number' => 'B450M DS3H',
            'price' => 5200,
            'quantity' => 15,
            'category_id' => 2,
            'image' => null,
            'performance' => 'Chipset: B450 • RAM Slots: 4 • Max RAM: 64GB • PCIe 3.0 • Budget Board',
            'dynamic_fields' => [
                'socket' => 'AM4',
                'form_factor' => 'mATX',
                'chipset' => 'B450',
                'ram_support' => 'DDR4 3600'
            ]
        ]);

        $this->createProduct([
            'name' => 'ASUS ROG STRIX Z690-E',
            'brand' => 'ASUS',
            'model_number' => 'Z690-E',
            'price' => 18000,
            'quantity' => 5,
            'category_id' => 2,
            'image' => null,
            'performance' => 'Chipset: Z690 • RAM Slots: 4 • Max RAM: 128GB • PCIe 5.0 • WiFi 6 • High-end VRM',
            'dynamic_fields' => [
                'socket' => 'LGA 1700',
                'form_factor' => 'ATX',
                'chipset' => 'Z690',
                'ram_support' => 'DDR5 6400'
            ]
        ]);

        $this->createProduct([
            'name' => 'MSI A320M-A PRO',
            'brand' => 'MSI',
            'model_number' => 'A320M-A PRO',
            'price' => 3800,
            'quantity' => 20,
            'category_id' => 2,
            'image' => null,
            'performance' => 'Chipset: A320 • RAM Slots: 2 • Max RAM: 32GB • PCIe 3.0 • Entry-level',
            'dynamic_fields' => [
                'socket' => 'AM4',
                'form_factor' => 'mATX',
                'chipset' => 'A320',
                'ram_support' => 'DDR4 3200'
            ]
        ]);

        $this->createProduct([
            'name' => 'ASRock B550 Steel Legend',
            'brand' => 'ASRock',
            'model_number' => 'B550 Steel Legend',
            'price' => 8500,
            'quantity' => 10,
            'category_id' => 2,
            'image' => null,
            'performance' => 'B550 • PCIe 4.0',
            'dynamic_fields' => ['socket' => 'AM4']
        ]);

        $this->createProduct([
            'name' => 'Gigabyte Z790 AORUS Elite',
            'brand' => 'Gigabyte',
            'model_number' => 'Z790',
            'price' => 16000,
            'quantity' => 6,
            'category_id' => 2,
            'image' => null,
            'performance' => 'Z790 • DDR5',
            'dynamic_fields' => ['socket' => 'LGA1700']
        ]);

        $this->createProduct([
            'name' => 'MSI MAG B660 Tomahawk',
            'brand' => 'MSI',
            'model_number' => 'B660 Tomahawk',
            'price' => 9500,
            'quantity' => 8,
            'category_id' => 2,
            'image' => null,
            'performance' => 'B660 Gaming',
            'dynamic_fields' => ['socket' => 'LGA1700']
        ]);

        $this->createProduct([
            'name' => 'ASUS TUF Gaming B550',
            'brand' => 'ASUS',
            'model_number' => 'B550 TUF',
            'price' => 9000,
            'quantity' => 9,
            'category_id' => 2,
            'image' => null,
            'performance' => 'Durable board',
            'dynamic_fields' => ['socket' => 'AM4']
        ]);

        $this->createProduct([
            'name' => 'Biostar A520MH',
            'brand' => 'Biostar',
            'model_number' => 'A520MH',
            'price' => 3500,
            'quantity' => 15,
            'category_id' => 2,
            'image' => null,
            'performance' => 'Budget motherboard',
            'dynamic_fields' => ['socket' => 'AM4']
        ]);

        // ======================
        // GRAPHICS CARDS
        // ======================

        $this->createProduct([
            'name' => 'NVIDIA RTX 3060',
            'brand' => 'NVIDIA',
            'model_number' => 'RTX 3060',
            'price' => 18000,
            'quantity' => 10,
            'category_id' => 3,
            'image' => null,
            'performance' => 'Boost: 1.78GHz • VRAM: 12GB GDDR6 • TDP: 170W',
            'dynamic_fields' => [
                'vram' => '12GB',
                'clock_speed' => '1.78GHz',
                'power_consumption' => '170W'
            ]
        ]);

        $this->createProduct([
            'name' => 'NVIDIA RTX 3070',
            'brand' => 'NVIDIA',
            'model_number' => 'RTX 3070',
            'price' => 25000,
            'quantity' => 8,
            'category_id' => 3,
            'image' => null,
            'performance' => 'Boost: 1.73GHz • VRAM: 8GB GDDR6 • TDP: 220W',
            'dynamic_fields' => [
                'vram' => '8GB',
                'clock_speed' => '1.73GHz',
                'power_consumption' => '220W'
            ]
        ]);

        $this->createProduct([
            'name' => 'NVIDIA RTX 3080',
            'brand' => 'NVIDIA',
            'model_number' => 'RTX 3080',
            'price' => 35000,
            'quantity' => 5,
            'category_id' => 3,
            'image' => null,
            'performance' => 'Boost: 1.71GHz • VRAM: 10GB GDDR6X • TDP: 320W',
            'dynamic_fields' => [
                'vram' => '10GB',
                'clock_speed' => '1.71GHz',
                'power_consumption' => '320W'
            ]
        ]);

        $this->createProduct([
            'name' => 'NVIDIA RTX 4060',
            'brand' => 'NVIDIA',
            'model_number' => 'RTX 4060',
            'price' => 20000,
            'quantity' => 12,
            'category_id' => 3,
            'image' => null,
            'performance' => 'Boost: 2.46GHz • VRAM: 8GB GDDR6 • TDP: 115W',
            'dynamic_fields' => [
                'vram' => '8GB',
                'clock_speed' => '2.46GHz',
                'power_consumption' => '115W'
            ]
        ]);

        $this->createProduct([
            'name' => 'NVIDIA RTX 4090',
            'brand' => 'NVIDIA',
            'model_number' => 'RTX 4090',
            'price' => 90000,
            'quantity' => 3,
            'category_id' => 3,
            'image' => null,
            'performance' => 'Boost: 2.52GHz • VRAM: 24GB GDDR6X • TDP: 450W',
            'dynamic_fields' => [
                'vram' => '24GB',
                'clock_speed' => '2.52GHz',
                'power_consumption' => '450W'
            ]
        ]);

        $this->createProduct([
            'name' => 'AMD RX 6600',
            'brand' => 'AMD',
            'model_number' => 'RX 6600',
            'price' => 16000,
            'quantity' => 14,
            'category_id' => 3,
            'image' => null,
            'performance' => 'Boost: 2.49GHz • VRAM: 8GB GDDR6 • TDP: 132W',
            'dynamic_fields' => [
                'vram' => '8GB',
                'clock_speed' => '2.49GHz',
                'power_consumption' => '132W'
            ]
        ]);

        $this->createProduct([
            'name' => 'AMD RX 6700 XT',
            'brand' => 'AMD',
            'model_number' => 'RX 6700 XT',
            'price' => 22000,
            'quantity' => 9,
            'category_id' => 3,
            'image' => null,
            'performance' => 'Boost: 2.58GHz • VRAM: 12GB GDDR6 • TDP: 230W',
            'dynamic_fields' => [
                'vram' => '12GB',
                'clock_speed' => '2.58GHz',
                'power_consumption' => '230W'
            ]
        ]);

        $this->createProduct([
            'name' => 'AMD RX 6800',
            'brand' => 'AMD',
            'model_number' => 'RX 6800',
            'price' => 30000,
            'quantity' => 6,
            'category_id' => 3,
            'image' => null,
            'performance' => 'Boost: 2.10GHz • VRAM: 16GB GDDR6 • TDP: 250W',
            'dynamic_fields' => [
                'vram' => '16GB',
                'clock_speed' => '2.10GHz',
                'power_consumption' => '250W'
            ]
        ]);

        $this->createProduct([
            'name' => 'AMD RX 7900 XTX',
            'brand' => 'AMD',
            'model_number' => 'RX 7900 XTX',
            'price' => 60000,
            'quantity' => 4,
            'category_id' => 3,
            'image' => null,
            'performance' => 'Boost: 2.50GHz • VRAM: 24GB GDDR6 • TDP: 355W',
            'dynamic_fields' => [
                'vram' => '24GB',
                'clock_speed' => '2.50GHz',
                'power_consumption' => '355W'
            ]
        ]);

        $this->createProduct([
            'name' => 'NVIDIA GTX 1660 Super',
            'brand' => 'NVIDIA',
            'model_number' => 'GTX 1660 Super',
            'price' => 12000,
            'quantity' => 15,
            'category_id' => 3,
            'image' => null,
            'performance' => 'Boost: 1.78GHz • VRAM: 6GB GDDR6 • TDP: 125W',
            'dynamic_fields' => [
                'vram' => '6GB',
                'clock_speed' => '1.78GHz',
                'power_consumption' => '125W'
            ]
        ]);
    }
}