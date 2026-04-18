@extends('layouts.app')

@section('title', 'Suppliers')
@section('content')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"></h1>
        </div>
        <button onclick="resetFormAndShow()" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Supplier
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-gray-900" id="totalSuppliersCount">0</p>
            <p class="text-xs text-gray-500">Total Suppliers</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-green-600" id="activeSuppliersCount">0</p>
            <p class="text-xs text-gray-500">Active Suppliers</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-blue-600" id="suppliersWithProductsCount">0</p>
            <p class="text-xs text-gray-500">With Products</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-purple-600" id="totalProductsFromSuppliers">0</p>
            <p class="text-xs text-gray-500">Products Supplied</p>
        </div>
    </div>

    <!-- Suppliers Grid -->
    <div id="supplierContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>
</div>

<!-- SUPPLIER MODAL -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold mb-4" id="modalTitle">Add Supplier</h2>
        <form id="supplierForm" enctype="multipart/form-data">
            <input type="hidden" id="supplier_id">
            
            <div class="grid grid-cols-2 gap-3">
                <div class="mb-3 col-span-2">
                    <label class="block text-gray-700 text-sm mb-1">Supplier Name *</label>
                    <input type="text" id="name" required class="w-full border p-2 rounded">
                </div>
                
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">Contact Number *</label>
                    <input type="text" id="contact_number" required class="w-full border p-2 rounded">
                </div>
                
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">Email</label>
                    <input type="email" id="email" class="w-full border p-2 rounded">
                </div>
                
                <div class="mb-3 col-span-2">
                    <label class="block text-gray-700 text-sm mb-1">Address *</label>
                    <textarea id="address" rows="2" required class="w-full border p-2 rounded"></textarea>
                </div>
                
                <div class="mb-3 col-span-2">
                    <label class="block text-gray-700 text-sm mb-1">Products Offered</label>
                    <select id="products_offered" multiple size="6" class="w-full border p-2 rounded">
                        <option value="">Loading products...</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple products</p>
                </div>
                
                <div class="mb-3 col-span-2">
                    <label class="block text-gray-700 text-sm mb-1">Supplier Image</label>
                    <input type="file" id="image" accept="image/*" class="w-full border p-2 rounded" onchange="previewImage(event)">
                    <img id="preview" class="hidden h-24 mx-auto mt-2 rounded object-cover">
                </div>
            </div>
            
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="hideForm()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- SUPPLIER DETAILS MODAL -->
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
        <p class="text-gray-600 mb-4" id="confirmMessage">Are you sure you want to delete this supplier?</p>
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
let pendingDeleteId = null;
let allProductsList = [];
const noImage600 = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'600\' height=\'200\' viewBox=\'0 0 600 200\'%3E%3Crect width=\'600\' height=\'200\' fill=\'%23cccccc\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23666666\' font-size=\'20\'%3ENo Image%3C/text%3E%3C/svg%3E';

