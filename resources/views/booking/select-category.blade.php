@extends('layouts.app')
@section('title', 'Pilih Kategori')

@section('content')
<div class="container py-4">
    <h2>Pilih Kategori Layanan</h2>
    <div class="row mt-3">
        @foreach($categories as $category)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5>{{ $category->name }}</h5>
                    <p>{{ $category->description }}</p>
                    <p class="text-muted">{{ $category->services_count }} layanan</p>
                    <a href="{{ route('booking.select-service', $category) }}" class="btn btn-primary">Pilih</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
