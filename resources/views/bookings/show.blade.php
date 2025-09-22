@extends('layouts.daisyui')

@section('title', 'รายละเอียดการจอง')
@section('page-title', 'รายละเอียดการจอง')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-2">
                <i class="fas fa-calendar-check me-3"></i>
                รายละเอียดการจอง
            </h1>
            <p class="text-base-content/70">รหัสการจอง: <span class="font-mono font-bold text-primary">{{ $booking->booking_id }}</span></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('bookings.index') }}" class="btn btn-ghost">
                <i class="fas fa-arrow-left me-2"></i>
                กลับ
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-2"></i>
                พิมพ์
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Status -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-primary mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        สถานะการจอง
                    </h2>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            @switch($booking->status)
                                @case('pending')
                                    <span class="badge badge-warning badge-lg">รอดำเนินการ</span>
                                    @break
                                @case('confirmed')
                                    <span class="badge badge-success badge-lg">ยืนยันแล้ว</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge badge-error badge-lg">ยกเลิก</span>
                                    @break
                                @case('completed')
                                    <span class="badge badge-info badge-lg">เสร็จสิ้น</span>
                                    @break
                            @endswitch
                            <span class="text-sm text-base-content/70">
                                อัปเดตล่าสุด: {{ $booking->updated_at->locale('th')->isoFormat('DD MMMM YYYY HH:mm') }}
                            </span>
                        </div>
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn btn-primary">
                                <i class="fas fa-cog me-2"></i>
                                เปลี่ยนสถานะ
                            </div>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                <li>
                                    <form action="{{ route('bookings.update-status', $booking) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="text-success">
                                            <i class="fas fa-check me-2"></i>ยืนยันการจอง
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('bookings.update-status', $booking) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="text-error" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะยกเลิกการจองนี้?')">
                                            <i class="fas fa-times me-2"></i>ยกเลิกการจอง
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('bookings.update-status', $booking) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="text-info">
                                            <i class="fas fa-flag-checkered me-2"></i>เสร็จสิ้น
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trip Information -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-primary mb-4">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        ข้อมูลทริป
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">ชื่อทริป</span>
                            </label>
                            <p class="text-lg font-semibold">{{ $booking->trip->name }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">วันที่เดินทาง</span>
                            </label>
                            <p class="text-lg">{{ $booking->tripSchedule->departure_date_thai }}</p>
                            @if($booking->tripSchedule->return_date_thai)
                                <p class="text-sm text-base-content/70">ถึง {{ $booking->tripSchedule->return_date_thai }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">ระยะเวลา</span>
                            </label>
                            <p class="text-lg">{{ $booking->tripSchedule->duration }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">ราคาต่อคน</span>
                            </label>
                            <p class="text-lg font-semibold text-success">{{ number_format($booking->tripSchedule->price, 2) }} บาท</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-primary mb-4">
                        <i class="fas fa-user me-2"></i>
                        ข้อมูลลูกค้า
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">ชื่อ-นามสกุล</span>
                            </label>
                            <p class="text-lg">{{ $booking->customer_name }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">เบอร์โทรศัพท์</span>
                            </label>
                            <p class="text-lg">{{ $booking->customer_phone }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">อีเมล</span>
                            </label>
                            <p class="text-lg">{{ $booking->customer_email }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Line ID</span>
                            </label>
                            <p class="text-lg">{{ $booking->customer_line_id ?: 'ไม่ระบุ' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-primary mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        รายละเอียดการจอง
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">จำนวนผู้เข้าร่วม</span>
                            </label>
                            <p class="text-lg font-semibold">{{ $booking->guests }} คน</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">ราคารวม</span>
                            </label>
                            <p class="text-2xl font-bold text-success">{{ number_format($booking->total_price, 2) }} บาท</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">วันที่จอง</span>
                            </label>
                            <p class="text-lg">{{ $booking->booking_date_thai }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">แหล่งที่มา</span>
                            </label>
                            <p class="text-lg">{{ ucfirst(str_replace('_', ' ', $booking->source)) }}</p>
                        </div>
                    </div>
                    @if($booking->notes)
                        <div class="mt-4">
                            <label class="label">
                                <span class="label-text font-semibold">หมายเหตุ</span>
                            </label>
                            <div class="bg-base-200 p-4 rounded-lg">
                                <p class="whitespace-pre-wrap">{{ $booking->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title text-primary mb-4">
                        <i class="fas fa-bolt me-2"></i>
                        การดำเนินการด่วน
                    </h3>
                    <div class="space-y-2">
                        <form action="{{ route('bookings.update-status', $booking) }}" method="POST" class="w-full">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="btn btn-success w-full">
                                <i class="fas fa-check me-2"></i>
                                ยืนยันการจอง
                            </button>
                        </form>
                        <form action="{{ route('bookings.update-status', $booking) }}" method="POST" class="w-full">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-error w-full" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะยกเลิกการจองนี้?')">
                                <i class="fas fa-times me-2"></i>
                                ยกเลิกการจอง
                            </button>
                        </form>
                        <form action="{{ route('bookings.update-status', $booking) }}" method="POST" class="w-full">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-info w-full">
                                <i class="fas fa-flag-checkered me-2"></i>
                                เสร็จสิ้น
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Booking Timeline -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title text-primary mb-4">
                        <i class="fas fa-history me-2"></i>
                        ประวัติการจอง
                    </h3>
                    <div class="timeline timeline-vertical">
                        <div class="timeline-item">
                            <div class="timeline-start timeline-box">
                                <div class="font-semibold">สร้างการจอง</div>
                                <div class="text-sm text-base-content/70">{{ $booking->created_at->locale('th')->isoFormat('DD MMMM YYYY HH:mm') }}</div>
                            </div>
                            <div class="timeline-middle">
                                <div class="timeline-dot bg-primary"></div>
                            </div>
                        </div>
                        @if($booking->updated_at != $booking->created_at)
                            <div class="timeline-item">
                                <div class="timeline-start timeline-box">
                                    <div class="font-semibold">อัปเดตล่าสุด</div>
                                    <div class="text-sm text-base-content/70">{{ $booking->updated_at->locale('th')->isoFormat('DD MMMM YYYY HH:mm') }}</div>
                                </div>
                                <div class="timeline-middle">
                                    <div class="timeline-dot bg-info"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title text-primary mb-4">
                        <i class="fas fa-phone me-2"></i>
                        ข้อมูลติดต่อ
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-phone text-primary"></i>
                            <a href="tel:{{ $booking->customer_phone }}" class="link link-primary">
                                {{ $booking->customer_phone }}
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope text-primary"></i>
                            <a href="mailto:{{ $booking->customer_email }}" class="link link-primary">
                                {{ $booking->customer_email }}
                            </a>
                        </div>
                        @if($booking->customer_line_id)
                            <div class="flex items-center gap-3">
                                <i class="fab fa-line text-primary"></i>
                                <span>{{ $booking->customer_line_id }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="toast toast-top toast-end">
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="toast toast-top toast-end">
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide toast after 3 seconds
    setTimeout(function() {
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(toast => {
            toast.style.display = 'none';
        });
    }, 3000);
});
</script>
@endpush
