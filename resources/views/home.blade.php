<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>JUSTRIX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .animate-fade-in {
            animation: fade-in 0.8s ease-out;
        }
        .animate-slide-in {
            animation: slide-in 0.6s ease-out;
        }
        .hero-section {
            background: linear-gradient(135deg, #1a1f2e 0%, #2d3748 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }
        .category-card {
            transition: all 0.3s ease;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .footer-link {
            transition: all 0.2s ease;
        }
        .footer-link:hover {
            color: #667eea;
            transform: translateX(5px);
        }
        .nav-link {
            transition: all 0.2s ease;
        }
        .nav-link:hover {
            color: #60a5fa;
        }
        /* Mobile menu styles */
        .mobile-menu {
            transition: transform 0.3s ease-in-out;
            transform: translateX(-100%);
        }
        .mobile-menu.open {
            transform: translateX(0);
        }
        .mobile-overlay {
            transition: opacity 0.3s ease-in-out;
        }
        /* Scroll to top button */
        .scroll-top-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 48px;
            height: 48px;
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
            z-index: 100;
        }
        .scroll-top-btn.show {
            opacity: 1;
            visibility: visible;
        }
        .scroll-top-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }
        /* Better touch targets for mobile */
        @media (max-width: 768px) {
            button, a, .category-card {
                cursor: pointer;
                -webkit-tap-highlight-color: transparent;
            }
            .scroll-top-btn {
                bottom: 70px;
                right: 16px;
                width: 44px;
                height: 44px;
            }
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Landing Page Navbar - Mobile Friendly -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 md:py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-2 md:space-x-3">
                    <img src="{{ asset('pictures/LOGO.png') }}" alt="JUSTRIX" class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover">
                    <span class="text-lg md:text-xl font-bold text-gray-800">JUSTRIX</span>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex space-x-6 lg:space-x-8">
                    <a href="#home" class="text-gray-700 hover:text-blue-600 transition">Home</a>
                    <a href="#categories" class="text-gray-700 hover:text-blue-600 transition">Categories</a>
                    <a href="#features" class="text-gray-700 hover:text-blue-600 transition">Features</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 transition">Contact</a>
                </div>
                
                <!-- Desktop Buttons -->
                <div class="hidden md:flex space-x-3">
                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Register</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Dashboard</a>
                    @endguest
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobileMenuToggle" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu Panel -->
        <div id="mobileMenu" class="mobile-menu fixed top-0 left-0 w-64 h-full bg-white shadow-xl z-50">
            <div class="p-4 border-b">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('pictures/LOGO.png') }}" alt="JUSTRIX" class="w-8 h-8 rounded-full">
                        <span class="font-bold text-gray-800">JUSTRIX</span>
                    </div>
                    <button id="closeMobileMenu" class="p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex flex-col p-4 space-y-3">
                <a href="#home" class="mobile-nav-link py-2 text-gray-700 hover:text-blue-600 transition">Home</a>
                <a href="#categories" class="mobile-nav-link py-2 text-gray-700 hover:text-blue-600 transition">Categories</a>
                <a href="#features" class="mobile-nav-link py-2 text-gray-700 hover:text-blue-600 transition">Features</a>
                <a href="#contact" class="mobile-nav-link py-2 text-gray-700 hover:text-blue-600 transition">Contact</a>
                <div class="border-t pt-3 mt-2">
                    @guest
                        <a href="{{ route('login') }}" class="block w-full text-center py-2 text-blue-600 border border-blue-600 rounded-lg mb-2">Login</a>
                        <a href="{{ route('register') }}" class="block w-full text-center py-2 bg-blue-600 text-white rounded-lg">Register</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block w-full text-center py-2 bg-blue-600 text-white rounded-lg">Dashboard</a>
                    @endguest
                </div>
            </div>
        </div>
        
        <!-- Mobile Overlay -->
        <div id="mobileOverlay" class="mobile-overlay fixed inset-0 bg-black/50 z-40 hidden"></div>
    </nav>

    <!-- Scroll to Top Button -->
    <button id="scrollTopBtn" class="scroll-top-btn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <!-- Hero Section - Mobile Responsive -->
    <section id="home" class="hero-section relative overflow-hidden">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 lg:py-28">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-3 md:mb-4 animate-fade-in">
                    Welcome to <span class="gradient-text">JUSTRIX</span>
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-gray-300 mb-6 md:mb-8 max-w-2xl mx-auto px-4 animate-slide-in">
                    Your trusted partner for quality products and exceptional service.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4">
                    @guest
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Get Started
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Go to Dashboard
                        </a>
                    @endguest
                    <a href="#categories" 
                       class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-700 hover:bg-gray-800 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        Explore Categories
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section - 3 cards per row on mobile -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        <div class="grid grid-cols-3 gap-2 sm:gap-4 md:gap-6">
            <div class="bg-white rounded-xl shadow-md p-3 sm:p-4 md:p-6 text-center hover:shadow-lg transition-all">
                <div class="text-xl sm:text-2xl md:text-4xl font-bold text-blue-600 mb-1 sm:mb-2" id="totalProducts">0</div>
                <div class="text-gray-600 font-medium text-xs sm:text-sm md:text-base">Total Products</div>
                <div class="text-xs text-gray-400 mt-1 hidden sm:block">Available in store</div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-3 sm:p-4 md:p-6 text-center hover:shadow-lg transition-all">
                <div class="text-xl sm:text-2xl md:text-4xl font-bold text-green-600 mb-1 sm:mb-2" id="totalCategories">0</div>
                <div class="text-gray-600 font-medium text-xs sm:text-sm md:text-base">Categories</div>
                <div class="text-xs text-gray-400 mt-1 hidden sm:block">Shop by category</div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-3 sm:p-4 md:p-6 text-center hover:shadow-lg transition-all">
                <div class="text-xl sm:text-2xl md:text-4xl font-bold text-purple-600 mb-1 sm:mb-2" id="totalSuppliers">0</div>
                <div class="text-gray-600 font-medium text-xs sm:text-sm md:text-base">Suppliers</div>
                <div class="text-xs text-gray-400 mt-1 hidden sm:block">Trusted partners</div>
            </div>
        </div>
    </div>

    <!-- Product Categories Section - 2 cards per row on mobile -->
    <section id="categories" class="bg-white py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 md:mb-4">Shop by Category</h2>
                <p class="text-gray-600 text-sm md:text-base max-w-2xl mx-auto">Browse our wide selection of products</p>
            </div>
            <div id="categoriesGrid" class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
                <div class="text-center py-8 text-gray-500 col-span-2 lg:col-span-4">Loading categories...</div>
            </div>
        </div>
    </section>

    <!-- Features Section - Mobile Responsive -->
    <section id="features" class="bg-gradient-to-r from-blue-50 to-indigo-50 py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 md:mb-4">Why Choose JUSTRIX?</h2>
                <p class="text-gray-600 text-sm md:text-base max-w-2xl mx-auto">Quality, reliability, and exceptional service</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
                <div class="bg-white rounded-xl p-4 md:p-6 text-center shadow-md hover:shadow-lg transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base md:text-xl font-semibold text-gray-900 mb-1 md:mb-2">Quality Products</h3>
                    <p class="text-gray-600 text-xs md:text-sm">Premium quality products from trusted brands</p>
                </div>
                <div class="bg-white rounded-xl p-4 md:p-6 text-center shadow-md hover:shadow-lg transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base md:text-xl font-semibold text-gray-900 mb-1 md:mb-2">Best Prices</h3>
                    <p class="text-gray-600 text-xs md:text-sm">Competitive pricing and great deals</p>
                </div>
                <div class="bg-white rounded-xl p-4 md:p-6 text-center shadow-md hover:shadow-lg transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <h3 class="text-base md:text-xl font-semibold text-gray-900 mb-1 md:mb-2">Fast Delivery</h3>
                    <p class="text-gray-600 text-xs md:text-sm">Quick and reliable shipping</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section - Mobile Responsive -->
    <div class="bg-gray-900 py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3 md:mb-4">Ready to Get Started?</h2>
            <p class="text-gray-400 mb-6 md:mb-8 max-w-2xl mx-auto text-sm md:text-base">Join thousands of satisfied customers who trust JUSTRIX</p>
            @guest
                <a href="{{ route('register') }}"
                   class="inline-flex items-center px-5 md:px-6 py-2.5 md:py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Create Account
                </a>
            @else
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center px-5 md:px-6 py-2.5 md:py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105">
                    Go to Dashboard
                </a>
            @endguest
        </div>
    </div>

    <!-- Footer - Mobile Responsive -->
    <footer id="contact" class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                <div>
                    <h3 class="text-lg md:text-xl font-bold mb-3 md:mb-4">JUSTRIX</h3>
                    <p class="text-gray-400 text-xs md:text-sm">Your trusted partner for quality products and exceptional service.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-3 md:mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="footer-link text-gray-400 hover:text-white text-sm block">Home</a></li>
                        <li><a href="#categories" class="footer-link text-gray-400 hover:text-white text-sm block">Categories</a></li>
                        <li><a href="#features" class="footer-link text-gray-400 hover:text-white text-sm block">Features</a></li>
                        <li><a href="#contact" class="footer-link text-gray-400 hover:text-white text-sm block">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3 md:mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white text-sm block">Help Center</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white text-sm block">Privacy Policy</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white text-sm block">Terms of Service</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white text-sm block">Returns Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3 md:mb-4">Contact Us</h4>
                    <ul class="space-y-2">
                        <li class="text-gray-400 text-xs md:text-sm flex items-center">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="break-all">info@justrix.com</span>
                        </li>
                        <li class="text-gray-400 text-xs md:text-sm flex items-center">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>+63 (2) 1234 5678</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-6 md:mt-8 pt-6 md:pt-8 text-center">
                <p class="text-gray-400 text-xs md:text-sm">&copy; 2026 JUSTRIX. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Scroll to Top Button functionality
        const scrollTopBtn = document.getElementById('scrollTopBtn');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollTopBtn.classList.add('show');
            } else {
                scrollTopBtn.classList.remove('show');
            }
        });
        
        // Mobile menu functionality
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        function openMobileMenu() {
            mobileMenu.classList.add('open');
            mobileOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileMenuFunc() {
            mobileMenu.classList.remove('open');
            mobileOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', openMobileMenu);
        }
        if (closeMobileMenu) {
            closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
        }
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', closeMobileMenuFunc);
        }
        
        // Close mobile menu when clicking nav links
        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', closeMobileMenuFunc);
        });
        
        // Load statistics
        async function loadStats() {
            try {
                const [productsRes, categoriesRes, suppliersRes] = await Promise.all([
                    fetch('/api/products'),
                    fetch('/api/categories'),
                    fetch('/api/suppliers')
                ]);
                
                let products = await productsRes.json();
                let categories = await categoriesRes.json();
                let suppliers = await suppliersRes.json();
                
                if (products.data) products = products.data;
                
                document.getElementById('totalProducts').innerText = products.length || 0;
                document.getElementById('totalCategories').innerText = categories.length || 0;
                document.getElementById('totalSuppliers').innerText = suppliers.length || 0;
                
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }
        
        // Load categories
        async function loadCategories() {
            try {
                const res = await fetch('/api/categories');
                let categories = await res.json();
                
                const container = document.getElementById('categoriesGrid');
                
                if (!categories || categories.length === 0) {
                    container.innerHTML = '<div class="col-span-2 lg:col-span-4 text-center text-gray-500 py-8">No categories available</div>';
                    return;
                }
                
                container.innerHTML = categories.map(category => `
                    <div class="category-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl cursor-pointer">
                        <div class="h-28 sm:h-32 md:h-40 overflow-hidden bg-gray-200">
                            ${category.image 
                                ? `<img src="/storage/${category.image}" class="w-full h-full object-cover">`
                                : `<div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-purple-500">
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                   </div>`
                            }
                        </div>
                        <div class="p-2 sm:p-3 md:p-4 text-center">
                            <h3 class="font-semibold text-gray-900 text-xs sm:text-sm md:text-base mb-1 truncate">${escapeHtml(category.name)}</h3>
                            <p class="text-xs sm:text-sm text-gray-500">${category.products_count || 0} products</p>
                            @auth
                                <a href="{{ route('products-ui') }}?category=${category.id}" class="inline-block mt-1 sm:mt-2 md:mt-3 text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">Browse →</a>
                            @else
                                <a href="{{ route('login') }}" class="inline-block mt-1 sm:mt-2 md:mt-3 text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">Login to Browse →</a>
                            @endauth
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error loading categories:', error);
                document.getElementById('categoriesGrid').innerHTML = '<div class="col-span-2 lg:col-span-4 text-center text-gray-500 py-8">Error loading categories</div>';
            }
        }
        
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadStats();
            loadCategories();
        });
    </script>
</body>
</html>