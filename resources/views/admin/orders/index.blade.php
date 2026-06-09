@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="fw-bold m-0 text-primary">Daftar Pesanan Masuk</h5>
        <div class="d-flex flex-wrap gap-2">
            <form action="{{ route('orders.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <label for="sort" class="text-muted small fw-semibold">Sortir:</label>
                <select name="sort" id="sort" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()" style="width: auto;">
                    <option value="waktu_baru" {{ ($sort ?? '') == 'waktu_baru' ? 'selected' : '' }}>Waktu (Terbaru)</option>
                    <option value="waktu_lama" {{ ($sort ?? '') == 'waktu_lama' ? 'selected' : '' }}>Waktu (Terlama)</option>
                    <option value="produk" {{ ($sort ?? '') == 'produk' ? 'selected' : '' }}>Berdasarkan Produk</option>
                    <option value="kustom_nama" {{ ($sort ?? '') == 'kustom_nama' ? 'selected' : '' }}>Nama Pelanggan</option>
                </select>
            </form>
            <a href="{{ route('transactions.history') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                <i class="bi bi-clock-history"></i> Lihat Riwayat Transaksi
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Waktu Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Detail Pesanan</th>
                        <th>Total Harga</th>
                        <th>Pengiriman & Catatan</th>
                        <th>Bukti Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $checkoutId => $group)
                        @php
                            $firstOrder = $group->first();
                            $totalHarga = $group->sum(fn($o) => $o->totalPrice());
                            $trxCode = str_starts_with($checkoutId, 'LEGACY-') ? $checkoutId : 'TRX-' . strtoupper(substr($checkoutId, 0, 8));
                            $isLegacy = str_starts_with($checkoutId, 'LEGACY-');
                        @endphp
                        <tr>
                            <td>
                                <div>{{ $firstOrder->created_at->format('d M Y H:i') }}</div>
                                <span class="badge bg-light text-dark border">{{ $trxCode }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $firstOrder->customer_name }}</div>
                                <a href="https://wa.me/{{ $firstOrder->customer_whatsapp }}" target="_blank" class="text-decoration-none text-success small">
                                    <i class="bi bi-whatsapp"></i> {{ $firstOrder->customer_whatsapp }}
                                </a>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0 small">
                                    @foreach($group as $o)
                                        <li class="mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                            <div class="fw-semibold text-primary">{{ $o->quantity }}x {{ $o->product->name }}</div>
                                            @if($o->optionsSummary())
                                                <div class="text-muted"><i class="bi bi-gear"></i> {{ $o->optionsSummary() }}</div>
                                            @endif
                                            @if($o->note)
                                                <div class="text-muted"><i class="bi bi-chat-text"></i> Catatan: {{ $o->note }}</div>
                                            @endif

                                            @if(!empty($o->options['photostrip_template']))
                                                <div class="mt-2 bg-light p-2 rounded small">
                                                    <span class="d-block fw-semibold mb-1"><i class="bi bi-palette"></i> Template Terpilih:</span>
                                                    <a href="javascript:void(0)" onclick="showPreview('{{ asset('templates/' . $o->options['photostrip_template']) }}')">
                                                        <img src="{{ asset('templates/' . $o->options['photostrip_template']) }}" class="rounded shadow-sm border turn-cursor" style="height: 60px; object-fit: contain;">
                                                    </a>
                                                    <a href="{{ asset('templates/' . $o->options['photostrip_template']) }}" download="Template_{{ $o->options['photostrip_template'] }}" class="btn btn-sm btn-outline-primary py-0 px-2 mt-1 d-block" style="width: fit-content; font-size: 0.70rem;">
                                                        <i class="bi bi-download"></i> Unduh Template
                                                    </a>
                                                </div>
                                            @endif

                                            @if(!empty($o->options['keychain_template']))
                                                <div class="mt-2 bg-light p-2 rounded small">
                                                    <span class="d-block fw-semibold mb-1"><i class="bi bi-palette"></i> Template Gantungan Kunci:</span>
                                                    <a href="javascript:void(0)" onclick="showPreview('{{ asset('templates/keychain/' . $o->options['keychain_template']) }}')">
                                                        <img src="{{ asset('templates/keychain/' . $o->options['keychain_template']) }}" class="rounded shadow-sm border turn-cursor" style="height: 60px; object-fit: contain;">
                                                    </a>
                                                    <a href="{{ asset('templates/keychain/' . $o->options['keychain_template']) }}" download="Template_{{ $o->options['keychain_template'] }}" class="btn btn-sm btn-outline-primary py-0 px-2 mt-1 d-block" style="width: fit-content; font-size: 0.70rem;">
                                                        <i class="bi bi-download"></i> Unduh Template
                                                    </a>
                                                </div>
                                            @endif

                                            @if(!empty($o->options['uploaded_photos']))
                                                <div class="mt-2 d-flex flex-wrap gap-2">
                                                    @foreach($o->options['uploaded_photos'] as $idx => $photoPath)
                                                        @php
                                                            $pUrl = str_starts_with($photoPath, 'uploads/') ? asset($photoPath) : asset('storage/' . $photoPath);
                                                        @endphp
                                                        <div class="position-relative d-inline-block text-center">
                                                            <a href="javascript:void(0)" onclick="showPreview('{{ $pUrl }}')">
                                                                <img src="{{ $pUrl }}" class="rounded border shadow-sm turn-cursor" style="width: 50px; height: 50px; object-fit: cover;" title="Foto {{ $idx + 1 }}">
                                                            </a>
                                                            <a href="{{ $pUrl }}" download="Foto_{{ $idx + 1 }}.png" class="btn btn-sm btn-primary py-0 px-1 d-block w-100 mt-1" style="font-size: 0.6rem;">
                                                                ⬇ DL
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($o->document_file)
                                                @php
                                                    $documentUrl = str_starts_with($o->document_file, 'uploads/')
                                                        ? asset($o->document_file)
                                                        : asset('storage/' . $o->document_file);
                                                @endphp
                                                <div class="mt-2 d-flex gap-2">
                                                    <a href="{{ $documentUrl }}" target="_blank" class="btn btn-sm btn-outline-secondary py-0" style="font-size: 0.75rem;" title="Buka di Tab Baru">
                                                        <i class="bi bi-box-arrow-up-right"></i> Buka
                                                    </a>
                                                    <a href="{{ $documentUrl }}" download class="btn btn-sm btn-outline-primary py-0" style="font-size: 0.75rem;" title="Download Dokumen">
                                                        <i class="bi bi-download"></i> Unduh
                                                    </a>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="fw-bold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                            <td>
                                @if($firstOrder->delivery_method === 'diantar')
                                    <div class="fw-semibold text-primary"><i class="bi bi-truck"></i> Diantar</div>
                                    <div class="text-muted small">{{ $firstOrder->delivery_address }}</div>
                                @elseif($firstOrder->delivery_method === 'diambil')
                                    <span class="badge bg-secondary rounded-pill"><i class="bi bi-shop"></i> Diambil</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                                
                                @if($firstOrder->additional_note)
                                    <div class="mt-2 small">
                                        <span class="fw-semibold d-block">Catatan Tambahan:</span>
                                        <span class="text-muted">{{ $firstOrder->additional_note }}</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($firstOrder->payment_proof)
                                    @php
                                        $paymentUrl = str_starts_with($firstOrder->payment_proof, 'uploads/') 
                                            ? asset($firstOrder->payment_proof) 
                                            : asset('storage/' . $firstOrder->payment_proof);
                                    @endphp
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <a href="javascript:void(0)" onclick="showPreview('{{ $paymentUrl }}')" title="Lihat Bukti Bayar">
                                            <img src="{{ $paymentUrl }}" class="rounded shadow-sm turn-cursor" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #fff;">
                                        </a>
                                        <a href="{{ $paymentUrl }}" download="Bukti_Bayar_{{ $firstOrder->customer_name }}_{{ substr($checkoutId, 0, 5) }}.png" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.70rem;">
                                            <i class="bi bi-download"></i> Unduh
                                        </a>
                                        <form action="{{ route('orders.togglePaymentValidation', $firstOrder->id) }}" method="POST" class="mt-2 w-100">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $firstOrder->payment_validated ? 'btn-success' : 'btn-outline-success' }} rounded-pill w-100 py-0" style="font-size: 0.70rem;">
                                                <i class="bi {{ $firstOrder->payment_validated ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                                                {{ $firstOrder->payment_validated ? 'Valid' : 'Validasi' }}
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">Belum Upload</span>
                                @endif
                            </td>
                            <td>
                                @if(!$isLegacy && $firstOrder->status === 'pending')
                                    <form action="{{ route('orders.updateStatusByCheckout', $firstOrder->checkout_id) }}" method="POST" onsubmit="return confirm('Selesaikan seluruh pesanan dalam transaksi ini?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                            Selesaikan Transaksi
                                        </button>
                                    </form>
                                @else
                                    @foreach($group as $o)
                                        @if($o->status === 'pending')
                                            <form action="{{ route('orders.updateStatus', $o->id) }}" method="POST" class="mb-1">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-pill w-100" style="font-size: 0.75rem;">
                                                    Selesai ({{ $o->product->name }})
                                                </button>
                                            </form>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 text-light"></i><br>
                            Belum ada pesanan yang masuk.
                        </td>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; opacity: 1; padding: 0.5rem;"></button>
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