@extends('layouts.daisyui')

@section('title', 'การจัดการทริป')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">การจัดการทริป</h1>
        <a href="{{ route('trips.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            เพิ่มทริปใหม่
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-6">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($trips as $trip)
            <div class="card bg-base-100 shadow-xl">
                @if($trip->cover_image)
                    <figure class="px-6 pt-6">
                        <img src="{{ asset('storage/' . $trip->cover_image) }}" alt="{{ $trip->name }}" class="rounded-xl w-full h-48 object-cover">
                    </figure>
                @elseif($trip->image)
                    <figure class="px-6 pt-6">
                        <img src="{{ asset('storage/' . $trip->image) }}" alt="{{ $trip->name }}" class="rounded-xl w-full h-48 object-cover">
                    </figure>
                @else
                    <figure class="px-6 pt-6">
                        <div class="w-full h-48 bg-gray-200 rounded-xl flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </figure>
                @endif
                
                <div class="card-body">
                    <h2 class="card-title">{{ $trip->name }}</h2>
                    
                    @if($trip->description)
                        <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($trip->description, 100) }}</p>
                    @endif
                    
                    @if($trip->schedules->count() > 0)
                        <div class="badge badge-primary">
                            {{ $trip->schedules->count() }} รอบ
                        </div>
                    @endif
                    
                    <div class="card-actions justify-end mt-4">
                        <a href="{{ route('trips.show', $trip) }}" class="btn btn-outline btn-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            ดู
                        </a>
                        <a href="{{ route('trips.edit', $trip) }}" class="btn btn-outline btn-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            แก้ไข
                        </a>
                        <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="inline" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบทริปนี้?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-error btn-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                ลบ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">ยังไม่มีทริป</h3>
                <p class="text-gray-500 mb-4">เริ่มต้นด้วยการสร้างทริปแรกของคุณ</p>
                <a href="{{ route('trips.create') }}" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    เพิ่มทริปใหม่
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
