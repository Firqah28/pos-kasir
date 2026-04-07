@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Kategori</h1>
                <p class="text-gray-500">Kelola daftar kategori barang</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button type="button" onclick="openModal()" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all btn-ripple">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kategori
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
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Kategori</th>
                        <th scope="col" class="relative py-4 pl-4 pr-6">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="kategoriList" class="divide-y divide-gray-100 bg-white">
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="kategoriModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg animate-scale-in">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white" id="modal-title">Data Kategori</h3>
                            <p class="text-blue-100 text-sm">Masukkan informasi kategori</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6">
                    <form id="kategoriForm">
                        <input type="hidden" id="kategori_id">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                            <input type="text" id="nama_kategori" required class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4 input-modern" placeholder="Masukkan nama kategori">
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
    async function loadKategori() {
        const res = await fetch('/api/kategori');
        const data = await res.json();
        const tbody = document.getElementById('kategoriList');
        tbody.innerHTML = '';
        
        if (data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="py-12 text-center">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Belum ada kategori</p>
                        <p class="text-gray-400 text-xs mt-1">Tambahkan kategori baru untuk mengelola barang</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        data.forEach(k => {
            tbody.innerHTML += `
                <tr class="table-row-enter hover:bg-gray-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-mono text-gray-500 sm:pl-6">${k.id}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-gray-900">
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10">
                            ${k.nama_kategori}
                        </span>
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-4 pr-6 text-right text-sm font-medium sm:pr-6">
                        <button onclick='editKategori(${JSON.stringify(k)})' class="text-blue-600 hover:text-blue-900 font-semibold hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors mr-2">Edit</button>
                        <button onclick="deleteKategori(${k.id})" class="text-red-600 hover:text-red-900 font-semibold hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                    </td>
                </tr>
            `;
        });
    }

    function openModal() {
        document.getElementById('kategoriModal').classList.remove('hidden');
        document.getElementById('kategoriForm').reset();
        document.getElementById('kategori_id').value = '';
    }

    function closeModal() {
        document.getElementById('kategoriModal').classList.add('hidden');
    }

    function editKategori(k) {
        openModal();
        document.getElementById('kategori_id').value = k.id;
        document.getElementById('nama_kategori').value = k.nama_kategori;
    }

    function deleteKategori(id) {
        Swal.fire({
            title: 'Hapus Kategori?',
            text: "Kategori yang dihapus mungkin akan mempengaruhi barang terkait!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const res = await fetch('/api/kategori/' + id, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    if (res.ok) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Kategori berhasil dihapus.', confirmButtonColor: '#10b981' });
                        loadKategori();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Kategori gagal dihapus.', confirmButtonColor: '#ef4444' });
                    }
                } catch(e) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.', confirmButtonColor: '#ef4444' });
                }
            }
        });
    }

    document.getElementById('kategoriForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('kategori_id').value;
        const payload = { nama_kategori: document.getElementById('nama_kategori').value };

        const method = id ? 'PUT' : 'POST';
        const url = id ? '/api/kategori/' + id : '/api/kategori';

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
            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Data kategori berhasil disimpan.', timer: 1500, showConfirmButton: false });
            loadKategori();
        }
    });

    loadKategori();
</script>
@endsection
