<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Makanan
            [
                'name' => 'Bakso',
                'description' => 'Bakso rudy bukan bakso sony',
                'price' => 15000,
                'cost_price' => 8000,
                'sku' => 'FD-001',
                'barcode' => '8991001200016',
                'stock' => 50,
                'min_stock' => 10,
                'category' => 'makanan',
                'image' => '5ed5f39c7d8fe.jpg',
                'attributes' => ['type' => 'makanan_berat', 'spicy_level' => 'medium']
            ],
            [
                'name' => 'Bakso jumbo',
                'description' => 'Bakso rudy bukan bakso sony',
                'price' => 20000,
                'cost_price' => 10000,
                'sku' => 'FD-002',
                'barcode' => '8991001200017',
                'stock' => 50,
                'min_stock' => 10,
                'category' => 'makanan',
                'image' => 'bakso-jumbo.jpg',
                'attributes' => ['type' => 'makanan_berat', 'spicy_level' => 'medium']
            ],
            [
                'name' => 'Bakso tetelan',
                'description' => 'Bakso rudy bukan bakso sony',
                'price' => 18000,
                'cost_price' => 10000,
                'sku' => 'FD-003',
                'barcode' => '8991001200018',
                'stock' => 50,
                'min_stock' => 10,
                'category' => 'makanan',
                'image' => 'bakso-tetelan.webp',
                'attributes' => ['type' => 'makanan_berat', 'spicy_level' => 'medium']
            ],
            [
                'name' => 'Bakso urat',
                'description' => 'Bakso rudy bukan bakso sony',
                'price' => 18000,
                'cost_price' => 10000,
                'sku' => 'FD-004',
                'barcode' => '8991001200019',
                'stock' => 50,
                'min_stock' => 10,
                'category' => 'makanan',
                'image' => 'bakso-urat.jpg',
                'attributes' => ['type' => 'makanan_berat', 'spicy_level' => 'medium']
            ],
            [
                'name' => 'Mie ayam',
                'description' => 'Bakso rudy bukan bakso sony',
                'price' => 15000,
                'cost_price' => 10000,
                'sku' => 'FD-005',
                'barcode' => '8991001200020',
                'stock' => 50,
                'min_stock' => 10,
                'category' => 'makanan',
                'image' => 'mie-ayam.webp',
                'attributes' => ['type' => 'makanan_berat', 'spicy_level' => 'medium']
            ],
            // Minuman
            [
                'name' => 'Es Teh Manis',
                'description' => 'Es teh manis segar',
                'price' => 5000,
                'cost_price' => 2000,
                'sku' => 'DR-005',
                'barcode' => '8991001200021',
                'stock' => 100,
                'min_stock' => 20,
                'category' => 'minuman',
                'image' => '64dc53ca9f3db.jpg',
                'attributes' => ['type' => 'minuman_dingin', 'size' => 'regular']
            ],

            // Snack & Lainnya
            [
                'name' => 'Toping',
                'description' => 'Sambal pedas homemade',
                'price' => 2000,
                'cost_price' => 800,
                'sku' => 'SN-002',
                'barcode' => '8991001200122',
                'stock' => 150,
                'min_stock' => 30,
                'category' => 'snack',
                'image' => '9045de619d3063de.jpg',
                'attributes' => ['type' => 'condiment', 'spicy_level' => 'hot']
            ],
            [
                'name' => 'Kerupuk',
                'description' => 'Kerupuk udang renyah',
                'price' => 3000,
                'cost_price' => 1500,
                'sku' => 'SN-003',
                'barcode' => '8991001200123',
                'stock' => 200,
                'min_stock' => 40,
                'category' => 'snack',
                'image' => 'b4123fd43c938974dac47b1108c44cd8.jpeg',
                'attributes' => ['type' => 'snack', 'package' => 'small']
            ],
            [
                'name' => 'Somay',
                'description' => 'Somay ikan',
                'price' => 10000,
                'cost_price' => 1500,
                'sku' => 'SN-004',
                'barcode' => '8991001200124',
                'stock' => 200,
                'min_stock' => 40,
                'category' => 'snack',
                'image' => 'somay.webp',
                'attributes' => ['type' => 'snack', 'package' => 'small']
            ],
            [
                'name' => 'Empek empek',
                'description' => 'Somay ikan',
                'price' => 10000,
                'cost_price' => 5000,
                'sku' => 'SN-005',
                'barcode' => '8991001200125',
                'stock' => 200,
                'min_stock' => 40,
                'category' => 'snack',
                'image' => 'empek-empek.webp',
                'attributes' => ['type' => 'snack', 'package' => 'small']
            ],
            [
                'name' => 'Batagor',
                'description' => 'Somay ikan',
                'price' => 10000,
                'cost_price' => 5000,
                'sku' => 'SN-006',
                'barcode' => '8991001200126',
                'stock' => 200,
                'min_stock' => 40,
                'category' => 'snack',
                'image' => 'batagor.webp',
                'attributes' => ['type' => 'snack', 'package' => 'small']
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Berhasil menambahkan ' . count($products) . ' produk ke database.');
    }
}