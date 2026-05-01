@extends('layouts.app')

@section('title', 'Suppliers')
@section('content')

<div class="space-y-4 sm:space-y-6">
    <!-- Stats Row - 2 columns on mobile, 4 on desktop -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <x-summary-card label="Total Suppliers" id="totalSuppliersCount" value="0" accent="gray" />
        <x-summary-card label="Active Suppliers" id="activeSuppliersCount" value="0" accent="green" />
        <x-summary-card label="With Products" id="suppliersWithProductsCount" value="0" accent="blue" />
        <x-summary-card label="Products Supplied" id="totalProductsFromSuppliers" value="0" accent="indigo" />
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900"></h1>
        </div>
        <button onclick="resetFormAndShow()"
                class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-lg transition shadow-sm text-sm sm:text-base">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Supplier
        </button>
    </div>

    <!-- Tabs - Mobile friendly -->
    <div class="tab-surface">
        <div class="grid grid-cols-3 gap-1 rounded-lg bg-gray-100 p-1" role="tablist" aria-label="Supplier tabs">
            <x-tab-button active="true" tab="all" onclick="setSupplierTab('all')">All</x-tab-button>
            <x-tab-button tab="active" onclick="setSupplierTab('active')">Active</x-tab-button>
            <x-tab-button tab="inactive" onclick="setSupplierTab('inactive')">Inactive</x-tab-button>
        </div>
    </div>

    <!-- Filter Bar - Stack on mobile -->
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" id="supplierSearch" placeholder="Search supplier, contact, address..." class="w-full pl-9 sm:pl-10 pr-3 py-2 sm:py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="w-full sm:w-auto sm:min-w-44">
            <select id="supplierStatusFilter" class="w-full px-3 py-2 sm:py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="applySupplierFilters()">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Supplier Grid - 2 cards per row on mobile, 3 on tablet, 4 on desktop -->
    <div class="section-card">
        <div class="section-header px-4 sm:px-6 py-3 sm:py-4">
            <h2 class="text-gray-900 font-semibold text-base sm:text-lg">Supplier Directory</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">View suppliers, contact details, and offered products.</p>
        </div>
        <div class="p-3 sm:p-6">
            <div id="supplierContainer" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 md:gap-6"></div>
            <div id="suppliersPagination" class="mt-4 flex justify-center"></div>
        </div>
    </div>
</div>

<!-- SUPPLIER MODAL - Responsive -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3 sm:p-4">
    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-4 sm:px-6 py-3 sm:py-4">
            <h2 class="text-base sm:text-xl font-bold" id="modalTitle">Add Supplier</h2>
        </div>
        <form id="supplierForm" enctype="multipart/form-data" class="p-4 sm:p-6">
            <input type="hidden" id="supplier_id">
            
            <div class="space-y-3 sm:space-y-4">
                <div>
                    <label class="block text-gray-700 text-xs sm:text-sm mb-1">Supplier Name *</label>
                    <input type="text" id="name" required class="w-full border p-2 rounded text-sm">
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-gray-700 text-xs sm:text-sm mb-1">Contact Number *</label>
                        <input type="text" id="contact_number" required class="w-full border p-2 rounded text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-xs sm:text-sm mb-1">Email</label>
                        <input type="email" id="email" class="w-full border p-2 rounded text-sm">
                    </div>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-xs sm:text-sm mb-1">Address *</label>
                    <textarea id="address" rows="2" required class="w-full border p-2 rounded text-sm"></textarea>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-xs sm:text-sm mb-1">Products Offered</label>
                    <select id="products_offered" multiple size="5" class="w-full border p-2 rounded text-sm">
                        <option value="">Loading products...</option>
                    </select>
                    <p class="text-[10px] sm:text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple products</p>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-xs sm:text-sm mb-1">Supplier Image</label>
                    <input type="file" id="image" accept="image/*" class="w-full border p-2 rounded text-sm" onchange="previewImage(event)">
                    <img id="preview" class="hidden h-16 sm:h-20 md:h-24 mx-auto mt-2 rounded object-cover">
                </div>
            </div>
            
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 mt-4 sm:mt-6 pt-4 border-t">
                <button type="button" onclick="hideForm()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-sm">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">Save Supplier</button>
            </div>
        </form>
    </div>
