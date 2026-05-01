<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>JUSTRIX - Premium Computer Parts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .product-card { animation: fadeIn 0.5s ease-out; }
        .category-active { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; }
        .filter-slider { -webkit-appearance: none; width: 100%; height: 6px; border-radius: 5px; background: #e5e7eb; outline: none; }
        .filter-slider::-webkit-slider-thumb { -webkit-appearance: none; width: 18px; height: 18px; border-radius: 50%; background: #2563eb; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.2); }
        .order-card { transition: all 0.2s ease; }
        .order-card:hover { transform: translateX(5px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .nav-active { color: #2563eb; border-bottom: 2px solid #2563eb; }
        .footer-link { transition: all 0.2s ease; }
        .footer-link:hover { color: #60a5fa; transform: translateX(5px); }
        .social-icon { transition: all 0.2s ease; }
        .social-icon:hover { transform: translateY(-3px); }
        
        /* Filter Sidebar - Mobile slide-out, desktop static */
        .filter-sidebar {
            position: fixed;
            left: -300px;
            top: 64px;
            bottom: 0;
            width: 280px;
            background: white;
            z-index: 190;
            overflow-y: auto;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: left 0.3s ease;
        }
        .filter-sidebar.open {
            left: 0;
        }
        .filter-overlay {
            position: fixed;
            top: 64px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 180;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .filter-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Desktop styles */
        @media (min-width: 768px) {
            .filter-sidebar {
                position: sticky;
                top: 80px;
                left: auto;
                width: 260px;
                height: fit-content;
                max-height: calc(100vh - 100px);
                transform: none;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                border-radius: 16px;
                background: white;
            }
            .filter-sidebar.open {
                left: auto;
            }
            .filter-overlay {
                display: none;
            }
        }
        
        /* Scroll to Top Button */
        .scroll-to-top {
            position: fixed;
            bottom: 80px;
            right: 16px;
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 90;
        }
        .scroll-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        @media (min-width: 768px) {
            .scroll-to-top {
                bottom: 24px;
                right: 24px;
                width: 48px;
                height: 48px;
            }
        }
        
        /* Placeholder image style */
        .no-image-placeholder {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Responsive adjustments */
        @media (max-width: 767px) {
            .filter-sidebar {
                padding: 16px;
            }
            .products-grid {
                gap: 12px;
            }
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Filter Overlay -->
    <div id="filterOverlay" class="filter-overlay" onclick="closeMobileFilter()"></div>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="scroll-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <!-- Customer Navbar -->
    <nav id="shopNavbar" class="bg-white shadow-md sticky top-0 z-[300]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 sm:py-4">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <img src="{{ asset('pictures/LOGO.png') }}" alt="JUSTRIX" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover shadow-md">
                    <div>
                        <span class="text-base sm:text-xl font-bold text-gray-800">JUSTRIX</span>
                        <p class="text-[10px] text-gray-500 hidden sm:block">Premium Computer Parts</p>
                    </div>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-4 lg:space-x-6">
                    <a href="#" onclick="showPage('home')" id="navHome" class="text-gray-700 hover:text-blue-600 transition font-medium nav-link pb-2">Home</a>
                    <a href="#" onclick="showPage('shop')" id="navShop" class="text-gray-700 hover:text-blue-600 transition font-medium nav-link pb-2">Shop</a>
                    <a href="#" onclick="showPage('orders')" id="navOrders" class="text-gray-700 hover:text-blue-600 transition font-medium nav-link pb-2 relative">
                        My Orders
                        <span id="ordersBadge" class="absolute -top-2 -right-6 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Mobile Filter Toggle -->
                    <button id="mobileFilterToggle" onclick="toggleMobileFilter()" class="hidden md:hidden p-1.5 sm:p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    
                    <!-- Cart Button -->
                    <button onclick="showPage('cart')" class="relative p-1.5 sm:p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"></path>
                        </svg>
                        <span id="cartCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] sm:text-xs rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center">0</span>
                    </button>
                    
                    <button onclick="showLogoutConfirm()" class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1.5 sm:py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition text-xs sm:text-sm font-semibold">
                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg z-50 md:hidden">
        <div class="flex justify-around py-2">
            <button onclick="showPage('home')" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition py-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-[10px] mt-0.5">Home</span>
            </button>
            <button onclick="showPage('shop')" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition py-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span class="text-[10px] mt-0.5">Shop</span>
            </button>
            <button onclick="showPage('orders')" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition relative py-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span class="text-[10px] mt-0.5">Orders</span>
                <span id="mobileOrdersBadge" class="absolute -top-1 -right-2 bg-blue-500 text-white text-[9px] rounded-full w-4 h-4 flex items-center justify-center hidden">0</span>
            </button>
            <button onclick="showPage('cart')" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition relative py-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"></path>
                </svg>
                <span class="text-[10px] mt-0.5">Cart</span>
                <span id="mobileCartBadge" class="absolute -top-1 -right-2 bg-red-500 text-white text-[9px] rounded-full w-4 h-4 flex items-center justify-center hidden">0</span>
            </button>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutConfirmModal" class="fixed inset-0 bg-black/50 z-50 items-center justify-center hidden" onclick="if(event.target===this)closeLogoutConfirm()">
        <div class="bg-white rounded-2xl w-[90%] max-w-sm mx-4 shadow-2xl">
            <div class="p-5 sm:p-6 text-center">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Confirm Logout</h3>
                <p class="text-gray-500 text-sm mb-5 sm:mb-6">Are you sure you want to logout from your account?</p>
                <div class="flex gap-3">
                    <button onclick="closeLogoutConfirm()" class="flex-1 py-2 sm:py-3 bg-gray-200 rounded-xl font-semibold hover:bg-gray-300 transition text-sm">Cancel</button>
                    <button onclick="confirmLogout()" class="flex-1 py-2 sm:py-3 bg-red-600 rounded-xl text-white font-semibold hover:bg-red-700 transition text-sm">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <!-- Page Content Container -->
    <div id="pageContainer" class="pb-16 md:pb-0">
        <!-- HOME PAGE -->
        <div id="homePage" class="page-content">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-10 sm:py-16 md:py-20">
                <div class="max-w-7xl mx-auto px-4 text-center">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-2 sm:mb-4 animate-fadeIn">Welcome to JUSTRIX, {{ Auth::user()->name }}!</h1>
                    <p class="text-sm sm:text-base md:text-lg text-blue-100 mb-5 sm:mb-8 px-2">Your Premier Destination for Premium Computer Parts</p>
                    <button onclick="showPage('shop')" class="px-5 sm:px-6 md:px-8 py-2 sm:py-3 bg-white text-blue-600 font-semibold rounded-xl hover:shadow-lg transition transform hover:scale-105 text-sm sm:text-base">Shop Now</button>
                </div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 py-8 sm:py-12 md:py-16">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center text-gray-800 mb-6 sm:mb-8 md:mb-12">Why Choose JUSTRIX?</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                    <div class="text-center p-4 sm:p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition group">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:bg-blue-200 transition">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg md:text-xl font-semibold mb-1 sm:mb-2">Genuine Products</h3>
                        <p class="text-gray-600 text-xs sm:text-sm">100% authentic computer parts from trusted manufacturers</p>
                    </div>
                    <div class="text-center p-4 sm:p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition group">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:bg-green-200 transition">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg md:text-xl font-semibold mb-1 sm:mb-2">Best Prices</h3>
                        <p class="text-gray-600 text-xs sm:text-sm">Competitive pricing and regular deals on components</p>
                    </div>
                    <div class="text-center p-4 sm:p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition group">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:bg-purple-200 transition">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg md:text-xl font-semibold mb-1 sm:mb-2">Fast Shipping</h3>
                        <p class="text-gray-600 text-xs sm:text-sm">Quick delivery and secure packaging nationwide</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 py-8 sm:py-12 md:py-16">
                <div class="max-w-7xl mx-auto px-4">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center text-gray-800 mb-6 sm:mb-8 md:mb-12">Popular Categories</h2>
                    <div id="homeCategories" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
                        <div class="text-center py-6 sm:py-8 text-gray-500 col-span-full">Loading categories...</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-10 sm:py-12 md:py-16">
                <div class="max-w-7xl mx-auto px-4 text-center">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold mb-2 sm:mb-4">Ready to Upgrade Your PC?</h2>
                    <p class="text-blue-100 mb-5 sm:mb-8 text-sm sm:text-base">Browse our collection of premium computer parts</p>
                    <button onclick="showPage('shop')" class="px-5 sm:px-6 md:px-8 py-2 sm:py-3 bg-white text-blue-600 font-semibold rounded-xl hover:shadow-lg transition transform hover:scale-105 text-sm sm:text-base">Start Shopping</button>
                </div>
            </div>
        </div>

        <!-- SHOP PAGE -->
        <div id="shopPage" class="page-content hidden">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:py-6">
                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <!-- Filter Sidebar -->
                    <div id="filterSidebar" class="filter-sidebar">
                        <div class="flex justify-between items-center mb-4 md:hidden">
                            <h3 class="font-bold text-base text-gray-800">Filters</h3>
                            <button onclick="closeMobileFilter()" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Filter by Budget -->
                        <div class="mb-5">
                            <h3 class="font-bold text-gray-900 text-sm sm:text-base mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Budget
                            </h3>
                            <div class="space-y-3 sm:space-y-4">
                                <div class="flex gap-2">
                                    <input type="number" id="minPrice" placeholder="Min ₱" class="w-1/2 px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <input type="number" id="maxPrice" placeholder="Max ₱" class="w-1/2 px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <input type="range" id="priceSlider" class="filter-slider w-full" min="0" max="50000" step="1000">
                                    <div class="flex justify-between text-[10px] sm:text-xs text-gray-500 mt-2">
                                        <span>₱0</span><span>₱10k</span><span>₱20k</span><span>₱30k</span><span>₱40k</span><span>₱50k+</span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="applyBudgetFilter()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition">Apply</button>
                                    <button onclick="clearFilters()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition">Clear</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t my-3 sm:my-4"></div>
                        
                        <!-- Categories -->
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm sm:text-base mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                Categories
                            </h3>
                            <div id="categoriesList" class="space-y-1.5 max-h-80 overflow-y-auto">
                                <button onclick="filterByCategory('')" class="category-filter-btn w-full text-left px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition text-xs sm:text-sm">All Products</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products Grid -->
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4 sm:mb-6">
                            <div>
                                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800">Shop Products</h2>
                                <div class="text-xs sm:text-sm text-gray-500 mt-0.5" id="productCount">Loading...</div>
                            </div>
                            <div class="relative w-full sm:w-72">
                                <input
                                    type="search"
                                    id="productSearch"
                                    placeholder="Search products..."
                                    class="w-full rounded-xl border border-gray-300 bg-white py-2 sm:py-2.5 pl-9 sm:pl-11 pr-3 text-xs sm:text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                >
                                <svg class="absolute left-3 top-1/2 w-4 h-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z"></path>
                                </svg>
                            </div>
                        </div>
                        <div id="productsGrid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-3 sm:gap-4 md:gap-5">
                            <div class="text-center py-8 text-gray-500 col-span-full">Loading products...</div>
                        </div>
                        <div id="shopPagination" class="mt-4 flex justify-center"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ORDERS PAGE -->
        <div id="ordersPage" class="page-content hidden">
            <div class="max-w-7xl mx-auto px-4 py-6 sm:py-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">My Orders</h2>
                <div id="ordersList" class="space-y-3 sm:space-y-4">
                    <div class="text-center text-gray-500 py-8 bg-white rounded-xl text-sm">Loading your orders...</div>
                </div>
            </div>
        </div>

        <!-- CART PAGE -->
        <div id="cartPage" class="page-content hidden">
            <div class="max-w-4xl mx-auto px-4 py-6 sm:py-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">Shopping Cart</h2>
                <div id="cartPageItems" class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="text-center text-gray-500 py-10 sm:py-12 text-sm">Your cart is empty</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkoutModal" class="fixed inset-0 bg-black/50 z-50 items-center justify-center hidden" onclick="if(event.target===this)closeCheckoutModal()">
        <div class="bg-white rounded-2xl w-[90%] max-w-md mx-4 shadow-2xl">
            <div class="p-5 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Checkout</h2>
                <form id="checkoutForm">
                    @csrf
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-gray-700 text-xs sm:text-sm font-semibold mb-1">Full Name *</label>
                        <input type="text" id="customer_name" value="{{ Auth::user()->name }}" required class="w-full border border-gray-300 rounded-xl p-2 sm:p-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-gray-700 text-xs sm:text-sm font-semibold mb-1">Email *</label>
                        <input type="email" id="customer_email" value="{{ Auth::user()->email }}" required class="w-full border border-gray-300 rounded-xl p-2 sm:p-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-gray-700 text-xs sm:text-sm font-semibold mb-1">Phone Number *</label>
                        <input type="text" id="customer_phone" required class="w-full border border-gray-300 rounded-xl p-2 sm:p-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-gray-700 text-xs sm:text-sm font-semibold mb-1">Payment Method</label>
                        <select id="payment_method" required class="w-full border border-gray-300 rounded-xl p-2 sm:p-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="cash">Cash on Delivery</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="mb-4 sm:mb-5">
                        <label class="block text-gray-700 text-xs sm:text-sm font-semibold mb-1">Delivery Address *</label>
                        <textarea id="notes" rows="2" required class="w-full border border-gray-300 rounded-xl p-2 sm:p-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your complete address"></textarea>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row gap-2 sm:gap-3">
                        <button type="button" onclick="closeCheckoutModal()" class="flex-1 py-2 sm:py-3 bg-gray-200 rounded-xl font-semibold hover:bg-gray-300 transition text-sm">Cancel</button>
                        <button type="submit" class="flex-1 py-2 sm:py-3 bg-green-600 rounded-xl text-white font-semibold hover:bg-green-700 transition text-sm">Place Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Details Modal -->
    <div id="productDetailsModal" class="fixed inset-0 bg-black/50 z-50 items-center justify-center hidden p-3 sm:p-4" onclick="if(event.target===this)closeProductDetails()">
        <div class="w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b px-4 sm:px-6 py-3 sm:py-4">
                <div>
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-[0.2em] text-blue-600">Product Details</p>
                    <h2 id="productDetailsTitle" class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900">Product</h2>
                </div>
                <button onclick="closeProductDetails()" class="rounded-full p-1.5 sm:p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="grid gap-4 sm:gap-6 p-4 sm:p-6 md:grid-cols-[1.1fr_0.9fr]">
                <div id="productDetailsImage" class="flex min-h-[200px] sm:min-h-[260px] items-center justify-center rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200"></div>
                <div>
                    <div class="mb-3 sm:mb-4 flex flex-wrap items-center gap-2">
                        <span id="productDetailsCategory" class="rounded-full bg-blue-100 px-2 sm:px-3 py-0.5 sm:py-1 text-[10px] sm:text-xs font-semibold text-blue-700">Category</span>
                        <span id="productDetailsStockBadge" class="rounded-full bg-emerald-100 px-2 sm:px-3 py-0.5 sm:py-1 text-[10px] sm:text-xs font-semibold text-emerald-700">In Stock</span>
                    </div>
                    <p id="productDetailsPrice" class="mb-3 sm:mb-4 text-2xl sm:text-3xl font-extrabold text-green-600">P0.00</p>
                    <div class="grid gap-2 sm:gap-3 grid-cols-2">
                        <div class="rounded-xl bg-gray-50 p-3 sm:p-4">
                            <p class="text-[9px] sm:text-xs uppercase tracking-wide text-gray-500">Brand</p>
                            <p id="productDetailsBrand" class="mt-1 font-semibold text-gray-800 text-xs sm:text-sm">N/A</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 p-3 sm:p-4">
                            <p class="text-[9px] sm:text-xs uppercase tracking-wide text-gray-500">Model</p>
                            <p id="productDetailsModel" class="mt-1 font-semibold text-gray-800 text-xs sm:text-sm">N/A</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 p-3 sm:p-4 col-span-2">
                            <p class="text-[9px] sm:text-xs uppercase tracking-wide text-gray-500">Performance</p>
                            <p id="productDetailsPerformance" class="mt-1 font-semibold text-gray-800 text-xs sm:text-sm">N/A</p>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-5 rounded-xl border border-gray-200 p-3 sm:p-4">
                        <p class="text-[9px] sm:text-xs uppercase tracking-wide text-gray-500">Extra Specs</p>
                        <div id="productDetailsSpecs" class="mt-2 sm:mt-3 space-y-1.5 text-xs sm:text-sm text-gray-700"></div>
                    </div>
                    <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button id="productDetailsAddToCart" class="flex-1 rounded-xl bg-blue-600 px-4 sm:px-5 py-2 sm:py-3 font-semibold text-white transition hover:bg-blue-700 text-sm">Add to Cart</button>
                        <button onclick="closeProductDetails()" class="flex-1 rounded-xl bg-gray-200 px-4 sm:px-5 py-2 sm:py-3 font-semibold text-gray-700 transition hover:bg-gray-300 text-sm">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-20 right-3 sm:bottom-24 sm:right-5 bg-green-600 text-white py-2 sm:py-3 px-3 sm:px-5 rounded-lg text-xs sm:text-sm z-[300] hidden shadow-lg">Success!</div>

    <!-- Footer -->
    <footer id="shopFooter" class="bg-gray-900 text-white mt-10 sm:mt-16">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('pictures/LOGO.png') }}" alt="JUSTRIX" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl object-cover shadow-lg">
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold">JUSTRIX</h3>
                            <p class="text-[10px] sm:text-xs text-gray-400">Premium Computer Parts</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-xs sm:text-sm mb-4">Your trusted source for quality computer components and PC parts since 2024.</p>
                    <div class="flex space-x-2 sm:space-x-3">
                        <a href="#" class="social-icon w-8 h-8 sm:w-10 sm:h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition"><svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg></a>
                        <a href="#" class="social-icon w-8 h-8 sm:w-10 sm:h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition"><svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-base sm:text-lg mb-3 sm:mb-4">Quick Links</h4>
                    <ul class="space-y-1.5 sm:space-y-2">
                        <li><a href="#" onclick="showPage('home')" class="footer-link text-gray-400 hover:text-white text-xs sm:text-sm block">Home</a></li>
                        <li><a href="#" onclick="showPage('shop')" class="footer-link text-gray-400 hover:text-white text-xs sm:text-sm block">Shop</a></li>
                        <li><a href="#" onclick="showPage('orders')" class="footer-link text-gray-400 hover:text-white text-xs sm:text-sm block">My Orders</a></li>
                        <li><a href="#" onclick="showPage('cart')" class="footer-link text-gray-400 hover:text-white text-xs sm:text-sm block">Cart</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-base sm:text-lg mb-3 sm:mb-4">Customer Service</h4>
                    <ul class="space-y-1.5 sm:space-y-2">
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white text-xs sm:text-sm block">Contact Us</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white text-xs sm:text-sm block">Returns Policy</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white text-xs sm:text-sm block">Shipping Info</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white text-xs sm:text-sm block">FAQs</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-base sm:text-lg mb-3 sm:mb-4">Contact Us</h4>
                    <ul class="space-y-2 sm:space-y-3">
                        <li class="flex items-center gap-2 sm:gap-3 text-gray-400 text-xs sm:text-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>support@justrix.com</li>
                        <li class="flex items-center gap-2 sm:gap-3 text-gray-400 text-xs sm:text-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>+63 (2) 1234 5678</li>
                        <li class="flex items-center gap-2 sm:gap-3 text-gray-400 text-xs sm:text-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>Makati City, Philippines</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-6 sm:mt-8 pt-5 sm:pt-8 text-center">
                <p class="text-gray-400 text-xs sm:text-sm">&copy; 2026 JUSTRIX. All rights reserved. | Premium Computer Parts Store</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile filter functions
        function syncMobileFilterOffset() {
            const navbar = document.getElementById('shopNavbar');
            const sidebar = document.getElementById('filterSidebar');
            const overlay = document.getElementById('filterOverlay');
            if (!navbar || !sidebar || !overlay) return;
            const navHeight = navbar.offsetHeight || 64;
            const offset = `${navHeight}px`;
            sidebar.style.top = offset;
            overlay.style.top = offset;
        }

        function toggleMobileFilter() {
            const sidebar = document.getElementById('filterSidebar');
            const overlay = document.getElementById('filterOverlay');
            syncMobileFilterOffset();
            sidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileFilter() {
            const sidebar = document.getElementById('filterSidebar');
            const overlay = document.getElementById('filterOverlay');

            sidebar.classList.remove('open');
            overlay.classList.remove('active');

            document.body.style.overflow = '';
        }
        
        // Scroll to Top button
        window.addEventListener('scroll', function() {
            const scrollBtn = document.getElementById('scrollToTop');
            if (window.scrollY > 300) {
                scrollBtn.classList.add('show');
            } else {
                scrollBtn.classList.remove('show');
            }
        });
        
        // Helper function for placeholder images
        function getProductImage(imagePath) {
            if (imagePath) {
                return '/storage/' + imagePath;
            }
            return 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 80 80\'%3E%3Crect width=\'80\' height=\'80\' fill=\'%23e2e8f0\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%2364748b\' font-size=\'10\'%3ENo Image%3C/text%3E%3C/svg%3E';
        }
        
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let allProducts = [];
        let productsLoaded = false;
        let isLoadingProducts = false;
        let currentCategory = '';
        let currentMinPrice = 0;
        let currentMaxPrice = 50000;
        let currentSearchQuery = '';
        let currentShopPage = 1;
        const SHOP_PRODUCTS_PER_PAGE = 16;
        const dom = {};

        function showLogoutConfirm() { document.getElementById('logoutConfirmModal').classList.remove('hidden'); document.getElementById('logoutConfirmModal').classList.add('flex'); }
        function closeLogoutConfirm() { document.getElementById('logoutConfirmModal').classList.remove('flex'); document.getElementById('logoutConfirmModal').classList.add('hidden'); }
        function confirmLogout() { document.getElementById('logout-form').submit(); }

        function showPage(page) {
            closeMobileFilter();
            document.querySelectorAll('.page-content').forEach(el => el.classList.add('hidden'));
            document.getElementById(`${page}Page`).classList.remove('hidden');
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('nav-active'));
            const footer = document.getElementById('shopFooter');
            if (footer) footer.classList.toggle('hidden', page !== 'home');
            const filterToggleBtn = document.getElementById('mobileFilterToggle');
            if (filterToggleBtn) {
                filterToggleBtn.classList.toggle('hidden', page !== 'shop');
            }
            if (page === 'home') document.getElementById('navHome').classList.add('nav-active');
            if (page === 'shop') document.getElementById('navShop').classList.add('nav-active');
            if (page === 'orders') document.getElementById('navOrders').classList.add('nav-active');
            if (page === 'shop') {
                loadProducts();
                applyShopFiltersAndRender();
            }
            if (page === 'orders') loadCustomerOrders();
            if (page === 'cart') updateCartPage();
            if (page === 'home') loadHomeCategories();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        async function loadHomeCategories() {
            try {
                const res = await fetch('/api/categories');
                let categories = await res.json();
                document.getElementById('homeCategories').innerHTML = categories.slice(0, 4).map(cat => `
                    <div class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition cursor-pointer" onclick="filterByCategory(${cat.id}); showPage('shop')">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 text-xs sm:text-sm">${escapeHtml(cat.name)}</h3>
                        <p class="text-gray-500 text-[10px] sm:text-xs mt-1">${cat.products_count || 0} products</p>
                    </div>
                `).join('');
            } catch (error) { console.error('Error loading home categories:', error); }
        }

        async function loadCategories() {
            try {
                const res = await fetch('/api/categories');
                if (!res.ok) throw new Error(`Failed to load categories (${res.status})`);
                let categories = await res.json();
                const container = document.getElementById('categoriesList');
                container.innerHTML = `<button onclick="filterByCategory('')" class="category-filter-btn w-full text-left px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition text-sm">All Products</button>`;
                categories.forEach(cat => { 
                    container.innerHTML += `<button onclick="filterByCategory(${cat.id})" class="category-filter-btn w-full text-left px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition text-sm">${escapeHtml(cat.name)}</button>`; 
                });
                updateCategoryActiveState();
            } catch (error) { console.error('Error loading categories:', error); }
        }

        function updateCategoryActiveState() {
            document.querySelectorAll('.category-filter-btn').forEach(btn => {
                const normalizedText = (btn.textContent || '').trim().toLowerCase();
                const btnCategoryId = btn.getAttribute('onclick')?.match(/filterByCategory\((.*?)\)/)?.[1]?.replace(/['"]/g, '');
                const isAllButton = normalizedText === 'all products';
                const isActive = currentCategory
                    ? String(btnCategoryId) === String(currentCategory)
                    : isAllButton;
                btn.classList.toggle('category-active', isActive);
                btn.classList.toggle('text-white', isActive);
                btn.classList.toggle('bg-blue-600', isActive);
            });
        }

        function filterByCategory(categoryId) { 
            currentCategory = categoryId ? String(categoryId) : '';
            currentShopPage = 1;
            updateCategoryActiveState();
            applyShopFiltersAndRender();
            if (window.innerWidth < 768) closeMobileFilter();
        }

        function applyBudgetFilter() {
            const minPrice = document.getElementById('minPrice').value;
            const maxPrice = document.getElementById('maxPrice').value;
            currentMinPrice = minPrice ? parseInt(minPrice) : 0;
            currentMaxPrice = maxPrice ? parseInt(maxPrice) : 50000;
            currentShopPage = 1;
            applyShopFiltersAndRender();
            if (window.innerWidth < 768) closeMobileFilter();
        }

        function clearFilters() {
            document.getElementById('minPrice').value = '';
            document.getElementById('maxPrice').value = '';
            const searchInput = document.getElementById('productSearch');
            if (searchInput) searchInput.value = '';
            currentMinPrice = 0;
            currentMaxPrice = 50000;
            currentCategory = '';
            currentSearchQuery = '';
            currentShopPage = 1;
            updateCategoryActiveState();
            applyShopFiltersAndRender();
            if (window.innerWidth < 768) closeMobileFilter();
        }

        function getFilteredProducts() {
            let filtered = [...allProducts];
            const search = currentSearchQuery.trim().toLowerCase();
            if (currentCategory) filtered = filtered.filter(p => String(p?.category_id ?? '') === String(currentCategory));
            filtered = filtered.filter(p => {
                const price = parseFloat(p?.price || 0);
                return price >= currentMinPrice && price <= currentMaxPrice;
            });
            if (search) {
                filtered = filtered.filter(product => [product?.name, product?.brand, product?.model_number, product?.performance, product?.category?.name || '']
                    .some(value => String(value || '').toLowerCase().includes(search)));
            }
            return filtered;
        }

        function renderPagination(totalItems) {
            const container = dom.shopPagination || document.getElementById('shopPagination');
            if (!container) return;
            const totalPages = Math.max(1, Math.ceil(totalItems / SHOP_PRODUCTS_PER_PAGE));
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }
            const pages = Array.from({ length: totalPages }, (_, i) => i + 1);
            container.innerHTML = `
                <div class="flex items-center gap-1 sm:gap-2">
                    <button type="button" onclick="changeShopPage(${currentShopPage - 1})" ${currentShopPage === 1 ? 'disabled' : ''} class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">Prev</button>
                    ${pages.map(page => `<button type="button" onclick="changeShopPage(${page})" class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border ${page === currentShopPage ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'}">${page}</button>`).join('')}
                    <button type="button" onclick="changeShopPage(${currentShopPage + 1})" ${currentShopPage === totalPages ? 'disabled' : ''} class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
                </div>
            `;
        }

        function changeShopPage(page) {
            const totalPages = Math.max(1, Math.ceil(getFilteredProducts().length / SHOP_PRODUCTS_PER_PAGE));
            currentShopPage = Math.min(Math.max(1, page), totalPages);
            applyShopFiltersAndRender();
        }

        function applyShopFiltersAndRender() {
            const filtered = getFilteredProducts();
            const totalPages = Math.max(1, Math.ceil(filtered.length / SHOP_PRODUCTS_PER_PAGE));
            currentShopPage = Math.min(currentShopPage, totalPages);
            const start = (currentShopPage - 1) * SHOP_PRODUCTS_PER_PAGE;
            renderProducts(filtered.slice(start, start + SHOP_PRODUCTS_PER_PAGE), filtered.length);
            renderPagination(filtered.length);
        }

        function renderProducts(products, totalCount = products.length) {
            const productCountEl = dom.productCount || document.getElementById('productCount');
            if (productCountEl) productCountEl.innerText = `${totalCount} products`;
            const container = dom.productsGrid || document.getElementById('productsGrid');
            if (products.length === 0) {
                container.innerHTML = '<div class="col-span-full text-center py-10 sm:py-12 bg-white rounded-xl"><svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><h3 class="text-base sm:text-lg font-medium text-gray-900 mb-1">No products found</h3><p class="text-xs sm:text-sm text-gray-500">Try adjusting your filters</p></div>'; 
                return;
            }
            container.innerHTML = products.map(product => `
                <div class="product-card bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="relative h-32 sm:h-40 md:h-44 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                        ${product.image ? `<img src="/storage/${product.image}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">` : `<div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200"><svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`}
                        ${product.quantity < 10 && product.quantity > 0 ? `<div class="absolute top-2 right-2"><span class="bg-amber-100 text-amber-700 text-[9px] sm:text-xs font-semibold px-1.5 py-0.5 rounded-full">Low Stock</span></div>` : ''}
                        ${product.quantity === 0 ? `<div class="absolute inset-0 bg-black/50 flex items-center justify-center"><span class="bg-red-600 text-white px-2 py-1 rounded-full text-[10px] sm:text-xs font-semibold">Out of Stock</span></div>` : ''}
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="font-bold text-gray-900 text-sm sm:text-base mb-1 truncate">${escapeHtml(product.name)}</h3>
                        <p class="text-xs text-gray-500 mb-1">${escapeHtml(product.brand || 'Unspecified brand')}</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-bold text-green-600 mb-2">₱${parseFloat(product.price).toLocaleString()}</p>
                        <div class="flex items-center justify-between text-xs sm:text-sm mb-3">
                            <span class="text-gray-500">Stock: ${product.quantity} units</span>
                            <span class="text-blue-600 text-[10px] sm:text-xs font-medium">${product?.category?.name ? escapeHtml(product.category.name) : 'No Category'}</span>
                        </div>
                        <button onclick="addToCartById(${product.id})" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-xl transition text-xs sm:text-sm ${product.quantity === 0 ? 'opacity-50 cursor-not-allowed' : ''}" ${product.quantity === 0 ? 'disabled' : ''}>Add to Cart</button>
                    </div>
                </div>
            `).join('');
        }

        async function loadProducts(force = false) {
            if ((productsLoaded && !force) || isLoadingProducts) {
                return;
            }
            isLoadingProducts = true;
            try {
                const res = await fetch('/api/products');
                if (!res.ok) throw new Error(`Failed to load products (${res.status})`);
                let products = await res.json();
                if (products.data) products = products.data;
                allProducts = Array.isArray(products) ? products : [];
                productsLoaded = true;
                applyShopFiltersAndRender();
            } catch (error) {
                console.error('Error loading products:', error);
                showToast('Unable to load products right now.', true);
            } finally {
                isLoadingProducts = false;
            }
        }

        async function loadCustomerOrders() {
            try {
                const res = await fetch('/api/sales');
                let salesData = await res.json();
                let sales = Array.isArray(salesData) ? salesData : Object.values(salesData);
                const userEmail = "{{ Auth::user()->email }}";
                let customerOrders = sales.filter(s => s.customer_email === userEmail);
                document.getElementById('ordersBadge').innerText = customerOrders.length;
                document.getElementById('ordersBadge').classList.toggle('hidden', customerOrders.length === 0);
                document.getElementById('mobileOrdersBadge').innerText = customerOrders.length;
                document.getElementById('mobileOrdersBadge').classList.toggle('hidden', customerOrders.length === 0);
                const container = document.getElementById('ordersList');
                if (customerOrders.length === 0) { container.innerHTML = '<div class="text-center text-gray-500 py-10 sm:py-12 bg-white rounded-xl text-sm">No orders found. Start shopping!</div>'; return; }
                container.innerHTML = customerOrders.slice().reverse().map(order => `
                    <div class="order-card bg-white rounded-xl p-4 sm:p-5 shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm sm:text-base">${escapeHtml(order.product?.name || 'Product')}</p>
                                <p class="text-xs text-gray-500">Order #${order.id}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600 text-sm sm:text-base">₱${parseFloat(order.total_price).toLocaleString()}</p>
                                <p class="text-[10px] sm:text-xs text-gray-500">${new Date(order.sold_at).toLocaleDateString()}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-xs sm:text-sm">
                            <div><span class="text-gray-500">Qty:</span> ${order.quantity}</div>
                            <div><span class="text-gray-500">Payment:</span> ${order.payment_method}</div>
                            <div><span class="text-gray-500">Status:</span> <span class="text-green-600 font-medium">Completed</span></div>
                        </div>
                        <div class="mt-3 text-xs text-gray-600"><svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>${order.notes || 'Delivery to your address'}</div>
                    </div>
                `).join('');
            } catch (error) { console.error('Error loading orders:', error); }
        }

        function updateCartPage() {
            const container = document.getElementById('cartPageItems');
            if (cart.length === 0) { container.innerHTML = '<div class="text-center text-gray-500 py-10 sm:py-12">Your cart is empty</div>'; return; }
            let total = 0;
            container.innerHTML = `<div class="divide-y">${cart.map(item => { const itemTotal = item.price * item.quantity; total += itemTotal; return `<div class="flex gap-3 sm:gap-4 p-3 sm:p-4 items-center"><img src="${getProductImage(item.image)}" class="w-14 h-14 sm:w-20 sm:h-20 object-cover rounded-lg"><div class="flex-1"><h4 class="font-semibold text-gray-800 text-sm sm:text-base">${escapeHtml(item.name)}</h4><p class="text-green-600 font-bold text-xs sm:text-sm">₱${parseFloat(item.price).toLocaleString()}</p><div class="flex items-center gap-2 mt-2"><button onclick="updateQuantity(${item.id}, -1)" class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-200 rounded-full hover:bg-gray-300 text-xs">-</button><span class="w-6 text-center text-sm">${item.quantity}</span><button onclick="updateQuantity(${item.id}, 1)" class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-200 rounded-full hover:bg-gray-300 text-xs">+</button><button onclick="removeFromCart(${item.id})" class="ml-2 text-red-500 hover:text-red-700 text-xs">Remove</button></div></div><div class="text-right"><p class="font-bold text-gray-900 text-sm sm:text-base">₱${itemTotal.toLocaleString()}</p></div></div>`; }).join('')}<div class="p-3 sm:p-4 bg-gray-50 flex justify-between items-center"><span class="font-bold text-base sm:text-lg">Total:</span><span class="font-bold text-xl sm:text-2xl text-green-600">₱${total.toLocaleString()}</span></div><div class="p-3 sm:p-4"><button onclick="checkout()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 sm:py-3 rounded-xl transition text-sm">Proceed to Checkout</button></div></div>`;
        }

        function saveCart() {
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartUI();
            updateCartPage();
        }
        function updateCartUI() { const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0); document.getElementById('cartCount').innerText = totalItems; document.getElementById('mobileCartBadge').innerText = totalItems; document.getElementById('mobileCartBadge').classList.toggle('hidden', totalItems === 0); }
        function updateQuantity(productId, change) { const item = cart.find(i => i.id === productId); if (item) { const newQuantity = item.quantity + change; if (newQuantity <= 0) removeFromCart(productId); else if (newQuantity <= item.stock) { item.quantity = newQuantity; saveCart(); } else showToast('Not enough stock!', true); } }
        function addToCartById(productId) {
            const product = allProducts.find(item => item.id === productId);
            if (!product) { showToast('Product not found.', true); return; }
            addToCart({ ...product, stock: product.quantity });
        }
        function addToCart(product) {
            const existing = cart.find(i => i.id === product.id);
            const productPrice = parseFloat(product?.price || 0);
            if (existing) {
                if (existing.quantity + 1 <= product.stock) {
                    existing.quantity++;
                    saveCart();
                    showToast(`${product.name} added to cart!`);
                } else showToast('Not enough stock!', true);
            } else {
                cart.push({ id: product.id, name: product.name, price: productPrice, image: product.image, stock: product.quantity, quantity: 1 });
                saveCart();
                showToast(`${product.name} added to cart!`);
            }
        }
        function removeFromCart(productId) { cart = cart.filter(i => i.id !== productId); saveCart(); }
        function closeProductDetails() { document.getElementById('productDetailsModal').classList.remove('flex'); document.getElementById('productDetailsModal').classList.add('hidden'); }
        function formatSpecLabel(key) { return String(key || '').replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase()); }
        
        function showProductDetails(productId) {
            const product = allProducts.find(item => item.id === productId);
            if (!product) { showToast('Product details are unavailable.', true); return; }
            document.getElementById('productDetailsTitle').innerText = product.name || 'Product Details';
            document.getElementById('productDetailsCategory').innerText = product?.category?.name || 'No Category';
            document.getElementById('productDetailsPrice').innerText = `₱${parseFloat(product.price || 0).toLocaleString()}`;
            document.getElementById('productDetailsBrand').innerText = product.brand || 'N/A';
            document.getElementById('productDetailsModel').innerText = product.model_number || 'N/A';
            document.getElementById('productDetailsPerformance').innerText = product.performance || 'No performance details provided.';
            const stockBadge = document.getElementById('productDetailsStockBadge');
            if (product.quantity === 0) {
                stockBadge.innerText = 'Out of Stock';
                stockBadge.className = 'rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-semibold text-red-700';
            } else if (product.quantity < 10) {
                stockBadge.innerText = `Low Stock (${product.quantity})`;
                stockBadge.className = 'rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-700';
            } else {
                stockBadge.innerText = `${product.quantity} units available`;
                stockBadge.className = 'rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-700';
            }
            document.getElementById('productDetailsImage').innerHTML = product.image ? `<img src="/storage/${product.image}" alt="${escapeHtml(product.name)}" class="h-full w-full rounded-xl object-cover">` : `<div class="flex h-full w-full items-center justify-center rounded-xl bg-gradient-to-br from-slate-100 to-slate-200"><svg class="h-12 w-12 sm:h-16 sm:w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 0 1 2.828 0L16 16m-2-2 1.586-1.586a2 2 0 0 1 2.828 0L20 14m-6-6h.01M6 20h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"></path></svg></div>`;
            document.getElementById('productDetailsSpecs').innerHTML = product.dynamic_fields && Object.keys(product.dynamic_fields).length ? Object.entries(product.dynamic_fields).map(([key, value]) => `<div class="flex items-start justify-between gap-2 rounded-lg bg-gray-50 px-2 py-1.5"><span class="font-medium text-gray-500 text-[10px] sm:text-xs">${escapeHtml(formatSpecLabel(key))}</span><span class="text-right font-semibold text-gray-800 text-[10px] sm:text-xs">${escapeHtml(value)}</span></div>`).join('') : '<p class="rounded-lg bg-gray-50 px-3 py-3 text-sm text-gray-500">No extra specifications available for this product yet.</p>';
            const addButton = document.getElementById('productDetailsAddToCart');
            addButton.disabled = product.quantity === 0;
            addButton.className = `flex-1 rounded-xl px-4 py-2 font-semibold text-white transition text-sm ${product.quantity === 0 ? 'cursor-not-allowed bg-gray-400' : 'bg-blue-600 hover:bg-blue-700'}`;
            addButton.onclick = () => addToCartById(product.id);
            document.getElementById('productDetailsModal').classList.remove('hidden');
            document.getElementById('productDetailsModal').classList.add('flex');
        }
        
        function closeCheckoutModal() { document.getElementById('checkoutModal').classList.remove('flex'); document.getElementById('checkoutModal').classList.add('hidden'); }
        async function checkout() { if (cart.length === 0) { showToast('Your cart is empty', true); return; } document.getElementById('checkoutModal').classList.remove('hidden'); document.getElementById('checkoutModal').classList.add('flex'); }

        document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const submitBtn = document.querySelector('#checkoutForm button[type="submit"]');
            const originalText = submitBtn.innerText;
            submitBtn.innerText = 'Processing...';
            submitBtn.disabled = true;
            let successCount = 0, errors = [];
            for (const item of cart) {
                try {
                    const res = await fetch('/api/sales', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ product_id: item.id, quantity: item.quantity, customer_name: document.getElementById('customer_name').value, customer_email: "{{ Auth::user()->email }}", customer_phone: document.getElementById('customer_phone').value, payment_method: document.getElementById('payment_method').value, notes: document.getElementById('notes').value }) });
                    const data = await res.json();
                    if (!res.ok) errors.push(`${item.name}: ${data.error || 'Checkout failed'}`);
                    else successCount++;
                } catch (error) { errors.push(`${item.name}: Network error`); }
            }
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
            if (errors.length > 0) { showToast(errors.join(', '), true); return; }
            cart = [];
            saveCart();
            closeCheckoutModal();
            showToast(`Order placed successfully! Thank you for shopping at JUSTRIX!`);
            await loadProducts(true);
            loadCustomerOrders();
            document.getElementById('checkoutForm').reset();
            showPage('orders');
        });

        function showToast(message, isError = false) { 
            const toast = document.getElementById('toast'); 
            toast.innerText = message; 
            toast.classList.remove('hidden'); 
            toast.className = `fixed bottom-20 right-3 sm:bottom-24 sm:right-5 py-2 sm:py-3 px-3 sm:px-5 rounded-lg text-xs sm:text-sm z-[300] shadow-lg ${isError ? 'bg-red-600 text-white' : 'bg-green-600 text-white'}`; 
            setTimeout(() => toast.classList.add('hidden'), 3000); 
        }
        
        function escapeHtml(text) { if (!text) return ''; const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }

        const priceSlider = document.getElementById('priceSlider');
        if (priceSlider) priceSlider.addEventListener('input', function(e) {
            document.getElementById('maxPrice').value = e.target.value === '50000' ? '' : e.target.value;
            currentMaxPrice = parseInt(e.target.value);
            currentShopPage = 1;
            applyShopFiltersAndRender();
        });
        const productSearch = document.getElementById('productSearch');
        if (productSearch) productSearch.addEventListener('input', function(e) {
            currentSearchQuery = e.target.value;
            currentShopPage = 1;
            applyShopFiltersAndRender();
        });

        document.addEventListener('DOMContentLoaded', () => {
            syncMobileFilterOffset();
            window.addEventListener('resize', syncMobileFilterOffset);
            dom.productsGrid = document.getElementById('productsGrid');
            dom.productCount = document.getElementById('productCount');
            dom.shopPagination = document.getElementById('shopPagination');
            loadCategories();
            loadProducts();
            loadHomeCategories();
            updateCartUI();
            loadCustomerOrders();
            showPage('home');
            
            // Close mobile filter when clicking outside on desktop
            document.addEventListener('click', function(event) {
                const sidebar = document.getElementById('filterSidebar');
                const filterBtn = event.target.closest('[onclick="toggleMobileFilter()"]');
                const isClickInside = sidebar && sidebar.contains(event.target);
                
                if (window.innerWidth < 768 && sidebar && sidebar.classList.contains('open') && !isClickInside && !filterBtn) {
                    closeMobileFilter();
                }
            });
        });
    </script>
</body>
</html>
