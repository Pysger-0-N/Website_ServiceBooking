@extends('layouts.admin')
@section('title', 'Kelola Booking')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Kelola Booking</h2>
</div>

FILTER
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    @foreach(['pending','confirmed','on_progress','done','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Provider</th>
                    <th>Layanan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->customer->name }}</td>
                    <td>{{ $booking->provider->name }}</td>
                    <td>{{ $booking->service->name }}</td>
                    <td>{{ $booking->booking_date }}</td>
                    <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td>{!! statusBadge($booking->status) !!}</td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-info">Detail</a>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Belum ada booking.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $bookings->withQueryString()->links() }}
    </div>
</div>
@endsection
