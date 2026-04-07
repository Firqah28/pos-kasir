@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Supplier</h1>
                <p class="text-gray-500">Kelola daftar supplier barang</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button type="button" onclick="openModal()" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all btn-ripple">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.941 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    Tambah Supplier
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Supplier</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kontak</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alamat</th>
                        <th scope="col" class="relative py-4 pl-4 pr-6">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="supplierList" class="divide-y divide-gray-100 bg-white">
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="supplierModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg animate-scale-in">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.941 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white" id="modal-title">Data Supplier</h3>
                            <p class="text-blue-100 text-sm">Masukkan informasi supplier</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6">
                    <form id="supplierForm">
                        <input type="hidden" id="supplier_id">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Supplier <span class="text-red-500">*</span></label>
                                <input type="text" id="nama_supplier" required class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4 input-modern" placeholder="Masukkan nama supplier">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kontak</label>
                                <input type="text" id="kontak" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4 input-modern" placeholder="Nomor telepon / Email">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                                <textarea id="alamat" rows="3" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4 input-modern resize-none" placeholder="Alamat lengkap supplier"></textarea>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <button type="button" onclick="closeModal()" class="rounded-xl bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function loadSupplier() {
        const res = await fetch('/api/supplier');
        const data = await res.json();
        const tbody = document.getElementById('supplierList');
        tbody.innerHTML = '';
        
        if (data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="py-12 text-center">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.941 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Belum ada supplier</p>
                        <p class="text-gray-400 text-xs mt-1">Tambahkan supplier untuk manajemen pembelian</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        data.forEach(s => {
            tbody.innerHTML += `
                <tr class="table-row-enter hover:bg-gray-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-semibold text-gray-900 sm:pl-6">${s.nama_supplier}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">${s.kontak || '-'}</td>
                    <td class="px-3 py-4 text-sm text-gray-600 truncate max-w-xs">${s.alamat || '-'}</td>
                    <td class="relative whitespace-nowrap py-4 pl-4 pr-6 text-right text-sm font-medium sm:pr-6">
                        <button onclick='editSupplier(${JSON.stringify(s)})' class="text-blue-600 hover:text-blue-900 font-semibold hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors mr-2">Edit</button>
                        <button onclick="deleteSupplier(${s.id})" class="text-red-600 hover:text-red-900 font-semibold hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                    </td>
                </tr>
            `;
        });
    }

    function openModal() {
        document.getElementById('supplierModal').classList.remove('hidden');
        document.getElementById('supplierForm').reset();
        document.getElementById('supplier_id').value = '';
    }

    function closeModal() {
        document.getElementById('supplierModal').classList.add('hidden');
    }

    function editSupplier(s) {
        openModal();
        document.getElementById('supplier_id').value = s.id;
        document.getElementById('nama_supplier').value = s.nama_supplier;
        document.getElementById('kontak').value = s.kontak || '';
        document.getElementById('alamat').value = s.alamat || '';
    }

    function deleteSupplier(id) {
        Swal.fire({
            title: 'Hapus Supplier?',
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
                    const res = await fetch('/api/supplier/' + id, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    if (res.ok) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Supplier berhasil dihapus.', confirmButtonColor: '#10b981' });
                        loadSupplier();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Supplier gagal dihapus.', confirmButtonColor: '#ef4444' });
                    }
                } catch(e) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.', confirmButtonColor: '#ef4444' });
                }
            }
        });
    }

    document.getElementById('supplierForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('supplier_id').value;
        const payload = {
            nama_supplier: document.getElementById('nama_supplier').value,
            kontak: document.getElementById('kontak').value,
            alamat: document.getElementById('alamat').value,
        };

        const method = id ? 'PUT' : 'POST';
        const url = id ? '/api/supplier/' + id : '/api/supplier';

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
            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Data supplier berhasil disimpan.', timer: 1500, showConfirmButton: false });
            loadSupplier();
        }
    });

    loadSupplier();
</script>
@endsection
