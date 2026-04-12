@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-8 text-center">CATEGORY</h1>

<!-- 🔹 GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

    <!-- 🔹 CATEGORY CARDS -->
    <div id="categoryContainer" class="contents"></div>

    <!-- 🔹 ADD CARD -->
    <div onclick="resetForm(); showForm()"
        class="bg-gray-300 h-80 flex flex-col items-center justify-center rounded-xl
                cursor-pointer transition-all duration-300
                hover:bg-gray-400 hover:scale-105 hover:shadow-lg
                hover:ring-2 hover:ring-blue-400">

        <div class="text-4xl font-bold text-gray-700">+</div>
        <div class="text-xs mt-2 text-gray-700 font-medium">
            ADD CATEGORY
        </div>

    </div>

</div>

<!-- 🔹 MODAL (Same modal for Add and Edit - like Product page) -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4" id="modalTitle">Add Category</h2>
        <form id="categoryForm" enctype="multipart/form-data">
            <input type="hidden" id="category_id">
            <input type="text" id="name" placeholder="Category Name" class="w-full border p-2 mb-3 rounded" required>
            <input type="file" id="image" accept="image/*" class="w-full border p-2 mb-3 rounded">
            <img id="preview" class="hidden h-24 mx-auto mb-3 rounded object-cover">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="hideForm()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- 🔹 CONFIRMATION MODAL -->
<div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-96 text-center">
        <div class="text-6xl mb-4">⚠️</div>
        <h3 class="text-xl font-bold mb-2" id="confirmTitle">Confirm Delete</h3>
        <p class="text-gray-600 mb-4" id="confirmMessage">Are you sure you want to delete this category?</p>
        <div class="flex justify-center gap-3">
            <button onclick="hideConfirmModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button id="confirmButton" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
        </div>
    </div>
</div>

<!-- 🔹 TOAST NOTIFICATION -->
<div id="toast" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce">
    <span id="toastMessage"></span>
</div>

<script>
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

// Reset form (for adding new category) - like Product page
function resetForm() {
    document.getElementById('modalTitle').innerText = 'Add Category';
    document.getElementById('category_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('preview').classList.add('hidden');
    document.getElementById('preview').src = '';
    document.getElementById('image').value = '';
}

// Show modal (just shows it, does NOT reset - like Product page)
function showForm() {
    document.getElementById('modal').classList.remove('hidden');
}

// Hide modal
function hideForm() {
    document.getElementById('modal').classList.add('hidden');
}

// Show confirm modal for delete
let pendingDeleteId = null;
let pendingDeleteName = null;

function showConfirmModal(categoryId, categoryName) {
    pendingDeleteId = categoryId;
    pendingDeleteName = categoryName;
    document.getElementById('confirmMessage').innerHTML = `Are you sure you want to delete category <strong>"${escapeHtml(categoryName)}"</strong>?<br>This will also affect products in this category.`;
    document.getElementById('confirmModal').classList.remove('hidden');
}

function hideConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    pendingDeleteId = null;
    pendingDeleteName = null;
}

// Load categories
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
            // Format ID as CA001, CA002, etc.
            const formattedId = 'CA' + String(cat.id).padStart(3, '0');
            
            container.innerHTML += `
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300" style="border-radius: 12px;">
                <!-- Header with action buttons -->
                <div style="background-color: #1a1f2e; padding: 5px 16px 24px 16px; display: flex; justify-content: flex-end; align-items: center; gap: 20px;">
                    <button onclick="editCategory(${cat.id})" style="color: white; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; letter-spacing: 0.5px; opacity: 0.9;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        EDIT
                    </button>
                    <button onclick="showConfirmModal(${cat.id}, '${escapeHtml(cat.name)}')" style="color: white; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; letter-spacing: 0.5px; opacity: 0.9;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        DELETE
                    </button>
                </div>

                <!-- Image -->
                <div style="height: 160px; background-color: #e5e7eb; overflow: hidden; border-radius: 20px; margin-top: -20px; border-top: 2px solid white;">
                    ${cat.image
                        ? `<img src="/storage/${cat.image}" style="width: 100%; height: 100%; object-fit: cover;">`
                        : '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><span style="font-size:12px;color:#9ca3af;">NO IMAGE</span></div>'
                    }
                </div>

                <!-- Info section -->
                <div style="padding: 14px 16px 16px;">
                    <h2 style="font-size: 15px; font-weight: 800; color: #111827; margin: 0 0 6px 0; letter-spacing: 0.2px;">
                        ${escapeHtml(cat.name)}
                    </h2>
                    <p style="font-size: 13px; color: #4b5563; margin: 0 0 4px 0;">Total products: ${cat.products_count || 0}</p>
                    <p style="font-size: 13px; color: #2563eb; font-weight: 500; margin: 0;">${formattedId}</p>
                </div>
            </div>
        `;
        });
    } catch (error) {
        console.error('Error:', error);
        showToast('Error loading categories', true);
    }
}

// Edit category (populates form then shows modal - like Product page)
async function editCategory(id) {
    try {
        console.log('Fetching category ID:', id);
        
        const res = await fetch(`/api/categories/${id}`);
        const category = await res.json();
        
        console.log('Category data received:', category);
        
        // Populate the form fields (like Product page)
        document.getElementById('category_id').value = category.id;
        document.getElementById('name').value = category.name || '';
        
        // Show existing image if any (like Product page)
        if (category.image) {
            const preview = document.getElementById('preview');
            preview.src = `/storage/${category.image}`;
            preview.classList.remove('hidden');
        } else {
            document.getElementById('preview').classList.add('hidden');
            document.getElementById('preview').src = '';
        }
        
        // Change modal title (like Product page)
        document.getElementById('modalTitle').innerText = 'Edit Category';
        
        // Show the modal (like Product page)
        showForm();
        
    } catch (error) {
        console.error('Error loading category:', error);
        showToast('Error loading category data', true);
    }
}

// Save category (ADD or UPDATE)
async function saveCategory(event) {
    event.preventDefault();
    
    const name = document.getElementById('name').value;
    if (!name) {
        showToast('Please enter a category name', true);
        return;
    }
    
    const categoryId = document.getElementById('category_id').value;
    const imageFile = document.getElementById('image').files[0];
    
    const formData = new FormData();
    formData.append('name', name);
    
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
            resetForm(); // Reset after successful save
            await loadCategories(); // Reload the list
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

// Delete category
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

// Set up event listeners
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

<!-- Add some CSS animations -->
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