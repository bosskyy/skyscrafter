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

        // Ambil list template photostrip
        $templates = [];
        $templatePath = public_path('templates');
        if (\Illuminate\Support\Facades\File::exists($templatePath)) {
            $files = \Illuminate\Support\Facades\File::files($templatePath);
            foreach ($files as $file) {
                if (in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
                    $templates[] = 'templates/' . $file->getFilename();
                }
            }
        }

        // Kirim data ke view 'layanan.blade.php'
        return view('layanan', compact('products', 'templates'));
    }

    // Method untuk halaman Kontak
    public function kontak()
    {
        return "Hubungi kami di: kupang-print@gmail.com";
    }
}
