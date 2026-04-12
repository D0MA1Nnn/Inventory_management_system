@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-4 text-center">PRODUCT</h1>

<!-- FILTER DROPDOWN (changed from modal to dropdown like purchases) -->
<div class="flex justify-end mb-3">
    <select id="categoryFilter" class="bg-gray-300 px-3 py-1 text-sm rounded hover:bg-gray-400 transition cursor-pointer" onchange="filterByCategory()">
        <option value="">ALL CATEGORIES</option>
    </select>
</div>

<!-- GRID -->
<div class="grid grid-cols-4 gap-4">

    <!-- PRODUCT CARDS -->
    <div id="productContainer" class="contents"></div>

    <!-- ADD CARD -->
    <div onclick="resetForm(); showForm()"
        class="bg-gray-300 h-80 flex flex-col items-center justify-center rounded-xl
                cursor-pointer transition-all duration-200
                hover:bg-gray-400 hover:scale-105 hover:shadow-lg
                hover:ring-2 hover:ring-blue-400">

        <div class="text-4xl font-bold text-gray-700">+</div>
        <div class="text-sm mt-2 text-gray-700 font-medium">
            ADD PRODUCT
        </div>

    </div>

</div>

<!-- PRODUCT MODAL -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold mb-4" id="modalTitle">Add Product</h2>
        <form id="productForm" enctype="multipart/form-data">
            <input type="hidden" id="product_id">
            
            <div class="grid grid-cols-2 gap-3">
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">Product Name *</label>
                    <input type="text" id="name" required class="w-full border p-2 rounded">
                </div>
                
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">Brand</label>
                    <input type="text" id="brand" class="w-full border p-2 rounded">
                </div>
                
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">Model Number</label>
                    <input type="text" id="model_number" class="w-full border p-2 rounded">
                </div>
                
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">Architecture/Socket</label>
                    <input type="text" id="architecture_socket" class="w-full border p-2 rounded">
                </div>
                
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">Core Configuration</label>
                    <input type="text" id="core_configuration" class="w-full border p-2 rounded">
                </div>
                
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">Integrated Graphics</label>
                    <input type="text" id="integrated_graphics" class="w-full border p-2 rounded">
                </div>
                
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">Price *</label>
                    <input type="number" id="price" required step="0.01" class="w-full border p-2 rounded">
                </div>
                
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1">Quantity *</label>
                    <input type="number" id="quantity" required class="w-full border p-2 rounded">
                </div>
                
                <div class="mb-3 col-span-2">
                    <label class="block text-gray-700 text-sm mb-1">Category</label>
                    <select id="category_id" class="w-full border p-2 rounded">
                        <option value="">Select Category</option>
                    </select>
                </div>
                
                <div class="mb-3 col-span-2">
                    <label class="block text-gray-700 text-sm mb-1">Performance Details</label>
                    <textarea id="performance" rows="3" class="w-full border p-2 rounded" placeholder="Base clock, boost clock, TDP, cache, etc."></textarea>
                </div>
                
                <div class="mb-3 col-span-2">
                    <label class="block text-gray-700 text-sm mb-1">Product Image</label>
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

<!-- PRODUCT DETAILS MODAL -->
<div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold" id="detailsTitle">Product Details</h2>
            <button onclick="hideDetailsModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <div class="p-6" id="detailsContent"></div>
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
let allProductsData = [];

// Show toast notification
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

// Reset form when adding new product
function resetForm() {
    document.getElementById('modalTitle').innerText = 'Add Product';
    document.getElementById('product_id').value = '';
    document.getElementById('productForm').reset();
    document.getElementById('preview').classList.add('hidden');
    document.getElementById('preview').src = '';
}

// Show product modal
function showForm() {
    document.getElementById('modal').classList.remove('hidden');
}

// Hide product modal
function hideForm() {
    document.getElementById('modal').classList.add('hidden');
}

// Show details modal
function showDetailsModal(product) {
    document.getElementById('detailsTitle').innerText = product.name;
    document.getElementById('detailsContent').innerHTML = `
        <div class="grid grid-cols-2 gap-4">
            ${product.image ? `<div class="col-span-2"><img src="/storage/${product.image}" class="w-full max-h-64 object-cover rounded"></div>` : ''}
            <div class="bg-gray-50 p-2 rounded"><strong>Brand:</strong><br>${product.brand || 'N/A'}</div>
            <div class="bg-gray-50 p-2 rounded"><strong>Model Number:</strong><br>${product.model_number || 'N/A'}</div>
            <div class="bg-gray-50 p-2 rounded"><strong>Architecture/Socket:</strong><br>${product.architecture_socket || 'N/A'}</div>
            <div class="bg-gray-50 p-2 rounded"><strong>Core Configuration:</strong><br>${product.core_configuration || 'N/A'}</div>
            <div class="bg-gray-50 p-2 rounded"><strong>Integrated Graphics:</strong><br>${product.integrated_graphics || 'N/A'}</div>
            <div class="bg-gray-50 p-2 rounded"><strong>Price:</strong><br>₱ ${parseFloat(product.price).toFixed(2)}</div>
            <div class="bg-gray-50 p-2 rounded"><strong>Stock:</strong><br>${product.quantity} units</div>
            <div class="bg-gray-50 p-2 rounded"><strong>Category:</strong><br>${product.category ? product.category.name : 'N/A'}</div>
            <div class="col-span-2 bg-gray-50 p-2 rounded"><strong>Performance:</strong><br>${product.performance || 'N/A'}</div>
        </div>
    `;
    document.getElementById('detailsModal').classList.remove('hidden');
}

