@extends('layouts.app')
@section('title', 'Dashboard Provider')

@section('content')
<div class="container py-4">
    <h2>Halo, {{ auth()->user()->name }}!</h2>
    <p class="text-muted">Selamat datang di dashboard provider kamu.</p>

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h3>{{ $stats['pending'] }}</h3>
                    <p class="mb-0">Menunggu</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h3>{{ $stats['confirmed'] }}</h3>
                    <p class="mb-0">Dikonfirmasi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h3>{{ $stats['done'] }}</h3>
                    <p class="mb-0">Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('provider.bookings') }}" class="btn btn-primary">Lihat Semua Booking</a>
        <a href="{{ route('provider.profile') }}" class="btn btn-outline-secondary">Edit Profil</a>
    </div>

    <h5>Booking Terbaru</h5>
    @if($bookings->isEmpty())
        <div class="alert alert-info">Belum ada booking masuk.</div>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Layanan</th>
                <th>Tanggal</th>
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
                <td>{!! statusBadge($booking->status) !!}</td>
                <td>
                    <a href="{{ route('provider.bookings') }}" class="btn btn-sm btn-info">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
