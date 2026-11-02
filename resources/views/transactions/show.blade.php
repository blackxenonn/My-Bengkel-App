@extends('layouts.app')

@section('page-title', 'Detail Transaksi')

@section('content')

{{-- TOMBOL AKSI --}}
<div class="d-flex justify-content-between align-items-center mb-3">

    <a href="{{ route('transactions.index') }}"
       class="btn btn-outline-secondary">
        Kembali
    </a>

    <div class="d-flex gap-2">

        {{-- Tombol pembatalan untuk Owner --}}
        @if ($transaction->status === 'completed' && auth()->user()->role?->name === 'owner')
            <button
                type="button"
                class="btn btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#cancelTransactionModal"
            >
                Batalkan Transaksi
            </button>
        @endif

        {{-- Tombol pengajuan pembatalan untuk Kasir --}}
        @if (
            $transaction->status === 'completed' &&
            auth()->user()->role?->name === 'kasir' &&
            !$pendingCancellationRequest
        )
            <button
                type="button"
                class="btn btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#requestCancellationModal"
            >
                Ajukan Pembatalan
            </button>
        @endif


        <button onclick="window.print()" class="btn btn-primary">
            Cetak
        </button>

    </div>
</div>

{{-- PESAN NOTIFIKASI --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- INFORMASI PENGAJUAN PEMBATALAN --}}
@if ($pendingCancellationRequest)
    <div class="alert alert-warning">
        <h6 class="fw-bold">Pengajuan Pembatalan Menunggu Persetujuan</h6>

        <p class="mb-1">
            <strong>Diajukan oleh:</strong>
            {{ $pendingCancellationRequest->requester?->name ?? '-' }}
        </p>

        <p class="mb-0">
            <strong>Alasan:</strong>
            {{ $pendingCancellationRequest->reason }}
        </p>
    </div>

@elseif ($latestCancellationRequest && $latestCancellationRequest->status === 'rejected')
    <div class="alert alert-danger">
        <h6 class="fw-bold">Pengajuan Pembatalan Ditolak</h6>

        <p class="mb-1">
            <strong>Alasan pengajuan:</strong>
            {{ $latestCancellationRequest->reason }}
        </p>

        <p class="mb-1">
            <strong>Diputuskan oleh:</strong>
            {{ $latestCancellationRequest->reviewer?->name ?? '-' }}
        </p>

        <p class="mb-0">
            <strong>Catatan Owner:</strong>
            {{ $latestCancellationRequest->review_notes ?? '-' }}
        </p>
    </div>
@endif

{{-- INFORMASI PEMBATALAN --}}
@if ($transaction->status === 'cancelled')
    <div class="alert alert-danger">

        <h6 class="fw-bold">Transaksi Dibatalkan</h6>

        <p class="mb-1">
            <strong>Waktu Pembatalan:</strong>
            {{ $transaction->cancelled_at?->format('d/m/Y H:i') ?? '-' }}
        </p>

        <p class="mb-1">
            <strong>Dibatalkan Oleh:</strong>
            {{ $transaction->cancelledBy?->name ?? '-' }}
        </p>

        <p class="mb-0">
            <strong>Alasan:</strong>
            {{ $transaction->cancellation_reason ?? '-' }}
        </p>

    </div>
@endif

