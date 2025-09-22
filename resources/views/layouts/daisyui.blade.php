<!DOCTYPE html>
<html lang="th" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    
    <style>
        /* Custom DaisyUI-like styles */
        .drawer {
            position: relative;
        }
        
        .drawer-toggle {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        
        .drawer-content {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .drawer-side {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16rem;
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            overflow-y: auto;
        }
        
        .drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }
        
        .drawer-toggle:checked ~ .drawer-side {
            transform: translateX(0);
        }
        
        .drawer-toggle:checked ~ .drawer-overlay {
            opacity: 1;
            visibility: visible;
        }
        
        @media (min-width: 1024px) {
            .drawer-side {
                transform: translateX(0);
                position: relative;
                height: auto;
                width: 16rem;
            }
            
            .drawer-content {
                margin-left: 0;
            }
            
            .drawer-overlay {
                display: none;
            }
        }
        
        /* Mobile specific styles */
        @media (max-width: 1023px) {
            .drawer-content {
                margin-left: 0;
            }
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1rem;
            position: relative;
            z-index: 1000;
        }
        
        /* Hamburger menu button */
        .btn-square {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }
        
        .btn-square:hover {
            background-color: #f3f4f6;
            transform: scale(1.05);
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        .btn-ghost {
            background-color: transparent;
            color: #6b7280;
        }
        
        .btn-ghost:hover {
            background-color: #f3f4f6;
        }
        
        .btn-square {
            width: 2.5rem;
            height: 2.5rem;
            padding: 0;
        }
        
        .dropdown {
            position: relative;
        }
        
        .dropdown-content {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            padding: 0.5rem;
            min-width: 12rem;
            z-index: 50;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s;
        }
        
        .dropdown:hover .dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .menu li a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #6b7280;
            text-decoration: none;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }
        
        .menu li a:hover {
            background-color: #f3f4f6;
            color: #374151;
        }
        
        .menu li a.active {
            background-color: #3b82f6;
            color: white;
        }
        
        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-color: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 1rem 0;
        }
    </style>
