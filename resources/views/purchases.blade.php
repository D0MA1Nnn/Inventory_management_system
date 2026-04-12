@extends('layouts.app')

@section('content')
<div class="p-5">
    <div class="text-center text-2xl font-bold mb-[18px] text-gray-900 tracking-[1px]">PURCHASE</div>

    <!-- BUY PRODUCTS -->
    <div class="flex items-center justify-between bg-[#1A1D2E] rounded-md px-4 py-2.5 mb-3">
        <span class="text-white text-xs font-semibold tracking-[1px]">BUY PRODUCTS</span>
        <select id="categoryFilter" class="bg-black text-white border border-gray-700 rounded px-2.5 py-1 text-xs outline-none cursor-pointer" onchange="filterProducts()">
            <option value="">FILTER BY: CATEGORY</option>
        </select>
    </div>
    <div id="buyGrid" class="grid grid-cols-4 gap-2.5 mb-5"></div>

    <!-- COMING PRODUCTS -->
    <div class="flex items-center justify-between bg-[#1A1D2E] rounded-md px-4 py-2.5 mb-3 mt-1">
        <span class="text-white text-xs font-semibold tracking-[1px]">COMING PRODUCTS</span>
    </div>
    <div id="comingGrid" class="grid grid-cols-4 gap-2.5 mb-5"></div>

    <!-- RECEIVED PRODUCTS -->
    <div class="flex items-center justify-between bg-[#1A1D2E] rounded-md px-4 py-2.5 mb-3 mt-1">
        <span class="text-white text-xs font-semibold tracking-[1px]">RECEIVED PRODUCTS</span>
        <button class="bg-gray-700 text-white border-none py-1 px-3.5 text-xs font-semibold cursor-pointer rounded tracking-[0.5px]" onclick="loadHistory()">HISTORY</button>
    </div>
    <div id="receivedGrid" class="grid grid-cols-4 gap-2.5"></div>
</div>

<!-- DETAIL MODAL -->
<div id="detailModal" class="modal-bg" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="bg-white rounded-lg w-[600px] max-w-[95%] max-h-[90vh] overflow-y-auto">
        <div class="h-48 overflow-hidden">
            <img id="modalImage" src="" class="w-full h-full object-cover">
        </div>
        <div class="flex items-center justify-between px-4 py-2 sticky top-0 bg-white z-10">
            <span id="modalName" class="text-lg font-bold text-gray-900"></span>
            <button class="bg-transparent border-none text-2xl cursor-pointer text-gray-500 leading-none" onclick="document.getElementById('detailModal').classList.remove('open')">✕</button>
        </div>
        <div id="modalContent"></div>
    </div>
</div>

<!-- SUPPLIER SELECTION MODAL -->
<div id="supplierModal" class="modal-bg" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="bg-white rounded-lg w-[500px] max-w-[95%]">
        <div class="flex items-center justify-between px-4 py-2 sticky top-0 bg-white z-10">
            <span class="text-lg font-bold text-gray-900">SELECT SUPPLIER</span>
            <button class="bg-transparent border-none text-2xl cursor-pointer text-gray-500 leading-none" onclick="document.getElementById('supplierModal').classList.remove('open')">✕</button>
        </div>
        <div class="p-4">
            <div class="flex gap-4 mb-5 pb-4 border-b border-gray-200">
                <img id="supplierProductImage" src="" class="w-[70px] h-[70px] object-cover rounded-lg">
                <div>
                    <div id="supplierProductName" class="font-bold text-sm mb-1"></div>
                    <div id="supplierProductPrice" class="text-green-600 font-semibold text-sm"></div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-xs mb-2">CHOOSE SUPPLIER</label>
                <div id="supplierList" class="flex flex-col gap-2 max-h-[300px] overflow-y-auto"></div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-500 text-xs mb-1">QUANTITY</label>
                <div class="flex items-center gap-2.5">
                    <button onclick="decrementSupplierQuantity()" class="w-8 h-8 bg-gray-100 border-none rounded cursor-pointer font-bold hover:bg-gray-200">-</button>
                    <input type="number" id="supplierQuantity" value="1" min="1" class="flex-1 p-2 border border-gray-300 rounded text-center text-xs">
                    <button onclick="incrementSupplierQuantity()" class="w-8 h-8 bg-gray-100 border-none rounded cursor-pointer font-bold hover:bg-gray-200">+</button>
                </div>
            </div>

            <div id="supplierTotalPrice" class="bg-gray-50 p-2.5 rounded text-center mb-4">
                <span class="text-gray-500 text-xs">TOTAL PRICE</span>
                <span class="text-green-600 text-lg font-bold block">₱ 0.00</span>
            </div>

            <button id="confirmSupplierBtn" class="w-full bg-green-600 text-white border-none rounded py-2.5 text-xs font-bold cursor-pointer disabled:opacity-50 hover:bg-green-700" disabled>SELECT SUPPLIER FIRST</button>
        </div>
    </div>
</div>

<!-- CONFIRMATION MODAL -->
<div id="confirmModal" class="modal-bg">
    <div class="bg-white rounded-xl w-[350px] max-w-[90%] text-center p-6">
        <div class="text-base font-bold mb-3 text-gray-900">CONFIRM ACTION</div>
        <div id="confirmMessage" class="text-sm text-gray-600 mb-5 leading-relaxed">Are you sure?</div>
        <div class="flex gap-2.5">
            <button onclick="closeConfirm()" class="flex-1 py-2.5 bg-gray-300 border-none rounded cursor-pointer text-xs font-semibold text-gray-800 hover:bg-gray-400">CANCEL</button>
            <button id="confirmYesBtn" class="flex-1 py-2.5 bg-green-600 border-none rounded cursor-pointer text-xs font-semibold text-white hover:bg-green-700">YES</button>
        </div>
    </div>
</div>

<!-- SUCCESS TOAST -->
<div id="successToast" class="fixed bottom-5 right-5 bg-green-600 text-white py-3 px-5 rounded text-sm z-[300] hidden">
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
</style>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    
    // Local placeholder images (no external requests)
    const noImage150 = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'150\' height=\'150\' viewBox=\'0 0 150 150\'%3E%3Crect width=\'150\' height=\'150\' fill=\'%23cccccc\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23666666\' font-size=\'14\'%3ENo Image%3C/text%3E%3C/svg%3E';
    const noImage70 = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'70\' height=\'70\' viewBox=\'0 0 70 70\'%3E%3Crect width=\'70\' height=\'70\' fill=\'%23cccccc\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23666666\' font-size=\'10\'%3ENo Image%3C/text%3E%3C/svg%3E';
    const noImage600 = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'600\' height=\'200\' viewBox=\'0 0 600 200\'%3E%3Crect width=\'600\' height=\'200\' fill=\'%23cccccc\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23666666\' font-size=\'20\'%3ENo Image%3C/text%3E%3C/svg%3E';

    let allProducts = [];
    let allSuppliers = [];
    let currentBuyProduct = null;
    let selectedSupplier = null;
    let confirmCallback = null;
    let pendingReceiveCartKey = null;

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

    // Load all suppliers
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
            
            console.log('Loaded suppliers with products:', allSuppliers);
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
            
            console.log('Suppliers loaded:', allSuppliers);
            console.log('Products loaded:', allProducts);
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }

    function displayProducts(products) {
        let container = document.getElementById('buyGrid');
        container.innerHTML = '';

        products.forEach(product => {
            container.innerHTML += `
                <div class="bg-white rounded-lg overflow-hidden shadow-sm">
                    <div class="relative h-[100px] overflow-hidden bg-gray-300 cursor-pointer group">
                    <img src="${product.image ? '/storage/' + product.image : noImage150}"
                        class="w-full h-full object-cover"
                        onerror="this.src='${noImage150}'">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition duration-150"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-150">
                        <span class="text-white text-xs font-bold tracking-[0.5px] cursor-pointer" onclick="showProductDetails(${product.id})">VIEW DETAILS</span>
                    </div>
                </div>
                    <div class="p-2 pb-2.5">
                        <div class="text-xs font-bold text-gray-900 mb-0.5">${escapeHtml(product.name)}</div>
                        <div class="text-green-600 text-xs font-semibold mb-0.5">₱ ${parseFloat(product.price).toLocaleString()}</div>
                        <div class="text-[10px] text-gray-600 mb-0.5">Stock: ${product.quantity}</div>
                        <div class="text-[10px] text-blue-600 mb-2">${product.category ? product.category.name : 'No Category'}</div>
                        <button class="w-full bg-[#1A1D2E] text-white border-none rounded py-1.5 text-[10px] font-bold cursor-pointer tracking-[0.5px] hover:bg-gray-800" onclick="showSupplierModal(${product.id})">BUY</button>
                    </div>
                </div>
            `;
        });
    }

    // Get suppliers that offer this product
    function getProductSuppliers(productId) {
        if (!allSuppliers || allSuppliers.length === 0) {
            console.log('No suppliers loaded');
            return [];
        }
        
        const productSuppliers = [];
        const targetProductId = parseInt(productId);
        
        allSuppliers.forEach(supplier => {
            const productsOffered = supplier.products_offered || [];
            
            if (productsOffered.includes(targetProductId)) {
                console.log(`✓ Supplier ${supplier.name} offers this product!`);
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
        
        console.log(`Found ${productSuppliers.length} suppliers for product ${targetProductId}:`, productSuppliers);
        return productSuppliers;
    }

    async function showSupplierModal(productId) {
        if (allSuppliers.length === 0) {
            showSuccess('Loading suppliers, please wait...');
            await loadSuppliers();
        }
        
        currentBuyProduct = allProducts.find(p => p.id == productId);
        if (!currentBuyProduct) return;

        document.getElementById('supplierProductName').innerText = currentBuyProduct.name;
        document.getElementById('supplierProductPrice').innerText = `₱ ${parseFloat(currentBuyProduct.price).toLocaleString()}`;
        document.getElementById('supplierProductImage').src = currentBuyProduct.image ? '/storage/' + currentBuyProduct.image : noImage70;

        document.getElementById('supplierQuantity').value = '1';
        selectedSupplier = null;
        document.getElementById('confirmSupplierBtn').disabled = true;
        document.getElementById('confirmSupplierBtn').innerText = 'SELECT SUPPLIER FIRST';
        updateSupplierTotalPrice();

        const productSuppliers = getProductSuppliers(productId);
        displaySuppliers(productSuppliers);
        
        document.getElementById('supplierModal').classList.add('open');
    }

    function displaySuppliers(suppliers) {
        let container = document.getElementById('supplierList');
        container.innerHTML = '';

        if (suppliers.length === 0) {
            container.innerHTML = `
                <div class="p-5 text-center text-gray-500">
                    No suppliers available for this product.<br>
                    <small>Please add this product to a supplier first in the Supplier section.</small>
                </div>
            `;
            return;
        }

        suppliers.forEach(supplier => {
            let supplierPrice = currentBuyProduct.price;
            
            container.innerHTML += `
                <div onclick='selectSupplier(${supplier.id}, "${escapeHtml(supplier.name)}", ${supplierPrice})' 
                    class="supplier-option p-3 border border-gray-300 rounded-lg cursor-pointer transition-all mb-2 hover:bg-gray-50"
                    data-supplier-id="${supplier.id}">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-semibold text-sm">${escapeHtml(supplier.name)}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                📞 ${supplier.contact_number || 'N/A'} | ✉️ ${supplier.email || 'N/A'}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-sm text-green-600">₱ ${parseFloat(supplierPrice).toLocaleString()}</div>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    function selectSupplier(supplierId, supplierName, supplierPrice) {
        document.querySelectorAll('.supplier-option').forEach(opt => {
            opt.style.background = '#fff';
            opt.style.borderColor = '#ddd';
        });

        let selected = document.querySelector(`.supplier-option[data-supplier-id="${supplierId}"]`);
        if (selected) {
            selected.style.background = '#e8f5e9';
            selected.style.borderColor = '#16a34a';
        }

        selectedSupplier = { id: supplierId, name: supplierName, price: supplierPrice };
        document.getElementById('confirmSupplierBtn').disabled = false;
        document.getElementById('confirmSupplierBtn').innerText = `BUY FROM ${supplierName.toUpperCase()}`;
        updateSupplierTotalPrice();
    }

    function updateSupplierTotalPrice() {
        let quantity = parseInt(document.getElementById('supplierQuantity').value) || 0;
        let price = selectedSupplier?.price || currentBuyProduct?.price || 0;
        let total = price * quantity;
        document.getElementById('supplierTotalPrice').innerHTML = `
            <span class="text-gray-500 text-xs">TOTAL PRICE</span>
            <span class="text-green-600 text-lg font-bold block">₱ ${total.toLocaleString()}</span>
        `;
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
        if (!selectedSupplier) {
            showSuccess('Please select a supplier');
            return;
        }

        let quantity = parseInt(document.getElementById('supplierQuantity').value);

        if (quantity < 1) {
            showSuccess('Invalid quantity');
            return;
        }

        showConfirm(`Buy ${quantity} item(s) from ${selectedSupplier.name}?`, async () => {
            try {
                let res = await fetch('/api/purchase/add-to-coming', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        product_id: currentBuyProduct.id,
                        supplier_id: selectedSupplier.id,
                        quantity: quantity,
                        price: currentBuyProduct.price
                    })
                });

                if (res.ok) {
                    showSuccess('Added to coming products!');
                    document.getElementById('supplierModal').classList.remove('open');
                    loadComingProducts();
                } else {
                    const error = await res.text();
                    console.error('Error:', error);
                    showSuccess('Error adding product');
                }
            } catch (error) {
                console.error(error);
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

            if (Object.keys(products).length === 0) {
                container.innerHTML = '<div class="col-span-4 text-center text-gray-500 py-8">No coming products</div>';
                return;
            }

            for (let [key, product] of Object.entries(products)) {
                // Get image URL - handle both direct and from productDetail
                let imageUrl = noImage150;
                if (product.product_image) {
                    imageUrl = '/storage/' + product.product_image;
                } else if (product.product_id) {
                    let productDetail = allProducts.find(p => p.id == product.product_id);
                    if (productDetail && productDetail.image) {
                        imageUrl = '/storage/' + productDetail.image;
                    }
                }

                container.innerHTML += `
                    <div class="bg-white rounded-lg overflow-hidden shadow-sm">
                        <div class="h-[110px] overflow-hidden bg-gray-300">
                            <img src="${imageUrl}" class="w-full h-full object-cover" onerror="this.src='${noImage150}'">
                        </div>
                        <div class="p-2 pb-2.5">
                            <div class="text-[10px] text-gray-600 leading-relaxed">
                                SUPPLIER: <span class="font-semibold text-gray-900">${escapeHtml(product.supplier_name)}</span><br>
                                PRODUCT: <span class="font-semibold text-gray-900">${escapeHtml(product.product_name)}</span><br>
                                UNIT PRICE: <span class="font-semibold text-gray-900">₱ ${parseFloat(product.price).toLocaleString()}</span><br>
                                QUANTITY: <span class="font-semibold text-gray-900">${product.quantity}</span><br>
                                CATEGORY: <span class="font-semibold text-gray-900">${product.category || 'N/A'}</span><br>
                                TOTAL: <span class="font-semibold text-gray-900">₱ ${(product.price * product.quantity).toLocaleString()}</span>
                            </div>
                            <button class="w-full bg-green-600 text-white border-none rounded py-1.5 text-[10px] font-bold cursor-pointer mt-2 tracking-[0.5px] hover:bg-green-700" onclick='showReceiveConfirm("${key}", "${escapeHtml(product.product_name)}", ${product.quantity})'>RECEIVE</button>
                        </div>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading coming products:', error);
        }
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
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ cart_key: cartKey })
            });

            if (res.ok) {
                showSuccess('Product received and added to inventory!');
                setTimeout(() => {
                    loadComingProducts();
                    loadReceivedProducts();
                    loadProducts();
                }, 500);
            } else {
                showSuccess('Error receiving product');
            }
        } catch (error) {
            console.error('Error receiving product:', error);
            showSuccess('Error occurred');
        }
    }

    async function loadReceivedProducts() {
        try {
            let res = await fetch('/api/purchase/received');
            let products = await res.json();
            let container = document.getElementById('receivedGrid');
            container.innerHTML = '';

            console.log('Received products data:', products);
            console.log('Number of received products:', Object.keys(products).length);

            if (Object.keys(products).length === 0) {
                container.innerHTML = '<div class="col-span-4 text-center text-gray-500 py-8">No received products</div>';
                return;
            }

            for (let [key, product] of Object.entries(products)) {
                // Get image URL - handle both direct and from productDetail
                let imageUrl = noImage150;
                if (product.product_image) {
                    imageUrl = '/storage/' + product.product_image;
                } else if (product.product_id) {
                    let productDetail = allProducts.find(p => p.id == product.product_id);
                    if (productDetail && productDetail.image) {
                        imageUrl = '/storage/' + productDetail.image;
                    }
                }

                <!-- Replace the container.innerHTML += template literal with this: -->
                container.innerHTML += `
                <div style="background: #6B7280; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                    <div style="position: relative; height: 110px; overflow: hidden;">
                    <img src="${imageUrl}"
                        style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(0.4) brightness(0.7);"
                        onerror="this.src='${noImage150}'">
                    <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;">
                        <span style="background: rgba(0, 0, 0, 0); color: #fff; font-size: 13px; font-weight: 700; padding: 6px 14px; border-radius: 4px; letter-spacing: 1.5px;">RECEIVED</span>
                    </div>
                    </div>
                    <div style="padding: 10px 12px 12px; background: #ffffff;">
                    <div style="font-size: 11px; color: #000000; line-height: 1.8;">
                        <span style="color: #000000;">SUPPLIER NAME:</span> <strong>${escapeHtml(product.supplier_name)}</strong><br>
                        <span style="color: #000000;">PRODUCT NAME:</span> <strong>${escapeHtml(product.product_name)}</strong><br>
                        <span style="color: #000000;">UNIT PRICE:</span> <strong>₱ ${parseFloat(product.price).toLocaleString()}</strong><br>
                        <span style="color: #000000;">QUANTITY:</span> <strong>${product.quantity}</strong><br>
                        <span style="color: #000000;">CATEGORY:</span> <strong>${product.category || 'N/A'}</strong><br>
                        <span style="color: #000000; font-weight: 700;">TOTAL PRICE:</span> <strong>₱ ${(product.price * product.quantity).toLocaleString()}</strong>
                    </div>
                    <button
                        onclick="showProductDetails(${product.product_id})"
                        style="width: 100%; margin-top: 10px; padding: 9px 0; background: #f3f4f6; color: #111827; border: none; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; cursor: pointer;"
                        onmouseover="this.style.background='#e5e7eb'"
                        onmouseout="this.style.background='#f3f4f6'">
                        VIEW DETAILS
                    </button>
                    </div>
                </div>
                `;
                }
            } catch (error) {
                console.error('Error loading received products:', error);
            }
        }

    function showProductDetails(productId) {
        let product = allProducts.find(p => p.id == productId);
        if (!product) return;

        document.getElementById('modalName').innerText = product.name;
        document.getElementById('modalImage').src = product.image ? '/storage/' + product.image : noImage600;

        let modalContent = document.getElementById('modalContent');
        modalContent.innerHTML = `
            <div class="grid grid-cols-2 gap-0 px-4">
                <div class="p-2.5 border-b border-gray-100">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Product Name</div>
                    <div class="text-sm text-gray-900 font-medium">${escapeHtml(product.name)}</div>
                </div>
                <div class="p-2.5 border-b border-gray-100">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Brand</div>
                    <div class="text-sm text-gray-900 font-medium">${escapeHtml(product.brand || 'N/A')}</div>
                </div>
                <div class="p-2.5 border-b border-gray-100">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Model Number</div>
                    <div class="text-sm text-gray-900 font-medium">${escapeHtml(product.model_number || 'N/A')}</div>
                </div>
                <div class="p-2.5 border-b border-gray-100">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Architecture/Socket</div>
                    <div class="text-sm text-gray-900 font-medium">${escapeHtml(product.architecture_socket || 'N/A')}</div>
                </div>
                <div class="p-2.5 border-b border-gray-100">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Core Configuration</div>
                    <div class="text-sm text-gray-900 font-medium">${escapeHtml(product.core_configuration || 'N/A')}</div>
                </div>
                <div class="p-2.5 border-b border-gray-100">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Integrated Graphics</div>
                    <div class="text-sm text-gray-900 font-medium">${escapeHtml(product.integrated_graphics || 'N/A')}</div>
                </div>
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
                    <div class="text-sm text-gray-900 font-medium">${product.category ? escapeHtml(product.category.name) : 'N/A'}</div>
                </div>
                <div class="p-2.5 border-b border-gray-100">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Status</div>
                    <div class="text-sm text-gray-900 font-medium">${product.quantity > 0 ? 'In Stock' : 'Out of Stock'}</div>
                </div>
            </div>
            ${product.description ? `
            <div class="p-3 border-t border-gray-100">
                <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Description</div>
                <div class="text-sm text-gray-900 leading-relaxed">${escapeHtml(product.description)}</div>
            </div>
            ` : ''}
            ${product.performance ? `
            <div class="p-3 border-t border-gray-100">
                <div class="text-[10px] text-gray-500 uppercase tracking-[0.3px] mb-1">Performance</div>
                <div class="text-sm text-gray-900 leading-relaxed">${escapeHtml(product.performance)}</div>
            </div>
            ` : ''}
        `;

        document.getElementById('detailModal').classList.add('open');
    }

    async function loadCategoryFilter() {
        try {
            let res = await fetch('/api/categories');
            let categories = await res.json();
            let select = document.getElementById('categoryFilter');

            categories.forEach(cat => {
                select.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
            });
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    function filterProducts() {
        let categoryId = document.getElementById('categoryFilter').value;
        if (!categoryId) {
            displayProducts(allProducts);
        } else {
            let filtered = allProducts.filter(p => p.category_id == categoryId);
            displayProducts(filtered);
        }
    }

    function loadHistory() {
        loadComingProducts();
        loadReceivedProducts();
        alert('History loaded - showing all coming and received products');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('supplierQuantity');
        if (quantityInput) {
            quantityInput.addEventListener('change', updateSupplierTotalPrice);
            quantityInput.addEventListener('input', updateSupplierTotalPrice);
        }
        
        const confirmBtn = document.getElementById('confirmSupplierBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', confirmSupplierPurchase);
        }
    });

    loadProducts();
    loadComingProducts();
    loadReceivedProducts();

    setInterval(() => {
        loadComingProducts();
        loadReceivedProducts();
    }, 30000);
</script>
@endsection