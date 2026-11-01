
@extends('layouts.app')

@section('title', 'Dashboard - Bengkel Management')

@section('page-title', 'Dashboard')


@section('content')

<div class="alert alert-primary">
    Selamat datang, <strong>{{ Auth::user()->name }}</strong>!
    Anda login sebagai
    <strong>{{ Auth::user()->role->name }}</strong>.
</div>

<div class="row g-3">

    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Penjualan Hari Ini</p>
                <h4>
                    Rp {{ number_format($totalSalesToday, 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Transaksi Hari Ini</p>
                <h4>{{ $totalTransactionsToday }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Jumlah Produk Aktif</p>
                <h4>{{ $totalProducts }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Stok Menipis</p>
                <h4>{{ $lowStockProducts }}</h4>
                <small class="text-muted">
                    Produk mencapai batas stok minimum
                </small>
            </div>
        </div>
    </div>

</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <h5>Aktivitas Sistem</h5>
        <p class="text-muted mb-0">
            Riwayat transaksi dan aktivitas stok akan ditampilkan
            setelah modul transaksi dan inventaris selesai dibuat.
        </p>
    </div>
</div>

@endsection

