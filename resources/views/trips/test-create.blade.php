@extends('layouts.daisyui')

@section('title', 'ทดสอบสร้างทริป')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">ทดสอบสร้างทริป (แบบง่าย)</h1>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <form action="{{ route('trips.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- ชื่อทริป -->
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">ชื่อทริป <span class="text-red-500">*</span></span>
                        </label>
                        <input type="text" name="name" class="input input-bordered" 
                               value="{{ old('name') }}" placeholder="กรอกชื่อทริป" required>
                        @error('name')
                            <label class="label">
                                <span class="label-text-alt text-red-500">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- รายละเอียดทริป -->
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">รายละเอียดทริป</span>
                        </label>
                        <textarea name="description" class="textarea textarea-bordered h-32" 
                                  placeholder="กรอกรายละเอียดทริป">{{ old('description') }}</textarea>
                        @error('description')
                            <label class="label">
                                <span class="label-text-alt text-red-500">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- รูปภาพหลัก -->
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">รูปภาพหลัก</span>
                        </label>
                        <input type="file" name="image" class="file-input file-input-bordered w-full" 
                               accept="image/*">
                        @error('image')
                            <label class="label">
                                <span class="label-text-alt text-red-500">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- แผนการเดินทาง -->
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">แผนการเดินทาง</span>
                        </label>
                        <textarea name="itinerary" class="textarea textarea-bordered h-32" 
                                  placeholder="กรอกแผนการเดินทาง">{{ old('itinerary') }}</textarea>
                        @error('itinerary')
                            <label class="label">
                                <span class="label-text-alt text-red-500">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- ค่าใช้จ่ายรวม -->
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">ค่าใช้จ่ายรวม</span>
                        </label>
                        <textarea name="total_cost" class="textarea textarea-bordered h-24" 
                                  placeholder="กรอกค่าใช้จ่ายรวม">{{ old('total_cost') }}</textarea>
                        @error('total_cost')
                            <label class="label">
                                <span class="label-text-alt text-red-500">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- ของส่วนตัวที่ต้องเตรียม -->
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">ของส่วนตัวที่ต้องเตรียม</span>
                        </label>
                        <textarea name="personal_items" class="textarea textarea-bordered h-24" 
                                  placeholder="กรอกของส่วนตัวที่ต้องเตรียม">{{ old('personal_items') }}</textarea>
                        @error('personal_items')
                            <label class="label">
                                <span class="label-text-alt text-red-500">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- คำแนะนำและข้อมูลพื้นที่ -->
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">คำแนะนำและข้อมูลพื้นที่</span>
                        </label>
                        <textarea name="area_info" class="textarea textarea-bordered h-24" 
                                  placeholder="กรอกคำแนะนำและข้อมูลพื้นที่">{{ old('area_info') }}</textarea>
                        @error('area_info')
                            <label class="label">
                                <span class="label-text-alt text-red-500">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- อุปกรณ์เช่า -->
                    <div class="form-control mb-6">
                        <label class="label">
                            <span class="label-text font-semibold">อุปกรณ์เช่า</span>
                        </label>
                        <textarea name="rental_equipment" class="textarea textarea-bordered h-24" 
                                  placeholder="กรอกอุปกรณ์เช่า">{{ old('rental_equipment') }}</textarea>
                        @error('rental_equipment')
                            <label class="label">
                                <span class="label-text-alt text-red-500">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- ปุ่มบันทึก -->
                    <div class="card-actions justify-end">
                        <a href="{{ route('trips.index') }}" class="btn btn-ghost">ยกเลิก</a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


