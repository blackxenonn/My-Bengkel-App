
@extends('layouts.app')

@section('page-title', 'Data Produk')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
    <p class="text-muted mb-0">
        Kelola seluruh data produk, harga, kategori, dan informasi stok bengkel.
    </p>

    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Produk
    </a>
</div>

{{-- Notifikasi --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Tabel Produk --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">

        {{-- FORM PENCARIAN --}}
        <form action="{{ route('products.index') }}"
            method="GET"
            data-live-search
            class="mb-3">

            <div class="input-group">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari nama produk, SKU, atau kategori..."
                    value="{{ $search }}"
                    data-live-search-input
                    autocomplete="off"
                >

                <button type="submit" class="btn btn-primary">
                    Cari
                </button>

                <a href="{{ route('products.index') }}"
                class="btn btn-secondary">
                    Reset
                </a>
            </div>
        </form>

        {{-- INFORMASI HASIL PENCARIAN --}}
        @if($search !== '')
            <div class="mb-3 text-muted small">
                Menampilkan hasil pencarian untuk:
                <strong>{{ $search }}</strong>
                ({{ $products->total() }} produk ditemukan)
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                {{ $products->firstItem() + $loop->index }}
                            </td>

                            <td>{{ $product->sku }}</td>

                            <td class="fw-semibold">
                                {{ $product->name }}
                            </td>

                            <td>
                                {{ $product->category?->name ?? '-' }}
                            </td>

                            <td>
                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            </td>

                            <td>
                                <span class="badge {{ $product->stock <= $product->minimum_stock ? 'bg-danger' : 'bg-success' }}">
                                    {{ $product->stock }} {{ $product->unit }}
                                </span>
                            </td>

                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('products.edit', $product) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('products.destroy', $product) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">

                                @if($search !== '')
                                    <i class="bi bi-search fs-3 d-block mb-2"></i>
                                    Tidak ada produk yang cocok dengan pencarian "{{ $search }}".
                                @else
                                    Belum ada data produk.
                                @endif

                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-3">
            {{ $products->links() }}
        </div>

    </div>
</div>

@endsection
