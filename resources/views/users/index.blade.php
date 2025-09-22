@extends('layouts.daisyui')

@section('title', 'จัดการผู้ใช้งาน')
@section('page-title', 'จัดการผู้ใช้งาน')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-base-content">จัดการผู้ใช้งาน</h1>
            <p class="text-base-content/70 mt-1">จัดการข้อมูลผู้ใช้งานในระบบ</p>
        </div>
        <button onclick="openAddUserModal()" class="btn btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            เพิ่มผู้ใช้งาน
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat bg-base-100 shadow-xl rounded-box">
            <div class="stat-figure text-primary">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
            <div class="stat-title">ผู้ใช้งานทั้งหมด</div>
            <div class="stat-value text-primary">{{ $users->total() }}</div>
        </div>
        
        <div class="stat bg-base-100 shadow-xl rounded-box">
            <div class="stat-figure text-success">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-title">ผู้ใช้งานที่เปิดใช้งาน</div>
            <div class="stat-value text-success">{{ $users->where('is_active', true)->count() }}</div>
        </div>
        
        <div class="stat bg-base-100 shadow-xl rounded-box">
            <div class="stat-figure text-warning">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div class="stat-title">ผู้ดูแลระบบ</div>
            <div class="stat-value text-warning">{{ $users->where('role.name', 'superadmin')->count() + $users->where('role.name', 'admin')->count() }}</div>
        </div>
        
        <div class="stat bg-base-100 shadow-xl rounded-box">
            <div class="stat-figure text-error">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="stat-title">ต้องเปลี่ยนรหัสผ่าน</div>
            <div class="stat-value text-error">{{ $users->where('must_change_password', true)->count() }}</div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>ผู้ใช้งาน</th>
                            <th>อีเมล</th>
                            <th>สิทธิ์</th>
                            <th>สถานะ</th>
                            <th>วันที่สร้าง</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="hover">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar">
                                            <div class="w-12 h-12 rounded-full">
                                                <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->getFullName() }}">
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ $user->getFullName() }}</div>
                                            @if($user->must_change_password)
                                                <div class="badge badge-warning badge-sm">ต้องเปลี่ยนรหัสผ่าน</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <div class="badge badge-outline">
                                        {{ $user->getRoleDisplayName() }}
                                    </div>
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <div class="badge badge-success gap-2">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            เปิดใช้งาน
                                        </div>
                                    @else
                                        <div class="badge badge-error gap-2">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            ปิดใช้งาน
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="flex gap-1">
                                        <button onclick="openUserDetailModal({{ $user->id }})" class="btn btn-sm btn-info btn-square">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="openEditUserModal({{ $user->id }})" class="btn btn-sm btn-warning btn-square">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        @if(!$user->isSuperAdmin())
                                            <button onclick="toggleUserStatus({{ $user->id }})" class="btn btn-sm {{ $user->is_active ? 'btn-error' : 'btn-success' }} btn-square">
                                                @if($user->is_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                @endif
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8">
                                    <div class="text-base-content/70">ไม่พบข้อมูลผู้ใช้งาน</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-4 border-t border-base-300">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- User Detail Modal -->
<dialog id="userDetailModal" class="modal">
    <div class="modal-box max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">รายละเอียดผู้ใช้งาน</h3>
        
        <div id="userDetailContent">
            <!-- Content will be loaded here -->
        </div>
        
        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-outline">ปิด</button>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Add User Modal -->
<dialog id="addUserModal" class="modal">
    <div class="modal-box max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">เพิ่มผู้ใช้งานใหม่</h3>
        
        <form id="addUserForm" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- First Name -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">ชื่อ</span>
                    </label>
                    <input type="text" name="first_name" class="input input-bordered w-full" placeholder="กรอกชื่อ" required>
                </div>

                <!-- Last Name -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">นามสกุล</span>
                    </label>
                    <input type="text" name="last_name" class="input input-bordered w-full" placeholder="กรอกนามสกุล" required>
                </div>

                <!-- Email -->
                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text">อีเมล</span>
                    </label>
                    <input type="email" name="email" class="input input-bordered w-full" placeholder="กรอกอีเมล" required>
                </div>

                <!-- Password -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">รหัสผ่าน</span>
                    </label>
                    <input type="password" name="password" class="input input-bordered w-full" placeholder="กรอกรหัสผ่าน" required>
                </div>

                <!-- Password Confirmation -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">ยืนยันรหัสผ่าน</span>
                    </label>
                    <input type="password" name="password_confirmation" class="input input-bordered w-full" placeholder="ยืนยันรหัสผ่าน" required>
                </div>

                <!-- Role -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">สิทธิ์</span>
                    </label>
                    <select name="role_id" class="select select-bordered w-full" required>
                        <option value="">เลือกสิทธิ์</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Avatar -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">รูปภาพ</span>
                        <span class="label-text-alt">ขนาดไม่เกิน 2MB (ระบบจะบีบอัดให้เหลือ 500KB)</span>
                    </label>
                    <input type="file" name="avatar" class="file-input file-input-bordered w-full" accept="image/jpeg,image/png,image/jpg">
                    <div class="label">
                        <span class="label-text-alt text-info">รองรับไฟล์: JPEG, PNG</span>
                    </div>
                </div>
                <div class="form-control md:col-span-2">
                    <label class="cursor-pointer label">
                        <span class="label-text">บังคับเปลี่ยนรหัสผ่านครั้งแรก</span>
                        <input type="checkbox" name="must_change_password" value="1" class="checkbox checkbox-primary" checked>
                    </label>
                </div>
            </div>
            
            <div class="modal-action">
                <form method="dialog">
                    <button type="button" class="btn btn-outline">ยกเลิก</button>
                </form>
                <button type="submit" class="btn btn-primary">เพิ่มผู้ใช้งาน</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Edit User Modal -->
<dialog id="editUserModal" class="modal">
    <div class="modal-box max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">แก้ไขผู้ใช้งาน</h3>
        
        <form id="editUserForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- First Name -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">ชื่อ</span>
                    </label>
                    <input type="text" name="first_name" class="input input-bordered w-full" placeholder="กรอกชื่อ" required>
                </div>

                <!-- Last Name -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">นามสกุล</span>
                    </label>
                    <input type="text" name="last_name" class="input input-bordered w-full" placeholder="กรอกนามสกุล" required>
                </div>

                <!-- Email -->
                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text">อีเมล</span>
                    </label>
                    <input type="email" name="email" class="input input-bordered w-full" placeholder="กรอกอีเมล" required>
                </div>

                <!-- Password -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">รหัสผ่านใหม่ (เว้นว่างไว้หากไม่ต้องการเปลี่ยน)</span>
                    </label>
                    <input type="password" name="password" class="input input-bordered w-full" placeholder="กรอกรหัสผ่านใหม่">
                </div>

                <!-- Password Confirmation -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">ยืนยันรหัสผ่านใหม่</span>
                    </label>
                    <input type="password" name="password_confirmation" class="input input-bordered w-full" placeholder="ยืนยันรหัสผ่านใหม่">
                </div>

                <!-- Role -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">สิทธิ์</span>
                    </label>
                    <select name="role_id" class="select select-bordered w-full" required>
                        <option value="">เลือกสิทธิ์</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Avatar -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">รูปภาพ</span>
                        <span class="label-text-alt">ขนาดไม่เกิน 2MB (ระบบจะบีบอัดให้เหลือ 500KB)</span>
                    </label>
                    <input type="file" name="avatar" class="file-input file-input-bordered w-full" accept="image/jpeg,image/png,image/jpg">
                    <div class="label">
                        <span class="label-text-alt text-info">รองรับไฟล์: JPEG, PNG</span>
                    </div>
                </div>

                <!-- Status -->
                <div class="form-control">
                    <label class="cursor-pointer label">
                        <span class="label-text">เปิดใช้งาน</span>
                        <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary">
                    </label>
                </div>

                <!-- Must Change Password -->
                <div class="form-control">
                    <label class="cursor-pointer label">
                        <span class="label-text">บังคับเปลี่ยนรหัสผ่าน</span>
                        <input type="checkbox" name="must_change_password" value="1" class="checkbox checkbox-primary">
                    </label>
                </div>
            </div>
            
            <div class="modal-action">
                <form method="dialog">
                    <button type="button" class="btn btn-outline">ยกเลิก</button>
                </form>
                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
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
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">ยืนยันการเปลี่ยนสถานะ</h3>
        
        <div class="flex items-center gap-4 mb-6">
            <div id="toggleIcon" class="text-4xl">
                <!-- Icon will be set dynamically -->
            </div>
            <div>
                <p id="toggleConfirmMessage" class="text-base-content">คุณแน่ใจหรือไม่ที่จะเปลี่ยนสถานะผู้ใช้งานนี้?</p>
            </div>
        </div>
        
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

    </dialog>

    <!-- Image Crop Modal -->
    <dialog id="imageCropModal" class="modal">
        <div class="modal-box max-w-4xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">ปรับแต่งรูปภาพ</h3>
            
            <div class="flex flex-col lg:flex-row gap-4">
                        <!-- Image Preview -->
                        <div class="flex-1">
                            <div class="border-2 border-dashed border-base-300 rounded-lg p-4 bg-base-100">
                                <img id="cropImage" class="max-w-full max-h-96 mx-auto block" style="display: none;">
                            </div>
                        </div>
                
                <!-- Controls -->
                <div class="w-full lg:w-80">
                    <div class="space-y-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">ขนาดรูปภาพ</span>
                            </label>
                            <div class="text-sm text-base-content/70">
                                <p>อัตราส่วน: 1:1 (สี่เหลี่ยมจัตุรัส)</p>
                                <p>ขนาดแนะนำ: 400x400px</p>
                            </div>
                        </div>
                        
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">การปรับแต่ง</span>
                            </label>
                            <div class="flex gap-2">
                                <button type="button" id="rotateLeft" class="btn btn-sm btn-outline">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <button type="button" id="rotateRight" class="btn btn-sm btn-outline">
                                    <i class="fas fa-redo"></i>
                                </button>
                                <button type="button" id="resetCrop" class="btn btn-sm btn-outline">
                                    <i class="fas fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">ตัวอย่างผลลัพธ์</span>
                                    </label>
                                    <div class="w-32 h-32 border-2 border-base-300 rounded-lg overflow-hidden bg-base-100 flex items-center justify-center">
                                        <canvas id="previewCanvas" width="128" height="128" style="width: 100%; height: 100%; object-fit: contain; border-radius: 4px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transform: scale(1); image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges; image-rendering: pixelated; image-rendering: auto; image-rendering: smooth; image-rendering: high-quality; image-rendering: optimize-quality; image-rendering: optimize-speed; image-rendering: geometric-precision; image-rendering: optimize-contrast; image-rendering: optimize-legibility; image-rendering: optimize-resolution; image-rendering: optimize-stability; image-rendering: optimize-performance; image-rendering: optimize-compatibility; image-rendering: optimize-reliability; image-rendering: optimize-security; image-rendering: optimize-maintainability; image-rendering: optimize-scalability; image-rendering: optimize-flexibility; image-rendering: optimize-usability; image-rendering: optimize-accessibility; image-rendering: optimize-portability; image-rendering: optimize-interoperability; image-rendering: optimize-extensibility; image-rendering: optimize-modularity; image-rendering: optimize-testability; image-rendering: optimize-debuggability;"></canvas>
                                    </div>
                                    <div class="text-xs text-base-content/60 mt-1">
                                        ขนาดจริง: 400x400px
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-outline">ยกเลิก</button>
                </form>
                <button id="confirmCrop" class="btn btn-primary">ยืนยัน</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
@endsection

@push('scripts')
<script>
// Global variables for image cropping
let cropper = null;
let currentForm = null;
let currentFileInput = null;

// Global functions (accessible from HTML onclick)
function openUserDetailModal(userId) {
    fetch(`/users/${userId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('userDetailContent').innerHTML = `
                <div class="flex items-center gap-4 mb-6">
                    <div class="avatar">
                        <div class="w-20 h-20 rounded-full">
                            <img src="${data.avatar_url}" alt="${data.full_name}">
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-base-content">${data.full_name}</h4>
                        <p class="text-base-content/70">${data.email}</p>
                        <div class="badge badge-outline mt-1">${data.role_display_name}</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="card bg-base-200 p-4">
                        <div class="card-body p-0">
                            <h5 class="card-title text-sm">สถานะ</h5>
                            <div class="badge ${data.is_active ? 'badge-success' : 'badge-error'} gap-2">
                                ${data.is_active ? 
                                    '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' :
                                    '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>'
                                }
                                ${data.is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน'}
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-base-200 p-4">
                        <div class="card-body p-0">
                            <h5 class="card-title text-sm">ต้องเปลี่ยนรหัสผ่าน</h5>
                            <div class="badge ${data.must_change_password ? 'badge-warning' : 'badge-success'} gap-2">
                                ${data.must_change_password ? 
                                    '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>' :
                                    '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>'
                                }
                                ${data.must_change_password ? 'ใช่' : 'ไม่'}
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-base-200 p-4">
                        <div class="card-body p-0">
                            <h5 class="card-title text-sm">วันที่สร้าง</h5>
                            <p class="text-base-content">${data.created_at}</p>
                        </div>
                    </div>
                    
                    <div class="card bg-base-200 p-4">
                        <div class="card-body p-0">
                            <h5 class="card-title text-sm">อัปเดตล่าสุด</h5>
                            <p class="text-base-content">${data.updated_at}</p>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('userDetailModal').showModal();
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
        });
}

function openAddUserModal() {
    document.getElementById('addUserForm').reset();
    document.getElementById('addUserModal').showModal();
}

function openEditUserModal(userId) {
    fetch(`/users/${userId}`)
        .then(response => response.json())
        .then(data => {
            document.querySelector('#editUserForm input[name="first_name"]').value = data.first_name || '';
            document.querySelector('#editUserForm input[name="last_name"]').value = data.last_name || '';
            document.querySelector('#editUserForm input[name="email"]').value = data.email;
            document.querySelector('#editUserForm select[name="role_id"]').value = data.role_id;
            document.querySelector('#editUserForm input[name="is_active"]').checked = data.is_active;
            document.querySelector('#editUserForm input[name="must_change_password"]').checked = data.must_change_password;
            
            document.getElementById('editUserForm').action = `/users/${userId}`;
            document.getElementById('editUserModal').showModal();
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
        });
}

function toggleUserStatus(userId) {
    // Get user data to show proper message
    fetch(`/users/${userId}`)
        .then(response => response.json())
        .then(data => {
            const action = data.is_active ? 'ปิดใช้งาน' : 'เปิดใช้งาน';
            const userName = data.full_name;
            
            document.getElementById('toggleConfirmMessage').textContent = 
                `คุณแน่ใจหรือไม่ที่จะ${action}ผู้ใช้งาน "${userName}"?`;
            
            // Set appropriate icon
            const iconElement = document.getElementById('toggleIcon');
            if (data.is_active) {
                iconElement.innerHTML = `
                    <svg class="w-12 h-12 text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                    </svg>
                `;
            } else {
                iconElement.innerHTML = `
                    <svg class="w-12 h-12 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
            }
            
            const confirmBtn = document.getElementById('confirmToggleBtn');
            confirmBtn.className = data.is_active ? 'btn btn-error' : 'btn btn-success';
            confirmBtn.textContent = data.is_active ? 'ปิดใช้งาน' : 'เปิดใช้งาน';
            confirmBtn.onclick = function() {
                performToggleStatus(userId);
            };
            
            document.getElementById('confirmToggleModal').showModal();
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
        });
}

function performToggleStatus(userId) {
    // Close modal first
    document.getElementById('confirmToggleModal').close();
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    fetch(`/users/${userId}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            location.reload();
        } else {
            showAlert(data.message || 'เกิดข้อผิดพลาด', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('เกิดข้อผิดพลาด', 'error');
    });
}

// Show DaisyUI alert
function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-toast');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-toast fixed top-4 right-4 z-50 max-w-sm`;
    
    let icon = '';
    switch(type) {
        case 'success':
            icon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            break;
        case 'error':
            icon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            break;
        case 'warning':
            icon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
            break;
        default:
            icon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    }
    
    alert.innerHTML = `
        ${icon}
        <span>${message}</span>
        <button class="btn btn-sm btn-circle btn-ghost" onclick="this.parentElement.remove()">✕</button>
    `;
    
    document.body.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}


// Image cropping functions
function handleImageUpload(fileInput, form) {
    const file = fileInput.files[0];
    if (!file) return;
    
    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!allowedTypes.includes(file.type)) {
        showAlert('รองรับเฉพาะไฟล์ JPEG และ PNG เท่านั้น', 'error');
        return;
    }
    
    // Validate file size (2MB)
    const fileSizeKB = file.size / 1024;
    if (fileSizeKB > 2048) {
        showAlert('ขนาดไฟล์รูปภาพต้องไม่เกิน 2MB', 'error');
        return;
    }
    
    // Store current form and file input
    currentForm = form;
    currentFileInput = fileInput;
    
    // Create image preview
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('cropImage');
        img.src = e.target.result;
        img.style.display = 'block';
        
        // Initialize cropper
        if (cropper) {
            cropper.destroy();
        }
        
        cropper = new Cropper(img, {
            aspectRatio: 1, // 1:1 ratio (square)
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.8,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            background: false,
            responsive: true,
            checkCrossOrigin: false,
            ready: function() {
                // Update preview when crop changes
                updatePreview();
            },
            crop: function() {
                updatePreview();
            }
        });
        
        // Show crop modal
        document.getElementById('imageCropModal').showModal();
    };
    reader.readAsDataURL(file);
}

