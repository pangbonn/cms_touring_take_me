@extends('layouts.daisyui')

@section('title', 'รายละเอียดทริป - ' . $trip->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('trips.index') }}" class="btn btn-ghost btn-sm mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                กลับ
            </a>
            <h1 class="text-3xl font-bold text-gray-800">รายละเอียดทริป</h1>
        </div>

        <div class="card bg-base-100 shadow-xl">
            @if($trip->cover_image)
                <figure class="px-6 pt-6">
                    <img src="{{ asset('storage/' . $trip->cover_image) }}" alt="{{ $trip->name }}" class="rounded-xl w-full h-64 object-cover">
                </figure>
            @elseif($trip->image)
                <figure class="px-6 pt-6">
                    <img src="{{ asset('storage/' . $trip->image) }}" alt="{{ $trip->name }}" class="rounded-xl w-full h-64 object-cover">
                </figure>
            @else
                <figure class="px-6 pt-6">
                    <div class="w-full h-64 bg-gray-200 rounded-xl flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </figure>
            @endif
            
            <div class="card-body">
                <h2 class="card-title text-2xl">{{ $trip->name }}</h2>
                
                <!-- รายละเอียดทริป -->
                @if($trip->description)
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">รายละเอียดทริป</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="whitespace-pre-line">{{ $trip->description }}</p>
                        </div>
                    </div>
                @endif
                
                <!-- รูปตัวอย่าง -->
                @if($trip->sample_images && count($trip->sample_images) > 0)
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-3">รูปตัวอย่าง</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($trip->sample_images as $sampleImage)
                                <div class="aspect-square">
                                    <img src="{{ asset('storage/' . $sampleImage) }}" alt="Sample Image" class="w-full h-full object-cover rounded-lg">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- แผนการเดินทาง -->
                    @if($trip->show_itinerary && $trip->itinerary)
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-lg">แผนการเดินทาง</span>
                            </label>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="whitespace-pre-line">{{ $trip->itinerary }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- ค่าใช้จ่ายรวม -->
                    @if($trip->show_total_cost && $trip->total_cost)
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-lg">ค่าใช้จ่ายรวม</span>
                            </label>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="whitespace-pre-line">{{ $trip->total_cost }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- ของส่วนตัวที่ต้องเตรียม -->
                    @if($trip->show_personal_items && $trip->personal_items)
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-lg">ของส่วนตัวที่ต้องเตรียม</span>
                            </label>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="whitespace-pre-line">{{ $trip->personal_items }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- คำแนะนำและข้อมูลพื้นที่ -->
                    @if($trip->area_info)
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-lg">คำแนะนำและข้อมูลพื้นที่</span>
                            </label>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="whitespace-pre-line">{{ $trip->area_info }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- อุปกรณ์เช่า -->
                    @if($trip->show_rental_equipment && $trip->rental_equipment)
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold text-lg">อุปกรณ์เช่า</span>
                            </label>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="whitespace-pre-line">{{ $trip->rental_equipment }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- รอบการเดินทาง -->
                @if($trip->schedules->count() > 0)
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-4">รอบการเดินทาง</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($trip->schedules as $schedule)
                                <div class="card bg-base-200 shadow-sm">
                                    <div class="card-body">
                                        <h4 class="card-title text-lg">
                                            {{ \Carbon\Carbon::parse($schedule->departure_date)->format('d/m/Y') }}
                                            @if($schedule->return_date)
                                                <br><span class="text-sm font-normal">
                                                    - {{ \Carbon\Carbon::parse($schedule->return_date)->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </h4>
                                        
                                        @if($schedule->price)
                                            <p class="text-primary font-semibold">
                                                {{ number_format($schedule->price, 2) }} บาท
                                            </p>
                                        @endif
                                        
                                        @if($schedule->max_participants)
                                            <p class="text-sm text-gray-600">
                                                สูงสุด {{ $schedule->max_participants }} คน
                                            </p>
                                        @endif
                                        
                                        <div class="card-actions justify-end">
                                            <div class="badge badge-outline">
                                                {{ $schedule->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="card-actions justify-end mt-6">
                    <a href="{{ route('trips.edit', $trip) }}" class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        แก้ไขทริป
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
