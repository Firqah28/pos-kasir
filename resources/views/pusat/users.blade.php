@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Pengguna</h1>
                <p class="text-gray-500">Tambah dan kelola Admin Toko serta Kasir per cabang</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button type="button" onclick="openModal()" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                    Tambah Pengguna
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
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Username</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cabang Toko</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dibuat</th>
                        <th scope="col" class="relative py-4 pl-4 pr-6">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($users as $user)
                    <tr class="table-row-enter hover:bg-gray-50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-bold text-gray-900">{{ $user->username }}</td>
                        <td class="whitespace-nowrap px-3 py-4">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Admin Toko</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Kasir</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ $user->store?->nama_toko ?? '-' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->created_at?->format('d M Y') }}</td>
                        <td class="relative whitespace-nowrap py-4 pl-4 pr-6 text-right text-sm font-medium">
                            <button type="button" onclick='editUser(@json($user))' class="text-blue-600 hover:text-blue-900 font-semibold hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors mr-2">Edit</button>
                            <button type="button" onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->username) }}')" class="text-red-600 hover:text-red-900 font-semibold hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">Belum ada pengguna</p>
                            <p class="text-gray-400 text-xs mt-1">Tambahkan Admin Toko atau Kasir untuk cabang</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="userModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg animate-scale-in">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white" id="modal-title">Data Pengguna</h3>
                            <p class="text-blue-100 text-sm">Masukkan informasi akun</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6">
                    <form id="userForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="POST" id="formMethod">
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                                <input type="text" id="username" name="username" required maxlength="50" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4" placeholder="Username login">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" id="passwordLabel">Password <span class="text-red-500">*</span></label>
                                <input type="password" id="password" name="password" minlength="5" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-4" placeholder="Minimal 5 karakter">
                                <p class="text-xs text-gray-400 mt-1" id="passwordHint">Kosongkan jika tidak ingin mengubah password.</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                                    <select id="role" name="role" required class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-3 bg-white">
                                        <option value="admin">Admin Toko</option>
                                        <option value="kasir">Kasir</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cabang Toko <span class="text-red-500">*</span></label>
                                    <select id="store_id" name="store_id" required class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 px-3 bg-white">
                                        <option value="">-- Pilih Toko --</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->nama_toko }} ({{ $store->kode_toko }})</option>
                                        @endforeach
                                    </select>
                                </div>
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

<form id="deleteUserForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function openModal() {
        document.getElementById('userModal').classList.remove('hidden');
        document.getElementById('userForm').reset();
        document.getElementById('user_id').value = '';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('userForm').action = '{{ route('pusat.users') }}';
        document.getElementById('password').required = true;
        document.getElementById('passwordHint').textContent = 'Minimal 5 karakter.';
        document.getElementById('modal-title').textContent = 'Data Pengguna';
    }

    function closeModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    function editUser(u) {
        openModal();
        document.getElementById('user_id').value = u.id;
        document.getElementById('username').value = u.username;
        document.getElementById('password').value = '';
        document.getElementById('password').required = false;
        document.getElementById('passwordHint').textContent = 'Kosongkan jika tidak ingin mengubah password.';
        document.getElementById('role').value = u.role;
        document.getElementById('store_id').value = u.store_id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('userForm').action = '{{ url('/pusat/users') }}/' + u.id;
        document.getElementById('modal-title').textContent = 'Edit Pengguna';
    }

    function deleteUser(id, username) {
        Swal.fire({
            title: 'Hapus Pengguna?',
            text: `Akun "${username}" akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteUserForm');
                form.action = '{{ url('/pusat/users') }}/' + id;
                form.submit();
            }
        });
    }
</script>
@endsection
