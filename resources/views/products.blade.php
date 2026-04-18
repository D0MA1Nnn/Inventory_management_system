@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-4 text-center">PRODUCT</h1>

<!-- FILTER DROPDOWN -->
<div class="flex justify-end mb-3">
    <select id="categoryFilter" class="bg-gray-300 px-3 py-1 text-sm rounded hover:bg-gray-400 transition cursor-pointer" onchange="filterByCategory()">
        <option value="">ALL CATEGORIES</option>
    </select>
</div>

<!-- GRID -->
<div class="grid grid-cols-4 gap-4">
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
                    <div id="dynamicFields" class="grid grid-cols-2 gap-3">
                        <!-- Custom fields will be loaded here based on selected category -->
                    </div>
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

<!-- PRODUCT DETAILS MODAL - SAME UI AS PURCHASES PAGE -->
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

// Show details modal - EXACT SAME UI AS PURCHASES PAGE
function showDetailsModal(product) {
    let extraFieldsHtml = '';
    
    // Brand field
    if (product.brand) {
        extraFieldsHtml += `
            <div class="p-2.5 border-b border-gray-100">
                <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Brand</div>
                <div class="text-sm text-gray-900 font-medium">${escapeHtml(product.brand)}</div>
            </div>
        `;
    }
    
    // Model Number field
    if (product.model_number) {
        extraFieldsHtml += `
            <div class="p-2.5 border-b border-gray-100">
                <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Model Number</div>
                <div class="text-sm text-gray-900 font-medium">${escapeHtml(product.model_number)}</div>
            </div>
        `;
    }
    
    // Custom fields from dynamic_fields
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
                    <p style="font-size: 13px; color: #2563eb; margin: 0 0 4px 0;">${product.category ? product.category.name : 'No Category'}</p>
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
    
    // Get all custom fields from the form
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
        
        // Trigger category change to load custom fields
        await updateDynamicFields();
        
        // Populate custom fields after they're loaded
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