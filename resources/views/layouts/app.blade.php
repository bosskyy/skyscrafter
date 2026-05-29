<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Percetakan Modern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #0d6efd; }
        .nav-link:hover { opacity: 0.8; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
            <img src="{{ asset('images/logo_sky.png') }}" alt="Logo SKY" width="50" 
         style="mix-blend-mode: screen;" class="me-2 rounded">
            SKYCRAFTER KUPANG
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto align-items-center">
                <a class="nav-link" href="/">Home</a>
                <a class="nav-link" href="/layanan">Layanan</a>
                
                @auth
                    <a class="nav-link" href="/admin/products">Produk</a>
                    <a class="nav-link fw-bold px-3 mx-lg-2 rounded bg-danger text-white shadow-sm" href="/admin/orders">
                        Daftar Pesanan
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="d-inline ms-lg-3">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm mt-2 mt-lg-0">Logout</button>
                    </form>
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

    <footer class="text-center mt-5 py-4 text-muted border-top">
        &copy; 2026 Percetakan Modern - Profesional & Cepat
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>