</div>

<!-- SUPPLIER DETAILS MODAL - Responsive -->
<div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3 sm:p-4">
    <div class="bg-white rounded-lg w-[95%] sm:w-[600px] max-w-[95%] max-h-[90vh] overflow-y-auto">
        <div class="h-32 sm:h-40 md:h-48 overflow-hidden">
            <img id="detailsImage" src="" class="w-full h-full object-cover">
        </div>
        <div class="flex items-center justify-between px-4 py-3 sticky top-0 bg-white z-10 border-b">
            <span id="detailsTitle" class="text-base sm:text-lg font-bold text-gray-900"></span>
            <button class="bg-transparent border-none text-xl sm:text-2xl cursor-pointer text-gray-500 leading-none" onclick="hideDetailsModal()">✕</button>
        </div>
        <div id="detailsContent" class="p-4 sm:p-6"></div>
    </div>
</div>

<!-- CONFIRMATION MODAL - Responsive -->
<div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white p-5 sm:p-6 rounded-lg w-full max-w-[90%] sm:w-96 text-center">
        <div class="text-5xl sm:text-6xl mb-4">⚠️</div>
        <h3 class="text-base sm:text-xl font-bold mb-2">Confirm Delete</h3>
        <p class="text-gray-600 mb-4 text-xs sm:text-sm" id="confirmMessage">Are you sure you want to delete this supplier?</p>
        <div class="flex justify-center gap-3">
            <button onclick="hideConfirmModal()" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-300 rounded hover:bg-gray-400 text-sm">Cancel</button>
            <button id="confirmButton" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Delete</button>
        </div>
    </div>
</div>

<!-- TOAST NOTIFICATION -->
<div id="toast" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg shadow-lg z-50 text-xs sm:text-sm">
    <span id="toastMessage"></span>
</div>

<script>
let pendingDeleteId = null;
let allProductsList = [];
let allSuppliersData = [];
let currentSupplierTab = 'all';
let currentSupplierSearch = '';
let currentSupplierStatus = '';
let currentSuppliersPage = 1;
const SUPPLIERS_PER_PAGE = 8;
const noImage600 = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'600\' height=\'200\' viewBox=\'0 0 600 200\'%3E%3Crect width=\'600\' height=\'200\' fill=\'%23cccccc\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23666666\' font-size=\'20\'%3ENo Image%3C/text%3E%3C/svg%3E';

function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    toastMessage.textContent = message;
    toast.className = `fixed bottom-4 right-4 px-4 sm:px-6 py-2 sm:py-3 rounded-lg shadow-lg z-50 ${isError ? 'bg-red-500' : 'bg-green-500'} text-white text-xs sm:text-sm`;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}

function updateSupplierStats(suppliers) {
    const total = suppliers.length;
    const active = suppliers.filter(s => (s.products_offered || []).length > 0).length;
    const withProducts = active;
    const totalProducts = suppliers.reduce((sum, supplier) => sum + ((supplier.products_offered || []).length), 0);

    document.getElementById('totalSuppliersCount').innerText = total;
    document.getElementById('activeSuppliersCount').innerText = active;
    document.getElementById('suppliersWithProductsCount').innerText = withProducts;
    document.getElementById('totalProductsFromSuppliers').innerText = totalProducts;
}

function getSupplierStatus(supplier) {
    return (supplier.products_offered || []).length > 0 ? 'active' : 'inactive';
}

function setSupplierTab(tab) {
    currentSupplierTab = tab;
    document.querySelectorAll('.tab-btn[data-tab]').forEach(btn => {
        const isActive = btn.dataset.tab === tab;
        btn.classList.toggle('active', isActive);
        btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });
    applySupplierFilters();
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
    if (pageKey !== 'suppliers') return;
    const filtered = getFilteredSuppliers();
    const totalPages = Math.max(1, Math.ceil(filtered.length / SUPPLIERS_PER_PAGE));
    currentSuppliersPage = Math.min(Math.max(1, page), totalPages);
    displaySuppliers(filtered);
}

