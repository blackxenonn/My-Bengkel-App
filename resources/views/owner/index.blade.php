@extends('layouts.app')

@section('page-title', 'Manajemen Owner')

@section('content')

<div class="mb-4">
    <p class="text-muted">
        Pusat kontrol untuk memantau dan mengelola operasional bengkel.
    </p>
</div>

{{-- Ringkasan Data --}}
<div class="row g-3 mb-4">

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Total Karyawan</div>
                <h3 class="fw-bold mt-2">{{ $totalEmployees }}</h3>
                <a href="{{ route('employees.index') }}"
                   class="small text-decoration-none">
                    Lihat karyawan
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Total Produk</div>
                <h3 class="fw-bold mt-2">{{ $totalProducts }}</h3>
                <a href="{{ route('products.index') }}"
                   class="small text-decoration-none">
                    Lihat produk
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Total Kategori</div>
                <h3 class="fw-bold mt-2">{{ $totalCategories }}</h3>
                <a href="{{ route('categories.index') }}"
                   class="small text-decoration-none">
                    Lihat kategori
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Produk Stok Menipis</div>
                <h3 class="fw-bold mt-2 text-danger">
                    {{ $lowStockProducts }}
                </h3>
                <a href="{{ route('products.index') }}"
                   class="small text-decoration-none">
                    Periksa produk
                </a>
            </div>
        </div>
    </div>

</div>

{{-- Manajemen Data --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Manajemen Data</h5>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-4">
                <a href="{{ route('products.index') }}"
                   class="btn btn-outline-primary w-100 py-3">
                    <strong>Data Produk</strong>
                    <div class="small">Kelola produk bengkel</div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('categories.index') }}"
                   class="btn btn-outline-primary w-100 py-3">
                    <strong>Data Kategori</strong>
                    <div class="small">Kelola kategori produk</div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('employees.index') }}"
                   class="btn btn-outline-primary w-100 py-3">
                    <strong>Data Karyawan</strong>
                    <div class="small">Lihat akun karyawan</div>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- Operasional Bengkel --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Operasional Bengkel</h5>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-4">
                <a href="{{ route('pos') }}"
                   class="btn btn-outline-success w-100 py-3">
                    <strong>POS / Kasir</strong>
                    <div class="small">Halaman transaksi penjualan</div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('stock-movements.index') }}"
                   class="btn btn-outline-success w-100 py-3">
                    <strong>Inventaris</strong>
                    <div class="small">Pantau pergerakan stok</div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('stock-movements.create') }}"
                   class="btn btn-outline-success w-100 py-3">
                    <strong>Tambah Pergerakan Stok</strong>
                    <div class="small">Catat perubahan persediaan</div>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- Laporan --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Laporan dan Aktivitas</h5>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <a href="{{ route('reports.index') }}"
                   class="btn btn-outline-dark w-100 py-3">
                    <strong>Laporan Bisnis</strong>
                    <div class="small">Lihat ringkasan laporan bengkel</div>
                </a>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <div class="text-muted small">
                        Total Pergerakan Stok Tercatat
                    </div>
                    <h4 class="fw-bold mt-2 mb-0">
                        {{ $totalStockMovements }}
                    </h4>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection