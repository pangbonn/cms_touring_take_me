@extends('layouts.daisyui')

@section('title', 'เพิ่มทริปใหม่')

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
            <h1 class="text-3xl font-bold text-gray-800">เพิ่มทริปใหม่</h1>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <form action="{{ route('trips.store') }}" method="POST" enctype="multipart/form-data" id="trip-form">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- ชื่อทริป -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">ชื่อทริป <span class="text-red-500">*</span></span>
                            </label>
                            <input type="text" name="name" class="input input-bordered @error('name') input-error @enderror" 
                                   value="{{ old('name') }}" placeholder="กรอกชื่อทริป" required>
                            @error('name')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- รายละเอียดทริป -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">รายละเอียดทริป</span>
                            </label>
                            <textarea name="description" class="textarea textarea-bordered h-32 @error('description') textarea-error @enderror" 
                                      placeholder="กรอกรายละเอียดทริป (สามารถขึ้นบรรทัดใหม่ได้)">{{ old('description') }}</textarea>
                            @error('description')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- รูปภาพหลัก -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">รูปภาพหลัก</span>
                            </label>
                            <input type="file" name="image" class="file-input file-input-bordered w-full @error('image') file-input-error @enderror" 
                                   accept="image/*">
                            @error('image')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- รูป Cover -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">รูป Cover</span>
                            </label>
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
                                <span class="label-text font-semibold">รูปตัวอย่าง (4 รูป)</span>
                            </label>
                            
                            <!-- Preview area for sample images -->
                            <div id="sample-images-preview" class="grid grid-cols-2 gap-4 mt-4">
                                <div class="aspect-square border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center relative" data-index="0">
                                    <div class="text-center">
                                        <span class="text-gray-500 block mb-2">รูปที่ 1</span>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="selectImageForSlot(0)">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            เลือกรูป
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-error btn-xs absolute top-1 right-1 hidden" onclick="removeSampleImage(0)">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="aspect-square border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center relative" data-index="1">
                                    <div class="text-center">
                                        <span class="text-gray-500 block mb-2">รูปที่ 2</span>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="selectImageForSlot(1)">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            เลือกรูป
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-error btn-xs absolute top-1 right-1 hidden" onclick="removeSampleImage(1)">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="aspect-square border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center relative" data-index="2">
                                    <div class="text-center">
                                        <span class="text-gray-500 block mb-2">รูปที่ 3</span>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="selectImageForSlot(2)">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            เลือกรูป
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-error btn-xs absolute top-1 right-1 hidden" onclick="removeSampleImage(2)">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="aspect-square border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center relative" data-index="3">
                                    <div class="text-center">
                                        <span class="text-gray-500 block mb-2">รูปที่ 4</span>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="selectImageForSlot(3)">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            เลือกรูป
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-error btn-xs absolute top-1 right-1 hidden" onclick="removeSampleImage(3)">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Hidden file input -->
                            <input type="file" id="hidden-file-input" accept="image/*" style="display: none;">
                            
                            @error('sample_images.*')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- แผนการเดินทาง -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">แผนการเดินทาง</span>
                            </label>
                            <textarea name="itinerary" class="textarea textarea-bordered h-32 @error('itinerary') textarea-error @enderror" 
                                      placeholder="กรอกแผนการเดินทาง (สามารถขึ้นบรรทัดใหม่ได้)">{{ old('itinerary') }}</textarea>
                            @error('itinerary')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- ค่าใช้จ่ายรวม -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">ค่าใช้จ่ายรวม</span>
                            </label>
                            <textarea name="total_cost" class="textarea textarea-bordered h-32 @error('total_cost') textarea-error @enderror" 
                                      placeholder="กรอกค่าใช้จ่ายรวม (สามารถขึ้นบรรทัดใหม่ได้)">{{ old('total_cost') }}</textarea>
                            @error('total_cost')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- ของส่วนตัวที่ต้องเตรียม -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">ของส่วนตัวที่ต้องเตรียม</span>
                            </label>
                            <textarea name="personal_items" class="textarea textarea-bordered h-32 @error('personal_items') textarea-error @enderror" 
                                      placeholder="กรอกของส่วนตัวที่ต้องเตรียม (สามารถขึ้นบรรทัดใหม่ได้)">{{ old('personal_items') }}</textarea>
                            @error('personal_items')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- คำแนะนำและข้อมูลพื้นที่ -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">คำแนะนำและข้อมูลพื้นที่</span>
                            </label>
                            <textarea name="area_info" class="textarea textarea-bordered h-32 @error('area_info') textarea-error @enderror" 
                                      placeholder="กรอกคำแนะนำและข้อมูลพื้นที่ (สามารถขึ้นบรรทัดใหม่ได้)">{{ old('area_info') }}</textarea>
                            @error('area_info')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- อุปกรณ์เช่า -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold">อุปกรณ์เช่า</span>
                            </label>
                            <textarea name="rental_equipment" class="textarea textarea-bordered h-32 @error('rental_equipment') textarea-error @enderror" 
                                      placeholder="กรอกอุปกรณ์เช่า (สามารถขึ้นบรรทัดใหม่ได้)">{{ old('rental_equipment') }}</textarea>
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
                                <input type="checkbox" name="show_itinerary" class="toggle toggle-primary" checked>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="cursor-pointer label">
                                <span class="label-text font-semibold">แสดงค่าใช้จ่ายรวม</span>
                                <input type="checkbox" name="show_total_cost" class="toggle toggle-primary" checked>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="cursor-pointer label">
                                <span class="label-text font-semibold">แสดงของส่วนตัวที่ต้องเตรียม</span>
                                <input type="checkbox" name="show_personal_items" class="toggle toggle-primary" checked>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="cursor-pointer label">
                                <span class="label-text font-semibold">แสดงอุปกรณ์เช่า</span>
                                <input type="checkbox" name="show_rental_equipment" class="toggle toggle-primary" checked>
                            </label>
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-6">
                        <a href="{{ route('trips.index') }}" class="btn btn-ghost">ยกเลิก</a>
                        <button type="button" class="btn btn-secondary" onclick="testSubmit()">ทดสอบ</button>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
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

