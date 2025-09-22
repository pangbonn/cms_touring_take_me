@extends('layouts.daisyui')

@section('title', 'ทดสอบ DaisyUI Components')
@section('page-title', 'ทดสอบ DaisyUI Components')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-8">ทดสอบ DaisyUI Components</h1>
        
        <!-- Input Components -->
        <div class="card bg-white shadow-lg mb-6">
            <div class="card-body">
                <h2 class="card-title mb-4">Input Components</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Text Input -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Text Input</span>
                        </label>
                        <input type="text" class="input input-bordered w-full" placeholder="กรอกข้อความ">
                    </div>
                    
                    <!-- Email Input -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Email Input</span>
                        </label>
                        <input type="email" class="input input-bordered w-full" placeholder="กรอกอีเมล">
                    </div>
                    
                    <!-- Password Input -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Password Input</span>
                        </label>
                        <input type="password" class="input input-bordered w-full" placeholder="กรอกรหัสผ่าน">
                    </div>
                    
                    <!-- Select -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Select</span>
                        </label>
                        <select class="select select-bordered w-full">
                            <option disabled selected>เลือกตัวเลือก</option>
                            <option>ตัวเลือก 1</option>
                            <option>ตัวเลือก 2</option>
                            <option>ตัวเลือก 3</option>
                        </select>
                    </div>
                    
                    <!-- File Input -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">File Input</span>
                        </label>
                        <input type="file" class="file-input file-input-bordered w-full">
                    </div>
                    
                    <!-- Checkbox -->
                    <div class="form-control">
                        <label class="cursor-pointer label">
                            <span class="label-text">Checkbox</span>
                            <input type="checkbox" class="checkbox checkbox-primary">
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Button Components -->
        <div class="card bg-white shadow-lg mb-6">
            <div class="card-body">
                <h2 class="card-title mb-4">Button Components</h2>
                
                <div class="flex flex-wrap gap-2">
                    <button class="btn btn-primary">Primary</button>
                    <button class="btn btn-secondary">Secondary</button>
                    <button class="btn btn-accent">Accent</button>
                    <button class="btn btn-neutral">Neutral</button>
                    <button class="btn btn-info">Info</button>
                    <button class="btn btn-success">Success</button>
                    <button class="btn btn-warning">Warning</button>
                    <button class="btn btn-error">Error</button>
                    <button class="btn btn-outline">Outline</button>
                    <button class="btn btn-ghost">Ghost</button>
                </div>
            </div>
        </div>
        
        <!-- Modal Test -->
        <div class="card bg-white shadow-lg mb-6">
            <div class="card-body">
                <h2 class="card-title mb-4">Modal Test</h2>
                
                <button class="btn btn-primary" onclick="document.getElementById('testModal').showModal()">
                    เปิด Modal ทดสอบ
                </button>
            </div>
        </div>
        
        <!-- Badge Components -->
        <div class="card bg-white shadow-lg mb-6">
            <div class="card-body">
                <h2 class="card-title mb-4">Badge Components</h2>
                
                <div class="flex flex-wrap gap-2">
                    <div class="badge badge-primary">Primary</div>
                    <div class="badge badge-secondary">Secondary</div>
                    <div class="badge badge-accent">Accent</div>
                    <div class="badge badge-neutral">Neutral</div>
                    <div class="badge badge-info">Info</div>
                    <div class="badge badge-success">Success</div>
                    <div class="badge badge-warning">Warning</div>
                    <div class="badge badge-error">Error</div>
                    <div class="badge badge-outline">Outline</div>
                </div>
            </div>
        </div>
        
        <!-- Alert Components -->
        <div class="card bg-white shadow-lg mb-6">
            <div class="card-body">
                <h2 class="card-title mb-4">Alert Components</h2>
                
                <div class="space-y-2">
                    <div class="alert alert-info">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>ข้อความแจ้งเตือนข้อมูล</span>
                    </div>
                    
                    <div class="alert alert-success">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>ข้อความสำเร็จ</span>
                    </div>
                    
                    <div class="alert alert-warning">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <span>ข้อความเตือน</span>
                    </div>
                    
                    <div class="alert alert-error">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>ข้อความข้อผิดพลาด</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Modal -->
<dialog id="testModal" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg">Modal ทดสอบ</h3>
        <p class="py-4">นี่คือ modal ทดสอบของ DaisyUI ที่มีมุมโค้งและสไตล์ที่สวยงาม</p>
        
        <div class="form-control mb-4">
            <label class="label">
                <span class="label-text">ทดสอบ Input</span>
            </label>
            <input type="text" class="input input-bordered w-full" placeholder="กรอกข้อความทดสอบ">
        </div>
        
        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-outline">ปิด</button>
            </form>
            <button class="btn btn-primary">บันทึก</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

@endsection
