@extends('layouts.app')

@section('title', 'Products')
@section('content')

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"></h1>
        </div>
        <button onclick="resetForm(); showForm()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-lg transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Product
        </button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-summary-card label="Total Products" id="totalProductsCount" value="0" accent="gray" />
        <x-summary-card label="In Stock" id="inStockCount" value="0" accent="green" />
        <x-summary-card label="Low Stock" id="lowStockCount" value="0" accent="amber" />
        <x-summary-card label="Out Of Stock" id="outOfStockCount" value="0" accent="red" />
    </div>

    <div class="tab-surface">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 rounded-lg bg-gray-100 p-1" role="tablist" aria-label="Product tabs">
            <x-tab-button active="true" tab="all" onclick="setProductTab('all')">All Products</x-tab-button>
            <x-tab-button tab="low" onclick="setProductTab('low')">Low Stock</x-tab-button>
            <x-tab-button tab="out" onclick="setProductTab('out')">Out Of Stock</x-tab-button>
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
                <input type="text" id="productSearch" placeholder="Search products, brand, model..." class="w-full pl-10 pr-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </x-slot:search>
        <x-slot:filters>
            <label for="categoryFilter" class="text-xs font-semibold uppercase text-gray-500">Category</label>
            <select id="categoryFilter" class="w-full sm:w-auto sm:min-w-52 px-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="applyFilters(true)">
                <option value="">All Categories</option>
            </select>
        </x-slot:filters>
    </x-filter-bar>

    <div class="section-card">
        <div class="section-header">
            <h2 class="text-gray-900 font-semibold">Product Catalog</h2>
            <p class="text-sm text-gray-500 mt-0.5">Review inventory and keep product data up to date.</p>
        </div>
        <div class="p-6">
            <div id="productContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>
        </div>
    </div>
</div>

<!-- PRODUCT MODAL -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold mb-4" id="modalTitle">Add Product</h2>
        <form id="productForm" enctype="multipart/form-data">
            <input type="hidden" id="product_id">
            
            <div class="space-y-4">
                <!-- SECTION 1: Basic Information -->
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3 text-base">Basic Information</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="mb-3 col-span-2">
                            <label class="block text-gray-700 text-sm mb-1">Product Name *</label>
                            <input type="text" id="name" required class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="mb-3">
                            <label class="block text-gray-700 text-sm mb-1">Brand *</label>
                            <input type="text" id="brand" required class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div class="mb-3">
                            <label class="block text-gray-700 text-sm mb-1">Model Number *</label>
                            <input type="text" id="model_number" required class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                
                <!-- SECTION 2: Custom Specifications (Based on Category) -->
                <div id="dynamicFieldsSection" class="border rounded-lg p-4" style="display: none;">
                    <h3 class="font-semibold text-gray-800 mb-3 text-base">Custom Specifications</h3>
                    <div id="dynamicFields" class="grid grid-cols-2 gap-3"></div>
                </div>
                
                <!-- SECTION 3: Pricing & Inventory -->
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3 text-base">Pricing & Inventory</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="mb-3">
                            <label class="block text-gray-700 text-sm mb-1">Price *</label>
                            <input type="number" id="price" required step="0.01" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div class="mb-3">
                            <label class="block text-gray-700 text-sm mb-1">Quantity *</label>
                            <input type="number" id="quantity" required class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                
                <!-- SECTION 4: Category -->
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3 text-base">Classification</h3>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="mb-3">
                            <label class="block text-gray-700 text-sm mb-1">Category *</label>
                            <select id="category_id" required class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" onchange="updateDynamicFields()">
                                <option value="">Select Category</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- SECTION 5: Performance Details -->
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3 text-base">Performance Details</h3>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="mb-3">
                            <label class="block text-gray-700 text-sm mb-1">Performance Details *</label>
                            <textarea id="performance" rows="3" required class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" placeholder="Base clock, boost clock, TDP, cache, etc."></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- SECTION 6: Product Image -->
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3 text-base">Product Image</h3>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="mb-3">
                            <label class="block text-gray-700 text-sm mb-1">Product Image</label>
                            <input type="file" id="image" accept="image/*" class="w-full border p-2 rounded" onchange="previewImage(event)">
                            <img id="preview" class="hidden h-24 mx-auto mt-2 rounded object-cover">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
                <button type="button" onclick="hideForm()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- PRODUCT DETAILS MODAL -->
