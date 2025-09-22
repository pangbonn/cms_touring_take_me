@extends('layouts.daisyui')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- User Info Card -->
<div class="card bg-gradient-to-r from-blue-500 to-purple-600 text-white mb-6">
    <div class="card-body">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">สวัสดี, {{ $user->name }}!</h2>
                <p class="text-blue-100">สิทธิ์: {{ $user->getRoleDisplayName() }}</p>
                <p class="text-blue-100">อีเมล: {{ $user->email }}</p>
            </div>
            <div class="text-6xl opacity-80">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>
</div>
<style>
    .stat {
        background-color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }
    
    .stat-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }
    
    .stat-secondary {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .stat-accent {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .stat-success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
    }
    
    .card {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table th,
    .table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .table th {
        background-color: #f9fafb;
        font-weight: 600;
        color: #374151;
    }
    
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .badge-success {
        background-color: #dcfce7;
        color: #166534;
    }
    
    .badge-warning {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .badge-error {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s;
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
    }
    
    .btn-outline {
        background-color: transparent;
        border-color: #d1d5db;
        color: #374151;
    }
    
    .btn-outline:hover {
        background-color: #f9fafb;
    }
    
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .btn-xs {
        padding: 0.125rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .btn-ghost {
        background-color: transparent;
        color: #6b7280;
    }
    
    .btn-ghost:hover {
        background-color: #f3f4f6;
    }
</style>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Trips -->
    <div class="stat stat-primary">
        <div class="flex justify-between items-center">
            <div>
                <div class="text-sm opacity-80">ทริปทั้งหมด</div>
                <div class="text-3xl font-bold">{{ $stats['totalTrips'] }}</div>
                <div class="text-sm opacity-80">ทริปที่เปิดใช้งาน</div>
            </div>
            <div class="text-4xl opacity-80">
                <i class="fas fa-map-marked-alt"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Bookings -->
    <div class="stat stat-secondary">
        <div class="flex justify-between items-center">
            <div>
                <div class="text-sm opacity-80">การจองทั้งหมด</div>
                <div class="text-3xl font-bold">{{ $stats['totalBookings'] }}</div>
                <div class="text-sm opacity-80">
                    @if($stats['bookingGrowth'] > 0)
                        +{{ $stats['bookingGrowth'] }}% จากเดือนที่แล้ว
                    @elseif($stats['bookingGrowth'] < 0)
                        {{ $stats['bookingGrowth'] }}% จากเดือนที่แล้ว
                    @else
                        ไม่มีการเปลี่ยนแปลง
                    @endif
                </div>
            </div>
            <div class="text-4xl opacity-80">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
    
    <!-- Confirmed Bookings -->
    <div class="stat stat-accent">
        <div class="flex justify-between items-center">
            <div>
                <div class="text-sm opacity-80">การจองที่อนุมัติ</div>
                <div class="text-3xl font-bold">{{ $stats['confirmedBookings'] }}</div>
                <div class="text-sm opacity-80">ผู้จองที่ยืนยันแล้ว</div>
            </div>
            <div class="text-4xl opacity-80">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="stat stat-success">
        <div class="flex justify-between items-center">
            <div>
                <div class="text-sm opacity-80">รายได้รวม</div>
                <div class="text-3xl font-bold">฿{{ number_format($stats['totalRevenue'], 0) }}</div>
                <div class="text-sm opacity-80">จากการจองที่อนุมัติ</div>
            </div>
            <div class="text-4xl opacity-80">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Booking Status Chart -->
    <div class="card">
        <div class="card-body">
            <h2 class="card-title text-lg mb-4">
                <i class="fas fa-chart-pie text-blue-600"></i>
                สถานะการจอง
            </h2>
            <div class="h-64">
                <canvas id="bookingStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="card">
        <div class="card-body">
            <h2 class="card-title text-lg mb-4">
                <i class="fas fa-chart-line text-green-600"></i>
                รายได้รายเดือน
            </h2>
            <div class="h-64">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Trip Popularity Chart -->
<div class="card mb-6">
    <div class="card-body">
        <h2 class="card-title text-lg mb-4">
            <i class="fas fa-chart-bar text-purple-600"></i>
            ความนิยมของทริป
        </h2>
        <div class="h-64">
            <canvas id="tripPopularityChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Bookings Table -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-calendar-alt text-blue-600"></i>
                    การจองล่าสุด
                </h2>
                <a href="{{ route('bookings.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye me-1"></i>
                    ดูทั้งหมด
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>รหัสการจอง</th>
                            <th>ลูกค้า</th>
                            <th>ทริป</th>
                            <th>วันที่เดินทาง</th>
                            <th>สถานะ</th>
                            <th>จำนวนเงิน</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                            <tr>
                                <td class="font-mono text-sm">{{ $booking->booking_id }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center">
                                            <i class="fas fa-user text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-sm">{{ $booking->customer_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->customer_phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm font-semibold">{{ $booking->trip->name }}</div>
                                </td>
                                <td>
                                    <div class="text-sm">{{ $booking->tripSchedule->departure_date_thai }}</div>
                                </td>
                                <td>
                                    @switch($booking->status)
                                        @case('pending')
                                            <div class="badge badge-warning">
                                                <i class="fas fa-clock me-1"></i>
                                                รอดำเนินการ
                                            </div>
                                            @break
                                        @case('confirmed')
                                            <div class="badge badge-success">
                                                <i class="fas fa-check me-1"></i>
                                                ยืนยันแล้ว
                                            </div>
                                            @break
                                        @case('cancelled')
                                            <div class="badge badge-error">
                                                <i class="fas fa-times me-1"></i>
                                                ยกเลิก
                                            </div>
                                            @break
                                        @case('completed')
                                            <div class="badge badge-info">
                                                <i class="fas fa-flag-checkered me-1"></i>
                                                เสร็จสิ้น
                                            </div>
                                            @break
                                    @endswitch
                                </td>
                                <td class="font-bold text-sm">฿{{ number_format($booking->total_price, 0) }}</td>
                                <td>
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-ghost btn-xs">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">
                                    <i class="fas fa-calendar-times text-2xl mb-2"></i>
                                    <div>ยังไม่มีการจอง</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Upcoming Trips -->
        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-calendar-alt text-blue-600"></i>
                    ทริปที่ใกล้ถึง
                </h2>
                <a href="{{ route('trips.calendar') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-calendar me-1"></i>
                    ปฏิทิน
                </a>
            </div>
            <div class="space-y-3">
                @forelse($upcomingTrips as $tripSchedule)
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold text-sm">{{ $tripSchedule->trip->name }}</h3>
                            <span class="badge badge-primary badge-sm">{{ number_format($tripSchedule->price, 0) }} บาท</span>
                        </div>
                        <div class="text-xs text-gray-600 space-y-1">
                            <div><i class="fas fa-calendar me-1"></i>{{ $tripSchedule->departure_date_thai }}</div>
                            <div><i class="fas fa-users me-1"></i>จำนวนสูงสุด: {{ $tripSchedule->max_participants }} คน</div>
                            <div><i class="fas fa-clock me-1"></i>{{ $tripSchedule->duration }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-calendar-times text-2xl mb-2"></i>
                        <div class="text-sm">ไม่มีทริปที่ใกล้ถึง</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-bolt text-blue-600"></i>
                การดำเนินการด่วน
            </h2>
            <div class="space-y-2">
                <a href="{{ route('booking.create') }}" class="btn btn-primary w-full justify-start">
                    <i class="fas fa-plus me-2"></i>
                    เพิ่มการจองใหม่
                </a>
                <a href="{{ route('bookings.index') }}" class="btn btn-outline w-full justify-start">
                    <i class="fas fa-calendar-check me-2"></i>
                    จัดการการจอง
                </a>
                <!-- @if($user->isSuperAdmin())
                    <a href="{{ route('users.index') }}" class="btn btn-outline w-full justify-start">
                        <i class="fas fa-users me-2"></i>
                        จัดการผู้ใช้
                    </a>
                @endif -->
                <a href="{{ route('trips.index') }}" class="btn btn-outline w-full justify-start">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    จัดการทริป
                </a>
            </div>
        </div>
        
        <!-- System Status -->
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-server text-blue-600"></i>
                สถานะระบบ
            </h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">ฐานข้อมูล</span>
                    <div class="badge badge-success">
                        <div class="w-2 h-2 bg-green-600 rounded-full me-1"></div>
                        ปกติ
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">เซิร์ฟเวอร์</span>
                    <div class="badge badge-success">
                        <div class="w-2 h-2 bg-green-600 rounded-full me-1"></div>
                        ปกติ
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">การเชื่อมต่อ</span>
                    <div class="badge badge-success">
                        <div class="w-2 h-2 bg-green-600 rounded-full me-1"></div>
                        ปกติ
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">การสำรองข้อมูล</span>
                    <div class="badge badge-warning">
                        <div class="w-2 h-2 bg-yellow-600 rounded-full me-1"></div>
                        รอดำเนินการ
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-history text-blue-600"></i>
                กิจกรรมล่าสุด
            </h2>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center">
                        <i class="fas fa-user text-xs"></i>
                    </div>
                    <div class="text-sm">
                        <div class="font-semibold text-gray-800">ผู้ใช้ใหม่ลงทะเบียน</div>
                        <div class="text-xs text-gray-500">2 นาทีที่แล้ว</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center">
                        <i class="fas fa-calendar text-xs"></i>
                    </div>
                    <div class="text-sm">
                        <div class="font-semibold text-gray-800">การจองใหม่</div>
                        <div class="text-xs text-gray-500">5 นาทีที่แล้ว</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-orange-600 text-white flex items-center justify-center">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                    <div class="text-sm">
                        <div class="font-semibold text-gray-800">การจองได้รับการยืนยัน</div>
                        <div class="text-xs text-gray-500">10 นาทีที่แล้ว</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart data from PHP
    const bookingStatusData = {
        pending: {{ $stats['pendingBookings'] }},
        confirmed: {{ $stats['confirmedBookings'] }},
        cancelled: {{ \App\Models\Booking::where('status', 'cancelled')->count() }},
        completed: {{ \App\Models\Booking::where('status', 'completed')->count() }}
    };

    const monthlyRevenueData = {!! json_encode($monthlyRevenue ?? []) !!};
    const tripPopularityData = {!! json_encode($tripPopularity ?? []) !!};

    // Booking Status Pie Chart
    const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
    new Chart(bookingStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['รอดำเนินการ', 'ยืนยันแล้ว', 'ยกเลิก', 'เสร็จสิ้น'],
            datasets: [{
                data: [
                    bookingStatusData.pending,
                    bookingStatusData.confirmed,
                    bookingStatusData.cancelled,
                    bookingStatusData.completed
                ],
                backgroundColor: [
                    '#f59e0b', // amber
                    '#10b981', // emerald
                    '#ef4444', // red
                    '#6366f1'  // indigo
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Monthly Revenue Line Chart
    const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyRevenueCtx, {
        type: 'line',
        data: {
            labels: monthlyRevenueData.labels || ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
            datasets: [{
                label: 'รายได้ (บาท)',
                data: monthlyRevenueData.values || [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '฿' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Trip Popularity Bar Chart
    const tripPopularityCtx = document.getElementById('tripPopularityChart').getContext('2d');
    new Chart(tripPopularityCtx, {
        type: 'bar',
        data: {
            labels: tripPopularityData.labels || ['ทริป A', 'ทริป B', 'ทริป C'],
            datasets: [{
                label: 'จำนวนการจอง',
                data: tripPopularityData.values || [0, 0, 0],
                backgroundColor: [
                    '#8b5cf6', // violet
                    '#06b6d4', // cyan
                    '#f59e0b', // amber
                    '#ef4444', // red
                    '#10b981'  // emerald
                ],
                borderWidth: 0,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection