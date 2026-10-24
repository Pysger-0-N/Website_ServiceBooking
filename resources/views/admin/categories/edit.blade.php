@extends('layouts.admin')
@section('title', 'Edit Kategori')

@section('content')
<div class="container py-4">
    <h2>Edit Kategori</h2>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm mb-3">Kembali</a>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
