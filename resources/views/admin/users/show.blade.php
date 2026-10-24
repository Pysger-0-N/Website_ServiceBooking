@extends('layouts.admin')
@section('title', 'Detail User')

@section('content')
<div class="container py-4">
    <h2>Detail User: {{ $user->name }}</h2>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm mb-3">Kembali</a>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Info Akun</h5>
            <p>Nama: <strong>{{ $user->name }}</strong></p>
            <p>Email: <strong>{{ $user->email }}</strong></p>
            <p>Role: <span class="badge bg-secondary">{{ $user->role }}</span></p>
            <p>Phone: {{ $user->phone ?? '-' }}</p>
            <p>Alamat: {{ $user->address ?? '-' }}</p>
        </div>
    </div>

    @if($user->role === 'provider' && $user->providerProfile)
    <div class="card mb-3">
        <div class="card-body">
            <h5>Profil Provider</h5>
            <p>Bio: {{ $user->providerProfile->bio ?? '-' }}</p>
            <p>Pengalaman: {{ $user->providerProfile->experience_years }} tahun</p>
            <p>Sertifikasi: {{ $user->providerProfile->certifications ?? '-' }}</p>
            <p>Rate: Rp {{ number_format($user->providerProfile->hourly_rate ?? 0, 0, ',', '.') }} / jam</p>
            <p>Radius: {{ $user->providerProfile->service_radius_km }} km</p>
            <p>Status: <span class="badge bg-success">{{ $user->providerProfile->status }}</span></p>
            <p>Kategori Keahlian:
                @foreach($user->providerCategories as $cat)
                    <span class="badge bg-info">{{ $cat->name }}</span>
                @endforeach
            </p>
        </div>
    </div>
    @endif

    @if($user->role === 'provider')
    <div class="card mb-3">
        <div class="card-body">
            <h5>Riwayat Booking sebagai Provider ({{ $user->bookingsAsProvider->count() }})</h5>
            @if($user->bookingsAsProvider->isEmpty())
                <p class="text-muted">Belum ada booking.</p>
            @else
            <table class="table table-sm">
                <thead><tr><th>#</th><th>Customer</th><th>Layanan</th><th>Tanggal</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($user->bookingsAsProvider as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->customer->name }}</td>
                        <td>{{ $booking->service->name }}</td>
                        <td>{{ $booking->booking_date }}</td>
                        <td>{!! statusBadge($booking->status) !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
