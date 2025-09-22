@extends('layouts.daisyui')

@section('title', 'รายละเอียดผู้ใช้')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">รายละเอียดผู้ใช้</h1>
        <div class="flex space-x-2">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                แก้ไข
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-outline">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                กลับ
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info Card -->
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-4">ข้อมูลผู้ใช้</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">ชื่อ</span>
                            </label>
                            <div class="text-lg">{{ $user->name }}</div>
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">อีเมล</span>
                            </label>
                            <div class="text-lg">{{ $user->email }}</div>
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">สิทธิ์</span>
                            </label>
                            <div class="badge badge-outline badge-lg">
                                {{ $user->getRoleDisplayName() }}
                            </div>
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">สถานะ</span>
                            </label>
                            @if($user->is_active)
                                <div class="badge badge-success badge-lg">เปิดใช้งาน</div>
                            @else
                                <div class="badge badge-error badge-lg">ปิดใช้งาน</div>
                            @endif
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">วันที่สร้าง</span>
                            </label>
                            <div class="text-lg">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">อัปเดตล่าสุด</span>
                            </label>
                            <div class="text-lg">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Avatar & Actions -->
        <div class="space-y-6">
            <!-- Avatar Card -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body text-center">
                    <div class="avatar mb-4">
                        <div class="w-24 h-24 rounded-full bg-primary text-primary-content flex items-center justify-center text-3xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>
                    <h3 class="text-xl font-bold">{{ $user->name }}</h3>
                    <p class="text-gray-500">{{ $user->getRoleDisplayName() }}</p>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title mb-4">การดำเนินการ</h3>
                    <div class="space-y-2">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            แก้ไขข้อมูล
                        </a>

                        @if(!$user->isSuperAdmin())
                            <form action="{{ route('users.toggle-status', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn {{ $user->is_active ? 'btn-error' : 'btn-success' }} w-full">
                                    @if($user->is_active)
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                        </svg>
                                        ปิดใช้งาน
                                    @else
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        เปิดใช้งาน
                                    @endif
                                </button>
                            </form>

                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error w-full">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    ลบผู้ใช้
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Role Info Card -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title mb-4">ข้อมูลสิทธิ์</h3>
                    @if($user->role)
                        <div class="space-y-2">
                            <div>
                                <span class="font-semibold">ชื่อสิทธิ์:</span>
                                <div class="badge badge-outline">{{ $user->role->name }}</div>
                            </div>
                            <div>
                                <span class="font-semibold">ชื่อแสดง:</span>
                                <div>{{ $user->role->display_name }}</div>
                            </div>
                            @if($user->role->description)
                                <div>
                                    <span class="font-semibold">คำอธิบาย:</span>
                                    <div class="text-sm text-gray-600">{{ $user->role->description }}</div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-gray-500">ไม่มีข้อมูลสิทธิ์</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
