@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Tagihan Fee Cabang</h1>
                <p class="text-gray-500">Atur status fee cabang secara manual (Aktif / Menunggu Pembayaran / Nonaktif)</p>
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

    <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4">
        <p class="text-sm text-blue-800">
            <span class="font-bold">Periode aktif: {{ $periode }}</span>
            — Cabang berstatus <b>Menunggu Pembayaran</b> atau <b>Nonaktif</b> tidak dapat masuk / melakukan transaksi. Status diatur manual oleh admin super.
        </p>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Toko</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fee (%)</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Penjualan Bulan Ini</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fee Bulan Ini</th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th scope="col" class="relative py-4 pl-4 pr-6">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($stores as $item)
                    @php $store = $item['store']; @endphp
                    <tr class="table-row-enter hover:bg-gray-50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-mono font-semibold text-gray-500">{{ $store->kode_toko ?? '-' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-gray-900">{{ $store->nama_toko }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-gray-700">{{ number_format($store->fee_persen, 0, ',', '.') }}%</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-gray-700">Rp {{ number_format($item['penjualan'], 0, ',', '.') }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-blue-600">Rp {{ number_format($item['fee_amount'], 0, ',', '.') }}</td>
                        <td class="whitespace-nowrap px-3 py-4">
                            @if($item['bill_status'] === 'paid')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Lunas</span>
                                @if($item['paid_at'])
                                    <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($item['paid_at'])->format('d M Y H:i') }}</p>
                                    @if($item['confirmed_by'])
                                        <p class="text-xs text-gray-400">oleh {{ $item['confirmed_by'] }}</p>
                                    @endif
                                @endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Belum Bayar</span>
                            @endif
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-4 pr-6 text-right text-sm font-medium">
                            <form method="POST" action="{{ route('pusat.fee.status', $store->id) }}" class="inline-flex items-center gap-2">
                                @csrf
                                <select name="status" class="rounded-lg border-0 py-1.5 pl-2 pr-8 text-xs font-semibold ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-500 {{ $store->status === 'aktif' ? 'text-green-700 bg-green-50' : ($store->status === 'nonaktif' ? 'text-red-700 bg-red-50' : 'text-amber-700 bg-amber-50') }}">
                                    <option value="aktif" {{ $store->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="menunggu_pembayaran" {{ $store->status === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                    <option value="nonaktif" {{ $store->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                <button type="submit" class="text-blue-600 hover:text-blue-900 font-semibold hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">Simpan</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">Belum ada cabang toko</p>
                            <p class="text-gray-400 text-xs mt-1">Tambahkan cabang melalui halaman Data Cabang Toko</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
