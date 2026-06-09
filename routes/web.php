<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController; 
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 0. IMAGE FALLBACK (Jika symlink tidak ada)
// ==========================================
Route::get('/api/images/{path}', [ImageController::class, 'serve'])->name('images.serve');

// ==========================================
// 1. HALAMAN PUBLIK (TANPA LOGIN)
// ==========================================
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/layanan', [PageController::class, 'layanan']);
Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
Route::post('/keranjang/{lineId}/hapus', [CartController::class, 'remove'])->name('cart.remove');

Route::post('/checkout/start', [CheckoutController::class, 'start'])->name('checkout.start');
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/{checkoutId}/pembayaran', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/checkout/{checkoutId}/upload-bukti', [CheckoutController::class, 'paymentProofForm'])->name('checkout.paymentProofForm');
Route::post('/checkout/{checkoutId}/upload-bukti', [CheckoutController::class, 'uploadPaymentProof'])->name('checkout.uploadPaymentProof');


// ==========================================
// 2. HALAMAN DASHBOARD (SETELAH LOGIN)
// ==========================================
Route::get('/dashboard', function () {
    return redirect()->route('orders.index');
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
    Route::patch('admin/orders/{id}/payment-validation', [OrderController::class, 'togglePaymentValidation'])->name('orders.togglePaymentValidation');
    Route::patch('admin/orders/checkout/{checkoutId}/status', [OrderController::class, 'updateStatusByCheckout'])->name('orders.updateStatusByCheckout');

    // --- MANAJEMEN RIWAYAT TRANSAKSI & DOWNLOAD LAPORAN ---
    Route::get('admin/transactions/history', [OrderController::class, 'transactionHistory'])->name('transactions.history');
    Route::get('admin/transactions/export', [OrderController::class, 'exportTransactions'])->name('transactions.export');

    // --- CRUD PRODUK CETAKAN ---
    Route::resource('admin/products', ProductController::class);

    // --- MANAJEMEN TEMPLATE ---
    Route::get('admin/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::post('admin/templates/upload', [TemplateController::class, 'upload'])->name('templates.upload');
    Route::post('admin/templates/delete', [TemplateController::class, 'delete'])->name('templates.delete');
});

require __DIR__ . '/auth.php';