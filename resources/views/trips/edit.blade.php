@extends('layouts.daisyui')

@section('title', 'แก้ไขทริป - ' . $trip->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('trips.index') }}" class="btn btn-ghost btn-sm mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                กลับ
            </a>
            <h1 class="text-3xl font-bold text-gray-800">แก้ไขทริป - {{ $trip->name }}</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-6" id="success-alert">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- แก้ไขข้อมูลทริป -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title mb-4">ข้อมูลทริป</h2>
                    
                    <form action="{{ route('trips.update', $trip) }}" method="POST" enctype="multipart/form-data" id="trip-form">
                        @csrf
                        @method('PUT')
                        
                        <!-- Hidden file inputs for cropped images -->
                        <input type="file" name="sample_images[]" id="hidden-file-input" style="display: none;" multiple>
                        <input type="file" name="cover_image" id="hidden-cover-input" style="display: none;">
                        
                        <div class="space-y-4">
                            <!-- ชื่อทริป -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">ชื่อทริป <span class="text-red-500">*</span></span>
                                </label>
                                <input type="text" name="name" class="input input-bordered @error('name') input-error @enderror" 
                                       value="{{ old('name', $trip->name) }}" placeholder="กรอกชื่อทริป" required>
                                @error('name')
                                    <label class="label">
                                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- รายละเอียดทริป -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">รายละเอียดทริป</span>
                                </label>
                                <textarea name="description" class="textarea textarea-bordered h-24 @error('description') textarea-error @enderror" 
                                          placeholder="กรอกรายละเอียดทริป (สามารถขึ้นบรรทัดใหม่ได้)">{{ old('description', $trip->description) }}</textarea>
                                @error('description')
                                    <label class="label">
                                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- รูปภาพหลัก -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">รูปภาพหลัก</span>
                                </label>
                                @if($trip->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $trip->image) }}" alt="{{ $trip->name }}" class="w-32 h-32 object-cover rounded-lg">
                                    </div>
                                @endif
                                <input type="file" name="image" class="file-input file-input-bordered w-full @error('image') file-input-error @enderror" 
                                       accept="image/*">
                                @error('image')
                                    <label class="label">
                                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- รูป Cover -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">รูป Cover</span>
                                </label>
                                @if($trip->cover_image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $trip->cover_image) }}" alt="Cover Image" class="w-32 h-18 object-cover rounded-lg">
                                    </div>
                                @endif
                                <input type="file" name="cover_image" class="file-input file-input-bordered w-full @error('cover_image') file-input-error @enderror" 
                                       accept="image/*" id="cover-image-input">
                                <div class="label">
                                    <span class="label-text-alt">รูป Cover จะถูก crop เป็นแนวนอนอัตโนมัติ</span>
                                </div>
                                @error('cover_image')
                                    <label class="label">
                                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                                    </label>
                                @enderror
                                
                                <!-- Cover image preview -->
                                <div id="cover-image-preview" class="mt-4 hidden">
                                    <div class="aspect-video border-2 border-dashed border-gray-300 rounded-lg overflow-hidden">
                                        <img id="cover-preview-img" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>

                            <!-- รูปตัวอย่าง 4 รูป -->
                            <div class="form-control md:col-span-2">
                                <label class="label">
                                    <span class="label-text font-semibold">รูปตัวอย่าง (สูงสุด 4 รูป)</span>
                                </label>
                                
                                <!-- Sample Images Grid -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    @for($i = 0; $i < 4; $i++)
                                        <div class="aspect-square border-2 border-dashed border-gray-300 rounded-lg overflow-hidden relative" id="sample-slot-{{ $i }}">
                                            @if(isset($trip->sample_images[$i]))
                                                <img src="{{ asset('storage/' . $trip->sample_images[$i]) }}" alt="Sample Image {{ $i + 1 }}" class="w-full h-full object-cover">
                                                <button type="button" class="btn btn-error btn-xs absolute top-1 right-1" data-remove-slot="{{ $i }}">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            @else
                                                <div class="w-full h-full flex items-center justify-center cursor-pointer" data-slot="{{ $i }}">
                                                    <div class="text-center">
                                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                        <span class="text-sm text-gray-500">รูปที่ {{ $i + 1 }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endfor
                                </div>
                                
                                <!-- Hidden file input for each slot -->
                                <input type="file" name="sample_images[]" class="hidden" id="sample-input-0" accept="image/*" onchange="handleSampleImageSelect(0, this)">
                                <input type="file" name="sample_images[]" class="hidden" id="sample-input-1" accept="image/*" onchange="handleSampleImageSelect(1, this)">
                                <input type="file" name="sample_images[]" class="hidden" id="sample-input-2" accept="image/*" onchange="handleSampleImageSelect(2, this)">
                                <input type="file" name="sample_images[]" class="hidden" id="sample-input-3" accept="image/*" onchange="handleSampleImageSelect(3, this)">
                                
                                <div class="label">
                                    <span class="label-text-alt">คลิกที่ช่องว่างเพื่อเลือกรูป - รูปจะถูก crop เป็น 4x4 อัตโนมัติ</span>
                                </div>
                                @error('sample_images.*')
                                    <label class="label">
                                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- แผนการเดินทาง -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">แผนการเดินทาง</span>
                                </label>
                                <textarea name="itinerary" class="textarea textarea-bordered h-24 @error('itinerary') textarea-error @enderror" 
                                          placeholder="กรอกแผนการเดินทาง (สามารถขึ้นบรรทัดใหม่ได้)">{{ old('itinerary', $trip->itinerary) }}</textarea>
                                @error('itinerary')
                                    <label class="label">
                                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- ค่าใช้จ่ายรวม -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">ค่าใช้จ่ายรวม</span>
                                </label>
                                <textarea name="total_cost" class="textarea textarea-bordered h-24 @error('total_cost') textarea-error @enderror" 
                                          placeholder="กรอกค่าใช้จ่ายรวม (สามารถขึ้นบรรทัดใหม่ได้)">{{ old('total_cost', $trip->total_cost) }}</textarea>
                                @error('total_cost')
                                    <label class="label">
                                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- ของส่วนตัวที่ต้องเตรียม -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">ของส่วนตัวที่ต้องเตรียม</span>
                                </label>
                                <textarea name="personal_items" class="textarea textarea-bordered h-24 @error('personal_items') textarea-error @enderror" 
                                          placeholder="กรอกของส่วนตัวที่ต้องเตรียม (สามารถขึ้นบรรทัดใหม่ได้)">{{ old('personal_items', $trip->personal_items) }}</textarea>
                                @error('personal_items')
                                    <label class="label">
                                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- คำแนะนำและข้อมูลพื้นที่ -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">คำแนะนำและข้อมูลพื้นที่</span>
                                </label>
                                <textarea name="area_info" class="textarea textarea-bordered h-24 @error('area_info') textarea-error @enderror" 
                                          placeholder="กรอกคำแนะนำและข้อมูลพื้นที่ (สามารถขึ้นบรรทัดใหม่ได้)">{{ old('area_info', $trip->area_info) }}</textarea>
                                @error('area_info')
                                    <label class="label">
                                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- อุปกรณ์เช่า -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">อุปกรณ์เช่า</span>
                                </label>
                                <textarea name="rental_equipment" class="textarea textarea-bordered h-24 @error('rental_equipment') textarea-error @enderror" 
                                          placeholder="กรอกอุปกรณ์เช่า (สามารถขึ้นบรรทัดใหม่ได้)">{{ old('rental_equipment', $trip->rental_equipment) }}</textarea>
                                @error('rental_equipment')
                                    <label class="label">
                                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>
                        </div>

                        <!-- การตั้งค่าการแสดงผล -->
                        <div class="divider">การตั้งค่าการแสดงผล</div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-control">
                                <label class="cursor-pointer label">
                                    <span class="label-text font-semibold">แสดงแผนการเดินทาง</span>
                                    <input type="checkbox" name="show_itinerary" class="toggle toggle-primary" {{ $trip->show_itinerary ? 'checked' : '' }}>
                                </label>
                            </div>

                            <div class="form-control">
                                <label class="cursor-pointer label">
                                    <span class="label-text font-semibold">แสดงค่าใช้จ่ายรวม</span>
                                    <input type="checkbox" name="show_total_cost" class="toggle toggle-primary" {{ $trip->show_total_cost ? 'checked' : '' }}>
                                </label>
                            </div>

                            <div class="form-control">
                                <label class="cursor-pointer label">
                                    <span class="label-text font-semibold">แสดงของส่วนตัวที่ต้องเตรียม</span>
                                    <input type="checkbox" name="show_personal_items" class="toggle toggle-primary" {{ $trip->show_personal_items ? 'checked' : '' }}>
                                </label>
                            </div>

                            <div class="form-control">
                                <label class="cursor-pointer label">
                                    <span class="label-text font-semibold">แสดงอุปกรณ์เช่า</span>
                                    <input type="checkbox" name="show_rental_equipment" class="toggle toggle-primary" {{ $trip->show_rental_equipment ? 'checked' : '' }}>
                                </label>
                            </div>

                            <div class="form-control">
                                <label class="cursor-pointer label">
                                    <span class="label-text font-semibold">แสดงรอบการเดินทาง</span>
                                    <input type="checkbox" name="show_schedule" class="toggle toggle-primary" {{ $trip->show_schedule ? 'checked' : '' }}>
                                </label>
                            </div>
                        </div>

                        <div class="card-actions justify-end mt-6">
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                บันทึกการแก้ไข
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- จัดการรอบการเดินทาง -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="card-title">รอบการเดินทาง</h2>
                        <button class="btn btn-primary btn-sm" onclick="document.getElementById('add-schedule-modal').showModal()">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            เพิ่มรอบ
                        </button>
                    </div>

                    @if($trip->schedules->count() > 0)
                        <div class="space-y-3">
                            @foreach($trip->schedules->sortByDesc('departure_date') as $schedule)
                                <div class="border rounded-lg p-4 {{ $schedule->is_active ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold">
                                                {{ formatThaiDate($schedule->departure_date) }}
                                                @if($schedule->return_date)
                                                    - {{ formatThaiDate($schedule->return_date) }}
                                                @endif
                                            </h3>
                                            @if($schedule->return_date)
                                                <p class="text-sm text-blue-600 font-medium">
                                                    {{ calculateDaysNights($schedule->departure_date, $schedule->return_date) }}
                                                </p>
                                            @endif
                                            @if($schedule->price)
                                                <p class="text-sm text-gray-600">ราคา: {{ number_format($schedule->price, 2) }} บาท</p>
                                            @endif
                                            @if($schedule->max_participants)
                                                <p class="text-sm text-gray-600">จำนวนผู้เข้าร่วมสูงสุด: {{ $schedule->max_participants }} คน</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <!-- Status Indicator with Better Design -->
                                            <div class="flex items-center space-x-2">
                                                <div class="flex items-center space-x-1 px-2 py-1 rounded-full {{ $schedule->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                                    <div class="w-2 h-2 rounded-full {{ $schedule->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                                                    <span class="text-xs font-medium">
                                                        {{ $schedule->is_active ? 'เปิด' : 'ปิด' }}
                                                    </span>
                                                </div>
                                                <form method="POST" action="{{ route('trips.schedules.toggle', [$trip, $schedule]) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm {{ $schedule->is_active ? 'btn-success' : 'btn-outline' }} hover:scale-105 transition-transform duration-200" 
                                                            title="{{ $schedule->is_active ? 'ปิดรอบนี้' : 'เปิดรอบนี้' }}"
                                                            onclick="return handleToggleSchedule(event, {{ $schedule->is_active ? 'false' : 'true' }})">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            @if($schedule->is_active)
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            @else
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            @endif
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                            
                                            <!-- Action Buttons with Better Design -->
                                            <div class="flex items-center space-x-1">
                                                <!-- Edit Button -->
                                                <button class="btn btn-ghost btn-sm hover:btn-primary hover:scale-105 transition-all duration-200" 
                                                        onclick="editSchedule({{ $schedule->id }})" 
                                                        title="แก้ไขรอบ">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                
                                                <!-- Delete Button -->
                                                <form action="{{ route('trips.schedules.destroy', [$trip, $schedule]) }}" method="POST" class="inline" onsubmit="return handleDeleteSchedule(event)">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-error btn-sm hover:scale-105 transition-all duration-200" 
                                                            title="ลบรอบ">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p>ยังไม่มีรอบการเดินทาง</p>
                            <p class="text-sm">คลิกปุ่ม "เพิ่มรอบ" เพื่อเพิ่มรอบการเดินทาง</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal เพิ่มรอบการเดินทาง -->
<dialog id="add-schedule-modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">เพิ่มรอบการเดินทาง</h3>
        
        <form action="{{ route('trips.schedules.store', $trip) }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">วันที่ออกเดินทาง <span class="text-red-500">*</span></span>
                    </label>
                    <input type="date" name="departure_date" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">วันที่กลับ</span>
                    </label>
                    <input type="date" name="return_date" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">จำนวนผู้เข้าร่วมสูงสุด</span>
                    </label>
                    <input type="number" name="max_participants" class="input input-bordered" min="0">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">ราคา (บาท)</span>
                    </label>
                    <input type="number" name="price" class="input input-bordered" min="0" step="0.01">
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('add-schedule-modal').close()">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">เพิ่มรอบ</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Modal แก้ไขรอบการเดินทาง -->
<dialog id="edit-schedule-modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">แก้ไขรอบการเดินทาง</h3>
        
        <form id="edit-schedule-form" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">วันที่ออกเดินทาง <span class="text-red-500">*</span></span>
                    </label>
                    <input type="date" name="departure_date" id="edit-departure-date" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">วันที่กลับ</span>
                    </label>
                    <input type="date" name="return_date" id="edit-return-date" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">จำนวนผู้เข้าร่วมสูงสุด</span>
                    </label>
                    <input type="number" name="max_participants" id="edit-max-participants" class="input input-bordered" min="0">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">ราคา (บาท)</span>
                    </label>
                    <input type="number" name="price" id="edit-price" class="input input-bordered" min="0" step="0.01">
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('edit-schedule-modal').close()">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
            </div>
        </form>
    </div>
</dialog>

<!-- SweetAlert2 for beautiful dialogs -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Enhanced dialog confirmation functions with SweetAlert2
async function confirmToggleSchedule(willBeActive) {
    const action = willBeActive ? 'เปิด' : 'ปิด';
    const actionColor = willBeActive ? 'success' : 'warning';
    const actionIcon = willBeActive ? 'success' : 'warning';
    
    const result = await Swal.fire({
        title: `ยืนยันการ${action}รอบการเดินทาง`,
        text: `คุณแน่ใจหรือไม่ที่จะ${action}รอบการเดินทางนี้?`,
        icon: actionIcon,
        showCancelButton: true,
        confirmButtonColor: willBeActive ? '#10b981' : '#f59e0b',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `ใช่, ${action}รอบนี้`,
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    });
    
    return result.isConfirmed;
}

async function confirmDeleteSchedule() {
    const result = await Swal.fire({
        title: 'ยืนยันการลบรอบการเดินทาง',
        text: 'คุณแน่ใจหรือไม่ที่จะลบรอบการเดินทางนี้?\nการดำเนินการนี้ไม่สามารถย้อนกลับได้',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ใช่, ลบรอบนี้',
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    });
    
    return result.isConfirmed;
}

// Handler functions for form submissions
async function handleToggleSchedule(event, willBeActive) {
    event.preventDefault();
    
    const confirmed = await confirmToggleSchedule(willBeActive);
    if (confirmed) {
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="loading loading-spinner loading-sm"></span>';
        button.disabled = true;
        
        // Submit the form
        event.target.closest('form').submit();
    }
}

async function handleDeleteSchedule(event) {
    event.preventDefault();
    
    const confirmed = await confirmDeleteSchedule();
    if (confirmed) {
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="loading loading-spinner loading-sm"></span>';
        button.disabled = true;
        
        // Submit the form
        event.target.closest('form').submit();
    }
}

// Show success message with SweetAlert2
document.addEventListener('DOMContentLoaded', function() {
    const successAlert = document.getElementById('success-alert');
    if (successAlert) {
        const message = successAlert.textContent.trim();
        Swal.fire({
            title: 'สำเร็จ!',
            text: message,
            icon: 'success',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#10b981'
        });
    }
});

function editSchedule(scheduleId) {
    // ข้อมูลรอบการเดินทาง (จะต้องส่งจาก backend)
    const schedules = @json($trip->schedules);
    const schedule = schedules.find(s => s.id === scheduleId);
    
    if (schedule) {
        document.getElementById('edit-departure-date').value = schedule.departure_date;
        document.getElementById('edit-return-date').value = schedule.return_date || '';
        document.getElementById('edit-max-participants').value = schedule.max_participants || '';
        document.getElementById('edit-price').value = schedule.price || '';
        
        document.getElementById('edit-schedule-form').action = `/trips/{{ $trip->id }}/schedules/${scheduleId}`;
        document.getElementById('edit-schedule-modal').showModal();
    }
}
</script>

<!-- Cover Crop Modal -->
<dialog id="cover-crop-modal" class="modal">
    <div class="modal-box max-w-4xl">
        <h3 class="font-bold text-lg mb-4">Crop รูป Cover</h3>
        
        <div class="flex gap-4">
            <div class="flex-1">
                <div id="cover-cropper-container" style="max-height: 400px;">
                    <img id="cover-crop-image" style="max-width: 100%;">
                </div>
            </div>
            <div class="w-48">
                <div id="cover-preview-container" class="aspect-video border border-gray-300 rounded-lg overflow-hidden">
                    <img id="cover-preview-image" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
        
        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="cancelCoverCrop()">ยกเลิก</button>
            <button type="button" class="btn btn-primary" onclick="applyCoverCrop()">ยืนยัน</button>
        </div>
    </div>
</dialog>

<!-- Cropper.js Modal -->
<dialog id="crop-modal" class="modal">
    <div class="modal-box max-w-4xl">
        <h3 class="font-bold text-lg mb-4">Crop รูปภาพ</h3>
        
        <div class="flex gap-4">
            <div class="flex-1">
                <div id="cropper-container" style="max-height: 400px;">
                    <img id="crop-image" style="max-width: 100%;">
                </div>
            </div>
            <div class="w-32">
                <div id="preview-container" class="aspect-square border border-gray-300 rounded-lg overflow-hidden">
                    <img id="preview-image" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
        
        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="cancelCrop()">ยกเลิก</button>
            <button type="button" class="btn btn-primary" onclick="applyCrop()">ยืนยัน</button>
        </div>
    </div>
</dialog>

<!-- Cropper.js CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

<!-- Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
// Initialize processed images array with existing images
let processedImages = new Array(4).fill(null);
let existingImages = {};
@if($trip->sample_images)
    @foreach($trip->sample_images as $index => $image)
        existingImages[{{ $index }}] = '{{ $image }}';
        console.log(`Setting existingImages[{{ $index }}] = '{{ $image }}'`);
    @endforeach
@endif
console.log('Initial existingImages:', existingImages);

let cropper = null;
let coverCropper = null;
let currentImageIndex = 0;
let processedCoverImage = null;

// Cover image handling
document.getElementById('cover-image-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            showCoverCropModal(e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

// Sample images handling - Individual slot selection
function selectSampleImage(slotIndex) {
    console.log(`selectSampleImage called with slotIndex: ${slotIndex}`);
    let input = document.getElementById(`sample-input-${slotIndex}`);
    console.log(`Looking for element: sample-input-${slotIndex}`);
    console.log(`Element found:`, input);
    
    if (!input) {
        console.log(`Element sample-input-${slotIndex} not found, creating new one`);
        // Create new file input if it doesn't exist
        input = document.createElement('input');
        input.type = 'file';
        input.name = 'sample_images[]';
        input.className = 'hidden';
        input.id = `sample-input-${slotIndex}`;
        input.accept = 'image/*';
        input.onchange = function() { handleSampleImageSelect(slotIndex, this); };
        document.body.appendChild(input);
        console.log(`Created new input for slot ${slotIndex}`);
    }
    
    if (input) {
        console.log(`Clicking input for slot ${slotIndex}`);
        input.click();
    } else {
        console.error(`Failed to create or find input for slot ${slotIndex}`);
    }
}

function handleSampleImageSelect(slotIndex, input) {
    const file = input.files[0];
    if (file && file.type.startsWith('image/')) {
        currentImageIndex = slotIndex;
        const reader = new FileReader();
        reader.onload = function(e) {
            showCropModal(e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

function showCoverCropModal(imageSrc) {
    const modal = document.getElementById('cover-crop-modal');
    const cropImage = document.getElementById('cover-crop-image');
    
    cropImage.src = imageSrc;
    modal.showModal();
    
    setTimeout(() => {
        if (coverCropper) {
            coverCropper.destroy();
        }
        
        coverCropper = new Cropper(cropImage, {
            aspectRatio: 16/9, // 16:9 ratio for horizontal crop
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.8,
            restore: false,
            guides: false,
            center: false,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            preview: '#cover-preview-image'
        });
    }, 100);
}

function showCropModal(imageSrc) {
    const modal = document.getElementById('crop-modal');
    const cropImage = document.getElementById('crop-image');
    
    cropImage.src = imageSrc;
    modal.showModal();
    
    setTimeout(() => {
        if (cropper) {
            cropper.destroy();
        }
        
        cropper = new Cropper(cropImage, {
            aspectRatio: 1, // 1:1 ratio for square crop
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.8,
            restore: false,
            guides: false,
            center: false,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            preview: '#preview-image'
        });
    }, 100);
}

function applyCoverCrop() {
    if (coverCropper) {
        const canvas = coverCropper.getCroppedCanvas({
            width: 800,
            height: 450,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        canvas.toBlob(function(blob) {
            processedCoverImage = blob;
            
            // Update preview
            const previewImg = document.getElementById('cover-preview-img');
            previewImg.src = canvas.toDataURL();
            document.getElementById('cover-image-preview').classList.remove('hidden');
            
            // Close modal
            document.getElementById('cover-crop-modal').close();
            
            // Update file input
            updateCoverFileInput();
        }, 'image/jpeg', 0.9);
    }
}

function applyCrop() {
    if (cropper) {
        const canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        canvas.toBlob(function(blob) {
            console.log(`applyCrop: Setting processedImages[${currentImageIndex}] = blob`);
            processedImages[currentImageIndex] = blob;
            console.log('processedImages after update:', processedImages);
            
            // Update the specific slot
            const slotDiv = document.getElementById(`sample-slot-${currentImageIndex}`);
            if (slotDiv) {
                // Clear content and add image
                slotDiv.innerHTML = '';
                const img = document.createElement('img');
                img.src = canvas.toDataURL();
                img.className = 'w-full h-full object-cover';
                slotDiv.appendChild(img);
                
                // Add remove button
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-error btn-xs absolute top-1 right-1';
                btn.setAttribute('data-remove-slot', currentImageIndex);
                btn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                slotDiv.appendChild(btn);
                
                // Add event listener to the remove button
                btn.addEventListener('click', function() {
                    const slotIndex = parseInt(this.getAttribute('data-remove-slot'));
                    console.log(`Remove button clicked for slot ${slotIndex}`);
                    removeSampleImage(slotIndex);
                });
            }
            
            // Close modal
            document.getElementById('crop-modal').close();
            
            // Update file input immediately
            console.log('Calling updateFileInput after crop');
            updateFileInput();
        }, 'image/jpeg', 0.9);
    }
}

function removeSampleImage(index) {
    console.log(`removeSampleImage: Removing image at index ${index}`);
    processedImages[index] = null;
    delete existingImages[index]; // Also remove from existing images
    console.log('processedImages after removal:', processedImages);
    console.log('existingImages after removal:', existingImages);
    
    // Reset slot div
    const slotDiv = document.getElementById(`sample-slot-${index}`);
    if (slotDiv) {
        slotDiv.innerHTML = `
            <div class="w-full h-full flex items-center justify-center cursor-pointer" data-slot="${index}">
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="text-sm text-gray-500">รูปที่ ${index + 1}</span>
                </div>
            </div>
        `;
        
        // Re-add event listener to the new element
        const newSlotDiv = slotDiv.querySelector(`[data-slot="${index}"]`);
        if (newSlotDiv) {
            newSlotDiv.addEventListener('click', function(event) {
                const slotIndex = parseInt(event.currentTarget.getAttribute('data-slot'));
                console.log(`Clicked slot ${slotIndex}`);
                selectSampleImage(slotIndex);
            });
        }
    }
    
    // Clear the file input and recreate it
    let fileInput = document.getElementById(`sample-input-${index}`);
    if (fileInput) {
        fileInput.remove();
    }
    
    // Create new file input
    fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.name = 'sample_images[]';
    fileInput.className = 'hidden';
    fileInput.id = `sample-input-${index}`;
    fileInput.accept = 'image/*';
    fileInput.onchange = function() { handleSampleImageSelect(index, this); };
    document.body.appendChild(fileInput);
    
    // Update file input
    updateFileInput();
}

function cancelCoverCrop() {
    document.getElementById('cover-crop-modal').close();
    if (coverCropper) {
        coverCropper.destroy();
        coverCropper = null;
    }
    // Reset file input
    document.getElementById('cover-image-input').value = '';
}

function cancelCrop() {
    document.getElementById('crop-modal').close();
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
}

function updateCoverFileInput() {
    if (processedCoverImage) {
        const dt = new DataTransfer();
        const file = new File([processedCoverImage], `cover_${Date.now()}.jpg`, { type: 'image/jpeg' });
        dt.items.add(file);
        document.getElementById('cover-image-input').files = dt.files;
    }
}

function updateFileInput() {
    console.log('updateFileInput called');
    console.log('processedImages:', processedImages);
    console.log('existingImages:', existingImages);
    
    // Create actual file inputs for form submission
    const form = document.getElementById('trip-form');
    
    // Remove existing sample image inputs to prevent duplicates
    const existingInputs = form.querySelectorAll('input[name="sample_images[]"], input[name="existing_sample_images[]"]');
    console.log(`Removing ${existingInputs.length} existing inputs`);
    existingInputs.forEach(input => input.remove());
    
    // Collect unique existing images to avoid duplicates
    const uniqueExistingImages = new Set();
    
    // Add file inputs for each slot that has an image (either new or existing)
    let fileCount = 0;
    for (let index = 0; index < 4; index++) {
        const hasNewImage = processedImages[index] && typeof processedImages[index] === 'object' && processedImages[index].constructor === Blob;
        const hasExistingImage = existingImages[index] && !processedImages[index]; // existing image that wasn't replaced
        
        if (hasNewImage || hasExistingImage) {
            console.log(`Creating input for slot ${index} - hasNewImage: ${hasNewImage}, hasExistingImage: ${hasExistingImage}`);
            
            if (hasNewImage) {
                // Create file input for new cropped image
                const file = new File([processedImages[index]], `sample_${index}_${Date.now()}.jpg`, { type: 'image/jpeg' });
                const input = document.createElement('input');
                input.type = 'file';
                input.name = 'sample_images[]';
                input.style.display = 'none';
                
                const dtFile = new DataTransfer();
                dtFile.items.add(file);
                input.files = dtFile.files;
                
                form.appendChild(input);
                fileCount++;
            } else if (hasExistingImage && !uniqueExistingImages.has(existingImages[index])) {
                // Create hidden input to preserve existing image (only if not already added)
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'existing_sample_images[]';
                input.value = existingImages[index];
                form.appendChild(input);
                uniqueExistingImages.add(existingImages[index]);
                console.log(`Preserving existing image for slot ${index}: ${existingImages[index]}`);
            }
        }
    }
    
    console.log(`Created ${fileCount} file inputs for new images and ${uniqueExistingImages.size} unique existing images`);
}

function updateCoverFileInput() {
    if (processedCoverImage) {
        const dt = new DataTransfer();
        const file = new File([processedCoverImage], `cover_${Date.now()}.jpg`, { type: 'image/jpeg' });
        dt.items.add(file);
        document.getElementById('hidden-cover-input').files = dt.files;
        
        // Create actual file input for form submission
        const form = document.getElementById('trip-form');
        
        // Remove existing cover image input
        const existingCoverInput = form.querySelector('input[name="cover_image"]');
        if (existingCoverInput) {
            existingCoverInput.remove();
        }
        
        // Add new file input for cover image
        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'cover_image';
        input.style.display = 'none';
        
        const dtFile = new DataTransfer();
        dtFile.items.add(file);
        input.files = dtFile.files;
        
        form.appendChild(input);
    }
}

// Handle form submission - Simplified version
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up simplified form handler');
    
    const form = document.getElementById('trip-form');
    const submitBtn = document.getElementById('submit-btn');
    
    if (!form) {
        console.error('Form not found!');
        return;
    }
    
    if (!submitBtn) {
        console.error('Submit button not found!');
        return;
    }
    
    console.log('Form and submit button found');
    
    // Check if sample input elements exist
    for (let i = 0; i < 4; i++) {
        const input = document.getElementById(`sample-input-${i}`);
        if (!input) {
            console.error(`Sample input ${i} not found!`);
        } else {
            console.log(`Sample input ${i} found:`, input);
        }
    }
    
    // Also check all sample image inputs
    const allInputs = document.querySelectorAll('input[name="sample_images[]"]');
    console.log(`Total sample image inputs found: ${allInputs.length}`);
    allInputs.forEach((input, index) => {
        console.log(`Input ${index}:`, input.id, input);
    });
    
    // Add click event listeners to sample image slots
    function addSlotEventListeners() {
        for (let i = 0; i < 4; i++) {
            const slotDiv = document.querySelector(`[data-slot="${i}"]`);
            if (slotDiv) {
                // Remove existing listeners to prevent duplicates
                slotDiv.removeEventListener('click', handleSlotClick);
                slotDiv.addEventListener('click', handleSlotClick);
            }
        }
    }
    
    function handleSlotClick(event) {
        const slotIndex = parseInt(event.currentTarget.getAttribute('data-slot'));
        console.log(`Clicked slot ${slotIndex}`);
        selectSampleImage(slotIndex);
    }
    
    // Initial setup
    addSlotEventListeners();
    
    // Add click event listeners to remove buttons
    document.querySelectorAll('[data-remove-slot]').forEach(button => {
        button.addEventListener('click', function() {
            const slotIndex = parseInt(this.getAttribute('data-remove-slot'));
            console.log(`Remove button clicked for slot ${slotIndex}`);
            removeSampleImage(slotIndex);
        });
    });
    
    // Create sample input elements dynamically if they don't exist
    for (let i = 0; i < 4; i++) {
        let input = document.getElementById(`sample-input-${i}`);
        if (!input) {
            console.log(`Creating sample-input-${i} dynamically`);
            input = document.createElement('input');
            input.type = 'file';
            input.name = 'sample_images[]';
            input.className = 'hidden';
            input.id = `sample-input-${i}`;
            input.accept = 'image/*';
            input.onchange = function() { handleSampleImageSelect(i, this); };
            document.body.appendChild(input);
        }
    }
    
    // Handle form submit event
    form.addEventListener('submit', function(e) {
        console.log('Form submit event triggered');
        
        // Check if form is valid
        if (!form.checkValidity()) {
            console.log('Form validation failed');
            e.preventDefault();
            form.reportValidity();
            return;
        }
        
        console.log('Form is valid, proceeding with submission');
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            กำลังบันทึก...
        `;
        
        // Update file inputs if needed
        try {
            console.log('Before updateFileInput - processedImages:', processedImages);
            updateFileInput();
            updateCoverFileInput();
            
            // Debug: Count file inputs
            const fileInputs = form.querySelectorAll('input[name="sample_images[]"]');
            console.log(`Total sample_images[] inputs: ${fileInputs.length}`);
            fileInputs.forEach((input, index) => {
                console.log(`Input ${index}: ${input.files.length} files`);
            });
            
            console.log('File inputs updated');
        } catch (error) {
            console.error('Error updating file inputs:', error);
        }
        
        // Let the form submit naturally
        console.log('Allowing form to submit naturally');
    });
});
</script>
@endsection
