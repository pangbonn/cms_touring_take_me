<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทดสอบฟอนต์ Sarabun</title>
    
    <!-- Google Fonts Sarabun -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }
        
        .font-test {
            font-family: 'Sarabun', sans-serif;
        }
        
        .font-weight-100 { font-weight: 100; }
        .font-weight-200 { font-weight: 200; }
        .font-weight-300 { font-weight: 300; }
        .font-weight-400 { font-weight: 400; }
        .font-weight-500 { font-weight: 500; }
        .font-weight-600 { font-weight: 600; }
        .font-weight-700 { font-weight: 700; }
        .font-weight-800 { font-weight: 800; }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold text-center mb-8 text-gray-800">ทดสอบฟอนต์ Sarabun</h1>
        
        <!-- ทดสอบน้ำหนักฟอนต์ -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">ทดสอบน้ำหนักฟอนต์ (Font Weight)</h2>
            <div class="space-y-2">
                <p class="font-weight-100 text-lg">น้ำหนัก 100 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="font-weight-200 text-lg">น้ำหนัก 200 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="font-weight-300 text-lg">น้ำหนัก 300 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="font-weight-400 text-lg">น้ำหนัก 400 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="font-weight-500 text-lg">น้ำหนัก 500 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="font-weight-600 text-lg">น้ำหนัก 600 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="font-weight-700 text-lg">น้ำหนัก 700 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="font-weight-800 text-lg">น้ำหนัก 800 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
            </div>
        </div>
        
        <!-- ทดสอบขนาดฟอนต์ -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">ทดสอบขนาดฟอนต์ (Font Size)</h2>
            <div class="space-y-2">
                <p class="text-xs">ขนาด xs - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="text-sm">ขนาด sm - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="text-base">ขนาด base - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="text-lg">ขนาด lg - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="text-xl">ขนาด xl - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="text-2xl">ขนาด 2xl - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="text-3xl">ขนาด 3xl - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="text-4xl">ขนาด 4xl - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
            </div>
        </div>
        
        <!-- ทดสอบตัวเอียง -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">ทดสอบตัวเอียง (Italic)</h2>
            <div class="space-y-2">
                <p class="text-lg italic font-weight-300">ตัวเอียง 300 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="text-lg italic font-weight-400">ตัวเอียง 400 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="text-lg italic font-weight-500">ตัวเอียง 500 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="text-lg italic font-weight-600">ตัวเอียง 600 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="text-lg italic font-weight-700">ตัวเอียง 700 - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
            </div>
        </div>
        
        <!-- ทดสอบข้อความยาว -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">ทดสอบข้อความยาว</h2>
            <div class="space-y-4">
                <p class="text-lg leading-relaxed">
                    ฟอนต์ Sarabun เป็นฟอนต์ไทยที่ออกแบบมาสำหรับการใช้งานทั่วไป มีความชัดเจนและอ่านง่าย เหมาะสำหรับการใช้งานในเอกสาร เว็บไซต์ และแอปพลิเคชันต่างๆ ฟอนต์นี้รองรับตัวอักษรไทยและอังกฤษได้อย่างสมบูรณ์
                </p>
                <p class="text-base leading-relaxed">
                    การใช้งานฟอนต์ Sarabun ในระบบ Laravel สามารถทำได้หลายวิธี เช่น การใช้ Google Fonts การดาวน์โหลดไฟล์ฟอนต์มาใช้ในเครื่อง หรือการกำหนดผ่าน CSS และ Tailwind CSS
                </p>
            </div>
        </div>
        
        <!-- ทดสอบ Tailwind Classes -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">ทดสอบ Tailwind Classes</h2>
            <div class="space-y-2">
                <p class="font-sarabun text-lg">font-sarabun - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="font-sans text-lg">font-sans - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="font-sarabun font-light text-lg">font-sarabun font-light - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="font-sarabun font-medium text-lg">font-sarabun font-medium - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="font-sarabun font-semibold text-lg">font-sarabun font-semibold - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
                <p class="font-sarabun font-bold text-lg">font-sarabun font-bold - สวัสดีครับ นี่คือการทดสอบฟอนต์ Sarabun</p>
            </div>
        </div>
        
        <!-- ข้อมูลฟอนต์ -->
        <div class="bg-blue-50 rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4 text-blue-800">ข้อมูลฟอนต์ Sarabun</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <h3 class="font-semibold text-blue-700 mb-2">ข้อมูลทั่วไป</h3>
                    <ul class="space-y-1 text-gray-700">
                        <li>• ชื่อ: Sarabun</li>
                        <li>• ประเภท: Sans-serif</li>
                        <li>• ภาษา: ไทย, อังกฤษ</li>
                        <li>• แหล่งที่มา: Google Fonts</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-blue-700 mb-2">น้ำหนักที่รองรับ</h3>
                    <ul class="space-y-1 text-gray-700">
                        <li>• 100 (Thin)</li>
                        <li>• 200 (Extra Light)</li>
                        <li>• 300 (Light)</li>
                        <li>• 400 (Regular)</li>
                        <li>• 500 (Medium)</li>
                        <li>• 600 (Semi Bold)</li>
                        <li>• 700 (Bold)</li>
                        <li>• 800 (Extra Bold)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
