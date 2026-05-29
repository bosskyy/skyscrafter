<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Memanggil model Product

class PageController extends Controller
{
    // Method untuk halaman Home
    public function home()
    {
        $products = \App\Models\Product::take(3)->get(); // Mengambil 3 produk unggulan
        return view('home', compact('products'));
    }

    // Method untuk halaman Layanan
    public function layanan()
    {
        $products = Product::all();

        // Kirim data ke view 'layanan.blade.php'
        return view('layanan', compact('products'));
    }

    // Method untuk halaman Kontak
    public function kontak()
    {
        return "Hubungi kami di: kupang-print@gmail.com";
    }
}
