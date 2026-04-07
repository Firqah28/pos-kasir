@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Kasir / Penjualan</h1>
        <p class="text-gray-500">Proses transaksi penjualan dengan cepat dan mudah</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Search & Product Grid -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Search & Filter -->
            <div class="bg-white p-5 rounded-2xl shadow-soft border border-gray-100">
                <!-- Scanner Status Indicator -->
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center space-x-2 bg-green-50 px-3 py-2 rounded-xl border border-green-200">
                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                        <span class="text-xs font-semibold text-green-700">Scanner Aktif</span>
                    </div>
                    <span class="text-xs text-gray-500">Scan barcode dari mana saja di halaman ini</span>
                </div>
                <form id="searchForm" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                            </svg>
                        </div>
                        <input type="text" id="searchInput"
                            class="block w-full pl-11 pr-4 py-3 rounded-xl border-0 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 input-modern"
                            placeholder="Ketik nama barang atau scan barcode..." autofocus>
                    </div>
                    <div class="w-full sm:w-48 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            </svg>
                        </div>
                        <select id="filterKategori" onchange="applyKasirFilter()" 
                            class="block w-full pl-11 pr-4 py-3 rounded-xl border-0 ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 input-modern appearance-none bg-white">
                            <option value="">Semua Kategori</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all btn-ripple">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="bg-white p-5 rounded-2xl shadow-soft border border-gray-100 min-h-[500px]">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Daftar Produk</h2>
                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full" id="productCount">0 Produk</span>
                </div>
                <div id="productsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <!-- Products will be rendered here -->
                </div>
            </div>
        </div>

        <!-- Right Column: Cart & Payment -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 flex flex-col h-auto lg:h-[calc(100vh-12rem)] sticky top-6">
            <!-- Cart Header -->
            <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-purple-50 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Keranjang</h2>
                            <p class="text-xs text-gray-500" id="cartItemCount">0 item</p>
                        </div>
                    </div>
                    <button onclick="clearCart()" class="text-xs text-red-500 hover:text-red-700 font-medium hover:bg-red-50 px-3 py-2 rounded-lg transition-colors">
                        Hapus Semua
                    </button>
                </div>
            </div>

            <!-- Cart Items -->
            <div id="cartItems" class="flex-1 overflow-y-auto p-5 space-y-3">
                <!-- Items will be rendered here -->
            </div>

            <!-- Empty Cart State -->
            <div id="emptyCartState" class="flex-1 flex flex-col items-center justify-center p-8 text-center">
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                <p class="text-gray-500 text-sm font-medium">Keranjang belanja kosong</p>
                <p class="text-gray-400 text-xs mt-1">Pilih produk untuk memulai transaksi</p>
            </div>

            <!-- Payment Section -->
            <div class="p-5 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-b-2xl">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <span class="text-base font-bold text-gray-900">Total Tagihan</span>
                        <span id="cartTotal" class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Rp 0</span>
                    </div>

                    <div>
                        <label for="bayar" class="block text-sm font-semibold text-gray-700 mb-2">Uang Diterima</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">Rp</span>
                            </div>
                            <input type="number" id="bayar" 
                                class="block w-full pl-12 pr-4 py-3 rounded-xl border-0 ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-green-500 sm:text-lg font-semibold input-modern" 
                                placeholder="0" required>
                        </div>
                    </div>

                    <div class="flex justify-between items-center p-4 rounded-xl bg-green-50 border border-green-200">
                        <span class="text-sm font-semibold text-gray-700">Kembalian</span>
                        <span id="kembalian" class="text-xl font-bold text-green-600">Rp 0</span>
                    </div>

                    <button type="button" onclick="showPaymentConfirmation()" id="btnBayar" 
                        class="w-full rounded-xl bg-gradient-to-r from-green-500 to-green-600 px-4 py-4 text-base font-bold text-white shadow-lg shadow-green-500/30 hover:shadow-xl hover:shadow-green-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none transition-all btn-ripple" 
                        disabled>
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H3V10.5z" />
                            </svg>
                            <span>Proses Pembayaran</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Confirmation Modal -->
