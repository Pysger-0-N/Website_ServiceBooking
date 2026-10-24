<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingModel;
use App\Models\CategoryModel;
use App\Models\ServiceModel;
use App\Models\UserModel;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'     => UserModel::where('role', 'customer')->count(),
            'total_providers' => UserModel::where('role', 'provider')->count(),
            'total_bookings'  => BookingModel::count(),
            'total_revenue'   => BookingModel::where('status', 'done')->sum('total_price'),
            'pending'         => BookingModel::where('status', 'pending')->count(),
            'done'            => BookingModel::where('status', 'done')->count(),
        ];

        $latestBookings = BookingModel::with('customer', 'provider', 'service')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestBookings'));
    }

    public function map()
    {
        $providers = UserModel::where('role', 'provider')
            ->whereHas('providerProfile', function ($q) {
                $q->where('status', 'active')
                  ->whereNotNull('location_lat')
                  ->whereNotNull('location_lng');
            })
            ->with('providerProfile', 'providerCategories')
            ->get()
            ->map(fn($p) => [
                'id'         => $p->id,
                'name'       => $p->name,
                'bio'        => $p->providerProfile->bio ?? '-',
                'experience' => $p->providerProfile->experience_years ?? 0,
                'radius'     => $p->providerProfile->service_radius_km ?? 0,
                'status'     => $p->providerProfile->status,
                'categories' => $p->providerCategories->pluck('name')->join(', '),
                'available'  => ($p->providerProfile->available_from ?? '-') . ' - ' . ($p->providerProfile->available_until ?? '-'),
                'lat'        => $p->providerProfile->location_lat,
                'lng'        => $p->providerProfile->location_lng,
                'url'        => route('admin.users.show', ['user' => $p->id]),
            ]);

        return view('admin.map', compact('providers'));
    }
}
