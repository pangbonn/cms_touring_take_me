@extends('layouts.daisyui')

@section('title', 'จัดการการยกเลิกทริป')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-base-content">จัดการการยกเลิกทริป</h1>
            <p class="text-base-content/70 mt-1">จัดการทริปและเงื่อนไขการยกเลิก</p>
        </div>
        <button onclick="openAddTripModal()" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>
            เพิ่มทริปใหม่
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat bg-base-100 rounded-lg shadow">
            <div class="stat-figure text-primary">
                <i class="fas fa-calendar-alt text-2xl"></i>
            </div>
            <div class="stat-title">ทริปทั้งหมด</div>
            <div class="stat-value text-primary">{{ $trips->total() }}</div>
        </div>
        
        <div class="stat bg-base-100 rounded-lg shadow">
            <div class="stat-figure text-success">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <div class="stat-title">ทริปที่เปิดใช้งาน</div>
            <div class="stat-value text-success">{{ $trips->where('status', 'active')->count() }}</div>
        </div>
        
        <div class="stat bg-base-100 rounded-lg shadow">
            <div class="stat-figure text-error">
                <i class="fas fa-times-circle text-2xl"></i>
            </div>
            <div class="stat-title">ทริปที่ยกเลิก</div>
            <div class="stat-value text-error">{{ $trips->where('status', 'cancelled')->count() }}</div>
        </div>
        
        <div class="stat bg-base-100 rounded-lg shadow">
            <div class="stat-figure text-info">
                <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <div class="stat-title">ทริปที่เสร็จสิ้น</div>
            <div class="stat-value text-info">{{ $trips->where('status', 'completed')->count() }}</div>
        </div>
    </div>

    <!-- Trips Table -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>ชื่อทริป</th>
                            <th>วันที่เดินทาง</th>
                            <th>ราคา</th>
                            <th>จำนวนผู้เข้าร่วม</th>
                            <th>สถานะ</th>
                            <th>ประเภทการยกเลิก</th>
                            <th>ผู้สร้าง</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trips as $trip)
                        <tr>
                            <td>
                                <div class="font-semibold">{{ $trip->trip_name }}</div>
                                @if($trip->trip_description)
                                <div class="text-sm text-base-content/70">{{ Str::limit($trip->trip_description, 50) }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="font-medium">{{ $trip->formatted_date }}</div>
                                <div class="text-sm text-base-content/70">
                                    {{ $trip->trip_date->diffForHumans() }}
                                </div>
                            </td>
                            <td>
                                <div class="font-semibold text-primary">{{ $trip->formatted_price }}</div>
                            </td>
                            <td>
                                <div class="text-sm">{{ $trip->participants_range }}</div>
                            </td>
                            <td>
                                <div class="badge {{ $trip->status_badge_class }}">
                                    @switch($trip->status)
                                        @case('active')
                                            <i class="fas fa-check-circle mr-1"></i>เปิดใช้งาน
                                            @break
                                        @case('cancelled')
                                            <i class="fas fa-times-circle mr-1"></i>ยกเลิก
                                            @break
                                        @case('completed')
                                            <i class="fas fa-calendar-check mr-1"></i>เสร็จสิ้น
                                            @break
                                    @endswitch
                                </div>
                            </td>
                            <td>
                                <div class="badge {{ $trip->cancellation_type_badge_class }}">
                                    @if($trip->cancellation_type === 'automatic')
                                        <i class="fas fa-robot mr-1"></i>อัตโนมัติ
                                    @else
                                        <i class="fas fa-user mr-1"></i>ด้วยตนเอง
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">{{ $trip->creator->first_name }} {{ $trip->creator->last_name }}</div>
                                <div class="text-xs text-base-content/70">{{ $trip->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <button onclick="openTripDetailModal({{ $trip->id }})" 
                                            class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="openEditTripModal({{ $trip->id }})" 
                                            class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($trip->canBeCancelled())
                                    <button onclick="toggleTripStatus({{ $trip->id }}, '{{ $trip->status }}')" 
                                            class="btn btn-sm {{ $trip->status === 'active' ? 'btn-error' : 'btn-success' }}">
                                        <i class="fas fa-{{ $trip->status === 'active' ? 'times' : 'check' }}"></i>
                                    </button>
                                    @endif
                                    <button onclick="deleteTrip({{ $trip->id }})" 
                                            class="btn btn-sm btn-error">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-8">
                                <div class="text-base-content/50">
                                    <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                    <p>ยังไม่มีทริป</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($trips->hasPages())
            <div class="flex justify-center mt-6">
                {{ $trips->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Trip Detail Modal -->
<dialog id="tripDetailModal" class="modal">
    <div class="modal-box max-w-4xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">รายละเอียดทริป</h3>
        <div id="tripDetailContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Add Trip Modal -->
<dialog id="addTripModal" class="modal">
    <div class="modal-box max-w-4xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">เพิ่มทริปใหม่</h3>
        
        <form id="addTripForm">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">ชื่อทริป</span>
                        </label>
                        <input type="text" name="trip_name" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">วันที่เดินทาง</span>
                        </label>
                        <input type="date" name="trip_date" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">ราคาทริป (บาท)</span>
                        </label>
                        <input type="number" name="trip_price" step="0.01" min="0" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">จำนวนผู้เข้าร่วมขั้นต่ำ</span>
                        </label>
                        <input type="number" name="min_participants" min="1" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">จำนวนผู้เข้าร่วมสูงสุด (ไม่บังคับ)</span>
                        </label>
                        <input type="number" name="max_participants" min="1" class="input input-bordered w-full">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">รายละเอียดทริป</span>
                        </label>
                        <textarea name="trip_description" rows="4" class="textarea textarea-bordered w-full"></textarea>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">ประเภทการยกเลิก</span>
                        </label>
                        <select name="cancellation_type" class="select select-bordered w-full" required>
                            <option value="manual">ด้วยตนเอง</option>
                            <option value="automatic">อัตโนมัติ</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">นโยบายการคืนเงิน</span>
                        </label>
                        <textarea name="refund_policy" rows="3" class="textarea textarea-bordered w-full" placeholder="ระบุนโยบายการคืนเงิน..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Cancellation Conditions -->
            <div class="mt-6">
                <h4 class="font-semibold mb-3">เงื่อนไขการยกเลิก</h4>
                <div id="cancellationConditions">
                    <!-- Default conditions will be loaded here -->
                </div>
            </div>

            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-outline">ยกเลิก</button>
                </form>
                <button type="submit" class="btn btn-primary">บันทึกทริป</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Edit Trip Modal -->
<dialog id="editTripModal" class="modal">
    <div class="modal-box max-w-4xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">แก้ไขทริป</h3>
        
        <form id="editTripForm">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">ชื่อทริป</span>
                        </label>
                        <input type="text" name="trip_name" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">วันที่เดินทาง</span>
                        </label>
                        <input type="date" name="trip_date" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">ราคาทริป (บาท)</span>
                        </label>
                        <input type="number" name="trip_price" step="0.01" min="0" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">จำนวนผู้เข้าร่วมขั้นต่ำ</span>
                        </label>
                        <input type="number" name="min_participants" min="1" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">จำนวนผู้เข้าร่วมสูงสุด (ไม่บังคับ)</span>
                        </label>
                        <input type="number" name="max_participants" min="1" class="input input-bordered w-full">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">รายละเอียดทริป</span>
                        </label>
                        <textarea name="trip_description" rows="4" class="textarea textarea-bordered w-full"></textarea>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">ประเภทการยกเลิก</span>
                        </label>
                        <select name="cancellation_type" class="select select-bordered w-full" required>
                            <option value="manual">ด้วยตนเอง</option>
                            <option value="automatic">อัตโนมัติ</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">นโยบายการคืนเงิน</span>
                        </label>
                        <textarea name="refund_policy" rows="3" class="textarea textarea-bordered w-full" placeholder="ระบุนโยบายการคืนเงิน..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Cancellation Conditions -->
            <div class="mt-6">
                <h4 class="font-semibold mb-3">เงื่อนไขการยกเลิก</h4>
                <div id="editCancellationConditions">
                    <!-- Conditions will be loaded here -->
                </div>
            </div>

            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-outline">ยกเลิก</button>
                </form>
                <button type="submit" class="btn btn-primary">อัปเดตทริป</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Confirm Toggle Status Modal -->
<dialog id="confirmToggleModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">ยืนยันการเปลี่ยนสถานะ</h3>
        <p id="toggleConfirmMessage" class="mb-4"></p>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-outline">ยกเลิก</button>
            </form>
            <button id="confirmToggleBtn" class="btn btn-primary">ยืนยัน</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endsection

@push('scripts')
<script>
// Global variables
let currentTripId = null;
let currentStatus = null;

// Show DaisyUI alert
function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-toast');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-toast fixed top-4 right-4 z-50 max-w-sm`;
    alertDiv.innerHTML = `
        <div>
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="btn btn-sm btn-ghost" onclick="this.parentElement.remove()">✕</button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.remove();
        }
    }, 5000);
}

// Modal functions
function openAddTripModal() {
    document.getElementById('addTripModal').showModal();
    loadDefaultCancellationConditions();
}

function openEditTripModal(tripId) {
    currentTripId = tripId;
    document.getElementById('editTripModal').showModal();
    loadTripData(tripId);
}

function openTripDetailModal(tripId) {
    document.getElementById('tripDetailModal').showModal();
    loadTripDetail(tripId);
}

function toggleTripStatus(tripId, status) {
    currentTripId = tripId;
    currentStatus = status;
    
    const message = status === 'active' 
        ? 'คุณต้องการยกเลิกทริปนี้หรือไม่?' 
        : 'คุณต้องการเปิดใช้งานทริปนี้หรือไม่?';
    
    document.getElementById('toggleConfirmMessage').textContent = message;
    document.getElementById('confirmToggleModal').showModal();
}

function performToggleStatus() {
    if (!currentTripId) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    fetch(`/trip-cancellations/${currentTripId}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            document.getElementById('confirmToggleModal').close();
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(data.message || 'เกิดข้อผิดพลาด', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
    });
}

function deleteTrip(tripId) {
    if (!confirm('คุณต้องการลบทริปนี้หรือไม่?')) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    fetch(`/trip-cancellations/${tripId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(data.message || 'เกิดข้อผิดพลาด', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
    });
}

// Load functions
function loadDefaultCancellationConditions() {
    const conditions = @json($defaultConditions);
    const container = document.getElementById('cancellationConditions');
    
    container.innerHTML = conditions.map((condition, index) => `
        <div class="card bg-base-200 mb-2">
            <div class="card-body p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">จำนวนวันก่อนเดินทาง</span>
                        </label>
                        <input type="number" name="cancellation_conditions[${index}][days_before]" 
                               value="${condition.days_before}" class="input input-bordered input-sm" readonly>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">เปอร์เซ็นต์การคืนเงิน</span>
                        </label>
                        <input type="number" name="cancellation_conditions[${index}][refund_percentage]" 
                               value="${condition.refund_percentage}" class="input input-bordered input-sm" readonly>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">คำอธิบาย</span>
                        </label>
                        <input type="text" name="cancellation_conditions[${index}][description]" 
                               value="${condition.description}" class="input input-bordered input-sm" readonly>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function loadTripData(tripId) {
    // This would typically fetch data from the server
    // For now, we'll use a placeholder
    showAlert('กำลังโหลดข้อมูลทริป...', 'info');
}

function loadTripDetail(tripId) {
    // This would typically fetch data from the server
    // For now, we'll use a placeholder
    document.getElementById('tripDetailContent').innerHTML = `
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl mb-4"></i>
            <p>กำลังโหลดข้อมูล...</p>
        </div>
    `;
}

// Form submissions
document.getElementById('addTripForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    fetch('/trip-cancellations', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            document.getElementById('addTripModal').close();
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(data.message || 'เกิดข้อผิดพลาด', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
    });
});

document.getElementById('editTripForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    formData.set('_method', 'PUT');
    
    fetch(`/trip-cancellations/${currentTripId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            document.getElementById('editTripModal').close();
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(data.message || 'เกิดข้อผิดพลาด', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
    });
});

// Event listeners
document.getElementById('confirmToggleBtn').addEventListener('click', function() {
    performToggleStatus();
});

// Set minimum date to tomorrow
document.addEventListener('DOMContentLoaded', function() {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.min = tomorrow.toISOString().split('T')[0];
    });
});
</script>
@endpush
