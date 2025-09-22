<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Trip;
use App\Models\TripSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Store a newly created booking.
     */
    public function store(Request $request): JsonResponse
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'tripId' => 'required|exists:trips,id',
            'customer.name' => 'required|string|max:255',
            'customer.phone' => 'required|string|max:20',
            'customer.email' => 'required|email|max:255',
            'customer.lineId' => 'nullable|string|max:100',
            'bookingDetails.guests' => 'required|integer|min:1',
            'bookingDetails.dateSlot.id' => 'required|exists:trip_schedules,id',
            'bookingDetails.notes' => 'nullable|string',
            'bookingDetails.totalPrice' => 'required|numeric|min:0',
            'bookingDetails.bookingDate' => 'required|date',
            'source' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get trip and schedule data
            $trip = Trip::findOrFail($request->tripId);
            $schedule = TripSchedule::findOrFail($request->bookingDetails['dateSlot']['id']);

            // Check if schedule belongs to the trip
            if ($schedule->trip_id != $trip->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ตารางเวลาที่เลือกไม่ตรงกับทริปที่เลือก'
                ], 400);
            }

            // Check if schedule is active
            if (!$schedule->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'ตารางเวลาที่เลือกไม่สามารถจองได้'
                ], 400);
            }

            // Check if there are enough slots available
            $currentBookings = Booking::where('trip_schedule_id', $schedule->id)
                ->where('status', '!=', 'cancelled')
                ->sum('guests');

            // if (($currentBookings + $request->bookingDetails['guests']) > $schedule->max_participants) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'จำนวนผู้เข้าร่วมเกินจำนวนที่กำหนด'
            //     ], 400);
            // }

            // Create booking
            $booking = Booking::create([
                'booking_id' => Booking::generateBookingId(),
                'trip_id' => $trip->id,
                'trip_schedule_id' => $schedule->id,
                'customer_name' => $request->customer['name'],
                'customer_phone' => $request->customer['phone'],
                'customer_email' => $request->customer['email'],
                'customer_line_id' => $request->customer['lineId'] ?? null,
                'guests' => $request->bookingDetails['guests'],
                'notes' => $request->bookingDetails['notes'] ?? null,
                'total_price' => $request->bookingDetails['totalPrice'],
                'booking_date' => $request->bookingDetails['bookingDate'],
                'status' => 'pending',
                'source' => $request->source ?? 'web_booking'
            ]);

            // Load relationships for response
            $booking->load(['trip', 'tripSchedule']);

            // Format response according to the required JSON structure
            $response = [
                'bookingId' => $booking->booking_id,
                'tripId' => $booking->trip_id,
                'tripTitle' => $booking->trip->name,
                'customer' => [
                    'name' => $booking->customer_name,
                    'phone' => $booking->customer_phone,
                    'email' => $booking->customer_email,
                    'lineId' => $booking->customer_line_id
                ],
                'bookingDetails' => [
                    'guests' => $booking->guests,
                    'dateSlot' => [
                        'id' => $booking->tripSchedule->id,
                        'departure_date' => $booking->tripSchedule->departure_date->toISOString(),
                        'return_date' => $booking->tripSchedule->return_date ? $booking->tripSchedule->return_date->toISOString() : null,
                        'departure_date_thai' => $booking->tripSchedule->departure_date->locale('th')->isoFormat('DD MMMM YYYY'),
                        'return_date_thai' => $booking->tripSchedule->return_date ? $booking->tripSchedule->return_date->locale('th')->isoFormat('DD MMMM YYYY') : null,
                        'duration' => $this->calculateDuration($booking->tripSchedule->departure_date, $booking->tripSchedule->return_date),
                        'max_participants' => $booking->tripSchedule->max_participants,
                        'price' => number_format($booking->tripSchedule->price, 2),
                        'is_active' => $booking->tripSchedule->is_active,
                        'created_at' => $booking->tripSchedule->created_at->toISOString(),
                        'updated_at' => $booking->tripSchedule->updated_at->toISOString()
                    ],
                    'notes' => $booking->notes,
                    'totalPrice' => $booking->total_price,
                    'bookingDate' => $booking->booking_date->toISOString()
                ],
                'status' => $booking->status,
                'source' => $booking->source
            ];

            return response()->json([
                'success' => true,
                'message' => 'จองสำเร็จ',
                'data' => $response
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการจอง',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(string $bookingId): JsonResponse
    {
        try {
            $booking = Booking::where('booking_id', $bookingId)
                ->with(['trip', 'tripSchedule'])
                ->firstOrFail();

            $response = [
                'bookingId' => $booking->booking_id,
                'tripId' => $booking->trip_id,
                'tripTitle' => $booking->trip->name,
                'customer' => [
                    'name' => $booking->customer_name,
                    'phone' => $booking->customer_phone,
                    'email' => $booking->customer_email,
                    'lineId' => $booking->customer_line_id
                ],
                'bookingDetails' => [
                    'guests' => $booking->guests,
                    'dateSlot' => [
                        'id' => $booking->tripSchedule->id,
                        'departure_date' => $booking->tripSchedule->departure_date->toISOString(),
                        'return_date' => $booking->tripSchedule->return_date ? $booking->tripSchedule->return_date->toISOString() : null,
                        'departure_date_thai' => $booking->tripSchedule->departure_date->locale('th')->isoFormat('DD MMMM YYYY'),
                        'return_date_thai' => $booking->tripSchedule->return_date ? $booking->tripSchedule->return_date->locale('th')->isoFormat('DD MMMM YYYY') : null,
                        'duration' => $this->calculateDuration($booking->tripSchedule->departure_date, $booking->tripSchedule->return_date),
                        'max_participants' => $booking->tripSchedule->max_participants,
                        'price' => number_format($booking->tripSchedule->price, 2),
                        'is_active' => $booking->tripSchedule->is_active,
                        'created_at' => $booking->tripSchedule->created_at->toISOString(),
                        'updated_at' => $booking->tripSchedule->updated_at->toISOString()
                    ],
                    'notes' => $booking->notes,
                    'totalPrice' => $booking->total_price,
                    'bookingDate' => $booking->booking_date->toISOString()
                ],
                'status' => $booking->status,
                'source' => $booking->source
            ];

            return response()->json([
                'success' => true,
                'data' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลการจอง'
            ], 404);
        }
    }

    /**
     * Calculate duration between departure and return dates
     */
    private function calculateDuration($departureDate, $returnDate): string
    {
        if (!$returnDate) {
            return '1 วัน';
        }

        $departure = Carbon::parse($departureDate);
        $return = Carbon::parse($returnDate);
        $days = $departure->diffInDays($return) + 1;

        if ($days == 1) {
            return '1 วัน';
        } else {
            return $days . ' วัน ' . ($days - 1) . ' คืน';
        }
    }
}
