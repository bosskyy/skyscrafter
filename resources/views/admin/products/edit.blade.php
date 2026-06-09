@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">Edit Produk: {{ $product->name }}</div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Produk</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto Produk Saat Ini</label>
                        <div class="mb-2">
                            @if($product->image)
                                <img src="{{ asset('images/' . rawurlencode($product->image)) }}" width="150" class="img-thumbnail shadow-sm">
                            @else
                                <span class="text-muted italic">Tidak ada foto sebelumnya</span>
                            @endif
                        </div>
                        <label class="form-label fw-bold">Ganti Foto Baru (Opsional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-secondary">Biarkan kosong jika tidak ingin mengubah foto.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" required>{{ $product->description }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-warning px-4 shadow-sm">Update Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection