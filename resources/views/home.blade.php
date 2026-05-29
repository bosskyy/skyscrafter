@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 overflow-hidden" style="height: calc(100vh - 70px);"> {{-- 70px adalah perkiraan tinggi navbar --}}
    <div class="row h-100 g-0">
        
        <!-- SISI KIRI: Hero Section (Teks & Visual Utama) -->
        <div class="col-lg-7 d-flex align-items-center bg-primary text-white p-5 position-relative">
            <div class="py-5">
                <h1 class="display-3 fw-bold mb-3 transition-up">Cetak Cepat & Berkualitas di Kupang</h1>
                <p class="fs-4 mb-4 opacity-75">Skycrafter hadir untuk memenuhi segala kebutuhan percetakan Anda dengan mesin modern dan hasil warna tajam.</p>
                
                <div class="d-flex gap-3 mt-4">
                    <a href="/layanan" class="btn btn-warning btn-lg px-5 fw-bold shadow-lg hover-scale">Lihat Layanan</a>
                    <div class="d-flex align-items-center gap-3 ms-4 d-none d-md-flex">
                        <div class="text-center">
                            <h4 class="fw-bold mb-0">🚀 Kilat</h4>
                            <small class="opacity-75">Bisa Tunggu</small>
                        </div>
                        <div class="vr"></div>
                        <div class="text-center">
                            <h4 class="fw-bold mb-0">💰 Murah</h4>
                            <small class="opacity-75">Harga Mahasiswa</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Ikon Background Halus -->
            <div class="position-absolute end-0 bottom-0 opacity-10 p-4">
                <i class="bi bi-printer" style="font-size: 12rem;"></i>
            </div>
        </div>

        <!-- SISI KANAN: Preview Produk Terlaris (Scrollable Grid Kecil) -->
        <div class="col-lg-5 bg-light p-5 d-flex flex-column justify-content-center">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold m-0">Produk Terlaris</h2>
                <span class="badge bg-primary rounded-pill">Top Picks</span>
            </div>

            <div class="row g-3 overflow-auto custom-scroll" style="max-height: 70vh;">
                @foreach($products->take(4) as $p) {{-- Membatasi 4 produk agar tidak terlalu padat --}}
                <div class="col-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-up">
                        <img src="{{ $p->image ? asset('storage/' . $p->image) : 'https://via.placeholder.com/300' }}" 
                             class="card-img-top" style="height: 120px; object-fit: cover;">
                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-1 text-truncate">{{ $p->name }}</h6>
                            <p class="small text-muted mb-2">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                            <a href="/layanan" class="btn btn-sm btn-outline-primary w-100 py-1">Detail</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<style>
    /* Menghilangkan scrollbar utama agar benar-benar single screen */
    body {
        overflow: hidden;
    }

    .transition-up {
        animation: slideUp 0.8s ease-out;
    }

    .hover-scale:hover {
        transform: scale(1.05);
        transition: 0.3s;
    }

    .hover-up:hover {
        transform: translateY(-5px);
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Styling scrollbar untuk list produk di sebelah kanan */
    .custom-scroll::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: #0d6efd;
        border-radius: 10px;
    }
</style>
@endsection