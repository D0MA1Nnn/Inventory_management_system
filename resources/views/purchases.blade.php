@extends('layouts.app')

@section('title', 'Purchases')
@section('content')

<div class="space-y-6">
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-bold text-gray-900"></h1>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="summary-card border-gray-200">
            <p class="text-xs font-semibold uppercase text-gray-500">Available Products</p>
            <p class="mt-2 text-2xl font-bold text-gray-900" id="totalProductsCount">0</p>
        </div>
        <div class="summary-card border-blue-200">
            <p class="text-xs font-semibold uppercase text-gray-500">Active Suppliers</p>
            <p class="mt-2 text-2xl font-bold text-blue-600" id="totalSuppliersCount">0</p>
        </div>
        <div class="summary-card border-amber-200">
            <p class="text-xs font-semibold uppercase text-gray-500">Pending Orders</p>
            <p class="mt-2 text-2xl font-bold text-amber-600" id="pendingPurchasesCount">0</p>
        </div>
        <div class="summary-card border-green-200">
            <p class="text-xs font-semibold uppercase text-gray-500">Completed Orders</p>
            <p class="mt-2 text-2xl font-bold text-green-600" id="completedPurchasesCount">0</p>
        </div>
    </div>

    <!-- Purchase Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 rounded-lg bg-gray-100 p-1" role="tablist" aria-label="Purchase sections">
            <button type="button" class="purchase-tab active px-4 py-2.5 rounded-md text-sm font-semibold transition-all duration-200" data-tab="buy" onclick="setPurchaseTab('buy')" role="tab" aria-selected="true">
                Buy Products
            </button>
            <button type="button" class="purchase-tab relative px-4 py-2.5 rounded-md text-sm font-semibold transition-all duration-200" data-tab="coming" onclick="setPurchaseTab('coming')" role="tab" aria-selected="false">
                Coming Products
                <span id="comingProductsBadge" class="tab-badge hidden">0</span>
            </button>
            <button type="button" class="purchase-tab px-4 py-2.5 rounded-md text-sm font-semibold transition-all duration-200" data-tab="received" onclick="setPurchaseTab('received')" role="tab" aria-selected="false">
                Received Products
            </button>
        </div>
        <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2">
            <label for="categoryFilter" class="text-xs font-semibold uppercase text-gray-500">Filter by Category</label>
            <select id="categoryFilter" class="w-full sm:w-auto sm:min-w-56 px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="filterProducts()">
                <option value="">ALL CATEGORIES</option>
            </select>
        </div>
    </div>

    <!-- Purchase Tab Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <section id="buySection" class="purchase-section transition-opacity duration-200" data-tab-panel="buy" role="tabpanel">
            <div class="section-header">
                <h2 class="text-gray-900 font-semibold">Buy Products</h2>
                <p class="text-gray-500 text-sm mt-0.5">Purchase products from your suppliers</p>
            </div>
            <div class="p-6">
                <div id="buyGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>
            </div>
        </section>

        <section id="comingSection" class="purchase-section hidden opacity-0 transition-opacity duration-200" data-tab-panel="coming" role="tabpanel">
            <div class="section-header">
                <h2 class="text-gray-900 font-semibold">Coming Products</h2>
                <p class="text-gray-500 text-sm mt-0.5">Products waiting to be received</p>
            </div>
            <div class="p-6">
                <div id="comingGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>
            </div>
        </section>

        <section id="receivedSection" class="purchase-section hidden opacity-0 transition-opacity duration-200" data-tab-panel="received" role="tabpanel">
            <div class="section-header">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-gray-900 font-semibold">Received Products</h2>
                        <p class="text-gray-500 text-sm mt-0.5">Latest received products</p>
                    </div>
                    <button onclick="openHistoryModal()" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition shadow-sm">
                        View All History
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="receivedGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>
            </div>
        </section>
    </div>
</div>

<!-- HISTORY MODAL -->
<div id="historyModal" class="modal-bg" onclick="if(event.target===this)closeHistoryModal()">
    <div class="bg-white rounded-2xl w-[900px] max-w-[95%] max-h-[90vh] overflow-hidden shadow-2xl flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 sticky top-0 bg-white z-10 border-b">
            <span class="text-xl font-bold text-gray-900">Purchase History</span>
            <button class="bg-transparent border-none text-2xl cursor-pointer text-gray-500 hover:text-gray-700 transition" onclick="closeHistoryModal()">✕</button>
        </div>
        <div class="overflow-y-auto flex-1 p-6">
            <div id="historyList" class="space-y-4">
                <div class="text-center text-gray-500 py-8">Loading history...</div>
            </div>
        </div>
    </div>
