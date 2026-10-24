@extends('layouts.admin')
@section('title', 'Kelola Service')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Kelola Service</h2>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Tambah Service</a>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm mb-3">Dashboard</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kategori</th>
                <th>Nama Service</th>
                <th>Harga</th>
                <th>Unit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->id }}</td>
                <td>{{ $service->category->name }}</td>
                <td>{{ $service->name }}</td>
                <td>Rp {{ number_format($service->base_price, 0, ',', '.') }}</td>
                <td>{{ $service->unit }}</td>
                <td>
                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus service ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
