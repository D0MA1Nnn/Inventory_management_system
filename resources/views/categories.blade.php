@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-8 text-center">CATEGORY</h1>

<!-- GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div id="categoryContainer" class="contents"></div>
    
    <div onclick="resetForm(); showForm()"
        class="bg-gray-300 h-80 flex flex-col items-center justify-center rounded-xl
                cursor-pointer transition-all duration-300
                hover:bg-gray-400 hover:scale-105 hover:shadow-lg
                hover:ring-2 hover:ring-blue-400">
        <div class="text-4xl font-bold text-gray-700">+</div>
        <div class="text-xs mt-2 text-gray-700 font-medium">ADD CATEGORY</div>
    </div>
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
    
    // Add event listener for type change
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
        
        if (categories.length === 0) {
            container.innerHTML = '<div class="col-span-4 text-center text-gray-500 py-8">No categories yet. Click + ADD CATEGORY to create one!</div>';
            return;
        }
        
        categories.forEach(cat => {
            const formattedId = 'CA' + String(cat.id).padStart(3, '0');
            
            // Safely check fields_schema
            let fieldsCount = 0;
            if (cat.fields_schema) {
                try {
                    const schema = typeof cat.fields_schema === 'string' ? JSON.parse(cat.fields_schema) : cat.fields_schema;
                    fieldsCount = Array.isArray(schema) ? schema.length : 0;
                } catch(e) {
                    fieldsCount = 0;
                }
            }
            
            container.innerHTML += `
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300" style="border-radius: 12px;">
                <div style="background-color: #1a1f2e; padding: 5px 16px 24px 16px; display: flex; justify-content: flex-end; align-items: center; gap: 20px;">
                    <button onclick="editCategory(${cat.id})" style="color: white; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        EDIT
                    </button>
                    <button onclick="showConfirmModal(${cat.id}, '${escapeHtml(cat.name)}')" style="color: white; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        DELETE
                    </button>
                </div>
                <div style="height: 160px; background-color: #e5e7eb; overflow: hidden; border-radius: 20px; margin-top: -20px; border-top: 2px solid white;">
                    ${cat.image
                        ? `<img src="/storage/${cat.image}" style="width: 100%; height: 100%; object-fit: cover;">`
                        : '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><span style="font-size:12px;color:#9ca3af;">NO IMAGE</span></div>'
                    }
                </div>
                <div style="padding: 14px 16px 16px;">
                    <h2 style="font-size: 15px; font-weight: 800; color: #111827; margin: 0 0 6px 0;">${escapeHtml(cat.name)}</h2>
                    <p style="font-size: 13px; color: #4b5563; margin: 0 0 4px 0;">Total products: ${cat.products_count || 0}</p>
                    <p style="font-size: 13px; color: #2563eb; font-weight: 500; margin: 0;">${formattedId}</p>
                    ${fieldsCount > 0 ? `<p style="font-size: 11px; color: #6b7280; margin-top: 8px;">📋 ${fieldsCount} custom fields</p>` : ''}
                </div>
            </div>
        `;
        });
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
        
        // Load fields schema
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