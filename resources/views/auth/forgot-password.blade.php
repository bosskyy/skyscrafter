@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="text-center mb-4 transition-up">
            <img src="{{ asset('images/logo_sky.png') }}" alt="Skycrafter Logo" width="100" class="shadow-lg rounded-circle p-2 bg-white">
            <h3 class="fw-bold mt-3 text-primary">PULIHKAN AKSES</h3>
            <p class="text-muted">Masukkan email untuk mendapatkan link reset password</p>
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-body p-5">
                <div class="mb-4 text-sm text-secondary text-center">
                    {{ __('Lupa kata sandi? Jangan khawatir. Beritahu kami alamat email Anda dan kami akan mengirimkan link pemulihan.') }}
                </div>

                @if (session('status'))
                    <div class="alert alert-success rounded-3 mb-4 shadow-sm" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-floating mb-4">
                        <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror" id="floatingInput" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                        <label for="floatingInput">Alamat Email Terdaftar</label>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-3 fw-bold rounded-3 shadow hover-scale">
                            KIRIM LINK PEMULIHAN
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-link text-decoration-none mt-2 text-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <p class="text-center mt-4 text-muted small">© 2026 Skycrafter Kupang - Professional Printing</p>
    </div>
</div>

<style>
    /* Menggunakan style yang sama dengan halaman login untuk konsistensi */
    .transition-up {
        animation: slideUp 0.8s ease-out;
    }

    .hover-scale:hover {
        transform: scale(1.02);
        transition: 0.3s;
        background-color: #0d47a1;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card {
        animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endsection