function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    toastMessage.textContent = message;
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${isError ? 'bg-red-500' : 'bg-green-500'} text-white`;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}

// Update stats
function updateSupplierStats(suppliers) {
    const total = suppliers.length;
    const active = suppliers.filter(s => s.products_offered && s.products_offered.length > 0).length;
    const withProducts = suppliers.filter(s => s.products_offered && s.products_offered.length > 0).length;
    let totalProducts = 0;
    suppliers.forEach(s => {
        if (s.products_offered) {
            totalProducts += s.products_offered.length;
        }
    });
    
    document.getElementById('totalSuppliersCount').innerText = total;
    document.getElementById('activeSuppliersCount').innerText = active;
    document.getElementById('suppliersWithProductsCount').innerText = withProducts;
    document.getElementById('totalProductsFromSuppliers').innerText = totalProducts;
}

// Load products for dropdown
async function loadProductsForDropdown() {
    try {
        const res = await fetch('/api/products');
        let products = await res.json();
        
        if (products.data) {
            products = products.data;
        }
        
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
        
        console.log('Products loaded for dropdown:', products.length);
    } catch (error) {
        console.error('Error loading products:', error);
        const select = document.getElementById('products_offered');
        select.innerHTML = '<option value="" disabled>Error loading products</option>';
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
        Array.from(productsSelect.options).forEach(option => {
            option.selected = false;
        });
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
            try {
                let productIds = supplier.products_offered;
                
                if (productIds && productIds.length > 0) {
                    const productsRes = await fetch('/api/products');
                    let allProducts = await productsRes.json();
                    
                    if (allProducts.data) {
                        allProducts = allProducts.data;
                    }
                    
                    const selectedProducts = allProducts.filter(product => {
                        return productIds.includes(product.id);
                    });
                    
                    if (selectedProducts.length > 0) {
                        productsHtml = '<ul class="list-disc pl-4 mt-1 space-y-1">';
                        selectedProducts.forEach(product => {
                            productsHtml += `
                                <li class="text-sm text-gray-700">
                                    <span class="font-medium">${escapeHtml(product.name)}</span>
                                    ${product.brand ? `<span class="text-gray-500"> (${escapeHtml(product.brand)})</span>` : ''}
                                    <span class="text-green-600"> - ₱${parseFloat(product.price).toLocaleString()}</span>
                                </li>
                            `;
                        });
                        productsHtml += '</ul>';
                    }
                }
            } catch (e) {
                console.error('Error parsing products:', e);
                productsHtml = '<p class="text-sm text-red-500">Error loading products</p>';
            }
        }
        
        document.getElementById('detailsTitle').innerText = supplier.name;
        document.getElementById('detailsImage').src = supplier.image ? '/storage/' + supplier.image : noImage600;
        
        document.getElementById('detailsContent').innerHTML = `
            <div class="grid grid-cols-2 gap-0 px-4">
                <div class="p-2.5 border-b border-gray-100">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Contact Number</div>
                    <div class="text-sm text-gray-900 font-medium">${escapeHtml(supplier.contact_number)}</div>
                </div>
                <div class="p-2.5 border-b border-gray-100">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Email</div>
                    <div class="text-sm text-gray-900 font-medium">${supplier.email ? escapeHtml(supplier.email) : 'N/A'}</div>
                </div>
                <div class="p-2.5 border-b border-gray-100 col-span-2">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Address</div>
                    <div class="text-sm text-gray-900 font-medium">${escapeHtml(supplier.address)}</div>
                </div>
                <div class="p-2.5 border-b border-gray-100 col-span-2">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Products Offered</div>
                    <div class="text-sm text-gray-900 font-medium">${productsHtml}</div>
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

