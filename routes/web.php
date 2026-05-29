<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController; 
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. HALAMAN PUBLIK (TANPA LOGIN)
// ==========================================
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/layanan', [PageController::class, 'layanan']);
Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');


// ==========================================
// 2. HALAMAN DASHBOARD (SETELAH LOGIN)
// ==========================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ==========================================
// 3. PANEL MANAJEMEN ADMIN (WAJIB LOGIN)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // --- MANAJEMEN PROFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- MANAJEMEN PESANAN AKTIF ---
    Route::get('admin/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('admin/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // --- MANAJEMEN RIWAYAT TRANSAKSI & DOWNLOAD LAPORAN ---
    Route::get('admin/transactions/history', [OrderController::class, 'transactionHistory'])->name('transactions.history');
    Route::get('admin/transactions/export', [OrderController::class, 'exportTransactions'])->name('transactions.export');

    // --- CRUD PRODUK CETAKAN ---
    Route::resource('admin/products', ProductController::class);
});

require __DIR__ . '/auth.php';