<div id="confirmModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md animate-scale-in">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-8">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/20 backdrop-blur-md">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-center text-xl font-bold text-white">Konfirmasi Transaksi</h3>
                </div>
                
                <div class="px-6 py-6">
                    <div class="bg-gray-50 rounded-2xl p-5 space-y-3 border border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Total Tagihan</span>
                            <span class="font-bold text-gray-900 text-lg" id="confTagihan">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Uang Diterima</span>
                            <span class="font-bold text-gray-900 text-lg" id="confBayar">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t-2 border-dashed border-gray-200">
                            <span class="text-sm font-bold text-gray-700">Kembalian</span>
                            <span class="font-bold text-green-600 text-xl" id="confKembalian">Rp 0</span>
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-500 text-center mt-4">
                        <svg class="inline h-4 w-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        Pastikan data transaksi sudah sesuai. Aksi ini akan memotong stok barang secara permanen.
                    </p>
                </div>
                
                <div class="grid grid-cols-2 gap-3 px-6 pb-6">
                    <button type="button" onclick="closeConfirmModal()" 
                        class="rounded-xl bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="executeSubmit()" 
                        class="rounded-xl bg-gradient-to-r from-green-500 to-green-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-green-500/30 hover:shadow-xl transition-all">
                        Ya, Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let products = [];
    let cart = [];
    let totalHarga = 0;

    async function loadProducts() {
        try {
            const res = await fetch('/api/barang');
            products = await res.json();
            renderProducts(products);
            document.getElementById('productCount').innerText = `${products.length} Produk`;
        } catch(e) {
            console.error('Failed to load products', e);
        }
    }

    function renderProducts(items) {
        const grid = document.getElementById('productsGrid');
        grid.innerHTML = '';
        if(items.length === 0) {
            grid.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Barang tidak ditemukan</p>
                    <p class="text-gray-400 text-xs mt-1">Coba dengan kata kunci atau kategori lain</p>
                </div>
            `;
            return;
        }

        items.forEach(p => {
            const outOfStock = p.stok <= 0;
            grid.innerHTML += `
                <div class="card-hover border rounded-xl p-4 cursor-pointer flex flex-col justify-between h-full ${outOfStock ? 'opacity-50 bg-gray-50' : 'hover:border-blue-400 hover:shadow-lg bg-white'} transition-all" onclick="${outOfStock ? '' : `addToCart(${p.id})`}">
                    <div>
                        <div class="flex items-start justify-between mb-2">
                            <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md truncate max-w-[120px]">${p.barcode || '-'}</div>
                            ${outOfStock ? '<span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-md font-semibold">Habis</span>' : ''}
                        </div>
                        <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 mb-2">${p.nama_barang}</h3>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-blue-600 text-sm">Rp ${Number(p.harga_jual).toLocaleString('id-ID')}</span>
                            <span class="text-xs ${outOfStock ? 'text-red-500 font-bold' : 'text-gray-500'}">Stok: ${p.stok}</span>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    async function fetchOptions(endpoint, selectId, isFilter = false) {
        try {
            const res = await fetch(endpoint);
            const data = await res.json();
            const select = document.getElementById(selectId);

            if (isFilter) {
                select.innerHTML = '<option value="">Semua Kategori</option>';
            }

            data.forEach(item => {
                const nameField = endpoint.includes('kategori') ? 'nama_kategori' : 'nama_supplier';
                select.innerHTML += `<option value="${item.id}">${item[nameField]}</option>`;
            });
        } catch (e) {
            console.error('Error fetching options:', e);
        }
    }

    function applyKasirFilter() {
        const query = document.getElementById('searchInput').value.trim().toLowerCase();
        const selectedKategori = document.getElementById('filterKategori').value;

        let filtered = products;

        if (selectedKategori && selectedKategori !== "") {
            filtered = filtered.filter(p => {
                if (p.kategori_id === null || p.kategori_id === undefined) return false;
                return String(p.kategori_id) === String(selectedKategori);
            });
        }

        if (query) {
            filtered = filtered.filter(p =>
                (p.nama_barang && p.nama_barang.toLowerCase().includes(query)) ||
                (p.barcode && p.barcode.toLowerCase() === query)
            );
        }

        renderProducts(filtered);
    }

    document.getElementById('searchForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const query = document.getElementById('searchInput').value.trim();

        if (query) {
            const exactLocalMatch = products.find(p => p.barcode && p.barcode.toLowerCase() === query.toLowerCase());
            if (exactLocalMatch && exactLocalMatch.stok > 0) {
                addToCart(exactLocalMatch.id);
                document.getElementById('searchInput').value = '';
                applyKasirFilter();
                return;
            }
        }

        applyKasirFilter();
    });

    function addToCart(id) {
        const product = products.find(p => p.id === id);
        if(!product || product.stok <= 0) return;

        const existing = cart.find(item => item.barang_id === id);
        if(existing) {
            if (existing.qty < product.stok) {
                existing.qty++;
                existing.subtotal = existing.qty * existing.harga_jual;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Tidak Mencukupi',
                    text: 'Stok maksimal telah tercapai!',
                    confirmButtonColor: '#3b82f6'
                });
            }
        } else {
            cart.push({
                barang_id: product.id,
                nama_barang: product.nama_barang,
                harga_jual: parseFloat(product.harga_jual) || 0,
                qty: 1,
                subtotal: parseFloat(product.harga_jual) || 0
            });
        }
        updateCartUI();
    }

    function updateCartQty(id, delta) {
        const item = cart.find(i => i.barang_id === id);
        if(!item) return;

        const product = products.find(p => p.id === id);

        item.qty += delta;
        if(item.qty <= 0) {
            cart = cart.filter(i => i.barang_id !== id);
        } else if (item.qty > product.stok) {
            item.qty = product.stok;
            Swal.fire({
                icon: 'info',
                title: 'Stok Maksimal',
                text: `Stok tersedia: ${product.stok}`,
                confirmButtonColor: '#3b82f6'
            });
        } else {
            item.subtotal = item.qty * item.harga_jual;
        }
        updateCartUI();
    }

    function updateCartQtyValue(id, value) {
        const item = cart.find(i => i.barang_id === id);
        if(!item) return;

        const product = products.find(p => p.id === id);
        const newQty = parseInt(value, 10);

        if (isNaN(newQty) || newQty <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Kuantitas tidak valid!',
                confirmButtonColor: '#3b82f6'
            });
            updateCartUI();
            return;
        }

        if (newQty > product.stok) {
            Swal.fire({
                icon: 'info',
                title: 'Stok Maksimal',
                text: `Stok tersedia: ${product.stok}`,
                confirmButtonColor: '#3b82f6'
            });
            item.qty = product.stok;
        } else {
            item.qty = newQty;
        }

        item.subtotal = item.qty * item.harga_jual;
        updateCartUI();
    }

    function removeCartItem(id) {
        cart = cart.filter(i => i.barang_id !== id);
        updateCartUI();
    }

    function clearCart() {
        if (cart.length === 0) return;
        Swal.fire({
            title: 'Hapus Keranjang?',
            text: "Semua item akan dihapus dari keranjang!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                updateCartUI();
            }
        });
    }

    function updateCartUI() {
        const cartEl = document.getElementById('cartItems');
        const emptyState = document.getElementById('emptyCartState');
        cartEl.innerHTML = '';
        totalHarga = 0;

        if(cart.length === 0) {
            emptyState.style.display = 'flex';
            document.getElementById('cartItemCount').innerText = '0 item';
            document.getElementById('btnBayar').disabled = true;
        } else {
            emptyState.style.display = 'none';
            document.getElementById('btnBayar').disabled = false;
            document.getElementById('cartItemCount').innerText = `${cart.length} item`;
            
            cart.forEach(item => {
                totalHarga += item.subtotal;
                cartEl.innerHTML += `
                    <div class="flex justify-between items-center p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-blue-200 transition-colors">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900 truncate">${item.nama_barang}</h4>
                            <div class="text-xs text-blue-600 font-bold mt-1">Rp ${Number(item.harga_jual).toLocaleString('id-ID')}</div>
                        </div>
                        <div class="flex items-center space-x-2 ml-3">
                            <button type="button" onclick="updateCartQty(${item.barang_id}, -1)" class="w-7 h-7 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:border-blue-300 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" /></svg>
                            </button>
                            <input type="number" value="${item.qty}" onchange="updateCartQtyValue(${item.barang_id}, this.value)"
                                class="w-12 px-1 py-1 text-sm font-bold text-center border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" min="1" />
                            <button type="button" onclick="updateCartQty(${item.barang_id}, 1)" class="w-7 h-7 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:border-blue-300 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" /></svg>
                            </button>
                            <button type="button" onclick="removeCartItem(${item.barang_id})" class="w-7 h-7 rounded-lg bg-red-50 border border-red-200 flex items-center justify-center text-red-500 hover:bg-red-100 transition-colors ml-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </div>
                    </div>
                `;
            });
        }

        document.getElementById('cartTotal').innerText = 'Rp ' + Number(totalHarga).toLocaleString('id-ID');
        calculateKembalian();
    }

    const bayarInput = document.getElementById('bayar');
    bayarInput.addEventListener('input', calculateKembalian);
    bayarInput.addEventListener('focus', function() { if(this.value === '0') this.value = ''; });
    bayarInput.addEventListener('blur', function() { if(this.value === '') this.value = '0'; });

    async function initPage() {
        await fetchOptions('/api/kategori', 'filterKategori', true);
        await loadProducts();
        updateCartUI();
    }

    initPage();

    // ==========================================
    // GLOBAL BARCODE SCANNER FUNCTIONALITY
    // ==========================================
    let scannerBuffer = '';
    let scannerTimeout = null;
    const SCAN_TIMEOUT = 150; // ms between keystrokes

    // Global keydown listener for barcode scanner
    document.addEventListener('keydown', function(e) {
        // Ignore if typing in input/textarea/select
        const activeElement = document.activeElement;
        const isInputFocused = activeElement.tagName === 'INPUT' || 
                               activeElement.tagName === 'TEXTAREA' || 
                               activeElement.tagName === 'SELECT';
        
        // If Enter is pressed
        if (e.key === 'Enter') {
            if (isInputFocused && activeElement.id === 'searchInput') {
                // Let the form submit handle it
                return;
            }
            
            // If we have a scanner buffer, process it as a barcode
            if (scannerBuffer.length > 0) {
                e.preventDefault();
                handleBarcodeScan(scannerBuffer.trim());
                scannerBuffer = '';
            }
            return;
        }
        
        // If not an input field and it's a printable character, capture for scanner
        if (!isInputFocused && e.key.length === 1 && !e.ctrlKey && !e.altKey && !e.metaKey) {
            e.preventDefault();
            scannerBuffer += e.key;
            
            // Reset buffer if no more input within timeout
            clearTimeout(scannerTimeout);
            scannerTimeout = setTimeout(() => {
                scannerBuffer = '';
            }, SCAN_TIMEOUT);
        }
    });

    // Handle barcode scan
    function handleBarcodeScan(barcode) {
        if (!barcode) return;
        
        console.log('Scanned barcode:', barcode);
        
        // Search for exact barcode match
        const product = products.find(p => p.barcode && p.barcode.toLowerCase() === barcode.toLowerCase());
        
        if (product) {
            if (product.stok > 0) {
                addToCart(product.id);
                showScanFeedback('✓ ' + product.nama_barang, 'success');
            } else {
                showScanFeedback('✗ Stok habis: ' + product.nama_barang, 'error');
            }
        } else {
            showScanFeedback('✗ Produk tidak ditemukan: ' + barcode, 'error');
        }
        
        // Clear search input
        document.getElementById('searchInput').value = '';
    }

    // Show scan feedback toast notification
    function showScanFeedback(message, type) {
        // Remove existing feedback if any
        const existingFeedback = document.getElementById('scanFeedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }
        
        // Create feedback element
        const feedback = document.createElement('div');
        feedback.id = 'scanFeedback';
        feedback.className = `fixed top-24 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 translate-x-full ${
            type === 'success' 
                ? 'bg-gradient-to-r from-green-500 to-green-600 text-white' 
                : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
        }`;
        
        feedback.innerHTML = `
            <div class="flex items-center space-x-3">
                ${type === 'success' 
                    ? '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    : '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                }
                <span class="font-semibold text-sm">${message}</span>
            </div>
        `;
        
        document.body.appendChild(feedback);
        
        // Animate in
        setTimeout(() => feedback.classList.remove('translate-x-full'), 10);
        
        // Animate out after 2 seconds
        setTimeout(() => {
            feedback.classList.add('translate-x-full');
            setTimeout(() => feedback.remove(), 300);
        }, 2000);
    }

    function calculateKembalian() {
        if(cart.length === 0) {
            document.getElementById('kembalian').innerText = 'Rp 0';
            document.getElementById('kembalian').classList.remove('text-red-600');
            document.getElementById('kembalian').classList.add('text-green-600');
            return;
        }

        const bayar = parseFloat(bayarInput.value) || 0;
        const kembalian = bayar - totalHarga;

        const kembaliEl = document.getElementById('kembalian');
        if (kembalian < 0) {
            kembaliEl.innerText = '- Rp ' + Number(Math.abs(kembalian)).toLocaleString('id-ID');
            kembaliEl.classList.add('text-red-600');
            kembaliEl.classList.remove('text-green-600');
            document.getElementById('btnBayar').disabled = true;
        } else {
            kembaliEl.innerText = 'Rp ' + Number(kembalian).toLocaleString('id-ID');
            kembaliEl.classList.remove('text-red-600');
            kembaliEl.classList.add('text-green-600');
            document.getElementById('btnBayar').disabled = false;
        }
    }

    function showPaymentConfirmation() {
        if(cart.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Keranjang Kosong',
                text: 'Tambahkan produk terlebih dahulu!',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        const bayar = parseFloat(bayarInput.value);
        if(isNaN(bayar) || bayar <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Input uang pembayaran tidak valid!',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        if(bayar < totalHarga) {
            Swal.fire({
                icon: 'warning',
                title: 'Uang Kurang',
                text: 'Uang pembayaran kurang dari total tagihan!',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        const kembalian = bayar - totalHarga;

        document.getElementById('confTagihan').innerText = 'Rp ' + Number(totalHarga).toLocaleString('id-ID');
        document.getElementById('confBayar').innerText = 'Rp ' + Number(bayar).toLocaleString('id-ID');
        document.getElementById('confKembalian').innerText = 'Rp ' + Number(kembalian).toLocaleString('id-ID');
        document.getElementById('confirmModal').classList.remove('hidden');
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }

    function executeSubmit() {
        closeConfirmModal();
        const bayar = parseFloat(bayarInput.value);
        const kembalian = bayar - totalHarga;
        submitTransaction(bayar, kembalian);
    }

    async function submitTransaction(bayar, kembalian) {
        const payload = {
            _token: '{{ csrf_token() }}',
            total_harga: totalHarga,
            bayar: bayar,
            kembalian: kembalian,
            items: cart
        };

        try {
            document.getElementById('btnBayar').disabled = true;
            document.getElementById('btnBayar').innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';

            const res = await fetch('/api/transaksi', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            if(res.ok) {
                const data = await res.json();
                Swal.fire({
                    icon: 'success',
                    title: 'Transaksi Berhasil!',
                    html: `ID Transaksi: <strong class="text-blue-600">${data.transaksi_id}</strong><br>Kembalian: <strong class="text-green-600">Rp ${Number(kembalian).toLocaleString('id-ID')}</strong>`,
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    document.getElementById('searchInput').focus();
                });

                cart = [];
                bayarInput.value = '';
                updateCartUI();
                await loadProducts();
            } else {
                const err = await res.json();
                Swal.fire({
                    icon: 'error',
                    title: 'Transaksi Gagal',
                    text: err.error || 'Terjadi kesalahan',
                    confirmButtonColor: '#ef4444'
                });
            }
        } catch(error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan jaringan.',
                confirmButtonColor: '#ef4444'
            });
        } finally {
            document.getElementById('btnBayar').disabled = false;
            document.getElementById('btnBayar').innerHTML = '<div class="flex items-center justify-center space-x-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H3V10.5z" /></svg><span>Proses Pembayaran</span></div>';
        }
    }
</script>
@endsection
