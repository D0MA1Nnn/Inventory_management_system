@extends('layouts.app')

@section('title', 'Sales')
@section('content')

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"></h1>
        </div>
        <button onclick="openSaleModal()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-lg transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Sale
        </button>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <x-summary-card label="Total Sales" id="totalSalesCount" value="0" accent="gray" />
        <x-summary-card label="Revenue" id="revenueTotal" value="₱0" accent="green" />
        <x-summary-card label="Orders Today" id="ordersToday" value="0" accent="blue" />
        <x-summary-card label="Completed Orders" id="completedOrders" value="0" accent="amber" />
    </div>

    <div class="tab-surface">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 rounded-lg bg-gray-100 p-1" role="tablist" aria-label="Sales tabs">
            <x-tab-button active="true" tab="all" onclick="setSalesTab('all')">All Sales</x-tab-button>
            <x-tab-button tab="pending" onclick="setSalesTab('pending')">Pending</x-tab-button>
            <x-tab-button tab="completed" onclick="setSalesTab('completed')">Completed</x-tab-button>
        </div>
    </div>

    <x-filter-bar>
        <x-slot:search>
            <div class="relative w-full max-w-xl">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" id="salesSearch" placeholder="Search product, customer, payment..." class="w-full pl-10 pr-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </x-slot:search>
        <x-slot:filters>
            <label for="salesFromDate" class="text-xs font-semibold uppercase text-gray-500">From</label>
            <input type="date" id="salesFromDate" class="px-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="applySalesFilters()">
            <label for="salesToDate" class="text-xs font-semibold uppercase text-gray-500">To</label>
            <input type="date" id="salesToDate" class="px-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="applySalesFilters()">
        </x-slot:filters>
    </x-filter-bar>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales by Category Chart -->
        <div class="section-card p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Sales by Category</h3>
            <div id="categoryChart" class="space-y-3">
                <div class="text-center text-gray-500 py-8">Loading chart...</div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="section-card p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Recent Transactions</h3>
            <div id="recentSalesList" class="space-y-3 max-h-[400px] overflow-y-auto">
                <div class="text-center text-gray-500 py-8">Loading transactions...</div>
            </div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-header">
            <h2 class="text-gray-900 font-semibold">Sales History</h2>
            <p class="text-gray-500 text-sm mt-0.5">All sales transactions</p>
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
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">Loading sales...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="viewSaleModal" class="modal-bg" onclick="if(event.target===this)closeViewSaleModal()">
    <div class="bg-white rounded-2xl w-[520px] max-w-[95%] shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <span class="text-lg font-bold text-gray-900">Sale Details</span>
            <button class="bg-transparent border-none text-2xl cursor-pointer text-gray-500 hover:text-gray-700 transition" onclick="closeViewSaleModal()">✕</button>
        </div>
        <div id="viewSaleContent" class="p-6"></div>
    </div>
</div>

<!-- NEW SALE MODAL -->
<div id="saleModal" class="modal-bg" onclick="if(event.target===this)closeSaleModal()">
    <div class="bg-white rounded-2xl w-[500px] max-w-[95%] shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 sticky top-0 bg-white z-10 border-b">
            <span class="text-xl font-bold text-gray-900">New Sale</span>
            <button class="bg-transparent border-none text-2xl cursor-pointer text-gray-500 hover:text-gray-700 transition" onclick="closeSaleModal()">✕</button>
        </div>
        <div class="p-6">
            <form id="saleForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Product *</label>
                    <select id="sale_product_id" required class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Select Product</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Quantity *</label>
                    <input type="number" id="sale_quantity" min="1" value="1" required class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Customer Name</label>
                    <input type="text" id="sale_customer_name" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Optional">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Customer Email</label>
                    <input type="email" id="sale_customer_email" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Optional">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Customer Phone</label>
                    <input type="text" id="sale_customer_phone" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Optional">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Payment Method *</label>
                    <select id="sale_payment_method" required class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Notes</label>
                    <textarea id="sale_notes" rows="2" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Optional notes"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeSaleModal()" class="flex-1 py-3 bg-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit" class="flex-1 py-3 bg-green-600 rounded-xl text-white font-semibold hover:bg-green-700 transition">Complete Sale</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CONFIRMATION MODAL -->
