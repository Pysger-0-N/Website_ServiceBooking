<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BookingModel;
use App\Models\ReviewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $bookings = BookingModel::where('customer_id', Auth::id())
            ->with('service', 'provider')
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('bookings'));
    }

    public function bookings()
    {
        $bookings = BookingModel::where('customer_id', Auth::id())
            ->with('service', 'provider')
            ->latest()
            ->get();

        return view('customer.bookings', compact('bookings'));
    }

    public function show(BookingModel $booking)
    {
        abort_if($booking->customer_id !== Auth::id(), 403);
        $booking->load('service', 'provider', 'review');
        return view('customer.booking-show', compact('booking'));
    }

    public function cancel(BookingModel $booking)
    {
        abort_if($booking->customer_id !== Auth::id(), 403);
        abort_if(!in_array($booking->status, ['pending', 'confirmed']), 400);

        $booking->update(['status' => 'cancelled']);
        return redirect()->route('customer.bookings')->with('success', 'Booking berhasil dibatalkan.');
    }

    public function review(Request $request, BookingModel $booking)
    {
        abort_if($booking->customer_id !== Auth::id(), 403);
        abort_if($booking->status !== 'done', 400);
        abort_if($booking->review, 400); // sudah pernah review

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        ReviewModel::create([
            'booking_id'  => $booking->id,
            'customer_id' => Auth::id(),
            'provider_id' => $booking->provider_id,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
        ]);

        return redirect()->route('customer.bookings.show', $booking)->with('success', 'Review berhasil dikirim!');
    }
}

