@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold m-0 text-primary">Daftar Pesanan Masuk</h5>
        {{-- Tombol Navigasi ke Riwayat --}}
        <a href="{{ route('transactions.history') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
            <i class="bi bi-clock-history"></i> Lihat Riwayat Transaksi
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>WhatsApp</th>
                        <th>File Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $o)
                    <tr>
                        <td>{{ $o->created_at->format('d M Y H:i') }}</td>
                        <td class="fw-bold">{{ $o->customer_name }}</td>
                        <td>{{ $o->product->name }}</td>
                        <td>{{ $o->quantity }}</td>
                        <td>
                            <a href="https://wa.me/{{ $o->customer_whatsapp }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                <i class="bi bi-whatsapp"></i> Hubungi WA
                            </a>
                        </td>
                        <td>
                            {{-- SUDAH DIPERBARUI: Sekarang membaca kolom document_file hasil perubahan database Anda --}}
                            @if($o->document_file)
                                @php
                                    $extension = pathinfo($o->document_file, PATHINFO_EXTENSION);
                                @endphp

                                {{-- Jika berkas berupa gambar/foto --}}
                                @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ asset('storage/' . $o->document_file) }}" class="rounded border" style="width: 45px; height: 45px; object-fit: cover;">
                                        <a href="{{ asset('storage/' . $o->document_file) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-2">
                                            Lihat Foto
                                        </a>
                                    </div>
                                {{-- Jika berkas berupa dokumen cetak (PDF/Word/dll) --}}
                                @else
                                    <a href="{{ asset('storage/' . $o->document_file) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3">
                                        Download Dokumen
                                    </a>
                                @endif
                            @else
                                <span class="text-muted small">Tidak ada file</span>
                            @endif
                        </td>
                        <td>
                            @if($o->status == 'pending')
                                <form action="{{ route('orders.updateStatus', $o->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Tandai Selesai</button>
                                </form>
                            @else
                                <span class="badge bg-success rounded-pill px-3 py-2">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    @if($orders->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada pesanan yang masuk.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection