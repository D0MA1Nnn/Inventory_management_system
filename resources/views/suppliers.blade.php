@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-4 text-center">SUPPLIER</h1>

<!-- 🔹 GRID -->
<div class="grid grid-cols-4 gap-4">
    <div id="supplierContainer" class="contents"></div>
    
    <div onclick="resetFormAndShow()" class="bg-gray-300 h-80 flex flex-col items-center justify-center rounded-xl cursor-pointer transition-all duration-200 hover:bg-gray-400 hover:scale-105 hover:shadow-lg hover:ring-2 hover:ring-blue-400">
        <div class="text-4xl font-bold text-gray-700">+</div>
        <div class="text-sm mt-2 text-gray-700 font-medium">ADD SUPPLIER</div>
    </div>
</div>

<!-- 🔹 SUPPLIER MODAL -->
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
                    <select id="products_offered" multiple size="5" class="w-full border p-2 rounded">
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

<!-- 🔹 SUPPLIER DETAILS MODAL -->
<div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold" id="detailsTitle">Supplier Details</h2>
            <button onclick="hideDetailsModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <div class="p-6" id="detailsContent"></div>
    </div>
</div>

<!-- 🔹 CONFIRMATION MODAL -->
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

<!-- 🔹 TOAST NOTIFICATION -->
<div id="toast" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <span id="toastMessage"></span>
</div>

<script>
let pendingDeleteId = null;

function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    toastMessage.textContent = message;
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${isError ? 'bg-red-500' : 'bg-green-500'} text-white`;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}

// Load products for dropdown
async function loadProductsForDropdown() {
    try {
        const res = await fetch('/api/products');
        let products = await res.json();
        
        if (products.data) {
            products = products.data;
        }
        
        const select = document.getElementById('products_offered');
        select.innerHTML = '';
        
        if (products.length === 0) {
            select.innerHTML = '<option value="">No products available. Please add products first.</option>';
            return;
        }
        
        products.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id;
            option.textContent = `${product.name} ${product.brand ? `(${product.brand})` : ''}`;
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
    
    // Clear selected products
    const productsSelect = document.getElementById('products_offered');
    Array.from(productsSelect.options).forEach(option => {
        option.selected = false;
    });
    
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

async function showDetailsModal(supplier) {
    let productsHtml = '<p class="text-sm">No products selected</p>';
    
    if (supplier.products_offered) {
        try {
            const productIds = typeof supplier.products_offered === 'string' 
                ? JSON.parse(supplier.products_offered) 
                : supplier.products_offered;
            
            if (productIds && productIds.length > 0) {
                // Fetch product names for the IDs
                const res = await fetch('/api/products');
                let allProducts = await res.json();
                if (allProducts.data) allProducts = allProducts.data;
                
                const selectedProducts = allProducts.filter(p => productIds.includes(String(p.id)));
                
                if (selectedProducts.length > 0) {
                    productsHtml = '<ul class="list-disc pl-4 mt-1">';
                    selectedProducts.forEach(p => {
                        productsHtml += `<li class="text-sm">${escapeHtml(p.name)} ${p.brand ? `(${escapeHtml(p.brand)})` : ''}</li>`;
                    });
                    productsHtml += '</ul>';
                } else {
                    productsHtml = '<p class="text-sm">No products selected</p>';
                }
            }
        } catch (e) {
            productsHtml = '<p class="text-sm">Invalid product data</p>';
        }
    }
    
    document.getElementById('detailsTitle').innerText = supplier.name;
    document.getElementById('detailsContent').innerHTML = `
        <div class="grid grid-cols-2 gap-4">
            ${supplier.image ? `<div class="col-span-2"><img src="/storage/${supplier.image}" class="w-full max-h-64 object-cover rounded"></div>` : ''}
            <div class="bg-gray-50 p-3 rounded"><strong>📞 Contact:</strong><br>${escapeHtml(supplier.contact_number)}</div>
            <div class="bg-gray-50 p-3 rounded"><strong>📧 Email:</strong><br>${supplier.email ? escapeHtml(supplier.email) : 'N/A'}</div>
            <div class="col-span-2 bg-gray-50 p-3 rounded"><strong>📍 Address:</strong><br>${escapeHtml(supplier.address)}</div>
            <div class="col-span-2 bg-gray-50 p-3 rounded"><strong>📦 Products Offered:</strong><br>${productsHtml}</div>
        </div>
    `;
    document.getElementById('detailsModal').classList.remove('hidden');
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
        
        if (!suppliers || suppliers.length === 0) {
            container.innerHTML = '<div class="col-span-4 text-center text-gray-500 py-8">No suppliers yet. Click + ADD SUPPLIER to create one!</div>';
            return;
        }
        
        suppliers.forEach(supplier => {
            container.innerHTML += `
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300" style="border-radius: 12px;">
                    <div style="background-color: #1a1f2e; padding: 10px 16px 24px 16px; display: flex; justify-content: space-evenly; align-items: center;">
                        <button onclick='showDetailsModal(${JSON.stringify(supplier)})' style="color: white; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; letter-spacing: 0.5px; opacity: 0.9;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            DETAILS
                        </button>
                        <button onclick="editSupplier(${supplier.id})" style="color: white; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; letter-spacing: 0.5px; opacity: 0.9;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            EDIT
                        </button>
                        <button onclick="showConfirmModal(${supplier.id}, '${escapeHtml(supplier.name)}')" style="color: white; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; letter-spacing: 0.5px; opacity: 0.9;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            DELETE
                        </button>
                    </div>
                    <div style="height: 160px; background-color: #e5e7eb; overflow: hidden; border-radius: 20px; margin-top: -20px; border-top: 2px solid white;">
                        ${supplier.image
                            ? `<img src="/storage/${supplier.image}" style="width: 100%; height: 100%; object-fit: cover;">`
                            : '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><span style="font-size:12px;color:#9ca3af;">NO IMAGE</span></div>'
                        }
                    </div>
                    <div style="padding: 14px 16px 16px;">
                        <h2 style="font-size: 15px; font-weight: 800; color: #111827; margin: 0 0 4px 0; letter-spacing: 0.2px;">${escapeHtml(supplier.name)}</h2>
                        <p style="font-size: 13px; color: #4b5563; margin: 0 0 3px 0;">📞 ${escapeHtml(supplier.contact_number)}</p>
                        <p style="font-size: 13px; color: #4b5563; margin: 0;">📍 ${escapeHtml(supplier.address.substring(0, 50))}${supplier.address.length > 50 ? '...' : ''}</p>
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
    
    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('contact_number', document.getElementById('contact_number').value);
    formData.append('address', document.getElementById('address').value);
    
    // Get selected products (multiple select)
    const productsSelect = document.getElementById('products_offered');
    const selectedProducts = Array.from(productsSelect.selectedOptions).map(option => option.value);
    formData.append('products_offered', JSON.stringify(selectedProducts));
    
    formData.append('email', document.getElementById('email').value);
    
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
            showToast(`Supplier ${supplierId ? 'updated' : 'added'} successfully!`);
            hideForm();
            document.getElementById('supplierForm').reset();
            document.getElementById('preview').classList.add('hidden');
            await loadSuppliers();
        } else {
            const error = await res.text();
            showToast('Error saving supplier', true);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error saving supplier', true);
    }
}

// Edit supplier - shows all existing data
async function editSupplier(id) {
    try {
        const res = await fetch(`/api/suppliers/${id}`);
        const supplier = await res.json();
        
        console.log('Editing supplier:', supplier);
        
        // Populate all fields
        document.getElementById('supplier_id').value = supplier.id;
        document.getElementById('name').value = supplier.name;
        document.getElementById('contact_number').value = supplier.contact_number;
        document.getElementById('email').value = supplier.email || '';
        document.getElementById('address').value = supplier.address;
        document.getElementById('modalTitle').innerText = 'Edit Supplier';
        
        // Handle products_offered (JSON array)
        if (supplier.products_offered) {
            const selectedProducts = typeof supplier.products_offered === 'string' 
                ? JSON.parse(supplier.products_offered) 
                : supplier.products_offered;
            
            const productsSelect = document.getElementById('products_offered');
            Array.from(productsSelect.options).forEach(option => {
                if (selectedProducts.includes(option.value)) {
                    option.selected = true;
                }
            });
        }
        
        // Show existing image if any
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

// Delete supplier
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
            showToast('Error deleting supplier', true);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error deleting supplier', true);
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
document.addEventListener('DOMContentLoaded', () => {
    loadSuppliers();
    loadProductsForDropdown(); // Load products for dropdown
    
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
    .transition { transition: all 0.3s ease; }
</style>

@endsection