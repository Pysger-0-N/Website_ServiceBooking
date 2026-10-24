@extends('layouts.app')
@section('title', 'Kelola Booking')

@section('content')
<div class="container py-4">
    <h2>Kelola Booking</h2>
    <a href="{{ route('provider.dashboard') }}" class="btn btn-secondary btn-sm mb-3">Dashboard</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($bookings->isEmpty())
        <div class="alert alert-info">Belum ada booking masuk.</div>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Alamat</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->customer->name }}</td>
                <td>{{ $booking->service->name }}</td>
                <td>{{ $booking->booking_date }}</td>
                <td>{{ $booking->booking_time }}</td>
                <td>{{ $booking->location_address }}</td>
                <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td>{!! statusBadge($booking->status) !!}</td>
                <td>
                    @if($booking->status === 'pending')
                        <form action="{{ route('provider.bookings.confirm', $booking) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-success">Terima</button>
                        </form>
                        <form action="{{ route('provider.bookings.reject', $booking) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Tolak booking ini?')">Tolak</button>
                        </form>
                    @elseif($booking->status === 'confirmed')
                        <form action="{{ route('provider.bookings.done', $booking) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-primary">Selesai</button>
                        </form>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
