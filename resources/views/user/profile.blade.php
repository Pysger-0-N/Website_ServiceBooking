@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="container py-4">
    <h2>Profil Saya</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- PROFIL INFORMATION --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Informasi Akun</strong>
            <span class="badge bg-secondary ms-2">{{ ucfirst($user->role) }}</span>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="phone" class="form-control"
                        placeholder="Contoh: 08123456789"
                        value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="3"
                        placeholder="Alamat lengkap kamu">{{ old('address', $user->address) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    {{-- CHANGE PASSWORD --}}
    <div class="card">
        <div class="card-header"><strong>Ganti Password</strong></div>
        <div class="card-body">
            <form action="{{ route('user.profile.password') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning">Ganti Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
