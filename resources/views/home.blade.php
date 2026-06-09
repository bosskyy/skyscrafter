@extends('layouts.app')

@section('content')
<div class="row g-0 align-items-center bg-white shadow-sm rounded-4 overflow-hidden mb-5 mt-2 custom-shadow">
    <!-- Hero Section -->
    <div class="col-lg-6 p-5 p-xl-5 position-relative text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #00438a 100%);">
        
        <div class="position-relative z-1">
            <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-bold">#1 Layanan Cetak Online</span>
            <h1 class="display-5 fw-bolder mb-3 lh-sm transition-up">
                Cetak Online.<br>Tinggal Duduk Manis.
            </h1>
            <p class="fs-6 mb-4 opacity-75 fw-light" style="max-width: 90%;">
                Nggak perlu lagi macet-macetan ke toko percetakan. Upload file Anda, bayar lewat QRIS, dan kurir kami siap mengantar pesanan langsung ke depan pintu Anda!
            </p>
            
            <div class="d-flex flex-wrap gap-3 mt-4 transition-up" style="animation-delay: 0.2s;">
                <a href="/layanan" class="btn btn-warning btn-lg px-4 fw-bold shadow-lg hover-scale text-dark">
                    <i class="bi bi-cart-plus-fill me-2"></i>Mulai Pesan
                </a>
                <a href="https://wa.me/6285738278755" target="_blank" class="btn btn-outline-light btn-lg px-4 fw-bold hover-scale">
                    <i class="bi bi-whatsapp me-2"></i>Tanya CS
                </a>
            </div>
            
            <div class="d-flex gap-4 mt-5 pt-3 border-top border-light border-opacity-25 transition-up" style="animation-delay: 0.4s;">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white bg-opacity-25 p-2 rounded text-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-truck fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6 lh-1">Kurir Antar</div>
                        <small class="opacity-75" style="font-size: 0.75rem;">Ke Alamat Anda</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white bg-opacity-25 p-2 rounded text-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-qr-code-scan fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6 lh-1">QRIS Cashless</div>
                        <small class="opacity-75" style="font-size: 0.75rem;">Bayar Mudah</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Background Icon -->
        <i class="bi bi-shop position-absolute" style="font-size: 15rem; bottom: -20px; right: -20px; transform: rotate(-10deg); color: rgba(255, 255, 255, 0.08); z-index: 0; pointer-events: none;"></i>
    </div>

    <!-- Sisi Kanannya (Produk Terlaris) -->
    <div class="col-lg-6 bg-white p-5 p-xl-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold text-dark m-0">Layanan Populer</h3>
                <p class="text-muted small m-0">Pilihan favorit pelanggan kami.</p>
            </div>
            <a href="/layanan" class="text-primary text-decoration-none fw-semibold small">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row g-4">
            @foreach($products->take(4) as $p)
            <div class="col-sm-6">
                <div class="card h-100 border rounded-4 overflow-hidden hover-up bg-light bg-opacity-50">
                    <img src="{{ $p->image ? asset('images/' . rawurlencode($p->image)) : asset('images/logo_sky.png') }}" 
                         class="card-img-top border-bottom" style="height: 140px; object-fit: cover;">
                    <div class="card-body p-3 text-center">
                        <h6 class="fw-bold mb-1 text-truncate text-dark">{{ $p->name }}</h6>
                        <p class="text-muted small fw-semibold mb-2">Mulai Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                        <a href="/layanan" class="btn btn-sm btn-outline-primary rounded-pill px-3 w-100">
                            Pesan <i class="bi bi-cart me-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Cara Kerja Section -->
<div class="py-5 mb-4 text-center">
    <div class="text-muted fw-bold mb-2 text-uppercase tracking-wider" style="letter-spacing: 2px;">Mudah & Praktis</div>
    <h2 class="fw-bolder mb-5">Hanya 3 Langkah untuk Mencetak</h2>
    
    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card border-0 bg-transparent">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-file-earmark-check fs-1"></i>
                    </div>
                    <h5 class="fw-bold">1. Upload & Pesan</h5>
                    <p class="text-muted small">Pilih layanan yang Anda mau, lengkapi detail pesanan, dan upload file dokumen/foto Anda.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-transparent">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-qr-code-scan fs-1"></i>
                    </div>
                    <h5 class="fw-bold">2. Bayar Mudah</h5>
                    <p class="text-muted small">Lakukan pembayaran secara non-tunai melalui QRIS (GoPay, OVO, Dana, M-Banking).</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-transparent">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-truck fs-1"></i>
                    </div>
                    <h5 class="fw-bold">3. Kurir Antar</h5>
                    <p class="text-muted small">Pesanan langsung diproses dan dikirim ke alamat rumah atau kos Anda tanpa perlu antre di toko.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling khusus Home */
    .transition-up {
        animation: slideUp 0.8s ease-out backwards;
    }

    .hover-scale { transition: transform 0.3s ease; }
    .hover-scale:hover {
        transform: scale(1.05);
    }

    .hover-up { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-up:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 25px rgba(0,0,0,0.1) !important;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection