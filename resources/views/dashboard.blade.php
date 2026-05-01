@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')

<div class="space-y-4 sm:space-y-6 md:space-y-8">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 md:p-8 text-white">
        <div class="flex flex-col md:flex-row items-center justify-between gap-3 sm:gap-4">
            <div class="text-center md:text-left">
                <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Welcome back, 
                    @auth
                        {{ Auth::user()->name }}
                    @endauth
                </h2>
                <p class="text-blue-100 text-xs sm:text-sm md:text-base">Here's what's happening with your inventory today.</p>
            </div>
            <div class="hidden lg:block">
                <svg class="w-16 h-16 md:w-20 md:h-20 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Grid - Responsive: 1 col mobile, 2 cols tablet, 4 cols desktop -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
        <!-- Total Products Card -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-4 sm:p-5 md:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="p-2 sm:p-3 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] sm:text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Inventory</span>
                </div>
                <div class="text-right">
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1" id="dashProducts">0</div>
                </div>
                <p class="text-xs sm:text-sm text-gray-500">Total Products</p>
                <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">In Stock</span>
                        <span class="text-green-600 font-semibold" id="inStockCount">0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs mt-2">
                        <span class="text-gray-500">Low Stock</span>
                        <span class="text-amber-600 font-semibold" id="lowStockCount">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Revenue Card -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-4 sm:p-5 md:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="p-2 sm:p-3 bg-green-50 rounded-xl group-hover:bg-green-100 transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] sm:text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">Revenue</span>
                </div>
                <div class="text-right">
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1" id="totalRevenue">₱0</div>
                </div>
                <p class="text-xs sm:text-sm text-gray-500">Total Sales Revenue</p>
                <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">Today</span>
                        <span class="text-green-600 font-semibold" id="todayRevenue">₱0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs mt-2">
                        <span class="text-gray-500">This Month</span>
                        <span class="text-blue-600 font-semibold" id="monthRevenue">₱0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-4 sm:p-5 md:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="p-2 sm:p-3 bg-purple-50 rounded-xl group-hover:bg-purple-100 transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] sm:text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Orders</span>
                </div>
                <div class="text-right">
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1" id="totalOrders">0</div>
                </div>
                <p class="text-xs sm:text-sm text-gray-500">Total Orders</p>
                <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">Today</span>
                        <span class="text-purple-600 font-semibold" id="todayOrders">0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs mt-2">
                        <span class="text-gray-500">Average Order</span>
                        <span class="text-green-600 font-semibold" id="avgOrderValue">₱0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suppliers Card -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-4 sm:p-5 md:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="p-2 sm:p-3 bg-amber-50 rounded-xl group-hover:bg-amber-100 transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] sm:text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Partners</span>
                </div>
                <div class="text-right">
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1" id="dashSuppliers">0</div>
                </div>
                <p class="text-xs sm:text-sm text-gray-500">Active Suppliers</p>
                <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">With Products</span>
                        <span class="text-amber-600 font-semibold" id="suppliersWithProducts">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stocks by Category - Vertical Bar Chart (Mobile Friendly) -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 mb-4 sm:mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Stocks by Category
            </h3>
            <div class="text-[10px] sm:text-xs text-gray-400">Total units in stock per category</div>
        </div>
        <div id="stocksChartContainer" class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0">
            <div id="stocksChart" class="min-w-[500px] sm:min-w-[600px]">
                <div class="text-center text-gray-500 py-8">Loading chart data...</div>
            </div>
        </div>
    </div>

    <!-- Sales by Category & Quick Actions Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Sales by Category -->
        <div class="lg:col-span-1 bg-white rounded-xl sm:rounded-2xl shadow-sm p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Sales by Category
            </h3>
            <div id="categoryChart" class="space-y-3 sm:space-y-4">
                <div class="text-center text-gray-500 py-8">Loading chart data...</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="lg:col-span-2 bg-white rounded-xl sm:rounded-2xl shadow-sm p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Quick Actions
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                {{-- ALL USERS --}}
                <a href="{{ route('products-ui') }}" class="flex items-center justify-between p-2.5 sm:p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition group">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">Add New Product</span>
                    </div>
                </a>

                <a href="{{ route('sales-ui') }}" class="flex items-center justify-between p-2.5 sm:p-3 bg-gray-50 rounded-xl hover:bg-green-50 transition group">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">New Sale</span>
                    </div>
                </a>

                {{-- ADMIN + MANAGER --}}
                @if(in_array(auth()->user()->role, ['admin','manager']))
                    <a href="{{ route('purchases-ui') }}" class="flex items-center justify-between p-2.5 sm:p-3 bg-gray-50 rounded-xl hover:bg-amber-50 transition group">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-amber-200 transition">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"></path>
                                </svg>
                            </div>
                            <span class="text-xs sm:text-sm font-medium text-gray-700">Create Purchase Order</span>
                        </div>
                    </a>
                @endif

                {{-- ADMIN + MANAGER --}}
                @if(in_array(auth()->user()->role, ['admin','manager']))
                    <a href="{{ route('categories-ui') }}" class="flex items-center justify-between p-2.5 sm:p-3 bg-gray-50 rounded-xl hover:bg-sky-50 transition group">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-sky-100 rounded-lg flex items-center justify-center group-hover:bg-sky-200 transition">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </div>
                            <span class="text-xs sm:text-sm font-medium text-gray-700">Manage Categories</span>
                        </div>
                    </a>

                    <a href="{{ route('suppliers-ui') }}" class="flex items-center justify-between p-2.5 sm:p-3 bg-gray-50 rounded-xl hover:bg-purple-50 transition group">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs sm:text-sm font-medium text-gray-700">Add New Supplier</span>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Sales Table - Mobile Friendly -->
    <!-- Recent Sales Table with Pagination -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-sm overflow-hidden">
    <div class="bg-gradient-to-r from-[#1A1D2E] to-[#2D3047] px-4 sm:px-6 py-3 sm:py-4">
        <h3 class="text-white font-semibold text-base sm:text-lg">Recent Sales</h3>
        <p class="text-gray-300 text-xs sm:text-sm mt-0.5">Latest transactions</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[500px] sm:min-w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-500 uppercase">Product</th>
                    <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="px-3 sm:px-4 py-2 sm:py-3 text-right text-[10px] sm:text-xs font-semibold text-gray-500 uppercase">Qty</th>
                    <th class="px-3 sm:px-4 py-2 sm:py-3 text-right text-[10px] sm:text-xs font-semibold text-gray-500 uppercase">Total</th>
                </tr>
            </thead>
            <tbody id="recentSalesTable">
                <tr><td colspan="5" class="text-center py-8 text-gray-500 text-sm">Loading sales...</td></tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Controls -->
    <div id="salesPagination" class="border-t border-gray-100 px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-xs text-gray-500" id="paginationInfo">
            Showing 0 to 0 of 0 entries
        </div>
        <div class="flex gap-2">
            <button id="prevPageBtn" onclick="changeSalesPage(-1)" disabled class="px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
                Previous
            </button>
            <div class="flex gap-1" id="pageNumbers"></div>
            <button id="nextPageBtn" onclick="changeSalesPage(1)" disabled class="px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
                Next
            </button>
        </div>
    </div>