{{-- DETAIL STRUK --}}
<div class="card border-0 shadow-sm" id="receipt">

    <div class="card-body p-4">

        <div class="text-center mb-4">
            <h4 class="fw-bold">MY BENGKEL APP</h4>
            <p class="text-muted mb-0">Struk Transaksi Penjualan</p>
        </div>

        <hr>

        {{-- INFORMASI TRANSAKSI --}}
        <div class="row mb-3">

            <div class="col-6">
                <small class="text-muted">Nomor Invoice</small>
                <div class="fw-semibold">
                    {{ $transaction->invoice_number }}
                </div>
            </div>

            <div class="col-6 text-end">
                <small class="text-muted">Tanggal Transaksi</small>
                <div class="fw-semibold">
                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                </div>
            </div>

            <div class="col-6 mt-3">
                <small class="text-muted">Kasir</small>
                <div>{{ $transaction->user->name ?? '-' }}</div>
            </div>

            <div class="col-6 mt-3 text-end">
                <small class="text-muted">Status</small>
                <div>
                    @if ($transaction->status === 'completed')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-danger">Dibatalkan</span>
                    @endif
                </div>
            </div>

        </div>

        <hr>

        {{-- DAFTAR PRODUK --}}
        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($transaction->details as $detail)
                        <tr>

                            <td>
                                <div class="fw-semibold">
                                    {{ $detail->product_name }}
                                </div>

                                <small class="text-muted">
                                    {{ $detail->sku }}
                                </small>
                            </td>

                            <td class="text-center">
                                {{ $detail->quantity }}
                            </td>

                            <td class="text-end">
                                Rp {{ number_format($detail->unit_price, 0, ',', '.') }}
                            </td>

                            <td class="text-end">
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

        <hr>

        {{-- RINGKASAN PEMBAYARAN --}}
        <div class="row justify-content-end">

            <div class="col-md-6">

                <div class="d-flex justify-content-between mb-2">
                    <span>Total</span>
                    <strong>
                        Rp {{ number_format($transaction->total, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Uang Dibayar</span>
                    <span>
                        Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Kembalian</span>
                    <span>
                        Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}
                    </span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Metode Pembayaran</span>
                    <span>
                        {{ ucfirst($transaction->payment_method) }}
                    </span>
                </div>

            </div>

        </div>

        <hr>

        <div class="text-center mt-4">

            @if ($transaction->status === 'cancelled')
                <p class="fw-bold text-danger">
                    TRANSAKSI DIBATALKAN
                </p>
            @else
                <p class="mb-1 fw-semibold">
                    Terima kasih atas kunjungan Anda.
                </p>
            @endif

            <small class="text-muted">
                Simpan struk ini sebagai bukti transaksi.
            </small>

        </div>

    </div>

</div>

{{-- MODAL PEMBATALAN --}}
@if ($transaction->status === 'completed' && auth()->user()->role?->name === 'owner')

    <div class="modal fade" id="cancelTransactionModal" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <form
                    action="{{ route('transactions.cancel', $transaction) }}"
                    method="POST"
                >

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Batalkan Transaksi
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>

                    </div>

                    <div class="modal-body">

                        <div class="alert alert-warning">
                            <strong>Perhatian!</strong>

                            <p class="mb-0 mt-1">
                                Pembatalan akan mengembalikan stok produk.
                                Transaksi tidak akan dihapus dari riwayat.
                            </p>
                        </div>

                        <label class="form-label">
                            Alasan Pembatalan
                        </label>

                        <textarea
                            name="cancellation_reason"
                            class="form-control"
                            rows="4"
                            minlength="5"
                            maxlength="1000"
                            required
                            placeholder="Contoh: Kesalahan input jumlah pembelian."
                        ></textarea>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Kembali
                        </button>

                        <button type="submit" class="btn btn-danger">
                            Ya, Batalkan Transaksi
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif


{{-- MODAL PENGAJUAN PEMBATALAN KASIR --}}
@if (
    $transaction->status === 'completed' &&
    auth()->user()->role?->name === 'kasir' &&
    !$pendingCancellationRequest
)

    <div class="modal fade" id="requestCancellationModal" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <form
                    action="{{ route('transactions.request-cancellation', $transaction) }}"
                    method="POST"
                >

                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Ajukan Pembatalan Transaksi
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>
                    </div>

                    <div class="modal-body">

                        <div class="alert alert-warning">
                            <strong>Perhatian!</strong>

                            <p class="mb-0 mt-1">
                                Pengajuan ini harus disetujui Owner.
                                Stok belum dikembalikan sebelum Owner
                                menyetujui pembatalan.
                            </p>
                        </div>

                        <label class="form-label">
                            Alasan Pembatalan
                        </label>

                        <textarea
                            name="reason"
                            class="form-control"
                            rows="4"
                            minlength="5"
                            maxlength="1000"
                            required
                            placeholder="Contoh: Customer salah membeli produk."
                        ></textarea>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Kembali
                        </button>

                        <button type="submit" class="btn btn-warning">
                            Kirim Pengajuan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif


@endsection