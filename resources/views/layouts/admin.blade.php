<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Service Booking')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        .sidebar { min-height: 100vh; background: #212529; }
        .sidebar .nav-link { color: #adb5bd; }
        .sidebar .nav-link:hover { color: #fff; }
        .sidebar .nav-link.active { color: #fff; background: #0d6efd; border-radius: 6px; }
        .sidebar .nav-header { color: #6c757d; font-size: 0.75rem; text-transform: uppercase; padding: 0.5rem 1rem; }
        .main-content { background: #f8f9fa; min-height: 100vh; }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">

        {{-- SIDEBAR --}}
        <div class="col-md-2 sidebar p-3">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none">
                <h5 class="mb-4">Admin Panel</h5>
            </a>

            <div class="nav-header">Main</div>
            <ul class="nav flex-column mb-3">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.map') }}"
                        class="nav-link {{ request()->routeIs('admin.map') ? 'active' : '' }}">
                        Peta Provider
                    </a>
                </li>
            </ul>

            <div class="nav-header">Kelola</div>
            <ul class="nav flex-column mb-3">
                <li class="nav-item">
                    <a href="{{ route('admin.bookings.index') }}"
                        class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        Booking
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.services.index') }}"
                        class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        Service
                    </a>
                </li>
            </ul>

            <div class="nav-header">Akun</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('user.profile') }}" class="nav-link">Profil</a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="nav-link btn btn-link text-danger">Logout</button>
                    </form>
                </li>
            </ul>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-md-10 main-content p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@stack('scripts')
</body>
</html>
