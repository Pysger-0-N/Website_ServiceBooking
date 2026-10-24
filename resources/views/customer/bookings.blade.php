@extends('layouts.app')
@section('title', 'Riwayat Booking')

@section('content')
<div class="container py-4">
    <h2>Riwayat Booking</h2>
    <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary btn-sm mb-3">Dashboard</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($bookings->isEmpty())
        <div class="alert alert-info">Belum ada booking.</div>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Layanan</th>
                <th>Provider</th>
                <th>Tanggal</th>
                <th>Total</th>
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
                <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
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
