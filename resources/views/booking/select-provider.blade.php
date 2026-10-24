@extends('layouts.app')
@section('title', 'Pilih Provider')

@section('content')
<div class="container py-4">
    <h2>Pilih Provider {{ $service->name }}</h2>
    <a href="{{ route('booking.select-service', $service->category) }}" class="btn btn-secondary btn-sm mb-3">Kembali</a>

    <div class="alert alert-info">
        Harga layanan: <strong>Rp {{ number_format($service->base_price, 0, ',', '.') }}</strong> / {{ $service->unit }}
        Pilih provider berdasarkan keahlian & lokasi terdekat!
    </div>

    @if($providers->isEmpty())
        <div class="alert alert-warning">Belum ada provider tersedia untuk layanan ini.</div>
    @else

    {{-- MAP PROVIDER --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Sebaran Lokasi Provider</strong>
            <small class="text-muted ms-2">Klik marker merah untuk info provider</small>
        </div>
        <div class="card-body p-0">
            <div id="map-providers" style="height: 400px; width: 100%;"></div>
        </div>
        <div class="card-footer">
            <small class="text-muted">
                Lokasi kamu &nbsp;|&nbsp;
                Lokasi provider &nbsp;|&nbsp;
                Radius layanan provider
            </small>
        </div>
    </div>

    {{-- LIST PROVIDER --}}
    <h5 class="mb-3">Daftar Provider Tersedia ({{ $providers->count() }})</h5>
    <div class="row">
        @foreach($providers as $provider)
        <div class="col-md-4 mb-3">
            <div class="card h-100" id="card-provider-{{ $provider->id }}">
                <div class="card-body">
                    <h5>{{ $provider->name }}</h5>
                    <hr>
                    <p>{{ $provider->providerProfile->bio ?? 'Belum ada deskripsi' }}</p>
                    <p>Pengalaman: <strong>{{ $provider->providerProfile->experience_years ?? 0 }} tahun</strong></p>
                    <p>Keahlian: {{ $provider->providerProfile->certifications ?? '-' }}</p>
                    <p>Radius: <strong>{{ $provider->providerProfile->service_radius_km ?? 0 }} km</strong></p>
                    <p>Tersedia:
                        <strong>
                            {{ $provider->providerProfile->available_from ?? '-' }}
                            —
                            {{ $provider->providerProfile->available_until ?? '-' }}
                        </strong>
                    </p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('booking.form', [$service, $provider]) }}" class="btn btn-primary w-100">
                        Pilih Provider Ini
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // DATA PROVIDER
    const providersData = @json($providersMapData);

    // INISIALISASI PETA
    const map = L.map('map-providers').setView([-6.9175, 107.6191], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // MARKER CUSTOMER
    let customerMarker = null;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Marker biru
            const blueIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
            });

            customerMarker = L.marker([lat, lng], { icon: blueIcon })
                .addTo(map)
                .bindPopup('<strong>Lokasi Kamu</strong>')
                .openPopup();

            map.setView([lat, lng], 13);
        });
    }

    // MARKER PROVIDER
    const redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
    });

    providersData.forEach(function(provider) {
        // Marker merah
        const marker = L.marker([provider.lat, provider.lng], { icon: redIcon })
            .addTo(map)
            .bindPopup(`
                <strong>${provider.name}</strong><br>
                Pengalaman: ${provider.experience} tahun<br>
                Radius: ${provider.radius} km<br>
                Tersedia: ${provider.available}<br>
                <br>
                <a href="${provider.url}" class="btn btn-primary btn-sm w-100">Pilih Provider Ini</a>
            `);

        // Lingkaran radius
        L.circle([provider.lat, provider.lng], {
            radius: provider.radius * 1000,
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.05,
            weight: 1,
        }).addTo(map);

        marker.on('click', function() {
            document.querySelectorAll('[id^="card-provider-"]').forEach(el => {
                el.classList.remove('border-danger', 'border-2');
            });
            const card = document.getElementById(`card-provider-${provider.id}`);
            if (card) {
                card.classList.add('border-danger', 'border-2');
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });

    if (providersData.length > 0) {
        const bounds = providersData.map(p => [p.lat, p.lng]);
        map.fitBounds(bounds, { padding: [50, 50] });
    }
</script>
@endpush
