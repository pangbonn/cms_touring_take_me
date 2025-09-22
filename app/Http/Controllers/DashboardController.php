<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\TripSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get statistics
        $stats = $this->getDashboardStats();
        
        // Get recent bookings
        $recentBookings = Booking::with(['trip', 'tripSchedule'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get upcoming trips
        $upcomingTrips = TripSchedule::with(['trip'])
            ->where('departure_date', '>=', Carbon::now())
            ->where('is_active', true)
            ->orderBy('departure_date', 'asc')
            ->limit(5)
            ->get();
        
        // Get chart data
        $monthlyRevenue = $this->getMonthlyRevenueData();
        $tripPopularity = $this->getTripPopularityData();
        
        return view('dashboard-daisyui', compact('user', 'stats', 'recentBookings', 'upcomingTrips', 'monthlyRevenue', 'tripPopularity'));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        $totalTrips = Trip::where('is_active', true)->count();
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        
        // Get monthly comparison
        $currentMonth = Carbon::now()->month;
        $lastMonth = Carbon::now()->subMonth()->month;
        
        $currentMonthBookings = Booking::whereMonth('created_at', $currentMonth)->count();
        $lastMonthBookings = Booking::whereMonth('created_at', $lastMonth)->count();
        
        $bookingGrowth = $lastMonthBookings > 0 
            ? round((($currentMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100, 1)
            : 0;
        
        return [
            'totalTrips' => $totalTrips,
            'totalBookings' => $totalBookings,
            'pendingBookings' => $pendingBookings,
            'confirmedBookings' => $confirmedBookings,
            'totalRevenue' => $totalRevenue,
            'bookingGrowth' => $bookingGrowth,
            'currentMonthBookings' => $currentMonthBookings,
            'lastMonthBookings' => $lastMonthBookings
        ];
    }

    /**
     * Get monthly revenue data for chart
     */
    private function getMonthlyRevenueData()
    {
        $currentYear = Carbon::now()->year;
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $revenue = Booking::where('status', 'confirmed')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('total_price');
            
            $monthlyData[] = $revenue;
        }
        
        return [
            'labels' => ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
            'values' => $monthlyData
        ];
    }

    /**
     * Get trip popularity data for chart
     */
    private function getTripPopularityData()
    {
        $tripBookings = Booking::with('trip')
            ->selectRaw('trip_id, COUNT(*) as booking_count')
            ->groupBy('trip_id')
            ->orderBy('booking_count', 'desc')
            ->limit(5)
            ->get();
        
        $labels = [];
        $values = [];
        
        foreach ($tripBookings as $tripBooking) {
            $labels[] = $tripBooking->trip->name;
            $values[] = $tripBooking->booking_count;
        }
        
        return [
            'labels' => $labels,
            'values' => $values
        ];
    }
}