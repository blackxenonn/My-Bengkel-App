
@extends('layouts.app')

@section('title', 'Dashboard Owner - My Bengkel App')

@section('page-title', 'Dashboard Admin / Owner')

@section('content')

<div class="alert alert-primary border-0 shadow-sm">
    Selamat datang, <strong>{{ Auth::user()->name }}</strong>!
    Berikut ringkasan keseluruhan aktivitas penjualan dan persediaan bengkel.
</div>

{{-- RINGKASAN PENJUALAN --}}
<h5 class="mb-3 mt-2">Ringkasan Penjualan Hari Ini</h5>

<div class="row g-3">

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Total Pemasukan</p>
                <h4 class="fw-bold text-success mb-0">
                    Rp {{ number_format($totalSalesToday, 0, ',', '.') }}
                </h4>
                <small class="text-muted">Seluruh Kasir</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Total Transaksi</p>
                <h4 class="fw-bold mb-0">
                    {{ number_format($totalTransactionsToday) }}
                </h4>
                <small class="text-muted">Transaksi berhasil</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Produk Terjual</p>
                <h4 class="fw-bold mb-0">
                    {{ number_format($totalProductsSoldToday) }}
                </h4>
                <small class="text-muted">Total unit hari ini</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Rata-rata Transaksi</p>
                <h4 class="fw-bold mb-0">
                    Rp {{ number_format($averageTransaction, 0, ',', '.') }}
                </h4>
                <small class="text-muted">Nilai rata-rata transaksi</small>
            </div>
        </div>
    </div>

</div>

{{-- RINGKASAN PERSEDIAAN --}}
<h5 class="mb-3 mt-4">Ringkasan Persediaan Gudang</h5>

<div class="row g-3">

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Produk Aktif</p>
                <h4 class="fw-bold mb-0">
                    {{ number_format($totalProducts) }}
                </h4>
                <small class="text-muted">Total produk aktif</small>
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
                <small class="text-muted">Mencapai batas minimum</small>
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
                <small class="text-muted">Total unit masuk</small>
            </div>
        </div>
    </div>

</div>

<div class="row g-3 mt-1">

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Barang Keluar Hari Ini</p>
                <h4 class="fw-bold text-danger mb-0">
                    {{ number_format($outgoingToday) }} unit
                </h4>
                <small class="text-muted">
                    Termasuk barang keluar dan penjualan
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Menu Pengelolaan</p>

                <div class="d-flex flex-wrap gap-2 mt-2">
                    <a href="{{ route('reports.index') }}"
                       class="btn btn-outline-primary btn-sm">
                        Laporan
                    </a>

                    <a href="{{ route('products.index') }}"
                       class="btn btn-outline-primary btn-sm">
                        Data Produk
                    </a>

                    <a href="{{ route('stock-movements.index') }}"
                       class="btn btn-outline-primary btn-sm">
                        Riwayat Stok
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- GRAFIK DAN PRODUK TERLARIS --}}
<div class="row g-3 mt-2">

    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="mb-3">Grafik Pemasukan 7 Hari Terakhir</h5>

                <div style="height: 300px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="mb-3">5 Produk Terlaris</h5>
                <small class="text-muted">Berdasarkan penjualan 7 hari terakhir</small>

                @forelse($topProducts as $index => $product)
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <strong>
                            {{ $index + 1 }}. {{ $product->product_name }}
                        </strong>

                        <span class="badge bg-primary">
                            {{ number_format($product->total_sold) }} terjual
                        </span>
                    </div>
                @empty
                    <p class="text-muted mt-3 mb-0">
                        Belum ada data penjualan.
                    </p>
                @endforelse

            </div>
        </div>
    </div>

</div>

{{-- STOK MENIPIS DAN HABIS --}}
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
                                <th>Produk</th>
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
                                        Tidak ada stok menipis.
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
                                <th>Produk</th>
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

{{-- TRANSAKSI TERBARU --}}
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Transaksi Terbaru</h5>

            <a href="{{ route('transactions.index') }}"
               class="btn btn-outline-primary btn-sm">
                Lihat Semua
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Invoice</th>
                        <th>Kasir</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentTransactions as $transaction)
                        <tr>
                            <td>
                                <strong>{{ $transaction->invoice_number }}</strong>
                            </td>

                            <td>{{ $transaction->user?->name ?? '-' }}</td>

                            <td>
                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                            </td>

                            <td>
                                @if($transaction->status === 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($transaction->status === 'cancelled')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('transactions.show', $transaction) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="text-center text-muted py-4">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- RIWAYAT PERGERAKAN STOK --}}
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Pergerakan Stok Terbaru</h5>

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
                        <th>Jenis</th>
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

                            <td>{{ number_format($movement->stock_before) }}</td>
                            <td>{{ number_format($movement->stock_after) }}</td>
                            <td>{{ $movement->user?->name ?? '-' }}</td>
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

{{-- GRAFIK PEMASUKAN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const salesLabels = @json($salesLabels);
    const salesData = @json($salesData);

    const salesCanvas = document.getElementById('salesChart');

    new Chart(salesCanvas, {
        type: 'line',

        data: {
            labels: salesLabels,

            datasets: [{
                label: 'Pemasukan (Rp)',
                data: salesData,
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID')
                                .format(context.raw);
                        }
                    }
                }
            },

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID')
                                .format(value);
                        }
                    }
                }
            }
        }
    });
</script>

@endsection