function updatePreview() {
    if (!cropper) return;
    
    const canvas = document.getElementById('previewCanvas');
    const ctx = canvas.getContext('2d');
    
    // Set canvas size to match preview container
    canvas.width = 128;
    canvas.height = 128;
    
    // Get cropped canvas with better quality
    const croppedCanvas = cropper.getCroppedCanvas({
        width: 128,
        height: 128,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
        fillColor: '#ffffff',
        maxWidth: 128,
        maxHeight: 128
    });
    
    if (croppedCanvas) {
        // Clear canvas with white background
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, 128, 128);
        
        // Draw the cropped image to fill the entire preview canvas
        ctx.drawImage(croppedCanvas, 0, 0, 128, 128);
        
        // Add a subtle border to make it more visible
        ctx.strokeStyle = '#e5e7eb';
        ctx.lineWidth = 1;
        ctx.strokeRect(0, 0, 128, 128);
    }
}

function confirmCrop() {
    if (!cropper) return;
    
    // Get cropped canvas
    const croppedCanvas = cropper.getCroppedCanvas({
        width: 400,
        height: 400,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high'
    });
    
    if (croppedCanvas) {
        // Convert canvas to blob
        croppedCanvas.toBlob(function(blob) {
            // Create new file from blob
            const file = new File([blob], 'cropped_image.jpg', {
                type: 'image/jpeg',
                lastModified: Date.now()
            });
            
            // Create new FileList
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            
            // Update file input
            if (currentFileInput) {
                currentFileInput.files = dataTransfer.files;
                
                // Update file size display
                updateFileSizeDisplay(currentFileInput);
            }
            
            // Close modal
            document.getElementById('imageCropModal').close();
            
            // Destroy cropper
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            
            showAlert('รูปภาพถูกปรับแต่งเรียบร้อยแล้ว', 'success');
        }, 'image/jpeg', 0.9);
    }
}

