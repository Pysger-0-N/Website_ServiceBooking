@extends('layouts.app')
@section('title', 'Pilih Layanan')

@section('content')
<div class="container py-4">
    <h2>Pilih Layanan — {{ $category->name }}</h2>
    <a href="{{ route('booking.select-category') }}" class="btn btn-secondary btn-sm mb-3">Kembali</a>
    <div class="row mt-2">
        @foreach($services as $service)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5>{{ $service->name }}</h5>
                    <p>{{ $service->description }}</p>
                    <p><strong>Harga: Rp {{ number_format($service->base_price, 0, ',', '.') }}</strong> / {{ $service->unit }}</p>
                    <a href="{{ route('booking.select-provider', $service) }}" class="btn btn-primary">Pilih</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
