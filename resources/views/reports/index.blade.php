
@extends('layouts.app')

@section('page-title', 'Laporan')

@section('content')

{{-- FILTER PERIODE --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('reports.index') }}" method="GET">
            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label">Tanggal Mulai</label>
                    <input
                        type="date"
                        name="start_date"
                        class="form-control"
                        value="{{ $startDate }}"
                        required
                    >
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Akhir</label>
                    <input
                        type="date"
                        name="end_date"
                        class="form-control"
                        value="{{ $endDate }}"
                        min="{{ $startDate }}"
                        required
                    >
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Terapkan Filter
                    </button>

                    <a href="{{ route('reports.index') }}"
                       class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- RINGKASAN PENJUALAN --}}
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Total Penjualan</div>
                <h4 class="fw-bold mt-2">
                    Rp {{ number_format($totalSales, 0, ',', '.') }}
                </h4>
                <small class="text-muted">Transaksi berhasil</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Jumlah Transaksi</div>
                <h4 class="fw-bold mt-2">
                    {{ number_format($totalTransactions, 0, ',', '.') }}
                </h4>
                <small class="text-muted">Transaksi berhasil</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Produk Terjual</div>
                <h4 class="fw-bold mt-2">
                    {{ number_format($totalProductsSold, 0, ',', '.') }}
                </h4>
                <small class="text-muted">Total unit terjual</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Produk Terdaftar</div>
                <h4 class="fw-bold mt-2">
                    {{ number_format($registeredProducts, 0, ',', '.') }}
                </h4>
                <small class="text-muted">Produk aktif</small>
            </div>
        </div>
    </div>

</div>

{{-- PRODUK TERLARIS --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Produk Terlaris</h5>

        @if ($topProducts->isEmpty())

            <div class="text-center text-muted py-4">
                Belum ada data produk terjual pada periode ini.
            </div>

        @else

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Produk</th>
                            <th class="text-end">Jumlah Terjual</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($topProducts as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td class="text-end">
                                    {{ number_format($product->total_sold, 0, ',', '.') }}
                                    unit
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        @endif

    </div>
</div>

{{-- RIWAYAT TRANSAKSI --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Riwayat Transaksi</h5>

        @if ($transactions->isEmpty())

            <div class="text-center text-muted py-4">
                Tidak ada transaksi berhasil pada periode ini.
            </div>

        @else

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor Invoice</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Metode Pembayaran</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($transactions as $index => $transaction)
                            <tr>
                                <td>
                                    {{ $transactions->firstItem() + $index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $transaction->invoice_number }}
                                </td>

                                <td>
                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    {{ $transaction->user->name ?? '-' }}
                                </td>

                                <td>
                                    {{ ucfirst($transaction->payment_method) }}
                                </td>

                                <td class="text-end fw-semibold">
                                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="mt-3">
                {{ $transactions->links() }}
            </div>

        @endif

    </div>
</div>

@endsection
