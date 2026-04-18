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
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Category
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-gray-900" id="totalCategoriesCount">0</p>
            <p class="text-xs text-gray-500">Total Categories</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-gray-900" id="categoriesWithProducts">0</p>
            <p class="text-xs text-gray-500">With Products</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-gray-900" id="totalCustomFields">0</p>
            <p class="text-xs text-gray-500">Custom Fields</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-gray-900" id="totalProductsInCategories">0</p>
            <p class="text-xs text-gray-500">Total Products</p>
        </div>
    </div>

    <!-- Categories Grid -->
    <div id="categoryContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>
</div>

<!-- MODAL -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold mb-4" id="modalTitle">Add Category</h2>
        <form id="categoryForm" enctype="multipart/form-data">
            <input type="hidden" id="category_id">
            <input type="text" id="name" placeholder="Category Name" class="w-full border p-2 mb-3 rounded" required>
            <input type="file" id="image" accept="image/*" class="w-full border p-2 mb-3 rounded">
            <img id="preview" class="hidden h-24 mx-auto mb-3 rounded object-cover">
            
            <!-- Field Schema Builder -->
            <div class="border rounded-lg p-4 mb-4">
                <h3 class="font-bold mb-3 text-gray-700">Product Fields Configuration</h3>
                <p class="text-xs text-gray-500 mb-3">Define what fields should appear when adding products under this category</p>
                
                <div id="fieldsContainer" class="space-y-2 mb-3">
                    <!-- Fields will be added here -->
                </div>
                
                <button type="button" onclick="addField()" class="text-blue-500 text-sm hover:text-blue-700 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Field
                </button>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="hideForm()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- CONFIRMATION MODAL -->
<div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-96 text-center">
        <div class="text-6xl mb-4">⚠️</div>
        <h3 class="text-xl font-bold mb-2">Confirm Delete</h3>
        <p class="text-gray-600 mb-4" id="confirmMessage">Are you sure you want to delete this category?</p>
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

function addField(fieldData = null) {
    const container = document.getElementById('fieldsContainer');
    
    const defaultField = fieldData || { name: '', label: '', type: 'text', required: false, options: '' };
    
    const fieldDiv = document.createElement('div');
    fieldDiv.className = 'field-item bg-gray-50 p-3 rounded-lg border mb-2';
    
    fieldDiv.innerHTML = `
        <div class="grid grid-cols-2 gap-2 mb-2">
            <div>
                <label class="text-xs text-gray-600">Field Name (database column)</label>
                <input type="text" class="field-name w-full border p-1 rounded text-sm" placeholder="e.g., socket_chipset" value="${escapeHtml(defaultField.name || '')}">
            </div>
            <div>
                <label class="text-xs text-gray-600">Display Label</label>
                <input type="text" class="field-label w-full border p-1 rounded text-sm" placeholder="e.g., Socket / Chipset" value="${escapeHtml(defaultField.label || '')}">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="text-xs text-gray-600">Field Type</label>
                <select class="field-type w-full border p-1 rounded text-sm">
                    <option value="text" ${defaultField.type === 'text' ? 'selected' : ''}>Text</option>
                    <option value="textarea" ${defaultField.type === 'textarea' ? 'selected' : ''}>Textarea</option>
                    <option value="number" ${defaultField.type === 'number' ? 'selected' : ''}>Number</option>
                    <option value="select" ${defaultField.type === 'select' ? 'selected' : ''}>Select</option>
                </select>
            </div>
            <div class="flex items-end justify-between">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" class="field-required" ${defaultField.required ? 'checked' : ''}>
                    <span class="text-xs text-gray-600">Required</span>
                </label>
                <button type="button" onclick="this.closest('.field-item').remove()" class="text-red-500 text-sm hover:text-red-700">Remove</button>
            </div>
        </div>
        <div class="field-options-container mt-2" style="display: ${defaultField.type === 'select' ? 'block' : 'none'}">
            <label class="text-xs text-gray-600">Options (comma separated)</label>
            <input type="text" class="field-options w-full border p-1 rounded text-sm" placeholder="Option 1, Option 2, Option 3" value="${escapeHtml(defaultField.options || '')}">
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
                    <button onclick="resetForm(); showForm()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Add Category</button>
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
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="relative h-40 overflow-hidden">
                        ${cat.image
                            ? `<img src="/storage/${cat.image}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">`
                            : `<div class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                               </div>`
                        }
                        <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="editCategory(${cat.id})" class="p-2 bg-white rounded-xl shadow-md hover:bg-gray-100 transition">
                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <button onclick="showConfirmModal(${cat.id}, '${escapeHtml(cat.name)}')" class="p-2 bg-white rounded-xl shadow-md hover:bg-red-50 transition">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="absolute bottom-3 left-3">
                            <span class="px-2 py-1 bg-black/50 backdrop-blur-sm rounded-lg text-white text-xs font-medium">${formattedId}</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 text-lg mb-1">${escapeHtml(cat.name)}</h3>
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="text-sm text-gray-600">${productCount} products</span>
                            </div>
                            ${fieldsCount > 0 ? `
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-xs text-gray-500">${fieldsCount} fields</span>
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
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce {
        animation: bounce 0.5s ease-in-out;
    }
</style>

@endsection