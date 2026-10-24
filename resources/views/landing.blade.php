@extends('layouts.app')
@section('title', 'Home - Service Booking')

@section('content')

{{-- HEADER --}}
<section id="hero" class="py-5 bg-primary text-white text-center">
    <div class="container">
        <h1>Temukan Jasa Terbaik di Sekitarmu</h1>
        <p class="lead">Booking layanan profesional dengan mudah, cepat, dan terpercaya.</p>
        @auth
            <a href="{{ route('booking.select-category') }}" class="btn btn-light btn-lg">Pesan Sekarang</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-light btn-lg">Login untuk Memesan</a>
        @endauth
    </div>
</section>

{{-- CONTENT --}}
<section id="services" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Kategori Layanan</h2>
        <div class="row">
            @foreach($categories as $category)
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text">{{ $category->description }}</p>
                        <p class="text-muted">{{ $category->services_count }} layanan tersedia</p>
                        @auth
                            <a href="{{ route('booking.select-service', $category) }}" class="btn btn-primary btn-sm">Pilih</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login untuk Pesan</a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FOOTER --}}
<section id="about" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Tentang ServiceBook</h2>
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <p>ServiceBook adalah platform yang menghubungkan pelanggan dengan para profesional jasa terpercaya. semua bisa ditemukan di sini.</p>
            </div>
        </div>
    </div>
</section>

@endsection
