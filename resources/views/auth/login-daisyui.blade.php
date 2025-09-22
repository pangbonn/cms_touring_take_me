<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Admin Panel</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .floating-animation {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .btn-hover {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .btn-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-hover:hover::before {
            left: 100%;
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .slide-in {
            animation: slideIn 0.8s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center p-4">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white rounded-full mix-blend-multiply filter blur-xl opacity-20 floating-animation"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 floating-animation" style="animation-delay: 1s;"></div>
        <div class="absolute top-40 left-40 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 floating-animation" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-md">
        <!-- Main Login Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 slide-in">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="relative inline-block mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-shield-alt text-3xl text-white"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-green-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-xs text-white"></i>
                    </div>
                </div>
                
                <h1 class="text-3xl font-bold gradient-text mb-2">
                    Welcome Back
                </h1>
                <p class="text-gray-600 text-sm">
                    เข้าสู่ระบบจัดการของคุณ
                </p>
            </div>
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                        <div>
                            <h4 class="text-red-800 font-medium">เกิดข้อผิดพลาด</h4>
                            <ul class="text-red-600 text-sm mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Email Input -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">อีเมล</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="กรุณากรอกอีเมลของคุณ" 
                               class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror" 
                               required 
                               autocomplete="email" 
                               autofocus>
                    </div>
                </div>
                
                <!-- Password Input -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">รหัสผ่าน</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               name="password" 
                               placeholder="กรุณากรอกรหัสผ่านของคุณ" 
                               class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror" 
                               required 
                               autocomplete="current-password">
                    </div>
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="remember" 
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                               {{ old('remember') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-600">จดจำการเข้าสู่ระบบ</span>
                    </label>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                        ลืมรหัสผ่าน?
                    </a>
                </div>
                
                <!-- Login Button -->
                <button type="submit" class="btn-hover w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    เข้าสู่ระบบ
                </button>
            </form>
        </div>
        
        <!-- Demo Credentials Card -->
        <div class="mt-6 bg-white rounded-2xl shadow-lg p-6 slide-in" style="animation-delay: 0.2s;">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">ข้อมูลทดสอบ</h3>
            </div>
            <div class="space-y-3">
                <!-- Super Admin -->
                <div class="p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-red-700">Super Admin</span>
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">สูงสุด</span>
                    </div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs text-gray-600">อีเมล:</span>
                        <code class="text-xs bg-white px-2 py-1 rounded border">superadmin@example.com</code>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-600">รหัสผ่าน:</span>
                        <code class="text-xs bg-white px-2 py-1 rounded border">password123</code>
                    </div>
                </div>

                <!-- Admin -->
                <div class="p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-blue-700">Admin</span>
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">ผู้ดูแล</span>
                    </div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs text-gray-600">อีเมล:</span>
                        <code class="text-xs bg-white px-2 py-1 rounded border">admin@example.com</code>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-600">รหัสผ่าน:</span>
                        <code class="text-xs bg-white px-2 py-1 rounded border">password123</code>
                    </div>
                </div>

                <!-- Report -->
                <div class="p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-green-700">Report</span>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">รายงาน</span>
                    </div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs text-gray-600">อีเมล:</span>
                        <code class="text-xs bg-white px-2 py-1 rounded border">report@example.com</code>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-600">รหัสผ่าน:</span>
                        <code class="text-xs bg-white px-2 py-1 rounded border">password123</code>
                    </div>
                </div>
            </div>
            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                <p class="text-xs text-blue-800 text-center">
                    <i class="fas fa-lightbulb mr-1"></i>
                    คลิกเพื่อคัดลอกข้อมูลเข้าสู่ระบบ
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-sm text-white/80">
                © 2024 Admin Panel. All rights reserved.
            </p>
        </div>
    </div>
    
    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add click to copy functionality for demo credentials
            const emailCode = document.querySelector('code');
            const passwordCode = document.querySelectorAll('code')[1];
            
            if (emailCode && passwordCode) {
                emailCode.addEventListener('click', function() {
                    navigator.clipboard.writeText('superadmin@example.com');
                    this.style.backgroundColor = '#10b981';
                    this.style.color = 'white';
                    setTimeout(() => {
                        this.style.backgroundColor = 'white';
                        this.style.color = 'black';
                    }, 1000);
                });
                
                passwordCode.addEventListener('click', function() {
                    navigator.clipboard.writeText('password123');
                    this.style.backgroundColor = '#10b981';
                    this.style.color = 'white';
                    setTimeout(() => {
                        this.style.backgroundColor = 'white';
                        this.style.color = 'black';
                    }, 1000);
                });
            }
        });
    </script>
</body>
</html>