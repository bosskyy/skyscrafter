<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'customer_whatsapp' => 'required|string',
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // Maksimal 10MB
        ]);

        $order = new Order();
        $order->product_id = $request->product_id;
        $order->customer_name = $request->customer_name;
        $order->quantity = $request->quantity;
        $order->customer_whatsapp = $request->customer_whatsapp;

        if ($request->hasFile('document_file')) {
            $filePath = $request->file('document_file')->store('document_files', 'public');
            $order->document_file = $filePath;
        }

        $order->save();

        return redirect()->back()->with('success', 'Pesanan berhasil dikirim! File Anda telah kami terima.');
    }

    // Mengubah status pesanan menjadi Selesai
    public function updateStatus($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'Selesai']);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // Menampilkan Pesanan Masuk (Hanya yang statusnya BUKAN Selesai / Pending)
    public function index()
    {
        // Mengambil pesanan yang belum selesai agar halaman utama admin tetap bersih
        $orders = Order::with('product')
            ->where('status', '!=', 'Selesai')
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    // ==========================================
    // FITUR TAMBAHAN: RIWAYAT TRANSAKSI
    // ==========================================

    // 1. Menampilkan halaman riwayat khusus pesanan "Selesai"
    public function transactionHistory()
    {
        $completedOrders = Order::with('product')
            ->where('status', 'Selesai')
            ->latest()
            ->get();

        return view('admin.orders.history', compact('completedOrders'));
    }

    // 2. Mengunduh data riwayat transaksi menjadi file CSV (Bisa langsung dibuka di Excel)
    public function exportTransactions()
    {
        $completedOrders = Order::with('product')
            ->where('status', 'Selesai')
            ->latest()
            ->get();

        $fileName = 'Riwayat_Transaksi_Skycrafter_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tanggal Selesai', 'Nama Pelanggan', 'Produk', 'Jumlah', 'WhatsApp', 'Total Harga'];

        $callback = function() use($completedOrders, $columns) {
            $file = fopen('php://output', 'w');
            
            // Menambahkan BOM (Byte Order Mark) agar Excel membaca tanda koma dan huruf dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Tulis header kolom
            fputcsv($file, $columns);

            // Tulis baris data transaksi
            foreach ($completedOrders as $order) {
                $totalHarga = $order->quantity * $order->product->price;

                fputcsv($file, [
                    $order->updated_at->format('d M Y H:i'),
                    $order->customer_name,
                    $order->product->name,
                    $order->quantity . ' pcs',
                    $order->customer_whatsapp,
                    'Rp ' . number_format($totalHarga, 0, ',', '.')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}