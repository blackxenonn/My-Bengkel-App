@extends('layouts.app')

@section('page-title', 'Edit Karyawan')

@section('content')

<div class="card border-0 shadow-sm">
    <div class="card-body">

        <form action="{{ route('employees.update', $employee->id) }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name"
                    class="form-control"
                    value="{{ old('name', $employee->name) }}" required>
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username"
                    class="form-control"
                    value="{{ old('username', $employee->username) }}" required>
                @error('username')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                    class="form-control"
                    value="{{ old('email', $employee->email) }}" required>
                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password"
                    class="form-control">
                <small class="text-muted">
                    Kosongkan jika tidak ingin mengganti password.
                </small>
                @error('password')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Role Karyawan</label>
                <select name="role_id" class="form-select" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}"
                            @selected(old('role_id', $employee->role_id) == $role->id)>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Status Akun</label>
                <select name="is_active" class="form-select" required>
                    <option value="1"
                        @selected(old('is_active', (string) (int) $employee->is_active) == '1')>
                        Aktif
                    </option>
                    <option value="0"
                        @selected(old('is_active', (string) (int) $employee->is_active) == '0')>
                        Nonaktif
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>

            <a href="{{ route('employees.index') }}"
                class="btn btn-secondary">
                Kembali
            </a>

        </form>

    </div>
</div>

@endsection