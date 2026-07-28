@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-3 mb-2">
            <a href="{{ route('dashboard') }}" class="p-2 rounded-xl hover:bg-gray-100 transition-colors">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Profil Toko</h1>
                <p class="text-gray-500">Kelola informasi toko dan cetak struk</p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl flex items-center space-x-3" role="alert">
            <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl" role="alert">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="font-medium">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-5">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Pengaturan Profil</h3>
                            <p class="text-blue-100 text-sm">Kelola informasi toko Anda</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Store Name -->
                        <div>
                            <label for="store_name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Toko</label>
                            <input type="text" name="store_name" id="store_name" value="{{ old('store_name', $settings['store_name'] ?? 'Toko POS Laravel') }}" required
                                class="block w-full px-4 py-3 rounded-xl border-0 ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm input-modern" placeholder="Masukkan nama toko">
                            @error('store_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="store_alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <input type="text" name="store_alamat" id="store_alamat" value="{{ old('store_alamat', $settings['store_alamat'] ?? '') }}"
                                class="block w-full px-4 py-3 rounded-xl border-0 ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm input-modern" placeholder="Masukkan alamat toko">
                            @error('store_alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label for="store_telepon" class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon</label>
                            <input type="text" name="store_telepon" id="store_telepon" value="{{ old('store_telepon', $settings['store_telepon'] ?? '') }}"
                                class="block w-full px-4 py-3 rounded-xl border-0 ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm input-modern" placeholder="Masukkan no. telepon">
                            @error('store_telepon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Terima Kasih -->
                        <div>
                            <label for="store_thank_you" class="block text-sm font-semibold text-gray-700 mb-2">Ucapan Terima Kasih (Struk)</label>
                            <input type="text" name="store_thank_you" id="store_thank_you" value="{{ old('store_thank_you', $settings['store_thank_you'] ?? 'Terima Kasih') }}"
                                class="block w-full px-4 py-3 rounded-xl border-0 ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm input-modern" placeholder="Terima Kasih">
                            @error('store_thank_you')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Logo Upload -->
                        <div>
                            <label for="store_logo" class="block text-sm font-semibold text-gray-700 mb-2">Logo Toko</label>
                            <div class="flex items-start space-x-6">
                                @if(isset($settings['store_logo']))
                                    <div class="shrink-0">
                                        <div class="w-32 h-32 rounded-xl border border-gray-200 p-2 bg-gray-50 flex items-center justify-center overflow-hidden">
                                            <img src="{{ asset('storage/' . $settings['store_logo']) }}" alt="Store Logo" class="max-w-full max-h-full object-contain">
                                        </div>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <input type="file" name="store_logo" id="store_logo" accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
                                    <p class="mt-2 text-xs text-gray-500">Format: JPEG, PNG, JPG, GIF, SVG. Maksimal: 2MB.</p>
                                    @error('store_logo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all btn-ripple">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Current Settings Preview -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6">
                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Info Toko</h4>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Nama Toko</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $settings['store_name'] ?? 'Toko POS Laravel' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Alamat</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $settings['store_alamat'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Telepon</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $settings['store_telepon'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Ucapan Terima Kasih</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $settings['store_thank_you'] ?? 'Terima Kasih' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Logo</p>
                        @if(isset($settings['store_logo']))
                            <span class="inline-flex items-center text-xs font-semibold text-green-700 bg-green-50 px-2 py-1 rounded-full">
                                <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Terupload
                            </span>
                        @else
                            <span class="inline-flex items-center text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                Belum ada logo
                            </span>
                        @endif
                    </div>
                </div>

                @if(isset($settings['store_logo']))
                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <form action="{{ route('profil.removeLogo') }}" method="POST" id="deleteLogoForm">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDeleteLogo()" class="w-full rounded-xl bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-100 transition-colors flex items-center justify-center space-x-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                <span>Hapus Logo</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Help Card -->
            <div class="bg-blue-50 rounded-2xl border border-blue-100 p-6">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-blue-900">Tips</h4>
                        <p class="text-xs text-blue-700 mt-1 leading-relaxed">Nama toko, alamat, telepon, dan ucapan terima kasih akan ditampilkan di struk cetak.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDeleteLogo() {
        Swal.fire({
            title: 'Hapus Logo?',
            text: "Logo toko saat ini akan dihapus secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteLogoForm').submit();
            }
        });
    }
</script>
@endsection