<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        $productIds = collect($cart)
            ->pluck('product_id')
            ->unique()
            ->values()
            ->all();

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        return view('cart.index', [
            'cart' => $cart,
            'products' => $products,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);

        $rules = [];

        if ($product->name === 'Pas Foto') {
            $rules = [
                'variant' => ['required', 'in:warna,hitam_putih'],
                'size' => ['required', 'in:2x3,3x4,4x6'],
                'document_file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            ];
        } elseif ($product->name === 'Fotocopy & Print') {
            $rules = [
                'variant' => ['required', 'in:warna,hitam_putih'],
                'copies' => ['required', 'integer', 'min:1', 'max:100'],
                'document_file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            ];
        } elseif ($product->name === 'Jilid') {
            $rules = [
                'binding_color' => ['required', 'in:Hitam,Biru,Merah'],
                'note' => ['required', 'string', 'max:1000'],
            ];
        } elseif ($product->name === 'Undangan') {
            $rules = [
                'document_file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
                'jenis_undangan' => ['required', 'string'],
                'nama_pengundang' => ['required', 'string'],
                'tanggal_acara' => ['required', 'string'],
                'tempat_acara' => ['required', 'string'],
                'waktu_acara' => ['required', 'string'],
                'keterangan_tambahan' => ['nullable', 'string'],
            ];
        } elseif ($product->name === 'Polaroid & Photostrip') {
            $rules = [
                'variant' => ['required', 'in:Polaroid,Photostrip'],
                'document_file' => ['nullable', 'array', 'min:1'],
                'document_file.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
                'photostrip_files' => ['nullable', 'array', 'min:3'],
                'photostrip_files.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
                'photostrip_template' => ['nullable', 'string']
            ];
        } else {
            $rules = [
                'document_file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            ];
        }

        $validated = $request->validate($rules);

        $options = [];
        if (isset($validated['variant'])) {
            $options['variant'] = $validated['variant'];
        }
        if (isset($validated['size'])) {
            $options['size'] = $validated['size'];
        }
        if (isset($validated['copies'])) {
            $options['copies'] = (int) $validated['copies'];
        }
        if (isset($validated['binding_color'])) {
            $options['binding_color'] = $validated['binding_color'];
        }
        if (isset($validated['photostrip_template']) && $validated['variant'] === 'Photostrip') {
            $options['photostrip_template'] = $validated['photostrip_template'];
        }

        $note = $validated['note'] ?? null;
        if ($product->name === 'Undangan') {
            $parts = [
                'Jenis: ' . $validated['jenis_undangan'],
                'Oleh: ' . $validated['nama_pengundang'],
                'Tgl: ' . $validated['tanggal_acara'],
                'Waktu: ' . $validated['waktu_acara'],
                'Tmpt: ' . $validated['tempat_acara'],
            ];
            if (!empty($validated['keterangan_tambahan'])) {
                $parts[] = 'Ket: ' . $validated['keterangan_tambahan'];
            }
            $note = implode(' | ', $parts);
        }

        $documentFilePath = null;
        if ($product->name === 'Polaroid & Photostrip') {
            $uploadedPhotos = [];
            if (isset($validated['variant']) && $validated['variant'] === 'Photostrip') {
                if ($request->hasFile('photostrip_files')) {
                    foreach ($request->file('photostrip_files') as $file) {
                        if ($file) {
                            $uploadedPhotos[] = $this->storePublicUpload($file, 'uploads/document_files');
                        }
                    }
                }
            } else {
                if ($request->hasFile('document_file')) {
                    $dFiles = is_array($request->file('document_file')) ? $request->file('document_file') : [$request->file('document_file')];
                    foreach ($dFiles as $file) {
                        if ($file) {
                            $uploadedPhotos[] = $this->storePublicUpload($file, 'uploads/document_files');
                        }
                    }
                }
            }
            if (!empty($uploadedPhotos)) {
                $options['uploaded_photos'] = $uploadedPhotos;
            }
        } elseif ($request->hasFile('document_file')) {
            if (is_array($request->file('document_file'))) {
                $absoluteDir = public_path('uploads/document_files');
                if (!\Illuminate\Support\Facades\File::exists($absoluteDir)) {
                    \Illuminate\Support\Facades\File::makeDirectory($absoluteDir, 0755, true);
                }
                $zip = new \ZipArchive();
                $zipFileName = 'uploads/document_files/' . \Illuminate\Support\Str::uuid() . '.zip';
                $zipPath = public_path($zipFileName);
                if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                    foreach ($request->file('document_file') as $index => $file) {
                        if ($file) {
                            $extension = $file->getClientOriginalExtension();
                            $zip->addFile($file->getRealPath(), 'foto_' . ($index+1) . '.' . $extension);
                        }
                    }
                    $zip->close();
                }
                $documentFilePath = $zipFileName;
            } else {
                $documentFilePath = $this->storePublicUpload($request->file('document_file'), 'uploads/document_files');
            }
        }

        $cart = session()->get('cart', []);
        $lineId = (string) Str::uuid();

        $cart[$lineId] = [
            'line_id' => $lineId,
            'product_id' => (int) $product->id,
            'quantity' => (int) $request->quantity,
            'note' => $note,
            'options' => $options,
            'document_file' => $documentFilePath,
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function remove(string $lineId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$lineId])) {
            unset($cart[$lineId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Item dihapus dari keranjang.');
    }

    private function storePublicUpload(UploadedFile $file, string $relativeDir): string
    {
        $absoluteDir = public_path($relativeDir);

        if (!File::exists($absoluteDir)) {
            File::makeDirectory($absoluteDir, 0755, true);
        }

        $filename = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($absoluteDir, $filename);

        return trim($relativeDir, '/') . '/' . $filename;
    }
}
