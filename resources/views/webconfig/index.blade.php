@extends('layouts.daisyui')

@section('title', 'การตั้งค่าเว็บไซต์')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-base-content">การตั้งค่าเว็บไซต์</h1>
            <p class="text-base-content/70 mt-1">จัดการข้อมูลเว็บไซต์และข้อมูลติดต่อ</p>
        </div>
    </div>

    <!-- Web Config Form -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <form id="webConfigForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Site Information -->
                        <div class="card bg-base-200">
                            <div class="card-body">
                                <h3 class="card-title text-lg mb-4">
                                    <i class="fas fa-globe text-primary"></i>
                                    ข้อมูลเว็บไซต์
                                </h3>
                                
                                <div class="form-control mb-4">
                                    <label class="label">
                                        <span class="label-text font-semibold">ชื่อเว็บไซต์</span>
                                    </label>
                                    <input type="text" name="site_name" value="{{ $config->site_name }}" 
                                           class="input input-bordered w-full" required>
                                </div>

                                <div class="form-control mb-4">
                                    <label class="label">
                                        <span class="label-text font-semibold">คำอธิบายเว็บไซต์</span>
                                    </label>
                                    <textarea name="site_description" rows="3" 
                                              class="textarea textarea-bordered w-full">{{ $config->site_description }}</textarea>
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">โลโก้เว็บไซต์</span>
                                    </label>
                                    <input type="file" name="site_logo" accept="image/*" 
                                           class="file-input file-input-bordered w-full">
                                    <div class="label">
                                        <span class="label-text-alt">รองรับไฟล์ JPEG, PNG ขนาดไม่เกิน 2MB</span>
                                    </div>
                                    
                                    @if($config->site_logo)
                                    <div class="mt-3">
                                        <img src="{{ $config->getLogoUrl() }}" alt="Current Logo" 
                                             class="w-32 h-32 object-contain border rounded-lg">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="card bg-base-200">
                            <div class="card-body">
                                <h3 class="card-title text-lg mb-4">
                                    <i class="fas fa-address-book text-primary"></i>
                                    ข้อมูลติดต่อ
                                </h3>
                                
                                <div class="form-control mb-4">
                                    <label class="label">
                                        <span class="label-text font-semibold">ที่อยู่</span>
                                    </label>
                                    <input type="text" name="contact_address" value="{{ $config->contact_address }}" 
                                           class="input input-bordered w-full">
                                </div>

                                <div class="form-control mb-4">
                                    <label class="label">
                                        <span class="label-text font-semibold">เบอร์โทรศัพท์</span>
                                    </label>
                                    <input type="text" name="contact_phone" value="{{ $config->contact_phone }}" 
                                           class="input input-bordered w-full">
                                </div>

                                <div class="form-control mb-4">
                                    <label class="label">
                                        <span class="label-text font-semibold">อีเมล</span>
                                    </label>
                                    <input type="email" name="contact_email" value="{{ $config->contact_email }}" 
                                           class="input input-bordered w-full">
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">ใบอนุญาต</span>
                                    </label>
                                    <input type="text" name="license_number" value="{{ $config->license_number }}" 
                                           class="input input-bordered w-full" placeholder="หมายเลขใบอนุญาต">
                                    <div class="label">
                                        <span class="label-text-alt">หมายเลขใบอนุญาตประกอบธุรกิจท่องเที่ยว</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Social Media -->
                        <div class="card bg-base-200">
                            <div class="card-body">
                                <h3 class="card-title text-lg mb-4">
                                    <i class="fas fa-share-alt text-primary"></i>
                                    Social Media
                                </h3>
                                
                                <div class="form-control mb-4">
                                    <label class="label">
                                        <span class="label-text font-semibold">Line ID</span>
                                    </label>
                                    <input type="text" name="line_id" value="{{ $config->line_id }}" 
                                           class="input input-bordered w-full">
                                </div>

                                <div class="form-control mb-4">
                                    <label class="label">
                                        <span class="label-text font-semibold">Facebook URL</span>
                                    </label>
                                    <input type="url" name="facebook_url" value="{{ $config->facebook_url }}" 
                                           class="input input-bordered w-full" placeholder="https://facebook.com/...">
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">TikTok URL</span>
                                    </label>
                                    <input type="url" name="tiktok_url" value="{{ $config->tiktok_url }}" 
                                           class="input input-bordered w-full" placeholder="https://tiktok.com/@...">
                                </div>
                            </div>
                        </div>

                        <!-- About Us -->
                        <div class="card bg-base-200">
                            <div class="card-body">
                                <h3 class="card-title text-lg mb-4">
                                    <i class="fas fa-info-circle text-primary"></i>
                                    เกี่ยวกับเรา
                                </h3>
                                
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">เนื้อหาเกี่ยวกับเรา</span>
                                    </label>
                                    <textarea name="about_us" rows="8" 
                                              class="textarea textarea-bordered w-full">{{ $config->about_us }}</textarea>
                                    <div class="label">
                                        <span class="label-text-alt">อธิบายเกี่ยวกับบริษัทหรือบริการ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-8">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save mr-2"></i>
                        บันทึกการตั้งค่า
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Crop Modal -->
<dialog id="imageCropModal" class="modal">
    <div class="modal-box max-w-4xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">ปรับแต่งโลโก้</h3>
        
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
                            <span class="label-text">ขนาดโลโก้</span>
                        </label>
                        <div class="text-sm text-base-content/70">
                            <p>อัตราส่วน: 1:1 (สี่เหลี่ยมจัตุรัส)</p>
                            <p>ขนาดแนะนำ: 200x200px</p>
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
                            <canvas id="previewCanvas" width="128" height="128" style="width: 100%; height: 100%; object-fit: contain; border-radius: 4px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transform: scale(1);"></canvas>
                        </div>
                        <div class="text-xs text-base-content/60 mt-1">
                            ขนาดจริง: 200x200px
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

// Show DaisyUI alert
function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-toast');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-toast fixed top-4 right-4 z-50 max-w-sm`;
    alertDiv.innerHTML = `
        <div>
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="btn btn-sm btn-ghost" onclick="this.parentElement.remove()">✕</button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.remove();
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
        width: 200,
        height: 200,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
        fillColor: '#ffffff'
    });
    
    if (croppedCanvas) {
        // Convert canvas to blob
        croppedCanvas.toBlob(function(blob) {
            // Create new file from blob
            const file = new File([blob], 'cropped_logo.png', { type: 'image/png' });
            
            // Create new FileList
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            
            // Update file input
            currentFileInput.files = dataTransfer.files;
            
            // Close modal
            document.getElementById('imageCropModal').close();
            
            // Show success message
            showAlert('โลโก้ถูกปรับแต่งเรียบร้อยแล้ว', 'success');
        }, 'image/png', 0.9);
    }
}

// Form submission
document.getElementById('webConfigForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        showAlert('ไม่พบ CSRF token กรุณารีเฟรชหน้าเว็บ', 'error');
        return;
    }
    
    formData.set('_method', 'PUT');
    
    fetch('/webconfig', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            // Reload page to show updated data
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert(data.message || 'เกิดข้อผิดพลาด', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
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
document.querySelectorAll('input[name="site_logo"]').forEach(function(input) {
    input.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleImageUpload(this, this.closest('form'));
        }
    });
});
</script>
@endpush
