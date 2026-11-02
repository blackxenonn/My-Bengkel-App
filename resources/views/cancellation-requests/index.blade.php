@extends('layouts.app')

@section('title', 'Pengajuan Pembatalan')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Pengajuan Pembatalan Transaksi</h4>
            <p class="text-muted mb-0">
                Daftar pengajuan pembatalan transaksi dari Kasir.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($cancellationRequests->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <h5 class="fw-semibold">Belum Ada Pengajuan</h5>
                <p class="text-muted mb-0">
                    Saat ini belum ada pengajuan pembatalan transaksi.
                </p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">No.</th>
                                <th>Invoice</th>
                                <th>Kasir</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($cancellationRequests as $request)
                                <tr>
                                    <td class="px-3">
                                        {{ $cancellationRequests->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <span class="fw-semibold">
                                            {{ $request->transaction->invoice_number }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $request->requester->name }}
                                    </td>

                                    <td>
                                        {{ $request->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td style="min-width: 200px; max-width: 300px;">
                                        {{ $request->reason }}
                                    </td>

                                    <td>
                                        @if ($request->status === 'pending')
                                            <span class="badge bg-warning text-dark">
                                                Menunggu
                                            </span>
                                        @elseif ($request->status === 'approved')
                                            <span class="badge bg-success">
                                                Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a
                                            href="{{ route('transactions.show', $request->transaction_id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Detail Transaksi
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white">
                {{ $cancellationRequests->links() }}
            </div>
        </div>
    @endif

</div>
@endsection