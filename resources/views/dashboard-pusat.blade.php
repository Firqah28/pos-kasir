@extends('layouts.app')

@section('content')
@php
    $periodeLabel = match($period) {
        'bulanan' => 'Bulan Ini',
        'tahunan' => 'Tahun Ini',
        default => 'Hari Ini',
    };
    $periodeRange = match($period) {
        'bulanan' => $date->translatedFormat('F Y'),
        'tahunan' => (string) $date->year,
        default => $date->translatedFormat('d M Y'),
    };
@endphp
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Pusat</h1>
            <p class="text-gray-500">Ringkasan performa seluruh cabang toko — {{ $periodeRange }}</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('pusat.toko') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72l1.189-1.19A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" /></svg>
                Kelola Toko
            </a>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="inline-flex rounded-xl bg-gray-100 p-1">
            @foreach(['harian' => 'Harian', 'bulanan' => 'Bulanan', 'tahunan' => 'Tahunan'] as $key => $label)
                <a href="{{ route('dashboard.pusat', ['period' => $key, 'date' => $date->format('Y-m-d')]) }}"
                   class="rounded-lg px-4 py-2 text-sm font-semibold transition-all {{ $period === $key ? 'bg-white text-blue-600 shadow' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <form method="GET" action="{{ route('dashboard.pusat') }}" class="flex items-center gap-2">
            <input type="hidden" name="period" value="{{ $period }}">
            <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" onchange="this.form.submit()"
                   class="rounded-xl border-0 py-2 px-3 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-500">
        </form>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5 mb-8">
        <div class="card-hover bg-white overflow-hidden rounded-2xl shadow-soft border border-gray-100 relative">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full"></div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72l1.189-1.19A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" /></svg>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Total</span>
                </div>
                <p class="text-sm font-medium text-gray-500 mb-1">Cabang Aktif</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalToko, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-1"></div>
        </div>

        <div class="card-hover bg-white overflow-hidden rounded-2xl shadow-soft border border-gray-100 relative">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-green-500/10 to-transparent rounded-bl-full"></div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">{{ $periodeLabel }}</span>
                </div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Penjualan</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 h-1"></div>
        </div>

        <div class="card-hover bg-white overflow-hidden rounded-2xl shadow-soft border border-gray-100 relative">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-amber-500/10 to-transparent rounded-bl-full"></div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H3V10.5z" /></svg>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">{{ $periodeLabel }}</span>
                </div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Pembelian</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-1"></div>
        </div>

        <div class="card-hover bg-white overflow-hidden rounded-2xl shadow-soft border border-gray-100 relative">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full"></div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>
                    </div>
                    <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">{{ $periodeLabel }}</span>
                </div>
                <p class="text-sm font-medium text-gray-500 mb-1">Keuntungan Bersih</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</h3>
                <p class="mt-1 text-xs font-semibold text-purple-600">{{ number_format($totalKeuntunganPersen, 1, ',', '.') }}% dari penjualan</p>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-1"></div>
        </div>

        <div class="card-hover bg-white overflow-hidden rounded-2xl shadow-soft border border-gray-100 relative">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-fuchsia-500/10 to-transparent rounded-bl-full"></div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-fuchsia-500 to-pink-600 flex items-center justify-center shadow-lg shadow-fuchsia-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xs font-semibold text-fuchsia-600 bg-fuchsia-50 px-2 py-1 rounded-full">{{ $periodeLabel }}</span>
                </div>
                <p class="text-sm font-medium text-gray-500 mb-1">Fee Keuntungan</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalFee, 0, ',', '.') }}</h3>
                <p class="mt-1 text-xs text-gray-400">sesuai persen tiap cabang</p>
            </div>
            <div class="bg-gradient-to-r from-fuchsia-500 to-pink-600 h-1"></div>
        </div>
    </div>

    <!-- Rekap Cabang -->
    <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 card-hover">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Rekap Performa Cabang</h3>
                <p class="text-sm text-gray-500">Ringkasan penjualan setiap cabang — {{ $periodeRange }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
            </div>
        </div>

        @if($stores->isEmpty())
            <div class="text-center py-10">
                <p class="text-gray-500">Belum ada cabang terdaftar.</p>
            </div>
        @else
            <div class="responsive-table-wrapper">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Cabang</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Penjualan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keuntungan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keuntungan %</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fee</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($stores as $store)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">{{ $store->kode_toko ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $store->nama_toko }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">Rp {{ number_format($store->transaksis_sum_total_harga ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-teal-600">Rp {{ number_format($store->keuntungan ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ ($store->keuntungan_persen ?? 0) > 0 ? 'bg-teal-50 text-teal-700' : 'bg-gray-100 text-gray-500' }}">{{ number_format($store->keuntungan_persen ?? 0, 1, ',', '.') }}%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold text-blue-600">Rp {{ number_format($store->fee ?? 0, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400">fee {{ number_format($store->fee_persen_show ?? 5, 0, ',', '.') }}%</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ number_format($store->users_count, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($store->status === 'aktif')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @elseif($store->status === 'menunggu_pembayaran')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Menunggu Fee</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($store->status === 'aktif')
                                    <a href="{{ route('pusat.cabang.masuk', $store->id) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white px-3 py-1.5 text-xs font-semibold transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                                        Lihat Detail
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
