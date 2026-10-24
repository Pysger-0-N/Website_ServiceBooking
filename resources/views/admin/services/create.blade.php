@extends('layouts.admin')
@section('title', 'Tambah Service')

@section('content')
<div class="container py-4">
    <h2>Tambah Service</h2>
    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary btn-sm mb-3">Kembali</a>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.services.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Service</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Harga Dasar (Rp)</label>
            <input type="number" name="base_price" class="form-control" value="{{ old('base_price') }}" min="0" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Unit</label>
            <select name="unit" class="form-control" required>
                <option value="visit" {{ old('unit') == 'visit' ? 'selected' : '' }}>Per Kunjungan</option>
                <option value="jam" {{ old('unit') == 'jam' ? 'selected' : '' }}>Per Jam</option>
                <option value="unit" {{ old('unit') == 'unit' ? 'selected' : '' }}>Per Unit</option>
                <option value="hari" {{ old('unit') == 'hari' ? 'selected' : '' }}>Per Hari</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
