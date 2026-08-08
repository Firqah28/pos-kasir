@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Data Cabang Toko</h1>
                <p class="text-gray-500">Kelola cabang toko di bawah kendali pusat</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button type="button" onclick="openModal()" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72l1.189-1.19A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" /></svg>
                    Tambah Toko
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 flex items-center justify-between rounded-2xl border border-green-200 bg-green-50 px-5 py-4">
            <div class="flex items-center gap-3">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4">
            @foreach($errors->all() as $error)
                <p class="text-sm font-semibold text-red-700">⚠ {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Toko</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alamat / Telepon</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pengguna</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Penjualan</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fee (%)</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th scope="col" class="relative py-4 pl-4 pr-6">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($stores as $store)
                    <tr class="table-row-enter hover:bg-gray-50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-mono font-semibold text-gray-500">{{ $store->kode_toko ?? '-' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-gray-900">{{ $store->nama_toko }}</td>
                        <td class="px-3 py-4 text-sm text-gray-600">
                            @if($store->alamat)
                                <p class="truncate max-w-xs">{{ $store->alamat }}</p>
                            @endif
                            @if($store->telepon)
                                <p class="text-xs text-gray-400">{{ $store->telepon }}</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ $store->users_count }} pengguna</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-green-600">Rp {{ number_format($store->transaksis_sum_total_harga ?? 0, 0, ',', '.') }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-gray-700">{{ number_format($store->fee_persen, 0, ',', '.') }}%</td>
                        <td class="whitespace-nowrap px-3 py-4">
                            @if($store->status === 'aktif')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @elseif($store->status === 'menunggu_pembayaran')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Menunggu Pembayaran</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-4 pr-6 text-right text-sm font-medium">
                            <button type="button" onclick='editStore(@json($store))' class="text-blue-600 hover:text-blue-900 font-semibold hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors mr-2">Edit</button>
                            <button type="button" onclick="deleteStore({{ $store->id }}, '{{ addslashes($store->nama_toko) }}')" class="text-red-600 hover:text-red-900 font-semibold hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72l1.189-1.19A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" /></svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">Belum ada cabang toko</p>
                            <p class="text-gray-400 text-xs mt-1">Tambahkan cabang untuk mulai mengelola toko</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="storeModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg animate-scale-in">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72l1.189-1.19A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white" id="modal-title">Data Toko</h3>
                            <p class="text-blue-100 text-sm">Masukkan informasi cabang toko</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6">
                    <form id="storeForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="POST" id="formMethod">
                        <input type="hidden" name="store_id" id="store_id">
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Toko <span class="text-red-500">*</span></label>
                                    <input type="text" id="kode_toko" name="kode_toko" required maxlength="20" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4" placeholder="cth: PSR">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Toko <span class="text-red-500">*</span></label>
                                    <input type="text" id="nama_toko" name="nama_toko" required maxlength="150" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4" placeholder="Nama cabang toko">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                                <textarea id="alamat" name="alamat" rows="2" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4 resize-none" placeholder="Alamat lengkap cabang"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Telepon</label>
                                <input type="text" id="telepon" name="telepon" maxlength="30" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4" placeholder="Nomor telepon toko">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Fee (%)</label>
                                <input type="number" id="fee_persen" name="fee_persen" min="0" max="100" step="0.01" value="5" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4" placeholder="cth: 5">
                                <p class="mt-1 text-xs text-gray-400">Persentase fee yang ditagih dari penjualan toko. Toko aktif setelah pembayaran fee dikonfirmasi.</p>
                            </div>
                        </div>

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

<form id="deleteStoreForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function openModal() {
        document.getElementById('storeModal').classList.remove('hidden');
        document.getElementById('storeForm').reset();
        document.getElementById('store_id').value = '';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('storeForm').action = '{{ route('pusat.toko') }}';
        document.getElementById('fee_persen').value = 5;
        document.getElementById('modal-title').textContent = 'Data Toko';
    }

    function closeModal() {
        document.getElementById('storeModal').classList.add('hidden');
    }

    function editStore(s) {
        openModal();
        document.getElementById('store_id').value = s.id;
        document.getElementById('kode_toko').value = s.kode_toko || '';
        document.getElementById('nama_toko').value = s.nama_toko;
        document.getElementById('alamat').value = s.alamat || '';
        document.getElementById('telepon').value = s.telepon || '';
        document.getElementById('fee_persen').value = s.fee_persen ?? 5;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('storeForm').action = '{{ url('/pusat/toko') }}/' + s.id;
        document.getElementById('modal-title').textContent = 'Edit Toko';
    }

    function deleteStore(id, name) {
        Swal.fire({
            title: 'Hapus Toko?',
            text: `Cabang "${name}" akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteStoreForm');
                form.action = '{{ url('/pusat/toko') }}/' + id;
                form.submit();
            }
        });
    }
</script>
@endsection
