
@extends('layouts.app')

@section('page-title', 'Data Karyawan')

@section('content')

{{-- Pesan Notifikasi --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </div>
@endif

{{-- Pesan Validasi --}}
@if($errors->any())
    <div class="alert alert-danger">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
    <p class="text-muted mb-0">
        Kelola akun karyawan, informasi pengguna, dan hak akses berdasarkan jabatan.
    </p>

    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Karyawan
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">

        {{-- Form Pencarian --}}
        <form action="{{ route('employees.index') }}"
              method="GET"
              data-live-search
              class="mb-3">

            <div class="input-group">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari nama, username, email, atau role..."
                    value="{{ $search }}"
                    data-live-search-input
                    autocomplete="off"
                >

                <button type="submit" class="btn btn-primary">
                    Cari
                </button>

                <a href="{{ route('employees.index') }}"
                   class="btn btn-secondary">
                    Reset
                </a>
            </div>
        </form>

        @if($search !== '')
            <p class="text-muted small mb-3">
                Menampilkan hasil pencarian untuk:
                <strong>{{ $search }}</strong>
                ({{ $employees->total() }} data ditemukan)
            </p>
        @endif

        {{-- Tabel Karyawan --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>
                                {{ $employees->firstItem() + $loop->index }}
                            </td>

                            <td class="fw-semibold">
                                {{ $employee->name }}
                            </td>

                            <td>
                                {{ $employee->username ?? '-' }}
                            </td>

                            <td>
                                {{ $employee->email }}
                            </td>

                            {{-- Role --}}
                            <td>
                                @php
                                    $roleName = $employee->role?->name ?? '-';
                                @endphp

                                @if($roleName === 'owner')
                                    <span class="badge bg-primary">Owner</span>
                                @elseif($roleName === 'kasir')
                                    <span class="badge bg-success">Kasir</span>
                                @elseif($roleName === 'gudang')
                                    <span class="badge bg-warning text-dark">Gudang</span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($roleName) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Status Akun --}}
                            <td>
                                @if($employee->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('employees.edit', $employee->id) }}"
                                        class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('employees.destroy', $employee->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $employee->name }}?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                @if($search !== '')
                                    Karyawan dengan kata kunci "{{ $search }}" tidak ditemukan.
                                @else
                                    Belum ada data karyawan.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $employees->links() }}
        </div>

    </div>
</div>

@endsection
