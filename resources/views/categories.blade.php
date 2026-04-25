@extends('layouts.app')

@section('title', 'Categories')
@section('content')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"></h1>
        </div>
        <button onclick="resetForm(); showForm()"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-lg transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Category
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="summary-card border-gray-200">
            <p class="text-xs font-semibold uppercase text-gray-500">Total Categories</p>
            <p class="mt-2 text-2xl font-bold text-gray-900" id="totalCategoriesCount">0</p>
        </div>
        <div class="summary-card border-blue-200">
            <p class="text-xs font-semibold uppercase text-gray-500">With Products</p>
            <p class="mt-2 text-2xl font-bold text-blue-600" id="categoriesWithProducts">0</p>
        </div>
        <div class="summary-card border-indigo-200">
            <p class="text-xs font-semibold uppercase text-gray-500">Custom Fields</p>
            <p class="mt-2 text-2xl font-bold text-indigo-600" id="totalCustomFields">0</p>
        </div>
        <div class="summary-card border-green-200">
            <p class="text-xs font-semibold uppercase text-gray-500">Total Products</p>
            <p class="mt-2 text-2xl font-bold text-green-600" id="totalProductsInCategories">0</p>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="section-header">
            <h2 class="text-gray-900 font-semibold">Category Library</h2>
            <p class="text-sm text-gray-500 mt-0.5">Review, edit, and maintain product categories.</p>
        </div>
        <div class="p-6">
            <div id="categoryContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div id="modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <div>
                <h2 class="text-xl font-bold text-gray-900" id="modalTitle">Add Category</h2>
                <p class="text-sm text-gray-500 mt-0.5">Set the category name, image, and product fields.</p>
            </div>
            <button type="button" onclick="hideForm()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>
        <form id="categoryForm" enctype="multipart/form-data">
            <div class="p-6 max-h-[70vh] overflow-y-auto">
                <input type="hidden" id="category_id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold uppercase text-gray-500 mb-2">Category Name</label>
                        <input type="text" id="name" placeholder="Category Name" class="w-full border border-gray-300 px-3 py-2 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="image" class="block text-xs font-semibold uppercase text-gray-500 mb-2">Category Image</label>
                        <input type="file" id="image" accept="image/*" class="w-full border border-gray-300 px-3 py-2 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <img id="preview" class="hidden h-28 w-full max-w-xs mx-auto mb-4 rounded-lg object-cover border border-gray-200">
            
                <!-- Field Schema Builder -->
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <div>
                            <h3 class="font-semibold text-gray-900">Product Fields</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Define fields shown when adding products under this category.</p>
                        </div>
                        <button type="button" onclick="addField()" class="inline-flex items-center justify-center gap-2 px-3 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Field
                        </button>
                    </div>
                
                    <div id="fieldsContainer" class="space-y-3">
                        <!-- Fields will be added here -->
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:justify-end gap-2 px-6 py-4 border-t border-gray-200 bg-gray-50">
                <button type="button" onclick="hideForm()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100 transition">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg font-semibold hover:bg-gray-800 transition">Save Category</button>
            </div>
        </form>
    </div>
</div>

<!-- CONFIRMATION MODAL -->
<div id="confirmModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white p-6 rounded-xl w-full max-w-sm text-center shadow-2xl">
        <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold mb-2 text-gray-900">Confirm Delete</h3>
        <p class="text-gray-600 mb-5 text-sm leading-relaxed" id="confirmMessage">Are you sure you want to delete this category?</p>
        <div class="flex justify-center gap-3">
            <button onclick="hideConfirmModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100 transition">Cancel</button>
            <button id="confirmButton" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">Delete</button>
        </div>
    </div>
</div>

<!-- TOAST NOTIFICATION -->
<div id="toast" class="hidden fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 text-sm font-medium">
    <span id="toastMessage"></span>
</div>
<script>
let pendingDeleteId = null;

function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    toastMessage.textContent = message;
    
    if (isError) {
        toast.classList.remove('bg-green-600');
        toast.classList.add('bg-red-600');
    } else {
        toast.classList.remove('bg-red-600');
        toast.classList.add('bg-green-600');
    }
    
    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

