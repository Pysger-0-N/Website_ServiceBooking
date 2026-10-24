@extends('layouts.app')
@section('title', 'Detail Booking')

@section('content')
<div class="container py-4">
    <h2>Detail Booking #{{ $booking->id }}</h2>
    <a href="{{ route('customer.bookings') }}" class="btn btn-secondary btn-sm mb-3">Kembali</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <p>Layanan: <strong>{{ $booking->service->name }}</strong></p>
            <p>Provider: <strong>{{ $booking->provider->name }}</strong></p>
            <p>Tanggal: <strong>{{ $booking->booking_date }}</strong></p>
            <p>Waktu: <strong>{{ $booking->booking_time }}</strong></p>
            <p>Alamat: <strong>{{ $booking->location_address }}</strong></p>
            <p>Catatan: {{ $booking->notes ?? '-' }}</p>
            <p>Total: <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></p>
            <p>Status: {!! statusBadge($booking->status) !!}</p>
        </div>
    </div>

    {{-- Tombol Cancel --}}
    @if(in_array($booking->status, ['pending', 'confirmed']))
    <form action="{{ route('customer.bookings.cancel', $booking) }}" method="POST" class="mb-3">
        @csrf
        <button class="btn btn-danger" onclick="return confirm('Yakin mau cancel?')">Batalkan Booking</button>
    </form>
    @endif

    {{-- Form Review --}}
    @if($booking->status === 'done' && !$booking->review)
    <div class="card">
        <div class="card-body">
            <h5>Beri Review</h5>
            <form action="{{ route('customer.bookings.review', $booking) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Rating (1-5)</label>
                    <input type="number" name="rating" class="form-control" min="1" max="5" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Komentar</label>
                    <textarea name="comment" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Kirim Review</button>
            </form>
        </div>
    </div>
    @elseif($booking->review)
    <div class="alert alert-success">
        Kamu sudah memberi review {{ $booking->review->rating }}/5 — {{ $booking->review->comment }}
    </div>
    @endif
</div>
@endsection