<div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg w-[600px] max-w-[95%] max-h-[90vh] overflow-y-auto">
        <div class="h-48 overflow-hidden">
            <img id="detailsImage" src="" class="w-full h-full object-cover">
        </div>
        <div class="flex items-center justify-between px-4 py-2 sticky top-0 bg-white z-10">
            <span id="detailsTitle" class="text-lg font-bold text-gray-900"></span>
            <button class="bg-transparent border-none text-2xl cursor-pointer text-gray-500 leading-none" onclick="hideDetailsModal()">✕</button>
        </div>
        <div id="detailsContent"></div>
    </div>
</div>

<!-- CONFIRMATION MODAL -->
<div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-96 text-center">
        <div class="text-6xl mb-4">⚠️</div>
        <h3 class="text-xl font-bold mb-2">Confirm Delete</h3>
        <p class="text-gray-600 mb-4" id="confirmMessage">Are you sure you want to delete this product?</p>
        <div class="flex justify-center gap-3">
            <button onclick="hideConfirmModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button id="confirmButton" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
        </div>
    </div>
</div>

<!-- TOAST NOTIFICATION -->
<div id="toast" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <span id="toastMessage"></span>
</div>

<script>
// Variables
let pendingDeleteId = null;
let currentFilter = '';
let currentSearch = '';
let currentStockTab = 'all';
let allProductsData = [];

// Local placeholder images
const noImage600 = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'600\' height=\'200\' viewBox=\'0 0 600 200\'%3E%3Crect width=\'600\' height=\'200\' fill=\'%23cccccc\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23666666\' font-size=\'20\'%3ENo Image%3C/text%3E%3C/svg%3E';

function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    toastMessage.textContent = message;
    
    if (isError) {
        toast.classList.remove('bg-green-500');
        toast.classList.add('bg-red-500');
    } else {
        toast.classList.remove('bg-red-500');
        toast.classList.add('bg-green-500');
    }
    
    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

// Update stats
function updateStats(products) {
    const totalProducts = products.length;
    const inStock = products.filter(p => p.quantity > 0).length;
    const lowStock = products.filter(p => p.quantity > 0 && p.quantity < 10).length;
    const outOfStock = products.filter(p => p.quantity <= 0).length;
    
    document.getElementById('totalProductsCount').innerText = totalProducts;
    document.getElementById('inStockCount').innerText = inStock;
    document.getElementById('lowStockCount').innerText = lowStock;
    document.getElementById('outOfStockCount').innerText = outOfStock;
}

function setProductTab(tab) {
    currentStockTab = tab;

    document.querySelectorAll('.tab-btn[data-tab]').forEach(btn => {
        const isActive = btn.dataset.tab === tab;
        btn.classList.toggle('active', isActive);
        btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });

    applyFilters();
}

function getFilteredProducts() {
    return allProductsData.filter(product => {
        const matchesCategory = !currentFilter || String(product.category_id) === String(currentFilter);
        const matchesStockTab = currentStockTab === 'all'
            || (currentStockTab === 'low' && product.quantity > 0 && product.quantity < 10)
            || (currentStockTab === 'out' && product.quantity <= 0);

        if (!matchesCategory || !matchesStockTab) {
            return false;
        }

        if (!currentSearch) {
            return true;
        }

        let dynamicFieldValues = [];
        if (product.dynamic_fields) {
            try {
                const dynamicFields = typeof product.dynamic_fields === 'string'
                    ? JSON.parse(product.dynamic_fields)
                    : product.dynamic_fields;
                dynamicFieldValues = Object.values(dynamicFields || {});
            } catch (error) {
                console.warn('Unable to parse product dynamic fields for search', error);
            }
        }

        const searchableText = [
            product.name,
            product.brand,
            product.model_number,
            product.performance,
            product.category ? product.category.name : '',
            ...dynamicFieldValues,
        ]
            .filter(Boolean)
            .join(' ')
            .toLowerCase();

        return searchableText.includes(currentSearch);
    });
}

function applyFilters(showFeedback = false) {
    currentFilter = document.getElementById('categoryFilter').value;
    currentSearch = document.getElementById('productSearch').value.trim().toLowerCase();

    const filteredProducts = getFilteredProducts();
    updateStats(filteredProducts);
    displayProducts(filteredProducts);

    if (showFeedback) {
        showToast(!currentFilter && !currentSearch ? 'Showing all products' : 'Filters applied');
    }
}

