@extends('layouts.app')

@section('title','Customers')

@section('content')

<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900"></h1>
        </div>
        <!-- Add Customer button removed -->
    </div>

    <!-- Stats Row - All in one row using flex -->
    <div class="flex flex-wrap gap-3 sm:gap-4">
        <div class="flex-1 min-w-[100px]">
            <x-summary-card label="Total Customers" id="totalCustomers" value="0" accent="gray" />
        </div>
        <div class="flex-1 min-w-[100px]">
            <x-summary-card label="Active Customers" id="activeCustomers" value="0" accent="green" />
        </div>
        <div class="flex-1 min-w-[100px]">
            <x-summary-card label="New (7 Days)" id="newCustomers" value="0" accent="blue" />
        </div>
    </div>

    <!-- Tabs - Mobile friendly -->
    <div class="tab-surface">
        <div class="grid grid-cols-3 gap-1 rounded-lg bg-gray-100 p-1" role="tablist" aria-label="Customer tabs">
            <x-tab-button active="true" tab="all" onclick="setCustomerTab('all')">All</x-tab-button>
            <x-tab-button tab="active" onclick="setCustomerTab('active')">Active</x-tab-button>
            <x-tab-button tab="new" onclick="setCustomerTab('new')">New</x-tab-button>
        </div>
    </div>

    <!-- Filter Bar - All in one row on desktop -->
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" id="customerSearch" placeholder="Search customer name or email..." class="w-full pl-9 sm:pl-10 pr-3 py-2 sm:py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="w-full sm:w-44">
            <select id="customerStatusFilter" class="w-full px-3 py-2 sm:py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="applyCustomerFilters()">
                <option value="">All Status</option>
                <option value="active">Active</option>
            </select>
        </div>
    </div>

    <!-- Customer Table - Responsive with horizontal scroll -->
    <div class="section-card">
        <div class="section-header px-4 sm:px-6 py-3 sm:py-4">
            <h2 class="text-gray-900 font-semibold text-base sm:text-lg">Customer Records</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Browse customer data and account history.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[500px] sm:min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                        <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                        <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-500">Joined</th>
                        <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody id="customerTable" class="divide-y divide-gray-100 bg-white">
                    <tr>
                        <td colspan="4" class="text-center py-6 sm:py-8 text-gray-500 text-sm">Loading customers...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="customersPagination" class="px-4 sm:px-6 py-3 flex justify-center"></div>
    </div>
</div>

<!-- Customer Details Modal - Responsive -->
<div id="customerViewModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-3 sm:p-4" onclick="if (event.target === this) closeCustomerModal()">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="flex items-center justify-between px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Customer Details</h3>
            <button type="button" onclick="closeCustomerModal()" class="text-gray-400 hover:text-gray-600 text-xl sm:text-2xl leading-none">&times;</button>
        </div>
        <div id="customerViewContent" class="p-4 sm:p-5"></div>
    </div>
</div>

<script>
let allCustomers = [];
let currentCustomerTab = 'all';
let currentCustomerSearch = '';
let currentCustomerStatus = '';
let currentCustomersPage = 1;
const CUSTOMERS_PER_PAGE = 10;

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function setCustomerTab(tab) {
    currentCustomerTab = tab;
    document.querySelectorAll('.tab-btn[data-tab]').forEach(btn => {
        const isActive = btn.dataset.tab === tab;
        btn.classList.toggle('active', isActive);
        btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });
    applyCustomerFilters(true);
}

