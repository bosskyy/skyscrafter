<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kita buat satu kategori utama dulu
        $category = Category::create([
            'name' => 'Jasa Percetakan',
            'slug' => 'jasa-percetakan'
        ]);

        // 2. Daftar produk yang kamu berikan tadi
        $products = [
            ['name' => 'Pas Foto', 'price' => 5000, 'desc' => 'Cetak pas foto berbagai ukuran (2x3, 3x4, 4x6).'],
            ['name' => 'Fotocopy & Print', 'price' => 500, 'desc' => 'Jasa fotocopy dan print dokumen hitam putih/warna.'],
            ['name' => 'Jilid', 'price' => 5000, 'desc' => 'Jilid lakban, jilid mika, atau jilid spiral.'],
            ['name' => 'Laminating', 'price' => 3000, 'desc' => 'Perlindungan dokumen dengan plastik laminating panas.'],
            ['name' => 'Undangan', 'price' => 2000, 'desc' => 'Cetak undangan pernikahan atau acara lainnya.'],
            ['name' => 'Polaroid & Photostrip', 'price' => 1500, 'desc' => 'Cetak foto ala polaroid atau photostrip kekinian.'],
        ];

        // 3. Masukkan ke database menggunakan perulangan (looping)
        foreach ($products as $item) {
            Product::create([
                'category_id' => $category->id,
                'name'        => $item['name'],
                'description' => $item['desc'],
                'price'       => $item['price'],
                'image'       => null, // Sementara kosongkan dulu fotonya
            ]);
        }
    }
}