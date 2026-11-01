@extends('layouts.app')

@section('page-title', 'Edit Produk')

@section('content')

<div class="mb-4">
    <h4>Edit Produk</h4>
    <p class="text-muted">Perbarui informasi produk.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">SKU</label>
                    <input type="text"
                           name="sku"
                           class="form-control"
                           value="{{ old('sku', $product->sku) }}"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $product->name) }}"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select">
                        <option value="">Tanpa kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected(old('category_id', $product->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Satuan</label>
                    <input type="text"
                           name="unit"
                           class="form-control"
                           value="{{ old('unit', $product->unit) }}"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Modal</label>
                    <input type="number"
                           name="cost_price"
                           class="form-control"
                           value="{{ old('cost_price', $product->cost_price) }}"
                           min="0"
                           step="0.01"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Jual</label>
                    <input type="number"
                           name="selling_price"
                           class="form-control"
                           value="{{ old('selling_price', $product->selling_price) }}"
                           min="0"
                           step="0.01"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Stok Saat Ini</label>
                    <input type="number"
                           class="form-control"
                           value="{{ $product->stock }}"
                           disabled>
                    <small class="text-muted">
                        Stok hanya dapat diubah melalui fitur pergerakan stok.
                    </small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Stok Minimum</label>
                    <input type="number"
                           name="minimum_stock"
                           class="form-control"
                           value="{{ old('minimum_stock', $product->minimum_stock) }}"
                           min="0"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select" required>
                        <option value="1" @selected(old('is_active', $product->is_active ? '1' : '0') == '1')>Aktif</option>
                        <option value="0" @selected(old('is_active', $product->is_active ? '1' : '0') == '0')>Nonaktif</option>
                    </select>
                </div>

            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </form>
    </div>
</div>

@endsection