async function loadSuppliers() {
    try {
        const res = await fetch('/api/suppliers');
        const suppliers = await res.json();
        
        const container = document.getElementById('supplierContainer');
        container.innerHTML = '';
        
        updateSupplierStats(suppliers);
        
        if (!suppliers || suppliers.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No suppliers yet</h3>
                    <p class="text-gray-500 mb-4">Click the Add Supplier button to create your first supplier</p>
                    <button onclick="resetFormAndShow()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Add Supplier</button>
                </div>
            `;
            return;
        }
        
        suppliers.forEach(supplier => {
            const productCount = supplier.products_offered ? supplier.products_offered.length : 0;
            const hasProducts = productCount > 0;
            
            container.innerHTML += `
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="relative h-40 overflow-hidden">
                        ${supplier.image
                            ? `<img src="/storage/${supplier.image}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">`
                            : `<div class="w-full h-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                               </div>`
                        }
                        <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="editSupplier(${supplier.id})" class="p-2 bg-white rounded-xl shadow-md hover:bg-gray-100 transition">
                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <button onclick="showConfirmModal(${supplier.id}, '${escapeHtml(supplier.name)}')" class="p-2 bg-white rounded-xl shadow-md hover:bg-red-50 transition">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 text-lg mb-1">${escapeHtml(supplier.name)}</h3>
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>${escapeHtml(supplier.contact_number)}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-3 line-clamp-1">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="truncate">${escapeHtml(supplier.address.substring(0, 50))}${supplier.address.length > 50 ? '...' : ''}</span>
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="text-sm text-gray-600">${productCount} products</span>
                            </div>
                            <button onclick="showDetailsModal(${supplier.id})" class="text-blue-600 text-sm font-medium hover:text-blue-700 transition">
                                View Details →
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
    } catch (error) {
        console.error('Error:', error);
        showToast('Error loading suppliers', true);
    }
}

// Save supplier
async function saveSupplier(event) {
    event.preventDefault();
    
    const name = document.getElementById('name').value.trim();
    const contactNumber = document.getElementById('contact_number').value.trim();
    const address = document.getElementById('address').value.trim();
    
    if (!name) {
        showToast('Please enter supplier name', true);
        return;
    }
    if (!contactNumber) {
        showToast('Please enter contact number', true);
        return;
    }
    if (!address) {
        showToast('Please enter address', true);
        return;
    }
    
    const formData = new FormData();
    formData.append('name', name);
    formData.append('contact_number', contactNumber);
    formData.append('address', address);
    
    const productsSelect = document.getElementById('products_offered');
    const selectedProducts = Array.from(productsSelect.selectedOptions)
        .filter(option => option.value && option.value !== 'Loading products...')
        .map(option => option.value);
    
    console.log('Selected products to save:', selectedProducts);
    formData.append('products_offered', JSON.stringify(selectedProducts));
    formData.append('email', document.getElementById('email').value || '');
    
    const imageFile = document.getElementById('image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    const supplierId = document.getElementById('supplier_id').value;
    let url = '/api/suppliers';
    let method = 'POST';
    
    if (supplierId) {
        url = `/api/suppliers/${supplierId}`;
        method = 'POST';
        formData.append('_method', 'PUT');
    }
    
    try {
        const res = await fetch(url, {
            method: method,
            body: formData
        });
        
        if (res.ok) {
            const data = await res.json();
            console.log('Save response:', data);
            showToast(`Supplier ${supplierId ? 'updated' : 'added'} successfully!`);
            hideForm();
            document.getElementById('supplierForm').reset();
            document.getElementById('preview').classList.add('hidden');
            await loadSuppliers();
        } else {
            const error = await res.text();
            console.error('Server error:', error);
            showToast('Error saving supplier: ' + error, true);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error saving supplier', true);
    }
}

// Edit supplier
async function editSupplier(id) {
    try {
        const res = await fetch(`/api/suppliers/${id}`);
        const supplier = await res.json();
        
        console.log('Editing supplier:', supplier);
        
        document.getElementById('supplier_id').value = supplier.id;
        document.getElementById('name').value = supplier.name || '';
        document.getElementById('contact_number').value = supplier.contact_number || '';
        document.getElementById('email').value = supplier.email || '';
        document.getElementById('address').value = supplier.address || '';
        document.getElementById('modalTitle').innerText = 'Edit Supplier';
        
        let selectedProducts = supplier.products_offered || [];
        
        console.log('Selected products for edit:', selectedProducts);
        
        const productsSelect = document.getElementById('products_offered');
        if (productsSelect.options.length === 0 || productsSelect.options[0]?.text === 'Loading products...') {
            await loadProductsForDropdown();
        }
        
        setTimeout(() => {
            Array.from(productsSelect.options).forEach(option => {
                option.selected = false;
            });
            
            if (selectedProducts && selectedProducts.length > 0) {
                Array.from(productsSelect.options).forEach(option => {
                    if (selectedProducts.includes(parseInt(option.value))) {
                        option.selected = true;
                    }
                });
            }
        }, 200);
        
        if (supplier.image) {
            const preview = document.getElementById('preview');
            preview.src = `/storage/${supplier.image}`;
            preview.classList.remove('hidden');
        } else {
            document.getElementById('preview').classList.add('hidden');
            document.getElementById('preview').src = '';
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
        const res = await fetch(`/api/suppliers/${pendingDeleteId}`, {
            method: 'DELETE'
        });
        
        if (res.ok) {
            showToast('Supplier deleted successfully!');
            hideConfirmModal();
            await loadSuppliers();
        } else {
            const error = await res.text();
            showToast('Error deleting supplier: ' + error, true);
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
    
    const form = document.getElementById('supplierForm');
    if (form) {
        form.addEventListener('submit', saveSupplier);
    }
    
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', previewImage);
    }
    
    const confirmDeleteBtn = document.getElementById('confirmButton');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', deleteSupplier);
    }
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