function renderPagination(containerId, totalItems, currentPage, perPage, pageKey) {
    const container = document.getElementById(containerId);
    if (!container) return;
    const totalPages = Math.max(1, Math.ceil(totalItems / perPage));
    if (totalPages <= 1) {
        container.innerHTML = '';
        return;
    }
    const pages = Array.from({ length: totalPages }, (_, i) => i + 1);
    container.innerHTML = `
        <div class="flex items-center gap-1 sm:gap-2">
            <button type="button" onclick="changePage('${pageKey}', ${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50">Prev</button>
            ${pages.map(page => `<button type="button" onclick="changePage('${pageKey}', ${page})" class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border ${page === currentPage ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'}">${page}</button>`).join('')}
            <button type="button" onclick="changePage('${pageKey}', ${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50">Next</button>
        </div>
    `;
}

function changePage(pageKey, page) {
    if (pageKey !== 'customers') return;
    const filtered = getFilteredCustomers();
    const totalPages = Math.max(1, Math.ceil(filtered.length / CUSTOMERS_PER_PAGE));
    currentCustomersPage = Math.min(Math.max(1, page), totalPages);
    renderCustomers(filtered);
}

function getFilteredCustomers() {
    const cutoff = new Date();
    cutoff.setDate(cutoff.getDate() - 7);

    return allCustomers.filter(customer => {
        const createdAt = new Date(customer.created_at);
        const isNew = createdAt >= cutoff;
        const status = 'active';
        const searchable = [customer.name, customer.email].filter(Boolean).join(' ').toLowerCase();

        const matchesSearch = !currentCustomerSearch || searchable.includes(currentCustomerSearch);
        const matchesStatus = !currentCustomerStatus || status === currentCustomerStatus;
        const matchesTab = currentCustomerTab === 'all'
            || (currentCustomerTab === 'active' && status === 'active')
            || (currentCustomerTab === 'new' && isNew);

        return matchesSearch && matchesStatus && matchesTab;
    });
}

function renderCustomers(customers) {
    const tbody = document.getElementById('customerTable');
    if (!customers.length) {
        renderPagination('customersPagination', 0, 1, CUSTOMERS_PER_PAGE, 'customers');
        tbody.innerHTML = '<tr><td colspan="4" class="text-center py-6 sm:py-8 text-gray-500 text-sm">No customers found for the selected filters.</td></tr>';
        return;
    }

    const totalPages = Math.max(1, Math.ceil(customers.length / CUSTOMERS_PER_PAGE));
    currentCustomersPage = Math.min(currentCustomersPage, totalPages);
    const pageItems = customers.slice((currentCustomersPage - 1) * CUSTOMERS_PER_PAGE, currentCustomersPage * CUSTOMERS_PER_PAGE);

    tbody.innerHTML = pageItems.map((customer) => `
        <tr class="border-b hover:bg-gray-50 transition cursor-pointer" onclick="viewCustomer(${customer.id})">
            <td class="px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-medium text-gray-900">${escapeHtml(customer.name)}</td>
            <td class="px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-600 break-all">${escapeHtml(customer.email || '-')}</td>
            <td class="px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-600">${new Date(customer.created_at).toLocaleDateString()}</td>
            <td class="px-3 sm:px-4 py-2 sm:py-3"><span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 text-[9px] sm:text-xs font-semibold rounded-full bg-green-100 text-green-700">Active</span></td>
        </tr>
    `).join('');
    renderPagination('customersPagination', customers.length, currentCustomersPage, CUSTOMERS_PER_PAGE, 'customers');
}

function applyCustomerFilters(resetPage = true) {
    currentCustomerSearch = (document.getElementById('customerSearch')?.value || '').trim().toLowerCase();
    currentCustomerStatus = document.getElementById('customerStatusFilter')?.value || '';
    if (resetPage) currentCustomersPage = 1;
    renderCustomers(getFilteredCustomers());
}

function viewCustomer(id) {
    const customer = allCustomers.find(c => c.id === id);
    if (!customer) return;

    document.getElementById('customerViewContent').innerHTML = `
        <div class="space-y-3 sm:space-y-4">
            <div class="flex items-center justify-center mb-2">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-2xl sm:text-3xl">${escapeHtml(customer.name ? customer.name.charAt(0).toUpperCase() : '?')}</span>
                </div>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 sm:p-4">
                <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500">Full Name</p>
                <p class="text-sm sm:text-base text-gray-900 font-medium mt-1 break-all">${escapeHtml(customer.name)}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 sm:p-4">
                <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500">Email Address</p>
                <p class="text-sm sm:text-base text-gray-900 font-medium mt-1 break-all">${escapeHtml(customer.email || 'N/A')}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 sm:p-4">
                <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500">Member Since</p>
                <p class="text-sm sm:text-base text-gray-900 font-medium mt-1">${new Date(customer.created_at).toLocaleString()}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 sm:p-4">
                <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500">Status</p>
                <p class="text-sm sm:text-base text-green-600 font-medium mt-1">Active</p>
            </div>
        </div>
    `;

    const modal = document.getElementById('customerViewModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeCustomerModal() {
    const modal = document.getElementById('customerViewModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function loadCustomers() {
    try {
        const res = await fetch('/api/customers');
        allCustomers = await res.json();
        currentCustomersPage = 1;
        applyCustomerFilters();
    } catch (error) {
        console.error('Error loading customers:', error);
        document.getElementById('customerTable').innerHTML = '<tr><td colspan="4" class="text-center py-6 sm:py-8 text-red-500 text-sm">Error loading customers. Please refresh the page.</td></tr>';
    }
}

async function loadStats() {
    try {
        const res = await fetch('/api/customers/stats');
        const stats = await res.json();

        document.getElementById('totalCustomers').innerText = stats.total || 0;
        document.getElementById('activeCustomers').innerText = stats.total || 0;
        document.getElementById('newCustomers').innerText = stats.new || 0;
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('customerSearch');
    if (search) search.addEventListener('input', () => applyCustomerFilters(true));

    setCustomerTab('all');
    loadCustomers();
    loadStats();
});
</script>

<style>
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>

@endsection
