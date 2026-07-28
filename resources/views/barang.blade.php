@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Barang</h1>
                <p class="text-gray-500">Kelola daftar barang, kategori, supplier, harga, dan stok</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <div class="flex items-center space-x-2 bg-white px-3 py-2 rounded-xl border border-gray-200 shadow-sm">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" id="searchBarang" oninput="applyFilter()" placeholder="Cari barang..." class="block border-0 py-1 text-gray-900 focus:ring-0 sm:text-sm text-xs bg-transparent w-40 placeholder-gray-400">
                </div>
                <div class="flex items-center space-x-2 bg-white px-3 py-2 rounded-xl border border-gray-200 shadow-sm">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                    </svg>
                    <span class="text-xs text-gray-500 font-medium">Filter:</span>
                    <select id="filterKategori" onchange="applyFilter()" class="block border-0 py-1 text-gray-900 focus:ring-0 sm:text-sm text-xs bg-transparent cursor-pointer">
                        <option value="">Semua Kategori</option>
                    </select>
                </div>
                <button type="button" onclick="openModal()" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all btn-ripple">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Barang
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto responsive-table-wrapper">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Barcode</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Beli</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Jual</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Satuan</th>
                        <th scope="col" class="relative py-4 pl-4 pr-6">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="barangList" class="divide-y divide-gray-100 bg-white">
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="barangModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl animate-scale-in">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white" id="modal-title">Data Barang</h3>
                            <p class="text-blue-100 text-sm">Lengkapi informasi barang di bawah ini</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6">
                    <form id="barangForm">
                        <input type="hidden" id="barang_id">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Barcode -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Input Barcode</label>
                                <div class="flex items-center space-x-4 mb-3">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="barcodeMode" value="manual" checked class="form-radio text-blue-600 focus:ring-blue-600 h-4 w-4" onchange="toggleBarcodeMode()">
                                        <span class="ml-2 text-sm text-gray-700">Manual</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="barcodeMode" value="scanner" class="form-radio text-blue-600 focus:ring-blue-600 h-4 w-4" onchange="toggleBarcodeMode()">
                                        <span class="ml-2 text-sm text-gray-700">Scanner USB</span>
                                    </label>
                                </div>
                                <label id="barcodeLabel" class="block text-sm font-semibold text-gray-700 mb-2">Barcode</label>
                                <input type="text" id="barcode" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4 input-modern" placeholder="Ketik atau scan barcode...">
                                <p id="scannerHelp" class="mt-2 text-xs text-blue-600 hidden">
                                    <svg class="inline h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    Mode Scanner aktif. Scan barcode untuk input otomatis.
                                </p>
                            </div>

                            <!-- Nama Barang -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Barang <span class="text-red-500">*</span></label>
                                <input type="text" id="nama_barang" required class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4 input-modern" placeholder="Masukkan nama barang">
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                                <select id="kategori_id" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4 bg-white">
                                    <option value="">-- Pilih Kategori --</option>
                                </select>
                            </div>

                            <!-- Supplier -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Supplier</label>
                                <select id="supplier_id" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4 bg-white">
                                    <option value="">-- Pilih Supplier --</option>
                                </select>
                            </div>

                            <!-- Harga Beli -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Beli <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-bold">Rp</span>
                                    </div>
                                    <input type="number" id="harga_beli" required class="block w-full pl-12 pr-4 rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 input-modern" placeholder="0">
                                </div>
                            </div>

                            <!-- Harga Jual -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Jual <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-bold">Rp</span>
                                    </div>
                                    <input type="number" id="harga_jual" required class="block w-full pl-12 pr-4 rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 input-modern" placeholder="0">
                                </div>
                            </div>

                            <!-- Stok -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Awal <span class="text-red-500">*</span></label>
                                <input type="number" id="stok" required class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4 input-modern" placeholder="0">
                            </div>

                            <!-- Satuan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan</label>
                                <input type="text" id="satuan" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4 input-modern" placeholder="Pcs, Box, Kg, dll">
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="mt-8 grid grid-cols-2 gap-3">
                            <button type="button" onclick="closeModal()" class="rounded-xl bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all">
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let allBarang = [];

    async function loadBarang() {
        try {
            const res = await fetch('/api/barang');
            if (!res.ok) throw new Error(`HTTP error: ${res.status}`);
            allBarang = await res.json();
            applyFilter();
        } catch (error) {
            console.error('Error fetching barang:', error);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat daftar barang.', confirmButtonColor: '#3b82f6' });
        }
    }

    function renderBarangTable(data) {
        const tbody = document.getElementById('barangList');
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="py-12 text-center">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Barang tidak ditemukan</p>
                        <p class="text-gray-400 text-xs mt-1">Tambahkan barang baru atau ubah filter</p>
                    </td>
                </tr>
            `;
            return;
        }
        data.forEach(b => {
            const lowStock = b.stok <= 5;
            tbody.innerHTML += `
                <tr class="table-row-enter hover:bg-gray-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-mono text-gray-600 sm:pl-6">${b.barcode || '-'}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-gray-900">${b.nama_barang}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10">
                            ${b.kategori?.nama_kategori || b.nama_kategori || 'Tanpa Kategori'}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">${b.supplier?.nama_supplier || b.nama_supplier || '-'}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">Rp ${Number(b.harga_beli).toLocaleString('id-ID')}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-green-600">Rp ${Number(b.harga_jual).toLocaleString('id-ID')}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                        <span class="${lowStock ? 'inline-flex items-center gap-1 rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700 ring-1 ring-inset ring-red-600/20' : 'text-gray-600'}">
                            ${lowStock ? '<svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>' : ''}
                            ${b.stok}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">${b.satuan || '-'}</td>
                    <td class="relative whitespace-nowrap py-4 pl-4 pr-6 text-right text-sm font-medium sm:pr-6">
                        <button onclick='editBarang(${JSON.stringify(b)})' class="text-blue-600 hover:text-blue-900 font-semibold mr-4 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">Edit</button>
                        <button onclick="deleteBarang(${b.id})" class="text-red-600 hover:text-red-900 font-semibold hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                    </td>
                </tr>
            `;
        });
    }

    function applyFilter() {
        const selectedKategori = document.getElementById('filterKategori').value;
        const searchQuery = (document.getElementById('searchBarang').value || '').toLowerCase().trim();
        let filteredData = allBarang;

        if (selectedKategori && selectedKategori !== "") {
            filteredData = filteredData.filter(b => {
                if (b.kategori_id === null || b.kategori_id === undefined) return false;
                return String(b.kategori_id) === String(selectedKategori);
            });
        }

        if (searchQuery) {
            filteredData = filteredData.filter(b =>
                (b.nama_barang || '').toLowerCase().includes(searchQuery) ||
                (b.barcode || '').toLowerCase().includes(searchQuery) ||
                (b.kategori?.nama_kategori || b.nama_kategori || '').toLowerCase().includes(searchQuery) ||
                (b.supplier?.nama_supplier || b.nama_supplier || '').toLowerCase().includes(searchQuery) ||
                (b.satuan || '').toLowerCase().includes(searchQuery)
            );
        }

        renderBarangTable(filteredData);
    }

    async function fetchOptions(endpoint, selectId, isFilter = false) {
        try {
            const res = await fetch(endpoint);
            const data = await res.json();
            const select = document.getElementById(selectId);

            if (isFilter) {
                select.innerHTML = '<option value="">Semua Kategori</option>';
            } else {
                select.innerHTML = select.options[0].outerHTML;
            }

            data.forEach(item => {
                const nameField = endpoint.includes('kategori') ? 'nama_kategori' : 'nama_supplier';
                select.innerHTML += `<option value="${item.id}">${item[nameField]}</option>`;
            });
        } catch (e) {
            console.error('Error fetching options:', e);
        }
    }

    async function openModal() {
        document.getElementById('barangModal').classList.remove('hidden');
        document.getElementById('barangForm').reset();
        document.getElementById('barang_id').value = '';
        await Promise.all([
            fetchOptions('/api/kategori', 'kategori_id'),
            fetchOptions('/api/supplier', 'supplier_id')
        ]);
    }

    function closeModal() {
        document.getElementById('barangModal').classList.add('hidden');
    }

    async function editBarang(b) {
        await openModal();
        document.getElementById('barang_id').value = b.id;
        document.getElementById('barcode').value = b.barcode || '';
        document.getElementById('nama_barang').value = b.nama_barang;
        document.getElementById('kategori_id').value = b.kategori_id || '';
        document.getElementById('supplier_id').value = b.supplier_id || '';
        document.getElementById('harga_beli').value = b.harga_beli;
        document.getElementById('harga_jual').value = b.harga_jual;
        document.getElementById('stok').value = b.stok;
        document.getElementById('satuan').value = b.satuan || '';
    }

    function deleteBarang(id) {
        Swal.fire({
            title: 'Hapus Barang?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const res = await fetch('/api/barang/' + id, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    if (res.ok) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Barang berhasil dihapus.', confirmButtonColor: '#10b981' });
                        loadBarang();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Barang gagal dihapus.', confirmButtonColor: '#ef4444' });
                    }
                } catch(e) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.', confirmButtonColor: '#ef4444' });
                }
            }
        });
    }

    document.getElementById('barangForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('barang_id').value;
        const payload = {
            barcode: document.getElementById('barcode').value,
            nama_barang: document.getElementById('nama_barang').value,
            kategori_id: document.getElementById('kategori_id').value,
            supplier_id: document.getElementById('supplier_id').value,
            harga_beli: document.getElementById('harga_beli').value,
            harga_jual: document.getElementById('harga_jual').value,
            stok: document.getElementById('stok').value,
            satuan: document.getElementById('satuan').value,
        };

        const method = id ? 'PUT' : 'POST';
        const url = id ? '/api/barang/' + id : '/api/barang';

        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        });

        if (!res.ok) {
            const errorData = await res.json();
            Swal.fire({ icon: 'error', title: 'Gagal', text: errorData.error || 'Terjadi kesalahan pada server', confirmButtonColor: '#ef4444' });
        } else {
            closeModal();
            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Data barang berhasil disimpan.', timer: 1500, showConfirmButton: false });
            loadBarang();
        }
    });

    function toggleBarcodeMode() {
        const mode = document.querySelector('input[name="barcodeMode"]:checked').value;
        const label = document.getElementById('barcodeLabel');
        const help = document.getElementById('scannerHelp');
        const barcodeInput = document.getElementById('barcode');

        if(mode === 'scanner') {
            label.innerText = 'Barcode (Scanner USB)';
            help.classList.remove('hidden');
            barcodeInput.focus();
        } else {
            label.innerText = 'Barcode (Manual)';
            help.classList.add('hidden');
        }
    }

    document.getElementById('barcode').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('nama_barang').focus();
        }
    });

    async function initPage() {
        await fetchOptions('/api/kategori', 'filterKategori', true);
        await loadBarang();
    }

    initPage();
</script>
@endsection
