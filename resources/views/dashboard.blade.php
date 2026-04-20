@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')

<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-6 md:p-8 text-white">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold mb-2">Welcome back, 
                    @auth
                        {{ Auth::user()->name }}
                    @endauth
                </h2>
                <p class="text-blue-100">Here's what's happening with your inventory today.</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-20 h-20 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Inventory</span>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1" id="dashProducts">0</div>
                <p class="text-sm text-gray-500">Total Products</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
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
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-50 rounded-xl group-hover:bg-green-100 transition">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">Revenue</span>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1" id="totalRevenue">₱0</div>
                <p class="text-sm text-gray-500">Total Sales Revenue</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
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
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 rounded-xl group-hover:bg-purple-100 transition">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Orders</span>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1" id="totalOrders">0</div>
                <p class="text-sm text-gray-500">Total Orders</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
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
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-amber-50 rounded-xl group-hover:bg-amber-100 transition">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Partners</span>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1" id="dashSuppliers">0</div>
                <p class="text-sm text-gray-500">Active Suppliers</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">With Products</span>
                        <span class="text-amber-600 font-semibold" id="suppliersWithProducts">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stocks by Category - Vertical Bar Chart -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Stocks by Category
            </h3>
            <div class="text-xs text-gray-400">Total units in stock per category</div>
        </div>
        <div id="stocksChartContainer" class="overflow-x-auto">
            <div id="stocksChart" class="min-w-[600px]">
                <div class="text-center text-gray-500 py-8">Loading chart data...</div>
            </div>
        </div>
    </div>

    <!-- Sales by Category & Quick Actions Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales by Category -->
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Sales by Category
            </h3>
            <div id="categoryChart" class="space-y-4">
                <div class="text-center text-gray-500 py-8">Loading chart data...</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Quick Actions
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {{-- ALL USERS --}}
                <a href="{{ route('products-ui') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Add New Product</span>
                    </div>
                </a>

                <a href="{{ route('sales-ui') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-green-50 transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">New Sale</span>
                    </div>
                </a>

                {{-- ADMIN + MANAGER --}}
                @if(in_array(auth()->user()->role, ['admin','manager']))
                    <a href="{{ route('purchases-ui') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-amber-50 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-amber-200 transition">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Create Purchase Order</span>
                        </div>
                    </a>
                @endif

                {{-- ADMIN ONLY --}}
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('suppliers-ui') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-purple-50 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Add New Supplier</span>
                        </div>
                    </a>
                @endif

            </div>
        </div>
    </div>

    <!-- Recent Sales Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-[#1A1D2E] to-[#2D3047] px-6 py-4">
            <h3 class="text-white font-semibold">Recent Sales</h3>
            <p class="text-gray-300 text-sm mt-0.5">Latest transactions</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Qty</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody id="recentSalesTable">
                    <tr><td colspan="5" class="text-center py-8 text-gray-500">Loading sales...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
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
            
            // Load stocks by category - Vertical Bar Chart
            await loadStocksByCategory(products, categories);
            
            // Load sales by category
            await loadCategoryChart();
            
            // Load recent sales table
            if (Array.isArray(sales)) {
                updateRecentSalesTable(sales.slice(0, 10));
            } else {
                updateRecentSalesTable([]);
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
            
            // Create vertical bar chart HTML
            let html = `
                <div class="flex items-end justify-around" style="min-height: 300px;">
            `;
            
            categoriesList.forEach((category, index) => {
                const stock = stocks[index];
                const percentage = (stock / maxStock) * 100;
                const barHeight = Math.max(percentage, 5); // Minimum 5% height for visibility
                const color = colors[index % colors.length];
                
                html += `
                    <div class="flex flex-col items-center text-center" style="width: 80px;">
                        <div class="relative group">
                            <div class="w-12 bg-gray-200 rounded-t-lg overflow-hidden" style="height: 200px;">
                                <div class="w-full rounded-t-lg transition-all duration-500" style="height: ${barHeight}%; background-color: ${color};"></div>
                            </div>
                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                                ${stock} units
                            </div>
                        </div>
                        <div class="mt-2 text-xs font-medium text-gray-600 truncate max-w-[80px]" title="${escapeHtml(category)}">
                            ${escapeHtml(category.length > 12 ? category.substring(0, 10) + '...' : category)}
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
                container.innerHTML = '<div class="text-center text-gray-500 py-8">No sales data yet. Complete some sales to see charts.</div>';
                return;
            }
            
            let html = '';
            const total = Object.values(categoryData).reduce((sum, d) => sum + (parseFloat(d.total) || 0), 0);
            
            if (total === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-8">No sales data yet. Complete some sales to see charts.</div>';
                return;
            }
            
            const colors = ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4'];
            let colorIndex = 0;
            
            for (const [category, data] of Object.entries(categoryData)) {
                const percentage = (parseFloat(data.total) / total) * 100;
                const color = colors[colorIndex % colors.length];
                html += `
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">${escapeHtml(category)}</span>
                            <span class="text-gray-500">₱${parseFloat(data.total).toLocaleString()}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="rounded-full h-2.5 transition-all duration-500" style="width: ${percentage}%; background-color: ${color}"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
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
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-gray-500">No sales recorded yet. Create your first sale!</td></tr>';
            return;
        }
        
        tbody.innerHTML = sales.map(sale => `
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="px-4 py-3 text-sm text-gray-600">${new Date(sale.sold_at).toLocaleDateString()}</td>
                <td class="px-4 py-3">
                    <div>
                        <p class="font-medium text-gray-900">${escapeHtml(sale.product?.name || 'Unknown Product')}</p>
                        <p class="text-xs text-gray-500">${sale.product?.category?.name || 'No Category'}</p>
                    </div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">${sale.customer_name ? escapeHtml(sale.customer_name) : 'Walk-in Customer'}</td>
                <td class="px-4 py-3 text-right text-sm text-gray-600">${sale.quantity}</td>
                <td class="px-4 py-3 text-right font-semibold text-green-600">₱${parseFloat(sale.total_price).toLocaleString()}</td>
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