@extends('layouts.daisyui')

@section('title', 'ปฏิทินรายการทริป')
@section('page-title', 'ปฏิทินรายการทริป')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-2">
                <i class="fas fa-calendar-alt me-3"></i>
                ปฏิทินรายการทริป
            </h1>
            <p class="text-base-content/70">ดูตารางเวลาทริปทั้งหมดในรูปแบบปฏิทิน</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('trips.index') }}" class="btn btn-ghost">
                <i class="fas fa-list me-2"></i>
                รายการทริป
            </a>
            <a href="{{ route('trips.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                เพิ่มทริป
            </a>
        </div>
    </div>

    <!-- Calendar Navigation -->
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <button id="prevMonth" class="btn btn-ghost btn-sm">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h2 id="currentMonth" class="text-xl font-bold text-primary">
                        {{ \Carbon\Carbon::now()->locale('th')->isoFormat('MMMM YYYY') }}
                    </h2>
                    <button id="nextMonth" class="btn btn-ghost btn-sm">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <button id="todayBtn" class="btn btn-outline btn-sm">
                        <i class="fas fa-calendar-day me-1"></i>
                        วันนี้
                    </button>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-outline btn-sm">
                            <i class="fas fa-filter me-1"></i>
                            กรองทริป
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a onclick="filterTrips('all')"><i class="fas fa-list me-2"></i>ทั้งหมด</a></li>
                            @foreach($trips as $trip)
                                <li><a onclick="filterTrips('{{ $trip->id }}')"><i class="fas fa-map-marked-alt me-2"></i>{{ $trip->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <!-- Color Legend -->
            <div class="flex flex-wrap gap-4 mb-4 p-3 bg-base-200 rounded-lg">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-success/20 border border-success rounded"></div>
                    <span class="text-sm">วันที่มีทริป</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-primary/10 border border-primary rounded"></div>
                    <span class="text-sm">วันนี้ (ไม่มีทริป)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-accent/20 border border-accent rounded"></div>
                    <span class="text-sm">วันนี้ (มีทริป)</span>
                </div>
            </div>
            
            <!-- Calendar Header -->
            <div class="grid grid-cols-7 gap-1 mb-4">
                <div class="text-center font-semibold text-base-content/70 py-2">จันทร์</div>
                <div class="text-center font-semibold text-base-content/70 py-2">อังคาร</div>
                <div class="text-center font-semibold text-base-content/70 py-2">พุธ</div>
                <div class="text-center font-semibold text-base-content/70 py-2">พฤหัสบดี</div>
                <div class="text-center font-semibold text-base-content/70 py-2">ศุกร์</div>
                <div class="text-center font-semibold text-base-content/70 py-2">เสาร์</div>
                <div class="text-center font-semibold text-base-content/70 py-2">อาทิตย์</div>
            </div>

            <!-- Calendar Days -->
            <div id="calendarGrid" class="grid grid-cols-7 gap-1">
                <!-- Calendar days will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Trip List Section -->
    <div id="tripListSection" class="mt-6" style="display: none;">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-primary">
                        <i class="fas fa-list me-2"></i>
                        รายการทริป
                    </h3>
                    <button class="btn btn-sm btn-ghost" onclick="closeTripList()">
                        <i class="fas fa-times me-1"></i>
                        ปิด
                    </button>
                </div>
                <div id="tripList">
                    <!-- Trip list will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Trip Data
const tripData = {!! json_encode($trips->map(function($trip) {
    return [
        'id' => $trip->id,
        'name' => $trip->name,
        'main_image' => $trip->image ? asset('storage/' . $trip->image) : null,
        'schedules' => $trip->schedules->map(function($schedule) {
            return [
                'id' => $schedule->id,
                'departure_date' => $schedule->departure_date,
                'return_date' => $schedule->return_date,
                'departure_date_thai' => $schedule->departure_date_thai,
                'return_date_thai' => $schedule->return_date_thai,
                'duration' => $schedule->duration,
                'price' => $schedule->price,
                'max_participants' => $schedule->max_participants,
                'is_active' => $schedule->is_active
            ];
        })
    ];
})) !!};

let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();
let filteredTripId = 'all';

document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();
    
    // Event listeners
    document.getElementById('prevMonth').addEventListener('click', function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    });
    
    document.getElementById('nextMonth').addEventListener('click', function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    });
    
    document.getElementById('todayBtn').addEventListener('click', function() {
        currentDate = new Date();
        currentMonth = currentDate.getMonth();
        currentYear = currentDate.getFullYear();
        renderCalendar();
    });
});

