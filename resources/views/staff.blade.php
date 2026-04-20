@extends('layouts.app')

@section('title', 'Staff Management')
@section('content')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"></h1>
        </div>

        @if(auth()->user()->role === 'admin')
            <button onclick="openStaffModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition shadow-sm">
                
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>

                Add Staff
            </button>
        @endif
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-gray-900" id="totalStaff">0</p>
            <p class="text-xs text-gray-500">Total Staff</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-green-600" id="activeStaff">0</p>
            <p class="text-xs text-gray-500">Active</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-amber-600" id="inactiveStaff">0</p>
            <p class="text-xs text-gray-500">Inactive</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-red-600" id="suspendedStaff">0</p>
            <p class="text-xs text-gray-500">Suspended</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-blue-600" id="adminStaff">0</p>
            <p class="text-xs text-gray-500">Admins</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-2xl font-bold text-purple-600" id="managerStaff">0</p>
            <p class="text-xs text-gray-500">Managers</p>
        </div>
    </div>

    <!-- Staff Grid -->
    <div id="staffContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>
</div>

<!-- Staff Modal -->
<div id="staffModal" class="modal-bg" onclick="if(event.target===this)closeStaffModal()">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 sticky top-0 bg-white z-10 border-b">
            <span id="modalTitle" class="text-xl font-bold text-gray-900">Add Staff</span>
            <button class="bg-transparent border-none text-2xl cursor-pointer text-gray-500 hover:text-gray-700 transition" onclick="closeStaffModal()">✕</button>
        </div>
        <div class="p-6">
            <form id="staffForm" enctype="multipart/form-data">
                <input type="hidden" id="staff_id">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Profile Image</label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden" id="profilePreviewContainer">
                                <img id="profilePreview" src="" class="w-full h-full object-cover hidden">
                                <span id="noImageText" class="text-gray-400 text-sm">No Image</span>
                            </div>
                            <input type="file" id="profile_image" accept="image/*" class="flex-1 border border-gray-300 rounded-xl p-2 text-sm">
                        </div>
                    </div>
                    
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Full Name *</label>
                        <input type="text" id="name" required class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Email *</label>
                        <input type="email" id="email" required class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                        <input type="password" id="password" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave blank to keep current">
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                    </div>
                    
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Role *</label>
                        <select id="role" name="role" required class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="staff">Staff</option>
                            <option value="manager">Manager</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Phone Number</label>
                        <input type="text" id="phone" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Position</label>
                        <input type="text" id="position" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Salary (₱)</label>
                        <input type="number" id="salary" step="0.01" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Hire Date</label>
                        <input type="date" id="hire_date" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Status *</label>
                        <select id="status" name="status" required class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Address</label>
                        <textarea id="address" rows="2" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeStaffModal()" class="flex-1 py-3 bg-gray-200 rounded-xl font-semibold hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit" class="flex-1 py-3 bg-blue-600 rounded-xl text-white font-semibold hover:bg-blue-700 transition">Save Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Staff Modal -->
<div id="viewStaffModal" class="modal-bg" onclick="if(event.target===this)closeViewModal()">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 sticky top-0 bg-white z-10 border-b">
            <span id="viewTitle" class="text-xl font-bold text-gray-900">Staff Details</span>
            <button class="bg-transparent border-none text-2xl cursor-pointer text-gray-500 hover:text-gray-700 transition" onclick="closeViewModal()">✕</button>
        </div>
        <div id="viewContent" class="p-6"></div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="modal-bg">
    <div class="bg-white rounded-2xl w-[350px] max-w-[90%] text-center p-6 shadow-2xl">
        <div class="text-6xl mb-4">⚠️</div>
        <div class="text-lg font-bold mb-2 text-gray-900">Confirm Delete</div>
        <div id="confirmMessage" class="text-sm text-gray-600 mb-5">Are you sure you want to delete this staff member?</div>
        <div class="flex gap-3">
            <button onclick="closeConfirmModal()" class="flex-1 py-3 bg-gray-200 rounded-xl cursor-pointer text-sm font-semibold text-gray-700 hover:bg-gray-300 transition">Cancel</button>
            <button id="confirmDeleteBtn" class="flex-1 py-3 bg-red-600 rounded-xl cursor-pointer text-sm font-semibold text-white hover:bg-red-700 transition">Delete</button>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div id="successToast" class="fixed bottom-5 right-5 bg-green-600 text-white py-3 px-5 rounded-xl text-sm z-[300] hidden shadow-lg">Success!</div>

<style>
.modal-bg {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 200;
    align-items: center;
    justify-content: center;
}
.modal-bg.open {
    display: flex;
}
</style>

