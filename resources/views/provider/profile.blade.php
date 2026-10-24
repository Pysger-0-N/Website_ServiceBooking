@extends('layouts.app')
@section('title', 'Profil Provider')

@section('content')
<div class="container py-4">
    <h2>Profil Provider</h2>
    <a href="{{ route('provider.dashboard') }}" class="btn btn-secondary btn-sm mb-3">Dashboard</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('provider.profile.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Bio / Deskripsi Diri</label>
            <textarea name="bio" class="form-control" rows="4"
                placeholder="Ceritakan tentang dirimu, keahlian, dan pengalamanmu...">{{ old('bio', $profile->bio ?? '') }}</textarea>
        </div>


        <div class="mb-3">
            <label class="form-label">Pengalaman (tahun)</label>
            <input type="number" name="experience_years" class="form-control" min="0" max="50"
                value="{{ old('experience_years', $profile->experience_years ?? 0) }}" required>
        </div>


        <div class="mb-3">
            <label class="form-label">Sertifikasi / Keahlian Khusus</label>
            <input type="text" name="certifications" class="form-control"
                placeholder="Contoh: Sertifikat Instalasi Listrik, Teknisi AC Daikin"
                value="{{ old('certifications', $profile->certifications ?? '') }}">
        </div>


        <div class="mb-3">
            <label class="form-label">Lokasi Kamu</label>
            <p class="text-muted small">Lokasi ini digunakan sebagai titik awal untuk menghitung jarak ke customer.</p>

            <div class="mb-2">
                <button type="button" class="btn btn-outline-primary btn-sm" id="btn-detect-provider">
                    Deteksi Lokasi Otomatis
                </button>
                <small class="text-muted ms-2">atau klik di peta</small>
            </div>

            <div id="map-provider" style="height: 550px; border: 1px solid #ccc; border-radius: 8px;"></div>

            <div class="mt-2">
                <label class="form-label">Alamat Terdeteksi</label>
                <input type="text" id="display-address-provider" class="form-control"
                    placeholder="Klik peta untuk memilih lokasi..."
                    value="{{ old('location_address', $profile->location_address ?? '') }}" readonly>
            </div>

            <input type="hidden" name="location_address" id="location_address_provider"
                value="{{ old('location_address', $profile->location_address ?? '') }}">
            <input type="hidden" name="location_lat" id="location_lat_provider"
                value="{{ old('location_lat', $profile->location_lat ?? '') }}">
            <input type="hidden" name="location_lng" id="location_lng_provider"
                value="{{ old('location_lng', $profile->location_lng ?? '') }}">
        </div>


        <div class="mb-3">
            <label class="form-label">Radius Layanan (km)</label>
            <input type="number" name="service_radius_km" class="form-control" min="1" max="100"
                value="{{ old('service_radius_km', $profile->service_radius_km ?? 10) }}" required>
        </div>


        <div class="mb-3">
            <label class="form-label">Hari Tersedia</label><br>
            @php
                $days = ['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu',
                         'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'];
                $activeDays = old('available_days', $profile->available_days ?? []);
            @endphp
            @foreach($days as $value => $label)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="available_days[]"
                        value="{{ $value }}" id="day_{{ $value }}"
                        {{ in_array($value, $activeDays) ? 'checked' : '' }}>
                    <label class="form-check-label" for="day_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach
        </div>


        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Jam Mulai</label>
                <input type="time" name="available_from" class="form-control"
                    value="{{ old('available_from', $profile->available_from ?? '08:00') }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Jam Selesai</label>
                <input type="time" name="available_until" class="form-control"
                    value="{{ old('available_until', $profile->available_until ?? '17:00') }}" required>
            </div>
        </div>


        <div class="mb-3">
            <label class="form-label">Kategori Keahlian <span class="text-danger">*</span></label>
            <p class="text-muted small">Pilih kategori layanan yang kamu kuasai. Bisa lebih dari satu.</p>
            <div class="row">
                @foreach($categories as $category)
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                            name="categories[]"
                            value="{{ $category->id }}"
                            id="cat_{{ $category->id }}"
                            {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                        <label class="form-check-label" for="cat_{{ $category->id }}">
                            <strong>{{ $category->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $category->description }}</small>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            @error('categories')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Simpan Profil</button>
    </form>
</div>


@push('scripts')
<script>
    // PROVIDER MAP INITIALIZATION
    const savedLat = {{ $profile->location_lat ?? -6.9175 }};
    const savedLng = {{ $profile->location_lng ?? 107.6191 }};

    const mapProvider = L.map('map-provider').setView([savedLat, savedLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapProvider);

    let markerProvider = null;
    @if($profile && $profile->location_lat && $profile->location_lng)
        markerProvider = L.marker([{{ $profile->location_lat }}, {{ $profile->location_lng }}]).addTo(mapProvider);
    @endif

    // SET MARKER
    function setMarkerProvider(lat, lng) {
        if (markerProvider) {
            mapProvider.removeLayer(markerProvider);
        }
        markerProvider = L.marker([lat, lng]).addTo(mapProvider);
        mapProvider.setView([lat, lng], 16);

        document.getElementById('location_lat_provider').value = lat;
        document.getElementById('location_lng_provider').value = lng;

        getAddressProvider(lat, lng);
    }

    // CLICK MAP
    mapProvider.on('contextmenu', function(e) {
        setMarkerProvider(e.latlng.lat, e.latlng.lng);
    });

    // REVERSE GEOCODING
    function getAddressProvider(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`, {
            headers: { 'Accept-Language': 'id' }
        })
        .then(res => res.json())
        .then(data => {
            const alamat = data.display_name || `${lat}, ${lng}`;
            document.getElementById('display-address-provider').value = alamat;
            document.getElementById('location_address_provider').value = alamat;
        })
        .catch(() => {
            const koordinat = `${lat}, ${lng}`;
            document.getElementById('display-address-provider').value = koordinat;
            document.getElementById('location_address_provider').value = koordinat;
        });
    }

    // AUTOMATIC DETECT LOCATION
    document.getElementById('btn-detect-provider').addEventListener('click', function() {
        if (!navigator.geolocation) {
            alert('Browser kamu tidak mendukung GPS.');
            return;
        }

        this.textContent = 'Mendeteksi...';
        this.disabled = true;
        const btn = this;

        navigator.geolocation.getCurrentPosition(
            function(position) {
                setMarkerProvider(position.coords.latitude, position.coords.longitude);
                btn.textContent = 'Deteksi Lokasi Otomatis';
                btn.disabled = false;
            },
            function() {
                alert('Gagal deteksi lokasi. Pastikan izin lokasi diaktifkan.');
                btn.textContent = 'Deteksi Lokasi Otomatis';
                btn.disabled = false;
            }
        );
    });
</script>
@endpush

@endsection
