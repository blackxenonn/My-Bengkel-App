
@extends('layouts.app')

@section('title', 'Dashboard Kasir - My Bengkel App')

@section('page-title', 'Dashboard Kasir')

@section('content')

<div class="alert alert-primary border-0 shadow-sm">
    Selamat datang, <strong>{{ Auth::user()->name }}</strong>!
    Berikut ringkasan aktivitas penjualan Anda hari ini.
</div>

{{-- Ringkasan penjualan --}}
<div class="row g-3">

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Pemasukan Hari Ini</p>
                <h4 class="fw-bold text-success mb-0">
                    Rp {{ number_format($totalSalesToday, 0, ',', '.') }}
                </h4>
                <small class="text-muted">Dari transaksi berhasil Anda</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Transaksi Hari Ini</p>
                <h4 class="fw-bold mb-0">
                    {{ number_format($totalTransactionsToday) }}
                </h4>
                <small class="text-muted">Transaksi selesai</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Produk Terjual Hari Ini</p>
                <h4 class="fw-bold mb-0">
                    {{ number_format($totalProductsSoldToday) }}
                </h4>
                <small class="text-muted">Total unit produk</small>
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
                <small class="text-muted">Nilai rata-rata transaksi selesai</small>
            </div>
        </div>
    </div>

</div>

{{-- Grafik dan produk terlaris --}}
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
                <h5 class="mb-3">5 Produk Terlaris Hari Ini</h5>

                @forelse($topProducts as $index => $product)
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <div class="me-2">
                            <strong>
                                {{ $index + 1 }}. {{ $product->product_name }}
                            </strong>
                        </div>

                        <span class="badge bg-primary">
                            {{ number_format($product->total_sold) }} terjual
                        </span>
                    </div>
                @empty
                    <p class="text-muted mb-0">
                        Belum ada produk yang terjual hari ini.
                    </p>
                @endforelse

            </div>
        </div>
    </div>

</div>

{{-- Transaksi terbaru --}}
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
                        <th>No.</th>
                        <th>Nomor Invoice</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentTransactions as $index => $transaction)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <strong>{{ $transaction->invoice_number }}</strong>
                            </td>

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
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- Grafik pemasukan --}}
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
