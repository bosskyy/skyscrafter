@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('checkout.payment', ['checkoutId' => $checkoutId]) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        Kembali ke Pembayaran
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold m-0 text-primary">Upload Bukti Bayar</h5>
    </div>
    <div class="card-body">
        @if($paymentProof)
            <div class="alert alert-success">
                Bukti pembayaran sudah diupload.
            </div>

            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="{{ asset($paymentProof) }}" alt="Bukti Bayar" class="rounded border" style="width: 90px; height: 90px; object-fit: cover;">
                <a href="{{ asset($paymentProof) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Bukti</a>
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.uploadPaymentProof', ['checkoutId' => $checkoutId]) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Bukti Bayar (JPG/PNG)</label>
                <input type="file" name="payment_proof" class="form-control" accept="image/*" required id="paymentInput">
                <div id="preview-payment" class="mt-3 text-center"></div>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                Upload
            </button>

            @if($paymentProof)
                <a href="/" class="btn btn-success rounded-pill px-4 fw-bold ms-2" onclick="confirmSelesai(event)">
                    Selesai
                </a>
            @endif
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmSelesai(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Terima Kasih!',
            text: 'Status pesanan akan di Informasikan lewat whatsapp.',
            icon: 'success',
            confirmButtonText: 'Kembali ke Beranda',
            confirmButtonColor: '#198754'
        }).then((result) => {
            window.location.href = "/";
        });
    }

    document.getElementById('paymentInput').addEventListener('change', function() {
        const container = document.getElementById('preview-payment');
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e){
                container.innerHTML = `<img src="${e.target.result}" style="max-width: 200px; max-height: 200px; object-fit: contain; border-radius: 8px;" class="border shadow-sm">`;
            }
            reader.readAsDataURL(this.files[0]);
        } else {
            container.innerHTML = '';
        }
    });
</script>
@endsection