function getFilteredSuppliers() {
    return allSuppliersData.filter((supplier) => {
        const status = getSupplierStatus(supplier);
        const searchableText = [
            supplier.name,
            supplier.contact_number,
            supplier.email,
            supplier.address,
        ].filter(Boolean).join(' ').toLowerCase();

        const matchesSearch = !currentSupplierSearch || searchableText.includes(currentSupplierSearch);
        const matchesStatus = !currentSupplierStatus || status === currentSupplierStatus;
        const matchesTab = currentSupplierTab === 'all' || status === currentSupplierTab;
        return matchesSearch && matchesStatus && matchesTab;
    });
}

function applySupplierFilters() {
    currentSupplierSearch = (document.getElementById('supplierSearch')?.value || '').trim().toLowerCase();
    currentSupplierStatus = document.getElementById('supplierStatusFilter')?.value || '';
    currentSuppliersPage = 1;
    displaySuppliers(getFilteredSuppliers());
}

async function loadProductsForDropdown() {
    try {
        const res = await fetch('/api/products');
        let products = await res.json();
        if (products.data) products = products.data;

        allProductsList = products;
        const select = document.getElementById('products_offered');
        select.innerHTML = '';

        if (!products || products.length === 0) {
            select.innerHTML = '<option value="" disabled>No products available. Please add products first.</option>';
            return;
        }

        products.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id;
            option.textContent = `${product.name} ${product.brand ? `(${product.brand})` : ''} - ₱${parseFloat(product.price).toLocaleString()}`;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading products:', error);
    }
}

function resetFormAndShow() {
    document.getElementById('modalTitle').innerText = 'Add Supplier';
    document.getElementById('supplier_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('contact_number').value = '';
    document.getElementById('email').value = '';
    document.getElementById('address').value = '';

    const productsSelect = document.getElementById('products_offered');
    if (productsSelect) {
        Array.from(productsSelect.options).forEach(option => option.selected = false);
    }

    document.getElementById('preview').classList.add('hidden');
    document.getElementById('preview').src = '';
    showForm();
}

function showForm() {
    document.getElementById('modal').classList.remove('hidden');
}

function hideForm() {
    document.getElementById('modal').classList.add('hidden');
}

async function showDetailsModal(supplierId) {
    try {
        const res = await fetch(`/api/suppliers/${supplierId}`);
        const supplier = await res.json();

        let productsHtml = '<p class="text-sm text-gray-500">No products selected</p>';
        if (supplier.products_offered && supplier.products_offered.length > 0) {
            const productsRes = await fetch('/api/products');
            let allProducts = await productsRes.json();
            if (allProducts.data) allProducts = allProducts.data;

            const selectedProducts = allProducts.filter(product => supplier.products_offered.includes(product.id));
            if (selectedProducts.length > 0) {
                productsHtml = '<ul class="list-disc pl-4 mt-1 space-y-1">' + selectedProducts.map(product => `
                    <li class="text-xs sm:text-sm text-gray-700">
                        <span class="font-medium">${escapeHtml(product.name)}</span>
                        ${product.brand ? `<span class="text-gray-500"> (${escapeHtml(product.brand)})</span>` : ''}
                        <span class="text-green-600"> - ₱${parseFloat(product.price).toLocaleString()}</span>
                    </li>
                `).join('') + '</ul>';
            }
        }

        document.getElementById('detailsTitle').innerText = supplier.name;
        document.getElementById('detailsImage').src = supplier.image ? '/storage/' + supplier.image : noImage600;
        document.getElementById('detailsContent').innerHTML = `
            <div class="grid grid-cols-2 gap-0">
                <div class="p-2 border-b border-gray-100">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Contact Number</div>
                    <div class="text-xs sm:text-sm text-gray-900 font-medium">${escapeHtml(supplier.contact_number)}</div>
                </div>
                <div class="p-2 border-b border-gray-100">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Email</div>
                    <div class="text-xs sm:text-sm text-gray-900 font-medium">${supplier.email ? escapeHtml(supplier.email) : 'N/A'}</div>
                </div>
                <div class="p-2 border-b border-gray-100 col-span-2">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Address</div>
                    <div class="text-xs sm:text-sm text-gray-900 font-medium">${escapeHtml(supplier.address)}</div>
                </div>
                <div class="p-2 border-b border-gray-100 col-span-2">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Products Offered</div>
                    <div class="text-xs sm:text-sm text-gray-900 font-medium">${productsHtml}</div>
                </div>
            </div>
        `;
        document.getElementById('detailsModal').classList.remove('hidden');
    } catch (error) {
        console.error('Error fetching supplier:', error);
        showToast('Error loading supplier details', true);
    }
}

function hideDetailsModal() {
    document.getElementById('detailsModal').classList.add('hidden');
}

function showConfirmModal(id, name) {
    pendingDeleteId = id;
    document.getElementById('confirmMessage').innerHTML = `Are you sure you want to delete supplier <strong>"${escapeHtml(name)}"</strong>?`;
    document.getElementById('confirmModal').classList.remove('hidden');
}

function hideConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    pendingDeleteId = null;
}