</head>
<body class="bg-base-200">
    <div class="drawer lg:drawer-open">
        <!-- Drawer Toggle -->
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        
        <!-- Main Content -->
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <div class="navbar bg-base-100 shadow-lg sticky top-0" style="z-index: 1000;">
                <div class="flex-none lg:hidden">
                    <label for="drawer-toggle" class="btn btn-square btn-ghost">
                        <i class="fas fa-bars text-xl"></i>
                    </label>
                </div>
                
                <div class="flex-1">
                    <h1 class="text-xl font-bold text-base-content">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="flex-none">
                    @auth
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                                <div class="w-8 h-8 bg-primary text-primary-content rounded-full flex items-center justify-center">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            </div>
                            <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                                <li>
                                    <div class="px-4 py-2 text-sm">
                                        <div class="font-semibold text-base-content">{{ auth()->user()->name }}</div>
                                        <div class="text-base-content/70">{{ auth()->user()->getRoleDisplayName() }}</div>
                                    </div>
                                </li>
                                <li><hr class="my-2"></li>
                                <li><a class="text-base-content"><i class="fas fa-user me-2"></i>โปรไฟล์</a></li>
                                @if(auth()->user()->isSuperAdmin())
                                    <li><a class="text-base-content"><i class="fas fa-cog me-2"></i>ตั้งค่า</a></li>
                                @endif
                                <li><hr class="my-2"></li>
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-error">
                                        <i class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @endauth
                </div>
            </div>
            
            <!-- Page Content -->
            <main class="flex-1 p-4">
                @yield('content')
            </main>
        </div>
        
        <!-- Sidebar -->
        <div class="drawer-side">
            <label for="drawer-toggle" class="drawer-overlay"></label>
            <aside class="min-h-full w-64 bg-base-100">
                <!-- Sidebar Header -->
                <div class="p-4 border-b border-base-300">
                    <h2 class="text-2xl font-bold text-primary mb-2">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Admin Panel
                    </h2>
                    @auth
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary text-primary-content rounded-full flex items-center justify-center">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-sm text-base-content">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-base-content/70">{{ auth()->user()->getRoleDisplayName() }}</div>
                            </div>
                        </div>
                    @endauth
                </div>
                
                <!-- Sidebar Menu -->
                <ul class="menu p-4 w-full">
                    <li>
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    @auth
                        @if(auth()->user()->isSuperAdmin())
                            <li>
                                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    <i class="fas fa-users"></i>
                                    จัดการผู้ใช้
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('webconfig.index') }}" class="{{ request()->routeIs('webconfig.*') ? 'active' : '' }}">
                                    <i class="fas fa-cog"></i>
                                    การตั้งค่าเว็บไซต์
                                </a>
                            </li>
                        @endif
                        
                        @if(auth()->user()->hasAdminPrivileges())
                            <li>
                                <a href="{{ route('trips.index') }}" class="{{ request()->routeIs('trips.index') || request()->routeIs('trips.create') || request()->routeIs('trips.edit') || request()->routeIs('trips.show') ? 'active' : '' }}">
                                    <i class="fas fa-map-marked-alt"></i>
                                    การจัดการทริป
                                </a>
                            </li>
                            <li>
                                <details class="{{ request()->routeIs('booking-terms.*') || request()->routeIs('cancellation-policies.*') ? 'open' : '' }}">
                                    <summary class="{{ request()->routeIs('booking-terms.*') || request()->routeIs('cancellation-policies.*') ? 'active' : '' }}">
                                        <i class="fas fa-cogs"></i>
                                        การจัดการเงื่อนไข
                                    </summary>
                                    <ul>
                                        <li>
                                            <a href="{{ route('booking-terms.index') }}" class="{{ request()->routeIs('booking-terms.*') ? 'active' : '' }}">
                                                <i class="fas fa-clipboard-list"></i>
                                                การจอง
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('cancellation-policies.index') }}" class="{{ request()->routeIs('cancellation-policies.*') ? 'active' : '' }}">
                                                <i class="fas fa-file-contract"></i>
                                                ยกเลิกทริป
                                            </a>
                                        </li>
                                    </ul>
                                </details>
                            </li>
                            <li>
                                <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-check"></i>
                                    จัดการการจอง
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('trips.calendar') }}" class="{{ request()->routeIs('trips.calendar') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt"></i>
                                    ปฏิทินรายการทริป
                                </a>
                            </li>
                            <!-- <li>
                                <a href="#" class="{{ request()->routeIs('tours.*') ? 'active' : '' }}">
                                    <i class="fas fa-map-marked-alt"></i>ย
                                    จัดการทัวร์
                                </a>
                            </li> -->
                        @endif
                        
                        @if(auth()->user()->isReport() || auth()->user()->hasAdminPrivileges())
                            <!-- <li>
                                <a href="#" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                    <i class="fas fa-chart-bar"></i>
                                    รายงาน
                                </a>
                            </li> -->
                        @endif
                        
                        @if(auth()->user()->isSuperAdmin())
                            <!-- <li>
                                <a href="#" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                    <i class="fas fa-cog"></i>
                                    ตั้งค่าระบบ
                                </a>
                            </li> -->
                        @endif
                    @endauth
                    
                    <!-- Divider -->
                    <div class="divider"></div>
                    
                    @auth
                        <li>
                            <a href="{{ route('logout') }}" class="text-error">
                                <i class="fas fa-sign-out-alt"></i>
                                ออกจากระบบ
                            </a>
                        </li>
                    @endauth
                </ul>
            </aside>
        </div>
    </div>
    
    @stack('scripts')
    
    <!-- Cropper.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    
    <!-- Slide Menu JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const drawerToggle = document.getElementById('drawer-toggle');
            const drawerOverlay = document.querySelector('.drawer-overlay');
            
            // Close drawer when clicking overlay
            if (drawerOverlay) {
                drawerOverlay.addEventListener('click', function() {
                    drawerToggle.checked = false;
                });
            }
            
            // Close drawer when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && drawerToggle.checked) {
                    drawerToggle.checked = false;
                }
            });
            
            // Handle responsive behavior
            function handleResize() {
                if (window.innerWidth >= 1024) {
                    drawerToggle.checked = false;
                }
            }
            
            window.addEventListener('resize', handleResize);
            handleResize(); // Initial call
        });
    </script>
</body>
</html>