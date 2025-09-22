# CMS Touring API Documentation

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
API ไม่ต้องการ authentication สำหรับการอ่านข้อมูล

## Response Format
```json
{
    "success": true,
    "message": "ข้อความอธิบาย",
    "data": {},
    "meta": {}
}
```

---

## Web Configuration API

### 1. Get All Website Configuration
```http
GET /api/web-config
```

**Response:**
```json
{
    "success": true,
    "message": "ดึงข้อมูลการตั้งค่าเว็บไซต์สำเร็จ",
    "data": {
        "id": 1,
        "site_name": "CMS Touring",
        "site_description": "ระบบจัดการทัวร์ออนไลน์",
        "contact_address": "กรุงเทพมหานคร",
        "contact_phone": "02-123-4567",
        "contact_email": "info@cmstouring.com",
        "license_number": "เลขที่ใบอนุญาต",
        "line_id": "line_id_here",
        "facebook_url": "https://facebook.com/company",
        "tiktok_url": "https://tiktok.com/@company",
        "about_us": "เราเป็นบริษัททัวร์ที่ให้บริการทัวร์คุณภาพสูง",
        "logo_url": "http://127.0.0.1:8000/storage/logos/logo.png",
        "created_at": "2025-09-08T10:00:00.000000Z",
        "updated_at": "2025-09-08T10:00:00.000000Z"
    }
}
```

### 2. Get Contact Information
```http
GET /api/web-config/contact
```

**Response:**
```json
{
    "success": true,
    "message": "ดึงข้อมูลการติดต่อสำเร็จ",
    "data": {
        "site_name": "CMS Touring",
        "contact_email": "info@cmstouring.com",
        "contact_phone": "02-123-4567",
        "contact_address": "กรุงเทพมหานคร",
        "license_number": "เลขที่ใบอนุญาต"
    }
}
```

### 3. Get Social Media Links
```http
GET /api/web-config/social
```

**Response:**
```json
{
    "success": true,
    "message": "ดึงข้อมูลโซเชียลมีเดียสำเร็จ",
    "data": {
        "facebook": "https://facebook.com/company",
        "tiktok": "https://tiktok.com/@company",
        "line": "line_id_here"
    }
}
```

### 4. Get Company Information
```http
GET /api/web-config/company
```

**Response:**
```json
{
    "success": true,
    "message": "ดึงข้อมูลบริษัทสำเร็จ",
    "data": {
        "site_name": "CMS Touring",
        "site_description": "ระบบจัดการทัวร์ออนไลน์",
        "about_us": "เราเป็นบริษัททัวร์ที่ให้บริการทัวร์คุณภาพสูง",
        "logo_url": "http://127.0.0.1:8000/storage/logos/logo.png",
        "license_number": "เลขที่ใบอนุญาต"
    }
}
```

### 5. Get Specific Configuration
```http
GET /api/web-config/{key}
```

**Available keys:**
- `site_name`
- `site_description`
- `contact_email`
- `contact_phone`
- `contact_address`
- `license_number`
- `line_id`
- `facebook_url`
- `tiktok_url`
- `about_us`
- `site_logo`

### 6. Update Website Configuration
```http
PUT /api/web-config
```

**Request Body:**
```json
{
    "site_name": "CMS Touring",
    "site_description": "ระบบจัดการทัวร์ออนไลน์",
    "contact_address": "กรุงเทพมหานคร",
    "contact_phone": "02-123-4567",
    "contact_email": "info@cmstouring.com",
    "license_number": "เลขที่ใบอนุญาต",
    "line_id": "line_id_here",
    "facebook_url": "https://facebook.com/company",
    "tiktok_url": "https://tiktok.com/@company",
    "about_us": "เราเป็นบริษัททัวร์ที่ให้บริการทัวร์คุณภาพสูง"
}
```

**Note:** สำหรับอัปโหลด logo ใช้ `multipart/form-data` และส่งไฟล์ใน field `site_logo`

### 7. Reset to Default Configuration
```http
POST /api/web-config/reset
```

**Response:**
```json
{
    "success": true,
    "message": "รีเซ็ตการตั้งค่าเว็บไซต์เป็นค่าเริ่มต้นสำเร็จ",
    "data": {
        "id": 1,
        "site_name": "CMS Touring",
        "site_description": "ระบบจัดการทัวร์ออนไลน์",
        "contact_address": "กรุงเทพมหานคร",
        "contact_phone": "02-123-4567",
        "contact_email": "info@cmstouring.com",
        "about_us": "เราเป็นบริษัททัวร์ที่ให้บริการทัวร์คุณภาพสูง พร้อมทีมงานมืออาชีพ",
        "logo_url": "http://127.0.0.1:8000/images/default-logo.png",
        "updated_at": "2025-09-08T10:00:00.000000Z"
    }
}

---

## Trip API

### 1. Get All Trips
```http
GET /api/trips
```

**Query Parameters:**
- `search` (optional): Search by trip name, description, or location
- `location` (optional): Filter by location
- `min_price` (optional): Minimum price filter
- `max_price` (optional): Maximum price filter
- `min_days` (optional): Minimum duration in days
- `max_days` (optional): Maximum duration in days
- `month` (optional): Filter by month (1-12)
- `year` (optional): Filter by year
- `start_date` (optional): Filter by start date (YYYY-MM-DD)
- `end_date` (optional): Filter by end date (YYYY-MM-DD)
- `show_schedule` (optional): Filter by show_schedule (true/false)

**Response:**
```json
{
    "success": true,
    "message": "ดึงข้อมูลทริปสำเร็จ",
    "data": [
        {
            "id": 1,
            "name": "ทริปเกาะสมุย",
            "description": "ทริปเที่ยวเกาะสมุย 3 วัน 2 คืน",
            "location": "เกาะสมุย",
            "cover_image": "http://127.0.0.1:8000/storage/trips/cover.jpg",
            "sample_images": [
                "http://127.0.0.1:8000/storage/trips/samples/sample1.jpg",
                "http://127.0.0.1:8000/storage/trips/samples/sample2.jpg"
            ],
            "show_itinerary": true,
            "show_total_cost": true,
            "show_personal_items": true,
            "show_rental_equipment": true,
            "show_schedule": true,
            "schedules_count": 3,
            "next_departure": "2025-10-01",
            "price_range": {
                "min": 2500,
                "max": 3500,
                "formatted": "฿2,500 - ฿3,500"
            },
            "created_at": "2025-09-08T10:00:00.000000Z",
            "updated_at": "2025-09-08T10:00:00.000000Z"
        }
    ],
    "meta": {
        "total": 1,
        "filters_applied": {
            "location": "เกาะสมุย"
        }
    }
}
```

### 2. Search Trips (Advanced)
```http
GET /api/trips/search
```

**Query Parameters:**
- `search` (optional): Search by trip name, description, or location
- `location` (optional): Filter by location
- `min_price` (optional): Minimum price filter
- `max_price` (optional): Maximum price filter
- `min_days` (optional): Minimum duration in days
- `max_days` (optional): Maximum duration in days
- `month` (optional): Filter by month (1-12)
- `year` (optional): Filter by year
- `start_date` (optional): Filter by start date (YYYY-MM-DD)
- `end_date` (optional): Filter by end date (YYYY-MM-DD)
- `show_schedule` (optional): Filter by show_schedule (true/false)
- `sort_by` (optional): Sort by field (created_at, name, location)
- `sort_order` (optional): Sort order (asc, desc)
- `per_page` (optional): Items per page (max 50, default 15)

**Response:**
```json
{
    "success": true,
    "message": "ค้นหาทริปสำเร็จ",
    "data": [
        {
            "id": 1,
            "name": "ทริปเกาะสมุย",
            "description": "ทริปเที่ยวเกาะสมุย 3 วัน 2 คืน",
            "location": "เกาะสมุย",
            "cover_image": "http://127.0.0.1:8000/storage/trips/cover.jpg",
            "sample_images": [
                "http://127.0.0.1:8000/storage/trips/samples/sample1.jpg"
            ],
            "show_itinerary": true,
            "show_total_cost": true,
            "show_personal_items": true,
            "show_rental_equipment": true,
            "show_schedule": true,
            "schedules_count": 3,
            "next_departure": "2025-10-01",
            "price_range": {
                "min": 2500,
                "max": 3500,
                "formatted": "฿2,500 - ฿3,500"
            },
            "duration_range": {
                "min": 3,
                "max": 5,
                "formatted": "3 - 5 วัน"
            },
            "created_at": "2025-09-08T10:00:00.000000Z",
            "updated_at": "2025-09-08T10:00:00.000000Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 2,
        "per_page": 15,
        "total": 25,
        "filters_applied": {
            "search": "เกาะ",
            "min_price": 2000,
            "max_price": 5000,
            "month": 10
        }
    }
}
```

### 3. Get Available Months
```http
GET /api/trips/available-months
```

**Query Parameters:**
- `year` (optional): Filter by specific year (e.g., 2025)

**Response (without year filter):**
```json
{
    "success": true,
    "message": "ดึงข้อมูลเดือนที่มีทริปสำเร็จ",
    "data": {
        "2025": {
            "year": 2025,
            "months": [
                {
                    "month": 10,
                    "month_name_thai": "ตุลาคม",
                    "month_name_english": "October",
                    "month_name_short_en": "Oct",
                    "count": 5
                },
                {
                    "month": 11,
                    "month_name_thai": "พฤศจิกายน",
                    "month_name_english": "November",
                    "month_name_short_en": "Nov",
                    "count": 3
                },
                {
                    "month": 12,
                    "month_name_thai": "ธันวาคม",
                    "month_name_english": "December",
                    "month_name_short_en": "Dec",
                    "count": 8
                }
            ]
        },
        "2026": {
            "year": 2026,
            "months": [
                {
                    "month": 1,
                    "month_name_thai": "มกราคม",
                    "month_name_english": "January",
                    "month_name_short_en": "Jan",
                    "count": 4
                }
            ]
        }
    },
    "meta": {
        "year_filter": null,
        "total_months": 4,
        "available_years": [2025, 2026]
    }
}
```

**Response (with year filter):**
```http
GET /api/trips/available-months?year=2025
```

```json
{
    "success": true,
    "message": "ดึงข้อมูลเดือนที่มีทริปสำเร็จ",
    "data": [
        {
            "month": 10,
            "year": 2025,
            "month_name_thai": "ตุลาคม",
            "month_name_english": "October",
            "month_name_short_en": "Oct",
            "count": 5,
            "formatted_date": "October 2025"
        },
        {
            "month": 11,
            "year": 2025,
            "month_name_thai": "พฤศจิกายน",
            "month_name_english": "November",
            "month_name_short_en": "Nov",
            "count": 3,
            "formatted_date": "November 2025"
        },
        {
            "month": 12,
            "year": 2025,
            "month_name_thai": "ธันวาคม",
            "month_name_english": "December",
            "month_name_short_en": "Dec",
            "count": 8,
            "formatted_date": "December 2025"
        }
    ],
    "meta": {
        "year_filter": 2025,
        "total_months": 3,
        "available_years": [2025, 2026]
    }
}
```

### 4. Get Price Range Statistics
```http
GET /api/trips/price-range
```

**Response:**
```json
{
    "success": true,
    "message": "ดึงข้อมูลช่วงราคาสำเร็จ",
    "data": {
        "min_price": 1500,
        "max_price": 15000,
        "avg_price": 4500.50
    }
}
```

### 5. Get Trip Detail
```http
GET /api/trips/{id}
```

**Response:**
```json
{
    "success": true,
    "message": "ดึงข้อมูลทริปรายละเอียดสำเร็จ",
    "data": {
        "id": 1,
        "name": "ทริปเกาะสมุย",
        "description": "ทริปเที่ยวเกาะสมุย 3 วัน 2 คืน",
        "location": "เกาะสมุย",
        "cover_image": "http://127.0.0.1:8000/storage/trips/cover.jpg",
        "sample_images": [
            "http://127.0.0.1:8000/storage/trips/samples/sample1.jpg"
        ],
        "show_itinerary": true,
        "show_total_cost": true,
        "show_personal_items": true,
        "show_rental_equipment": true,
        "show_schedule": true,
        "schedules": [
            {
                "id": 1,
                "departure_date": "2025-10-01",
                "return_date": "2025-10-03",
                "departure_date_thai": "1 ตุลาคม 2568",
                "return_date_thai": "3 ตุลาคม 2568",
                "duration": "3 วัน 2 คืน",
                "max_participants": 20,
                "price": 2500,
                "is_active": true,
                "created_at": "2025-09-08T10:00:00.000000Z",
                "updated_at": "2025-09-08T10:00:00.000000Z"
            }
        ],
        "price_range": {
            "min": 2500,
            "max": 3500,
            "formatted": "฿2,500 - ฿3,500"
        },
        "created_at": "2025-09-08T10:00:00.000000Z",
        "updated_at": "2025-09-08T10:00:00.000000Z"
    }
}
```

### 3. Get Trip Schedules
```http
GET /api/trips/{id}/schedules
```

**Response:**
```json
{
    "success": true,
    "message": "ดึงข้อมูลรอบการเดินทางสำเร็จ",
    "data": [
        {
            "id": 1,
            "trip_id": 1,
            "departure_date": "2025-10-01",
            "return_date": "2025-10-03",
            "departure_date_thai": "1 ตุลาคม 2568",
            "return_date_thai": "3 ตุลาคม 2568",
            "duration": "3 วัน 2 คืน",
            "max_participants": 20,
            "price": 2500,
            "is_active": true,
            "created_at": "2025-09-08T10:00:00.000000Z",
            "updated_at": "2025-09-08T10:00:00.000000Z"
        }
    ],
    "meta": {
        "trip_id": 1,
        "trip_name": "ทริปเกาะสมุย",
        "total_schedules": 1
    }
}
```

### 4. Get Specific Schedule
```http
GET /api/trips/{tripId}/schedules/{scheduleId}
```

### 5. Get Upcoming Trips
```http
GET /api/trips/upcoming
```

**Query Parameters:**
- `limit` (optional): Number of trips to return (default: 5)
- `days_ahead` (optional): Days ahead to look for trips (default: 30)

---

## Health Check
```http
GET /api/health
```

**Response:**
```json
{
    "status": "ok",
    "timestamp": "2025-09-08T10:00:00.000000Z",
    "version": "1.0.0"
}
```

---

## Error Responses

### 404 Not Found
```json
{
    "success": false,
    "message": "ไม่พบทริปที่ระบุ",
    "data": null
}
```

### 500 Internal Server Error
```json
{
    "success": false,
    "message": "เกิดข้อผิดพลาดในการดึงข้อมูลทริป",
    "error": "Error message details"
}
```

---

## Usage Examples

### JavaScript/Fetch
```javascript
// Get all trips
fetch('http://127.0.0.1:8000/api/trips')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Trips:', data.data);
        } else {
            console.error('Error:', data.message);
        }
    });

// Get trip detail
fetch('http://127.0.0.1:8000/api/trips/1')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Trip detail:', data.data);
        }
    });

// Get web config
fetch('http://127.0.0.1:8000/api/web-config')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Web config:', data.data);
        }
    });
```

### cURL
```bash
# Get all trips
curl -X GET "http://127.0.0.1:8000/api/trips"

# Get trip detail
curl -X GET "http://127.0.0.1:8000/api/trips/1"

# Get web config
curl -X GET "http://127.0.0.1:8000/api/web-config"
```
