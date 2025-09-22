@extends('layouts.daisyui')

@section('title', 'จัดการเงื่อนไขการยกเลิกทริป')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-base-content">จัดการเงื่อนไขการยกเลิกทริป</h1>
            <p class="text-base-content/70 mt-1">จัดการนโยบายและเงื่อนไขการยกเลิกทริป</p>
        </div>
        <button onclick="openAddPolicyModal()" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>
            เพิ่มนโยบายใหม่
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat bg-base-100 rounded-lg shadow">
            <div class="stat-figure text-primary">
                <i class="fas fa-file-contract text-2xl"></i>
            </div>
            <div class="stat-title">นโยบายทั้งหมด</div>
            <div class="stat-value text-primary">{{ $policies->total() }}</div>
        </div>
        
        <div class="stat bg-base-100 rounded-lg shadow">
            <div class="stat-figure text-success">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <div class="stat-title">นโยบายที่เปิดใช้งาน</div>
            <div class="stat-value text-success">{{ $policies->where('is_active', true)->count() }}</div>
        </div>
        
        <div class="stat bg-base-100 rounded-lg shadow">
            <div class="stat-figure text-warning">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <div class="stat-title">เหตุสุดวิสัย</div>
            <div class="stat-value text-warning">{{ $policies->where('policy_type', 'force_majeure')->count() }}</div>
        </div>
        
        <div class="stat bg-base-100 rounded-lg shadow">
            <div class="stat-figure text-info">
                <i class="fas fa-map-marker-alt text-2xl"></i>
            </div>
            <div class="stat-title">เฉพาะสถานที่</div>
            <div class="stat-value text-info">{{ $policies->where('policy_type', 'location_specific')->count() }}</div>
        </div>
    </div>

    <!-- Policies Table -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>ชื่อนโยบาย</th>
                            <th>ประเภท</th>
                            <th>สถานที่ใช้บังคับ</th>
                            <th>สถานะ</th>
                            <th>เริ่มต้น</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($policies as $policy)
                        <tr>
                            <td class="w-48">
                                <div class="font-semibold">{{ $policy->policy_name }}</div>
                                @if($policy->policy_description)
                                <div class="text-sm text-base-content/70">{{ Str::limit($policy->policy_description, 30) }}</div>
                                @endif
                            </td>
                            <td class="w-24">
                                <div class="badge {{ $policy->policy_type_badge_class }}">
                                    @switch($policy->policy_type)
                                        @case('standard')
                                            <i class="fas fa-file-contract mr-1"></i>มาตรฐาน
                                            @break
                                        @case('force_majeure')
                                            <i class="fas fa-exclamation-triangle mr-1"></i>เหตุสุดวิสัย
                                            @break
                                        @case('location_specific')
                                            <i class="fas fa-map-marker-alt mr-1"></i>เฉพาะสถานที่
                                            @break
                                    @endswitch
                                </div>
                            </td>
                            <td class="w-32">
                                <div class="text-sm truncate" title="{{ $policy->applicable_locations_string }}">
                                    {{ Str::limit($policy->applicable_locations_string, 20) }}
                                </div>
                            </td>
                            <td class="w-20">
                                <div class="badge {{ $policy->status_badge_class }}">
                                    @if($policy->is_active)
                                        <i class="fas fa-check-circle mr-1"></i>เปิดใช้งาน
                                    @else
                                        <i class="fas fa-times-circle mr-1"></i>ปิดใช้งาน
                                    @endif
                                </div>
                            </td>
                            <td class="w-20">
                                @if($policy->is_default)
                                    <div class="badge badge-warning">
                                        <i class="fas fa-star mr-1"></i>เริ่มต้น
                                    </div>
                                @else
                                    <div class="text-base-content/50">-</div>
                                @endif
                            </td>
                            
                            <td class="w-40">
                                <div class="flex gap-1 flex-wrap">
                                    <button onclick="openPolicyDetailModal({{ $policy->id }})" 
                                            class="btn btn-xs btn-info">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="openEditPolicyModal({{ $policy->id }})" 
                                            class="btn btn-xs btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if(!$policy->is_default)
                                    <button onclick="setAsDefault({{ $policy->id }})" 
                                            class="btn btn-xs btn-success">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    @endif
                                    <button onclick="togglePolicyStatus({{ $policy->id }}, {{ $policy->is_active ? 'true' : 'false' }})" 
                                            class="btn btn-xs {{ $policy->is_active ? 'btn-error' : 'btn-success' }}">
                                        <i class="fas fa-{{ $policy->is_active ? 'times' : 'check' }}"></i>
                                    </button>
                                    @if(!$policy->is_default)
                                    <button onclick="deletePolicy({{ $policy->id }})" 
                                            class="btn btn-xs btn-error">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8">
                                <div class="text-base-content/50">
                                    <i class="fas fa-file-contract text-4xl mb-4"></i>
                                    <p>ยังไม่มีนโยบายการยกเลิก</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($policies->hasPages())
            <div class="flex justify-center mt-6">
                {{ $policies->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Policy Detail Modal -->
<dialog id="policyDetailModal" class="modal">
    <div class="modal-box max-w-4xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">รายละเอียดนโยบายการยกเลิก</h3>
        <div id="policyDetailContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Add Policy Modal -->
<dialog id="addPolicyModal" class="modal">
    <div class="modal-box max-w-6xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">เพิ่มนโยบายการยกเลิกใหม่</h3>
        
        <form id="addPolicyForm">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">ชื่อนโยบาย</span>
                        </label>
                        <input type="text" name="policy_name" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">ประเภทนโยบาย</span>
                        </label>
                        <select name="policy_type" class="select select-bordered w-full" required onchange="togglePolicyTypeFields()">
                            <option value="standard">มาตรฐาน</option>
                            <option value="force_majeure">เหตุสุดวิสัย</option>
                            <option value="location_specific">เฉพาะสถานที่</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">รายละเอียดนโยบาย</span>
                        </label>
                        <textarea name="policy_description" rows="3" class="textarea textarea-bordered w-full" placeholder="อธิบายรายละเอียดนโยบาย..."></textarea>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">ลำดับความสำคัญ</span>
                        </label>
                        <input type="number" name="priority" min="0" max="999" value="0" class="input input-bordered w-full">
                        <div class="label">
                            <span class="label-text-alt">ตัวเลขสูงสุด = ความสำคัญสูงสุด</span>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <span class="label-text font-semibold">เปิดใช้งานทันที</span>
                            <input type="checkbox" name="is_active" class="checkbox checkbox-primary" checked>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <span class="label-text font-semibold">ตั้งเป็นนโยบายเริ่มต้น</span>
                            <input type="checkbox" name="is_default" class="checkbox checkbox-warning">
                        </label>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Force Majeure Conditions -->
                    <div id="forceMajeureSection" class="form-control" style="display: none;">
                        <label class="label">
                            <span class="label-text font-semibold">เงื่อนไขเหตุสุดวิสัย</span>
                        </label>
                        <textarea name="force_majeure_conditions" rows="4" class="textarea textarea-bordered w-full" placeholder="ระบุเงื่อนไขเหตุสุดวิสัย เช่น ภัยพิบัติ โรคระบาด สงคราม..."></textarea>
                        <div class="label">
                            <span class="label-text-alt">ตัวอย่าง: แผ่นดินไหว น้ำท่วม พายุ โควิด-19 รัฐประหาร</span>
                        </div>
                    </div>

                    <!-- Applicable Locations -->
                    <div id="locationsSection" class="form-control" style="display: none;">
                        <label class="label">
                            <span class="label-text font-semibold">สถานที่ที่ใช้บังคับ</span>
                        </label>
                        <div class="max-h-40 overflow-y-auto border rounded-lg p-2">
                            @foreach($commonLocations as $location)
                            <label class="label cursor-pointer">
                                <span class="label-text">{{ $location }}</span>
                                <input type="checkbox" name="applicable_locations[]" value="{{ $location }}" class="checkbox checkbox-sm">
                            </label>
                            @endforeach
                        </div>
                        <div class="label">
                            <span class="label-text-alt">เลือกสถานที่ที่ต้องการใช้บังคับนโยบายนี้</span>
                        </div>
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
                <button type="submit" class="btn btn-primary">บันทึกนโยบาย</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Edit Policy Modal -->
<dialog id="editPolicyModal" class="modal">
    <div class="modal-box max-w-6xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">แก้ไขนโยบายการยกเลิก</h3>
        
        <form id="editPolicyForm">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">ชื่อนโยบาย</span>
                        </label>
                        <input type="text" name="policy_name" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">ประเภทนโยบาย</span>
                        </label>
                        <select name="policy_type" class="select select-bordered w-full" required onchange="togglePolicyTypeFields()">
                            <option value="standard">มาตรฐาน</option>
                            <option value="force_majeure">เหตุสุดวิสัย</option>
                            <option value="location_specific">เฉพาะสถานที่</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">รายละเอียดนโยบาย</span>
                        </label>
                        <textarea name="policy_description" rows="3" class="textarea textarea-bordered w-full" placeholder="อธิบายรายละเอียดนโยบาย..."></textarea>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">ลำดับความสำคัญ</span>
                        </label>
                        <input type="number" name="priority" min="0" max="999" class="input input-bordered w-full">
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <span class="label-text font-semibold">เปิดใช้งาน</span>
                            <input type="checkbox" name="is_active" class="checkbox checkbox-primary">
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <span class="label-text font-semibold">ตั้งเป็นนโยบายเริ่มต้น</span>
                            <input type="checkbox" name="is_default" class="checkbox checkbox-warning">
                        </label>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Force Majeure Conditions -->
                    <div id="editForceMajeureSection" class="form-control" style="display: none;">
                        <label class="label">
                            <span class="label-text font-semibold">เงื่อนไขเหตุสุดวิสัย</span>
                        </label>
                        <textarea name="force_majeure_conditions" rows="4" class="textarea textarea-bordered w-full" placeholder="ระบุเงื่อนไขเหตุสุดวิสัย เช่น ภัยพิบัติ โรคระบาด สงคราม..."></textarea>
                    </div>

                    <!-- Applicable Locations -->
                    <div id="editLocationsSection" class="form-control" style="display: none;">
                        <label class="label">
                            <span class="label-text font-semibold">สถานที่ที่ใช้บังคับ</span>
                        </label>
                        <div class="max-h-40 overflow-y-auto border rounded-lg p-2">
                            @foreach($commonLocations as $location)
                            <label class="label cursor-pointer">
                                <span class="label-text">{{ $location }}</span>
                                <input type="checkbox" name="applicable_locations[]" value="{{ $location }}" class="checkbox checkbox-sm">
                            </label>
                            @endforeach
                        </div>
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
                <button type="submit" class="btn btn-primary">อัปเดตนโยบาย</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Confirm Actions Modal -->
<dialog id="confirmActionModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">ยืนยันการดำเนินการ</h3>
        <p id="confirmActionMessage" class="mb-4"></p>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-outline">ยกเลิก</button>
            </form>
            <button id="confirmActionBtn" class="btn btn-primary">ยืนยัน</button>
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
let currentPolicyId = null;
let currentAction = null;

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
function openAddPolicyModal() {
    document.getElementById('addPolicyModal').showModal();
    loadDefaultCancellationConditions();
}

function openEditPolicyModal(policyId) {
    currentPolicyId = policyId;
    document.getElementById('editPolicyModal').showModal();
    loadPolicyData(policyId);
}

function openPolicyDetailModal(policyId) {
    document.getElementById('policyDetailModal').showModal();
    loadPolicyDetail(policyId);
}

function togglePolicyTypeFields() {
    const policyType = document.querySelector('select[name="policy_type"]').value;
    const forceMajeureSection = document.getElementById('forceMajeureSection');
    const locationsSection = document.getElementById('locationsSection');
    const editForceMajeureSection = document.getElementById('editForceMajeureSection');
    const editLocationsSection = document.getElementById('editLocationsSection');
    
    // Hide all sections first
    forceMajeureSection.style.display = 'none';
    locationsSection.style.display = 'none';
    editForceMajeureSection.style.display = 'none';
    editLocationsSection.style.display = 'none';
    
    // Show relevant sections based on policy type
    if (policyType === 'force_majeure') {
        forceMajeureSection.style.display = 'block';
        editForceMajeureSection.style.display = 'block';
    } else if (policyType === 'location_specific') {
        locationsSection.style.display = 'block';
        editLocationsSection.style.display = 'block';
    }
}

function togglePolicyStatus(policyId, isActive) {
    currentPolicyId = policyId;
    currentAction = 'toggle';
    
    const message = isActive 
        ? 'คุณต้องการปิดใช้นโยบายนี้หรือไม่?' 
        : 'คุณต้องการเปิดใช้นโยบายนี้หรือไม่?';
    
    document.getElementById('confirmActionMessage').textContent = message;
    document.getElementById('confirmActionModal').showModal();
}

function setAsDefault(policyId) {
    currentPolicyId = policyId;
    currentAction = 'setDefault';
    
    document.getElementById('confirmActionMessage').textContent = 'คุณต้องการตั้งนโยบายนี้เป็นนโยบายเริ่มต้นหรือไม่?';
    document.getElementById('confirmActionModal').showModal();
}

function deletePolicy(policyId) {
    currentPolicyId = policyId;
    currentAction = 'delete';
    
    document.getElementById('confirmActionMessage').textContent = 'คุณต้องการลบนโยบายนี้หรือไม่?';
    document.getElementById('confirmActionModal').showModal();
}

function performAction() {
    if (!currentPolicyId || !currentAction) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    let url = '';
    let method = 'PATCH';
    
    switch(currentAction) {
        case 'toggle':
            url = `/cancellation-policies/${currentPolicyId}/toggle-status`;
            break;
        case 'setDefault':
            url = `/cancellation-policies/${currentPolicyId}/set-default`;
            break;
        case 'delete':
            url = `/cancellation-policies/${currentPolicyId}`;
            method = 'DELETE';
            break;
    }
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            document.getElementById('confirmActionModal').close();
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
                               value="${condition.days_before}" class="input input-bordered input-sm" required>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">เปอร์เซ็นต์การคืนเงิน</span>
                        </label>
                        <input type="number" name="cancellation_conditions[${index}][refund_percentage]" 
                               value="${condition.refund_percentage}" class="input input-bordered input-sm" required min="0" max="100">
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">คำอธิบาย</span>
                        </label>
                        <input type="text" name="cancellation_conditions[${index}][description]" 
                               value="${condition.description}" class="input input-bordered input-sm" required>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function loadPolicyData(policyId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    fetch(`/cancellation-policies/${policyId}/details`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.policy) {
            const policy = data.policy;
            
            // Fill form fields
            document.querySelector('#editPolicyForm input[name="policy_name"]').value = policy.policy_name;
            document.querySelector('#editPolicyForm select[name="policy_type"]').value = policy.policy_type;
            document.querySelector('#editPolicyForm textarea[name="policy_description"]').value = policy.policy_description || '';
            document.querySelector('#editPolicyForm input[name="priority"]').value = policy.priority;
            document.querySelector('#editPolicyForm input[name="is_active"]').checked = policy.is_active;
            document.querySelector('#editPolicyForm input[name="is_default"]').checked = policy.is_default;
            
            // Handle force majeure conditions
            if (policy.policy_type === 'force_majeure') {
                document.querySelector('#editPolicyForm textarea[name="force_majeure_conditions"]').value = policy.force_majeure_conditions || '';
            }
            
            // Handle applicable locations
            if (policy.policy_type === 'location_specific' && policy.applicable_locations) {
                policy.applicable_locations.forEach(location => {
                    const checkbox = document.querySelector(`#editPolicyForm input[name="applicable_locations[]"][value="${location}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }
            
            // Load cancellation conditions
            loadEditCancellationConditions(policy.cancellation_conditions);
            
            // Toggle fields based on policy type
            togglePolicyTypeFields();
            
        } else {
            showAlert('ไม่สามารถโหลดข้อมูลได้', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
    });
}

function loadEditCancellationConditions(conditions) {
    const container = document.getElementById('editCancellationConditions');
    
    container.innerHTML = conditions.map((condition, index) => `
        <div class="card bg-base-200 mb-2">
            <div class="card-body p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">จำนวนวันก่อนเดินทาง</span>
                        </label>
                        <input type="number" name="cancellation_conditions[${index}][days_before]" 
                               value="${condition.days_before}" class="input input-bordered input-sm" required>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">เปอร์เซ็นต์การคืนเงิน</span>
                        </label>
                        <input type="number" name="cancellation_conditions[${index}][refund_percentage]" 
                               value="${condition.refund_percentage}" class="input input-bordered input-sm" required min="0" max="100">
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">คำอธิบาย</span>
                        </label>
                        <input type="text" name="cancellation_conditions[${index}][description]" 
                               value="${condition.description}" class="input input-bordered input-sm" required>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function loadPolicyDetail(policyId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    // Show loading
    document.getElementById('policyDetailContent').innerHTML = `
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl mb-4"></i>
            <p>กำลังโหลดข้อมูล...</p>
        </div>
    `;
    
    fetch(`/cancellation-policies/${policyId}/details`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.policy) {
            const policy = data.policy;
            document.getElementById('policyDetailContent').innerHTML = `
                <div class="space-y-6">
                    <!-- Policy Header -->
                    <div class="card bg-base-200">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <h4 class="card-title">${policy.policy_name}</h4>
                                <div class="flex gap-2">
                                    <div class="badge ${getPolicyTypeBadgeClass(policy.policy_type)}">${getPolicyTypeLabel(policy.policy_type)}</div>
                                    <div class="badge ${policy.is_active ? 'badge-success' : 'badge-error'}">${policy.is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน'}</div>
                                    ${policy.is_default ? '<div class="badge badge-warning"><i class="fas fa-star mr-1"></i>เริ่มต้น</div>' : ''}
                                </div>
                            </div>
                            ${policy.policy_description ? `<p class="text-base-content/70 mt-2">${policy.policy_description}</p>` : ''}
                        </div>
                    </div>
                    
                    <!-- Policy Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="card bg-base-100">
                            <div class="card-body">
                                <h5 class="font-semibold mb-3">ข้อมูลนโยบาย</h5>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-base-content/70">ลำดับความสำคัญ:</span>
                                        <span class="font-medium">${policy.priority}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-base-content/70">ผู้สร้าง:</span>
                                        <span class="font-medium">${policy.creator ? policy.creator.first_name + ' ' + policy.creator.last_name : 'ไม่ระบุ'}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-base-content/70">วันที่สร้าง:</span>
                                        <span class="font-medium">${policy.created_at ? new Date(policy.created_at).toLocaleDateString('th-TH') : 'ไม่ระบุ'}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-base-content/70">อัปเดตล่าสุด:</span>
                                        <span class="font-medium">${policy.updated_at ? new Date(policy.updated_at).toLocaleDateString('th-TH') : 'ไม่ระบุ'}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-base-100">
                            <div class="card-body">
                                <h5 class="font-semibold mb-3">สถานที่ใช้บังคับ</h5>
                                <div class="text-sm">
                                    ${policy.applicable_locations || 'ไม่ระบุ'}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Force Majeure Conditions -->
                    ${policy.policy_type === 'force_majeure' && policy.force_majeure_conditions ? `
                    <div class="card bg-base-100">
                        <div class="card-body">
                            <h5 class="font-semibold mb-3">เงื่อนไขเหตุสุดวิสัย</h5>
                            <div class="text-sm whitespace-pre-line">${policy.force_majeure_conditions}</div>
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Cancellation Conditions -->
                    <div class="card bg-base-100">
                        <div class="card-body">
                            <h5 class="font-semibold mb-3">เงื่อนไขการยกเลิก</h5>
                            <div class="overflow-x-auto">
                                <table class="table table-zebra table-sm w-full">
                                    <thead>
                                        <tr>
                                            <th>จำนวนวันก่อนเดินทาง</th>
                                            <th>เปอร์เซ็นต์การคืนเงิน</th>
                                            <th>คำอธิบาย</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${policy.cancellation_conditions.map(condition => `
                                            <tr>
                                                <td>${condition.days_before} วัน</td>
                                                <td>${condition.refund_percentage}%</td>
                                                <td>${condition.description}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            document.getElementById('policyDetailContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-circle text-2xl mb-4 text-error"></i>
                    <p>ไม่สามารถโหลดข้อมูลได้</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('policyDetailContent').innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-circle text-2xl mb-4 text-error"></i>
                <p>เกิดข้อผิดพลาดในการโหลดข้อมูล</p>
            </div>
        `;
    });
}

// Form submissions
document.getElementById('addPolicyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    fetch('/cancellation-policies', {
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
            document.getElementById('addPolicyModal').close();
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

document.getElementById('editPolicyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    formData.set('_method', 'PUT');
    
    fetch(`/cancellation-policies/${currentPolicyId}`, {
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
            document.getElementById('editPolicyModal').close();
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
document.getElementById('confirmActionBtn').addEventListener('click', function() {
    performAction();
});

// Helper functions for policy type
function getPolicyTypeBadgeClass(policyType) {
    switch(policyType) {
        case 'standard':
            return 'badge-primary';
        case 'force_majeure':
            return 'badge-warning';
        case 'location_specific':
            return 'badge-info';
        default:
            return 'badge-neutral';
    }
}

function getPolicyTypeLabel(policyType) {
    switch(policyType) {
        case 'standard':
            return 'มาตรฐาน';
        case 'force_majeure':
            return 'เหตุสุดวิสัย';
        case 'location_specific':
            return 'เฉพาะสถานที่';
        default:
            return 'ไม่ระบุ';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load default conditions for add modal
    loadDefaultCancellationConditions();
});
</script>
@endpush
