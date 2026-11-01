
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


<div class="row g-3 mt-2">

    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="mb-3">Grafik Penjualan 7 Hari Terakhir</h5>
                <canvas id="salesChart" height="110"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="mb-3">5 Produk Terlaris</h5>

                @forelse($topProducts as $index => $product)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <strong>{{ $index + 1 }}. {{ $product->product_name }}</strong>
                        </div>
                        <span class="badge bg-primary">
                            {{ number_format($product->total_sold) }} terjual
                        </span>
                    </div>
                @empty
                    <p class="text-muted mb-0">
                        Belum ada data penjualan.
                    </p>
                @endforelse

            </div>
        </div>
    </div>

</div>

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
                label: 'Penjualan (Rp)',
                data: salesData,
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
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

