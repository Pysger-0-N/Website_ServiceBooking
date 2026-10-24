@extends('layouts.admin')
@section('title', 'Kelola User')

@section('content')
<div class="container py-4">
    <h2>Kelola User</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm mb-3">Dashboard</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status Provider</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                <td>
                    @if($user->role === 'provider')
                        @php $status = $user->providerProfile->status ?? 'pending' @endphp
                        <span class="badge {{ $status === 'active' ? 'bg-success' : ($status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                            {{ $status }}
                        </span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">Detail</a>

                    @if($user->role === 'provider')
                        <form action="{{ route('admin.users.verify-provider', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @php $status = $user->providerProfile->status ?? 'pending' @endphp
                            <button class="btn btn-sm {{ $status === 'active' ? 'btn-warning' : 'btn-success' }}">
                                {{ $status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    @endif

                    @if($user->role !== 'admin')
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus user ini?')">Hapus</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
