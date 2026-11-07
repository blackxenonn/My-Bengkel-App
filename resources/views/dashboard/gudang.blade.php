
@extends('layouts.app')

@section('title', 'Dashboard Gudang - My Bengkel App')

@section('page-title', 'Dashboard Gudang')

@section('content')

<div class="alert alert-primary border-0 shadow-sm">
    Selamat datang, <strong>{{ Auth::user()->name }}</strong>!
    Berikut ringkasan kondisi persediaan dan aktivitas gudang.
</div>

{{-- Ringkasan stok --}}
<div class="row g-3">

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Total Produk Aktif</p>
                <h4 class="fw-bold mb-0">
                    {{ number_format($totalProducts) }}
                </h4>
                <small class="text-muted">Produk yang aktif</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Stok Menipis</p>
                <h4 class="fw-bold text-warning mb-0">
                    {{ number_format($lowStockCount) }}
                </h4>
                <small class="text-muted">Sudah mencapai batas minimum</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Stok Habis</p>
                <h4 class="fw-bold text-danger mb-0">
                    {{ number_format($outOfStockCount) }}
                </h4>
                <small class="text-muted">Produk dengan stok nol</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Barang Masuk Hari Ini</p>
                <h4 class="fw-bold text-success mb-0">
                    {{ number_format($incomingToday) }}
                </h4>
                <small class="text-muted">Total unit barang masuk</small>
            </div>
        </div>
    </div>

</div>

{{-- Ringkasan barang keluar --}}
<div class="row g-3 mt-1">

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Barang Keluar Hari Ini</p>
                    <h4 class="fw-bold text-danger mb-0">
                        {{ number_format($outgoingToday) }} unit
                    </h4>
                    <small class="text-muted">
                        Termasuk barang keluar dan penjualan
                    </small>
                </div>

                <div class="fs-1 text-danger">
                    <i class="bi bi-box-arrow-up"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Akses Riwayat Stok</p>
                    <h5 class="fw-bold mb-2">Pergerakan Stok</h5>
                    <a href="{{ route('stock-movements.index') }}"
                       class="btn btn-outline-primary btn-sm">
                        Lihat Riwayat Stok
                    </a>
                </div>

                <div class="fs-1 text-primary">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Daftar stok menipis dan stok habis --}}
<div class="row g-3 mt-2">

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Produk dengan Stok Menipis</h5>
                    <span class="badge bg-warning text-dark">
                        {{ $lowStockCount }} produk
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Minimum</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($lowStockProducts as $product)
                                <tr>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <div class="small text-muted">
                                            {{ $product->sku }}
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            {{ number_format($product->stock) }}
                                            {{ $product->unit }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ number_format($product->minimum_stock) }}
                                        {{ $product->unit }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="text-center text-muted py-4">
                                        Tidak ada produk dengan stok menipis.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Produk dengan Stok Habis</h5>
                    <span class="badge bg-danger">
                        {{ $outOfStockCount }} produk
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Produk</th>
                                <th>SKU</th>
                                <th>Minimum</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($outOfStockProducts as $product)
                                <tr>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                    </td>

                                    <td>{{ $product->sku }}</td>

                                    <td>
                                        {{ number_format($product->minimum_stock) }}
                                        {{ $product->unit }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="text-center text-muted py-4">
                                        Tidak ada produk yang stoknya habis.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

{{-- Riwayat pergerakan stok --}}
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Riwayat Pergerakan Stok Terbaru</h5>

            <a href="{{ route('stock-movements.index') }}"
               class="btn btn-outline-primary btn-sm">
                Lihat Semua
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Jenis Pergerakan</th>
                        <th>Jumlah</th>
                        <th>Stok Sebelum</th>
                        <th>Stok Sesudah</th>
                        <th>Petugas</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentStockMovements as $movement)
                        <tr>
                            <td>
                                {{ $movement->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <strong>
                                    {{ $movement->product?->name ?? 'Produk tidak ditemukan' }}
                                </strong>
                                <div class="small text-muted">
                                    {{ $movement->product?->sku ?? '-' }}
                                </div>
                            </td>

                            <td>
                                @if($movement->movement_type === 'incoming')
                                    <span class="badge bg-success">Barang Masuk</span>
                                @elseif($movement->movement_type === 'outgoing')
                                    <span class="badge bg-warning text-dark">Barang Keluar</span>
                                @elseif($movement->movement_type === 'sale')
                                    <span class="badge bg-primary">Penjualan</span>
                                @elseif($movement->movement_type === 'adjustment')
                                    <span class="badge bg-secondary">Penyesuaian</span>
                                @else
                                    <span class="badge bg-dark">
                                        {{ ucfirst($movement->movement_type) }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($movement->movement_type === 'adjustment')
                                    {{ $movement->quantity > 0 ? '+' : '' }}
                                    {{ number_format($movement->quantity) }}
                                @else
                                    {{ number_format(abs($movement->quantity)) }}
                                @endif
                            </td>

                            <td>
                                {{ number_format($movement->stock_before) }}
                            </td>

                            <td>
                                {{ number_format($movement->stock_after) }}
                            </td>

                            <td>
                                {{ $movement->user?->name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="text-center text-muted py-4">
                                Belum ada aktivitas pergerakan stok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
