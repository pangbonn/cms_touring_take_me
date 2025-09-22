@extends('layouts.daisyui')

@section('title', 'จัดการเงื่อนไขการจอง')

@section('content')
<div class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-file-contract text-blue-600 mr-3"></i>
                        จัดการเงื่อนไขการจอง
                    </h1>
                    <p class="text-gray-600 text-lg">จัดการเงื่อนไขและข้อกำหนดการจองทริป</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-end">
                    <button onclick="createDefaultTerms()" class="btn btn-outline btn-lg hover:btn-warning transition-all duration-300">
                        <i class="fas fa-magic mr-2"></i>
                        สร้างเงื่อนไขเริ่มต้น
                    </button>
                    <button onclick="openAddTermModal()" class="btn btn-primary btn-lg hover:btn-secondary transition-all duration-300 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        เพิ่มเงื่อนไขใหม่
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card bg-white shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="card-body text-center">
                    <div class="flex justify-center mb-3">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-contract text-2xl text-blue-600"></i>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">เงื่อนไขทั้งหมด</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $terms->total() }}</p>
                </div>
            </div>
            
            <div class="card bg-white shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="card-body text-center">
                    <div class="flex justify-center mb-3">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-2xl text-green-600"></i>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">เงื่อนไขที่เปิดใช้งาน</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $terms->where('is_active', true)->count() }}</p>
                </div>
            </div>
            
            <div class="card bg-white shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="card-body text-center">
                    <div class="flex justify-center mb-3">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">เงื่อนไขบังคับ</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $terms->where('is_required', true)->count() }}</p>
                </div>
            </div>
            
            <div class="card bg-white shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="card-body text-center">
                    <div class="flex justify-center mb-3">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-tags text-2xl text-purple-600"></i>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">หมวดหมู่</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $terms->pluck('term_category')->unique()->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Terms Table -->
        <div class="card bg-white shadow-2xl rounded-2xl overflow-hidden">
            <div class="card-body p-0">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6">
                    <h2 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-list-alt mr-3"></i>
                        รายการเงื่อนไขการจอง
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-gray-700 font-semibold">ลำดับ</th>
                                <th class="text-gray-700 font-semibold">หัวข้อเงื่อนไข</th>
                                <th class="text-gray-700 font-semibold">หมวดหมู่</th>
                                <th class="text-gray-700 font-semibold">สถานะ</th>
                                <th class="text-gray-700 font-semibold">บังคับ</th>
                                <th class="text-gray-700 font-semibold">การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($terms as $term)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td>
                                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-600 rounded-full font-bold text-lg">
                                        {{ $term->sort_order }}
                                    </div>
                                </td>
                                <td>
                                    <div class="max-w-xs">
                                        <div class="font-semibold text-gray-800 mb-1">{{ $term->term_title }}</div>
                                        <div class="text-sm text-gray-500 line-clamp-2">{{ Str::limit($term->term_content, 80) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $term->category_badge_class }} badge-lg">
                                        {{ $term->category_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $term->status_badge_class }} badge-lg">
                                        @if($term->is_active)
                                            <i class="fas fa-check-circle mr-1"></i>เปิดใช้งาน
                                        @else
                                            <i class="fas fa-times-circle mr-1"></i>ปิดใช้งาน
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $term->required_badge_class }} badge-lg">
                                        @if($term->is_required)
                                            <i class="fas fa-exclamation-triangle mr-1"></i>บังคับ
                                        @else
                                            <i class="fas fa-info-circle mr-1"></i>ไม่บังคับ
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        <button onclick="openTermDetailModal({{ $term->id }})" 
                                                class="btn btn-sm btn-info hover:btn-primary transition-colors duration-200" 
                                                title="ดูรายละเอียด">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="openEditTermModal({{ $term->id }})" 
                                                class="btn btn-sm btn-warning hover:btn-secondary transition-colors duration-200" 
                                                title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="toggleTermStatus({{ $term->id }}, {{ $term->is_active ? 'true' : 'false' }})" 
                                                class="btn btn-sm {{ $term->is_active ? 'btn-error hover:btn-warning' : 'btn-success hover:btn-primary' }} transition-colors duration-200" 
                                                title="{{ $term->is_active ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}">
                                            <i class="fas fa-{{ $term->is_active ? 'times' : 'check' }}"></i>
                                        </button>
                                        <button onclick="toggleTermRequired({{ $term->id }}, {{ $term->is_required ? 'true' : 'false' }})" 
                                                class="btn btn-sm {{ $term->is_required ? 'btn-outline hover:btn-neutral' : 'btn-error hover:btn-warning' }} transition-colors duration-200" 
                                                title="{{ $term->is_required ? 'ยกเลิกบังคับ' : 'ตั้งเป็นบังคับ' }}">
                                            <i class="fas fa-{{ $term->is_required ? 'unlock' : 'lock' }}"></i>
                                        </button>
                                        <button onclick="deleteTerm({{ $term->id }})" 
                                                class="btn btn-sm btn-error hover:btn-warning transition-colors duration-200" 
                                                title="ลบ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-16">
                                    <div class="text-gray-400">
                                        <i class="fas fa-file-contract text-6xl mb-6"></i>
                                        <h3 class="text-xl font-semibold mb-4">ยังไม่มีเงื่อนไขการจอง</h3>
                                        <p class="mb-6">เริ่มต้นด้วยการสร้างเงื่อนไขเริ่มต้นหรือเพิ่มเงื่อนไขใหม่</p>
                                        <button onclick="createDefaultTerms()" class="btn btn-primary btn-lg">
                                            <i class="fas fa-magic mr-2"></i>
                                            สร้างเงื่อนไขเริ่มต้น
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($terms->hasPages())
                <div class="flex justify-center p-6 bg-gray-50">
                    {{ $terms->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>


<!-- Term Detail Modal -->
<dialog id="termDetailModal" class="modal">
    <div class="modal-box max-w-5xl bg-white shadow-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 hover:bg-gray-100">✕</button>
        </form>
        
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 -m-6 mb-6 rounded-t-2xl">
            <h3 class="font-bold text-2xl flex items-center">
                <i class="fas fa-info-circle mr-3"></i>
                รายละเอียดเงื่อนไขการจอง
            </h3>
            <p class="text-indigo-100 mt-2">ดูข้อมูลรายละเอียดของเงื่อนไขการจอง</p>
        </div>
        
        <div id="termDetailContent" class="min-h-96">
            <!-- Content will be loaded here -->
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Add Term Modal -->
<dialog id="addTermModal" class="modal">
    <div class="modal-box max-w-5xl bg-white shadow-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 hover:bg-gray-100">✕</button>
        </form>
        
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6 -m-6 mb-6 rounded-t-2xl">
            <h3 class="font-bold text-2xl flex items-center">
                <i class="fas fa-plus-circle mr-3"></i>
                เพิ่มเงื่อนไขการจองใหม่
            </h3>
            <p class="text-blue-100 mt-2">กรอกข้อมูลเงื่อนไขการจองที่ต้องการเพิ่ม</p>
        </div>
        
        <form id="addTermForm" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-heading text-blue-600 mr-2"></i>
                                หัวข้อเงื่อนไข
                            </span>
                        </label>
                        <input type="text" name="term_title" class="input input-bordered w-full focus:input-primary transition-colors duration-200" 
                               placeholder="ระบุหัวข้อเงื่อนไข..." required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-tags text-green-600 mr-2"></i>
                                หมวดหมู่
                            </span>
                        </label>
                        <select name="term_category" class="select select-bordered w-full focus:select-primary transition-colors duration-200" required>
                            <option value="">เลือกหมวดหมู่...</option>
                            @foreach($categoryOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-sort-numeric-up text-purple-600 mr-2"></i>
                                ลำดับการแสดง
                            </span>
                        </label>
                        <input type="number" name="sort_order" min="0" max="999" value="0" 
                               class="input input-bordered w-full focus:input-primary transition-colors duration-200">
                        <div class="label">
                            <span class="label-text-alt text-gray-500">ตัวเลขน้อย = แสดงก่อน</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label cursor-pointer bg-green-50 p-4 rounded-lg hover:bg-green-100 transition-colors duration-200">
                                <span class="label-text font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-toggle-on text-green-600 mr-2"></i>
                                    เปิดใช้งานทันที
                                </span>
                                <input type="checkbox" name="is_active" class="checkbox checkbox-success" checked>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer bg-red-50 p-4 rounded-lg hover:bg-red-100 transition-colors duration-200">
                                <span class="label-text font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    เป็นเงื่อนไขบังคับ
                                </span>
                                <input type="checkbox" name="is_required" class="checkbox checkbox-error">
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-file-alt text-indigo-600 mr-2"></i>
                                เนื้อหาเงื่อนไข
                            </span>
                        </label>
                        <textarea name="term_content" rows="8" class="textarea textarea-bordered w-full focus:textarea-primary transition-colors duration-200" 
                                  required placeholder="ระบุเนื้อหาเงื่อนไขการจอง..."></textarea>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-info-circle text-cyan-600 mr-2"></i>
                                ข้อมูลเพิ่มเติม
                            </span>
                        </label>
                        <textarea name="additional_info" rows="4" class="textarea textarea-bordered w-full focus:textarea-primary transition-colors duration-200" 
                                  placeholder="ข้อมูลเพิ่มเติมหรือคำแนะนำ..."></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-action bg-gray-50 -m-6 mt-6 p-6 rounded-b-2xl">
                <form method="dialog">
                    <button class="btn btn-outline btn-lg hover:btn-neutral transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        ยกเลิก
                    </button>
                </form>
                <button type="submit" class="btn btn-primary btn-lg hover:btn-secondary transition-colors duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    บันทึกเงื่อนไข
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Edit Term Modal -->
<dialog id="editTermModal" class="modal">
    <div class="modal-box max-w-5xl bg-white shadow-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 hover:bg-gray-100">✕</button>
        </form>
        
        <div class="bg-gradient-to-r from-orange-600 to-red-600 text-white p-6 -m-6 mb-6 rounded-t-2xl">
            <h3 class="font-bold text-2xl flex items-center">
                <i class="fas fa-edit mr-3"></i>
                แก้ไขเงื่อนไขการจอง
            </h3>
            <p class="text-orange-100 mt-2">แก้ไขข้อมูลเงื่อนไขการจองที่เลือก</p>
        </div>
        
        <form id="editTermForm" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-heading text-blue-600 mr-2"></i>
                                หัวข้อเงื่อนไข
                            </span>
                        </label>
                        <input type="text" name="term_title" class="input input-bordered w-full focus:input-primary transition-colors duration-200" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-tags text-green-600 mr-2"></i>
                                หมวดหมู่
                            </span>
                        </label>
                        <select name="term_category" class="select select-bordered w-full focus:select-primary transition-colors duration-200" required>
                            @foreach($categoryOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-sort-numeric-up text-purple-600 mr-2"></i>
                                ลำดับการแสดง
                            </span>
                        </label>
                        <input type="number" name="sort_order" min="0" max="999" class="input input-bordered w-full focus:input-primary transition-colors duration-200">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label cursor-pointer bg-green-50 p-4 rounded-lg hover:bg-green-100 transition-colors duration-200">
                                <span class="label-text font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-toggle-on text-green-600 mr-2"></i>
                                    เปิดใช้งาน
                                </span>
                                <input type="checkbox" name="is_active" class="checkbox checkbox-success">
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer bg-red-50 p-4 rounded-lg hover:bg-red-100 transition-colors duration-200">
                                <span class="label-text font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    เป็นเงื่อนไขบังคับ
                                </span>
                                <input type="checkbox" name="is_required" class="checkbox checkbox-error">
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-file-alt text-indigo-600 mr-2"></i>
                                เนื้อหาเงื่อนไข
                            </span>
                        </label>
                        <textarea name="term_content" rows="8" class="textarea textarea-bordered w-full focus:textarea-primary transition-colors duration-200" required></textarea>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-info-circle text-cyan-600 mr-2"></i>
                                ข้อมูลเพิ่มเติม
                            </span>
                        </label>
                        <textarea name="additional_info" rows="4" class="textarea textarea-bordered w-full focus:textarea-primary transition-colors duration-200"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-action bg-gray-50 -m-6 mt-6 p-6 rounded-b-2xl">
                <form method="dialog">
                    <button class="btn btn-outline btn-lg hover:btn-neutral transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        ยกเลิก
                    </button>
                </form>
                <button type="submit" class="btn btn-warning btn-lg hover:btn-error transition-colors duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    อัปเดตเงื่อนไข
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Confirm Actions Modal -->
<dialog id="confirmActionModal" class="modal">
    <div class="modal-box bg-white shadow-2xl max-w-md">
        <div class="text-center">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-2xl text-yellow-600"></i>
            </div>
            <h3 class="font-bold text-xl mb-4 text-gray-800">ยืนยันการดำเนินการ</h3>
            <p id="confirmActionMessage" class="mb-6 text-gray-600"></p>
            <div class="flex gap-3 justify-center">
                <form method="dialog">
                    <button class="btn btn-outline btn-lg hover:btn-neutral transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        ยกเลิก
                    </button>
                </form>
                <button id="confirmActionBtn" class="btn btn-warning btn-lg hover:btn-error transition-colors duration-200 shadow-lg">
                    <i class="fas fa-check mr-2"></i>
                    ยืนยัน
                </button>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endsection

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar for better UX */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Smooth transitions for all interactive elements */
.btn, .input, .select, .textarea, .checkbox {
    transition: all 0.2s ease-in-out;
}

/* Hover effects for cards */
.card:hover {
    transform: translateY(-2px);
    transition: transform 0.3s ease-in-out;
}
</style>
@endpush

@push('scripts')
<script>
// Global variables
let currentTermId = null;
let currentAction = null;

// Show enhanced alert with animations
function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-toast');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert with enhanced styling
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-toast fixed top-6 right-6 z-50 max-w-sm shadow-2xl transform transition-all duration-300 ease-in-out`;
    alertDiv.style.transform = 'translateX(100%)';
    
    const iconClass = type === 'success' ? 'check-circle' : 
                     type === 'error' ? 'exclamation-circle' : 
                     type === 'warning' ? 'exclamation-triangle' : 'info-circle';
    
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-${iconClass} text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">${message}</p>
            </div>
        </div>
        <button class="btn btn-sm btn-ghost hover:bg-white/20 transition-colors duration-200" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Animate in
    setTimeout(() => {
        alertDiv.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove after 5 seconds with animation
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Modal functions
function openAddTermModal() {
    document.getElementById('addTermModal').showModal();
}

function openEditTermModal(termId) {
    currentTermId = termId;
    document.getElementById('editTermModal').showModal();
    loadTermData(termId);
}

function openTermDetailModal(termId) {
    document.getElementById('termDetailModal').showModal();
    loadTermDetail(termId);
}

function toggleTermStatus(termId, isActive) {
    currentTermId = termId;
    currentAction = 'toggleStatus';
    
    const message = isActive 
        ? 'คุณต้องการปิดใช้เงื่อนไขนี้หรือไม่?' 
        : 'คุณต้องการเปิดใช้เงื่อนไขนี้หรือไม่?';
    
    document.getElementById('confirmActionMessage').textContent = message;
    document.getElementById('confirmActionModal').showModal();
}

function toggleTermRequired(termId, isRequired) {
    currentTermId = termId;
    currentAction = 'toggleRequired';
    
    const message = isRequired 
        ? 'คุณต้องการยกเลิกเงื่อนไขบังคับนี้หรือไม่?' 
        : 'คุณต้องการตั้งเงื่อนไขนี้เป็นบังคับหรือไม่?';
    
    document.getElementById('confirmActionMessage').textContent = message;
    document.getElementById('confirmActionModal').showModal();
}

function deleteTerm(termId) {
    currentTermId = termId;
    currentAction = 'delete';
    
    document.getElementById('confirmActionMessage').textContent = 'คุณต้องการลบเงื่อนไขนี้หรือไม่?';
    document.getElementById('confirmActionModal').showModal();
}

function createDefaultTerms() {
    if (!confirm('คุณต้องการสร้างเงื่อนไขเริ่มต้นหรือไม่?')) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    fetch('/booking-terms/create-default', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
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

function performAction() {
    if (!currentTermId || !currentAction) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    let url = '';
    let method = 'PATCH';
    
    switch(currentAction) {
        case 'toggleStatus':
            url = `/booking-terms/${currentTermId}/toggle-status`;
            break;
        case 'toggleRequired':
            url = `/booking-terms/${currentTermId}/toggle-required`;
            break;
        case 'delete':
            url = `/booking-terms/${currentTermId}`;
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
function loadTermData(termId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    fetch(`/booking-terms/${termId}/details`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.term) {
            const term = data.term;
            
            // Fill form fields
            document.querySelector('#editTermForm input[name="term_title"]').value = term.term_title;
            document.querySelector('#editTermForm select[name="term_category"]').value = term.term_category;
            document.querySelector('#editTermForm textarea[name="term_content"]').value = term.term_content;
            document.querySelector('#editTermForm input[name="sort_order"]').value = term.sort_order;
            document.querySelector('#editTermForm input[name="is_active"]').checked = term.is_active;
            document.querySelector('#editTermForm input[name="is_required"]').checked = term.is_required;
            document.querySelector('#editTermForm textarea[name="additional_info"]').value = term.additional_info || '';
            
        } else {
            showAlert('ไม่สามารถโหลดข้อมูลได้', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
    });
}

function loadTermDetail(termId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    // Show loading
    document.getElementById('termDetailContent').innerHTML = `
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl mb-4"></i>
            <p>กำลังโหลดข้อมูล...</p>
        </div>
    `;
    
    fetch(`/booking-terms/${termId}/details`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.term) {
            const term = data.term;
            document.getElementById('termDetailContent').innerHTML = `
                <div class="space-y-6">
                    <!-- Term Header -->
                    <div class="card bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200">
                        <div class="card-body">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <div>
                                    <h4 class="text-2xl font-bold text-gray-800 mb-2">${term.term_title}</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="badge ${term.category_badge_class} badge-lg">
                                            <i class="fas fa-tag mr-1"></i>${term.category_label}
                                        </span>
                                        <span class="badge ${term.status_badge_class} badge-lg">
                                            <i class="fas fa-${term.is_active ? 'check-circle' : 'times-circle'} mr-1"></i>${term.status_label}
                                        </span>
                                        <span class="badge ${term.required_badge_class} badge-lg">
                                            <i class="fas fa-${term.is_required ? 'exclamation-triangle' : 'info-circle'} mr-1"></i>${term.required_label}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">ลำดับการแสดง</div>
                                    <div class="text-3xl font-bold text-blue-600">${term.sort_order}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Term Content -->
                    <div class="card bg-white border border-gray-200">
                        <div class="card-body">
                            <h5 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                                <i class="fas fa-file-alt text-indigo-600 mr-2"></i>
                                เนื้อหาเงื่อนไข
                            </h5>
                            <div class="prose max-w-none">
                                <div class="text-gray-700 whitespace-pre-line leading-relaxed">${term.term_content}</div>
                            </div>
                        </div>
                    </div>
                    
                    ${term.additional_info ? `
                    <!-- Additional Info -->
                    <div class="card bg-white border border-gray-200">
                        <div class="card-body">
                            <h5 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                                <i class="fas fa-info-circle text-cyan-600 mr-2"></i>
                                ข้อมูลเพิ่มเติม
                            </h5>
                            <div class="prose max-w-none">
                                <div class="text-gray-700 whitespace-pre-line leading-relaxed">${term.additional_info}</div>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Term Details -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="card bg-white border border-gray-200">
                            <div class="card-body">
                                <h5 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                                    <i class="fas fa-user text-green-600 mr-2"></i>
                                    ข้อมูลผู้สร้าง
                                </h5>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">ผู้สร้าง:</span>
                                        <span class="font-semibold text-gray-800">${term.creator.first_name} ${term.creator.last_name}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">วันที่สร้าง:</span>
                                        <span class="font-semibold text-gray-800">${new Date(term.created_at).toLocaleDateString('th-TH', { 
                                            year: 'numeric', 
                                            month: 'long', 
                                            day: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        })}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600 font-medium">อัปเดตล่าสุด:</span>
                                        <span class="font-semibold text-gray-800">${new Date(term.updated_at).toLocaleDateString('th-TH', { 
                                            year: 'numeric', 
                                            month: 'long', 
                                            day: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        })}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-white border border-gray-200">
                            <div class="card-body">
                                <h5 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                                    <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                                    สถิติการใช้งาน
                                </h5>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">สถานะ:</span>
                                        <span class="badge ${term.status_badge_class} badge-lg">
                                            ${term.is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน'}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">ประเภท:</span>
                                        <span class="badge ${term.required_badge_class} badge-lg">
                                            ${term.is_required ? 'บังคับ' : 'ไม่บังคับ'}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600 font-medium">หมวดหมู่:</span>
                                        <span class="badge ${term.category_badge_class} badge-lg">
                                            ${term.category_label}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            document.getElementById('termDetailContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-circle text-2xl mb-4 text-error"></i>
                    <p>ไม่สามารถโหลดข้อมูลได้</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('termDetailContent').innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-circle text-2xl mb-4 text-error"></i>
                <p>เกิดข้อผิดพลาดในการโหลดข้อมูล</p>
            </div>
        `;
    });
}

// Form submissions
document.getElementById('addTermForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    fetch('/booking-terms', {
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
            document.getElementById('addTermModal').close();
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

document.getElementById('editTermForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    formData.set('_method', 'PUT');
    
    fetch(`/booking-terms/${currentTermId}`, {
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
            document.getElementById('editTermModal').close();
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
</script>
@endpush
