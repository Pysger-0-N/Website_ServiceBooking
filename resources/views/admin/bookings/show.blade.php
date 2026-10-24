@extends('layouts.admin')
@section('title', 'Detail Booking')

@section('content')
<h2>Detail Booking #{{ $booking->id }}</h2>
<a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm mb-3">Kembali</a>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header"><strong>Info Booking</strong></div>
            <div class="card-body">
                <p>Layanan: <strong>{{ $booking->service->name }}</strong></p>
                <p>Tanggal: <strong>{{ $booking->booking_date }}</strong></p>
                <p>Waktu: <strong>{{ $booking->booking_time }}</strong></p>
                <p>Alamat: <strong>{{ $booking->location_address }}</strong></p>
                <p>Total: <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></p>
                <p>Catatan: {{ $booking->notes ?? '-' }}</p>
                <p>Status: {!! statusBadge($booking->status) !!}</p>
            </div>
        </div>

        {{-- UPDATE STATUS --}}
        <div class="card mb-3">
            <div class="card-header"><strong>Update Status</strong></div>
            <div class="card-body">
                <form action="{{ route('admin.bookings.status', $booking) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <select name="status" class="form-control">
                        @foreach(['pending','confirmed','on_progress','done','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $booking->status == $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $s)) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>

        {{-- REVIEW --}}
        @if($booking->review)
        <div class="card mb-3">
            <div class="card-header"><strong>Review Customer</strong></div>
            <div class="card-body">
                <p>Rating: <strong>{{ $booking->review->rating }}/5</strong></p>
                <p>Komentar: {{ $booking->review->comment ?? '-' }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-6">
        {{-- INFO CUSTOMER --}}
        <div class="card mb-3">
            <div class="card-header"><strong>Customer</strong></div>
            <div class="card-body">
                <p>Nama: <strong>{{ $booking->customer->name }}</strong></p>
                <p>Email: {{ $booking->customer->email }}</p>
                <p>Phone: {{ $booking->customer->phone ?? '-' }}</p>
            </div>
        </div>

        {{-- INFO PROVIDER --}}
        <div class="card mb-3">
            <div class="card-header"><strong>Provider</strong></div>
            <div class="card-body">
                <p>Nama: <strong>{{ $booking->provider->name }}</strong></p>
                <p>Email: {{ $booking->provider->email }}</p>
                <p>Phone: {{ $booking->provider->phone ?? '-' }}</p>
            </div>
        </div>

        {{-- PETA LOKASI BOOKING --}}
        @if($booking->location_lat && $booking->location_lng)
        <div class="card">
            <div class="card-header"><strong>Lokasi Booking</strong></div>
            <div class="card-body p-0">
                <div id="map-booking" style="height: 250px;"></div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    @if($booking->location_lat && $booking->location_lng)
    const mapBooking = L.map('map-booking', { zoomControl: true }).setView(
        [{{ $booking->location_lat }}, {{ $booking->location_lng }}], 15
    );
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapBooking);
    L.marker([{{ $booking->location_lat }}, {{ $booking->location_lng }}])
        .addTo(mapBooking)
        .bindPopup('Lokasi Booking')
        .openPopup();
    @endif
</script>
@endpush
@endsection