// Update custom fields based on selected category
async function updateDynamicFields() {
    const categoryId = document.getElementById('category_id').value;
    const dynamicFields = document.getElementById('dynamicFields');
    const dynamicFieldsSection = document.getElementById('dynamicFieldsSection');
    
    if (!categoryId) {
        dynamicFieldsSection.style.display = 'none';
        dynamicFields.innerHTML = '';
        return;
    }
    
    try {
        const res = await fetch(`/api/categories/${categoryId}`);
        const category = await res.json();
        
        let fieldsSchema = [];
        if (category.fields_schema) {
            fieldsSchema = typeof category.fields_schema === 'string' 
                ? JSON.parse(category.fields_schema) 
                : category.fields_schema;
        }
        
        if (fieldsSchema.length === 0) {
            dynamicFieldsSection.style.display = 'none';
            dynamicFields.innerHTML = '';
            return;
        }
        
        dynamicFieldsSection.style.display = 'block';
        let fieldsHtml = '';
        
        fieldsSchema.forEach(field => {
            const required = field.required ? 'required' : '';
            let fieldInput = '';
            
            if (field.type === 'textarea') {
                fieldInput = `<textarea id="${field.name}" name="${field.name}" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" rows="2" ${required}></textarea>`;
                fieldsHtml += `<div class="mb-3 col-span-2">
                    <label class="block text-gray-700 text-sm mb-1">${field.label} ${field.required ? '*' : ''}</label>
                    ${fieldInput}
                </div>`;
            } else if (field.type === 'select' && field.options) {
                fieldInput = `<select id="${field.name}" name="${field.name}" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" ${required}>
                    <option value="">Select ${field.label}</option>
                    ${field.options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                </select>`;
                fieldsHtml += `<div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">${field.label} ${field.required ? '*' : ''}</label>
                    ${fieldInput}
                </div>`;
            } else if (field.type === 'number') {
                fieldInput = `<input type="number" id="${field.name}" name="${field.name}" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" step="any" ${required}>`;
                fieldsHtml += `<div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">${field.label} ${field.required ? '*' : ''}</label>
                    ${fieldInput}
                </div>`;
            } else {
                fieldInput = `<input type="text" id="${field.name}" name="${field.name}" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" ${required}>`;
                fieldsHtml += `<div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">${field.label} ${field.required ? '*' : ''}</label>
                    ${fieldInput}
                </div>`;
            }
        });
        
        dynamicFields.innerHTML = fieldsHtml;
        
    } catch (error) {
        console.error('Error loading category fields:', error);
        dynamicFieldsSection.style.display = 'none';
        dynamicFields.innerHTML = '';
    }
}

// Reset form when adding new product
function resetForm() {
    document.getElementById('modalTitle').innerText = 'Add Product';
    document.getElementById('product_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('brand').value = '';
    document.getElementById('model_number').value = '';
    document.getElementById('price').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('category_id').value = '';
    document.getElementById('performance').value = '';
    document.getElementById('preview').classList.add('hidden');
    document.getElementById('preview').src = '';
    document.getElementById('dynamicFieldsSection').style.display = 'none';
    document.getElementById('dynamicFields').innerHTML = '';
}

// Show product modal
function showForm() {
    document.getElementById('modal').classList.remove('hidden');
}

// Hide product modal
function hideForm() {
    document.getElementById('modal').classList.add('hidden');
}

// Hide details modal
function hideDetailsModal() {
    document.getElementById('detailsModal').classList.add('hidden');
}

// Show details modal
function showDetailsModal(product) {
    let extraFieldsHtml = '';
    
    if (product.brand) {
        extraFieldsHtml += `
            <div class="p-2.5 border-b border-gray-100">
                <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Brand</div>
                <div class="text-sm text-gray-900 font-medium">${escapeHtml(product.brand)}</div>
            </div>
        `;
    }
    
    if (product.model_number) {
        extraFieldsHtml += `
            <div class="p-2.5 border-b border-gray-100">
                <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Model Number</div>
                <div class="text-sm text-gray-900 font-medium">${escapeHtml(product.model_number)}</div>
            </div>
        `;
    }
    
    if (product.dynamic_fields) {
        const dynamicFields = typeof product.dynamic_fields === 'string' 
            ? JSON.parse(product.dynamic_fields) 
            : product.dynamic_fields;
        
        for (const [key, value] of Object.entries(dynamicFields)) {
            if (value) {
                const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                extraFieldsHtml += `
                    <div class="p-2.5 border-b border-gray-100">
                        <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">${escapeHtml(label)}</div>
                        <div class="text-sm text-gray-900 font-medium">${escapeHtml(String(value))}</div>
                    </div>
                `;
            }
        }
    }
    
    document.getElementById('detailsTitle').innerText = product.name;
    document.getElementById('detailsImage').src = product.image ? '/storage/' + product.image : noImage600;
    
    let detailsContent = document.getElementById('detailsContent');
    detailsContent.innerHTML = `
        <div class="grid grid-cols-2 gap-0 px-4">
            ${extraFieldsHtml}
            <div class="p-2.5 border-b border-gray-100">
                <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Price</div>
                <div class="text-sm text-gray-900 font-medium">₱ ${parseFloat(product.price).toLocaleString()}</div>
            </div>
            <div class="p-2.5 border-b border-gray-100">
                <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Stock</div>
                <div class="text-sm text-gray-900 font-medium">${product.quantity} units</div>
            </div>
            <div class="p-2.5 border-b border-gray-100">
                <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Category</div>
                <div class="text-sm text-gray-900 font-medium">${product.category ? product.category.name : 'N/A'}</div>
            </div>
            <div class="p-2.5 border-b border-gray-100">
                <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Status</div>
                <div class="text-sm text-gray-900 font-medium">${product.quantity > 0 ? 'In Stock' : 'Out of Stock'}</div>
            </div>
        </div>
        ${product.performance ? `
        <div class="p-3 border-t border-gray-100">
            <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Performance</div>
            <div class="text-sm text-gray-900 leading-relaxed">${escapeHtml(product.performance)}</div>
        </div>
        ` : ''}
    `;
    
    document.getElementById('detailsModal').classList.remove('hidden');
}

// Show confirm modal
function showConfirmModal(id, name) {
    pendingDeleteId = id;
    document.getElementById('confirmMessage').innerHTML = `Are you sure you want to delete product <strong>"${name}"</strong>?`;
    document.getElementById('confirmModal').classList.remove('hidden');
}

function hideConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    pendingDeleteId = null;
}

