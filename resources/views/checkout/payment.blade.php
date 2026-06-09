@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="/" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        Kembali ke Home
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold m-0 text-primary">Pembayaran (QRIS)</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Total yang harus dibayar</div>
                    <div class="fs-4 fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>

                <div class="text-center">
                    <img src="{{ asset('images/qris-anda.png') }}" alt="QRIS" class="img-fluid border rounded" style="max-height: 360px;">
                    <div class="text-muted small mt-2">Scan QRIS untuk melakukan pembayaran.</div>
                </div>

                <div class="d-grid mt-4">
                    <a href="{{ route('checkout.paymentProofForm', ['checkoutId' => $checkoutId]) }}" class="btn btn-primary rounded-pill fw-bold">
                        Sudah Bayar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold m-0">Ringkasan Pesanan</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach($orders as $o)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold">{{ $o->product->name }}</div>
                                <div class="text-muted small">{{ $o->optionsSummary() }}</div>
                                <div class="text-muted small">Qty: {{ $o->quantity }}</div>
                            </div>
                            <div class="fw-semibold">Rp {{ number_format($o->totalPrice(), 0, ',', '.') }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