</div>

<!-- DETAIL MODAL -->
<div id="detailModal" class="modal-bg" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="bg-white rounded-2xl w-[600px] max-w-[95%] max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="h-48 overflow-hidden rounded-t-2xl">
            <img id="modalImage" src="" class="w-full h-full object-cover">
        </div>
        <div class="flex items-center justify-between px-6 py-4 sticky top-0 bg-white z-10 border-b">
            <span id="modalName" class="text-xl font-bold text-gray-900"></span>
            <button class="bg-transparent border-none text-2xl cursor-pointer text-gray-500 hover:text-gray-700 transition" onclick="document.getElementById('detailModal').classList.remove('open')">✕</button>
        </div>
        <div id="modalContent" class="p-6"></div>
    </div>
</div>

<!-- SUPPLIER SELECTION MODAL -->
<div id="supplierModal" class="modal-bg" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="bg-white rounded-2xl w-[500px] max-w-[95%] shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 sticky top-0 bg-white z-10 border-b">
            <span class="text-xl font-bold text-gray-900">Select Supplier</span>
            <button class="bg-transparent border-none text-2xl cursor-pointer text-gray-500 hover:text-gray-700 transition" onclick="document.getElementById('supplierModal').classList.remove('open')">✕</button>
        </div>
        <div class="p-6">
            <div class="flex gap-4 mb-6 pb-4 border-b border-gray-200">
                <img id="supplierProductImage" src="" class="w-20 h-20 object-cover rounded-xl shadow-md">
                <div>
                    <div id="supplierProductName" class="font-bold text-lg mb-1"></div>
                    <div id="supplierProductPrice" class="text-green-600 font-semibold text-base"></div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-xs font-semibold mb-2 uppercase tracking-wide">Choose Supplier</label>
                <div id="supplierList" class="flex flex-col gap-2 max-h-[300px] overflow-y-auto"></div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-xs font-semibold mb-2 uppercase tracking-wide">Quantity</label>
                <div class="flex items-center gap-3">
                    <button onclick="decrementSupplierQuantity()" class="w-10 h-10 bg-gray-100 border rounded-xl cursor-pointer font-bold hover:bg-gray-200 transition">-</button>
                    <input type="number" id="supplierQuantity" value="1" min="1" class="flex-1 p-3 border border-gray-300 rounded-xl text-center text-base focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button onclick="incrementSupplierQuantity()" class="w-10 h-10 bg-gray-100 border rounded-xl cursor-pointer font-bold hover:bg-gray-200 transition">+</button>
                </div>
            </div>

            <div id="supplierTotalPrice" class="bg-gray-50 p-4 rounded-xl text-center mb-6">
                <span class="text-gray-500 text-xs uppercase tracking-wide">Total Price</span>
                <span class="text-green-600 text-2xl font-bold block">₱ 0.00</span>
            </div>

            <button id="confirmSupplierBtn" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>Select Supplier First</button>
        </div>
    </div>
</div>

<!-- CONFIRMATION MODAL -->
<div id="confirmModal" class="modal-bg">
    <div class="bg-white rounded-2xl w-[350px] max-w-[90%] text-center p-6 shadow-2xl">
        <div class="text-6xl mb-4">⚠️</div>
        <div class="text-lg font-bold mb-2 text-gray-900">Confirm Action</div>
        <div id="confirmMessage" class="text-sm text-gray-600 mb-5 leading-relaxed">Are you sure?</div>
        <div class="flex gap-3">
            <button onclick="closeConfirm()" class="flex-1 py-3 bg-gray-200 rounded-xl cursor-pointer text-sm font-semibold text-gray-700 hover:bg-gray-300 transition">Cancel</button>
            <button id="confirmYesBtn" class="flex-1 py-3 bg-green-600 rounded-xl cursor-pointer text-sm font-semibold text-white hover:bg-green-700 transition">Yes</button>
        </div>
    </div>
</div>

