<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skycrafter - Percetakan Online Kupang</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar { 
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%); 
        }
        .navbar-brand img {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background: white;
            padding: 4px;
        }
        .nav-link { font-weight: 500; letter-spacing: 0.3px; }
        .nav-link:hover { opacity: 0.8; }
        
        .footer { 
            background-color: #212529; 
            color: #adb5bd; 
            margin-top: auto; 
        }
        .footer a { color: #adb5bd; text-decoration: none; transition: color 0.3s; }
        .footer a:hover { color: #fff; }
        .custom-shadow { box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <!-- Promo Banner -->
    <div class="bg-warning text-dark text-center py-2 px-3 fw-bold tracking-tight shadow-sm" style="font-size: 0.85rem; z-index: 1040; position: relative;">
        <i class="bi bi-megaphone-fill text-danger me-2 fs-6 align-middle"></i>
        PROMO SPESIAL: Gratis biaya pengantaran & penjemputan berkas khusus area Kota Kupang untuk minimum transaksi Rp 200.000! 🎉
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
            <img src="{{ asset('images/logo_sky.png') }}" alt="Logo SKY" width="45" class="me-3">
            <span class="d-none d-sm-inline">SKYCRAFTER KUPANG</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto align-items-center">
                <a class="nav-link" href="/"><i class="bi bi-house-door me-1"></i> Home</a>
                <a class="nav-link" href="/layanan"><i class="bi bi-grid me-1"></i> Layanan</a>
                <a class="nav-link" href="{{ route('cart.index') }}"><i class="bi bi-cart3 me-1"></i> Keranjang</a>
                
                @auth
                    <a class="nav-link" href="/admin/products"><i class="bi bi-box-seam me-1"></i> Produk</a>
                    <a class="nav-link fw-bold px-3 mx-lg-2 rounded bg-danger text-white shadow-sm" href="/admin/orders">
                        <i class="bi bi-clipboard2-check me-1"></i> Daftar Pesanan
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="d-inline ms-lg-3">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm mt-2 mt-lg-0"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
                    </form>
                @else
                    <a class="nav-link btn btn-outline-light btn-sm mt-2 mt-lg-0 ms-2 px-3 fw-semibold" href="/login"><i class="bi bi-person-lock me-1"></i> Login Admin</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(isset($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </div>

    <footer class="footer pt-5 pb-4 mt-auto">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-md-5">
                    <a class="navbar-brand fw-bold d-flex align-items-center mb-3 text-white text-decoration-none" href="/">
                        <img src="{{ asset('images/logo_sky.png') }}" alt="Logo SKY" width="40" class="me-2 rounded bg-white p-1">
                        <span>SKYCRAFTER</span>
                    </a>
                    <p class="small text-muted pe-md-4 mb-4" style="line-height: 1.6;">
                        Pusat percetakan online pertama di Kupang. Melayani cetak dokumen, pas foto, jilid, dan perlengkapan acara Anda dengan cepat. Kini mendukung layanan antar langsung ke rumah.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-muted text-decoration-none" title="Facebook"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-muted text-decoration-none" title="Instagram"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="https://wa.me/6285738278755" target="_blank" class="text-muted text-decoration-none" title="WhatsApp"><i class="bi bi-whatsapp fs-5"></i></a>
                    </div>
                </div>
                <div class="col-md-3 offset-md-1">
                    <h6 class="text-white fw-bold mb-3 text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Layanan Kami</h6>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2"><a href="/layanan"><i class="bi bi-chevron-right text-primary small me-1"></i> Print &amp; Fotocopy</a></li>
                        <li class="mb-2"><a href="/layanan"><i class="bi bi-chevron-right text-primary small me-1"></i> Cetak Pas Foto</a></li>
                        <li class="mb-2"><a href="/layanan"><i class="bi bi-chevron-right text-primary small me-1"></i> Jilid &amp; Laminating</a></li>
                        <li class="mb-2"><a href="/layanan"><i class="bi bi-chevron-right text-primary small me-1"></i> Cetak Undangan &amp; Polaroid</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white fw-bold mb-3 text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Bantuan & Info</h6>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2"><a href="/layanan"><i class="bi bi-cart3 me-2"></i> Pesan Online Sekarang</a></li>
                        <li class="mb-2"><a href="https://wa.me/6285738278755" target="_blank"><i class="bi bi-whatsapp me-2"></i> Hubungi Admin (CS)</a></li>
                        <li class="mb-2 mt-4"><a href="/login" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="bi bi-person-lock me-1"></i> Login Karyawan</a></li>
                    </ul>
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3 border-top border-secondary small text-muted">
                <div>
                    &copy; {{ date('Y') }} <strong>Skycrafter Kupang</strong>. Hak Cipta Dilindungi.
                </div>
                <div class="mt-2 mt-md-0">
                    <i class="bi bi-lightning-charge-fill text-warning"></i> Cetak Nyaman, Nggak Pake Antre.
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>