</div>

<script>
    // Sales pagination variables
    let allSalesData = [];
    let currentSalesPage = 1;
    let salesPerPage = 5;
    
    function updateSalesTableWithPagination() {
        const startIndex = (currentSalesPage - 1) * salesPerPage;
        const endIndex = startIndex + salesPerPage;
        const paginatedSales = allSalesData.slice(startIndex, endIndex);
        const totalPages = Math.ceil(allSalesData.length / salesPerPage);
        
        // Update table
        updateRecentSalesTable(paginatedSales);
        
        // Update pagination info
        const start = allSalesData.length === 0 ? 0 : startIndex + 1;
        const end = Math.min(endIndex, allSalesData.length);
        document.getElementById('paginationInfo').innerHTML = `Showing ${start} to ${end} of ${allSalesData.length} entries`;
        
        // Update buttons
        document.getElementById('prevPageBtn').disabled = currentSalesPage === 1;
        document.getElementById('nextPageBtn').disabled = currentSalesPage === totalPages || totalPages === 0;
        
        // Update page numbers
        const pageNumbersContainer = document.getElementById('pageNumbers');
        pageNumbersContainer.innerHTML = '';
        
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            btn.onclick = () => goToSalesPage(i);
            btn.className = `px-3 py-1.5 text-xs font-medium rounded-lg border transition ${
                i === currentSalesPage 
                    ? 'bg-blue-600 text-white border-blue-600' 
                    : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'
            }`;
            pageNumbersContainer.appendChild(btn);
        }
    }
    
    function changeSalesPage(direction) {
        const newPage = currentSalesPage + direction;
        if (newPage >= 1 && newPage <= Math.ceil(allSalesData.length / salesPerPage)) {
            currentSalesPage = newPage;
            updateSalesTableWithPagination();
        }
    }
    
    function goToSalesPage(page) {
        currentSalesPage = page;
        updateSalesTableWithPagination();
    }
    
    // Modified updateRecentSalesTable function
    function updateRecentSalesTable(sales) {
        const tbody = document.getElementById('recentSalesTable');
        
        if (!sales || sales.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-6 sm:py-8 text-gray-500 text-sm">No sales recorded yet. Create your first sale!</td></tr>';
            return;
        }
        
        tbody.innerHTML = sales.map(sale => `
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="px-3 sm:px-4 py-2 sm:py-3 text-[11px] sm:text-sm text-gray-600">${new Date(sale.sold_at).toLocaleDateString()}</td>
                <td class="px-3 sm:px-4 py-2 sm:py-3">
                    <div>
                        <p class="font-medium text-gray-900 text-xs sm:text-sm">${escapeHtml(sale.product?.name || 'Unknown Product')}</p>
                        <p class="text-[10px] sm:text-xs text-gray-500 hidden sm:block">${sale.product?.category?.name || 'No Category'}</p>
                    </div>
                </td>
                <td class="px-3 sm:px-4 py-2 sm:py-3 text-[11px] sm:text-sm text-gray-600">${sale.customer_name ? escapeHtml(sale.customer_name) : 'Walk-in Customer'}</td>
                <td class="px-3 sm:px-4 py-2 sm:py-3 text-right text-[11px] sm:text-sm text-gray-600">${sale.quantity}</td>
                <td class="px-3 sm:px-4 py-2 sm:py-3 text-right font-semibold text-green-600 text-[11px] sm:text-sm">₱${parseFloat(sale.total_price).toLocaleString()}</td>
            </tr>
        `).join('');
    }
    
    // Update the loadDashboardStats function to store all sales data
    async function loadDashboardStats() {
        try {
            const [productsRes, categoriesRes, suppliersRes, salesRes, statsRes] = await Promise.all([
                fetch('/api/products'),
                fetch('/api/categories'),
                fetch('/api/suppliers'),
                fetch('/api/sales'),
                fetch('/api/sales/stats')
            ]);
            
            let products = await productsRes.json();
            let categories = await categoriesRes.json();
            let suppliers = await suppliersRes.json();
            let sales = await salesRes.json();
            let stats = await statsRes.json();
            
            if (products.data) products = products.data;
            
            const totalProducts = products.length || 0;
            const totalSuppliers = suppliers.length || 0;
            const lowStockCount = products.filter(p => p.quantity > 0 && p.quantity < 10).length;
            const inStockCount = products.filter(p => p.quantity > 0).length;
            const suppliersWithProducts = suppliers.filter(s => s.products_offered && s.products_offered.length > 0).length;
            
            document.getElementById('dashProducts').innerText = totalProducts;
            document.getElementById('dashSuppliers').innerText = totalSuppliers;
            document.getElementById('inStockCount').innerText = inStockCount;
            document.getElementById('lowStockCount').innerText = lowStockCount;
            document.getElementById('suppliersWithProducts').innerText = suppliersWithProducts;
            
            // Sales stats
            document.getElementById('totalRevenue').innerText = '₱' + parseFloat(stats.total_sales || 0).toLocaleString();
            document.getElementById('todayRevenue').innerText = '₱' + parseFloat(stats.today_sales || 0).toLocaleString();
            document.getElementById('monthRevenue').innerText = '₱' + parseFloat(stats.this_month_sales || 0).toLocaleString();
            document.getElementById('totalOrders').innerText = stats.total_orders || 0;
            
            // Calculate today's orders
            const todayOrders = Array.isArray(sales) ? sales.filter(s => {
                const saleDate = new Date(s.sold_at).toDateString();
                const today = new Date().toDateString();
                return saleDate === today;
            }).length : 0;
            document.getElementById('todayOrders').innerText = todayOrders;
            
            document.getElementById('avgOrderValue').innerText = '₱' + parseFloat(stats.average_order_value || 0).toLocaleString();
            
            // Load stocks by category
            await loadStocksByCategory(products, categories);
            
            // Load sales by category
            await loadCategoryChart();
            
            // Load recent sales with pagination
            if (Array.isArray(sales)) {
                // Sort by date descending (newest first)
                allSalesData = sales.sort((a, b) => new Date(b.sold_at) - new Date(a.sold_at));
                currentSalesPage = 1;
                updateSalesTableWithPagination();
            } else {
                allSalesData = [];
                updateSalesTableWithPagination();
            }
            
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }
    
    async function loadStocksByCategory(products, categories) {
        try {
            const container = document.getElementById('stocksChart');
            
            // Calculate stock per category
            const stockByCategory = {};
            
            // Initialize all categories with 0
            categories.forEach(cat => {
                stockByCategory[cat.name] = 0;
            });
            
            // Add stock from products
            products.forEach(product => {
                const categoryName = product.category?.name || 'Uncategorized';
                if (!stockByCategory[categoryName]) {
                    stockByCategory[categoryName] = 0;
                }
                stockByCategory[categoryName] += product.quantity;
            });
            
            const categoriesList = Object.keys(stockByCategory);
            const stocks = Object.values(stockByCategory);
            const maxStock = Math.max(...stocks, 1);
            
            if (categoriesList.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-8">No data available</div>';
                return;
            }
            
            // Color palette for bars
            const colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4', '#ec4899', '#14b8a6', '#f97316', '#6366f1'];
            
            // Create vertical bar chart HTML - FIXED VERSION
            let html = `
                <div class="flex items-end justify-around gap-2 sm:gap-4" style="min-height: 300px;">
            `;
            
            categoriesList.forEach((category, index) => {
                const stock = stocks[index];
                const percentage = (stock / maxStock) * 100;
                const barHeight = Math.max(percentage, 5); // Minimum 5% height for visibility
                const color = colors[index % colors.length];
                
                html += `
                    <div class="flex flex-col items-center text-center" style="width: 70px; flex-shrink: 0;">
                        <div class="relative" style="height: 200px; width: 40px;">
                            <div class="absolute bottom-0 left-0 right-0 bg-gray-200 rounded-t-lg overflow-hidden" style="height: 200px; width: 100%;">
                                <div class="w-full rounded-t-lg transition-all duration-500 absolute bottom-0" style="height: ${barHeight}%; background-color: ${color};"></div>
                            </div>
                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">
                                ${stock} units
                            </div>
                        </div>
                        <div class="mt-2 text-xs font-medium text-gray-600 truncate max-w-[70px]" title="${escapeHtml(category)}">
                            ${escapeHtml(category.length > 10 ? category.substring(0, 8) + '...' : category)}
                        </div>
                        <div class="text-xs text-gray-400 mt-1">${stock}</div>
                    </div>
                `;
            });
            
            html += `</div>`;
            container.innerHTML = html;
            
        } catch (error) {
            console.error('Error loading stocks chart:', error);
            document.getElementById('stocksChart').innerHTML = '<div class="text-center text-red-500 py-8">Error loading chart data</div>';
        }
    }

    async function loadCategoryChart() {
        try {
            const res = await fetch('/api/sales/category');
            const categoryData = await res.json();
            const container = document.getElementById('categoryChart');
            
            if (!categoryData || Object.keys(categoryData).length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-6 sm:py-8">No sales data yet. Complete some sales to see charts.</div>';
                return;
            }
            
            let html = '';
            const total = Object.values(categoryData).reduce((sum, d) => sum + (parseFloat(d.total) || 0), 0);
            
            if (total === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-6 sm:py-8">No sales data yet. Complete some sales to see charts.</div>';
                return;
            }
            
            const colors = ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4'];
            let colorIndex = 0;
            
            for (const [category, data] of Object.entries(categoryData)) {
                const percentage = (parseFloat(data.total) / total) * 100;
                const color = colors[colorIndex % colors.length];
                html += `
                    <div class="mb-3 sm:mb-4">
                        <div class="flex justify-between text-xs sm:text-sm mb-1">
                            <span class="font-medium text-gray-700 truncate max-w-[120px] sm:max-w-none">${escapeHtml(category)}</span>
                            <span class="text-gray-500 text-[10px] sm:text-xs">₱${parseFloat(data.total).toLocaleString()}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 sm:h-2.5">
                            <div class="rounded-full h-2 sm:h-2.5 transition-all duration-500" style="width: ${percentage}%; background-color: ${color}"></div>
                        </div>
                        <div class="flex justify-between text-[10px] sm:text-xs text-gray-400 mt-1">
                            <span>${data.quantity} items sold</span>
                            <span>${data.count} orders</span>
                        </div>
                    </div>
                `;
                colorIndex++;
            }
            container.innerHTML = html;
            
        } catch (error) {
            console.error('Error loading category chart:', error);
            document.getElementById('categoryChart').innerHTML = '<div class="text-center text-red-500 py-8">Error loading chart data</div>';
        }
    }

    function updateRecentSalesTable(sales) {
        const tbody = document.getElementById('recentSalesTable');
        
        if (!sales || sales.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-6 sm:py-8 text-gray-500 text-sm">No sales recorded yet. Create your first sale!</td></tr>';
            return;
        }
        
        tbody.innerHTML = sales.map(sale => `
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="px-3 sm:px-4 py-2 sm:py-3 text-[11px] sm:text-sm text-gray-600">${new Date(sale.sold_at).toLocaleDateString()}</td>
                <td class="px-3 sm:px-4 py-2 sm:py-3">
                    <div>
                        <p class="font-medium text-gray-900 text-xs sm:text-sm">${escapeHtml(sale.product?.name || 'Unknown Product')}</p>
                        <p class="text-[10px] sm:text-xs text-gray-500 hidden sm:block">${sale.product?.category?.name || 'No Category'}</p>
                    </div>
                </td>
                <td class="px-3 sm:px-4 py-2 sm:py-3 text-[11px] sm:text-sm text-gray-600">${sale.customer_name ? escapeHtml(sale.customer_name) : 'Walk-in Customer'}</td>
                <td class="px-3 sm:px-4 py-2 sm:py-3 text-right text-[11px] sm:text-sm text-gray-600">${sale.quantity}</td>
                <td class="px-3 sm:px-4 py-2 sm:py-3 text-right font-semibold text-green-600 text-[11px] sm:text-sm">₱${parseFloat(sale.total_price).toLocaleString()}</td>
            </tr>
        `).join('');
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadDashboardStats();
    });
</script>
@endsection