@extends('layouts.app')

@section('content')
<div class="d-flex flex-column" style="height: calc(100vh - 100px); overflow: hidden;">
    
    <div class="text-center py-4 bg-white shadow-sm mb-3">
        <h2 class="fw-bold m-0">Layanan Percetakan</h2>
        @if(session('success'))
            <div class="alert alert-success mt-2 mb-0 py-2 mx-auto" style="max-width: 500px;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="flex-grow-1 overflow-auto custom-scroll px-3">
        <div class="row g-4 pb-5"> {{-- Tambahkan padding bawah agar card tidak terpotong --}}
            @foreach($products as $p)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                    @if($p->image)
                        <img src="{{ asset('storage/' . $p->image) }}" class="card-img-top" alt="{{ $p->name }}" style="height: 220px; object-fit: cover;">
                    @else
                        <div class="bg-light text-center py-5" style="height: 220px;">
                            <i class="text-muted">Tidak ada foto</i>
                        </div>
                    @endif

                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <h5 class="fw-bold text-primary mb-1">{{ $p->name }}</h5>
                        <p class="text-muted small mb-3">Mulai dari Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                        <button class="btn btn-primary w-100 shadow-sm rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#orderModal{{ $p->id }}">
                            Pesan Sekarang
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="orderModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Pesan {{ $p->name }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body p-4">
                                <input type="hidden" name="product_id" value="{{ $p->id }}">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Anda</label>
                                    <input type="text" name="customer_name" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jumlah Pesanan</label>
                                    <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">WhatsApp</label>
                                    <input type="text" name="customer_whatsapp" class="form-control" placeholder="08..." required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Upload File / Foto yang Ingin Dicetak</label>
                                    <input type="file" name="document_file" class="form-control" required>
                                    <small class="text-muted d-block mt-1">Format: PDF, DOCX, JPG, PNG (Maks. 10MB)</small>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary px-4 shadow">Kirim Pesanan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    /* Mencegah scroll di body utama agar tetap satu layar */
    body {
        overflow: hidden;
    }

    /* Kustomisasi scrollbar agar lebih rapi */
    .custom-scroll::-webkit-scrollbar {
        width: 8px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: #0d6efd;
        border-radius: 10px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #0b5ed7;
    }
</style>
@endsection