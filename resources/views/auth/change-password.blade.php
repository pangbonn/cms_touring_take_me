@extends('layouts.daisyui')

@section('title', 'เปลี่ยนรหัสผ่าน')
@section('page-title', 'เปลี่ยนรหัสผ่าน')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-md mx-auto">
        <div class="card bg-white shadow-lg">
            <div class="card-body">
                <div class="text-center mb-6">
                    <div class="avatar mb-4">
                        <div class="w-20 h-20 rounded-full mx-auto">
                            <img src="{{ auth()->user()->getAvatarUrl() }}" alt="{{ auth()->user()->getFullName() }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">เปลี่ยนรหัสผ่าน</h2>
                    <p class="text-gray-600 mt-2">กรุณาเปลี่ยนรหัสผ่านของคุณเพื่อความปลอดภัย</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-error mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('change-password') }}">
                    @csrf
                    
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">รหัสผ่านปัจจุบัน</span>
                        </label>
                        <input type="password" name="current_password" class="input input-bordered w-full @error('current_password') input-error @enderror" placeholder="กรอกรหัสผ่านปัจจุบัน" required>
                        @error('current_password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">รหัสผ่านใหม่</span>
                        </label>
                        <input type="password" name="password" class="input input-bordered w-full @error('password') input-error @enderror" placeholder="กรอกรหัสผ่านใหม่" required>
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control mb-6">
                        <label class="label">
                            <span class="label-text font-semibold">ยืนยันรหัสผ่านใหม่</span>
                        </label>
                        <input type="password" name="password_confirmation" class="input input-bordered w-full" placeholder="ยืนยันรหัสผ่านใหม่" required>
                    </div>

                    <div class="form-control">
                        <button type="submit" class="btn btn-primary w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            เปลี่ยนรหัสผ่าน
                        </button>
                    </div>
                </form>

                <div class="divider">หรือ</div>
                
                <div class="text-center">
                    <a href="{{ route('logout') }}" class="btn btn-outline btn-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        ออกจากระบบ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
