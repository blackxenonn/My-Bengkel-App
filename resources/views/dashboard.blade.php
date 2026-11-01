
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
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted mb-1">Total Penjualan Hari Ini</p>
                <h4>Rp 0</h4>
                <small class="text-muted">Akan terhubung ke database</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted mb-1">Jumlah Transaksi</p>
                <h4>0</h4>
                <small class="text-muted">Hari ini</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted mb-1">Jumlah Produk</p>
                <h4>0</h4>
                <small class="text-muted">Data produk</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted mb-1">Stok Menipis</p>
                <h4>0</h4>
                <small class="text-muted">Perlu diperiksa</small>
            </div>
        </div>
    </div>

</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <h5>Aktivitas Sistem</h5>
        <p class="text-muted mb-0">
            Ringkasan aktivitas dan transaksi akan ditampilkan di sini.
        </p>
    </div>
</div>

@endsection