function addField(fieldData = null) {
    const container = document.getElementById('fieldsContainer');
    
    const defaultField = fieldData || { name: '', label: '', type: 'text', required: false, options: '' };
    
    const fieldDiv = document.createElement('div');
    fieldDiv.className = 'field-item bg-white p-4 rounded-lg border border-gray-200 shadow-sm';
    
    fieldDiv.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
            <div>
                <label class="text-xs font-semibold uppercase text-gray-500">Field Name</label>
                <input type="text" class="field-name w-full border border-gray-300 px-3 py-2 rounded-lg text-sm mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., socket_chipset" value="${escapeHtml(defaultField.name || '')}">
            </div>
            <div>
                <label class="text-xs font-semibold uppercase text-gray-500">Display Label</label>
                <input type="text" class="field-label w-full border border-gray-300 px-3 py-2 rounded-lg text-sm mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Socket / Chipset" value="${escapeHtml(defaultField.label || '')}">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-semibold uppercase text-gray-500">Field Type</label>
                <select class="field-type w-full border border-gray-300 px-3 py-2 rounded-lg text-sm mt-1 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="text" ${defaultField.type === 'text' ? 'selected' : ''}>Text</option>
                    <option value="textarea" ${defaultField.type === 'textarea' ? 'selected' : ''}>Textarea</option>
                    <option value="number" ${defaultField.type === 'number' ? 'selected' : ''}>Number</option>
                    <option value="select" ${defaultField.type === 'select' ? 'selected' : ''}>Select</option>
                </select>
            </div>
            <div class="flex items-end justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" class="field-required" ${defaultField.required ? 'checked' : ''}>
                    <span class="text-xs font-medium">Required</span>
                </label>
                <button type="button" onclick="this.closest('.field-item').remove()" class="text-red-600 text-sm font-semibold hover:text-red-700">Remove</button>
            </div>
        </div>
        <div class="field-options-container mt-2" style="display: ${defaultField.type === 'select' ? 'block' : 'none'}">
            <label class="text-xs font-semibold uppercase text-gray-500">Options</label>
            <input type="text" class="field-options w-full border border-gray-300 px-3 py-2 rounded-lg text-sm mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Option 1, Option 2, Option 3" value="${escapeHtml(defaultField.options || '')}">
        </div>
    `;
    
    const typeSelect = fieldDiv.querySelector('.field-type');
    typeSelect.addEventListener('change', function() {
        const optionsContainer = fieldDiv.querySelector('.field-options-container');
        optionsContainer.style.display = this.value === 'select' ? 'block' : 'none';
    });
    
    container.appendChild(fieldDiv);
}

function getFieldsSchema() {
    const fields = [];
    document.querySelectorAll('.field-item').forEach(item => {
        const field = {
            name: item.querySelector('.field-name').value,
            label: item.querySelector('.field-label').value,
            type: item.querySelector('.field-type').value,
            required: item.querySelector('.field-required').checked
        };
        if (field.type === 'select') {
            const optionsInput = item.querySelector('.field-options');
            if (optionsInput && optionsInput.value) {
                field.options = optionsInput.value.split(',').map(opt => opt.trim());
            }
        }
        if (field.name && field.label) {
            fields.push(field);
        }
    });
    return fields;
}

function loadFieldsSchema(schema) {
    const container = document.getElementById('fieldsContainer');
    container.innerHTML = '';
    
    if (schema && Array.isArray(schema) && schema.length > 0) {
        schema.forEach(field => {
            addField(field);
        });
    }
}

function resetForm() {
    document.getElementById('modalTitle').innerText = 'Add Category';
    document.getElementById('category_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('preview').classList.add('hidden');
    document.getElementById('preview').src = '';
    document.getElementById('image').value = '';
    loadFieldsSchema([]);
}

function showForm() {
    document.getElementById('modal').classList.remove('hidden');
}

function hideForm() {
    document.getElementById('modal').classList.add('hidden');
}

function showConfirmModal(categoryId, categoryName) {
    pendingDeleteId = categoryId;
    document.getElementById('confirmMessage').innerHTML = `Are you sure you want to delete category <strong>"${escapeHtml(categoryName)}"</strong>?<br>This will also affect products in this category.`;
    document.getElementById('confirmModal').classList.remove('hidden');
}

function hideConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    pendingDeleteId = null;
}

async function loadCategories() {
    try {
        const res = await fetch('/api/categories');
        const categories = await res.json();
        
        const container = document.getElementById('categoryContainer');
        container.innerHTML = '';
        
        let categoriesWithProducts = 0;
        let totalCustomFields = 0;
        let totalProducts = 0;
        
        if (categories.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No categories yet</h3>
                    <p class="text-gray-500 mb-4">Click the Add Category button to create your first category</p>
                    <button onclick="resetForm(); showForm()" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">Add Category</button>
                </div>
            `;
            document.getElementById('totalCategoriesCount').innerText = 0;
            document.getElementById('categoriesWithProducts').innerText = 0;
            document.getElementById('totalCustomFields').innerText = 0;
            document.getElementById('totalProductsInCategories').innerText = 0;
            return;
        }
        
        categories.forEach(cat => {
            const formattedId = 'CA' + String(cat.id).padStart(3, '0');
            // Use products_count from the API response
            const productCount = cat.products_count || 0;
            totalProducts += productCount;
            if (productCount > 0) categoriesWithProducts++;
            
            let fieldsCount = 0;
            if (cat.fields_schema) {
                try {
                    const schema = typeof cat.fields_schema === 'string' ? JSON.parse(cat.fields_schema) : cat.fields_schema;
                    fieldsCount = Array.isArray(schema) ? schema.length : 0;
                    totalCustomFields += fieldsCount;
                } catch(e) { fieldsCount = 0; }
            }
            
            container.innerHTML += `
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-200">
                    <div class="relative h-36 overflow-hidden bg-gray-100">
                        ${cat.image
                            ? `<img src="/storage/${cat.image}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">`
                            : `<div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                               </div>`
                        }
                        <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="editCategory(${cat.id})" class="p-2 bg-white rounded-lg shadow-md hover:bg-gray-100 transition" title="Edit category">
                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <button onclick="showConfirmModal(${cat.id}, '${escapeHtml(cat.name)}')" class="p-2 bg-white rounded-lg shadow-md hover:bg-red-50 transition" title="Delete category">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="absolute bottom-3 left-3">
                            <span class="px-2 py-1 bg-white/95 backdrop-blur-sm rounded-md text-gray-800 text-xs font-bold shadow-sm">${formattedId}</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 text-base mb-1 truncate">${escapeHtml(cat.name)}</h3>
                        <div class="flex items-center justify-between gap-3 mt-4">
                            <div class="flex items-center gap-2 min-w-0">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="text-sm text-gray-600">${productCount} products</span>
                            </div>
                            ${fieldsCount > 0 ? `
                            <div class="flex items-center gap-1 px-2 py-1 bg-indigo-50 rounded-full">
                                <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-xs font-semibold text-indigo-700">${fieldsCount} fields</span>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        document.getElementById('totalCategoriesCount').innerText = categories.length;
        document.getElementById('categoriesWithProducts').innerText = categoriesWithProducts;
        document.getElementById('totalCustomFields').innerText = totalCustomFields;
        document.getElementById('totalProductsInCategories').innerText = totalProducts;
        
    } catch (error) {
        console.error('Error:', error);
        showToast('Error loading categories', true);
    }
}

