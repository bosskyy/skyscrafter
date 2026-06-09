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

    // Mengubah status pesanan menjadi Selesai (semua item di dalam satu checkout)
    public function updateStatusByCheckout($checkoutId)
    {
        Order::where('checkout_id', $checkoutId)->update(['status' => 'Selesai']);
        return redirect()->back()->with('success', 'Transaksi berhasil diselesaikan!');
    }

    // Mengubah status pesanan menjadi Selesai
    public function updateStatus($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'Selesai']);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // Menampilkan Pesanan Masuk (Hanya yang statusnya BUKAN Selesai / Pending)
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'waktu_baru');

        // Mengambil pesanan yang belum selesai agar halaman utama admin tetap bersih
        // Lalu dikelompokkan berdasarkan checkout_id, jika null maka gunakan ID pesanan sendiri.
        $query = Order::with('product')
            ->where('status', '!=', 'Selesai')
            ->get()
            ->groupBy(function ($order) {
                return $order->checkout_id ?: 'LEGACY-' . $order->id;
            });

        if ($sort === 'waktu_lama') {
            $orders = $query->sortBy(fn($group) => $group->first()->created_at);
        } elseif ($sort === 'kustom_nama') {
            $orders = $query->sortBy(fn($group) => $group->first()->customer_name);
        } elseif ($sort === 'produk') {
            $orders = $query->sortBy(fn($group) => $group->first()->product->name ?? '');
        } else {
            $orders = $query->sortByDesc(fn($group) => $group->first()->created_at);
        }

        return view('admin.orders.index', compact('orders', 'sort'));
    }

    // ==========================================
    // FITUR TAMBAHAN: RIWAYAT TRANSAKSI
    // ==========================================

    // 1. Menampilkan halaman riwayat khusus pesanan "Selesai"
    public function transactionHistory(Request $request)
    {
        $sort = $request->query('sort', 'waktu_baru');

        $query = Order::with('product')
            ->where('status', 'Selesai')
            ->get()
            ->groupBy(function ($order) {
                return $order->checkout_id ?: 'LEGACY-' . $order->id;
            });

        if ($sort === 'waktu_lama') {
            $completedOrders = $query->sortBy(fn($group) => $group->first()->updated_at);
        } elseif ($sort === 'kustom_nama') {
            $completedOrders = $query->sortBy(fn($group) => $group->first()->customer_name);
        } elseif ($sort === 'produk') {
            $completedOrders = $query->sortBy(fn($group) => $group->first()->product->name ?? '');
        } else {
            $completedOrders = $query->sortByDesc(fn($group) => $group->first()->updated_at);
        }

        return view('admin.orders.history', compact('completedOrders', 'sort'));
    }

    // 2. Mengunduh data riwayat transaksi menjadi file CSV (Bisa langsung dibuka di Excel)
    public function exportTransactions()
    {
        $completedGroupedOrders = Order::with('product')
            ->where('status', 'Selesai')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy(function ($order) {
                return $order->checkout_id ?: 'LEGACY-' . $order->id;
            });

        $fileName = 'Riwayat_Transaksi_Skycrafter_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Transaksi', 'Waktu', 'Nama Pelanggan', 'WhatsApp', 'Pengantaran', 'Total Harga', 'Detail Pesanan'];

        $callback = function() use($completedGroupedOrders, $columns) {
            $file = fopen('php://output', 'w');
            
            // Menambahkan BOM (Byte Order Mark) agar Excel membaca tanda koma dan huruf dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Tulis header kolom
            fputcsv($file, $columns);

            // Tulis baris data transaksi
            foreach ($completedGroupedOrders as $checkoutId => $group) {
                $first = $group->first();
                $totalHarga = $group->sum(fn($o) => $o->totalPrice());
                
                $detailItems = [];
                foreach($group as $o) {
                    $itemText = $o->quantity . 'x ' . $o->product->name;
                    if ($o->optionsSummary()) {
                        $itemText .= ' (' . $o->optionsSummary() . ')';
                    }
                    $detailItems[] = $itemText;
                }
                
                $trxCode = str_starts_with($checkoutId, 'LEGACY-') ? $checkoutId : 'TRX-' . strtoupper(substr($checkoutId, 0, 8));

                fputcsv($file, [
                    $trxCode,
                    $first->updated_at->format('d M Y H:i'),
                    $first->customer_name,
                    $first->customer_whatsapp,
                    $first->delivery_method === 'diantar' ? "Diantar ({$first->delivery_address})" : 'Diambil',
                    'Rp ' . number_format($totalHarga, 0, ',', '.'),
                    implode(', ', $detailItems)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}