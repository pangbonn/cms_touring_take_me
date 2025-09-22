<?php

namespace App\Http\Controllers;

use App\Models\TripCancellation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TripCancellationController extends Controller
{
    /**
     * Display a listing of trip cancellations
     */
    public function index()
    {
        $trips = TripCancellation::with('creator')
            ->orderBy('trip_date', 'desc')
            ->paginate(10);
            
        $defaultConditions = TripCancellation::getDefaultCancellationConditions();
        return view('trip-cancellations.index', compact('trips', 'defaultConditions'));
    }

    /**
     * Show the form for creating a new trip cancellation
     */
    public function create()
    {
        $defaultConditions = TripCancellation::getDefaultCancellationConditions();
        return view('trip-cancellations.create', compact('defaultConditions'));
    }

    /**
     * Store a newly created trip cancellation
     */
    public function store(Request $request)
    {
        $request->validate([
            'trip_name' => 'required|string|max:255',
            'trip_description' => 'nullable|string|max:1000',
            'trip_date' => 'required|date|after:today',
            'trip_price' => 'required|numeric|min:0',
            'min_participants' => 'required|integer|min:1',
            'max_participants' => 'nullable|integer|min:1|gte:min_participants',
            'cancellation_type' => 'required|in:automatic,manual',
            'refund_policy' => 'nullable|string|max:2000',
        ]);

        $trip = TripCancellation::create([
            'trip_name' => $request->trip_name,
            'trip_description' => $request->trip_description,
            'trip_date' => $request->trip_date,
            'trip_price' => $request->trip_price,
            'min_participants' => $request->min_participants,
            'max_participants' => $request->max_participants,
            'cancellation_type' => $request->cancellation_type,
            'cancellation_conditions' => $request->cancellation_conditions ?? TripCancellation::getDefaultCancellationConditions(),
            'refund_policy' => $request->refund_policy,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'สร้างทริปเรียบร้อยแล้ว',
            'redirect' => route('trip-cancellations.index')
        ]);
    }

    /**
     * Display the specified trip cancellation
     */
    public function show(TripCancellation $tripCancellation)
    {
        $tripCancellation->load('creator');
        return view('trip-cancellations.show', compact('tripCancellation'));
    }

    /**
     * Show the form for editing the specified trip cancellation
     */
    public function edit(TripCancellation $tripCancellation)
    {
        $defaultConditions = TripCancellation::getDefaultCancellationConditions();
        return view('trip-cancellations.edit', compact('tripCancellation', 'defaultConditions'));
    }

    /**
     * Update the specified trip cancellation
     */
    public function update(Request $request, TripCancellation $tripCancellation)
    {
        try {
            $request->validate([
                'trip_name' => 'required|string|max:255',
                'trip_description' => 'nullable|string|max:1000',
                'trip_date' => 'required|date|after:today',
                'trip_price' => 'required|numeric|min:0',
                'min_participants' => 'required|integer|min:1',
                'max_participants' => 'nullable|integer|min:1|gte:min_participants',
                'cancellation_type' => 'required|in:automatic,manual',
                'refund_policy' => 'nullable|string|max:2000',
            ]);

            $tripCancellation->update([
                'trip_name' => $request->trip_name,
                'trip_description' => $request->trip_description,
                'trip_date' => $request->trip_date,
                'trip_price' => $request->trip_price,
                'min_participants' => $request->min_participants,
                'max_participants' => $request->max_participants,
                'cancellation_type' => $request->cancellation_type,
                'cancellation_conditions' => $request->cancellation_conditions ?? TripCancellation::getDefaultCancellationConditions(),
                'refund_policy' => $request->refund_policy,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตทริปเรียบร้อยแล้ว'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified trip cancellation
     */
    public function destroy(TripCancellation $tripCancellation)
    {
        try {
            $tripCancellation->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'ลบทริปเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle trip status (active/cancelled)
     */
    public function toggleStatus(TripCancellation $tripCancellation)
    {
        try {
            if ($tripCancellation->status === 'active') {
                $tripCancellation->update(['status' => 'cancelled']);
                $message = 'ยกเลิกทริปเรียบร้อยแล้ว';
            } else {
                $tripCancellation->update(['status' => 'active']);
                $message = 'เปิดใช้งานทริปเรียบร้อยแล้ว';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_status' => $tripCancellation->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cancellation conditions for a trip
     */
    public function getCancellationConditions(TripCancellation $tripCancellation)
    {
        return response()->json([
            'success' => true,
            'conditions' => $tripCancellation->cancellation_conditions ?? TripCancellation::getDefaultCancellationConditions()
        ]);
    }
}
