<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = \App\Models\Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all(); // Ambil kategori untuk pilihan dropdown
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        // Simpan file ke public/images directory
        if ($request->hasFile('image')) {
            $imagesPath = public_path('images');
            if (!\Illuminate\Support\Facades\File::exists($imagesPath)) {
                \Illuminate\Support\Facades\File::makeDirectory($imagesPath, 0755, true);
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($imagesPath, $filename);
            $data['image'] = $filename;
        }

        \App\Models\Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambah!');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = \App\Models\Product::findOrFail($id); // Cari produk, jika tidak ada tampilkan 404
        $categories = \App\Models\Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'required',
        ]);

        $product = \App\Models\Product::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            if ($product->image) {
                $oldImagePath = public_path('images/' . $product->image);
                if (\Illuminate\Support\Facades\File::exists($oldImagePath)) {
                    \Illuminate\Support\Facades\File::delete($oldImagePath);
                }
            }

            // Simpan foto baru ke public/images directory
            $imagesPath = public_path('images');
            if (!\Illuminate\Support\Facades\File::exists($imagesPath)) {
                \Illuminate\Support\Facades\File::makeDirectory($imagesPath, 0755, true);
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($imagesPath, $filename);
            $data['image'] = $filename;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // 1. Cari datanya
        $product = \App\Models\Product::findOrFail($id);

        // 2. Hapus gambarnya jika ada
        if ($product->image) {
            $imagePath = public_path('images/' . $product->image);
            if (\Illuminate\Support\Facades\File::exists($imagePath)) {
                \Illuminate\Support\Facades\File::delete($imagePath);
            }
        }

        // 3. Hapus datanya
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
