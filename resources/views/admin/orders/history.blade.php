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
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="fw-bold m-0 text-success">Riwayat Transaksi (Selesai)</h5>
        <form action="{{ route('transactions.history') }}" method="GET" class="d-flex align-items-center gap-2">
            <label for="sort" class="text-muted small fw-semibold">Sortir:</label>
            <select name="sort" id="sort" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()" style="width: auto;">
                <option value="waktu_baru" {{ ($sort ?? '') == 'waktu_baru' ? 'selected' : '' }}>Waktu (Terbaru)</option>
                <option value="waktu_lama" {{ ($sort ?? '') == 'waktu_lama' ? 'selected' : '' }}>Waktu (Terlama)</option>
                <option value="produk" {{ ($sort ?? '') == 'produk' ? 'selected' : '' }}>Berdasarkan Produk</option>
                <option value="kustom_nama" {{ ($sort ?? '') == 'kustom_nama' ? 'selected' : '' }}>Nama Pelanggan</option>
            </select>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Waktu Selesai</th>
                        <th>Pelanggan</th>
                        <th>Detail Pesanan</th>
                        <th>WhatsApp</th>
                        <th>Total Pendapatan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($completedOrders as $checkoutId => $group)
                        @php
                            $firstOrder = $group->first();
                            $totalHarga = $group->sum(fn($o) => $o->totalPrice());
                            $trxCode = str_starts_with($checkoutId, 'LEGACY-') ? $checkoutId : 'TRX-' . strtoupper(substr($checkoutId, 0, 8));
                        @endphp
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $trxCode }}</span>
                            </td>
                            <td>{{ $firstOrder->updated_at->format('d M Y H:i') }}</td>
                            <td class="fw-bold">{{ $firstOrder->customer_name }}</td>
                            <td>
                                <ul class="list-unstyled mb-0 small">
                                    @foreach($group as $o)
                                        <li>
                                            <span class="fw-semibold text-primary">{{ $o->quantity }}x {{ $o->product->name }}</span>
                                            @if($o->optionsSummary())
                                                <span class="text-muted">({{ $o->optionsSummary() }})</span>
                                            @endif
                                            
                                            @if(!empty($o->options['photostrip_template']))
                                                <div class="mt-2 text-muted fw-semibold">
                                                    <i class="bi bi-palette"></i> Template:
                                                    <a href="{{ asset('templates/' . $o->options['photostrip_template']) }}" download="Template_{{ $o->options['photostrip_template'] }}" class="text-decoration-none">Unduh <i class="bi bi-download"></i></a>
                                                </div>
                                            @endif

                                            @if(!empty($o->options['uploaded_photos']))
                                                <div class="mt-1 d-flex flex-wrap gap-1">
                                                    @foreach($o->options['uploaded_photos'] as $idx => $photoPath)
                                                        @php
                                                            $pUrl = str_starts_with($photoPath, 'uploads/') ? asset($photoPath) : asset('storage/' . $photoPath);
                                                        @endphp
                                                        <a href="{{ $pUrl }}" download="Foto_Histori_{{ $idx + 1 }}.png" class="badge bg-primary text-white text-decoration-none" style="font-size: 0.65rem;">
                                                            File {{ $idx + 1 }} <i class="bi bi-download"></i>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($o->document_file)
                                                @php
                                                    $docUrl = str_starts_with($o->document_file, 'uploads/') 
                                                        ? asset($o->document_file) 
                                                        : asset('storage/' . $o->document_file);
                                                @endphp
                                                <div class="mt-1">
                                                    <a href="{{ $docUrl }}" download class="text-decoration-none" style="font-size: 0.70rem;">
                                                        <i class="bi bi-download"></i> Unduh Dokumen
                                                    </a>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <a href="https://wa.me/{{ $firstOrder->customer_whatsapp }}" target="_blank" class="text-decoration-none text-success fw-semibold small">
                                    <i class="bi bi-whatsapp"></i> {{ $firstOrder->customer_whatsapp }}
                                </a>
                            </td>
                            <td class="fw-bold text-dark">
                                Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                @if($firstOrder->payment_proof)
                                    @php
                                        $payUrl = str_starts_with($firstOrder->payment_proof, 'uploads/') 
                                            ? asset($firstOrder->payment_proof) 
                                            : asset('storage/' . $firstOrder->payment_proof);
                                    @endphp
                                    <div class="mt-2 text-center">
                                        <a href="javascript:void(0)" onclick="showPreview('{{ $payUrl }}')" title="Lihat Bukti Bayar">
                                            <img src="{{ $payUrl }}" class="rounded shadow-sm border mb-1 turn-cursor" style="height: 40px; object-fit: cover; border: 2px solid #fff;">
                                        </a><br>
                                        <a href="{{ $payUrl }}" download="Bukti_{{ $trxCode }}.png" class="text-success text-decoration-none" style="font-size: 0.75rem;">
                                            <i class="bi bi-download"></i> Unduh
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success rounded-pill px-3 py-2">Selesai</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Belum ada riwayat transaksi yang selesai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Preview Gambar -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 0.5rem;"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <img id="previewImageSrc" src="" class="img-fluid rounded shadow" style="max-height: 85vh; width: auto; object-fit: contain; background: white; padding: 5px;">
            </div>
        </div>
    </div>
</div>

<script>
    function showPreview(src) {
        document.getElementById('previewImageSrc').src = src;
        var myModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        myModal.show();
    }
</script>

@endsection