function renderCalendar() {
    const firstDay = new Date(currentYear, currentMonth, 1);
    const lastDay = new Date(currentYear, currentMonth + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = (firstDay.getDay() + 6) % 7; // Convert Sunday=0 to Monday=0
    
    // Update month display
    const monthNames = [
        'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
        'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
    ];
    document.getElementById('currentMonth').textContent = `${monthNames[currentMonth]} ${currentYear + 543}`;
    
    // Clear calendar grid
    const calendarGrid = document.getElementById('calendarGrid');
    calendarGrid.innerHTML = '';
    
    // Add empty cells for days before the first day of the month
    for (let i = 0; i < startingDayOfWeek; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'h-24 border border-base-300 bg-base-200';
        calendarGrid.appendChild(emptyCell);
    }
    
    // Add days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayCell = document.createElement('div');
        dayCell.className = 'h-24 border border-base-300 bg-base-100 p-2 relative';
        
        // Day number
        const dayNumber = document.createElement('div');
        dayNumber.className = 'text-sm font-semibold mb-1';
        dayNumber.textContent = day;
        dayCell.appendChild(dayNumber);
        
        // Add trips for this day
        const tripsForDay = getTripsForDay(day);
        
        // Add background color for days with trips
        if (tripsForDay.length > 0) {
            dayCell.classList.add('bg-success/20', 'border-success');
        }
        
        tripsForDay.forEach(trip => {
            const tripElement = document.createElement('div');
            tripElement.className = 'text-xs bg-primary text-primary-content rounded px-1 py-0.5 mb-1 cursor-pointer hover:bg-primary-focus';
            tripElement.textContent = trip.name;
            tripElement.title = `${trip.name} - ${trip.departure_date_thai}`;
            tripElement.onclick = () => showTripList(tripsForDay);
            dayCell.appendChild(tripElement);
        });
        
        // Highlight today
        const today = new Date();
        if (day === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()) {
            if (tripsForDay.length > 0) {
                // Today with trips - use accent color
                dayCell.classList.add('bg-accent/20', 'border-accent');
            } else {
                // Today without trips - use primary color
                dayCell.classList.add('bg-primary/10', 'border-primary');
            }
        }
        
        calendarGrid.appendChild(dayCell);
    }
}

function getTripsForDay(day) {
    const trips = [];
    const targetDate = new Date(currentYear, currentMonth, day);
    
    tripData.forEach(trip => {
        if (filteredTripId !== 'all' && trip.id != filteredTripId) return;
        
        trip.schedules.forEach(schedule => {
            const departureDate = new Date(schedule.departure_date);
            const returnDate = schedule.return_date ? new Date(schedule.return_date) : null;
            
            // Check if the day falls within the trip period
            if (departureDate.toDateString() === targetDate.toDateString() || 
                (returnDate && targetDate >= departureDate && targetDate <= returnDate)) {
                trips.push({
                    ...schedule,
                    tripName: trip.name,
                    tripId: trip.id,
                    main_image: trip.main_image
                });
            }
        });
    });
    
    return trips;
}

function filterTrips(tripId) {
    filteredTripId = tripId;
    renderCalendar();
}

function showTripList(trips) {
    const tripListSection = document.getElementById('tripListSection');
    const tripList = document.getElementById('tripList');
    
    if (trips.length === 0) {
        tripList.innerHTML = `
            <div class="text-center py-8 text-base-content/70">
                <i class="fas fa-calendar-times text-4xl mb-4"></i>
                <p class="text-lg">ไม่มีทริปในวันนี้</p>
            </div>
        `;
    } else {
        tripList.innerHTML = `
            <div class="space-y-4">
                ${trips.map(trip => `
                    <div class="card bg-base-200 shadow-sm">
                        <div class="card-body p-4">
                            <div class="flex gap-4 mb-3">
                                <div class="flex-shrink-0">
                                    <div class="w-24 h-24 rounded-lg overflow-hidden bg-base-300">
                                        ${trip.main_image ? 
                                            `<img src="${trip.main_image}" alt="${trip.tripName}" class="w-full h-full object-cover">` :
                                            `<div class="w-full h-full flex items-center justify-center text-base-content/50">
                                                <i class="fas fa-image text-2xl"></i>
                                            </div>`
                                        }
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="card-title text-lg">${trip.tripName}</h4>
                                        <span class="badge ${trip.is_active ? 'badge-success' : 'badge-error'}">
                                            ${trip.is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน'}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <div>
                                            <label class="label py-1">
                                                <span class="label-text font-semibold text-sm">วันที่เดินทาง</span>
                                            </label>
                                            <p class="text-sm">${trip.departure_date_thai}</p>
                                            ${trip.return_date_thai ? `<p class="text-xs text-base-content/70">ถึง ${trip.return_date_thai}</p>` : ''}
                                        </div>
                                        <div>
                                            <label class="label py-1">
                                                <span class="label-text font-semibold text-sm">ระยะเวลา</span>
                                            </label>
                                            <p class="text-sm">${trip.duration}</p>
                                        </div>
                                        <div>
                                            <label class="label py-1">
                                                <span class="label-text font-semibold text-sm">ราคาต่อคน</span>
                                            </label>
                                            <p class="text-sm font-semibold text-success">${parseFloat(trip.price).toLocaleString()} บาท</p>
                                        </div>
                                        <div>
                                            <label class="label py-1">
                                                <span class="label-text font-semibold text-sm">จำนวนสูงสุด</span>
                                            </label>
                                            <p class="text-sm">${trip.max_participants} คน</p>
                                        </div>
                                        <div>
                                            <label class="label py-1">
                                                <span class="label-text font-semibold text-sm">รหัสตารางเวลา</span>
                                            </label>
                                            <p class="text-sm font-mono">#${trip.id}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    tripListSection.style.display = 'block';
    
    // Scroll to the trip list section
    tripListSection.scrollIntoView({ behavior: 'smooth' });
}

function closeTripList() {
    document.getElementById('tripListSection').style.display = 'none';
}
</script>
@endpush