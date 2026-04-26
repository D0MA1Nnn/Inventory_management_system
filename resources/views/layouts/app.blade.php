<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>JUSTRIX - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }
        .sidebar.closed {
            transform: translateX(-100%);
        }
        .sidebar-overlay {
            transition: opacity 0.3s ease-in-out;
        }
        .sidebar-item {
            transition: all 0.2s ease;
            position: relative;
        }
        .sidebar-item:hover {
            background: linear-gradient(90deg, #1e293b 0%, #0f172a 100%);
            padding-left: 1.75rem;
        }
        .sidebar-item.active {
            background: linear-gradient(90deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #60a5fa;
        }
        .user-dropdown {
            transition: all 0.2s ease;
        }
        .dropdown-menu {
            transition: all 0.2s ease;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
        }
        .user-dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0 !important;
            }
        }
        .notification-badge {
            animation: pulse 0.5s ease-in-out;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        .notification-dropdown {
            display: inline-block;
        }
        .notification-dropdown .dropdown-panel {
            transition: opacity 0.2s ease, visibility 0.2s ease;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .notification-dropdown:hover .dropdown-panel {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        .summary-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-left-width: 4px;
            border-radius: 0.75rem;
            padding: 1rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }
        .section-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }
        .section-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: #ffffff;
        }
        .tab-surface {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            padding: 0.75rem;
        }
        .tab-btn {
            position: relative;
            color: #4b5563;
            background: transparent;
        }
        .tab-btn:hover {
            color: #111827;
            background: #ffffff;
        }
        .tab-btn.active {
            color: #ffffff;
            background: #1a1d2e;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.14);
        }
        .tab-badge {
            position: absolute;
            top: -0.45rem;
            right: -0.45rem;
            min-width: 1.25rem;
            height: 1.25rem;
            padding: 0 0.35rem;
            border-radius: 9999px;
            background: #f59e0b;
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 700;
            line-height: 1.25rem;
            text-align: center;
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.35);
            pointer-events: none;
        }
        .tab-badge.hidden {
            display: none;
        }
        .filter-surface {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            padding: 0.75rem;
        }

        /* Sidebar group styles */
        .nav-group {
            margin-bottom: 1.5rem;
        }
        .nav-group-title {
            padding: 0.5rem 1.75rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
        }

        /* Scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: #1f2937;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Mobile Menu Button -->
    <div class="fixed top-4 left-4 z-50 md:hidden">
        <button id="menuToggle" class="p-2 bg-[#1A1D2E] text-white rounded-xl shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden sidebar-overlay"></div>

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="sidebar fixed md:relative w-72 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex flex-col shadow-2xl overflow-y-auto z-50 h-full transform -translate-x-full md:translate-x-0 transition-transform duration-300">
            
            <!-- Logo Section -->
            <div class="flex items-center justify-between gap-3 px-6 py-6 border-b border-gray-700">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('pictures/LOGO.png') }}" alt="JUSTRIX" class="w-12 h-12 rounded-xl object-cover shadow-lg">
                    <div>
                        <div class="text-xl font-bold tracking-wide">JUSTRIX</div>
                        <div class="text-xs text-gray-400 mt-0.5">Admin Dashboard</div>
                    </div>
                </div>
                <button id="closeSidebar" class="md:hidden text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Navigation Menu -->
            <nav class="flex-1 py-6">
                @php
                    $userRole = auth()->user()->role ?? '';
                @endphp

                <!-- MAIN MENU -->
                <div class="nav-group">
                    <div class="nav-group-title">Main Menu</div>
                    
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center gap-3 px-6 py-3 mx-2 rounded-lg text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>

                    <!-- Products -->
                    <a href="{{ route('products-ui') }}" class="sidebar-item flex items-center gap-3 px-6 py-3 mx-2 rounded-lg text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="text-sm font-medium">Products</span>
                    </a>

                    <!-- Sales -->
                    <a href="{{ route('sales-ui') }}" class="sidebar-item flex items-center gap-3 px-6 py-3 mx-2 rounded-lg text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">Sales</span>
                    </a>
                </div>

                <!-- INVENTORY MANAGEMENT -->
                @if(in_array($userRole, ['admin', 'manager']))
                <div class="nav-group">
                    <div class="nav-group-title">Inventory Management</div>
                    
                    <!-- Categories -->
                    <a href="{{ route('categories-ui') }}" class="sidebar-item flex items-center gap-3 px-6 py-3 mx-2 rounded-lg text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="text-sm font-medium">Categories</span>
                    </a>

                    <!-- Suppliers -->
                    <a href="{{ route('suppliers-ui') }}" class="sidebar-item flex items-center gap-3 px-6 py-3 mx-2 rounded-lg text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">Suppliers</span>
                    </a>

                    <!-- Purchases -->
                    <a href="{{ route('purchases-ui') }}" class="sidebar-item flex items-center gap-3 px-6 py-3 mx-2 rounded-lg text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"></path>
                        </svg>
                        <span class="text-sm font-medium">Purchases</span>
                    </a>
                </div>
                @endif

                <!-- PEOPLE MANAGEMENT -->
                @if($userRole === 'admin')
                <div class="nav-group">
                    <div class="nav-group-title">People Management</div>
                    
                    <!-- Customers -->
                    <a href="{{ route('customers-ui') }}" class="sidebar-item flex items-center gap-3 px-6 py-3 mx-2 rounded-lg text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">Customers</span>
                    </a>

                    <!-- Staff -->
                    <a href="{{ route('staff-ui') }}" class="sidebar-item flex items-center gap-3 px-6 py-3 mx-2 rounded-lg text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v4m0 4h.01M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                        </svg>
                        <span class="text-sm font-medium">Staff</span>
                    </a>
                </div>

                <!-- SYSTEM -->
                <div class="nav-group">
                    <div class="nav-group-title">System</div>
                    
                    <!-- Activity Logs -->
                    <a href="{{ route('admin.logs.index') }}" class="sidebar-item flex items-center gap-3 px-6 py-3 mx-2 rounded-lg text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm font-medium">Activity Logs</span>
                    </a>
                </div>
                @endif
            </nav>
            
            <!-- User Section at Bottom -->
            <div class="border-t border-gray-700 p-4">
                <div class="user-dropdown relative">
                    <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-gray-800 cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ substr(auth()->user()->name ?? 'G', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Guest' }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="dropdown-menu absolute bottom-full left-0 right-0 mb-2 bg-gray-800 rounded-lg overflow-hidden shadow-xl">
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-gray-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="text-sm">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto main-content w-full">
            <!-- Top Header -->
            <div class="bg-white shadow-sm sticky top-0 z-10">
                <div class="px-4 sm:px-8 py-4 flex items-center justify-between">
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 truncate">@yield('title', 'Dashboard')</h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5 truncate">Welcome back, {{ auth()->user()->name ?? 'Guest' }}</p>
                    </div>
                    
                    <!-- Notifications -->
                    <div class="flex items-center gap-2 sm:gap-4">
                        <!-- Low Stock Notification -->
                        <div class="relative notification-dropdown">
                            <div class="relative">
                                <button class="relative p-2 text-gray-500 hover:text-gray-700 transition rounded-lg hover:bg-gray-100">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <span id="lowStockBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 hidden">0</span>
                                </button>
                            </div>
                            <div id="lowStockDropdown" class="dropdown-panel absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg z-50 border border-gray-100">
                                <div class="p-3 border-b bg-gray-50 rounded-t-xl">
                                    <h3 class="font-semibold text-gray-800">Low Stock Alerts</h3>
                                    <p class="text-xs text-gray-500">Products with stock below 10 units</p>
                                </div>
                                <div id="lowStockList" class="max-h-80 overflow-y-auto">
                                    <div class="p-4 text-center text-gray-500">Loading...</div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Purchases Notification -->
                        <div class="relative notification-dropdown">
                            <div class="relative">
                                <button class="relative p-2 text-gray-500 hover:text-gray-700 transition rounded-lg hover:bg-gray-100">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"></path>
                                    </svg>
                                    <span id="pendingPurchasesBadge" class="absolute -top-1 -right-1 bg-amber-500 text-white text-xs rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 hidden">0</span>
                                </button>
                            </div>
                            <div id="pendingPurchasesDropdown" class="dropdown-panel absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg z-50 border border-gray-100">
                                <div class="p-3 border-b bg-gray-50 rounded-t-xl">
                                    <h3 class="font-semibold text-gray-800">Pending Purchases</h3>
                                    <p class="text-xs text-gray-500">Products waiting to be received</p>
                                </div>
                                <div id="pendingPurchasesList" class="max-h-80 overflow-y-auto">
                                    <div class="p-4 text-center text-gray-500">Loading...</div>
                                </div>
                            </div>
                        </div>

                        <!-- Today's Sales Notification -->
                        <div class="relative notification-dropdown">
                            <div class="relative">
                                <button class="relative p-2 text-gray-500 hover:text-gray-700 transition rounded-lg hover:bg-gray-100">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span id="todaySalesBadge" class="absolute -top-1 -right-1 bg-green-500 text-white text-xs rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 hidden">0</span>
                                </button>
                            </div>
                            <div id="todaySalesDropdown" class="dropdown-panel absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg z-50 border border-gray-100">
                                <div class="p-3 border-b bg-gray-50 rounded-t-xl">
                                    <h3 class="font-semibold text-gray-800">Today's Sales</h3>
                                    <p class="text-xs text-gray-500">Recent transactions today</p>
                                </div>
                                <div id="todaySalesList" class="max-h-80 overflow-y-auto">
                                    <div class="p-4 text-center text-gray-500">Loading...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page Content -->
            <div class="p-4 sm:p-6 md:p-8">
                @yield('content')
            </div>
        </main>
    </div>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
    
    <script>
        let lastNotificationHash = '';

        function playNotificationSound() {
            const audio = new Audio('https://www.soundjay.com/buttons/sounds/button-3.mp3');
            audio.play().catch(() => {});
        }

        // Mobile sidebar toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebarFunc() {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        if (menuToggle) {
            menuToggle.addEventListener('click', openSidebar);
        }
        if (closeSidebar) {
            closeSidebar.addEventListener('click', closeSidebarFunc);
        }
        if (overlay) {
            overlay.addEventListener('click', closeSidebarFunc);
        }
        
        // Set active link based on current URL
        function setActiveLink() {
            const currentUrl = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar-item');
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if (href === currentUrl) {
                    link.classList.add('active');
                }
            });
        }
        
        document.addEventListener('DOMContentLoaded', setActiveLink);
        window.addEventListener('popstate', setActiveLink);
        
        // Close sidebar on window resize (if screen becomes desktop)
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                closeSidebarFunc();
            }
        });
        
        // Load notifications data
        async function loadNotifications() {
            try {
                // ================= LOW STOCK =================
                const productsRes = await fetch('/api/products');
                let products = await productsRes.json();
                if (products.data) products = products.data;

                const lowStock = products.filter(p => p.quantity > 0 && p.quantity < 10);

                document.getElementById('lowStockBadge').innerText = lowStock.length;
                document.getElementById('lowStockBadge').classList.toggle('hidden', lowStock.length === 0);

                document.getElementById('lowStockList').innerHTML =
                    lowStock.length > 0
                    ? lowStock.map(p => `
                        <div class="p-3 border-b">
                            <p class="text-sm font-medium">${escapeHtml(p.name)}</p>
                            <p class="text-xs text-gray-500">Stock: ${p.quantity}</p>
                        </div>
                    `).join('')
                    : '<div class="p-4 text-center text-gray-500">No low stock</div>';

                // ================= PENDING PURCHASES =================
                const purchaseRes = await fetch('/api/purchase/coming');

                let purchases = [];
                if (purchaseRes.ok) {
                    purchases = await purchaseRes.json();
                    purchases = Array.isArray(purchases) ? purchases : Object.values(purchases);
                }

                document.getElementById('pendingPurchasesBadge').innerText = purchases.length;
                document.getElementById('pendingPurchasesBadge')
                    .classList.toggle('hidden', purchases.length === 0);

                document.getElementById('pendingPurchasesList').innerHTML =
                    purchases.length > 0
                    ? purchases.slice(0,5).map(p => `
                        <div class="p-3 border-b">
                            <p class="text-sm font-medium">${escapeHtml(p.product_name || p.product?.name || 'Unknown product')}</p>
                            <p class="text-xs text-gray-500">
                                ${escapeHtml(p.supplier_name || p.supplier?.name || 'Unknown supplier')} - Qty: ${p.quantity}
                            </p>
                        </div>
                    `).join('')
                    : '<div class="p-4 text-center text-gray-500">No pending purchases</div>';

                // ================= TODAY SALES =================
                const salesRes = await fetch('/api/sales');
                const sales = await salesRes.json();

                const today = new Date().toDateString();
                const todaySales = sales.filter(s => new Date(s.sold_at).toDateString() === today);

                document.getElementById('todaySalesBadge').innerText = todaySales.length;
                document.getElementById('todaySalesBadge')
                    .classList.toggle('hidden', todaySales.length === 0);

                document.getElementById('todaySalesList').innerHTML =
                    todaySales.length > 0
                    ? todaySales.slice(0,5).map(s => `
                        <div class="p-3 border-b">
                            <p class="text-sm font-medium">${escapeHtml(s.product?.name)}</p>
                            <p class="text-xs text-gray-500">Qty: ${s.quantity}</p>
                        </div>
                    `).join('')
                    : '<div class="p-4 text-center text-gray-500">No sales today</div>';

                const currentHash = JSON.stringify({
                    lowStockCount: lowStock.length,
                    purchasesCount: purchases.length,
                    salesCount: todaySales.length
                });

                if (lastNotificationHash && lastNotificationHash !== currentHash) {
                    playNotificationSound();
                }

                lastNotificationHash = currentHash;

            } catch (error) {
                console.error('Notification error:', error);
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadNotifications();
            setInterval(loadNotifications, 5000); // every 5 seconds
        });
    </script>
</body>
</html>