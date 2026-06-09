@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="fw-bold text-primary mb-0">
                <i class="bi bi-image"></i> Manajemen Template
            </h2>
            <p class="text-muted small mb-0">Kelola template Photostrip dan Gantungan Kunci</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Photostrip Templates Section -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-images"></i> Template Photostrip
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Upload Form -->
                    <form action="{{ route('templates.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <input type="hidden" name="template_type" value="photostrip">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload Template Photostrip</label>
                            <input type="file" name="template_file" class="form-control" accept="image/*" required>
                            <small class="text-muted d-block mt-1">Format: JPEG, PNG, WebP (Maks. 5MB)</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">
                            <i class="bi bi-cloud-upload"></i> Upload Template
                        </button>
                    </form>

                    <hr>

                    <!-- Templates List -->
                    <div class="mt-3">
                        <h6 class="fw-bold mb-3">Template yang Ada ({{ count($photostripTemplates) }})</h6>
                        @if(count($photostripTemplates) > 0)
                            <div class="row g-3">
                                @foreach($photostripTemplates as $template)
                                    <div class="col-6 col-md-4">
                                        <div class="card h-100 shadow-sm border">
                                            <div class="card-img-top position-relative overflow-hidden" style="height: 120px; background: #f8f9fa;">
                                                <img src="{{ $template['url'] }}" class="w-100 h-100" style="object-fit: contain; padding: 5px;">
                                            </div>
                                            <div class="card-body p-2">
                                                <p class="small text-truncate mb-1" title="{{ $template['filename'] }}">
                                                    {{ basename($template['filename'], '.png') }}
                                                </p>
                                                <form action="{{ route('templates.delete') }}" method="POST" onsubmit="return confirm('Hapus template ini?');">
                                                    @csrf
                                                    <input type="hidden" name="template_type" value="photostrip">
                                                    <input type="hidden" name="filename" value="{{ $template['filename'] }}">
                                                    <button type="submit" class="btn btn-sm btn-danger w-100 py-0" style="font-size: 0.75rem;">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info py-2">
                                <i class="bi bi-info-circle"></i> Belum ada template Photostrip. Silakan upload template terlebih dahulu.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Keychain Templates Section -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-success text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-key"></i> Template Gantungan Kunci
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Upload Form -->
                    <form action="{{ route('templates.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <input type="hidden" name="template_type" value="keychain">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload Template Gantungan Kunci</label>
                            <input type="file" name="template_file" class="form-control" accept="image/*" required>
                            <small class="text-muted d-block mt-1">Format: JPEG, PNG, WebP (Maks. 5MB)</small>
                        </div>
                        <button type="submit" class="btn btn-success w-100 rounded-pill">
                            <i class="bi bi-cloud-upload"></i> Upload Template
                        </button>
                    </form>

                    <hr>

                    <!-- Templates List -->
                    <div class="mt-3">
                        <h6 class="fw-bold mb-3">Template yang Ada ({{ count($keychainTemplates) }})</h6>
                        @if(count($keychainTemplates) > 0)
                            <div class="row g-3">
                                @foreach($keychainTemplates as $template)
                                    <div class="col-6 col-md-4">
                                        <div class="card h-100 shadow-sm border">
                                            <div class="card-img-top position-relative overflow-hidden" style="height: 120px; background: #f8f9fa;">
                                                <img src="{{ $template['url'] }}" class="w-100 h-100" style="object-fit: contain; padding: 5px;">
                                            </div>
                                            <div class="card-body p-2">
                                                <p class="small text-truncate mb-1" title="{{ $template['filename'] }}">
                                                    {{ basename($template['filename'], '.png') }}
                                                </p>
                                                <form action="{{ route('templates.delete') }}" method="POST" onsubmit="return confirm('Hapus template ini?');">
                                                    @csrf
                                                    <input type="hidden" name="template_type" value="keychain">
                                                    <input type="hidden" name="filename" value="{{ $template['filename'] }}">
                                                    <button type="submit" class="btn btn-sm btn-danger w-100 py-0" style="font-size: 0.75rem;">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info py-2">
                                <i class="bi bi-info-circle"></i> Belum ada template Gantungan Kunci. Silakan upload template terlebih dahulu.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
