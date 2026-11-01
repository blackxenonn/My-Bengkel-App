
@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control"
                          rows="3">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select" required>
                    <option value="1"
                        {{ old('is_active', $category->is_active) == 1 ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="0"
                        {{ old('is_active', $category->is_active) == 0 ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>

            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </form>
    </div>
</div>

@endsection
