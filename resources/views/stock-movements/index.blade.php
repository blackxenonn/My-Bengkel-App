@extends('layouts.app')

@section('page-title', 'Riwayat Pergerakan Stok')

@section('content')

<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('stock-movements.create') }}" class="btn btn-primary">
        Tambah Pergerakan Stok
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

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
                    @forelse($movements as $movement)
                        <tr>
                            <td>{{ $movements->firstItem() + $loop->index }}</td>
                            <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
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
                                        <span class="badge bg-success">Stok Masuk</span>
                                        @break
                                    @case('outgoing')
                                        <span class="badge bg-danger">Stok Keluar</span>
                                        @break
                                    @case('adjustment')
                                        <span class="badge bg-warning text-dark">Penyesuaian</span>
                                        @break
                                    @case('sale')
                                        <span class="badge bg-primary">Penjualan</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $movement->movement_type }}</span>
                                @endswitch
                            </td>
                            <td>
                                {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                            </td>
                            <td>{{ $movement->stock_before }}</td>
                            <td>{{ $movement->stock_after }}</td>
                            <td>{{ $movement->user?->name ?? '-' }}</td>
                            <td>{{ $movement->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Belum ada riwayat pergerakan stok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $movements->links() }}
    </div>
</div>

@endsection