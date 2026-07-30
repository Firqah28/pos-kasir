@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembelian (Restock)</h1>
        <p class="text-gray-500">Kelola pembelian dan restock barang dari supplier</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Search & Product Grid -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Search -->
            <div class="bg-white p-5 rounded-2xl shadow-soft border border-gray-100">
                <form id="searchForm" class="flex gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                            </svg>
                        </div>
                        <input type="text" id="searchInput" 
                            class="block w-full pl-11 pr-4 py-3 rounded-xl border-0 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 input-modern" 
                            placeholder="Cari nama barang atau scan barcode..." autofocus>
                    </div>
                    <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all btn-ripple">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="bg-white p-5 rounded-2xl shadow-soft border border-gray-100 min-h-[500px]">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Pilih Barang untuk Dipesan</h2>
                <div id="productsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <!-- Products will be rendered here -->
                </div>
            </div>
        </div>

        <!-- Right Column: Cart & Checkout -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 flex flex-col h-auto lg:h-[calc(100vh-12rem)] sticky top-6">
            <!-- Cart Header -->
            <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-purple-50 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Daftar Pembelian</h2>
                            <p class="text-xs text-gray-500">Pilih supplier tujuan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supplier Selection -->
            <div class="p-5 border-b border-gray-100">
                <label for="supplier_id" class="block text-sm font-semibold text-gray-700 mb-2">Supplier Tujuan</label>
                <select id="supplier_id" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4 bg-white">
                    <option value="">-- Pilih Supplier --</option>
                </select>
            </div>

            <!-- Cart Items -->
            <div id="cartItems" class="flex-1 overflow-y-auto p-5 space-y-3">
                <!-- Items will be rendered here -->
            </div>

            <!-- Empty Cart State -->
            <div id="emptyCartState" class="flex-1 flex flex-col items-center justify-center p-8 text-center hidden">
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                    </svg>
                </div>
                <p class="text-gray-500 text-sm font-medium">Daftar pembelian kosong</p>
                <p class="text-gray-400 text-xs mt-1">Pilih barang untuk melakukan pembelian</p>
            </div>

            <!-- Payment Section -->
            <div class="p-5 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-b-2xl">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <span class="text-base font-bold text-gray-900">Total Pembelian</span>
                        <span id="cartTotal" class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Rp 0</span>
                    </div>

                    <button type="button" id="btnSelesaikan" onclick="showConfirmModal()" 
                        class="w-full rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-4 text-base font-bold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none transition-all btn-ripple" 
                        disabled>
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Selesaikan Pembelian</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md animate-scale-in">
                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-8">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/20 backdrop-blur-md">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-center text-xl font-bold text-white">Konfirmasi Pembelian</h3>
                </div>
                
                <div class="px-6 py-6">
                    <div class="bg-gray-50 rounded-2xl p-5 space-y-3 border border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Supplier</span>
                            <span class="font-bold text-gray-900" id="confSupplier">-</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t-2 border-dashed border-gray-200">
                            <span class="text-sm font-bold text-gray-700">Total Pembelian</span>
                            <span class="font-bold text-blue-600 text-xl" id="confTagihan">Rp 0</span>
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-500 text-center mt-4">
                        <svg class="inline h-4 w-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        Pastikan data supplier dan barang sudah benar. Aksi ini akan memodifikasi stok barang dan data finansial!
                    </p>
                </div>
                
                <div class="grid grid-cols-2 gap-3 px-6 pb-6">
                    <button type="button" onclick="closeConfirmModal()" 
                        class="rounded-xl bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="executeSubmit()" 
                        class="rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all">
                        Ya, Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function playSuccessSound() {
        try {
            const utter = new SpeechSynthesisUtterance('Pembelian Berhasil');
            utter.lang = 'id-ID';
            utter.rate = 1.1;
            utter.pitch = 1.2;
            speechSynthesis.cancel();
            speechSynthesis.speak(utter);
        } catch(e) {}
    }

    let products = [];
    let cart = [];
    let totalHarga = 0;

    async function loadData() {
        try {
            const supRes = await fetch('/api/supplier');
            const suppliers = await supRes.json();
            const supSelect = document.getElementById('supplier_id');
            suppliers.forEach(s => {
                supSelect.innerHTML += `<option value="${s.id}">${s.nama_supplier}</option>`;
            });

            const res = await fetch('/api/barang');
            if (res.status === 401) {
                window.location.href = '/login';
                return;
            }
            products = await res.json();
            renderProducts(products);
        } catch(e) {
            console.error('Failed to load data', e);
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
                    <p class="text-gray-400 text-xs mt-1">Coba dengan kata kunci lain</p>
                </div>
            `;
            return;
        }

        items.forEach(p => {
            grid.innerHTML += `
                <div class="card-hover border rounded-xl p-4 cursor-pointer flex flex-col justify-between h-full hover:border-blue-400 hover:shadow-lg bg-white transition-all" onclick="addToCart(${p.id})">
                    <div>
                        <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md mb-2 truncate">${p.barcode || '-'}</div>
                        <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 mb-2">${p.nama_barang}</h3>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-blue-600 text-sm">Rp ${Number(p.harga_beli).toLocaleString('id-ID')}</span>
                            <span class="text-xs text-gray-500">Stok: ${p.stok}</span>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    document.getElementById('searchForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const query = document.getElementById('searchInput').value.trim();
        if(!query) {
            renderProducts(products);
            return;
        }

        try {
            const res = await fetch('/api/barang/' + encodeURIComponent(query));
            const data = await res.json();

            if (data.id) {
                addToCart(data.id);
                document.getElementById('searchInput').value = '';
                renderProducts(products);
                return;
            }
        } catch(e) {
            console.error('Barcode search error', e);
        }

        const qLower = query.toLowerCase();
        const filtered = products.filter(p =>
            (p.nama_barang && p.nama_barang.toLowerCase().includes(qLower)) ||
            (p.barcode && p.barcode.toLowerCase() === qLower)
        );
        renderProducts(filtered);
    });

    function addToCart(id) {
        const product = products.find(p => p.id === id);
        if(!product) return;

        const existing = cart.find(item => item.barang_id === id);
        if(existing) {
            existing.qty++;
            existing.subtotal = existing.qty * existing.harga_beli;
        } else {
            cart.push({
                barang_id: product.id,
                nama_barang: product.nama_barang,
                harga_beli: parseFloat(product.harga_beli) || 0,
                qty: 1,
                subtotal: parseFloat(product.harga_beli) || 0
            });
        }
        updateCartUI();
    }

    function updateCartQty(id, delta) {
        const item = cart.find(i => i.barang_id === id);
        if(!item) return;

        item.qty += delta;
        if(item.qty <= 0) {
            cart = cart.filter(i => i.barang_id !== id);
        } else {
            item.subtotal = item.qty * item.harga_beli;
        }
        updateCartUI();
    }

    function updateCartQtyValue(id, value) {
        const item = cart.find(i => i.barang_id === id);
        if(!item) return;

        const newQty = parseInt(value, 10);
        if (isNaN(newQty) || newQty <= 0) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Kuantitas tidak valid!', confirmButtonColor: '#3b82f6' });
            updateCartUI();
            return;
        }

        item.qty = newQty;
        item.subtotal = item.qty * item.harga_beli;
        updateCartUI();
    }

    function removeCartItem(id) {
        cart = cart.filter(i => i.barang_id !== id);
        updateCartUI();
    }

    function updateCartPrice(id, newPrice) {
        const item = cart.find(i => i.barang_id === id);
        if(item) {
            const parsedPrice = parseFloat(newPrice);
            if (!isNaN(parsedPrice) && parsedPrice >= 0) {
                item.harga_beli = parsedPrice;
                item.subtotal = item.qty * item.harga_beli;
                updateCartUI();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Input harga beli tidak valid!', confirmButtonColor: '#3b82f6' });
                updateCartUI();
            }
        }
    }

    function updateCartUI() {
        const cartEl = document.getElementById('cartItems');
        const emptyState = document.getElementById('emptyCartState');
        cartEl.innerHTML = '';
        totalHarga = 0;

        if(cart.length === 0) {
            emptyState.style.display = 'flex';
            document.getElementById('btnSelesaikan').disabled = true;
        } else {
            emptyState.style.display = 'none';
            document.getElementById('btnSelesaikan').disabled = false;
            cart.forEach(item => {
                totalHarga += item.subtotal;
                cartEl.innerHTML += `
                    <div class="flex flex-col p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-blue-200 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-sm font-semibold text-gray-900 flex-1 pr-2">${item.nama_barang}</h4>
                            <button type="button" onclick="removeCartItem(${item.barang_id})" class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-1">
                                <span class="text-xs text-gray-500">Rp</span>
                                <input type="number" value="${item.harga_beli}" onchange="updateCartPrice(${item.barang_id}, this.value)"
                                    class="w-20 text-xs font-bold text-blue-600 border-gray-300 rounded-lg px-1 py-0.5 focus:ring-2 focus:ring-blue-500" min="0" />
                            </div>
                            <div class="flex items-center border rounded-lg bg-white">
                                <button type="button" onclick="updateCartQty(${item.barang_id}, -1)" class="px-2 py-1 text-gray-600 hover:bg-blue-50 transition-colors">-</button>
                                <input type="number" value="${item.qty}" onchange="updateCartQtyValue(${item.barang_id}, this.value)"
                                    class="w-14 px-1 py-1 text-xs font-medium text-center border-none focus:ring-0 bg-transparent m-0 p-0" min="1" />
                                <button type="button" onclick="updateCartQty(${item.barang_id}, 1)" class="px-2 py-1 text-gray-600 hover:bg-blue-50 transition-colors">+</button>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        document.getElementById('cartTotal').innerText = 'Rp ' + Number(totalHarga).toLocaleString('id-ID');
    }

    function showConfirmModal() {
        const supplier_id = document.getElementById('supplier_id').value;
        if (!supplier_id) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Supplier harus dipilih!', confirmButtonColor: '#3b82f6' });
            return;
        }

        if (cart.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Daftar pembelian masih kosong!', confirmButtonColor: '#3b82f6' });
            return;
        }

        const supplierName = document.getElementById('supplier_id').options[document.getElementById('supplier_id').selectedIndex].text;

        document.getElementById('confSupplier').innerText = supplierName;
        document.getElementById('confTagihan').innerText = 'Rp ' + Number(totalHarga).toLocaleString('id-ID');
        document.getElementById('confirmModal').classList.remove('hidden');
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }

    function executeSubmit() {
        closeConfirmModal();
        submitTransaction();
    }

    async function submitTransaction() {
        const supplier_id = document.getElementById('supplier_id').value;
        const payload = {
            supplier_id: supplier_id,
            items: cart.map(c => ({
                barang_id: c.barang_id,
                qty: c.qty,
                harga_beli: c.harga_beli
            }))
        };

        const btn = document.getElementById('btnSelesaikan');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';

        try {
            const res = await fetch('/api/pembelian', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                const data = await res.json();
                playSuccessSound();
                Swal.fire({
                    icon: 'success',
                    title: 'Pembelian Berhasil!',
                    text: 'Pembelian berhasil disimpan.',
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    document.getElementById('searchInput').focus();
                });
                cart = [];
                document.getElementById('supplier_id').value = '';
                updateCartUI();
                await loadData();
            } else {
                const error = await res.json();
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal: ' + (error.error || 'Terjadi kesalahan'), confirmButtonColor: '#ef4444' });
            }
        } catch (err) {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server', confirmButtonColor: '#ef4444' });
        } finally {
            btn.disabled = (!cart.length);
            btn.innerHTML = '<div class="flex items-center justify-center space-x-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg><span>Selesaikan Pembelian</span></div>';
        }
    }

    loadData();
</script>
@endsection
