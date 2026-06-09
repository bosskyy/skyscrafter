@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold m-0 text-primary">Keranjang</h5>
        <a href="/layanan" class="btn btn-sm btn-outline-primary rounded-pill px-3">Tambah Layanan</a>
    </div>

    <div class="card-body">
        @if(empty($cart))
            <div class="text-center text-muted py-4">
                Keranjang masih kosong.
            </div>
        @else
            <form id="checkout-form" method="POST" action="{{ route('checkout.start') }}">
                @csrf
            </form>

            <div class="alert alert-info">
                Pilih item yang mau di-checkout.
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="40"></th>
                            <th>Layanan</th>
                            <th>Detail</th>
                            <th width="90">Qty</th>
                            <th width="160">Subtotal</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp

                        @foreach($cart as $lineId => $item)
                            @php
                                $product = $products[$item['product_id']] ?? null;
                                $unitPrice = (int) ($product->price ?? 0);
                                
                                $variant = data_get($item, 'options.variant');
                                if ($product && $product->name === 'Polaroid & Photostrip') {
                                    if ($variant === 'Polaroid') {
                                        $unitPrice = 20000;
                                    } elseif ($variant === 'Photostrip') {
                                        $unitPrice = 5000;
                                    }
                                }

                                $qty = (int) ($item['quantity'] ?? 0);

                                $copies = (int) data_get($item, 'options.copies', 1);
                                if ($copies < 1) {
                                    $copies = 1;
                                }

                                $subtotal = $unitPrice * $qty * $copies;
                                $grandTotal += $subtotal;

                                $detailParts = [];
                                if ($variant === 'warna') {
                                    $detailParts[] = 'Warna';
                                } elseif ($variant === 'hitam_putih') {
                                    $detailParts[] = 'Hitam Putih';
                                } elseif (in_array($variant, ['Polaroid', 'Photostrip'])) {
                                    $detailParts[] = 'Tipe: ' . $variant;
                                }

                                $template = data_get($item, 'options.photostrip_template');
                                if ($template) {
                                    $detailParts[] = 'Template: ' . str_replace('.png', '', $template);
                                }

                                $size = data_get($item, 'options.size');
                                if ($size) {
                                    $detailParts[] = 'Ukuran ' . $size;
                                }

                                $copiesOpt = data_get($item, 'options.copies');
                                if ($copiesOpt) {
                                    $detailParts[] = 'Rangkap ' . $copiesOpt;
                                }

                                $bindingColor = data_get($item, 'options.binding_color');
                                if ($bindingColor) {
                                    $detailParts[] = 'Warna Jilid ' . $bindingColor;
                                }

                                if (!empty($item['note'])) {
                                    $detailParts[] = 'Catatan: ' . $item['note'];
                                }

                                $detailText = implode(', ', $detailParts) ?: '-';
                            @endphp

                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input" name="line_ids[]" value="{{ $lineId }}" form="checkout-form">
                                </td>
                                <td class="fw-semibold">{{ $product?->name ?? 'Produk tidak ditemukan' }}</td>
                                <td class="text-muted small">{{ $detailText }}</td>
                                <td>{{ $qty }}</td>
                                <td class="fw-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('cart.remove', $lineId) }}" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Total (semua item):
                    <span class="fw-bold text-dark">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-4" form="checkout-form">
                    Checkout
                </button>
            </div>
        @endif
    </div>
</div>
@endsection
