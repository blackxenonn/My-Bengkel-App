
@extends('layouts.app')

@section('page-title', 'Riwayat Pergerakan Stok')

@section('content')

<div class="container-fluid">

    {{-- Deskripsi dan tombol tambah --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
        <p class="text-muted mb-0">
            Pantau seluruh aktivitas perubahan stok produk yang tercatat dalam sistem.
        </p>

        <a href="{{ route('stock-movements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Pergerakan Stok
        </a>
    </div>

    {{-- Notifikasi berhasil --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Notifikasi error --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Pencarian riwayat pergerakan stok --}}
    <form
        action="{{ route('stock-movements.index') }}"
        method="GET"
        class="mb-3"
        data-live-search
    >
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>

            <input
                type="search"
                name="search"
                class="form-control"
                placeholder="Cari produk, SKU, jenis pergerakan, pengguna, atau catatan..."
                value="{{ $search }}"
                data-live-search-input
                autocomplete="off"
            >

            @if ($search !== '')
                <a
                    href="{{ route('stock-movements.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Jumlah hasil pencarian --}}
    @if ($search !== '')
        <p class="text-muted small mb-3">
            Menampilkan {{ $movements->total() }}
            hasil pencarian untuk "{{ $search }}".
        </p>
    @endif

    {{-- Tabel riwayat pergerakan stok --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Jenis</th>
                            <th>Perubahan</th>
                            <th>Stok Sebelum</th>
                            <th>Stok Sesudah</th>
                            <th>Pengguna</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($movements as $movement)

                            <tr>
                                <td>
                                    {{ $movements->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $movement->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    {{ $movement->product?->name ?? 'Produk tidak tersedia' }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $movement->product?->sku }}
                                    </small>
                                </td>

                                <td>
                                    @switch($movement->movement_type)

                                        @case('incoming')
                                            <span class="badge bg-success">
                                                Stok Masuk
                                            </span>
                                            @break

                                        @case('outgoing')
                                            <span class="badge bg-danger">
                                                Stok Keluar
                                            </span>
                                            @break

                                        @case('adjustment')
                                            <span class="badge bg-warning text-dark">
                                                Penyesuaian
                                            </span>
                                            @break

                                        @case('sale')
                                            <span class="badge bg-primary">
                                                Penjualan
                                            </span>
                                            @break

                                        @default
                                            <span class="badge bg-secondary">
                                                {{ $movement->movement_type }}
                                            </span>

                                    @endswitch
                                </td>

                                <td>
                                    {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                </td>

                                <td>
                                    {{ $movement->stock_before }}
                                </td>

                                <td>
                                    {{ $movement->stock_after }}
                                </td>

                                <td>
                                    {{ $movement->user?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $movement->notes ?? '-' }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">

                                    @if ($search !== '')
                                        Tidak ada riwayat pergerakan stok yang cocok
                                        dengan pencarian "{{ $search }}".
                                    @else
                                        Belum ada riwayat pergerakan stok.
                                    @endif

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $movements->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
