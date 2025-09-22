<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TripController extends Controller
{
    /**
     * Get all active trips with active schedules
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Trip::with(['schedules' => function($query) {
                $query->where('is_active', true)
                      ->orderBy('departure_date', 'asc');
            }]);

            // Filter by show_schedule if needed
            if ($request->has('show_schedule')) {
                $query->where('show_schedule', $request->boolean('show_schedule'));
            }

            // Filter by location if provided
            if ($request->has('location')) {
                $query->where('location', 'like', '%' . $request->location . '%');
            }

            // Search by trip name
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%')
                      ->orWhere('location', 'like', '%' . $searchTerm . '%');
                });
            }

            // Filter by price range
            if ($request->has('min_price') || $request->has('max_price')) {
                $query->whereHas('schedules', function($q) use ($request) {
                    if ($request->has('min_price')) {
                        $q->where('price', '>=', $request->min_price);
                    }
                    if ($request->has('max_price')) {
                        $q->where('price', '<=', $request->max_price);
                    }
                });
            }

            // Filter by duration (days)
            if ($request->has('min_days') || $request->has('max_days')) {
                $query->whereHas('schedules', function($q) use ($request) {
                    if ($request->has('min_days')) {
                        $q->whereRaw('DATEDIFF(return_date, departure_date) >= ?', [$request->min_days]);
                    }
                    if ($request->has('max_days')) {
                        $q->whereRaw('DATEDIFF(return_date, departure_date) <= ?', [$request->max_days]);
                    }
                });
            }

            // Filter by month
            if ($request->has('month')) {
                $query->whereHas('schedules', function($q) use ($request) {
                    $q->whereMonth('departure_date', $request->month);
                });
            }

            // Filter by year
            if ($request->has('year')) {
                $query->whereHas('schedules', function($q) use ($request) {
                    $q->whereYear('departure_date', $request->year);
                });
            }

            // Filter by specific date range
            if ($request->has('start_date')) {
                $query->whereHas('schedules', function($q) use ($request) {
                    $q->where('departure_date', '>=', $request->start_date);
                });
            }

            if ($request->has('end_date')) {
                $query->whereHas('schedules', function($q) use ($request) {
                    $q->where('departure_date', '<=', $request->end_date);
                });
            }

            $trips = $query->orderBy('created_at', 'desc')->get();

            $formattedTrips = $trips->map(function ($trip) {
                return [
                    'id' => $trip->id,
                    'name' => $trip->name,
                    'description' => $trip->description,
                    'location' => $trip->location,
                    'cover_image' => $trip->cover_image ? asset('storage/' . $trip->cover_image) : null,
                    'sample_images' => $trip->sample_images ? array_map(function($image) {
                        return asset('storage/' . $image);
                    }, $trip->sample_images) : [],
                    'show_itinerary' => $trip->show_itinerary,
                    'show_total_cost' => $trip->show_total_cost,
                    'show_personal_items' => $trip->show_personal_items,
                    'show_rental_equipment' => $trip->show_rental_equipment,
                    'show_schedule' => $trip->show_schedule,
                    'schedules_count' => $trip->schedules->count(),
                    'next_departure' => $trip->schedules->min('departure_date'),
                    'price_range' => $this->getPriceRange($trip->schedules),
                    'created_at' => $trip->created_at,
                    'updated_at' => $trip->updated_at
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลทริปสำเร็จ',
                'data' => $formattedTrips,
                'meta' => [
                    'total' => $trips->count(),
                    'filters_applied' => $request->only([
                        'search', 'location', 'min_price', 'max_price', 
                        'min_days', 'max_days', 'month', 'year', 
                        'start_date', 'end_date', 'show_schedule'
                    ])
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูลทริป',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trip detail with schedules
     */
    public function show(string $id): JsonResponse
    {
        try {
            $trip = Trip::with(['schedules' => function($query) {
                $query->where('is_active', true)
                      ->orderBy('departure_date', 'asc');
            }])->find($id);

            if (!$trip) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบทริปที่ระบุ',
                    'data' => null
                ], 404);
            }

            $formattedTrip = [
                'id' => $trip->id,
                'name' => $trip->name,
                'description' => $trip->description,
                'location' => $trip->location,
                'cover_image' => $trip->cover_image ? asset('storage/' . $trip->cover_image) : null,
                'sample_images' => $trip->sample_images ? array_map(function($image) {
                    return asset('storage/' . $image);
                }, $trip->sample_images) : [],
                'show_itinerary' => $trip->show_itinerary,
                'show_total_cost' => $trip->show_total_cost,
                'show_personal_items' => $trip->show_personal_items,
                'show_rental_equipment' => $trip->show_rental_equipment,
                'show_schedule' => $trip->show_schedule,
                'schedules' => $trip->schedules->map(function ($schedule) {
                    return [
                        'id' => $schedule->id,
                        'departure_date' => $schedule->departure_date,
                        'return_date' => $schedule->return_date,
                        'departure_date_thai' => formatThaiDate($schedule->departure_date),
                        'return_date_thai' => $schedule->return_date ? formatThaiDate($schedule->return_date) : null,
                        'duration' => $schedule->return_date ? calculateDaysNights($schedule->departure_date, $schedule->return_date) : null,
                        'max_participants' => $schedule->max_participants,
                        'price' => $schedule->price,
                        'is_active' => $schedule->is_active,
                        'created_at' => $schedule->created_at,
                        'updated_at' => $schedule->updated_at
                    ];
                }),
                'price_range' => $this->getPriceRange($trip->schedules),
                'created_at' => $trip->created_at,
                'updated_at' => $trip->updated_at
            ];

            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลทริปรายละเอียดสำเร็จ',
                'data' => $formattedTrip
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูลทริป',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trip schedules
     */
    public function schedules(string $id): JsonResponse
    {
        try {
            $trip = Trip::find($id);

            if (!$trip) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบทริปที่ระบุ',
                    'data' => null
                ], 404);
            }

            $schedules = TripSchedule::where('trip_id', $id)
                ->where('is_active', true)
                ->orderBy('departure_date', 'asc')
                ->get();

            $formattedSchedules = $schedules->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'trip_id' => $schedule->trip_id,
                    'departure_date' => $schedule->departure_date,
                    'return_date' => $schedule->return_date,
                    'departure_date_thai' => formatThaiDate($schedule->departure_date),
                    'return_date_thai' => $schedule->return_date ? formatThaiDate($schedule->return_date) : null,
                    'duration' => $schedule->return_date ? calculateDaysNights($schedule->departure_date, $schedule->return_date) : null,
                    'max_participants' => $schedule->max_participants,
                    'price' => $schedule->price,
                    'is_active' => $schedule->is_active,
                    'created_at' => $schedule->created_at,
                    'updated_at' => $schedule->updated_at
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลรอบการเดินทางสำเร็จ',
                'data' => $formattedSchedules,
                'meta' => [
                    'trip_id' => $id,
                    'trip_name' => $trip->name,
                    'total_schedules' => $schedules->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูลรอบการเดินทาง',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search trips with advanced filters
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = Trip::with(['schedules' => function($query) {
                $query->where('is_active', true)
                      ->orderBy('departure_date', 'asc');
            }]);

            // Search by trip name, description, or location
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%')
                      ->orWhere('location', 'like', '%' . $searchTerm . '%');
                });
            }

            // Filter by price range
            if ($request->has('min_price') || $request->has('max_price')) {
                $query->whereHas('schedules', function($q) use ($request) {
                    if ($request->has('min_price')) {
                        $q->where('price', '>=', $request->min_price);
                    }
                    if ($request->has('max_price')) {
                        $q->where('price', '<=', $request->max_price);
                    }
                });
            }

            // Filter by duration (days)
            if ($request->has('min_days') || $request->has('max_days')) {
                $query->whereHas('schedules', function($q) use ($request) {
                    if ($request->has('min_days')) {
                        $q->whereRaw('DATEDIFF(return_date, departure_date) >= ?', [$request->min_days]);
                    }
                    if ($request->has('max_days')) {
                        $q->whereRaw('DATEDIFF(return_date, departure_date) <= ?', [$request->max_days]);
                    }
                });
            }

            // Filter by month
            if ($request->has('month')) {
                $query->whereHas('schedules', function($q) use ($request) {
                    $q->whereMonth('departure_date', $request->month);
                });
            }

            // Filter by year
            if ($request->has('year')) {
                $query->whereHas('schedules', function($q) use ($request) {
                    $q->whereYear('departure_date', $request->year);
                });
            }

            // Filter by specific date range
            if ($request->has('start_date')) {
                $query->whereHas('schedules', function($q) use ($request) {
                    $q->where('departure_date', '>=', $request->start_date);
                });
            }

            if ($request->has('end_date')) {
                $query->whereHas('schedules', function($q) use ($request) {
                    $q->where('departure_date', '<=', $request->end_date);
                });
            }

            // Filter by location
            if ($request->has('location')) {
                $query->where('location', 'like', '%' . $request->location . '%');
            }

            // Filter by show_schedule
            if ($request->has('show_schedule')) {
                $query->where('show_schedule', $request->boolean('show_schedule'));
            }

            // Sorting options
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $allowedSortFields = ['created_at', 'name', 'location'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $perPage = min($perPage, 50); // Limit max per page

            $trips = $query->paginate($perPage);

            $formattedTrips = $trips->map(function ($trip) {
                return [
                    'id' => $trip->id,
                    'name' => $trip->name,
                    'description' => $trip->description,
                    'location' => $trip->location,
                    'cover_image' => $trip->cover_image ? asset('storage/' . $trip->cover_image) : null,
                    'sample_images' => $trip->sample_images ? array_map(function($image) {
                        return asset('storage/' . $image);
                    }, $trip->sample_images) : [],
                    'show_itinerary' => $trip->show_itinerary,
                    'show_total_cost' => $trip->show_total_cost,
                    'show_personal_items' => $trip->show_personal_items,
                    'show_rental_equipment' => $trip->show_rental_equipment,
                    'show_schedule' => $trip->show_schedule,
                    'schedules_count' => $trip->schedules->count(),
                    'next_departure' => $trip->schedules->min('departure_date'),
                    'price_range' => $this->getPriceRange($trip->schedules),
                    'duration_range' => $this->getDurationRange($trip->schedules),
                    'created_at' => $trip->created_at,
                    'updated_at' => $trip->updated_at
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'ค้นหาทริปสำเร็จ',
                'data' => $formattedTrips,
                'meta' => [
                    'current_page' => $trips->currentPage(),
                    'last_page' => $trips->lastPage(),
                    'per_page' => $trips->perPage(),
                    'total' => $trips->total(),
                    'filters_applied' => $request->only([
                        'search', 'location', 'min_price', 'max_price', 
                        'min_days', 'max_days', 'month', 'year', 
                        'start_date', 'end_date', 'show_schedule', 'sort_by', 'sort_order'
                    ])
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการค้นหาทริป',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available months for trips
     */
    public function availableMonths(Request $request): JsonResponse
    {
        try {
            $query = TripSchedule::where('is_active', true);

            // Filter by year if provided
            if ($request->has('year')) {
                $query->whereYear('departure_date', $request->year);
            }

            $months = $query->selectRaw('MONTH(departure_date) as month, YEAR(departure_date) as year, COUNT(*) as count')
                ->groupBy('month', 'year')
                ->orderBy('year')
                ->orderBy('month')
                ->get()
                ->map(function($item) {
                    $monthNamesThai = [
                        1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
                        5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
                        9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
                    ];

                    $monthNamesEnglish = [
                        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                    ];
                    
                    return [
                        'month' => $item->month,
                        'year' => $item->year,
                        'month_name_thai' => $monthNamesThai[$item->month] ?? 'ไม่ระบุ',
                        'month_name_english' => $monthNamesEnglish[$item->month] ?? 'Unknown',
                        'month_name_short_en' => substr($monthNamesEnglish[$item->month] ?? 'Unknown', 0, 3),
                        'count' => $item->count,
                        'formatted_date' => $monthNamesEnglish[$item->month] . ' ' . $item->year
                    ];
                });

            // Group by year if no specific year filter
            $groupedData = [];
            if (!$request->has('year')) {
                $groupedData = $months->groupBy('year')->map(function($yearMonths, $year) {
                    return [
                        'year' => $year,
                        'months' => $yearMonths->map(function($month) {
                            return [
                                'month' => $month['month'],
                                'month_name_thai' => $month['month_name_thai'],
                                'month_name_english' => $month['month_name_english'],
                                'month_name_short_en' => $month['month_name_short_en'],
                                'count' => $month['count']
                            ];
                        })
                    ];
                });
            }

            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลเดือนที่มีทริปสำเร็จ',
                'data' => $request->has('year') ? $months : $groupedData,
                'meta' => [
                    'year_filter' => $request->get('year'),
                    'total_months' => $months->count(),
                    'available_years' => TripSchedule::where('is_active', true)
                        ->selectRaw('YEAR(departure_date) as year')
                        ->distinct()
                        ->orderBy('year')
                        ->pluck('year')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูลเดือน',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get price range statistics
     */
    public function priceRange(): JsonResponse
    {
        try {
            $priceStats = TripSchedule::where('is_active', true)
                ->selectRaw('MIN(price) as min_price, MAX(price) as max_price, AVG(price) as avg_price')
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลช่วงราคาสำเร็จ',
                'data' => [
                    'min_price' => $priceStats->min_price,
                    'max_price' => $priceStats->max_price,
                    'avg_price' => round($priceStats->avg_price, 2)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูลช่วงราคา',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to get duration range
     */
    private function getDurationRange($schedules)
    {
        if ($schedules->isEmpty()) {
            return null;
        }

        $durations = $schedules->map(function($schedule) {
            if ($schedule->return_date) {
                return \Carbon\Carbon::parse($schedule->departure_date)
                    ->diffInDays(\Carbon\Carbon::parse($schedule->return_date)) + 1;
            }
            return null;
        })->filter();

        if ($durations->isEmpty()) {
            return null;
        }

        $minDays = $durations->min();
        $maxDays = $durations->max();

        if ($minDays == $maxDays) {
            return [
                'min' => $minDays,
                'max' => $maxDays,
                'formatted' => "{$minDays} วัน"
            ];
        }

        return [
            'min' => $minDays,
            'max' => $maxDays,
            'formatted' => "{$minDays} - {$maxDays} วัน"
        ];
    }

    /**
     * Helper method to get price range
     */
    private function getPriceRange($schedules)
    {
        if ($schedules->isEmpty()) {
            return null;
        }

        $prices = $schedules->pluck('price')->filter();
        
        if ($prices->isEmpty()) {
            return null;
        }

        $minPrice = $prices->min();
        $maxPrice = $prices->max();

        if ($minPrice == $maxPrice) {
            return [
                'min' => $minPrice,
                'max' => $maxPrice,
                'formatted' => '฿' . number_format($minPrice)
            ];
        }

        return [
            'min' => $minPrice,
            'max' => $maxPrice,
            'formatted' => '฿' . number_format($minPrice) . ' - ฿' . number_format($maxPrice)
        ];
    }
}
