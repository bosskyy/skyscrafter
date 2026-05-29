@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="text-center mb-4 transition-up">
            <img src="{{ asset('images/logo_sky.png') }}" alt="Skycrafter Logo" width="120" class="shadow-lg rounded-circle p-2 bg-white">
            <h3 class="fw-bold mt-3 text-primary">SKYCRAFTER KUPANG</h3>
            <p class="text-muted">Silakan login untuk mengelola percetakan</p>
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-body p-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control rounded-3" id="floatingInput" placeholder="name@example.com" required autofocus>
                        <label for="floatingInput">Alamat Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control rounded-3" id="floatingPassword" placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                        <label class="form-check-label text-muted" for="remember_me">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow hover-scale">
                        MASUK SEKARANG
                    </button>

                    <!-- @if (Route::has('password.request'))
                        <div class="text-center mt-4">
                            <a class="text-decoration-none small text-secondary" href="{{ route('password.request') }}">
                                Lupa kata sandi Anda?
                            </a>
                        </div>
                    @endif -->
                </form>
            </div>
        </div>
        
    </div>
</div>

<style>
    /* Efek Interaktif */
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