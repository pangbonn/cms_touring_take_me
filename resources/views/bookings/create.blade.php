@extends('layouts.daisyui')

@section('title', 'จองทัวร์')
@section('page-title', 'จองทัวร์')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-primary mb-4">
            <i class="fas fa-calendar-check me-3"></i>
            จองทัวร์
        </h1>
        <p class="text-lg text-base-content/70">กรอกข้อมูลเพื่อจองทัวร์ของคุณ</p>
    </div>

    <!-- Booking Form -->
    <div class="max-w-4xl mx-auto">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <form id="bookingForm" class="space-y-6">
                    @csrf
                    
                    <!-- Trip Selection -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">
                                <i class="fas fa-map-marked-alt me-2"></i>
                                เลือกทริป
                            </span>
                        </label>
                        <select id="tripSelect" class="select select-bordered w-full" required>
                            <option value="">กรุณาเลือกทริป</option>
                        </select>
                        <div class="label">
                            <span class="label-text-alt text-error" id="tripError"></span>
                        </div>
                    </div>

                    <!-- Trip Schedule Selection -->
                    <div class="form-control" id="scheduleSection" style="display: none;">
                        <label class="label">
                            <span class="label-text font-semibold">
                                <i class="fas fa-calendar me-2"></i>
                                เลือกวันที่เดินทาง
                            </span>
                        </label>
                        <div id="scheduleOptions" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Schedule options will be populated here -->
                        </div>
                        <div class="label">
                            <span class="label-text-alt text-error" id="scheduleError"></span>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="divider">
                        <i class="fas fa-user me-2"></i>
                        ข้อมูลลูกค้า
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">ชื่อ-นามสกุล *</span>
                            </label>
                            <input type="text" id="customerName" class="input input-bordered w-full" placeholder="กรุณากรอกชื่อ-นามสกุล" required>
                            <div class="label">
                                <span class="label-text-alt text-error" id="nameError"></span>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">เบอร์โทรศัพท์ *</span>
                            </label>
                            <input type="tel" id="customerPhone" class="input input-bordered w-full" placeholder="กรุณากรอกเบอร์โทรศัพท์" required>
                            <div class="label">
                                <span class="label-text-alt text-error" id="phoneError"></span>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">อีเมล *</span>
                            </label>
                            <input type="email" id="customerEmail" class="input input-bordered w-full" placeholder="กรุณากรอกอีเมล" required>
                            <div class="label">
                                <span class="label-text-alt text-error" id="emailError"></span>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Line ID</span>
                            </label>
                            <input type="text" id="customerLineId" class="input input-bordered w-full" placeholder="กรุณากรอก Line ID (ไม่บังคับ)">
                            <div class="label">
                                <span class="label-text-alt text-error" id="lineIdError"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="divider">
                        <i class="fas fa-info-circle me-2"></i>
                        รายละเอียดการจอง
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">จำนวนผู้เข้าร่วม *</span>
                            </label>
                            <input type="number" id="guests" class="input input-bordered w-full" min="1" placeholder="จำนวนผู้เข้าร่วม" required>
                            <div class="label">
                                <span class="label-text-alt text-error" id="guestsError"></span>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">ราคารวม (บาท) *</span>
                            </label>
                            <input type="number" id="totalPrice" class="input input-bordered w-full" min="0" step="0.01" placeholder="ราคารวม" required>
                            <div class="label">
                                <span class="label-text-alt text-error" id="priceError"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">หมายเหตุ</span>
                        </label>
                        <textarea id="notes" class="textarea textarea-bordered w-full" rows="3" placeholder="หมายเหตุเพิ่มเติม (ไม่บังคับ)"></textarea>
                        <div class="label">
                            <span class="label-text-alt text-error" id="notesError"></span>
                        </div>
                    </div>

                    <!-- Trip Information Display -->
                    <div id="tripInfo" class="card bg-base-200 shadow-lg" style="display: none;">
                        <div class="card-body">
                            <h3 class="card-title text-primary">
                                <i class="fas fa-info-circle me-2"></i>
                                ข้อมูลทริป
                            </h3>
                            <div id="tripDetails" class="space-y-2">
                                <!-- Trip details will be populated here -->
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-center pt-6">
                        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg w-full md:w-auto">
                            <i class="fas fa-calendar-check me-2"></i>
                            จองทัวร์
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="modal">
        <div class="modal-box text-center">
            <div class="loading loading-spinner loading-lg text-primary mb-4"></div>
            <h3 class="font-bold text-lg">กำลังประมวลผล...</h3>
            <p class="py-4">กรุณารอสักครู่ กำลังดำเนินการจองทัวร์ให้คุณ</p>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-box text-center">
            <div class="text-success text-6xl mb-4">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 class="font-bold text-lg text-success mb-4">จองสำเร็จ!</h3>
            <div class="bg-base-200 p-4 rounded-lg mb-4">
                <p class="font-semibold">รหัสการจอง:</p>
                <p class="text-primary text-xl font-bold" id="bookingId"></p>
            </div>
            <p class="mb-4">ขอบคุณที่เลือกใช้บริการของเรา เราจะติดต่อกลับไปในเร็วๆ นี้</p>
            <div class="modal-action">
                <button class="btn btn-primary" onclick="location.reload()">
                    <i class="fas fa-plus me-2"></i>
                    จองใหม่
                </button>
                <button class="btn btn-ghost" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>
                    พิมพ์
                </button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="modal">
        <div class="modal-box text-center">
            <div class="text-error text-6xl mb-4">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="font-bold text-lg text-error mb-4">เกิดข้อผิดพลาด</h3>
            <p class="mb-4" id="errorMessage">ไม่สามารถดำเนินการจองได้ กรุณาลองใหม่อีกครั้ง</p>
            <div class="modal-action">
                <button class="btn btn-primary" onclick="closeErrorModal()">
                    <i class="fas fa-redo me-2"></i>
                    ลองใหม่
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tripSelect = document.getElementById('tripSelect');
    const scheduleSection = document.getElementById('scheduleSection');
    const scheduleOptions = document.getElementById('scheduleOptions');
    const tripInfo = document.getElementById('tripInfo');
    const tripDetails = document.getElementById('tripDetails');
    const bookingForm = document.getElementById('bookingForm');
    const loadingModal = document.getElementById('loadingModal');
    const successModal = document.getElementById('successModal');
    const errorModal = document.getElementById('errorModal');

    let selectedTrip = null;
    let selectedSchedule = null;

    // Load trips on page load
    loadTrips();

    // Trip selection change
    tripSelect.addEventListener('change', function() {
        const tripId = this.value;
        if (tripId) {
            loadTripSchedules(tripId);
            loadTripDetails(tripId);
        } else {
            scheduleSection.style.display = 'none';
            tripInfo.style.display = 'none';
        }
    });

    // Form submission
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitBooking();
    });

    // Load trips from API
    async function loadTrips() {
        try {
            const response = await fetch('/api/trips');
            const data = await response.json();
            
            if (data.success) {
                tripSelect.innerHTML = '<option value="">กรุณาเลือกทริป</option>';
                data.data.forEach(trip => {
                    const option = document.createElement('option');
                    option.value = trip.id;
                    option.textContent = trip.name;
                    tripSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading trips:', error);
            showError('ไม่สามารถโหลดข้อมูลทริปได้');
        }
    }

    // Load trip schedules
    async function loadTripSchedules(tripId) {
        try {
            const response = await fetch(`/api/trips/${tripId}/schedules`);
            const data = await response.json();
            
            if (data.success) {
                scheduleSection.style.display = 'block';
                scheduleOptions.innerHTML = '';
                
                data.data.forEach(schedule => {
                    const scheduleCard = createScheduleCard(schedule);
                    scheduleOptions.appendChild(scheduleCard);
                });
            }
        } catch (error) {
            console.error('Error loading schedules:', error);
            showError('ไม่สามารถโหลดข้อมูลตารางเวลาได้');
        }
    }

    // Create schedule card
    function createScheduleCard(schedule) {
        const card = document.createElement('div');
        card.className = 'card bg-base-100 border-2 border-base-300 hover:border-primary cursor-pointer transition-all';
        card.innerHTML = `
            <div class="card-body p-4">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-bold text-lg">${formatDateThai(schedule.departure_date)}</h4>
                    <span class="badge badge-primary">${schedule.price} บาท</span>
                </div>
                <div class="text-sm text-base-content/70 space-y-1">
                    <p><i class="fas fa-calendar me-2"></i>${schedule.departure_date_thai} - ${schedule.return_date_thai || 'ไม่ระบุ'}</p>
                    <p><i class="fas fa-users me-2"></i>จำนวนสูงสุด: ${schedule.max_participants} คน</p>
                    <p><i class="fas fa-clock me-2"></i>${schedule.duration}</p>
                </div>
            </div>
        `;
        
        card.addEventListener('click', function() {
            // Remove previous selection
            document.querySelectorAll('.schedule-card').forEach(c => {
                c.classList.remove('border-primary', 'bg-primary/10');
                c.classList.add('border-base-300');
            });
            
            // Add selection to current card
            this.classList.add('border-primary', 'bg-primary/10');
            this.classList.remove('border-base-300');
            
            selectedSchedule = schedule;
            updateTripInfo();
        });
        
        card.classList.add('schedule-card');
        return card;
    }

    // Load trip details
    async function loadTripDetails(tripId) {
        try {
            const response = await fetch(`/api/trips/${tripId}`);
            const data = await response.json();
            
            if (data.success) {
                selectedTrip = data.data;
                updateTripInfo();
            }
        } catch (error) {
            console.error('Error loading trip details:', error);
        }
    }

    // Update trip info display
    function updateTripInfo() {
        if (selectedTrip && selectedSchedule) {
            tripInfo.style.display = 'block';
            tripDetails.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="font-semibold">ชื่อทริป:</p>
                        <p>${selectedTrip.name}</p>
                    </div>
                    <div>
                        <p class="font-semibold">วันที่เดินทาง:</p>
                        <p>${selectedSchedule.departure_date_thai} - ${selectedSchedule.return_date_thai || 'ไม่ระบุ'}</p>
                    </div>
                    <div>
                        <p class="font-semibold">ระยะเวลา:</p>
                        <p>${selectedSchedule.duration}</p>
                    </div>
                    <div>
                        <p class="font-semibold">ราคาต่อคน:</p>
                        <p>${selectedSchedule.price} บาท</p>
                    </div>
                </div>
            `;
        }
    }

    // Submit booking
    async function submitBooking() {
        if (!validateForm()) {
            return;
        }

        const bookingData = {
            tripId: parseInt(tripSelect.value),
            customer: {
                name: document.getElementById('customerName').value,
                phone: document.getElementById('customerPhone').value,
                email: document.getElementById('customerEmail').value,
                lineId: document.getElementById('customerLineId').value || null
            },
            bookingDetails: {
                guests: parseInt(document.getElementById('guests').value),
                dateSlot: {
                    id: selectedSchedule.id
                },
                notes: document.getElementById('notes').value || null,
                totalPrice: parseFloat(document.getElementById('totalPrice').value),
                bookingDate: new Date().toISOString()
            },
            source: 'web_booking'
        };

        showLoading();

        try {
            const response = await fetch('/api/bookings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(bookingData)
            });

            const result = await response.json();

            if (result.success) {
                showSuccess(result.data.bookingId);
            } else {
                showError(result.message || 'เกิดข้อผิดพลาดในการจอง');
            }
        } catch (error) {
            console.error('Booking error:', error);
            showError('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
        } finally {
            hideLoading();
        }
    }

    // Validate form
    function validateForm() {
        let isValid = true;
        
        // Clear previous errors
        clearErrors();
        
        // Validate trip selection
        if (!tripSelect.value) {
            showFieldError('tripError', 'กรุณาเลือกทริป');
            isValid = false;
        }
        
        // Validate schedule selection
        if (!selectedSchedule) {
            showFieldError('scheduleError', 'กรุณาเลือกวันที่เดินทาง');
            isValid = false;
        }
        
        // Validate customer name
        const customerName = document.getElementById('customerName').value.trim();
        if (!customerName) {
            showFieldError('nameError', 'กรุณากรอกชื่อ-นามสกุล');
            isValid = false;
        }
        
        // Validate phone
        const customerPhone = document.getElementById('customerPhone').value.trim();
        if (!customerPhone) {
            showFieldError('phoneError', 'กรุณากรอกเบอร์โทรศัพท์');
            isValid = false;
        }
        
        // Validate email
        const customerEmail = document.getElementById('customerEmail').value.trim();
        if (!customerEmail) {
            showFieldError('emailError', 'กรุณากรอกอีเมล');
            isValid = false;
        } else if (!isValidEmail(customerEmail)) {
            showFieldError('emailError', 'รูปแบบอีเมลไม่ถูกต้อง');
            isValid = false;
        }
        
        // Validate guests
        const guests = parseInt(document.getElementById('guests').value);
        if (!guests || guests < 1) {
            showFieldError('guestsError', 'กรุณากรอกจำนวนผู้เข้าร่วมอย่างน้อย 1 คน');
            isValid = false;
        }
        
        // Validate total price
        const totalPrice = parseFloat(document.getElementById('totalPrice').value);
        if (!totalPrice || totalPrice < 0) {
            showFieldError('priceError', 'กรุณากรอกราคารวม');
            isValid = false;
        }
        
        return isValid;
    }

    // Helper functions
    function clearErrors() {
        const errorElements = document.querySelectorAll('[id$="Error"]');
        errorElements.forEach(el => el.textContent = '');
    }

    function showFieldError(fieldId, message) {
        document.getElementById(fieldId).textContent = message;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function formatDateThai(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('th-TH', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    function showLoading() {
        loadingModal.classList.add('modal-open');
    }

    function hideLoading() {
        loadingModal.classList.remove('modal-open');
    }

    function showSuccess(bookingId) {
        document.getElementById('bookingId').textContent = bookingId;
        successModal.classList.add('modal-open');
    }

    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        errorModal.classList.add('modal-open');
    }

    function closeErrorModal() {
        errorModal.classList.remove('modal-open');
    }

    // Auto-fill price when schedule is selected
    function updatePrice() {
        if (selectedSchedule && document.getElementById('guests').value) {
            const guests = parseInt(document.getElementById('guests').value);
            const pricePerPerson = parseFloat(selectedSchedule.price);
            const totalPrice = guests * pricePerPerson;
            document.getElementById('totalPrice').value = totalPrice;
        }
    }

    // Update price when guests change
    document.getElementById('guests').addEventListener('input', updatePrice);
});
</script>
@endpush
