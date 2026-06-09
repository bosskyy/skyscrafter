<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::updateOrCreate([
            'slug' => 'jasa-percetakan',
        ], [
            'name' => 'Jasa Percetakan',
            'slug' => 'jasa-percetakan'
        ]);

        $products = [
            ['name' => 'Pas Foto', 'price' => 8000, 'desc' => 'Cetak pas foto berbagai ukuran (2x3, 3x4, 4x6). 1 paket mendapatkan 5 foto.', 'image' => 'Pas foto.png'],
            ['name' => 'Fotocopy & Print', 'price' => 500, 'desc' => 'Jasa fotocopy dan print dokumen hitam putih/warna.', 'image' => 'fotocopy.png'],
            ['name' => 'Jilid', 'price' => 5000, 'desc' => 'Jilid lakban, jilid mika, atau jilid spiral.', 'image' => 'jilid.png'],
            ['name' => 'Laminating', 'price' => 3000, 'desc' => 'Perlindungan dokumen dengan plastik laminating panas.', 'image' => 'laminating.png'],
            ['name' => 'Undangan', 'price' => 2000, 'desc' => 'Cetak undangan pernikahan atau acara lainnya.', 'image' => 'undangan.png'],
            ['name' => 'Polaroid & Photostrip', 'price' => 1500, 'desc' => 'Cetak foto ala polaroid atau photostrip kekinian.', 'image' => 'polaroid photostrip.png'],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate([
                'name' => $item['name'],
            ], [
                'category_id' => $category->id,
                'name'        => $item['name'],
                'description' => $item['desc'],
                'price'       => $item['price'],
                'image'       => $item['image'],
            ]);
        }
    }
}