// Load categories for filter dropdown
async function loadFilterCategories() {
    try {
        const res = await fetch('/api/categories');
        const categories = await res.json();
        
        const select = document.getElementById('categoryFilter');
        select.innerHTML = '<option value="">ALL CATEGORIES</option>';
        
        categories.forEach(cat => {
            select.innerHTML += `<option value="${cat.id}">${escapeHtml(cat.name)}</option>`;
        });
    } catch (error) {
        console.error('Error:', error);
    }
}

// Filter products by category
function filterByCategory() {
    applyFilters(true);
}

// Load categories for product form
async function loadCategories() {
    try {
        const res = await fetch('/api/categories');
        const categories = await res.json();
        
        const select = document.getElementById('category_id');
        select.innerHTML = '<option value="">Select Category</option>';
        
        categories.forEach(cat => {
            select.innerHTML += `<option value="${cat.id}">${escapeHtml(cat.name)}</option>`;
        });
    } catch (error) {
        console.error('Error:', error);
    }
}

// Load products
async function loadProducts() {
    try {
        const res = await fetch('/api/products');
        
        if (!res.ok) {
            throw new Error(`HTTP ${res.status}`);
        }
        
        let products = await res.json();
        
        if (products.data) {
            products = products.data;
        }
        
        allProductsData = products;
        applyFilters();
        
    } catch (error) {
        console.error('Error loading products:', error);
        showToast('Error loading products', true);
    }
}

