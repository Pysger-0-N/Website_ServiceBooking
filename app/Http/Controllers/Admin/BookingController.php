<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingModel;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = BookingModel::with('customer', 'provider', 'service');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by tanggal
        if ($request->date_from) {
            $query->where('booking_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->where('booking_date', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate(20);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(BookingModel $booking)
    {
        $booking->load('customer', 'provider', 'service', 'review');
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, BookingModel $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,on_progress,done,cancelled',
        ]);

        $booking->update(['status' => $request->status]);
        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Status booking berhasil diupdate!');
    }

}
