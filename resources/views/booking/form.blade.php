@extends('layouts.app')
@section('title', 'Detail Booking')

@section('content')
<div class="container py-4">
    <h2>Detail Booking</h2>
    <a href="{{ route('booking.select-provider', $service) }}" class="btn btn-secondary btn-sm mb-3">Kembali</a>

    <div class="card mb-3">
        <div class="card-body">
            <h6>Ringkasan:</h6>
            <p>Layanan: <strong>{{ $service->name }}</strong></p>
            <p>Provider: <strong>{{ $provider->name }}</strong></p>
            <p>Harga: <strong>Rp {{ number_format($service->base_price, 0, ',', '.') }}</strong> / {{ $service->unit }}</p>
            <p>Jam Tersedia: <strong>{{ $availableFrom }} — {{ $availableUntil }}</strong></p>
            <p>Hari Tersedia:
                @foreach($availableDays as $day)
                    <span class="badge bg-info">{{ ucfirst($day) }}</span>
                @endforeach
            </p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('booking.store') }}" method="POST">
        @csrf
        <input type="hidden" name="service_id" value="{{ $service->id }}">
        <input type="hidden" name="provider_id" value="{{ $provider->id }}">

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="booking_date" id="booking_date" class="form-control"
                min="{{ date('Y-m-d') }}" value="{{ old('booking_date') }}" required>
            <small id="day-warning" class="text-danger d-none">Provider tidak tersedia di hari ini!</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Waktu</label>
            <input type="time" name="booking_time" id="booking_time" class="form-control"
                min="{{ $availableFrom }}" max="{{ $availableUntil }}"
                value="{{ old('booking_time') }}" required>
            <small class="text-muted">Jam tersedia: {{ $availableFrom }} — {{ $availableUntil }}</small>
        </div>

        {{-- LOCATION --}}
        <div class="mb-3">
            <label class="form-label">Lokasi Kamu</label>
            <div class="mb-2">
                <button type="button" class="btn btn-outline-primary btn-sm" id="btn-detect">
                    Deteksi Lokasi Otomatis
                </button>
                <small class="text-muted ms-2">atau klik langsung di peta</small>
            </div>
            <div id="map" style="height: 350px; border: 1px solid #ccc; border-radius: 8px;"></div>
            <div class="mt-2">
                <label class="form-label">Alamat Terdeteksi</label>
                <input type="text" id="display-address" class="form-control"
                    placeholder="Klik peta untuk memilih lokasi..." readonly>
            </div>
            <small id="radius-warning" class="text-danger d-none"></small>
            <input type="hidden" name="location_address" id="location_address" value="{{ old('location_address') }}" required>
            <input type="hidden" name="location_lat" id="location_lat" value="{{ old('location_lat') }}">
            <input type="hidden" name="location_lng" id="location_lng" value="{{ old('location_lng') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan Tambahan (opsional)</label>
            <textarea name="notes" class="form-control" rows="2"
                placeholder="Contoh: Tolong bawa peralatan sendiri">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success" id="btn-submit">Konfirmasi Booking</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // DATA PROVIDER DARI BLADE
    const providerLat    = {{ $provider->providerProfile->location_lat ?? 'null' }};
    const providerLng    = {{ $provider->providerProfile->location_lng ?? 'null' }};
    const providerRadius = {{ $provider->providerProfile->service_radius_km ?? 10 }};
    const availableDays  = @json($availableDays);
    const availableFrom  = '{{ $availableFrom }}';
    const availableUntil = '{{ $availableUntil }}';

    // PETA
    const defaultLat = providerLat ?? -6.9175;
    const defaultLng = providerLng ?? 107.6191;

    const map = L.map('map').setView([defaultLat, defaultLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Marker & lingkaran radius provider
    if (providerLat && providerLng) {
        const providerIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34],
        });
        L.marker([providerLat, providerLng], { icon: providerIcon })
            .addTo(map)
            .bindPopup('Lokasi Provider: {{ $provider->name }}');

        L.circle([providerLat, providerLng], {
            radius: providerRadius * 1000,
            color: 'red', fillColor: '#f03', fillOpacity: 0.05, weight: 1,
        }).addTo(map);
    }

    // Marker customer
    let customerMarker = null;

    function setMarker(lat, lng) {
        if (customerMarker) map.removeLayer(customerMarker);

        const blueIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34],
        });

        customerMarker = L.marker([lat, lng], { icon: blueIcon }).addTo(map);
        map.setView([lat, lng], 14);

        document.getElementById('location_lat').value = lat;
        document.getElementById('location_lng').value = lng;

        getAddress(lat, lng);
        checkRadius(lat, lng);
    }

    map.on('click', function(e) {
        setMarker(e.latlng.lat, e.latlng.lng);
    });

    // REVERSE GEOCODING
    function getAddress(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`, {
            headers: { 'Accept-Language': 'id' }
        })
        .then(res => res.json())
        .then(data => {
            const alamat = data.display_name || `${lat}, ${lng}`;
            document.getElementById('display-address').value = alamat;
            document.getElementById('location_address').value = alamat;
        })
        .catch(() => {
            document.getElementById('display-address').value = `${lat}, ${lng}`;
            document.getElementById('location_address').value = `${lat}, ${lng}`;
        });
    }

    // CEK RADIUS
    function checkRadius(lat, lng) {
        if (!providerLat || !providerLng) return;

        const distance = calculateDistance(providerLat, providerLng, lat, lng);
        const warning  = document.getElementById('radius-warning');
        const btnSubmit = document.getElementById('btn-submit');

        if (distance > providerRadius) {
            warning.textContent = `Lokasi kamu (${distance} km) diluar radius layanan provider (${providerRadius} km).`;
            warning.classList.remove('d-none');
            btnSubmit.disabled = true;
        } else {
            warning.textContent = `Lokasi kamu (${distance} km) dalam radius layanan provider.`;
            warning.classList.remove('d-none');
            warning.classList.remove('text-danger');
            warning.classList.add('text-success');
            btnSubmit.disabled = false;
        }
    }

    // HAVERSINE FORMULA (JS)
    function calculateDistance(lat1, lng1, lat2, lng2) {
        const R    = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a    = Math.sin(dLat/2) * Math.sin(dLat/2) +
                     Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                     Math.sin(dLng/2) * Math.sin(dLng/2);
        return Math.round(6371 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)) * 100) / 100;
    }

    // DETEKSI LOKASI OTOMATIS
    document.getElementById('btn-detect').addEventListener('click', function() {
        if (!navigator.geolocation) { alert('Browser tidak support GPS.'); return; }
        this.textContent = 'Mendeteksi...';
        this.disabled = true;
        const btn = this;
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                setMarker(pos.coords.latitude, pos.coords.longitude);
                btn.textContent = 'Deteksi Lokasi Otomatis';
                btn.disabled = false;
            },
            function() {
                alert('Gagal deteksi lokasi.');
                btn.textContent = 'Deteksi Lokasi Otomatis';
                btn.disabled = false;
            }
        );
    });

    // VALIDASI HARI CLIENT SIDE
    document.getElementById('booking_date').addEventListener('change', function() {
        const date    = new Date(this.value);
        const dayName = date.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
        const warning = document.getElementById('day-warning');

        if (availableDays.length > 0 && !availableDays.includes(dayName)) {
            warning.classList.remove('d-none');
            document.getElementById('btn-submit').disabled = true;
        } else {
            warning.classList.add('d-none');
            document.getElementById('btn-submit').disabled = false;
        }
    });
</script>
@endpush
