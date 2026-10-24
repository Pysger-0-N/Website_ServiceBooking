@extends('layouts.app')
@section('title', 'Booking Berhasil')

@section('content')
<div class="container py-4 text-center">
    <div class="alert alert-success">
        <h3>Booking Berhasil!</h3>
        <p>Booking kamu sedang menunggu konfirmasi dari provider.</p>
    </div>

    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body text-start">
            <h5>Detail Booking #{{ $booking->id }}</h5>
            <p>Layanan: <strong>{{ $booking->service->name }}</strong></p>
            <p>Provider: <strong>{{ $booking->provider->name }}</strong></p>
            <p>Tanggal: <strong>{{ $booking->booking_date }}</strong></p>
            <p>Waktu: <strong>{{ $booking->booking_time }}</strong></p>
            <p>Alamat: <strong>{{ $booking->location_address }}</strong></p>
            <p>Total: <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></p>
            <p>Status: <span class="badge bg-warning">{{ $booking->status }}</span></p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('customer.dashboard') }}" class="btn btn-primary">Lihat Dashboard</a>
        <a href="{{ route('booking.select-category') }}" class="btn btn-outline-primary">Booking Lagi</a>
    </div>
</div>
@endsection
