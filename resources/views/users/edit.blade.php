@extends('layouts.daisyui')

@section('title', 'แก้ไขผู้ใช้')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">แก้ไขผู้ใช้: {{ $user->name }}</h1>
        <a href="{{ route('users.index') }}" class="btn btn-outline">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            กลับ
        </a>
    </div>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">ชื่อ</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               class="input input-bordered @error('name') input-error @enderror" 
                               placeholder="กรอกชื่อ" required>
                        @error('name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">อีเมล</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="input input-bordered @error('email') input-error @enderror" 
                               placeholder="กรอกอีเมล" required>
                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">รหัสผ่านใหม่</span>
                        </label>
                        <input type="password" name="password" 
                               class="input input-bordered @error('password') input-error @enderror" 
                               placeholder="กรอกรหัสผ่านใหม่ (เว้นว่างไว้หากไม่ต้องการเปลี่ยน)">
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">ยืนยันรหัสผ่านใหม่</span>
                        </label>
                        <input type="password" name="password_confirmation" 
                               class="input input-bordered" 
                               placeholder="ยืนยันรหัสผ่านใหม่">
                    </div>

                    <!-- Role -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">สิทธิ์</span>
                        </label>
                        <select name="role_id" class="select select-bordered @error('role_id') select-error @enderror" required>
                            <option value="">เลือกสิทธิ์</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" 
                                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->display_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">สถานะ</span>
                        </label>
                        <label class="cursor-pointer label">
                            <span class="label-text">เปิดใช้งาน</span>
                            <input type="checkbox" name="is_active" value="1" 
                                   class="checkbox checkbox-primary" 
                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        </label>
                    </div>
                </div>

                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        อัปเดต
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
