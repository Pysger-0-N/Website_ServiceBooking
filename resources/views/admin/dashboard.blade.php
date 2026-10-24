@extends('layouts.admin')
@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">
    <h2>Admin Dashboard </h2>

    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h3>{{ $stats['total_users'] }}</h3>
                    <p class="mb-0">Customer</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-info">
                <div class="card-body">
                    <h3>{{ $stats['total_providers'] }}</h3>
                    <p class="mb-0">Provider</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h3>{{ $stats['total_bookings'] }}</h3>
                    <p class="mb-0">Total Booking</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h3>{{ $stats['pending'] }}</h3>
                    <p class="mb-0">Menunggu</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h3>{{ $stats['done'] }}</h3>
                    <p class="mb-0">Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-dark">Kelola Booking</a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Kelola Kategori</a>
        <a href="{{ route('admin.services.index') }}" class="btn btn-info">Kelola Service</a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-warning">Kelola User</a>
        <a href="{{ route('admin.map') }}" class="btn btn-success">Peta Provider</a>
    </div>

    <h5>Booking Terbaru</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Provider</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($latestBookings as $booking)
            <tr>
                <td>{{ $booking->id }}</td>
                <td>{{ $booking->customer->name }}</td>
                <td>{{ $booking->provider->name }}</td>
                <td>{{ $booking->service->name }}</td>
                <td>{{ $booking->booking_date }}</td>
                <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td>{!! statusBadge($booking->status) !!}<td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