function hideDetailsModal() {
    document.getElementById('detailsModal').classList.add('hidden');
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

// Filter products by category (like purchases page)
function filterByCategory() {
    const filterValue = document.getElementById('categoryFilter').value;
    currentFilter = filterValue;
    
    if (!currentFilter) {
        displayProducts(allProductsData);
        showToast('Showing all products');
    } else {
        const filtered = allProductsData.filter(p => p.category_id == currentFilter);
        displayProducts(filtered);
        showToast('Filtering products...');
    }
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
        displayProducts(allProductsData);
        
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
        let message = currentFilter ? 'No products found in this category. Click + ADD PRODUCT to create one!' : 'No products yet. Click + ADD PRODUCT to create one!';
        container.innerHTML = `<div class="col-span-4 text-center text-gray-500 py-8">${message}</div>`;
        return;
    }
    
    products.forEach(product => {
        const formattedPrice = parseFloat(product.price).toFixed(2);

        container.innerHTML += `
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300" style="border-radius: 12px;">
                <div style="background-color: #1a1f2e; padding: 10px 16px 24px 16px; display: flex; justify-content: space-evenly; align-items: center;">
                    <button onclick='showDetailsModal(${JSON.stringify(product)})' style="color: white; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; letter-spacing: 0.5px; opacity: 0.9;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        DETAILS
                    </button>
                    <button onclick="editProduct(${product.id})" style="color: white; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; letter-spacing: 0.5px; opacity: 0.9;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        EDIT
                    </button>
                    <button onclick="showConfirmModal(${product.id}, '${escapeHtml(product.name)}')" style="color: white; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; letter-spacing: 0.5px; opacity: 0.9;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        DELETE
                    </button>
                </div>
                <div style="height: 160px; background-color: #e5e7eb; overflow: hidden; border-radius: 20px; margin-top: -20px; border-top: 2px solid white;">
                    ${product.image
                        ? `<img src="/storage/${product.image}" style="width: 100%; height: 100%; object-fit: cover;">`
                        : '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><span style="font-size:12px;color:#9ca3af;">NO IMAGE</span></div>'
                    }
                </div>
                <div style="padding: 14px 16px 16px;">
                    <h2 style="font-size: 15px; font-weight: 800; color: #111827; margin: 0 0 4px 0; letter-spacing: 0.2px;">${escapeHtml(product.name)}</h2>
                    <p style="font-size: 13px; color: #16a34a; font-weight: 700; margin: 0 0 3px 0;">₱ ${formattedPrice}</p>
                    <p style="font-size: 13px; color: #4b5563; margin: 0 0 3px 0;">Stock: ${product.quantity}</p>
                    <p style="font-size: 13px; color: #2563eb; margin: 0 0 4px 0;">${product.category ? escapeHtml(product.category.name) : 'No Category'}</p>
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
    formData.append('architecture_socket', document.getElementById('architecture_socket').value);
    formData.append('core_configuration', document.getElementById('core_configuration').value);
    formData.append('performance', document.getElementById('performance').value);
    formData.append('integrated_graphics', document.getElementById('integrated_graphics').value);
    formData.append('price', document.getElementById('price').value);
    formData.append('quantity', document.getElementById('quantity').value);
    formData.append('category_id', document.getElementById('category_id').value);
    
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
        console.log('Fetching product ID:', id);
        
        const res = await fetch(`/api/products/${id}`);
        const product = await res.json();
        
        console.log('Product data received:', product);
        
        // Populate all fields
        document.getElementById('product_id').value = product.id;
        document.getElementById('name').value = product.name || '';
        document.getElementById('brand').value = product.brand || '';
        document.getElementById('model_number').value = product.model_number || '';
        document.getElementById('architecture_socket').value = product.architecture_socket || '';
        document.getElementById('core_configuration').value = product.core_configuration || '';
        document.getElementById('performance').value = product.performance || '';
        document.getElementById('integrated_graphics').value = product.integrated_graphics || '';
        document.getElementById('price').value = product.price || '';
        document.getElementById('quantity').value = product.quantity || '';
        document.getElementById('category_id').value = product.category_id || '';
        
        // Change modal title
        document.getElementById('modalTitle').innerText = 'Edit Product';
        
        // Show existing image if any
        if (product.image) {
            const preview = document.getElementById('preview');
            preview.src = `/storage/${product.image}`;
            preview.classList.remove('hidden');
        } else {
            document.getElementById('preview').classList.add('hidden');
            document.getElementById('preview').src = '';
        }
        
        // Show the modal
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
    .transition {
        transition: all 0.3s ease;
    }
</style>

@endsection