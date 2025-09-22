@extends('layouts.daisyui')

@section('title', 'จัดการการจอง')
@section('page-title', 'จัดการการจอง')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-2">
                <i class="fas fa-calendar-check me-3"></i>
                จัดการการจอง
            </h1>
            <p class="text-base-content/70">ดูและจัดการการจองทัวร์ทั้งหมด</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('booking.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                จองใหม่
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat bg-base-100 shadow rounded-lg">
            <div class="stat-figure text-primary">
                <i class="fas fa-clock text-3xl"></i>
            </div>
            <div class="stat-title">รอดำเนินการ</div>
            <div class="stat-value text-warning">{{ $bookings->where('status', 'pending')->count() }}</div>
        </div>
        
        <div class="stat bg-base-100 shadow rounded-lg">
            <div class="stat-figure text-success">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>
            <div class="stat-title">ยืนยันแล้ว</div>
            <div class="stat-value text-success">{{ $bookings->where('status', 'confirmed')->count() }}</div>
        </div>
        
        <div class="stat bg-base-100 shadow rounded-lg">
            <div class="stat-figure text-error">
                <i class="fas fa-times-circle text-3xl"></i>
            </div>
            <div class="stat-title">ยกเลิก</div>
            <div class="stat-value text-error">{{ $bookings->where('status', 'cancelled')->count() }}</div>
        </div>
        
        <div class="stat bg-base-100 shadow rounded-lg">
            <div class="stat-figure text-info">
                <i class="fas fa-flag-checkered text-3xl"></i>
            </div>
            <div class="stat-title">เสร็จสิ้น</div>
            <div class="stat-value text-info">{{ $bookings->where('status', 'completed')->count() }}</div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>รหัสการจอง</th>
                            <th>ทริป</th>
                            <th>ลูกค้า</th>
                            <th>วันที่เดินทาง</th>
                            <th>จำนวนคน</th>
                            <th>ราคารวม</th>
                            <th>สถานะ</th>
                            <th>วันที่จอง</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <div class="font-mono text-sm font-bold text-primary">
                                        {{ $booking->booking_id }}
                                    </div>
                                </td>
                                <td>
                                    <div class="font-semibold">{{ $booking->trip->name }}</div>
                                </td>
                                <td>
                                    <div>
                                        <div class="font-semibold">{{ $booking->customer_name }}</div>
                                        <div class="text-sm text-base-content/70">{{ $booking->customer_phone }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <div>{{ $booking->tripSchedule->departure_date_thai }}</div>
                                        @if($booking->tripSchedule->return_date_thai)
                                            <div class="text-base-content/70">- {{ $booking->tripSchedule->return_date_thai }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-outline">{{ $booking->guests }} คน</span>
                                </td>
                                <td>
                                    <div class="font-semibold text-success">
                                        {{ number_format($booking->total_price, 2) }} บาท
                                    </div>
                                </td>
                                <td>
                                    @switch($booking->status)
                                        @case('pending')
                                            <span class="badge badge-warning">รอดำเนินการ</span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge badge-success">ยืนยันแล้ว</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge badge-error">ยกเลิก</span>
                                            @break
                                        @case('completed')
                                            <span class="badge badge-info">เสร็จสิ้น</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <div class="text-sm">
                                        {{ $booking->booking_date_thai }}
                                    </div>
                                </td>
                                <td>
                                    <div class="flex gap-1">
                                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-ghost btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <div class="dropdown dropdown-end">
                                            <div tabindex="0" role="button" class="btn btn-ghost btn-sm">
                                                <i class="fas fa-ellipsis-v"></i>
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-8">
                                    <div class="text-base-content/50">
                                        <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                        <p>ยังไม่มีการจอง</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="flex justify-center mt-6">
                    {{ $bookings->links() }}
                </div>
            @endif
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