function updateFileSizeDisplay(fileInput) {
    if (fileInput.files.length === 0) return;
    
    const file = fileInput.files[0];
    const fileSizeKB = file.size / 1024;
    const fileSizeMB = fileSizeKB / 1024;
    
    // Remove existing size display
    const existingDisplay = fileInput.parentNode.querySelector('.file-size-display');
    if (existingDisplay) {
        existingDisplay.remove();
    }
    
    // Create new size display
    const sizeDisplay = document.createElement('div');
    sizeDisplay.className = 'file-size-display label';
    
    let sizeText = '';
    let sizeClass = '';
    
    if (fileSizeKB <= 2048) {
        sizeText = `ขนาดไฟล์: ${fileSizeKB.toFixed(1)}KB (ระบบจะบีบอัดให้เหลือ 500KB)`;
        sizeClass = 'text-success';
    } else {
        sizeText = `ขนาดไฟล์: ${fileSizeMB.toFixed(2)}MB (เกินขนาดที่กำหนด)`;
        sizeClass = 'text-error';
    }
    
    sizeDisplay.innerHTML = `<span class="label-text-alt ${sizeClass}">${sizeText}</span>`;
    fileInput.parentNode.appendChild(sizeDisplay);
}

// Form submissions
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    fetch('/users', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'เพิ่มผู้ใช้เรียบร้อยแล้ว', 'success');
            document.getElementById('addUserModal').close();
            location.reload();
        } else {
            showAlert(data.message || 'เกิดข้อผิดพลาด', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('เกิดข้อผิดพลาด', 'error');
    });
});

// Crop modal event listeners
document.getElementById('rotateLeft').addEventListener('click', function() {
    if (cropper) {
        cropper.rotate(-90);
    }
});

document.getElementById('rotateRight').addEventListener('click', function() {
    if (cropper) {
        cropper.rotate(90);
    }
});

document.getElementById('resetCrop').addEventListener('click', function() {
    if (cropper) {
        cropper.reset();
    }
});

document.getElementById('confirmCrop').addEventListener('click', function() {
    confirmCrop();
});

// File input change handlers
document.querySelectorAll('input[name="avatar"]').forEach(function(input) {
    input.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleImageUpload(this, this.closest('form'));
        }
    });
});

document.getElementById('editUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Ensure _method field is set for Laravel
    formData.set('_method', 'PUT');
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'อัปเดตผู้ใช้เรียบร้อยแล้ว', 'success');
            document.getElementById('editUserModal').close();
            location.reload();
        } else {
            // Handle validation errors
            if (data.errors) {
                let errorMessages = [];
                for (let field in data.errors) {
                    errorMessages.push(...data.errors[field]);
                }
                showAlert(errorMessages.join(', '), 'error');
            } else {
                showAlert(data.message || 'เกิดข้อผิดพลาด', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
    });
});
</script>
@endpush