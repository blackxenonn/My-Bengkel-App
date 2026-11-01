
@extends('layouts.app')

@section('page-title', 'Laporan')

@section('content')

<div class="mb-4">
    <p class="text-muted mb-0">
        Ringkasan penjualan dan aktivitas operasional bengkel.
    </p>
</div>

{{-- Ringkasan Penjualan --}}
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Total Penjualan</div>
                <h4 class="fw-bold mt-2">Rp 0</h4>
                <small class="text-muted">Belum tersedia</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Jumlah Transaksi</div>
                <h4 class="fw-bold mt-2">0</h4>
                <small class="text-muted">Belum tersedia</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Produk Terjual</div>
                <h4 class="fw-bold mt-2">0</h4>
                <small class="text-muted">Belum tersedia</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Produk Terdaftar</div>
                <h4 class="fw-bold mt-2">-</h4>
                <small class="text-muted">Lihat data inventaris</small>
            </div>
        </div>
    </div>

</div>

{{-- Laporan Penjualan --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Laporan Penjualan</h5>

        <div class="text-center text-muted py-5">
            <h6>Belum ada data laporan penjualan</h6>
            <p class="mb-0">
                Data akan muncul setelah transaksi POS tersimpan.
            </p>
        </div>

    </div>
</div>

{{-- Produk Terlaris --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Produk Terlaris</h5>

        <div class="text-center text-muted py-4">
            Belum ada data produk terjual.
        </div>

    </div>
</div>

{{-- Riwayat Transaksi --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Riwayat Transaksi</h5>

        <div class="text-center text-muted py-4">
            Belum ada transaksi yang tercatat.
        </div>

    </div>
</div>

@endsection