<!-- Password Confirmation Modal for Staff -->
<div id="passwordConfirmModal" class="modal-bg" onclick="if(event.target===this)closePasswordModal()">
    <div class="bg-white rounded-2xl w-[400px] max-w-[90%] text-center p-6 shadow-2xl">
        <div class="text-6xl mb-4">🔒</div>
        <div class="text-lg font-bold mb-2 text-gray-900">Confirm Password</div>
        <p class="text-sm text-gray-600 mb-4">Please enter your password to complete this purchase</p>
        <input type="password" id="confirmPassword" class="w-full border border-gray-300 rounded-xl p-3 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your password">
        <div class="flex gap-3">
            <button onclick="closePasswordModal()" class="flex-1 py-3 bg-gray-200 rounded-xl font-semibold hover:bg-gray-300 transition">Cancel</button>
            <button id="confirmPasswordBtn" class="flex-1 py-3 bg-blue-600 rounded-xl text-white font-semibold hover:bg-blue-700 transition">Confirm</button>
        </div>
    </div>
</div>

<!-- SUCCESS TOAST -->
<div id="successToast" class="fixed bottom-5 right-5 bg-green-600 text-white py-3 px-5 rounded-xl text-sm z-[300] hidden shadow-lg">
    Success!
</div>

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
.purchase-tab {
    color: #4b5563;
    background: transparent;
}
.purchase-tab:hover {
    color: #111827;
    background: #ffffff;
}
.purchase-tab.active {
    color: #ffffff;
    background: #1A1D2E;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.14);
}
.tab-badge {
    position: absolute;
    top: -0.45rem;
    right: -0.45rem;
    min-width: 1.25rem;
    height: 1.25rem;
    padding: 0 0.35rem;
    border-radius: 9999px;
    background: #f59e0b;
    color: #ffffff;
    font-size: 0.7rem;
    font-weight: 700;
    line-height: 1.25rem;
    text-align: center;
    box-shadow: 0 4px 10px rgba(245, 158, 11, 0.35);
    transition: opacity 160ms ease, transform 160ms ease;
    pointer-events: none;
}
.tab-badge.hidden {
    display: none;
}
.tab-badge.badge-pop {
    transform: scale(1.12);
}
</style>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    const userRole = "{{ Auth::user()->role }}";
    
    const noImage150 = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'150\' height=\'150\' viewBox=\'0 0 150 150\'%3E%3Crect width=\'150\' height=\'150\' fill=\'%23cccccc\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23666666\' font-size=\'14\'%3ENo Image%3C/text%3E%3C/svg%3E';
    const noImage70 = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'70\' height=\'70\' viewBox=\'0 0 70 70\'%3E%3Crect width=\'70\' height=\'70\' fill=\'%23cccccc\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23666666\' font-size=\'10\'%3ENo Image%3C/text%3E%3C/svg%3E';
    const noImage600 = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'600\' height=\'200\' viewBox=\'0 0 600 200\'%3E%3Crect width=\'600\' height=\'200\' fill=\'%23cccccc\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23666666\' font-size=\'20\'%3ENo Image%3C/text%3E%3C/svg%3E';

    let allProducts = [];
    let allSuppliers = [];
    let currentBuyProduct = null;
    let selectedSupplier = null;
    let confirmCallback = null;
    let pendingReceiveCartKey = null;
    let pendingProductId = null;

    function setPurchaseTab(activeTab) {
        document.querySelectorAll('.purchase-tab').forEach(tab => {
            const isActive = tab.dataset.tab === activeTab;
            tab.classList.toggle('active', isActive);
            tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        document.querySelectorAll('.purchase-section').forEach(section => {
            const isActive = section.dataset.tabPanel === activeTab;
            if (isActive) {
                section.classList.remove('hidden');
                requestAnimationFrame(() => section.classList.remove('opacity-0'));
            } else {
                section.classList.add('opacity-0');
                setTimeout(() => {
                    const activeButton = document.querySelector('.purchase-tab.active');
                    if (activeButton && section.dataset.tabPanel !== activeButton.dataset.tab) {
                        section.classList.add('hidden');
                    }
                }, 200);
            }
        });
    }

    function updateComingProductsBadge(count) {
        const badge = document.getElementById('comingProductsBadge');
        if (!badge) return;

        badge.textContent = count > 99 ? '99+' : count;
        badge.classList.toggle('hidden', count === 0);

        if (count > 0) {
            badge.classList.remove('badge-pop');
            void badge.offsetWidth;
            badge.classList.add('badge-pop');
            setTimeout(() => badge.classList.remove('badge-pop'), 180);
        }
    }

    function openHistoryModal() {
        document.getElementById('historyModal').classList.add('open');
        loadFullHistory();
    }

    function closeHistoryModal() {
        document.getElementById('historyModal').classList.remove('open');
    }

    async function loadFullHistory() {
        try {
            let res = await fetch('/api/purchase/received');
            let products = await res.json();
            let container = document.getElementById('historyList');
            
            const productsArray = Object.values(products);
            
            if (productsArray.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-8">No received products found</div>';
                return;
            }
            
            container.innerHTML = productsArray.map(product => {
                let imageUrl = noImage150;
                if (product.product_image) imageUrl = '/storage/' + product.product_image;
                else if (product.product_id) {
                    let productDetail = allProducts.find(p => p.id == product.product_id);
                    if (productDetail && productDetail.image) imageUrl = '/storage/' + productDetail.image;
                }
                
                let receivedDate = 'N/A';
                if (product.received_at) {
                    const date = new Date(product.received_at);
                    receivedDate = date.toLocaleString('en-PH', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                }
                
                return `
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-200">
                        <div class="flex flex-col md:flex-row">
                            <div class="relative w-full md:w-48 h-32 md:h-auto overflow-hidden bg-gray-100">
                                <img src="${imageUrl}" class="w-full h-full object-cover" onerror="this.src='${noImage150}'">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center md:hidden"><span class="bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full">RECEIVED</span></div>
                            </div>
                            <div class="flex-1 p-4">
                                <div class="hidden md:block float-right"><span class="bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full">RECEIVED</span></div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div><span class="text-gray-500 text-sm">Supplier:</span><br><strong class="text-gray-900">${escapeHtml(product.supplier_name)}</strong></div>
                                    <div><span class="text-gray-500 text-sm">Product:</span><br><strong class="text-gray-900">${escapeHtml(product.product_name)}</strong></div>
                                    <div><span class="text-gray-500 text-sm">Unit Price:</span><br><strong class="text-green-600">₱ ${parseFloat(product.price).toLocaleString()}</strong></div>
                                    <div><span class="text-gray-500 text-sm">Quantity:</span><br><strong class="text-gray-900">${product.quantity}</strong></div>
                                    <div><span class="text-gray-500 text-sm">Total:</span><br><strong class="text-green-600">₱ ${(product.price * product.quantity).toLocaleString()}</strong></div>
                                    <div><span class="text-gray-500 text-sm">Received:</span><br><strong class="text-blue-600">${receivedDate}</strong></div>
                                </div>
                                <button class="w-full mt-4 bg-gray-800 hover:bg-gray-900 text-white font-semibold py-2 rounded-xl transition text-sm" onclick="showProductDetails(${product.product_id}); closeHistoryModal();">View Product Details</button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        } catch (error) {
            console.error('Error loading history:', error);
            document.getElementById('historyList').innerHTML = '<div class="text-center text-red-500 py-8">Error loading history</div>';
        }
    }

    function showConfirm(message, callback) {
        document.getElementById('confirmMessage').innerText = message;
        document.getElementById('confirmModal').classList.add('open');
        confirmCallback = callback;
    }

    function closeConfirm() {
        document.getElementById('confirmModal').classList.remove('open');
        confirmCallback = null;
        pendingReceiveCartKey = null;
    }

    document.getElementById('confirmYesBtn').onclick = function () {
        if (confirmCallback) {
            confirmCallback();
        }
        closeConfirm();
    };

    function showSuccess(message) {
        let toast = document.getElementById('successToast');
        toast.innerText = message;
        toast.classList.remove('hidden');
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 2500);
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Password confirmation functions
    function showPasswordModal(productId) {
        pendingProductId = productId;
        document.getElementById('passwordConfirmModal').classList.add('open');
        document.getElementById('confirmPassword').value = '';
    }

    function closePasswordModal() {
        document.getElementById('passwordConfirmModal').classList.remove('open');
        pendingProductId = null;
    }

    async function verifyPasswordAndProceed() {
        const password = document.getElementById('confirmPassword').value;
        
        if (!password) {
            showToast('Please enter your password', true);
            return;
        }
        
        try {
            const res = await fetch('/api/verify-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ password: password })
            });
            
            const data = await res.json();
            
            if (data.valid) {
                closePasswordModal();
                // Proceed to supplier modal
                if (pendingProductId) {
                    proceedToSupplierModal(pendingProductId);
                }
            } else {
                showToast('Incorrect password. Please try again.', true);
                document.getElementById('confirmPassword').value = '';
            }
        } catch (error) {
            console.error('Error verifying password:', error);
            showToast('Error verifying password', true);
        }
    }

    document.getElementById('confirmPasswordBtn').onclick = verifyPasswordAndProceed;

    // Modified showSupplierModal with role check
    async function showSupplierModal(productId) {
        if (allSuppliers.length === 0) {
            showSuccess('Loading suppliers, please wait...');
            await loadSuppliers();
        }
        
        // Check if user is staff (not admin)
        if (userRole === 'staff') {
            showPasswordModal(productId);
            return;
        }
        
        // Admin proceeds directly
        proceedToSupplierModal(productId);
    }

    async function proceedToSupplierModal(productId) {
        currentBuyProduct = allProducts.find(p => p.id == productId);
        if (!currentBuyProduct) return;

        document.getElementById('supplierProductName').innerText = currentBuyProduct.name;
        document.getElementById('supplierProductPrice').innerText = `₱ ${parseFloat(currentBuyProduct.price).toLocaleString()}`;
        document.getElementById('supplierProductImage').src = currentBuyProduct.image ? '/storage/' + currentBuyProduct.image : noImage70;

        document.getElementById('supplierQuantity').value = '1';
        selectedSupplier = null;
        document.getElementById('confirmSupplierBtn').disabled = true;
        document.getElementById('confirmSupplierBtn').innerText = 'Select Supplier First';
        updateSupplierTotalPrice();

        const productSuppliers = getProductSuppliers(productId);
        displaySuppliers(productSuppliers);
        document.getElementById('supplierModal').classList.add('open');
    }

    async function loadSuppliers() {
        try {
            const res = await fetch('/api/suppliers');
            const suppliers = await res.json();
            
            allSuppliers = suppliers.map(supplier => {
                let productsOffered = supplier.products_offered;
                if (typeof productsOffered === 'string') {
                    try {
                        productsOffered = JSON.parse(productsOffered);
                    } catch(e) {
                        productsOffered = [];
                    }
                }
                if (!productsOffered) {
                    productsOffered = [];
                }
                return {
                    ...supplier,
                    products_offered: productsOffered
                };
            });
            document.getElementById('totalSuppliersCount').innerText = allSuppliers.length;
        } catch (error) {
            console.error('Error loading suppliers:', error);
            allSuppliers = [];
        }
    }

    async function loadProducts() {
        try {
            await loadSuppliers();
            let res = await fetch('/api/products');
            allProducts = await res.json();
            displayProducts(allProducts);
            loadCategoryFilter();
            document.getElementById('totalProductsCount').innerText = allProducts.length;
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }

    function displayProducts(products) {
        let container = document.getElementById('buyGrid');
        container.innerHTML = '';

        if (products.length === 0) {
            container.innerHTML = `<div class="col-span-full text-center py-12"><div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center"><svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg></div><h3 class="text-lg font-medium text-gray-900 mb-1">No products available</h3><p class="text-gray-500">Add products to start purchasing</p></div>`;
            return;
        }

        products.forEach(product => {
            const isLowStock = product.quantity > 0 && product.quantity < 10;
            container.innerHTML += `
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-200">
                    <div class="relative h-40 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                        <img src="${product.image ? '/storage/' + product.image : noImage150}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='${noImage150}'">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition duration-300"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                            <span class="px-4 py-2 bg-white/90 backdrop-blur-sm rounded-lg text-sm font-semibold text-gray-800 cursor-pointer hover:bg-white transition" onclick="showProductDetails(${product.id})">View Details</span>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full ${product.quantity > 0 ? (isLowStock ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') : 'bg-red-100 text-red-700'}">${product.quantity > 0 ? (isLowStock ? 'Low Stock' : 'In Stock') : 'Out of Stock'}</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 text-base mb-1 truncate">${escapeHtml(product.name)}</h3>
                        <p class="text-xl font-bold text-green-600 mb-2">₱ ${parseFloat(product.price).toLocaleString()}</p>
                        <div class="flex items-center justify-between text-xs mb-3">
                            <span class="text-gray-500">Stock: ${product.quantity} units</span>
                            <span class="text-blue-600 font-medium">${product.category ? product.category.name : 'No Category'}</span>
                        </div>
                        <button class="w-full bg-[#1A1D2E] hover:bg-[#2D3047] text-white font-semibold py-2.5 rounded-lg transition text-sm" onclick="showSupplierModal(${product.id})">Buy Now</button>
                    </div>
                </div>
            `;
        });
    }

    function getProductSuppliers(productId) {
        if (!allSuppliers || allSuppliers.length === 0) return [];
        const productSuppliers = [];
        const targetProductId = parseInt(productId);
        allSuppliers.forEach(supplier => {
            const productsOffered = supplier.products_offered || [];
            if (productsOffered.includes(targetProductId)) {
                productSuppliers.push({
                    id: supplier.id,
                    name: supplier.name,
                    contact_number: supplier.contact_number,
                    email: supplier.email,
                    address: supplier.address,
                    image: supplier.image,
                    price: currentBuyProduct ? currentBuyProduct.price : 0
                });
            }
        });
        return productSuppliers;
    }

    function displaySuppliers(suppliers) {
        let container = document.getElementById('supplierList');
        container.innerHTML = '';
        if (suppliers.length === 0) {
            container.innerHTML = `<div class="p-8 text-center bg-gray-50 rounded-xl"><svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg><p class="text-gray-500">No suppliers available for this product.</p><p class="text-sm text-gray-400 mt-1">Please add this product to a supplier first.</p></div>`;
            return;
        }
        suppliers.forEach(supplier => {
            let supplierPrice = currentBuyProduct.price;
            container.innerHTML += `
                <div onclick='selectSupplier(${supplier.id}, "${escapeHtml(supplier.name)}", ${supplierPrice})' class="supplier-option p-4 border border-gray-200 rounded-xl cursor-pointer transition-all hover:border-blue-400 hover:shadow-md" data-supplier-id="${supplier.id}">
                    <div class="flex justify-between items-center">
                        <div><div class="font-semibold text-gray-900">${escapeHtml(supplier.name)}</div><div class="text-xs text-gray-500 mt-1">📞 ${supplier.contact_number || 'N/A'} | ✉️ ${supplier.email || 'N/A'}</div></div>
                        <div class="text-right"><div class="font-bold text-lg text-green-600">₱ ${parseFloat(supplierPrice).toLocaleString()}</div></div>
                    </div>
                </div>
            `;
        });
    }

    function selectSupplier(supplierId, supplierName, supplierPrice) {
        document.querySelectorAll('.supplier-option').forEach(opt => {
            opt.style.background = '#fff';
            opt.style.borderColor = '#e5e7eb';
        });
        let selected = document.querySelector(`.supplier-option[data-supplier-id="${supplierId}"]`);
        if (selected) {
            selected.style.background = '#f0fdf4';
            selected.style.borderColor = '#22c55e';
        }
        selectedSupplier = { id: supplierId, name: supplierName, price: supplierPrice };
        document.getElementById('confirmSupplierBtn').disabled = false;
        document.getElementById('confirmSupplierBtn').innerText = `Buy from ${supplierName.toUpperCase()}`;
        updateSupplierTotalPrice();
    }

    function updateSupplierTotalPrice() {
        let quantity = parseInt(document.getElementById('supplierQuantity').value) || 0;
        let price = selectedSupplier?.price || currentBuyProduct?.price || 0;
        let total = price * quantity;
        document.getElementById('supplierTotalPrice').innerHTML = `<span class="text-gray-500 text-xs uppercase tracking-wide">Total Price</span><span class="text-green-600 text-2xl font-bold block">₱ ${total.toLocaleString()}</span>`;
    }

    function incrementSupplierQuantity() {
        let input = document.getElementById('supplierQuantity');
        let value = parseInt(input.value) || 0;
        input.value = value + 1;
        updateSupplierTotalPrice();
    }

    function decrementSupplierQuantity() {
        let input = document.getElementById('supplierQuantity');
        let value = parseInt(input.value) || 0;
        if (value > 1) {
            input.value = value - 1;
            updateSupplierTotalPrice();
        }
    }

    async function confirmSupplierPurchase() {
        if (!selectedSupplier) { showSuccess('Please select a supplier'); return; }
        let quantity = parseInt(document.getElementById('supplierQuantity').value);
        if (quantity < 1) { showSuccess('Invalid quantity'); return; }
        showConfirm(`Buy ${quantity} item(s) from ${selectedSupplier.name}?`, async () => {
            try {
                let res = await fetch('/api/purchase/add-to-coming', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ product_id: currentBuyProduct.id, supplier_id: selectedSupplier.id, quantity: quantity, price: currentBuyProduct.price })
                });
                if (res.ok) {
                    showSuccess('Added to coming products!');
                    document.getElementById('supplierModal').classList.remove('open');
                    loadComingProducts();
                } else { showSuccess('Error adding product'); }
            } catch (error) { showSuccess('Error occurred'); }
        });
    }

    async function loadComingProducts() {
        try {
            let res = await fetch('/api/purchase/coming');
            let products = await res.json();
            let container = document.getElementById('comingGrid');
            container.innerHTML = '';
            const pendingCount = Object.keys(products).length;
            document.getElementById('pendingPurchasesCount').innerText = pendingCount;
            updateComingProductsBadge(pendingCount);
            if (pendingCount === 0) {
                container.innerHTML = `<div class="col-span-full text-center py-12"><div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center"><svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"></path></svg></div><h3 class="text-lg font-medium text-gray-900 mb-1">No coming products</h3><p class="text-gray-500">Purchase products to see them here</p></div>`;
                return;
            }
            for (let [key, product] of Object.entries(products)) {
                let imageUrl = noImage150;
                if (product.product_image) imageUrl = '/storage/' + product.product_image;
                else if (product.product_id) {
                    let productDetail = allProducts.find(p => p.id == product.product_id);
                    if (productDetail && productDetail.image) imageUrl = '/storage/' + productDetail.image;
                }
                container.innerHTML += `
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-200">
                        <div class="relative h-32 overflow-hidden bg-gray-100"><img src="${imageUrl}" class="w-full h-full object-cover" onerror="this.src='${noImage150}'"></div>
                        <div class="p-4">
                            <div class="space-y-1 text-sm">
                                <p><span class="text-gray-500">Supplier:</span> <strong class="text-gray-900">${escapeHtml(product.supplier_name)}</strong></p>
                                <p><span class="text-gray-500">Product:</span> <strong class="text-gray-900">${escapeHtml(product.product_name)}</strong></p>
                                <p><span class="text-gray-500">Unit Price:</span> <strong class="text-green-600">₱ ${parseFloat(product.price).toLocaleString()}</strong></p>
                                <p><span class="text-gray-500">Quantity:</span> <strong class="text-gray-900">${product.quantity}</strong></p>
                                <p><span class="text-gray-500">Total:</span> <strong class="text-green-600">₱ ${(product.price * product.quantity).toLocaleString()}</strong></p>
                            </div>
                            <button class="w-full mt-4 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition text-sm" onclick='showReceiveConfirm("${key}", "${escapeHtml(product.product_name)}", ${product.quantity})'>Receive</button>
                        </div>
                    </div>
                `;
            }
        } catch (error) { console.error('Error loading coming products:', error); }
    }

    function showReceiveConfirm(cartKey, productName, quantity) {
        pendingReceiveCartKey = cartKey;
        showConfirm(`Receive ${quantity} x ${productName}? This will add the stock to inventory.`, async () => {
            await processReceive(pendingReceiveCartKey);
        });
    }

    async function processReceive(cartKey) {
        try {
            let res = await fetch('/api/purchase/receive', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ cart_key: cartKey })
            });
            if (res.ok) {
                showSuccess('Product received and added to inventory!');
                setTimeout(() => { loadComingProducts(); loadReceivedProducts(); loadProducts(); }, 500);
            } else { showSuccess('Error receiving product'); }
        } catch (error) { showSuccess('Error occurred'); }
    }

    async function loadReceivedProducts() {
        try {
            let res = await fetch('/api/purchase/received');
            let products = await res.json();
            let container = document.getElementById('receivedGrid');
            container.innerHTML = '';
            const productsArray = Object.values(products);
            const completedCount = productsArray.length;
            document.getElementById('completedPurchasesCount').innerText = completedCount;
            
            if (completedCount === 0) {
                container.innerHTML = `<div class="col-span-full text-center py-12"><div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center"><svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div><h3 class="text-lg font-medium text-gray-900 mb-1">No received products</h3><p class="text-gray-500">Received products will appear here</p></div>`;
                return;
            }
            
            // Only show latest 4 received products
            const latestProducts = productsArray.slice(0, 4);
            
            latestProducts.forEach(product => {
                let imageUrl = noImage150;
                if (product.product_image) imageUrl = '/storage/' + product.product_image;
                else if (product.product_id) {
                    let productDetail = allProducts.find(p => p.id == product.product_id);
                    if (productDetail && productDetail.image) imageUrl = '/storage/' + productDetail.image;
                }
                
                let receivedDate = 'N/A';
                if (product.received_at) {
                    const date = new Date(product.received_at);
                    receivedDate = date.toLocaleString('en-PH', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                }
                
                container.innerHTML += `
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-200">
                        <div class="relative h-32 overflow-hidden bg-gray-100">
                            <img src="${imageUrl}" class="w-full h-full object-cover opacity-75" onerror="this.src='${noImage150}'">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center"><span class="bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full">RECEIVED</span></div>
                        </div>
                        <div class="p-4">
                            <div class="space-y-1 text-sm">
                                <p><span class="text-gray-500">Supplier:</span> <strong class="text-gray-900">${escapeHtml(product.supplier_name)}</strong></p>
                                <p><span class="text-gray-500">Product:</span> <strong class="text-gray-900">${escapeHtml(product.product_name)}</strong></p>
                                <p><span class="text-gray-500">Unit Price:</span> <strong class="text-green-600">₱ ${parseFloat(product.price).toLocaleString()}</strong></p>
                                <p><span class="text-gray-500">Quantity:</span> <strong class="text-gray-900">${product.quantity}</strong></p>
                                <p><span class="text-gray-500">Total:</span> <strong class="text-green-600">₱ ${(product.price * product.quantity).toLocaleString()}</strong></p>
                                <p><span class="text-gray-500">Received:</span> <strong class="text-blue-600">${receivedDate}</strong></p>
                            </div>
                            <button class="w-full mt-4 bg-gray-800 hover:bg-gray-900 text-white font-semibold py-2 rounded-lg transition text-sm" onclick="showProductDetails(${product.product_id})">View Details</button>
                        </div>
                    </div>
                `;
            });
        } catch (error) { console.error('Error loading received products:', error); }
    }

    function showProductDetails(productId) {
        let product = allProducts.find(p => p.id == productId);
        if (!product) return;
        document.getElementById('modalName').innerText = product.name;
        document.getElementById('modalImage').src = product.image ? '/storage/' + product.image : noImage600;
        let modalContent = document.getElementById('modalContent');
        modalContent.innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-xl"><div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Brand</div><div class="text-sm font-medium text-gray-900">${escapeHtml(product.brand || 'N/A')}</div></div>
                    <div class="bg-gray-50 p-3 rounded-xl"><div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Model Number</div><div class="text-sm font-medium text-gray-900">${escapeHtml(product.model_number || 'N/A')}</div></div>
                    <div class="bg-gray-50 p-3 rounded-xl"><div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Price</div><div class="text-lg font-bold text-green-600">₱ ${parseFloat(product.price).toLocaleString()}</div></div>
                    <div class="bg-gray-50 p-3 rounded-xl"><div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Stock</div><div class="text-sm font-medium text-gray-900">${product.quantity} units</div></div>
                    <div class="bg-gray-50 p-3 rounded-xl"><div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Category</div><div class="text-sm font-medium text-gray-900">${product.category ? product.category.name : 'N/A'}</div></div>
                    <div class="bg-gray-50 p-3 rounded-xl"><div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Status</div><div class="text-sm font-medium ${product.quantity > 0 ? 'text-green-600' : 'text-red-600'}">${product.quantity > 0 ? 'In Stock' : 'Out of Stock'}</div></div>
                </div>
                ${product.performance ? `<div class="bg-gray-50 p-4 rounded-xl"><div class="text-xs text-gray-500 uppercase tracking-wide mb-2">Performance</div><div class="text-sm text-gray-700 leading-relaxed">${escapeHtml(product.performance)}</div></div>` : ''}
            </div>
        `;
        document.getElementById('detailModal').classList.add('open');
    }

    async function loadCategoryFilter() {
        try {
            let res = await fetch('/api/categories');
            let categories = await res.json();
            let select = document.getElementById('categoryFilter');
            categories.forEach(cat => { select.innerHTML += `<option value="${cat.id}">${cat.name}</option>`; });
        } catch (error) { console.error('Error loading categories:', error); }
    }

    function filterProducts() {
        let categoryId = document.getElementById('categoryFilter').value;
        if (!categoryId) { displayProducts(allProducts); } 
        else { let filtered = allProducts.filter(p => p.category_id == categoryId); displayProducts(filtered); }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('supplierQuantity');
        if (quantityInput) {
            quantityInput.addEventListener('change', updateSupplierTotalPrice);
            quantityInput.addEventListener('input', updateSupplierTotalPrice);
        }
        const confirmBtn = document.getElementById('confirmSupplierBtn');
        if (confirmBtn) { confirmBtn.addEventListener('click', confirmSupplierPurchase); }
    });

    loadProducts();
    loadComingProducts();
    loadReceivedProducts();
    setPurchaseTab('buy');
    setInterval(() => { loadComingProducts(); loadReceivedProducts(); }, 30000);
</script>
@endsection