function displaySuppliers(suppliers) {
    const container = document.getElementById('supplierContainer');
    container.innerHTML = '';

    if (!allSuppliersData.length) {
        renderPagination('suppliersPagination', 0, 1, SUPPLIERS_PER_PAGE, 'suppliers');
        container.innerHTML = `
            <div class="col-span-full text-center py-8 sm:py-12">
                <div class="w-16 h-16 sm:w-24 sm:h-24 mx-auto mb-3 sm:mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-1">No suppliers yet</h3>
                <p class="text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4">Click Add Supplier to create your first supplier.</p>
                <button onclick="resetFormAndShow()" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition text-sm">Add Supplier</button>
            </div>
        `;
        return;
    }

    if (!suppliers.length) {
        renderPagination('suppliersPagination', 0, 1, SUPPLIERS_PER_PAGE, 'suppliers');
        container.innerHTML = `
            <div class="col-span-full text-center py-8 sm:py-12">
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-1">No suppliers match the filters</h3>
                <p class="text-xs sm:text-sm text-gray-500">Try a different search or status.</p>
            </div>
        `;
        return;
    }

    const totalPages = Math.max(1, Math.ceil(suppliers.length / SUPPLIERS_PER_PAGE));
    currentSuppliersPage = Math.min(currentSuppliersPage, totalPages);
    const pageItems = suppliers.slice((currentSuppliersPage - 1) * SUPPLIERS_PER_PAGE, currentSuppliersPage * SUPPLIERS_PER_PAGE);

    pageItems.forEach(supplier => {
        const productCount = (supplier.products_offered || []).length;
        const status = productCount > 0 ? 'active' : 'inactive';
        container.innerHTML += `
            <div class="group bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-200">
                <div class="relative h-24 sm:h-28 md:h-32 overflow-hidden ${supplier.image ? 'bg-gray-100' : 'bg-gradient-to-br from-gray-200 to-gray-300'}">
                    ${supplier.image
                        ? `<img src="/storage/${supplier.image}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">`
                        : `<div class="w-full h-full flex items-center justify-center text-gray-500 text-xs font-medium">No Image</div>`
                    }
                </div>
                <div class="p-3 sm:p-4">
                    <div class="flex items-center justify-between gap-2 mb-1 sm:mb-2">
                        <h3 class="font-bold text-gray-900 text-sm sm:text-base truncate">${escapeHtml(supplier.name)}</h3>
                        <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] sm:text-xs font-semibold rounded-full ${status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'}">
                            ${status === 'active' ? 'Active' : 'Inactive'}
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-600 truncate">${escapeHtml(supplier.contact_number || 'No contact')}</p>
                    <p class="text-xs sm:text-sm text-gray-500 truncate">${escapeHtml(supplier.address || 'No address')}</p>
                    <div class="flex items-center justify-between mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-gray-100">
                        <span class="text-xs sm:text-sm text-gray-600">${productCount} products</span>
                        <div class="grid grid-cols-3 gap-1">
                            <button onclick="showDetailsModal(${supplier.id})" class="px-1.5 sm:px-2 py-1 text-[10px] sm:text-xs font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition">View</button>
                            <button onclick="editSupplier(${supplier.id})" class="px-1.5 sm:px-2 py-1 text-[10px] sm:text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Edit</button>
                            <button onclick="showConfirmModal(${supplier.id}, '${escapeHtml(supplier.name)}')" class="px-1.5 sm:px-2 py-1 text-[10px] sm:text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">Del</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    renderPagination('suppliersPagination', suppliers.length, currentSuppliersPage, SUPPLIERS_PER_PAGE, 'suppliers');
}

async function loadSuppliers() {
    try {
        const res = await fetch('/api/suppliers');
        allSuppliersData = await res.json();
        updateSupplierStats(allSuppliersData);
        applySupplierFilters();
    } catch (error) {
        console.error('Error:', error);
        showToast('Error loading suppliers', true);
    }
}

async function saveSupplier(event) {
    event.preventDefault();

    const name = document.getElementById('name').value.trim();
    const contactNumber = document.getElementById('contact_number').value.trim();
    const address = document.getElementById('address').value.trim();

    if (!name || !contactNumber || !address) {
        showToast('Name, contact number, and address are required.', true);
        return;
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('contact_number', contactNumber);
    formData.append('address', address);
    formData.append('email', document.getElementById('email').value || '');

    const productsSelect = document.getElementById('products_offered');
    const selectedProducts = Array.from(productsSelect.selectedOptions)
        .filter(option => option.value && option.value !== 'Loading products...')
        .map(option => option.value);
    formData.append('products_offered', JSON.stringify(selectedProducts));

    const imageFile = document.getElementById('image').files[0];
    if (imageFile) formData.append('image', imageFile);

    const supplierId = document.getElementById('supplier_id').value;
    let url = '/api/suppliers';
    if (supplierId) {
        url = `/api/suppliers/${supplierId}`;
        formData.append('_method', 'PUT');
    }

    try {
        const res = await fetch(url, { method: 'POST', body: formData });
        if (res.ok) {
            showToast(`Supplier ${supplierId ? 'updated' : 'added'} successfully!`);
            hideForm();
            document.getElementById('supplierForm').reset();
            document.getElementById('preview').classList.add('hidden');
            currentSuppliersPage = 1;
            await loadSuppliers();
        } else {
            showToast('Error saving supplier', true);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error saving supplier', true);
    }
}

async function editSupplier(id) {
    try {
        const res = await fetch(`/api/suppliers/${id}`);
        const supplier = await res.json();

        document.getElementById('supplier_id').value = supplier.id;
        document.getElementById('name').value = supplier.name || '';
        document.getElementById('contact_number').value = supplier.contact_number || '';
        document.getElementById('email').value = supplier.email || '';
        document.getElementById('address').value = supplier.address || '';
        document.getElementById('modalTitle').innerText = 'Edit Supplier';

        const productsSelect = document.getElementById('products_offered');
        if (!productsSelect.options.length) {
            await loadProductsForDropdown();
        }

        const selectedProducts = supplier.products_offered || [];
        Array.from(productsSelect.options).forEach(option => {
            option.selected = selectedProducts.includes(parseInt(option.value, 10));
        });

        if (supplier.image) {
            const preview = document.getElementById('preview');
            preview.src = `/storage/${supplier.image}`;
            preview.classList.remove('hidden');
        }

        showForm();
    } catch (error) {
        console.error('Error:', error);
        showToast('Error loading supplier data', true);
    }
}

async function deleteSupplier() {
    if (!pendingDeleteId) return;

    try {
        const res = await fetch(`/api/suppliers/${pendingDeleteId}`, { method: 'DELETE' });
        if (res.ok) {
            showToast('Supplier deleted successfully!');
            hideConfirmModal();
            currentSuppliersPage = 1;
            await loadSuppliers();
        } else {
            showToast('Error deleting supplier', true);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error deleting supplier', true);
    }
}

function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('preview');
        preview.src = e.target.result;
        preview.classList.remove('hidden');
    };
    if (event.target.files && event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', async () => {
    await loadProductsForDropdown();
    await loadSuppliers();

    const supplierSearch = document.getElementById('supplierSearch');
    if (supplierSearch) supplierSearch.addEventListener('input', applySupplierFilters);

    setSupplierTab('all');

    const form = document.getElementById('supplierForm');
    if (form) form.addEventListener('submit', saveSupplier);

    const imageInput = document.getElementById('image');
    if (imageInput) imageInput.addEventListener('change', previewImage);

    const confirmDeleteBtn = document.getElementById('confirmButton');
    if (confirmDeleteBtn) confirmDeleteBtn.addEventListener('click', deleteSupplier);
});
</script>

<style>
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce { animation: bounce 0.5s ease-in-out; }
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

@endsection
