
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

    {{-- Notifikasi berhasil --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Notifikasi error --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                                <th>Alasan Pengajuan</th>
                                <th>Status</th>
                                <th>Catatan Owner</th>
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

                                    <td style="min-width: 180px;">
                                        @if ($request->review_notes)
                                            {{ $request->review_notes }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif

                                        @if ($request->reviewer)
                                            <div class="small text-muted mt-1">
                                                Diproses oleh: {{ $request->reviewer->name }}
                                            </div>
                                        @endif

                                        @if ($request->reviewed_at)
                                            <div class="small text-muted">
                                                {{ $request->reviewed_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-center" style="min-width: 220px;">
                                        <div class="d-flex flex-column gap-2">

                                            {{-- Detail transaksi --}}
                                            <a
                                                href="{{ route('transactions.show', $request->transaction_id) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Detail Transaksi
                                            </a>

                                            @if ($request->status === 'pending')

                                                {{-- Tombol setujui --}}
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#approveModal{{ $request->id }}"
                                                >
                                                    Setujui
                                                </button>

                                                {{-- Tombol tolak --}}
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $request->id }}"
                                                >
                                                    Tolak
                                                </button>

                                            @else
                                                <span class="small text-muted">
                                                    Sudah diproses
                                                </span>
                                            @endif

                                        </div>
                                    </td>
                                </tr>

                                @if ($request->status === 'pending')

                                    {{-- Modal persetujuan --}}
                                    <div
                                        class="modal fade"
                                        id="approveModal{{ $request->id }}"
                                        tabindex="-1"
                                        aria-hidden="true"
                                    >
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">

                                                <form
                                                    action="{{ route('cancellation-requests.approve', $request->id) }}"
                                                    method="POST"
                                                >
                                                    @csrf

                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">
                                                            Setujui Pembatalan
                                                        </h5>
                                                        <button
                                                            type="button"
                                                            class="btn-close"
                                                            data-bs-dismiss="modal"
                                                        ></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p>
                                                            Apakah kamu yakin ingin menyetujui
                                                            pembatalan transaksi
                                                            <strong>
                                                                {{ $request->transaction->invoice_number }}
                                                            </strong>?
                                                        </p>

                                                        <div class="alert alert-warning small">
                                                            Transaksi akan dibatalkan dan stok
                                                            produk akan dikembalikan secara otomatis.
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Catatan Owner (Opsional)
                                                            </label>
                                                            <textarea
                                                                name="review_notes"
                                                                class="form-control"
                                                                rows="3"
                                                                maxlength="1000"
                                                                placeholder="Tambahkan catatan jika diperlukan..."
                                                            ></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button
                                                            type="button"
                                                            class="btn btn-secondary"
                                                            data-bs-dismiss="modal"
                                                        >
                                                            Batal
                                                        </button>

                                                        <button
                                                            type="submit"
                                                            class="btn btn-success"
                                                        >
                                                            Ya, Setujui
                                                        </button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal penolakan --}}
                                    <div
                                        class="modal fade"
                                        id="rejectModal{{ $request->id }}"
                                        tabindex="-1"
                                        aria-hidden="true"
                                    >
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">

                                                <form
                                                    action="{{ route('cancellation-requests.reject', $request->id) }}"
                                                    method="POST"
                                                >
                                                    @csrf

                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">
                                                            Tolak Pembatalan
                                                        </h5>
                                                        <button
                                                            type="button"
                                                            class="btn-close"
                                                            data-bs-dismiss="modal"
                                                        ></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p>
                                                            Kamu akan menolak pengajuan pembatalan
                                                            transaksi
                                                            <strong>
                                                                {{ $request->transaction->invoice_number }}
                                                            </strong>.
                                                        </p>

                                                        <div class="alert alert-info small">
                                                            Transaksi tetap selesai dan stok produk
                                                            tidak akan berubah.
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Alasan Penolakan
                                                                <span class="text-danger">*</span>
                                                            </label>

                                                            <textarea
                                                                name="review_notes"
                                                                class="form-control"
                                                                rows="3"
                                                                minlength="5"
                                                                maxlength="1000"
                                                                required
                                                                placeholder="Tuliskan alasan penolakan minimal 5 karakter..."
                                                            ></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button
                                                            type="button"
                                                            class="btn btn-secondary"
                                                            data-bs-dismiss="modal"
                                                        >
                                                            Batal
                                                        </button>

                                                        <button
                                                            type="submit"
                                                            class="btn btn-danger"
                                                        >
                                                            Ya, Tolak
                                                        </button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @endif
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
