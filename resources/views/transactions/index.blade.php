
@extends('layouts.app')

@section('page-title', 'Riwayat Transaksi')

@section('content')

<div class="card border-0 shadow-sm">
    <div class="card-body">

        {{-- Form pencarian dan filter tanggal --}}
        <form
            method="GET"
            action="{{ route('transactions.index') }}"
            class="row g-2 mb-3"
            data-live-search
        >

            <div class="col-md-5">
                <input
                    type="search"
                    name="search"
                    class="form-control"
                    placeholder="Cari nomor invoice, nama kasir, atau status..."
                    value="{{ $search }}"
                    data-live-search-input
                    autocomplete="off"
                >
            </div>

            <div class="col-md-4">
                <input
                    type="date"
                    name="date"
                    class="form-control"
                    value="{{ $date }}"
                >
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Cari
                </button>

                <a
                    href="{{ route('transactions.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Reset
                </a>
            </div>

        </form>

        {{-- Jumlah hasil pencarian --}}
        @if ($search !== '' || $date)
            <p class="text-muted small mb-3">
                Menampilkan {{ $transactions->total() }} transaksi

                @if ($search !== '')
                    untuk pencarian "{{ $search }}"
                @endif

                @if ($date)
                    pada tanggal {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                @endif
                .
            </p>
        @endif

        {{-- Tabel transaksi --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>
                                {{ $transactions->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>{{ $transaction->invoice_number }}</strong>
                            </td>

                            <td>
                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                {{ $transaction->user?->name ?? '-' }}
                            </td>

                            <td>
                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                            </td>

                            <td>
                                @if ($transaction->status === 'completed')
                                    <span class="badge bg-success">
                                        Selesai
                                    </span>
                                @elseif ($transaction->status === 'cancelled')
                                    <span class="badge bg-danger">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <a
                                    href="{{ route('transactions.show', $transaction) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                @if ($search !== '' || $date)
                                    Tidak ada transaksi yang sesuai dengan pencarian atau filter.
                                @else
                                    Belum ada transaksi.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $transactions->links() }}
        </div>

    </div>
</div>

@endsection
