@extends('layouts.app')

@section('title','Customers')

@section('content')

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"></h1>
        </div>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-lg transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Customer
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <x-summary-card label="Total Customers" id="totalCustomers" value="0" accent="gray" />
        <x-summary-card label="Active Customers" id="activeCustomers" value="0" accent="green" />
        <x-summary-card label="New (7 Days)" id="newCustomers" value="0" accent="blue" />
    </div>

    <div class="tab-surface">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 rounded-lg bg-gray-100 p-1" role="tablist" aria-label="Customer tabs">
            <x-tab-button active="true" tab="all" onclick="setCustomerTab('all')">All Customers</x-tab-button>
            <x-tab-button tab="active" onclick="setCustomerTab('active')">Active</x-tab-button>
            <x-tab-button tab="new" onclick="setCustomerTab('new')">New (7 Days)</x-tab-button>
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
                <input type="text" id="customerSearch" placeholder="Search customer name or email..." class="w-full pl-10 pr-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </x-slot:search>
        <x-slot:filters>
            <label for="customerStatusFilter" class="text-xs font-semibold uppercase text-gray-500">Status</label>
            <select id="customerStatusFilter" class="w-full sm:w-auto sm:min-w-44 px-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="applyCustomerFilters()">
                <option value="">All Status</option>
                <option value="active">Active</option>
            </select>
        </x-slot:filters>
    </x-filter-bar>

    <div class="section-card">
        <div class="section-header">
            <h2 class="text-gray-900 font-semibold">Customer Records</h2>
            <p class="text-sm text-gray-500 mt-0.5">Browse customer data and account history.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Joined</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody id="customerTable">
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">Loading customers...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="customerViewModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4" onclick="if (event.target === this) closeCustomerModal()">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Customer Details</h3>
            <button type="button" onclick="closeCustomerModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <div id="customerViewContent" class="p-5"></div>
    </div>
</div>

<script>
let allCustomers = [];
let currentCustomerTab = 'all';
let currentCustomerSearch = '';
let currentCustomerStatus = '';

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
    applyCustomerFilters();
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
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-gray-500">No customers found for the selected filters.</td></tr>';
        return;
    }

    tbody.innerHTML = customers.map((customer) => `
        <tr class="border-b hover:bg-gray-50 transition">
            <td class="px-4 py-3 text-sm font-medium text-gray-900">${escapeHtml(customer.name)}</td>
            <td class="px-4 py-3 text-sm text-gray-600">${escapeHtml(customer.email || '-')}</td>
            <td class="px-4 py-3 text-sm text-gray-600">${new Date(customer.created_at).toLocaleDateString()}</td>
            <td class="px-4 py-3"><span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Active</span></td>
            <td class="px-4 py-3">
                <div class="flex items-center justify-center gap-2">
                    <button onclick="viewCustomer(${customer.id})" class="px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition">View</button>
                    <button disabled class="px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">Edit</button>
                    <button disabled class="px-3 py-1.5 text-xs font-medium text-gray-400 bg-red-50 rounded-lg cursor-not-allowed">Delete</button>
                </div>
            </td>
        </tr>
    `).join('');
}

function applyCustomerFilters() {
    currentCustomerSearch = (document.getElementById('customerSearch')?.value || '').trim().toLowerCase();
    currentCustomerStatus = document.getElementById('customerStatusFilter')?.value || '';
    renderCustomers(getFilteredCustomers());
}

function viewCustomer(id) {
    const customer = allCustomers.find(c => c.id === id);
    if (!customer) return;

    document.getElementById('customerViewContent').innerHTML = `
        <div class="space-y-3">
            <div>
                <p class="text-xs font-semibold uppercase text-gray-500">Name</p>
                <p class="text-sm text-gray-900 font-medium mt-1">${escapeHtml(customer.name)}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-500">Email</p>
                <p class="text-sm text-gray-900 font-medium mt-1">${escapeHtml(customer.email || 'N/A')}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-500">Joined</p>
                <p class="text-sm text-gray-900 font-medium mt-1">${new Date(customer.created_at).toLocaleString()}</p>
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
    const res = await fetch('/api/customers');
    allCustomers = await res.json();
    applyCustomerFilters();
}

async function loadStats() {
    const res = await fetch('/api/customers/stats');
    const stats = await res.json();

    document.getElementById('totalCustomers').innerText = stats.total || 0;
    document.getElementById('activeCustomers').innerText = stats.total || 0;
    document.getElementById('newCustomers').innerText = stats.new || 0;
}

document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('customerSearch');
    if (search) search.addEventListener('input', applyCustomerFilters);

    setCustomerTab('all');
    loadCustomers();
    loadStats();
});
</script>

@endsection