<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trips = Trip::with(['schedules' => function($query) {
            $query->where('is_active', true)->orderBy('departure_date', 'desc');
        }])->orderBy('created_at', 'desc')->get();
        return view('trips.index', compact('trips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trips.create');
    }

    /**
     * Show the test form for creating a new resource.
     */
    public function testCreate()
    {
        return view('trips.test-create');
    }

    /**
     * Show the calendar view of trips.
     */
    public function calendar()
    {
        $trips = Trip::with(['schedules' => function($query) {
            $query->where('is_active', true)->orderBy('departure_date', 'asc');
        }])->where('is_active', true)->get();

        return view('trips.calendar', compact('trips'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug logging
        \Log::info('Trip store method called', [
            'request_data' => $request->all(),
            'has_files' => $request->hasFile('image') || $request->hasFile('cover_image') || $request->hasFile('sample_images'),
            'files' => $request->allFiles()
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sample_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'itinerary' => 'nullable|string',
            'total_cost' => 'nullable|string',
            'personal_items' => 'nullable|string',
            'area_info' => 'nullable|string',
            'rental_equipment' => 'nullable|string',
            'show_itinerary' => 'nullable|in:on',
            'show_total_cost' => 'nullable|in:on',
            'show_personal_items' => 'nullable|in:on',
            'show_rental_equipment' => 'nullable|in:on',
            'show_schedule' => 'nullable|in:on',
        ]);

        $data = $request->all();
        
        // Handle main image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('trips', 'public');
        }
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('trips', 'public');
        }
        
        // Handle sample images upload
        if ($request->hasFile('sample_images')) {
            $sampleImages = [];
            foreach ($request->file('sample_images') as $image) {
                $sampleImages[] = $image->store('trips/samples', 'public');
            }
            $data['sample_images'] = $sampleImages;
        }
        
        // Set default values for show fields if not provided
        $data['show_itinerary'] = $request->has('show_itinerary');
        $data['show_total_cost'] = $request->has('show_total_cost');
        $data['show_personal_items'] = $request->has('show_personal_items');
        $data['show_rental_equipment'] = $request->has('show_rental_equipment');

        \Log::info('Creating trip with data', ['data' => $data]);

        $trip = Trip::create($data);

        \Log::info('Trip created successfully', ['trip_id' => $trip->id]);

        return redirect()->route('trips.index')->with('success', 'ทริปถูกสร้างเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        $trip->load('schedules');
        return view('trips.show', compact('trip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        $trip->load('schedules');
        return view('trips.edit', compact('trip'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        try {
            // Debug logging
            \Log::info('Trip update method called', [
                'trip_id' => $trip->id,
                'request_data' => $request->all(),
                'has_files' => $request->hasFile('image') || $request->hasFile('cover_image') || $request->hasFile('sample_images'),
                'files' => $request->allFiles()
            ]);

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'sample_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'itinerary' => 'nullable|string',
                'total_cost' => 'nullable|string',
                'personal_items' => 'nullable|string',
                'area_info' => 'nullable|string',
                'rental_equipment' => 'nullable|string',
                'show_itinerary' => 'nullable|in:on',
                'show_total_cost' => 'nullable|in:on',
                'show_personal_items' => 'nullable|in:on',
                'show_rental_equipment' => 'nullable|in:on',
                'show_schedule' => 'nullable|in:on',
            ]);

            \Log::info('Validation passed');

            $data = $request->all();
            
            // Handle main image upload
            if ($request->hasFile('image')) {
                \Log::info('Processing main image');
                // Delete old image
                if ($trip->image) {
                    Storage::disk('public')->delete($trip->image);
                }
                $data['image'] = $request->file('image')->store('trips', 'public');
                \Log::info('Main image uploaded', ['path' => $data['image']]);
            }
            
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                \Log::info('Processing cover image');
                // Delete old cover image
                if ($trip->cover_image) {
                    Storage::disk('public')->delete($trip->cover_image);
                }
                $data['cover_image'] = $request->file('cover_image')->store('trips', 'public');
                \Log::info('Cover image uploaded', ['path' => $data['cover_image']]);
            }
            
            // Handle sample images upload
            $sampleImages = [];
            
            // Add existing images that weren't replaced
            if ($request->has('existing_sample_images')) {
                $existingImages = $request->input('existing_sample_images');
                // Filter out empty values and duplicates
                $existingImages = array_filter($existingImages, function($image) {
                    return !empty($image);
                });
                $sampleImages = array_merge($sampleImages, array_values($existingImages));
                \Log::info('Preserving existing sample images', ['count' => count($existingImages), 'images' => $existingImages]);
            }
            
            // Add new uploaded images
            if ($request->hasFile('sample_images')) {
                \Log::info('Processing new sample images');
                
                foreach ($request->file('sample_images') as $image) {
                    $sampleImages[] = $image->store('trips/samples', 'public');
                }
                \Log::info('New sample images uploaded', ['count' => count($request->file('sample_images'))]);
            }
            
            // Only update sample_images if we have any images
            if (!empty($sampleImages)) {
                $data['sample_images'] = $sampleImages;
                \Log::info('Total sample images after processing', ['count' => count($sampleImages)]);
            }
            
            // Set values for show fields - convert checkbox values to boolean
            $data['show_itinerary'] = $request->has('show_itinerary') ? true : false;
            $data['show_total_cost'] = $request->has('show_total_cost') ? true : false;
            $data['show_personal_items'] = $request->has('show_personal_items') ? true : false;
            $data['show_rental_equipment'] = $request->has('show_rental_equipment') ? true : false;
            $data['show_schedule'] = $request->has('show_schedule') ? true : false;

            \Log::info('Updating trip with data', ['data' => $data]);

            $trip->update($data);

            \Log::info('Trip updated successfully', ['trip_id' => $trip->id]);

            return redirect()->route('trips.index')->with('success', 'ทริปถูกอัปเดตเรียบร้อยแล้ว');
            
        } catch (\Exception $e) {
            \Log::error('Trip update failed', [
                'trip_id' => $trip->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตทริป: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        // Delete main image if exists
        if ($trip->image) {
            Storage::disk('public')->delete($trip->image);
        }
        
        // Delete cover image if exists
        if ($trip->cover_image) {
            Storage::disk('public')->delete($trip->cover_image);
        }
        
        // Delete sample images if exist
        if ($trip->sample_images) {
            foreach ($trip->sample_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $trip->delete();

        return redirect()->route('trips.index')->with('success', 'ทริปถูกลบเรียบร้อยแล้ว');
    }

    /**
     * Store a new schedule for the trip.
     */
    public function storeSchedule(Request $request, Trip $trip)
    {
        $request->validate([
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after:departure_date',
            'max_participants' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        
        // Set default values for nullable fields
        $data['max_participants'] = $request->max_participants ?? 0;
        $data['price'] = $request->price ?? 0;

        $trip->schedules()->create($data);

        return redirect()->route('trips.edit', $trip)->with('success', 'รอบการเดินทางถูกเพิ่มเรียบร้อยแล้ว');
    }

    /**
     * Update a schedule for the trip.
     */
    public function updateSchedule(Request $request, Trip $trip, TripSchedule $schedule)
    {
        $request->validate([
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after:departure_date',
            'max_participants' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        
        // Set default values for nullable fields
        $data['max_participants'] = $request->max_participants ?? 0;
        $data['price'] = $request->price ?? 0;

        $schedule->update($data);

        return redirect()->route('trips.edit', $trip)->with('success', 'รอบการเดินทางถูกอัปเดตเรียบร้อยแล้ว');
    }

    /**
     * Toggle schedule active status.
     */
    public function toggleSchedule(Trip $trip, TripSchedule $schedule)
    {
        $schedule->update(['is_active' => !$schedule->is_active]);
        
        $status = $schedule->is_active ? 'เปิด' : 'ปิด';
        return redirect()->route('trips.edit', $trip)->with('success', "รอบการเดินทางถูก{$status}เรียบร้อยแล้ว");
    }

    /**
     * Delete a schedule for the trip.
     */
    public function destroySchedule(Trip $trip, TripSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('trips.edit', $trip)->with('success', 'รอบการเดินทางถูกลบเรียบร้อยแล้ว');
    }
}