async function editCategory(id) {
    try {
        const res = await fetch(`/api/categories/${id}`);
        const category = await res.json();
        
        document.getElementById('category_id').value = category.id;
        document.getElementById('name').value = category.name || '';
        document.getElementById('modalTitle').innerText = 'Edit Category';
        
        if (category.image) {
            const preview = document.getElementById('preview');
            preview.src = `/storage/${category.image}`;
            preview.classList.remove('hidden');
        } else {
            document.getElementById('preview').classList.add('hidden');
            document.getElementById('preview').src = '';
        }
        
        let fieldsSchema = [];
        if (category.fields_schema) {
            try {
                fieldsSchema = typeof category.fields_schema === 'string' 
                    ? JSON.parse(category.fields_schema) 
                    : category.fields_schema;
            } catch(e) {
                fieldsSchema = [];
            }
        }
        loadFieldsSchema(fieldsSchema);
        
        showForm();
    } catch (error) {
        console.error('Error loading category:', error);
        showToast('Error loading category data', true);
    }
}

async function saveCategory(event) {
    event.preventDefault();
    
    const name = document.getElementById('name').value;
    if (!name) {
        showToast('Please enter a category name', true);
        return;
    }
    
    const categoryId = document.getElementById('category_id').value;
    const imageFile = document.getElementById('image').files[0];
    const fieldsSchema = getFieldsSchema();
    
    const formData = new FormData();
    formData.append('name', name);
    formData.append('fields_schema', JSON.stringify(fieldsSchema));
    
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    let url = '/api/categories';
    let method = 'POST';
    
    if (categoryId) {
        url = `/api/categories/${categoryId}`;
        method = 'POST';
        formData.append('_method', 'PUT');
    }
    
    try {
        const res = await fetch(url, {
            method: method,
            body: formData
        });
        
        if (res.ok) {
            const action = categoryId ? 'updated' : 'added';
            showToast(`Category ${action} successfully!`);
            hideForm();
            resetForm();
            await loadCategories();
        } else {
            const error = await res.text();
            console.error('Server error:', error);
            showToast('Error saving category', true);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error saving category', true);
    }
}

async function deleteCategory() {
    if (!pendingDeleteId) return;
    
    try {
        const res = await fetch(`/api/categories/${pendingDeleteId}`, {
            method: 'DELETE'
        });
        
        if (res.ok) {
            showToast('Category deleted successfully!');
            hideConfirmModal();
            await loadCategories();
        } else {
            showToast('Error deleting category', true);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error deleting category', true);
    }
}

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

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    
    const form = document.getElementById('categoryForm');
    if (form) {
        form.addEventListener('submit', saveCategory);
    }
    
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', previewImage);
    }
    
    const confirmDeleteBtn = document.getElementById('confirmButton');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', deleteCategory);
    }
});
</script>

<style>
    .summary-card {
        background: #ffffff;
        border-width: 1px;
        border-left-width: 4px;
        border-radius: 0.75rem;
        padding: 1rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }
    .section-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
    }
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce {
        animation: bounce 0.5s ease-in-out;
    }
</style>

@endsection

