<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUSTRIX - Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">

    <!-- Customer Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('pictures/LOGO.png') }}" alt="JUSTRIX" class="w-10 h-10 rounded-full object-cover">
                    <span class="text-xl font-bold text-gray-800">JUSTRIX</span>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('shop') }}" class="text-gray-700 hover:text-blue-600 transition">Shop</a>
                    <a href="{{ route('cart') }}" class="text-gray-700 hover:text-blue-600 transition">Cart</a>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="text-red-600 hover:text-red-700 transition">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Welcome to JUSTRIX Shop!</h1>
            <p class="text-xl">Find the best products at the best prices</p>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Our Products</h2>
        <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div class="text-center py-8 text-gray-500 col-span-4">Loading products...</div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 JUSTRIX. All rights reserved.</p>
        </div>
    </footer>

    <script>
        async function loadProducts() {
            try {
                const res = await fetch('/api/products');
                let products = await res.json();
                if (products.data) products = products.data;
                
                const container = document.getElementById('productsGrid');
                
                if (products.length === 0) {
                    container.innerHTML = '<div class="col-span-4 text-center text-gray-500 py-8">No products available</div>';
                    return;
                }
                
                container.innerHTML = products.map(product => `
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="h-48 overflow-hidden bg-gray-200">
                            ${product.image 
                                ? `<img src="/storage/${product.image}" class="w-full h-full object-cover">`
                                : `<div class="w-full h-full flex items-center justify-center bg-gray-300">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                   </div>`
                            }
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 mb-1">${escapeHtml(product.name)}</h3>
                            <p class="text-green-600 font-bold text-xl mb-2">₱${parseFloat(product.price).toLocaleString()}</p>
                            <p class="text-gray-500 text-sm mb-3">Stock: ${product.quantity} units</p>
                            <button onclick="addToCart(${product.id})" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error loading products:', error);
            }
        }
        
        function addToCart(productId) {
            alert('Product added to cart! (Feature coming soon)');
        }
        
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        document.addEventListener('DOMContentLoaded', loadProducts);
    </script>
</body>
</html>