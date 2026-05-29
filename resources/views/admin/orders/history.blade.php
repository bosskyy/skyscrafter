@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Pesanan Masuk
    </a>
    {{-- Tombol Download Laporan --}}
    <a href="{{ route('transactions.export') }}" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
        <i class="bi bi-download"></i> Download Riwayat (CSV)
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold m-0 text-success">Riwayat Transaksi (Selesai)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal Selesai</th>
                        <th>Nama Pelanggan</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>WhatsApp</th>
                        <th>Total Pendapatan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($completedOrders as $co)
                    <tr>
                        <td>{{ $co->updated_at->format('d M Y H:i') }}</td>
                        <td class="fw-bold">{{ $co->customer_name }}</td>
                        <td>{{ $co->product->name }}</td>
                        <td>{{ $co->quantity }} pcs</td>
                        <td>
                            <a href="https://wa.me/{{ $co->customer_whatsapp }}" target="_blank" class="text-decoration-none text-success fw-semibold">
                                {{ $co->customer_whatsapp }}
                            </a>
                        </td>
                        <td class="fw-bold text-dark">
                            Rp {{ number_format($co->quantity * $co->product->price, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge bg-success rounded-pill px-3 py-2">Selesai</span>
                        </td>
                    </tr>
                    @endforeach

                    @if($completedOrders->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat transaksi yang selesai.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection