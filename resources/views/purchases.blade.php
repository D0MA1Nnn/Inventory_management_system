@extends('layouts.app')

@section('title', 'Purchases')
@section('content')

<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col gap-1">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900"></h1>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <x-summary-card label="Available Products" id="totalProductsCount" value="0" accent="gray" />
        <x-summary-card label="Active Suppliers" id="totalSuppliersCount" value="0" accent="blue" />
        <x-summary-card label="Pending Orders" id="pendingPurchasesCount" value="0" accent="amber" />
        <x-summary-card label="Completed Orders" id="completedPurchasesCount" value="0" accent="green" />
    </div>

    <!-- Purchase Tabs - Mobile friendly -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-2 sm:p-3">
        <div class="grid grid-cols-3 gap-1 rounded-lg bg-gray-100 p-1" role="tablist" aria-label="Purchase sections">
            <button type="button" class="purchase-tab active px-2 sm:px-4 py-1.5 sm:py-2.5 rounded-md text-xs sm:text-sm font-semibold transition-all duration-200" data-tab="buy" onclick="setPurchaseTab('buy')" role="tab" aria-selected="true">
                Buy
            </button>
            <button type="button" class="purchase-tab relative px-2 sm:px-4 py-1.5 sm:py-2.5 rounded-md text-xs sm:text-sm font-semibold transition-all duration-200" data-tab="coming" onclick="setPurchaseTab('coming')" role="tab" aria-selected="false">
                Coming
                <span id="comingProductsBadge" class="tab-badge hidden">0</span>
            </button>
            <button type="button" class="purchase-tab px-2 sm:px-4 py-1.5 sm:py-2.5 rounded-md text-xs sm:text-sm font-semibold transition-all duration-200" data-tab="received" onclick="setPurchaseTab('received')" role="tab" aria-selected="false">
                Received
            </button>
        </div>
        <div class="mt-3 sm:mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2">
            <label for="categoryFilter" class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500">Filter by Category</label>
            <select id="categoryFilter" class="w-full sm:w-auto sm:min-w-56 px-3 py-1.5 sm:py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="filterProducts()">
                <option value="">ALL CATEGORIES</option>
            </select>
        </div>
    </div>

    <!-- Purchase Tab Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Buy Section -->
        <section id="buySection" class="purchase-section transition-opacity duration-200" data-tab-panel="buy" role="tabpanel">
            <div class="section-header px-4 sm:px-6 py-3 sm:py-4">
                <h2 class="text-gray-900 font-semibold text-base sm:text-lg">Buy Products</h2>
                <p class="text-gray-500 text-xs sm:text-sm mt-0.5">Purchase products from your suppliers</p>
            </div>
            <div class="p-3 sm:p-6">
                <div id="buyGrid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 md:gap-6"></div>
                <div id="buyPagination" class="mt-4 flex justify-center"></div>
            </div>
        </section>

        <!-- Coming Products Section -->
        <section id="comingSection" class="purchase-section hidden opacity-0 transition-opacity duration-200" data-tab-panel="coming" role="tabpanel">
            <div class="section-header px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-gray-900 font-semibold text-base sm:text-lg">Coming Products</h2>
                        <p class="text-gray-500 text-xs sm:text-sm mt-0.5">Products waiting to be received</p>
                    </div>
                </div>
            </div>
            <div class="p-3 sm:p-6">
                <div id="comingGrid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 md:gap-6"></div>
                <div id="comingPagination" class="mt-4 flex justify-center"></div>
            </div>
        </section>

        <!-- Received Products Section -->
        <section id="receivedSection" class="purchase-section hidden opacity-0 transition-opacity duration-200" data-tab-panel="received" role="tabpanel">
            <div class="section-header px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-gray-900 font-semibold text-base sm:text-lg">Received Products</h2>
                        <p class="text-gray-500 text-xs sm:text-sm mt-0.5">Latest received products</p>
                    </div>
                    <button onclick="openHistoryModal()" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-900 hover:bg-gray-800 text-white text-xs sm:text-sm font-medium rounded-lg transition shadow-sm">
                        View All History
                    </button>
                </div>
            </div>
            <div class="p-3 sm:p-6">
                <div id="receivedGrid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 md:gap-6"></div>
                <div id="receivedPagination" class="mt-4 flex justify-center"></div>
            </div>
        </section>
    </div>
</div>

<!-- HISTORY MODAL - Responsive -->
<div id="historyModal" class="modal-bg" onclick="if(event.target===this)closeHistoryModal()">
    <div class="bg-white rounded-2xl w-[95%] sm:w-[900px] max-w-[95%] max-h-[90vh] overflow-hidden shadow-2xl flex flex-col">
        <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 sticky top-0 bg-white z-10 border-b">
            <span class="text-base sm:text-xl font-bold text-gray-900">Purchase History</span>
            <button class="bg-transparent border-none text-xl sm:text-2xl cursor-pointer text-gray-500 hover:text-gray-700 transition" onclick="closeHistoryModal()">✕</button>
        </div>
        <div class="overflow-y-auto flex-1 p-4 sm:p-6">
            <div id="historyList" class="space-y-3 sm:space-y-4">
                <div class="text-center text-gray-500 py-8 text-sm">Loading history...</div>
            </div>
        </div>
    </div>
</div>

<!-- DETAIL MODAL - Responsive -->
<div id="detailModal" class="modal-bg" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="bg-white rounded-2xl w-[95%] sm:w-[600px] max-w-[95%] max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="h-32 sm:h-40 md:h-48 overflow-hidden rounded-t-2xl">
            <img id="modalImage" src="" class="w-full h-full object-cover">
        </div>
        <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 sticky top-0 bg-white z-10 border-b">
            <span id="modalName" class="text-base sm:text-xl font-bold text-gray-900"></span>
            <button class="bg-transparent border-none text-xl sm:text-2xl cursor-pointer text-gray-500 hover:text-gray-700 transition" onclick="document.getElementById('detailModal').classList.remove('open')">✕</button>
        </div>
        <div id="modalContent" class="p-4 sm:p-6"></div>
    </div>
</div>

<!-- SUPPLIER SELECTION MODAL - Responsive -->
<div id="supplierModal" class="modal-bg" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="bg-white rounded-2xl w-[95%] sm:w-[500px] max-w-[95%] shadow-2xl">
        <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 sticky top-0 bg-white z-10 border-b">
            <span class="text-base sm:text-xl font-bold text-gray-900">Select Supplier</span>
            <button class="bg-transparent border-none text-xl sm:text-2xl cursor-pointer text-gray-500 hover:text-gray-700 transition" onclick="document.getElementById('supplierModal').classList.remove('open')">✕</button>
        </div>
        <div class="p-4 sm:p-6">
            <div class="flex gap-3 sm:gap-4 mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-gray-200">
                <img id="supplierProductImage" src="" class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-xl shadow-md">
                <div>
                    <div id="supplierProductName" class="font-bold text-base sm:text-lg mb-1"></div>
                    <div id="supplierProductPrice" class="text-green-600 font-semibold text-sm sm:text-base"></div>
                </div>
            </div>

            <div class="mb-3 sm:mb-4">
                <label class="block text-gray-500 text-[10px] sm:text-xs font-semibold mb-1 sm:mb-2 uppercase tracking-wide">Choose Supplier</label>
                <div id="supplierList" class="flex flex-col gap-2 max-h-[300px] overflow-y-auto"></div>
            </div>

            <div class="mb-3 sm:mb-4">
                <label class="block text-gray-500 text-[10px] sm:text-xs font-semibold mb-1 sm:mb-2 uppercase tracking-wide">Quantity</label>
                <div class="flex items-center gap-2 sm:gap-3">
                    <button onclick="decrementSupplierQuantity()" class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 border rounded-xl cursor-pointer font-bold hover:bg-gray-200 transition text-sm">-</button>
                    <input type="number" id="supplierQuantity" value="1" min="1" class="flex-1 p-2 sm:p-3 border border-gray-300 rounded-xl text-center text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button onclick="incrementSupplierQuantity()" class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 border rounded-xl cursor-pointer font-bold hover:bg-gray-200 transition text-sm">+</button>
                </div>
            </div>

            <div id="supplierTotalPrice" class="bg-gray-50 p-3 sm:p-4 rounded-xl text-center mb-4 sm:mb-6">
                <span class="text-gray-500 text-[10px] sm:text-xs uppercase tracking-wide">Total Price (Cost)</span>
                <span class="text-green-600 text-xl sm:text-2xl font-bold block">₱ 0.00</span>
            </div>

            <button id="confirmSupplierBtn" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 sm:py-3 rounded-xl transition disabled:opacity-50 disabled:cursor-not-allowed text-sm" disabled>Select Supplier First</button>
        </div>
    </div>
</div>

<!-- CONFIRMATION MODAL - Responsive -->
<div id="confirmModal" class="modal-bg">
    <div class="bg-white rounded-2xl w-[90%] sm:w-[350px] max-w-[90%] text-center p-5 sm:p-6 shadow-2xl">
        <div class="text-5xl sm:text-6xl mb-4">⚠️</div>
        <div class="text-base sm:text-lg font-bold mb-2 text-gray-900">Confirm Action</div>
        <div id="confirmMessage" class="text-xs sm:text-sm text-gray-600 mb-4 sm:mb-5 leading-relaxed">Are you sure?</div>
        <div class="flex gap-3">
            <button onclick="closeConfirm()" class="flex-1 py-2 sm:py-3 bg-gray-200 rounded-xl cursor-pointer text-sm font-semibold text-gray-700 hover:bg-gray-300 transition">Cancel</button>
            <button id="confirmYesBtn" class="flex-1 py-2 sm:py-3 bg-green-600 rounded-xl cursor-pointer text-sm font-semibold text-white hover:bg-green-700 transition">Yes</button>
        </div>
    </div>
</div>

<!-- Password Confirmation Modal for Staff - Responsive -->
<div id="passwordConfirmModal" class="modal-bg" onclick="if(event.target===this)closePasswordModal()">
    <div class="bg-white rounded-2xl w-[90%] sm:w-[400px] max-w-[90%] text-center p-5 sm:p-6 shadow-2xl">
        <div class="text-5xl sm:text-6xl mb-4">🔒</div>
        <div class="text-base sm:text-lg font-bold mb-2 text-gray-900">Confirm Password</div>
        <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">Please enter your password to complete this purchase</p>
        <input type="password" id="confirmPassword" class="w-full border border-gray-300 rounded-xl p-2 sm:p-3 mb-3 sm:mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Enter your password">
        <div class="flex gap-3">
            <button onclick="closePasswordModal()" class="flex-1 py-2 sm:py-3 bg-gray-200 rounded-xl font-semibold hover:bg-gray-300 transition text-sm">Cancel</button>
            <button id="confirmPasswordBtn" class="flex-1 py-2 sm:py-3 bg-blue-600 rounded-xl text-white font-semibold hover:bg-blue-700 transition text-sm">Confirm</button>
        </div>
    </div>
</div>

<!-- SUCCESS TOAST -->
<div id="successToast" class="fixed bottom-5 right-5 bg-green-600 text-white py-2 sm:py-3 px-3 sm:px-5 rounded-xl text-xs sm:text-sm z-[300] hidden shadow-lg">
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
    let currentBuyPage = 1;
    let currentComingPage = 1;
    let currentReceivedPage = 1;
    const PURCHASES_PER_PAGE = 16;

    function renderPagination(containerId, totalItems, currentPage, perPage, pageKey) {
        const container = document.getElementById(containerId);
        if (!container) return;
        const totalPages = Math.max(1, Math.ceil(totalItems / perPage));
        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }
        const pages = Array.from({ length: totalPages }, (_, i) => i + 1);
        container.innerHTML = `
            <div class="flex items-center gap-1 sm:gap-2">
                <button type="button" onclick="changePage('${pageKey}', ${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50">Prev</button>
                ${pages.map(page => `<button type="button" onclick="changePage('${pageKey}', ${page})" class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border ${page === currentPage ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'}">${page}</button>`).join('')}
                <button type="button" onclick="changePage('${pageKey}', ${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50">Next</button>
            </div>
        `;
    }

    function changePage(pageKey, page) {
        if (pageKey === 'buy') {
            const categoryId = document.getElementById('categoryFilter').value;
            const filtered = !categoryId ? allProducts : allProducts.filter(p => p.category_id == categoryId);
            currentBuyPage = Math.max(1, Math.min(page, Math.ceil(Math.max(1, filtered.length) / PURCHASES_PER_PAGE)));
            displayProducts(filtered);
            return;
        }
        if (pageKey === 'coming') {
            currentComingPage = Math.max(1, page);
            loadComingProducts();
            return;
        }
        if (pageKey === 'received') {
            currentReceivedPage = Math.max(1, page);
            loadReceivedProducts();
        }
    }

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
                container.innerHTML = '<div class="text-center text-gray-500 py-8 text-sm">No received products found</div>';
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
                
                const originalPrice = product.price;
                
                return `
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-200">
                        <div class="flex flex-col md:flex-row">
                            <div class="relative w-full md:w-40 h-24 md:h-auto overflow-hidden bg-gray-100">
                                <img src="${imageUrl}" class="w-full h-full object-cover" onerror="this.src='${noImage150}'">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center md:hidden"><span class="bg-green-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">RECEIVED</span></div>
                            </div>
                            <div class="flex-1 p-3 sm:p-4">
                                <div class="hidden md:block float-right"><span class="bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full">RECEIVED</span></div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                                    <div><span class="text-gray-500 text-[10px] sm:text-sm">Supplier:</span><br><strong class="text-gray-900 text-xs sm:text-sm">${escapeHtml(product.supplier_name)}</strong></div>
                                    <div><span class="text-gray-500 text-[10px] sm:text-sm">Product:</span><br><strong class="text-gray-900 text-xs sm:text-sm">${escapeHtml(product.product_name)}</strong></div>
                                    <div><span class="text-gray-500 text-[10px] sm:text-sm">Original Price:</span><br><strong class="text-green-600 text-xs sm:text-sm">₱ ${parseFloat(originalPrice).toLocaleString()}</strong></div>
                                    <div><span class="text-gray-500 text-[10px] sm:text-sm">Quantity:</span><br><strong class="text-gray-900 text-xs sm:text-sm">${product.quantity}</strong></div>
                                    <div><span class="text-gray-500 text-[10px] sm:text-sm">Total Cost:</span><br><strong class="text-green-600 text-xs sm:text-sm">₱ ${(originalPrice * product.quantity).toLocaleString()}</strong></div>
                                    <div><span class="text-gray-500 text-[10px] sm:text-sm">Received:</span><br><strong class="text-blue-600 text-xs sm:text-sm">${receivedDate}</strong></div>
                                </div>
                                <button class="w-full mt-3 sm:mt-4 bg-gray-800 hover:bg-gray-900 text-white font-semibold py-1.5 sm:py-2 rounded-xl transition text-xs sm:text-sm" onclick="showProductDetails(${product.product_id}); closeHistoryModal();">View Product Details</button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        } catch (error) {
            console.error('Error loading history:', error);
            document.getElementById('historyList').innerHTML = '<div class="text-center text-red-500 py-8 text-sm">Error loading history</div>';
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
            showSuccess('Please enter your password');
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
                if (pendingProductId) {
                    proceedToSupplierModal(pendingProductId);
                }
            } else {
                showSuccess('Incorrect password. Please try again.');
                document.getElementById('confirmPassword').value = '';
            }
        } catch (error) {
            console.error('Error verifying password:', error);
            showSuccess('Error verifying password');
        }
    }

    document.getElementById('confirmPasswordBtn').onclick = verifyPasswordAndProceed;

    async function showSupplierModal(productId) {
        if (allSuppliers.length === 0) {
            showSuccess('Loading suppliers, please wait...');
            await loadSuppliers();
        }
        
        if (userRole === 'staff') {
            showPasswordModal(productId);
            return;
        }
        
        proceedToSupplierModal(productId);
    }

    async function proceedToSupplierModal(productId) {
        currentBuyProduct = allProducts.find(p => p.id == productId);
        if (!currentBuyProduct) return;

        const costPrice = currentBuyProduct.cost_price || currentBuyProduct.price;
        
        document.getElementById('supplierProductName').innerText = currentBuyProduct.name;
        document.getElementById('supplierProductPrice').innerText = `₱ ${parseFloat(costPrice).toLocaleString()}`;
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
            renderPagination('buyPagination', 0, 1, PURCHASES_PER_PAGE, 'buy');
            container.innerHTML = `<div class="col-span-full text-center py-8 sm:py-12"><div class="w-16 h-16 sm:w-24 sm:h-24 mx-auto mb-3 sm:mb-4 bg-gray-100 rounded-full flex items-center justify-center"><svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg></div><h3 class="text-base sm:text-lg font-medium text-gray-900 mb-1">No products available</h3><p class="text-xs sm:text-sm text-gray-500">Add products to start purchasing</p></div>`;
            return;
        }
        const totalPages = Math.max(1, Math.ceil(products.length / PURCHASES_PER_PAGE));
        currentBuyPage = Math.min(currentBuyPage, totalPages);
        const pageItems = products.slice((currentBuyPage - 1) * PURCHASES_PER_PAGE, currentBuyPage * PURCHASES_PER_PAGE);
        pageItems.forEach(product => {
            const isLowStock = product.quantity > 0 && product.quantity < 10;
            const purchasePrice = product.cost_price || product.price;
            
            container.innerHTML += `
                <div class="group bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-200">
                    <div class="relative h-28 sm:h-32 md:h-40 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                        <img src="${product.image ? '/storage/' + product.image : noImage150}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='${noImage150}'">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition duration-300"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                            <span class="px-2 sm:px-4 py-1 sm:py-2 bg-white/90 backdrop-blur-sm rounded-lg text-[10px] sm:text-sm font-semibold text-gray-800 cursor-pointer hover:bg-white transition" onclick="showProductDetails(${product.id})">View</span>
                        </div>
                        <div class="absolute top-2 right-2">
                            <span class="px-1.5 py-0.5 text-[9px] sm:text-xs font-semibold rounded-full ${product.quantity > 0 ? (isLowStock ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') : 'bg-red-100 text-red-700'}">${product.quantity > 0 ? (isLowStock ? 'Low' : 'In Stock') : 'Out'}</span>
                        </div>
                        <div class="absolute bottom-2 left-2">
                            <span class="px-1.5 py-0.5 text-[9px] sm:text-xs font-semibold rounded-full bg-blue-600 text-white">Cost</span>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="font-bold text-gray-900 text-sm sm:text-base mb-1 truncate">${escapeHtml(product.name)}</h3>
                        <div class="mb-1 sm:mb-2">
                            <p class="text-base sm:text-xl md:text-2xl font-bold text-green-600">₱ ${parseFloat(purchasePrice).toLocaleString()}</p>
                            ${product.markup_percentage > 0 ? `<p class="text-[10px] sm:text-xs text-gray-400 line-through">Retail: ₱ ${parseFloat(product.price).toLocaleString()}</p>` : ''}
                        </div>
                        <div class="flex items-center justify-between text-[10px] sm:text-xs mb-2 sm:mb-3">
                            <span class="text-gray-500">Stock: ${product.quantity}</span>
                            <span class="text-blue-600 font-medium">${product.category ? product.category.name : 'No Category'}</span>
                        </div>
                        <button class="w-full bg-[#1A1D2E] hover:bg-[#2D3047] text-white font-semibold py-1.5 sm:py-2 rounded-lg transition text-xs sm:text-sm" onclick="showSupplierModal(${product.id})">Buy Now</button>
                    </div>
                </div>
            `;
        });
        renderPagination('buyPagination', products.length, currentBuyPage, PURCHASES_PER_PAGE, 'buy');
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
                    price: currentBuyProduct ? (currentBuyProduct.cost_price || currentBuyProduct.price) : 0
                });
            }
        });
        return productSuppliers;
    }

    function displaySuppliers(suppliers) {
        let container = document.getElementById('supplierList');
        container.innerHTML = '';
        if (suppliers.length === 0) {
            container.innerHTML = `<div class="p-6 sm:p-8 text-center bg-gray-50 rounded-xl"><svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg><p class="text-gray-500 text-sm">No suppliers available for this product.</p><p class="text-xs text-gray-400 mt-1">Please add this product to a supplier first.</p></div>`;
            return;
        }
        suppliers.forEach(supplier => {
            let supplierPrice = currentBuyProduct.cost_price || currentBuyProduct.price;
            container.innerHTML += `
                <div onclick='selectSupplier(${supplier.id}, "${escapeHtml(supplier.name)}", ${supplierPrice})' class="supplier-option p-3 sm:p-4 border border-gray-200 rounded-xl cursor-pointer transition-all hover:border-blue-400 hover:shadow-md" data-supplier-id="${supplier.id}">
                    <div class="flex justify-between items-center">
                        <div><div class="font-semibold text-gray-900 text-sm">${escapeHtml(supplier.name)}</div><div class="text-[10px] sm:text-xs text-gray-500 mt-1">📞 ${supplier.contact_number || 'N/A'}</div></div>
                        <div class="text-right"><div class="font-bold text-base sm:text-lg text-green-600">₱ ${parseFloat(supplierPrice).toLocaleString()}</div></div>
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
        let price = selectedSupplier?.price || (currentBuyProduct?.cost_price || currentBuyProduct?.price || 0);
        let total = price * quantity;
        document.getElementById('supplierTotalPrice').innerHTML = `<span class="text-gray-500 text-[10px] sm:text-xs uppercase tracking-wide">Total Price (Cost)</span><span class="text-green-600 text-xl sm:text-2xl font-bold block">₱ ${total.toLocaleString()}</span>`;
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
                const purchasePrice = currentBuyProduct.cost_price || currentBuyProduct.price;
                
                let res = await fetch('/api/purchase/add-to-coming', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        product_id: currentBuyProduct.id,
                        supplier_id: selectedSupplier.id,
                        quantity: quantity,
                        price: purchasePrice
                    })
                });
                if (res.ok) {
                    showSuccess('Added to coming products!');
                    document.getElementById('supplierModal').classList.remove('open');
                    loadComingProducts();
                } else {
                    const error = await res.json();
                    console.error('Error:', error);
                    showSuccess('Error adding product');
                }
            } catch (error) {
                console.error('Error:', error);
                showSuccess('Error occurred');
            }
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
                renderPagination('comingPagination', 0, 1, PURCHASES_PER_PAGE, 'coming');
                container.innerHTML = `<div class="col-span-full text-center py-8 sm:py-12"><div class="w-16 h-16 sm:w-24 sm:h-24 mx-auto mb-3 sm:mb-4 bg-gray-100 rounded-full flex items-center justify-center"><svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"></path></svg></div><h3 class="text-base sm:text-lg font-medium text-gray-900 mb-1">No coming products</h3><p class="text-xs sm:text-sm text-gray-500">Purchase products to see them here</p></div>`;
                return;
            }
            const entries = Object.entries(products);
            const totalPages = Math.max(1, Math.ceil(entries.length / PURCHASES_PER_PAGE));
            currentComingPage = Math.min(currentComingPage, totalPages);
            const pageEntries = entries.slice((currentComingPage - 1) * PURCHASES_PER_PAGE, currentComingPage * PURCHASES_PER_PAGE);
            for (let [key, product] of pageEntries) {
                let imageUrl = noImage150;
                if (product.product_image) imageUrl = '/storage/' + product.product_image;
                else if (product.product_id) {
                    let productDetail = allProducts.find(p => p.id == product.product_id);
                    if (productDetail && productDetail.image) imageUrl = '/storage/' + productDetail.image;
                }
                const originalPrice = product.price;
                
                container.innerHTML += `
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-200">
                        <div class="relative h-24 sm:h-28 md:h-32 overflow-hidden bg-gray-100">
                            <img src="${imageUrl}" class="w-full h-full object-cover" onerror="this.src='${noImage150}'">
                            <div class="absolute bottom-2 left-2">
                                <span class="px-1.5 py-0.5 text-[9px] sm:text-xs font-semibold rounded-full bg-amber-500 text-white">Pending</span>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4">
                            <div class="space-y-1 text-xs sm:text-sm">
                                <p><span class="text-gray-500">Supplier:</span> <strong class="text-gray-900">${escapeHtml(product.supplier_name)}</strong></p>
                                <p><span class="text-gray-500">Product:</span> <strong class="text-gray-900">${escapeHtml(product.product_name)}</strong></p>
                                <p><span class="text-gray-500">Original:</span> <strong class="text-green-600">₱ ${parseFloat(originalPrice).toLocaleString()}</strong></p>
                                <p><span class="text-gray-500">Qty:</span> <strong class="text-gray-900">${product.quantity}</strong></p>
                                <p><span class="text-gray-500">Total:</span> <strong class="text-green-600">₱ ${(originalPrice * product.quantity).toLocaleString()}</strong></p>
                            </div>
                            <button class="w-full mt-2 sm:mt-3 bg-green-600 hover:bg-green-700 text-white font-semibold py-1.5 sm:py-2 rounded-lg transition text-xs sm:text-sm" onclick='showReceiveConfirm("${key}", "${escapeHtml(product.product_name)}", ${product.quantity})'>Receive</button>
                        </div>
                    </div>
                `;
            }
            renderPagination('comingPagination', entries.length, currentComingPage, PURCHASES_PER_PAGE, 'coming');
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
                renderPagination('receivedPagination', 0, 1, PURCHASES_PER_PAGE, 'received');
                container.innerHTML = `<div class="col-span-full text-center py-8 sm:py-12"><div class="w-16 h-16 sm:w-24 sm:h-24 mx-auto mb-3 sm:mb-4 bg-gray-100 rounded-full flex items-center justify-center"><svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div><h3 class="text-base sm:text-lg font-medium text-gray-900 mb-1">No received products</h3><p class="text-xs sm:text-sm text-gray-500">Received products will appear here</p></div>`;
                return;
            }
            const totalPages = Math.max(1, Math.ceil(productsArray.length / PURCHASES_PER_PAGE));
            currentReceivedPage = Math.min(currentReceivedPage, totalPages);
            const pageItems = productsArray.slice((currentReceivedPage - 1) * PURCHASES_PER_PAGE, currentReceivedPage * PURCHASES_PER_PAGE);
            pageItems.forEach(product => {
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
                
                const originalPrice = product.price;
                
                container.innerHTML += `
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-200">
                        <div class="relative h-24 sm:h-28 md:h-32 overflow-hidden bg-gray-100">
                            <img src="${imageUrl}" class="w-full h-full object-cover opacity-75" onerror="this.src='${noImage150}'">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                <span class="bg-green-600 text-white text-[9px] sm:text-xs font-bold px-1.5 sm:px-2 py-0.5 rounded-full">RECEIVED</span>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4">
                            <div class="space-y-1 text-xs sm:text-sm">
                                <p><span class="text-gray-500">Supplier:</span> <strong class="text-gray-900">${escapeHtml(product.supplier_name)}</strong></p>
                                <p><span class="text-gray-500">Product:</span> <strong class="text-gray-900">${escapeHtml(product.product_name)}</strong></p>
                                <p><span class="text-gray-500">Original:</span> <strong class="text-green-600">₱ ${parseFloat(originalPrice).toLocaleString()}</strong></p>
                                <p><span class="text-gray-500">Qty:</span> <strong class="text-gray-900">${product.quantity}</strong></p>
                                <p><span class="text-gray-500">Total:</span> <strong class="text-green-600">₱ ${(originalPrice * product.quantity).toLocaleString()}</strong></p>
                                <p><span class="text-gray-500">Received:</span> <strong class="text-blue-600 text-[10px]">${receivedDate.substring(0, 10)}</strong></p>
                            </div>
                            <button class="w-full mt-2 sm:mt-3 bg-gray-800 hover:bg-gray-900 text-white font-semibold py-1.5 sm:py-2 rounded-lg transition text-xs sm:text-sm" onclick="showProductDetails(${product.product_id})">View Details</button>
                        </div>
                    </div>
                `;
            });
            renderPagination('receivedPagination', productsArray.length, currentReceivedPage, PURCHASES_PER_PAGE, 'received');
        } catch (error) { console.error('Error loading received products:', error); }
    }

    function showProductDetails(productId) {
        let product = allProducts.find(p => p.id == productId);
        if (!product) return;
        document.getElementById('modalName').innerText = product.name;
        document.getElementById('modalImage').src = product.image ? '/storage/' + product.image : noImage600;
        let modalContent = document.getElementById('modalContent');
        modalContent.innerHTML = `
            <div class="space-y-3 sm:space-y-4">
                <div class="grid grid-cols-2 gap-2 sm:gap-4">
                    <div class="bg-gray-50 p-2 sm:p-3 rounded-xl"><div class="text-[9px] sm:text-xs text-gray-500 uppercase tracking-wide mb-1">Brand</div><div class="text-xs sm:text-sm font-medium text-gray-900">${escapeHtml(product.brand || 'N/A')}</div></div>
                    <div class="bg-gray-50 p-2 sm:p-3 rounded-xl"><div class="text-[9px] sm:text-xs text-gray-500 uppercase tracking-wide mb-1">Model</div><div class="text-xs sm:text-sm font-medium text-gray-900">${escapeHtml(product.model_number || 'N/A')}</div></div>
                    <div class="bg-gray-50 p-2 sm:p-3 rounded-xl"><div class="text-[9px] sm:text-xs text-gray-500 uppercase tracking-wide mb-1">Cost Price</div><div class="text-xs sm:text-sm font-medium text-gray-900">₱ ${parseFloat(product.cost_price || product.price).toLocaleString()}</div></div>
                    <div class="bg-gray-50 p-2 sm:p-3 rounded-xl"><div class="text-[9px] sm:text-xs text-gray-500 uppercase tracking-wide mb-1">Selling Price</div><div class="text-xs sm:text-sm font-bold text-green-600">₱ ${parseFloat(product.price).toLocaleString()}</div></div>
                    <div class="bg-gray-50 p-2 sm:p-3 rounded-xl"><div class="text-[9px] sm:text-xs text-gray-500 uppercase tracking-wide mb-1">Stock</div><div class="text-xs sm:text-sm font-medium text-gray-900">${product.quantity} units</div></div>
                    <div class="bg-gray-50 p-2 sm:p-3 rounded-xl"><div class="text-[9px] sm:text-xs text-gray-500 uppercase tracking-wide mb-1">Category</div><div class="text-xs sm:text-sm font-medium text-gray-900">${product.category ? product.category.name : 'N/A'}</div></div>
                    <div class="bg-gray-50 p-2 sm:p-3 rounded-xl"><div class="text-[9px] sm:text-xs text-gray-500 uppercase tracking-wide mb-1">Markup</div><div class="text-xs sm:text-sm font-medium text-blue-600">${product.markup_percentage || 0}%</div></div>
                    <div class="bg-gray-50 p-2 sm:p-3 rounded-xl"><div class="text-[9px] sm:text-xs text-gray-500 uppercase tracking-wide mb-1">Status</div><div class="text-xs sm:text-sm font-medium ${product.quantity > 0 ? 'text-green-600' : 'text-red-600'}">${product.quantity > 0 ? 'In Stock' : 'Out of Stock'}</div></div>
                </div>
                ${product.performance ? `<div class="bg-gray-50 p-3 sm:p-4 rounded-xl"><div class="text-[9px] sm:text-xs text-gray-500 uppercase tracking-wide mb-1">Performance</div><div class="text-xs sm:text-sm text-gray-700 leading-relaxed">${escapeHtml(product.performance)}</div></div>` : ''}
            </div>
        `;
        document.getElementById('detailModal').classList.add('open');
    }

    async function loadCategoryFilter() {
        try {
            let res = await fetch('/api/categories');
            let categories = await res.json();
            let select = document.getElementById('categoryFilter');
            select.innerHTML = '<option value="">ALL CATEGORIES</option>';
            categories.forEach(cat => { select.innerHTML += `<option value="${cat.id}">${escapeHtml(cat.name)}</option>`; });
        } catch (error) { console.error('Error loading categories:', error); }
    }

    function filterProducts() {
        let categoryId = document.getElementById('categoryFilter').value;
        currentBuyPage = 1;
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