<script>
    let pendingDeleteId = null;

    function showToast(message, isError = false) {
        const toast = document.getElementById('successToast');
        toast.innerText = message;
        toast.classList.remove('hidden');
        if (isError) {
            toast.classList.add('bg-red-600');
            toast.classList.remove('bg-green-600');
        } else {
            toast.classList.add('bg-green-600');
            toast.classList.remove('bg-red-600');
        }
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    function openStaffModal() {
        document.getElementById('modalTitle').innerText = 'Add Staff';
        document.getElementById('staff_id').value = '';
        document.getElementById('staffForm').reset();
        document.getElementById('profilePreview').classList.add('hidden');
        document.getElementById('noImageText').classList.remove('hidden');
        document.getElementById('role').value = 'staff';
        document.getElementById('status').value = 'active';
        document.getElementById('staffModal').classList.add('open');
    }

    function closeStaffModal() {
        document.getElementById('staffModal').classList.remove('open');
    }

    function closeViewModal() {
        document.getElementById('viewStaffModal').classList.remove('open');
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.remove('open');
        pendingDeleteId = null;
    }

    function showConfirmModal(id) {
        pendingDeleteId = id;
        document.getElementById('confirmModal').classList.add('open');
    }

    async function loadStaff() {
        try {
            const [staffRes, statsRes] = await Promise.all([
                fetch('/api/staff'),
                fetch('/api/staff/stats')
            ]);
            
            if (!staffRes.ok) throw new Error('Failed to load staff');
            if (!statsRes.ok) throw new Error('Failed to load stats');
            
            const staff = await staffRes.json();
            const stats = await statsRes.json();
            
            updateStats(stats);
            displayStaff(staff);
            
        } catch (error) {
            console.error('REAL ERROR:', error);
        }
    }

    function updateStats(stats) {
        document.getElementById('totalStaff').innerText = stats.total || 0;
        document.getElementById('activeStaff').innerText = stats.active || 0;
        document.getElementById('inactiveStaff').innerText = stats.inactive || 0;
        document.getElementById('suspendedStaff').innerText = stats.suspended || 0;
        document.getElementById('adminStaff').innerText = stats.admin || 0;
        document.getElementById('managerStaff').innerText = stats.manager || 0;
    }

    function displayStaff(staff) {
        const container = document.getElementById('staffContainer');
        
        if (!staff || staff.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-12 bg-white rounded-2xl">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No staff members</h3>
                    <p class="text-gray-500 mb-4">Click the Add Staff button to create your first staff account</p>
                    <button onclick="openStaffModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Add Staff</button>
                </div>
            `;
            return;
        }
        
        const statusColors = {
            active: 'bg-green-100 text-green-700',
            inactive: 'bg-amber-100 text-amber-700',
            suspended: 'bg-red-100 text-red-700'
        };
        
        const roleColors = {
            admin: 'bg-purple-100 text-purple-700',
            manager: 'bg-blue-100 text-blue-700',
            staff: 'bg-gray-100 text-gray-700'
        };
        
        container.innerHTML = staff.map(member => `
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                <div class="relative h-32 overflow-hidden bg-gradient-to-br from-blue-400 to-purple-500">
                    <div class="absolute inset-0 flex items-center justify-center">
                        ${member.profile_image 
                            ? `<img src="/storage/${member.profile_image}" class="w-20 h-20 rounded-full border-4 border-white object-cover shadow-lg">`
                            : `<div class="w-20 h-20 rounded-full bg-white/30 backdrop-blur-sm flex items-center justify-center">
                                <span class="text-white font-bold text-2xl">${escapeHtml(member.name.charAt(0))}</span>
                               </div>`
                        }
                    </div>
                    <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="viewStaff(${member.id})" class="p-2 bg-white rounded-xl shadow-md hover:bg-gray-100 transition">
                            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        <button onclick="editStaff(${member.id})" class="p-2 bg-white rounded-xl shadow-md hover:bg-gray-100 transition">
                            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        <button onclick="showConfirmModal(${member.id})" class="p-2 bg-white rounded-xl shadow-md hover:bg-red-50 transition">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-gray-900 text-lg mb-1">${escapeHtml(member.name)}</h3>
                    <p class="text-sm text-gray-500 mb-2">${escapeHtml(member.email)}</p>
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${roleColors[member.role] || 'bg-gray-100 text-gray-700'}">${(member.role || 'staff').toUpperCase()}</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${statusColors[member.status] || 'bg-green-100 text-green-700'}">${(member.status || 'active').toUpperCase()}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        ${member.phone || 'No phone'}
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        ${member.position || 'No position'}
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs text-gray-400">Joined ${new Date(member.created_at).toLocaleDateString()}</span>
                        <button onclick="viewStaff(${member.id})" class="text-blue-600 text-sm font-medium hover:text-blue-700 transition">View Details →</button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    async function viewStaff(id) {
        try {
            const res = await fetch(`/api/staff/${id}`);
            if (!res.ok) throw new Error('Failed to load staff details');
            
            const staff = await res.json();
            
            document.getElementById('viewTitle').innerText = staff.name;
            document.getElementById('viewContent').innerHTML = `
                <div class="flex flex-col items-center mb-6">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center mb-3">
                        ${staff.profile_image 
                            ? `<img src="/storage/${staff.profile_image}" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">`
                            : `<span class="text-white font-bold text-4xl">${escapeHtml(staff.name.charAt(0))}</span>`
                        }
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">${escapeHtml(staff.name)}</h2>
                    <p class="text-gray-500">${escapeHtml(staff.email)}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Role</label>
                        <p class="font-medium text-gray-900">${(staff.role || 'staff').toUpperCase()}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Status</label>
                        <p class="font-medium text-gray-900 capitalize">${staff.status || 'active'}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Phone</label>
                        <p class="font-medium text-gray-900">${staff.phone || 'N/A'}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Position</label>
                        <p class="font-medium text-gray-900">${staff.position || 'N/A'}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Salary</label>
                        <p class="font-medium text-gray-900">${staff.salary ? '₱' + parseFloat(staff.salary).toLocaleString() : 'N/A'}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Hire Date</label>
                        <p class="font-medium text-gray-900">${staff.hire_date ? new Date(staff.hire_date).toLocaleDateString() : 'N/A'}</p>
                    </div>
                    <div class="col-span-2 bg-gray-50 p-3 rounded-xl">
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Address</label>
                        <p class="font-medium text-gray-900">${staff.address || 'N/A'}</p>
                    </div>
                </div>
            `;
            document.getElementById('viewStaffModal').classList.add('open');
        } catch (error) {
            console.error('Error viewing staff:', error);
            showToast('Error loading staff details', true);
        }
    }

    async function editStaff(id) {
        try {
            const res = await fetch(`/api/staff/${id}`);
            if (!res.ok) throw new Error('Failed to load staff data');
            
            const staff = await res.json();
            
            console.log('Editing staff:', staff);
            
            document.getElementById('modalTitle').innerText = 'Edit Staff';
            document.getElementById('staff_id').value = staff.id;
            document.getElementById('name').value = staff.name;
            document.getElementById('email').value = staff.email;
            document.getElementById('role').value = staff.role || 'staff';
            document.getElementById('phone').value = staff.phone || '';
            document.getElementById('address').value = staff.address || '';
            document.getElementById('position').value = staff.position || '';
            document.getElementById('salary').value = staff.salary || '';
            document.getElementById('hire_date').value = staff.hire_date || '';
            document.getElementById('status').value = staff.status || 'active';
            
            if (staff.profile_image) {
                document.getElementById('profilePreview').src = `/storage/${staff.profile_image}`;
                document.getElementById('profilePreview').classList.remove('hidden');
                document.getElementById('noImageText').classList.add('hidden');
            } else {
                document.getElementById('profilePreview').classList.add('hidden');
                document.getElementById('noImageText').classList.remove('hidden');
            }
            
            document.getElementById('staffModal').classList.add('open');
        } catch (error) {
            console.error('Error editing staff:', error);
            showToast('Error loading staff data', true);
        }
    }

    async function deleteStaff() {
        if (!pendingDeleteId) return;
        
        try {
            const res = await fetch(`/api/staff/${pendingDeleteId}`, { method: 'DELETE' });
            if (res.ok) {
                showToast('Staff deleted successfully!');
                closeConfirmModal();
                loadStaff();
            } else {
                const data = await res.json();
                showToast(data.error || 'Error deleting staff', true);
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error deleting staff', true);
        }
    }

    document.getElementById('confirmDeleteBtn').onclick = deleteStaff;

    document.getElementById('staffForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const staffId = document.getElementById('staff_id').value;
        const formData = new FormData();
        
        // Get all form values - IMPORTANT: role must be captured
        const roleValue = document.getElementById('role').value;
        const statusValue = document.getElementById('status').value;
        
        console.log('Submitting - Role value:', roleValue);
        console.log('Submitting - Status value:', statusValue);
        
        formData.append('name', document.getElementById('name').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('role', roleValue);
        formData.append('status', statusValue);
        formData.append('phone', document.getElementById('phone').value || '');
        formData.append('address', document.getElementById('address').value || '');
        formData.append('position', document.getElementById('position').value || '');
        formData.append('salary', document.getElementById('salary').value || '');
        formData.append('hire_date', document.getElementById('hire_date').value || '');
        
        const password = document.getElementById('password').value;
        if (password) {
            formData.append('password', password);
        }
        
        const profileImage = document.getElementById('profile_image').files[0];
        if (profileImage) {
            formData.append('profile_image', profileImage);
        }
        
        const url = staffId ? `/api/staff/${staffId}` : '/api/staff';
        const method = 'POST';
        if (staffId) {
            formData.append('_method', 'PUT');
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const submitBtn = document.querySelector('#staffForm button[type="submit"]');
        const originalText = submitBtn.innerText;
        submitBtn.innerText = 'Saving...';
        submitBtn.disabled = true;
        
        try {
            const res = await fetch(url, { 
                method: method, 
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData 
            });
            
            const data = await res.json();
            
            if (res.ok) {
                showToast(`Staff ${staffId ? 'updated' : 'added'} successfully!`);
                closeStaffModal();
                loadStaff();
            } else {
                showToast(data.error || 'Error saving staff', true);
                console.error('Server error:', data);
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error saving staff', true);
        } finally {
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
        }
    });

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    document.getElementById('profile_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('profilePreview').src = event.target.result;
                document.getElementById('profilePreview').classList.remove('hidden');
                document.getElementById('noImageText').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        loadStaff();
    });
</script>
@endsection