@extends('layouts.app')

@section('page-title', 'Tambah Pergerakan Stok')

@section('content')

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

        <form action="{{ route('stock-movements.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Produk</label>
                <select name="product_id" class="form-select" required>
                    <option value="">Pilih produk</option>

                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            @selected(old('product_id') == $product->id)>
                            {{ $product->sku }} - {{ $product->name }}
                            (Stok: {{ $product->stock }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Pergerakan</label>
                <select name="movement_type" class="form-select" required>
                    <option value="">Pilih jenis pergerakan</option>
                    <option value="incoming" @selected(old('movement_type') == 'incoming')>
                        Stok Masuk
                    </option>
                    <option value="outgoing" @selected(old('movement_type') == 'outgoing')>
                        Stok Keluar
                    </option>
                    <option value="adjustment" @selected(old('movement_type') == 'adjustment')>
                        Penyesuaian Stok
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah Perubahan</label>
                <input type="number"
                       name="quantity"
                       class="form-control"
                       value="{{ old('quantity') }}"
                       required
                       step="1">

                <small class="text-muted">
                    Stok masuk dan keluar menggunakan angka positif.
                    Penyesuaian menggunakan angka positif atau negatif.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan / Alasan</label>
                <textarea name="notes"
                          class="form-control"
                          rows="3"
                          placeholder="Contoh: Pembelian barang dari pemasok">{{ old('notes') }}</textarea>

                <small class="text-muted">
                    Wajib diisi untuk stok keluar dan penyesuaian.
                </small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Simpan Pergerakan Stok
                </button>

                <a href="{{ route('stock-movements.index') }}"
                   class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>

    </div>
</div>

@endsection