// Display products in grid
function displayProducts(products) {
    const container = document.getElementById('productContainer');
    container.innerHTML = '';
    
    if (products.length === 0) {
        let message = 'No products yet. Click ADD PRODUCT to create one!';

        if (currentFilter && currentSearch) {
            message = 'No products match the selected category and search.';
        } else if (currentFilter) {
            message = 'No products found in this category. Click ADD PRODUCT to create one!';
        } else if (currentSearch) {
            message = 'No products match your search.';
        }

        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">${message}</h3>
                <p class="text-gray-500 mb-4">Get started by adding your first product</p>
                <button onclick="resetForm(); showForm()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Add Product</button>
            </div>
        `;
        return;
    }
    
    products.forEach(product => {
        const formattedPrice = parseFloat(product.price).toFixed(2);
        const isLowStock = product.quantity > 0 && product.quantity < 10;

        container.innerHTML += `
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                <div class="relative h-48 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                    ${product.image
                        ? `<img src="/storage/${product.image}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">`
                        : `<div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>`
                    }
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full ${product.quantity > 0 ? (isLowStock ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') : 'bg-red-100 text-red-700'}">
                            ${product.quantity > 0 ? (isLowStock ? 'Low Stock' : 'In Stock') : 'Out of Stock'}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 text-lg mb-1 truncate">${escapeHtml(product.name)}</h3>
                    <p class="text-2xl font-bold text-green-600 mb-2">₱ ${formattedPrice}</p>
                    <div class="flex items-center justify-between text-sm mb-3">
                        <span class="text-gray-500">Stock: ${product.quantity} units</span>
                        <span class="text-blue-600 text-xs font-medium">${product.category ? product.category.name : 'No Category'}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <button onclick='showDetailsModal(${JSON.stringify(product)})' class="px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition">
                            View
                        </button>
                        <button onclick="editProduct(${product.id})" class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                            Edit
                        </button>
                        <button onclick="showConfirmModal(${product.id}, '${escapeHtml(product.name)}')" class="px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
}

// Save product
async function saveProduct(event) {
    event.preventDefault();
    
    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('brand', document.getElementById('brand').value);
    formData.append('model_number', document.getElementById('model_number').value);
    formData.append('price', document.getElementById('price').value);
    formData.append('quantity', document.getElementById('quantity').value);
    formData.append('category_id', document.getElementById('category_id').value);
    formData.append('performance', document.getElementById('performance').value);
    
    const dynamicFieldsContainer = document.getElementById('dynamicFields');
    const dynamicInputs = dynamicFieldsContainer.querySelectorAll('input, select, textarea');
    dynamicInputs.forEach(input => {
        if (input.id && input.value) {
            formData.append(input.id, input.value);
        }
    });
    
    const imageFile = document.getElementById('image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    const productId = document.getElementById('product_id').value;
    let url = '/api/products';
    let method = 'POST';
    
    if (productId) {
        url = `/api/products/${productId}`;
        method = 'POST';
        formData.append('_method', 'PUT');
    }
    
    try {
        const res = await fetch(url, {
            method: method,
            body: formData
        });
        
        if (res.ok) {
            const action = productId ? 'updated' : 'added';
            showToast(`Product ${action} successfully!`);
            hideForm();
            document.getElementById('productForm').reset();
            document.getElementById('preview').classList.add('hidden');
            await loadProducts();
        } else {
            const error = await res.text();
            console.error('Error:', error);
            showToast('Error saving product', true);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error saving product', true);
    }
}

// Edit product
async function editProduct(id) {
    try {
        const res = await fetch(`/api/products/${id}`);
        const product = await res.json();
        
        document.getElementById('product_id').value = product.id;
        document.getElementById('name').value = product.name || '';
        document.getElementById('brand').value = product.brand || '';
        document.getElementById('model_number').value = product.model_number || '';
        document.getElementById('price').value = product.price || '';
        document.getElementById('quantity').value = product.quantity || '';
        document.getElementById('category_id').value = product.category_id || '';
        document.getElementById('performance').value = product.performance || '';
        
        document.getElementById('modalTitle').innerText = 'Edit Product';
        
        if (product.image) {
            const preview = document.getElementById('preview');
            preview.src = `/storage/${product.image}`;
            preview.classList.remove('hidden');
        } else {
            document.getElementById('preview').classList.add('hidden');
            document.getElementById('preview').src = '';
        }
        
        await updateDynamicFields();
        
        setTimeout(() => {
            if (product.dynamic_fields) {
                const dynamicFields = typeof product.dynamic_fields === 'string' 
                    ? JSON.parse(product.dynamic_fields) 
                    : product.dynamic_fields;
                
                for (const [key, value] of Object.entries(dynamicFields)) {
                    const field = document.getElementById(key);
                    if (field && value) {
                        field.value = value;
                    }
                }
            }
        }, 300);
        
        showForm();
        
    } catch (error) {
        console.error('Error loading product:', error);
        showToast('Error loading product data', true);
    }
}

// Delete product
async function deleteProduct() {
    if (!pendingDeleteId) return;
    
    try {
        const res = await fetch(`/api/products/${pendingDeleteId}`, {
            method: 'DELETE'
        });
        
        if (res.ok) {
            showToast('Product deleted successfully!');
            hideConfirmModal();
            await loadProducts();
        } else {
            showToast('Error deleting product', true);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error deleting product', true);
    }
}

// Preview image
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('preview');
        preview.src = e.target.result;
        preview.classList.remove('hidden');
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}

// Escape HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    loadProducts();
    loadFilterCategories();
    
    const form = document.getElementById('productForm');
    if (form) {
        form.addEventListener('submit', saveProduct);
    }

    const searchInput = document.getElementById('productSearch');
    if (searchInput) {
        searchInput.addEventListener('input', () => applyFilters());
    }

    setProductTab('all');
    
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', previewImage);
    }
    
    const confirmDeleteBtn = document.getElementById('confirmButton');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', deleteProduct);
    }
});
</script>

<style>
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce {
        animation: bounce 0.5s ease-in-out;
    }
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

@endsection
