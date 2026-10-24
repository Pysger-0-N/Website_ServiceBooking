<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\BookingModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $query = BookingModel::where('provider_id', auth()->id());
        
        $bookings = $query->with('service', 'customer')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'pending'     => BookingModel::where('provider_id', auth()->id())->where('status', 'pending')->count(),
            'confirmed'   => BookingModel::where('provider_id', auth()->id())->where('status', 'confirmed')->count(),
            'on_progress' => BookingModel::where('provider_id', auth()->id())->where('status', 'on_progress')->count(),
            'done'        => BookingModel::where('provider_id', auth()->id())->where('status', 'done')->count(),
        ];

        return view('provider.dashboard', compact('bookings', 'stats'));
    }

    public function bookings()
    {
        $bookings = BookingModel::where('provider_id', auth()->id())
            ->with('service', 'customer')
            ->latest()
            ->get();

        return view('provider.bookings', compact('bookings'));
    }

    public function confirm(BookingModel $booking)
    {
        abort_if($booking->provider_id !== auth()->id(), 403);
        $booking->update(['status' => 'confirmed']);
        return redirect()->route('provider.bookings')->with('success', 'Booking dikonfirmasi!');
    }

    public function reject(BookingModel $booking)
    {
        abort_if($booking->provider_id !== auth()->id(), 403);
        $booking->update(['status' => 'cancelled']);
        return redirect()->route('provider.bookings')->with('success', 'Booking ditolak.');
    }

    public function done(BookingModel $booking)
    {
        abort_if($booking->provider_id !== auth()->id(), 403);
        $booking->update(['status' => 'done']);
        return redirect()->route('provider.bookings')->with('success', 'Booking selesai!');
    }
}