<div id="confirmModal" class="modal-bg">
    <div class="bg-white rounded-2xl w-[350px] max-w-[90%] text-center p-6 shadow-2xl">
        <div class="text-6xl mb-4">⚠️</div>
        <div class="text-lg font-bold mb-2 text-gray-900">Confirm Delete</div>
        <div id="confirmMessage" class="text-sm text-gray-600 mb-5">Are you sure you want to delete this sale?</div>
        <div class="flex gap-3">
            <button onclick="closeConfirmModal()" class="flex-1 py-3 bg-gray-200 rounded-xl cursor-pointer text-sm font-semibold text-gray-700 hover:bg-gray-300 transition">Cancel</button>
            <button id="confirmDeleteBtn" class="flex-1 py-3 bg-red-600 rounded-xl cursor-pointer text-sm font-semibold text-white hover:bg-red-700 transition">Delete</button>
        </div>
    </div>
</div>

<style>
.modal-bg {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 200;
    align-items: center;
    justify-content: center;
}
.modal-bg.open {
    display: flex;
}
</style>

<script>
    const userRole = "{{ auth()->user()->role }}";

    let pendingDeleteId = null;
    let allSalesData = [];
    let currentSalesTab = 'all';
    let currentSalesSearch = '';
    let currentSalesFromDate = '';
    let currentSalesToDate = '';

    function setSalesTab(tab) {
        currentSalesTab = tab;
        document.querySelectorAll('.tab-btn[data-tab]').forEach(btn => {
            const isActive = btn.dataset.tab === tab;
            btn.classList.toggle('active', isActive);
            btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
        applySalesFilters();
    }

    function applySalesFilters() {
        currentSalesSearch = (document.getElementById('salesSearch')?.value || '').trim().toLowerCase();
        currentSalesFromDate = document.getElementById('salesFromDate')?.value || '';
        currentSalesToDate = document.getElementById('salesToDate')?.value || '';
        updateSalesTable(getFilteredSales());
    }

    function getFilteredSales() {
        return allSalesData.filter((sale) => {
            const status = sale.status || 'completed';
            const soldAt = sale.sold_at ? new Date(sale.sold_at) : null;
            const searchable = [
                sale.product?.name,
                sale.customer_name,
                sale.payment_method,
                status,
            ].filter(Boolean).join(' ').toLowerCase();

            const matchesSearch = !currentSalesSearch || searchable.includes(currentSalesSearch);
            const matchesTab = currentSalesTab === 'all' || status === currentSalesTab;
            const fromDate = currentSalesFromDate ? new Date(`${currentSalesFromDate}T00:00:00`) : null;
            const toDate = currentSalesToDate ? new Date(`${currentSalesToDate}T23:59:59`) : null;
            const matchesFrom = !fromDate || (soldAt && soldAt >= fromDate);
            const matchesTo = !toDate || (soldAt && soldAt <= toDate);

            return matchesSearch && matchesTab && matchesFrom && matchesTo;
        });
    }

    function openSaleModal() {
        document.getElementById('saleModal').classList.add('open');
        loadProductsForSale();
    }

    function closeSaleModal() {
        document.getElementById('saleModal').classList.remove('open');
        document.getElementById('saleForm').reset();
        document.getElementById('sale_quantity').value = '1';
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.remove('open');
        pendingDeleteId = null;
    }

    function showConfirmModal(id) {
        pendingDeleteId = id;
        document.getElementById('confirmModal').classList.add('open');
    }

    async function loadProductsForSale() {
        try {
            const res = await fetch('/api/products');
            let products = await res.json();
            if (products.data) products = products.data;
            
            const select = document.getElementById('sale_product_id');
            select.innerHTML = '<option value="">Select Product</option>';
            
            products.forEach(product => {
                if (product.quantity > 0) {
                    select.innerHTML += `<option value="${product.id}" data-price="${product.price}" data-stock="${product.quantity}">${escapeHtml(product.name)} - ₱${parseFloat(product.price).toLocaleString()} (Stock: ${product.quantity})</option>`;
                }
            });
        } catch (error) {
            console.error('Error loading products:', error);
            showToast('Error loading products', true);
        }
    }

    async function loadSales() {
        try {
            const [salesRes, statsRes, categoryRes, recentRes] = await Promise.all([
                fetch('/api/sales'),
                fetch('/api/sales/stats'),
                fetch('/api/sales/category'),
                fetch('/api/sales/recent')
            ]);
            
            const sales = await salesRes.json();
            const stats = await statsRes.json();
            const categoryData = await categoryRes.json();
            const recentSales = await recentRes.json();
            allSalesData = sales || [];
            
            updateStats(stats, allSalesData);
            updateCategoryChart(categoryData);
            updateRecentSales(recentSales);
            applySalesFilters();
            
        } catch (error) {
            console.error('Error loading sales:', error);
            showToast('Error loading sales', true);
        }
    }

    function updateStats(stats, sales) {
        const todayKey = new Date().toDateString();
        const ordersToday = (sales || []).filter(sale => new Date(sale.sold_at).toDateString() === todayKey).length;

        document.getElementById('totalSalesCount').innerText = (sales || []).length;
        document.getElementById('revenueTotal').innerText = '₱' + parseFloat(stats.total_sales || 0).toLocaleString();
        document.getElementById('ordersToday').innerText = ordersToday;
        document.getElementById('completedOrders').innerText = stats.total_orders || 0;
    }

    function updateCategoryChart(categoryData) {
        const container = document.getElementById('categoryChart');
        
        if (Object.keys(categoryData).length === 0) {
            container.innerHTML = '<div class="text-center text-gray-500 py-8">No sales data yet</div>';
            return;
        }
        
        let html = '';
        const total = Object.values(categoryData).reduce((sum, d) => sum + d.total, 0);
        for (const [category, data] of Object.entries(categoryData)) {
            const percentage = (data.total / total) * 100;
            html += `
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700">${escapeHtml(category)}</span>
                        <span class="text-gray-500">₱${data.total.toLocaleString()}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 rounded-full h-2" style="width: ${percentage}%"></div>
                    </div>
                </div>
            `;
        }
        container.innerHTML = html;
    }

    function updateRecentSales(sales) {
        const container = document.getElementById('recentSalesList');
        
        if (sales.length === 0) {
            container.innerHTML = '<div class="text-center text-gray-500 py-8">No recent sales</div>';
            return;
        }
        
        container.innerHTML = sales.map(sale => `
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <div class="min-w-0">
                    <p class="font-medium text-gray-900 truncate">${escapeHtml(sale.product.name)}</p>
                    <p class="text-xs text-gray-500">${new Date(sale.sold_at).toLocaleDateString()} • Qty: ${sale.quantity}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-green-600">₱${parseFloat(sale.total_price).toLocaleString()}</p>
                    <p class="text-xs text-gray-500">${sale.payment_method}</p>
                </div>
            </div>
        `).join('');
    }

    function updateSalesTable(sales) {
        const tbody = document.getElementById('salesTableBody');
        
        if (sales.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500">No sales recorded yet</td></tr>';
            return;
        }
        
        tbody.innerHTML = sales.map(sale => `
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="px-4 py-3 text-sm text-gray-600">${new Date(sale.sold_at).toLocaleDateString()}</td>
                <td class="px-4 py-3">
                    <div>
                        <p class="font-medium text-gray-900">${escapeHtml(sale.product.name)}</p>
                        <p class="text-xs text-gray-500">${sale.product.category ? sale.product.category.name : 'No Category'}</p>
                    </div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    ${sale.customer_name ? escapeHtml(sale.customer_name) : '-'}
                </td>
                <td class="px-4 py-3 text-right text-sm text-gray-600">${sale.quantity}</td>
                <td class="px-4 py-3 text-right font-semibold text-green-600">₱${parseFloat(sale.total_price).toLocaleString()}</td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full ${sale.status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700'}">${sale.status || 'completed'}</span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="viewSale(${sale.id})" class="px-2.5 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition">View</button>
                        ${userRole !== 'staff' ? `
                        <button onclick="showConfirmModal(${sale.id})" class="px-2.5 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function viewSale(saleId) {
        const sale = allSalesData.find(item => item.id === saleId);
        if (!sale) return;

        document.getElementById('viewSaleContent').innerHTML = `
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div><p class="text-xs font-semibold uppercase text-gray-500">Product</p><p class="mt-1 text-gray-900 font-medium">${escapeHtml(sale.product?.name || 'N/A')}</p></div>
                <div><p class="text-xs font-semibold uppercase text-gray-500">Customer</p><p class="mt-1 text-gray-900 font-medium">${escapeHtml(sale.customer_name || '-')}</p></div>
                <div><p class="text-xs font-semibold uppercase text-gray-500">Quantity</p><p class="mt-1 text-gray-900 font-medium">${sale.quantity}</p></div>
                <div><p class="text-xs font-semibold uppercase text-gray-500">Total</p><p class="mt-1 text-green-600 font-semibold">₱${parseFloat(sale.total_price).toLocaleString()}</p></div>
                <div><p class="text-xs font-semibold uppercase text-gray-500">Status</p><p class="mt-1 text-gray-900 font-medium">${escapeHtml(sale.status || 'completed')}</p></div>
                <div><p class="text-xs font-semibold uppercase text-gray-500">Sold At</p><p class="mt-1 text-gray-900 font-medium">${new Date(sale.sold_at).toLocaleString()}</p></div>
            </div>
        `;

        document.getElementById('viewSaleModal').classList.add('open');
    }

    function closeViewSaleModal() {
        document.getElementById('viewSaleModal').classList.remove('open');
    }

    async function deleteSale() {
        if (!pendingDeleteId) return;
        
        try {
            const res = await fetch(`/api/sales/${pendingDeleteId}`, { method: 'DELETE' });
            if (res.ok) {
                showToast('Sale deleted successfully!');
                closeConfirmModal();
                loadSales();
            } else {
                const data = await res.json();
                showToast(data.error || 'Error deleting sale', true);
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error deleting sale', true);
        }
    }

    document.getElementById('confirmDeleteBtn').onclick = deleteSale;

    document.getElementById('saleForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const productId = document.getElementById('sale_product_id').value;
        const quantity = document.getElementById('sale_quantity').value;
        
        if (!productId) {
            showToast('Please select a product', true);
            return;
        }
        
        const submitBtn = document.querySelector('#saleForm button[type="submit"]');
        const originalText = submitBtn.innerText;
        submitBtn.innerText = 'Processing...';
        submitBtn.disabled = true;
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const res = await fetch('/api/sales', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    customer_name: document.getElementById('sale_customer_name').value,
                    customer_email: document.getElementById('sale_customer_email').value,
                    customer_phone: document.getElementById('sale_customer_phone').value,
                    payment_method: document.getElementById('sale_payment_method').value,
                    notes: document.getElementById('sale_notes').value
                })
            });
            
            const data = await res.json();
            
            if (res.ok) {
                showToast('Sale completed successfully!');
                closeSaleModal();
                loadSales();
            } else {
                showToast(data.error || 'Error processing sale', true);
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error processing sale', true);
        } finally {
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
        }
    });

    function showToast(message, isError = false) {
        const toast = document.getElementById('successToast');
        toast.innerText = message;
        toast.classList.remove('hidden');
        if (isError) {
            toast.classList.add('bg-red-600');
            toast.classList.remove('bg-green-600');
        } else {
            toast.classList.add('bg-green-600');
            toast.classList.remove('bg-red-600');
        }
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const salesSearch = document.getElementById('salesSearch');
        if (salesSearch) {
            salesSearch.addEventListener('input', applySalesFilters);
        }

        setSalesTab('all');
        loadSales();
    });
</script>

<!-- SUCCESS TOAST -->
<div id="successToast" class="fixed bottom-5 right-5 bg-green-600 text-white py-3 px-5 rounded-xl text-sm z-[300] hidden shadow-lg">
    Success!
</div>

@endsection