<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Show the booking form.
     */
    public function create()
    {
        return view('bookings.create');
    }

    /**
     * Display a listing of bookings for admin.
     */
    public function index()
    {
        $bookings = Booking::with(['trip', 'tripSchedule'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        $booking->load(['trip', 'tripSchedule']);
        
        return view('bookings.show', compact('booking'));
    }

    /**
     * Update the status of the specified booking.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $booking->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'อัปเดตสถานะการจองเรียบร้อยแล้ว');
    }
}
