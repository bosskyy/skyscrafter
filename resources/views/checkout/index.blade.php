@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('cart.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        Kembali ke Keranjang
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

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold m-0 text-primary">Checkout</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Layanan</th>
                                <th>Detail</th>
                                <th width="80">Qty</th>
                                <th width="140">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                @php
                                    $product = $products[$item['product_id']] ?? null;
                                    $unitPrice = (int) ($product->price ?? 0);
                                    $qty = (int) ($item['quantity'] ?? 0);

                                    $copies = (int) data_get($item, 'options.copies', 1);
                                    if ($copies < 1) {
                                        $copies = 1;
                                    }

                                    $subtotal = $unitPrice * $qty * $copies;

                                    $detailParts = [];
                                    $variant = data_get($item, 'options.variant');
                                    if ($variant === 'warna') {
                                        $detailParts[] = 'Warna';
                                    } elseif ($variant === 'hitam_putih') {
                                        $detailParts[] = 'Hitam Putih';
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
                                    <td class="fw-semibold">{{ $product?->name ?? 'Produk tidak ditemukan' }}</td>
                                    <td class="text-muted small">{{ $detailText }}</td>
                                    <td>{{ $qty }}</td>
                                    <td class="fw-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">Total</div>
                    <div class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold m-0">Data Pemesan</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('checkout.process') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Anda</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">WhatsApp</label>
                        <input type="text" name="customer_whatsapp" class="form-control" placeholder="08..." value="{{ old('customer_whatsapp') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pesanan Diantar / Diambil</label>
                        <select name="delivery_method" id="delivery_method" class="form-control" required>
                            <option value="diambil" {{ old('delivery_method') === 'diambil' ? 'selected' : '' }}>Diambil</option>
                            <option value="diantar" {{ old('delivery_method') === 'diantar' ? 'selected' : '' }}>Diantar</option>
                        </select>
                    </div>

                    <div class="mb-3" id="address_wrapper" style="display:none;">
                        <label class="form-label fw-bold">Alamat Pengantaran</label>
                        <textarea name="delivery_address" class="form-control" rows="3" placeholder="Tulis alamat lengkap...">{{ old('delivery_address') }}</textarea>
                        <small class="text-muted">Wajib diisi jika memilih diantar.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan Tambahan</label>
                        <textarea name="additional_note" class="form-control" rows="3" placeholder="Opsional...">{{ old('additional_note') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 shadow-sm rounded-pill fw-bold">
                        Lanjut ke Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const deliveryMethod = document.getElementById('delivery_method');
        const addressWrapper = document.getElementById('address_wrapper');

        function toggleAddress() {
            addressWrapper.style.display = deliveryMethod.value === 'diantar' ? 'block' : 'none';
        }

        deliveryMethod.addEventListener('change', toggleAddress);
        toggleAddress();
    })();
</script>
@endsection
