@extends('layouts.admin')
@section('title', 'Peta Sebaran Provider')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Peta Sebaran Provider</h2>
    <span class="badge bg-primary fs-6">{{ count($providers) }} Provider Aktif</span>
</div>

<div class="card">
    <div class="card-body p-0">
        <div id="map-admin" style="height: 600px; width: 100%;"></div>
    </div>
    <div class="card-footer">
        <small class="text-muted">
            Titik merah = lokasi provider &nbsp;|&nbsp;
            Lingkaran = radius layanan &nbsp;|&nbsp;
            Klik marker untuk detail provider
        </small>
    </div>
</div>
@endsection


@push('scripts')
<script>
    const providers = @json($providers);

    const map = L.map('map-admin').setView([-6.9175, 107.6191], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34],
    });

    if (providers.length === 0) {
        map.setView([-6.9175, 107.6191], 11);
    } else {
        const bounds = providers.map(p => [p.lat, p.lng]);

        providers.forEach(function(p) {
            L.marker([p.lat, p.lng], { icon: redIcon })
                .addTo(map)
                .bindPopup(`
                    <strong>${p.name}</strong><br>
                    ${p.categories || '-'}<br>
                    Pengalaman: ${p.experience} tahun<br>
                    Radius: ${p.radius} km<br>
                    Tersedia: ${p.available}<br>
                    <br>
                    
                `);

            L.circle([p.lat, p.lng], {
                radius: p.radius * 1000,
                color: 'red', fillColor: '#f03', fillOpacity: 0.05, weight: 1,
            }).addTo(map);
        });

        map.fitBounds(bounds, { padding: [50, 50] });
    }
</script>
@endpush
