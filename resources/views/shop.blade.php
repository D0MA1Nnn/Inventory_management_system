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
        .profile-dropdown { transition: all 0.2s ease; opacity: 0; visibility: hidden; transform: translateY(-10px); }
        .profile-trigger:hover .profile-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
        @media (max-width: 768px) {
            .filter-sidebar { position: fixed; left: -280px; top: 0; bottom: 0; width: 280px; background: white; z-index: 100; overflow-y: auto; padding: 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.1); transition: left 0.3s ease; }
            .filter-sidebar.open { left: 0; }
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Customer Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('pictures/LOGO.png') }}" alt="JUSTRIX" class="w-10 h-10 rounded-full object-cover shadow-md">
                    <div>
                        <span class="text-xl font-bold text-gray-800">JUSTRIX</span>
                        <p class="text-xs text-gray-500 hidden md:block">Premium Computer Parts</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#" onclick="showPage('home')" id="navHome" class="text-gray-700 hover:text-blue-600 transition font-medium nav-link pb-2">Home</a>
                    <a href="#" onclick="showPage('shop')" id="navShop" class="text-gray-700 hover:text-blue-600 transition font-medium nav-link pb-2">Shop</a>
                    <a href="#" onclick="showPage('orders')" id="navOrders" class="text-gray-700 hover:text-blue-600 transition font-medium nav-link pb-2 relative">
                        My Orders
                        <span id="ordersBadge" class="absolute -top-2 -right-6 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Mobile Filter Toggle -->
                    <button onclick="toggleMobileFilter()" class="md:hidden p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <button onclick="showPage('cart')" class="relative p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"></path>
                        </svg>
                        <span id="cartCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                    </button>
                    
                    <!-- Profile Dropdown -->
                    <div class="relative profile-trigger">
                        <button class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center shadow-md">
                                <span class="text-white font-bold text-sm">
                                    @auth
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                    @endauth
                                </span>
                            </div>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="profile-dropdown absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl overflow-hidden z-50">
                            <div class="p-4 border-b bg-gray-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">
                                            @auth
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            @endauth
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <button onclick="showProfileModal()" class="w-full flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-xl transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>My Profile</span>
                                </button>
                                <button onclick="showPage('orders')" class="w-full flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-xl transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <span>My Orders</span>
                                </button>
                                <div class="border-t my-2"></div>
                                <button onclick="showLogoutConfirm()" class="w-full flex items-center gap-3 px-3 py-2 text-red-600 hover:bg-red-50 rounded-xl transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg z-50 md:hidden">
        <div class="flex justify-around py-3">
            <button onclick="showPage('home')" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg><span class="text-xs mt-1">Home</span></button>
            <button onclick="showPage('shop')" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg><span class="text-xs mt-1">Shop</span></button>
            <button onclick="showPage('orders')" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition relative"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg><span class="text-xs mt-1">Orders</span><span id="mobileOrdersBadge" class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center hidden">0</span></button>
            <button onclick="showPage('cart')" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition relative"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"></path></svg><span class="text-xs mt-1">Cart</span><span id="mobileCartBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center hidden">0</span></button>
        </div>
    </div>

    <!-- Mobile Filter Overlay -->
    <div id="mobileFilterOverlay" class="fixed inset-0 bg-black/50 z-40 hidden" onclick="toggleMobileFilter()"></div>

    <!-- Profile Modal -->
    <div id="profileModal" class="fixed inset-0 bg-black/50 z-50 items-center justify-center hidden" onclick="if(event.target===this)closeProfileModal()">
        <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">My Profile</h2>
                    <button onclick="closeProfileModal()" class="text-gray-500 hover:text-gray-700"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-3xl">
                                @auth
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                @endauth
                            </span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Full Name</label>
                        <p class="text-gray-800 font-medium">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Email Address</label>
                        <p class="text-gray-800 font-medium">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Member Since</label>
                        <p class="text-gray-800 font-medium">{{ Auth::user()->created_at->format('F d, Y') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Role</label>
                        <p class="text-gray-800 font-medium">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button onclick="closeProfileModal()" class="flex-1 py-3 bg-gray-200 rounded-xl font-semibold hover:bg-gray-300 transition">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutConfirmModal" class="fixed inset-0 bg-black/50 z-50 items-center justify-center hidden" onclick="if(event.target===this)closeLogoutConfirm()">
        <div class="bg-white rounded-2xl w-full max-w-sm mx-4 shadow-2xl">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Confirm Logout</h3>
                <p class="text-gray-500 mb-6">Are you sure you want to logout from your account?</p>
                <div class="flex gap-3">
                    <button onclick="closeLogoutConfirm()" class="flex-1 py-3 bg-gray-200 rounded-xl font-semibold hover:bg-gray-300 transition">Cancel</button>
                    <button onclick="confirmLogout()" class="flex-1 py-3 bg-red-600 rounded-xl text-white font-semibold hover:bg-red-700 transition">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <!-- Page Content Container -->
    <div id="pageContainer" class="pb-20 md:pb-0">
        <!-- HOME PAGE -->
        <div id="homePage" class="page-content">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16 md:py-24">
                <div class="max-w-7xl mx-auto px-4 text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4 animate-fadeIn">Welcome to JUSTRIX, {{ Auth::user()->name }}!</h1>
                    <p class="text-xl text-blue-100 mb-8">Your Premier Destination for Premium Computer Parts</p>
                    <button onclick="showPage('shop')" class="px-8 py-3 bg-white text-blue-600 font-semibold rounded-xl hover:shadow-lg transition transform hover:scale-105">Shop Now</button>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 py-16">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Why Choose JUSTRIX?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition group"><div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition"><svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><h3 class="text-xl font-semibold mb-2">Genuine Products</h3><p class="text-gray-600">100% authentic computer parts from trusted manufacturers</p></div>
                    <div class="text-center p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition group"><div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition"><svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><h3 class="text-xl font-semibold mb-2">Best Prices</h3><p class="text-gray-600">Competitive pricing and regular deals on components</p></div>
                    <div class="text-center p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition group"><div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition"><svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg></div><h3 class="text-xl font-semibold mb-2">Fast Shipping</h3><p class="text-gray-600">Quick delivery and secure packaging nationwide</p></div>
                </div>
            </div>
            <div class="bg-gray-50 py-16">
                <div class="max-w-7xl mx-auto px-4"><h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Popular Categories</h2><div id="homeCategories" class="grid grid-cols-2 md:grid-cols-4 gap-6"><div class="text-center py-8 text-gray-500 col-span-full">Loading categories...</div></div></div>
            </div>
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-16"><div class="max-w-7xl mx-auto px-4 text-center"><h2 class="text-3xl font-bold mb-4">Ready to Upgrade Your PC?</h2><p class="text-blue-100 mb-8">Browse our collection of premium computer parts</p><button onclick="showPage('shop')" class="px-8 py-3 bg-white text-blue-600 font-semibold rounded-xl hover:shadow-lg transition transform hover:scale-105">Start Shopping</button></div></div>
        </div>

        <!-- SHOP PAGE -->
        <div id="shopPage" class="page-content hidden">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Filter Sidebar - Left Side -->
                    <div id="filterSidebar" class="filter-sidebar md:relative md:w-72 flex-shrink-0 bg-white rounded-2xl shadow-sm p-5 md:block">
                        <div class="flex justify-between items-center mb-4 md:hidden">
                            <h3 class="font-bold text-lg text-gray-800">Filters</h3>
                            <button onclick="toggleMobileFilter()" class="text-gray-500 hover:text-gray-700"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                        
                        <!-- Filter by Budget -->
                        <div class="mb-6">
                            <h3 class="font-bold text-gray-900 text-lg mb-3 flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Filter by Budget</h3>
                            <div class="space-y-4">
                                <div class="flex gap-2">
                                    <input type="number" id="minPrice" placeholder="Min ₱" class="w-1/2 px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <input type="number" id="maxPrice" placeholder="Max ₱" class="w-1/2 px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <input type="range" id="priceSlider" class="filter-slider w-full" min="0" max="50000" step="1000">
                                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                                        <span>₱0</span><span>₱10k</span><span>₱20k</span><span>₱30k</span><span>₱40k</span><span>₱50k+</span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="applyBudgetFilter()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-xl text-sm font-medium transition">Apply</button>
                                    <button onclick="clearFilters()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-xl text-sm font-medium transition">Clear</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t my-4"></div>
                        
                        <!-- Categories -->
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg mb-3 flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>Categories</h3>
                            <div id="categoriesList" class="space-y-2 max-h-96 overflow-y-auto">
                                <button onclick="filterByCategory('')" class="category-filter-btn w-full text-left px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-100 transition">All Products</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products Grid - Right Side -->
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Shop Products</h2>
                            <div class="text-sm text-gray-500" id="productCount">Loading...</div>
                        </div>
                        <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"><div class="text-center py-8 text-gray-500 col-span-full">Loading products...</div></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ORDERS PAGE -->
        <div id="ordersPage" class="page-content hidden">
            <div class="max-w-7xl mx-auto px-4 py-8"><h2 class="text-2xl font-bold text-gray-800 mb-6">My Orders</h2><div id="ordersList" class="space-y-4"><div class="text-center text-gray-500 py-8 bg-white rounded-2xl">Loading your orders...</div></div></div>
        </div>

        <!-- CART PAGE -->
        <div id="cartPage" class="page-content hidden">
            <div class="max-w-4xl mx-auto px-4 py-8"><h2 class="text-2xl font-bold text-gray-800 mb-6">Shopping Cart</h2><div id="cartPageItems" class="bg-white rounded-2xl shadow-sm overflow-hidden"><div class="text-center text-gray-500 py-12">Your cart is empty</div></div></div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkoutModal" class="fixed inset-0 bg-black/50 z-50 items-center justify-center hidden" onclick="if(event.target===this)closeCheckoutModal()">
        <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl"><div class="p-6"><h2 class="text-xl font-bold text-gray-800 mb-4">Checkout</h2><form id="checkoutForm">@csrf<div class="mb-4"><label class="block text-gray-700 text-sm font-semibold mb-2">Full Name *</label><input type="text" id="customer_name" value="{{ Auth::user()->name }}" required class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500"></div><div class="mb-4"><label class="block text-gray-700 text-sm font-semibold mb-2">Email *</label><input type="email" id="customer_email" value="{{ Auth::user()->email }}" required class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500"></div><div class="mb-4"><label class="block text-gray-700 text-sm font-semibold mb-2">Phone Number *</label><input type="text" id="customer_phone" required class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500"></div><div class="mb-4"><label class="block text-gray-700 text-sm font-semibold mb-2">Payment Method</label><select id="payment_method" required class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500"><option value="cash">Cash on Delivery</option><option value="card">Credit/Debit Card</option><option value="bank_transfer">Bank Transfer</option></select></div><div class="mb-4"><label class="block text-gray-700 text-sm font-semibold mb-2">Delivery Address *</label><textarea id="notes" rows="2" required class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your complete address"></textarea></div><div class="flex gap-3"><button type="button" onclick="closeCheckoutModal()" class="flex-1 py-3 bg-gray-200 rounded-xl font-semibold hover:bg-gray-300 transition">Cancel</button><button type="submit" class="flex-1 py-3 bg-green-600 rounded-xl text-white font-semibold hover:bg-green-700 transition">Place Order</button></div></form></div></div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-24 right-5 bg-green-600 text-white py-3 px-5 rounded-xl text-sm z-[300] hidden shadow-lg">Success!</div>

    <!-- Scroll to Top Button -->
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" id="scrollTop" class="fixed bottom-24 right-5 bg-blue-600 text-white p-3 rounded-full shadow-lg hidden scroll-to-top z-40 hover:bg-blue-700 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg></button>

    <!-- Professional Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div><div class="flex items-center gap-3 mb-4"><img src="{{ asset('pictures/LOGO.png') }}" alt="JUSTRIX" class="w-12 h-12 rounded-xl object-cover shadow-lg"><div><h3 class="text-xl font-bold">JUSTRIX</h3><p class="text-xs text-gray-400">Premium Computer Parts</p></div></div><p class="text-gray-400 text-sm mb-4">Your trusted source for quality computer components and PC parts since 2024.</p><div class="flex space-x-3"><a href="#" class="social-icon w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg></a><a href="#" class="social-icon w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a></div></div>
                <div><h4 class="font-semibold text-lg mb-4">Quick Links</h4><ul class="space-y-2"><li><a href="#" onclick="showPage('home')" class="footer-link text-gray-400 hover:text-white text-sm block">Home</a></li><li><a href="#" onclick="showPage('shop')" class="footer-link text-gray-400 hover:text-white text-sm block">Shop</a></li><li><a href="#" onclick="showPage('orders')" class="footer-link text-gray-400 hover:text-white text-sm block">My Orders</a></li><li><a href="#" onclick="showPage('cart')" class="footer-link text-gray-400 hover:text-white text-sm block">Cart</a></li></ul></div>
                <div><h4 class="font-semibold text-lg mb-4">Customer Service</h4><ul class="space-y-2"><li><a href="#" class="footer-link text-gray-400 hover:text-white text-sm block">Contact Us</a></li><li><a href="#" class="footer-link text-gray-400 hover:text-white text-sm block">Returns Policy</a></li><li><a href="#" class="footer-link text-gray-400 hover:text-white text-sm block">Shipping Info</a></li><li><a href="#" class="footer-link text-gray-400 hover:text-white text-sm block">FAQs</a></li></ul></div>
                <div><h4 class="font-semibold text-lg mb-4">Contact Us</h4><ul class="space-y-3"><li class="flex items-center gap-3 text-gray-400 text-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>support@justrix.com</li><li class="flex items-center gap-3 text-gray-400 text-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>+63 (2) 1234 5678</li><li class="flex items-center gap-3 text-gray-400 text-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>Makati City, Philippines</li></ul></div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center"><p class="text-gray-400 text-sm">&copy; 2024 JUSTRIX. All rights reserved. | Premium Computer Parts Store</p></div>
        </div>
    </footer>

    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let allProducts = [];
        let currentCategory = '';
        let currentMinPrice = 0;
        let currentMaxPrice = 50000;

        function showProfileModal() { document.getElementById('profileModal').classList.remove('hidden'); document.getElementById('profileModal').classList.add('flex'); }
        function closeProfileModal() { document.getElementById('profileModal').classList.remove('flex'); document.getElementById('profileModal').classList.add('hidden'); }
        function showLogoutConfirm() { document.getElementById('logoutConfirmModal').classList.remove('hidden'); document.getElementById('logoutConfirmModal').classList.add('flex'); }
        function closeLogoutConfirm() { document.getElementById('logoutConfirmModal').classList.remove('flex'); document.getElementById('logoutConfirmModal').classList.add('hidden'); }
        function confirmLogout() { document.getElementById('logout-form').submit(); }
        function toggleMobileFilter() { document.getElementById('filterSidebar').classList.toggle('open'); document.getElementById('mobileFilterOverlay').classList.toggle('hidden'); }

        window.addEventListener('scroll', function() { document.getElementById('scrollTop').classList.toggle('hidden', window.scrollY <= 300); });

        function showPage(page) {
            document.querySelectorAll('.page-content').forEach(el => el.classList.add('hidden'));
            document.getElementById(`${page}Page`).classList.remove('hidden');
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('nav-active'));
            if (page === 'home') document.getElementById('navHome').classList.add('nav-active');
            if (page === 'shop') document.getElementById('navShop').classList.add('nav-active');
            if (page === 'orders') document.getElementById('navOrders').classList.add('nav-active');
            if (page === 'shop') loadProducts();
            if (page === 'orders') loadCustomerOrders();
            if (page === 'cart') updateCartPage();
            if (page === 'home') loadHomeCategories();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        async function loadHomeCategories() {
            try {
                const res = await fetch('/api/categories');
                let categories = await res.json();
                document.getElementById('homeCategories').innerHTML = categories.slice(0, 4).map(cat => `<div class="bg-white rounded-2xl shadow-sm p-6 text-center hover:shadow-md transition cursor-pointer group" onclick="filterByCategory(${cat.id}); showPage('shop')"><div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition"><svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div><h3 class="font-semibold text-gray-800">${escapeHtml(cat.name)}</h3><p class="text-sm text-gray-500 mt-1">${cat.products_count || 0} products</p></div>`).join('');
            } catch (error) { console.error('Error loading home categories:', error); }
        }

        async function loadCategories() {
            try {
                const res = await fetch('/api/categories');
                let categories = await res.json();
                const container = document.getElementById('categoriesList');
                container.innerHTML = `<button onclick="filterByCategory('')" class="category-filter-btn w-full text-left px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-100 transition">All Products</button>`;
                categories.forEach(cat => { container.innerHTML += `<button onclick="filterByCategory(${cat.id})" class="category-filter-btn w-full text-left px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-100 transition">${escapeHtml(cat.name)}</button>`; });
            } catch (error) { console.error('Error loading categories:', error); }
        }

        function filterByCategory(categoryId) { currentCategory = categoryId; loadProducts(); document.querySelectorAll('.category-filter-btn').forEach(btn => btn.classList.remove('category-active', 'text-white', 'bg-blue-600')); if(event.target) event.target.classList.add('category-active', 'text-white', 'bg-blue-600'); }

        function applyBudgetFilter() { const minPrice = document.getElementById('minPrice').value; const maxPrice = document.getElementById('maxPrice').value; currentMinPrice = minPrice ? parseInt(minPrice) : 0; currentMaxPrice = maxPrice ? parseInt(maxPrice) : 50000; loadProducts(); if(document.getElementById('filterSidebar').classList.contains('open')) toggleMobileFilter(); }

        function clearFilters() { document.getElementById('minPrice').value = ''; document.getElementById('maxPrice').value = ''; currentMinPrice = 0; currentMaxPrice = 50000; currentCategory = ''; loadProducts(); document.querySelectorAll('.category-filter-btn').forEach(btn => btn.classList.remove('category-active', 'text-white', 'bg-blue-600')); if(document.querySelector('.category-filter-btn')) document.querySelector('.category-filter-btn').classList.add('category-active', 'text-white', 'bg-blue-600'); if(document.getElementById('filterSidebar').classList.contains('open')) toggleMobileFilter(); }

        async function loadProducts() {
            try {
                const res = await fetch('/api/products');
                let products = await res.json();
                if (products.data) products = products.data;
                allProducts = products;
                let filtered = [...allProducts];
                if (currentCategory) filtered = filtered.filter(p => p.category_id == currentCategory);
                filtered = filtered.filter(p => p.price >= currentMinPrice && p.price <= currentMaxPrice);
                document.getElementById('productCount') && (document.getElementById('productCount').innerText = `${filtered.length} products`);
                const container = document.getElementById('productsGrid');
                if (filtered.length === 0) { container.innerHTML = '<div class="col-span-full text-center py-12 bg-white rounded-2xl"><svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><h3 class="text-lg font-medium text-gray-900 mb-1">No products found</h3><p class="text-gray-500">Try adjusting your filters</p></div>'; return; }
                container.innerHTML = filtered.map(product => `<div class="product-card bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group"><div class="relative h-48 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">${product.image ? `<img src="/storage/${product.image}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">` : `<div class="w-full h-full flex items-center justify-center"><svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`}${product.quantity < 10 && product.quantity > 0 ? `<div class="absolute top-3 right-3"><span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-1 rounded-full">Low Stock</span></div>` : ''}${product.quantity === 0 ? `<div class="absolute inset-0 bg-black/50 flex items-center justify-center"><span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Out of Stock</span></div>` : ''}</div><div class="p-4"><h3 class="font-bold text-gray-900 text-lg mb-1 truncate">${escapeHtml(product.name)}</h3><p class="text-2xl font-bold text-green-600 mb-2">₱${parseFloat(product.price).toLocaleString()}</p><div class="flex items-center justify-between text-sm mb-3"><span class="text-gray-500">Stock: ${product.quantity} units</span><span class="text-blue-600 text-xs font-medium">${product.category ? product.category.name : 'No Category'}</span></div><button onclick='addToCart(${JSON.stringify({...product, stock: product.quantity})})' class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl transition" ${product.quantity === 0 ? 'disabled' : ''}>Add to Cart</button></div></div>`).join('');
            } catch (error) { console.error('Error loading products:', error); }
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
                if (customerOrders.length === 0) { container.innerHTML = '<div class="text-center text-gray-500 py-12 bg-white rounded-2xl">No orders found. Start shopping!</div>'; return; }
                container.innerHTML = customerOrders.slice().reverse().map(order => `<div class="order-card bg-white rounded-xl p-5 shadow-sm border border-gray-100"><div class="flex justify-between items-start mb-3"><div><p class="font-semibold text-gray-900">${escapeHtml(order.product?.name || 'Product')}</p><p class="text-sm text-gray-500">Order #${order.id}</p></div><div class="text-right"><p class="font-bold text-green-600">₱${parseFloat(order.total_price).toLocaleString()}</p><p class="text-xs text-gray-500">${new Date(order.sold_at).toLocaleDateString()}</p></div></div><div class="grid grid-cols-3 gap-2 text-sm"><div><span class="text-gray-500">Qty:</span> ${order.quantity}</div><div><span class="text-gray-500">Payment:</span> ${order.payment_method}</div><div><span class="text-gray-500">Status:</span> <span class="text-green-600 font-medium">Completed</span></div></div><div class="mt-3 text-sm text-gray-600"><svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>${order.notes || 'Delivery to your address'}</div></div>`).join('');
            } catch (error) { console.error('Error loading orders:', error); }
        }

        function updateCartPage() {
            const container = document.getElementById('cartPageItems');
            if (cart.length === 0) { container.innerHTML = '<div class="text-center text-gray-500 py-12">Your cart is empty</div>'; return; }
            let total = 0;
            container.innerHTML = `<div class="divide-y">${cart.map(item => { const itemTotal = item.price * item.quantity; total += itemTotal; return `<div class="flex gap-4 p-4 items-center"><img src="${item.image ? '/storage/' + item.image : 'https://via.placeholder.com/80'}" class="w-20 h-20 object-cover rounded-lg"><div class="flex-1"><h4 class="font-semibold text-gray-800">${escapeHtml(item.name)}</h4><p class="text-green-600 font-bold">₱${parseFloat(item.price).toLocaleString()}</p><div class="flex items-center gap-2 mt-2"><button onclick="updateQuantity(${item.id}, -1)" class="w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300">-</button><span class="w-10 text-center">${item.quantity}</span><button onclick="updateQuantity(${item.id}, 1)" class="w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300">+</button><button onclick="removeFromCart(${item.id})" class="ml-4 text-red-500 hover:text-red-700">Remove</button></div></div><div class="text-right"><p class="font-bold text-gray-900">₱${itemTotal.toLocaleString()}</p></div></div>`; }).join('')}<div class="p-4 bg-gray-50 flex justify-between items-center"><span class="font-bold text-lg">Total:</span><span class="font-bold text-2xl text-green-600">₱${total.toLocaleString()}</span></div><div class="p-4"><button onclick="checkout()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition">Proceed to Checkout</button></div></div>`;
        }

        function saveCart() { localStorage.setItem('cart', JSON.stringify(cart)); updateCartUI(); updateCartPage(); }
        function updateCartUI() { const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0); document.getElementById('cartCount').innerText = totalItems; document.getElementById('mobileCartBadge').innerText = totalItems; document.getElementById('mobileCartBadge').classList.toggle('hidden', totalItems === 0); }
        function updateQuantity(productId, change) { const item = cart.find(i => i.id === productId); if (item) { const newQuantity = item.quantity + change; if (newQuantity <= 0) removeFromCart(productId); else if (newQuantity <= item.stock) { item.quantity = newQuantity; saveCart(); } else showToast('Not enough stock!', true); } }
        function addToCart(product) { const existing = cart.find(i => i.id === product.id); if (existing) { if (existing.quantity + 1 <= product.stock) { existing.quantity++; saveCart(); showToast(`${product.name} added to cart!`); } else showToast('Not enough stock!', true); } else { cart.push({ id: product.id, name: product.name, price: product.price, image: product.image, stock: product.quantity, quantity: 1 }); saveCart(); showToast(`${product.name} added to cart!`); } }
        function removeFromCart(productId) { cart = cart.filter(i => i.id !== productId); saveCart(); }
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
            cart = []; saveCart(); closeCheckoutModal(); showToast(`Order placed successfully! Thank you for shopping at JUSTRIX!`); loadProducts(); loadCustomerOrders(); document.getElementById('checkoutForm').reset(); showPage('orders');
        });

        function showToast(message, isError = false) { const toast = document.getElementById('toast'); toast.innerText = message; toast.classList.remove('hidden'); toast.className = `fixed bottom-24 right-5 py-3 px-5 rounded-xl text-sm z-[300] shadow-lg ${isError ? 'bg-red-600 text-white' : 'bg-green-600 text-white'}`; setTimeout(() => toast.classList.add('hidden'), 3000); }
        function escapeHtml(text) { if (!text) return ''; const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }

        const priceSlider = document.getElementById('priceSlider');
        if (priceSlider) priceSlider.addEventListener('input', function(e) { document.getElementById('maxPrice').value = e.target.value === '50000' ? '' : e.target.value; currentMaxPrice = parseInt(e.target.value); loadProducts(); });

        document.addEventListener('DOMContentLoaded', () => { loadCategories(); loadProducts(); loadHomeCategories(); updateCartUI(); loadCustomerOrders(); showPage('home'); });
    </script>
</body>
</html>