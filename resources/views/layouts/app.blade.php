<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JUSTRIX</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

            <!-- Search + Settings -->
            <div class="flex items-center gap-3">
                <input type="text" placeholder="Search"
                    class="px-3 py-1 rounded bg-gray-800 text-white text-sm outline-none hidden md:block">
                ⚙️
            </div>

        </div>

        <!-- 🔹 MENU ROW -->
        <div class="flex justify-center mt-4 space-x-8 text-sm">
            <a href="/dashboard" class="nav-link hover:text-blue-400 transition">Dashboard</a>
            <a href="/categories-ui" class="nav-link hover:text-blue-400 transition">Category</a>
            <a href="/products-ui" class="nav-link hover:text-blue-400 transition">Product</a>
            <a href="/suppliers-ui" class="nav-link hover:text-blue-400 transition">Supplier</a>
            <a href="#" class="nav-link hover:text-blue-400 transition">Purchases</a>
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
            
            // Get all nav links
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                // Remove active class from all links
                link.classList.remove('text-blue-400');
                link.classList.add('text-white');
                
                // Get the href attribute
                const href = link.getAttribute('href');
                
                // Check if this link matches the current URL
                if (href === currentUrl) {
                    link.classList.remove('text-white');
                    link.classList.add('text-blue-400');
                }
                
                // Special case for categories-ui
                if (currentUrl === '/categories-ui' && href === '/categories-ui') {
                    link.classList.remove('text-white');
                    link.classList.add('text-blue-400');
                }
                
                // Special case for products-ui
                if (currentUrl === '/products-ui' && href === '/products-ui') {
                    link.classList.remove('text-white');
                    link.classList.add('text-blue-400');
                }
                
                // Handle root/home page
                if ((currentUrl === '/' || currentUrl === '') && href === '/dashboard') {
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