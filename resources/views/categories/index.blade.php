
@extends('layouts.app')

@section('page-title', 'Kategori Produk')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
    <p class="text-muted mb-0">
        Kelola kategori untuk mengelompokkan produk agar lebih terorganisir.
    </p>

    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Kategori
    </a>
</div>

{{-- Notifikasi --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

{{-- Tabel Kategori --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">

        {{-- FORM PENCARIAN --}}
        <form action="{{ route('categories.index') }}"
            method="GET"
            data-live-search
            class="mb-3">

            <div class="input-group">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari nama atau deskripsi kategori..."
                    value="{{ $search }}"
                    data-live-search-input
                    autocomplete="off"
                >

                <button type="submit" class="btn btn-primary">
                    Cari
                </button>

                <a href="{{ route('categories.index') }}"
                class="btn btn-secondary">
                    Reset
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                {{ $categories->firstItem() + $loop->index }}
                            </td>

                            <td class="fw-semibold">
                                {{ $category->name }}
                            </td>

                            <td>
                                {{ $category->description ?? '-' }}
                            </td>

                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('categories.edit', $category) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('categories.destroy', $category) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $categories->links() }}
        </div>

    </div>
</div>

@endsection