<!-- Cropper.js CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

<!-- Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
let cropper = null;
let coverCropper = null;
let currentImageIndex = 0;
let processedImages = new Array(4).fill(null);
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

// Hidden file input handling
document.getElementById('hidden-file-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            showCropModal(e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

function selectImageForSlot(slotIndex) {
    currentImageIndex = slotIndex;
    document.getElementById('hidden-file-input').click();
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
            processedImages[currentImageIndex] = blob;
            
            // Update preview
            const previewDivs = document.querySelectorAll('#sample-images-preview > div');
            const previewDiv = previewDivs[currentImageIndex];
            
            // Clear content and add image
            previewDiv.innerHTML = '';
            const img = document.createElement('img');
            img.src = canvas.toDataURL();
            img.className = 'w-full h-full object-cover rounded-lg';
            previewDiv.appendChild(img);
            
            // Add remove button
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-error btn-xs absolute top-1 right-1';
            btn.onclick = () => removeSampleImage(currentImageIndex);
            btn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            previewDiv.appendChild(btn);
            
            // Close modal
            document.getElementById('crop-modal').close();
            
            // Update file input
            updateFileInput();
        }, 'image/jpeg', 0.9);
    }
}

function removeSampleImage(index) {
    processedImages[index] = null;
    
    // Reset preview div
    const previewDivs = document.querySelectorAll('#sample-images-preview > div');
    const previewDiv = previewDivs[index];
    previewDiv.innerHTML = `
        <div class="text-center">
            <span class="text-gray-500 block mb-2">รูปที่ ${index + 1}</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="selectImageForSlot(${index})">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                เลือกรูป
            </button>
        </div>
    `;
    
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
    // Reset hidden file input
    document.getElementById('hidden-file-input').value = '';
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
    const dt = new DataTransfer();
    processedImages.forEach((blob, index) => {
        if (blob) {
            const file = new File([blob], `sample_${index}_${Date.now()}.jpg`, { type: 'image/jpeg' });
            dt.items.add(file);
        }
    });
    
    // Update the hidden file input
    document.getElementById('hidden-file-input').files = dt.files;
    
    // Create actual file inputs for form submission
    const form = document.querySelector('form');
    
    // Remove existing sample image inputs
    const existingInputs = form.querySelectorAll('input[name^="sample_images"]');
    existingInputs.forEach(input => input.remove());
    
    // Add new file inputs for each processed image
    processedImages.forEach((blob, index) => {
        if (blob) {
            const file = new File([blob], `sample_${index}_${Date.now()}.jpg`, { type: 'image/jpeg' });
            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'sample_images[]';
            input.style.display = 'none';
            
            // Create a new FileList with the file
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            
            form.appendChild(input);
        }
    });
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
            updateFileInput();
            updateCoverFileInput();
            console.log('File inputs updated');
        } catch (error) {
            console.error('Error updating file inputs:', error);
        }
        
        // Let the form submit naturally
        console.log('Allowing form to submit naturally');
    });
});

// Test function
function testSubmit() {
    console.log('Test submit clicked');
    const form = document.getElementById('trip-form');
    if (form) {
        console.log('Form found, submitting...');
        form.submit();
    } else {
        console.error('Form not found!');
    }
}
</script>
@endsection
