<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JUSTRIX - Inventory Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-200">

    <!-- 🔹 TOP NAVBAR -->
    <nav class="bg-[#141720] text-white px-6 py-4">

        <!-- TOP ROW -->
        <div class="flex items-center justify-between">

            <!-- Logo with circular image -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('pictures/LOGO.png') }}"
                    alt="Logo"
                    class="w-15 h-10 rounded-full object-cover">
                <div class="text-xl font-bold">
                    JUSTRIX
                </div>
            </div>

            <!-- Search + Settings + Auth -->
            <div class="flex items-center gap-3">
                <input type="text" placeholder="Search"
                    class="px-3 py-1 rounded bg-gray-800 text-white text-sm outline-none hidden md:block">
                
                <!-- 🔹 LOGIN / REGISTER BUTTONS -->
                @guest
                    <a href="{{ route('login') }}" 
                       class="px-4 py-1.5 text-sm bg-blue-600 hover:bg-blue-700 rounded-lg transition text-white">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="px-4 py-1.5 text-sm bg-green-600 hover:bg-green-700 rounded-lg transition text-white">
                        Register
                    </a>
                @else
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-300">{{ Auth::user()->name }}</span>
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="px-4 py-1.5 text-sm bg-red-600 hover:bg-red-700 rounded-lg transition text-white">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                @endguest
                
                <span class="text-xl cursor-pointer hover:text-gray-300 transition">⚙️</span>
            </div>

        </div>


        <!-- 🔹 MENU ROW -->
        <div class="flex justify-center mt-4 space-x-8 text-sm">
            <a href="{{ route('dashboard') }}" class="nav-link hover:text-blue-400 transition">Dashboard</a>
            <a href="{{ route('categories-ui') }}" class="nav-link hover:text-blue-400 transition">Category</a>
            <a href="{{ route('products-ui') }}" class="nav-link hover:text-blue-400 transition">Product</a>
            <a href="{{ route('suppliers-ui') }}" class="nav-link hover:text-blue-400 transition">Supplier</a>
            <a href="{{ route('purchases-ui') }}" class="nav-link hover:text-blue-400 transition">Purchases</a>
            <a href="#" class="nav-link hover:text-blue-400 transition">Sales</a>
        </div>

    </nav>

    <!-- 🔹 PAGE CONTENT -->
    <div class="p-6">
        @yield('content')
    </div>

    <script>
        // Function to set active link based on current URL
        function setActiveLink() {
            const currentUrl = window.location.pathname;
            
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                link.classList.remove('text-blue-400');
                link.classList.add('text-white');
                
                const href = link.getAttribute('href');
                
                // Handle dashboard
                if ((currentUrl === '/dashboard' || currentUrl === '/dashboard/') && href === '{{ route('dashboard') }}') {
                    link.classList.remove('text-white');
                    link.classList.add('text-blue-400');
                }
                
                // Handle categories
                if (currentUrl === '/categories-ui' && href === '{{ route('categories-ui') }}') {
                    link.classList.remove('text-white');
                    link.classList.add('text-blue-400');
                }
                
                // Handle products
                if (currentUrl === '/products-ui' && href === '{{ route('products-ui') }}') {
                    link.classList.remove('text-white');
                    link.classList.add('text-blue-400');
                }
                
                // Handle suppliers
                if (currentUrl === '/suppliers-ui' && href === '{{ route('suppliers-ui') }}') {
                    link.classList.remove('text-white');
                    link.classList.add('text-blue-400');
                }
                
                // Handle purchases
                if (currentUrl === '/purchases-ui' && href === '{{ route('purchases-ui') }}') {
                    link.classList.remove('text-white');
                    link.classList.add('text-blue-400');
                }
            });
        }
        
        // Run when page loads
        document.addEventListener('DOMContentLoaded', setActiveLink);
        
        // Also run after any navigation (for better UX)
        window.addEventListener('popstate', setActiveLink);
    </script>

    <style>
        .transition {
            transition: all 0.3s ease;
        }
    </style>

</body>
</html>