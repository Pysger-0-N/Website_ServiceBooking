@extends('layouts.app')
@section('title', 'Dashboard Customer')

@section('content')
<div class="container py-4">
    <h2>Halo, {{ auth()->user()->name }}!</h2>
    <p class="text-muted">Selamat datang di dashboard kamu.</p>

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('booking.select-category') }}" class="btn btn-primary">Booking Baru</a>
        <a href="{{ route('customer.bookings') }}" class="btn btn-outline-secondary">Lihat Semua Booking</a>
    </div>

    <h5>Booking Terbaru</h5>
    @if($bookings->isEmpty())
        <div class="alert alert-info">Belum ada booking. <a href="{{ route('booking.select-category') }}">Booking sekarang!</a></div>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Layanan</th>
                <th>Provider</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->service->name }}</td>
                <td>{{ $booking->provider->name }}</td>
                <td>{{ $booking->booking_date }}</td>
                <td>{!! statusBadge($booking->status) !!}</td>
                <td>
                    <a href="{{ route('customer.bookings.show', $booking) }}" class="btn btn-sm btn-info">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
