@extends('layouts.app')

@section('page-title', 'Riwayat Transaksi')

@section('content')

<div class="card border-0 shadow-sm">
    <div class="card-body">

        <form method="GET" action="{{ route('transactions.index') }}" class="row g-2 mb-4">

            <div class="col-md-5">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari nomor invoice..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="col-md-4">
                <input
                    type="date"
                    name="date"
                    class="form-control"
                    value="{{ request('date') }}"
                >
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Cari
                </button>

                <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>

        </form>

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
                            <td>{{ $transactions->firstItem() + $loop->index }}</td>

                            <td>
                                <strong>{{ $transaction->invoice_number }}</strong>
                            </td>

                            <td>
                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                {{ $transaction->user->name ?? '-' }}
                            </td>

                            <td>
                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                            </td>

                            <td>
                                @if ($transaction->status === 'completed')
                                    <span class="badge bg-success">Selesai</span>
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
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="mt-3">
            {{ $transactions->links() }}
        </div>

    </div>
</div>

@endsection