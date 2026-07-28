@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Overview</h1>
        <p class="text-gray-500">Ringkasan aktivitas dan performa toko Anda hari ini</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Card 1: Total Penjualan Hari Ini -->
        <div class="card-hover bg-white overflow-hidden rounded-2xl shadow-soft border border-gray-100 relative">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full"></div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Hari Ini</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Penjualan</p>
                    <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-1"></div>
        </div>

        <!-- Card 2: HPP / Modal Terjual -->
        <div class="card-hover bg-white overflow-hidden rounded-2xl shadow-soft border border-gray-100 relative">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-amber-500/10 to-transparent rounded-bl-full"></div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H3V10.5z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Hari Ini</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">HPP (Modal Terjual)</p>
                    <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalModalTerjual, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-1"></div>
        </div>

        <!-- Card 3: Keuntungan Bersih -->
        <div class="card-hover bg-white overflow-hidden rounded-2xl shadow-soft border border-gray-100 relative">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-green-500/10 to-transparent rounded-bl-full"></div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">Hari Ini</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Keuntungan Bersih</p>
                    <h3 class="text-2xl font-bold text-green-600">Rp {{ number_format($keuntunganBersih, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 h-1"></div>
        </div>

        <!-- Card 4: Pengeluaran Supplier -->
        <div class="card-hover bg-white overflow-hidden rounded-2xl shadow-soft border border-gray-100 relative">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-red-500/10 to-transparent rounded-bl-full"></div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">Hari Ini</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Pengeluaran Supplier</p>
                    <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($pengeluaranSupplier, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="bg-gradient-to-r from-red-500 to-red-600 h-1"></div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-soft p-6 border border-gray-100 card-hover">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Grafik Aktivitas</h3>
                    <p class="text-sm text-gray-500">24 Jam Terakhir</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                        <span class="text-xs text-gray-500 font-medium">Pendapatan</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <span class="text-xs text-gray-500 font-medium">Transaksi</span>
                    </div>
                </div>
            </div>
            <div id="salesChart" style="height: 320px; width: 100%;"></div>
        </div>

        <!-- Top Items Chart -->
        <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 card-hover flex flex-col">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Produk Terlaris</h3>
            <div id="topItemsChart" style="height: 200px; width: 100%;"></div>
            <div id="chartLegend" class="mt-4 w-full space-y-2"></div>
        </div>
    </div>

    <!-- Top Selling Items List -->
    <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 card-hover">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Detail Produk Terlaris</h3>
                <p class="text-sm text-gray-500">Produk dengan penjualan tertinggi hari ini</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172V9.375a4.5 4.5 0 10-9 0v2.907c0 1.09-.344 2.1-1.003 2.927" />
                </svg>
            </div>
        </div>
        <div class="overflow-y-auto max-h-[350px] pr-2 space-y-3" id="topItemsList">
            <!-- Items will be rendered here -->
        </div>
    </div>

    @if(auth()->check() && auth()->user()->role === 'admin')
    <!-- Profil Toko Button -->
    <div class="mt-8">
        <a href="{{ route('profil.index') }}" class="inline-flex items-center space-x-2 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            <span>Pengaturan Profil Toko</span>
        </a>
    </div>
    @endif
</div>

<script>
    let salesChartInstance = null;

    async function fetchDashboardData() {
        try {
            const today = new Date().toLocaleDateString('en-CA');
            const [resBarang, resPerJam] = await Promise.all([
                fetch(`/api/laporan/barang?startDate=${today}&endDate=${today}`),
                fetch(`/api/laporan/perjam?date=${today}`)
            ]);

            const dataBarang = await resBarang.json();
            const dataPerJam = await resPerJam.json();

            renderChart(dataPerJam);
            renderTopItems(dataBarang);
            renderTopItemsChart(dataBarang);

        } catch(e) {
            console.error("Dashboard fetch error:", e);
        }
    }

    function renderChart(data) {
        if(!data) return;

        const salesDataPoints = [];
        const volumeDataPoints = [];
        const today = new Date().toLocaleDateString('en-CA');

        for(let i = 0; i < 24; i++) {
            const hourStr = String(i).padStart(2, '0');
            const label = `${hourStr}:00`;
            const fullHourStr = `${today} ${hourStr}:00:00`;

            const found = data.find(x => x.jam === fullHourStr);
            salesDataPoints.push({ label, y: found ? Number(found.total_penjualan) : 0 });
            volumeDataPoints.push({ label, y: found ? Number(found.total_transaksi) : 0 });
        }

        if (salesChartInstance) {
            salesChartInstance.destroy();
        }

        salesChartInstance = new CanvasJS.Chart("salesChart", {
            animationEnabled: true,
            theme: "light2",
            title: { text: "" },
            toolTip: { shared: true },
            legend: { cursor: "pointer", itemclick: function (e) {
                if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                } else {
                    e.dataSeries.visible = true;
                }
                e.chart.render();
            }},
            axisX: {
                labelFontColor: "#9ca3af",
                labelFontSize: 11,
                lineThickness: 0,
                tickThickness: 0,
                labelAngle: -45
            },
            axisY: {
                title: "Pendapatan",
                titleFontColor: "#3b82f6",
                lineColor: "#3b82f6",
                labelFontColor: "#3b82f6",
                tickColor: "#3b82f6",
                includeZero: true,
                prefix: "Rp ",
                labelFontSize: 11,
                gridDashType: "dash",
                gridColor: "#f3f4f6",
                lineThickness: 0
            },
            axisY2: {
                title: "Transaksi",
                titleFontColor: "#10b981",
                lineColor: "#10b981",
                labelFontColor: "#10b981",
                tickColor: "#10b981",
                includeZero: true,
                labelFontSize: 11,
                lineThickness: 0
            },
            data: [{
                type: "splineArea",
                name: "Total Pendapatan",
                showInLegend: true,
                axisYType: "primary",
                yValueFormatString: "Rp #,##0",
                color: "#3b82f6",
                fillOpacity: 0.2,
                lineThickness: 3,
                dataPoints: salesDataPoints
            }, {
                type: "spline",
                name: "Total Transaksi",
                axisYType: "secondary",
                showInLegend: true,
                yValueFormatString: "#,##0 Transaksi",
                color: "#10b981",
                markerSize: 8,
                markerType: "circle",
                lineThickness: 3,
                dataPoints: volumeDataPoints
            }]
        });
        salesChartInstance.render();
    }

    let topItemsChartInstance = null;
    function renderTopItemsChart(data) {
        if(!data || data.length === 0) return;

        const top5 = data.slice(0, 5);
        const dataPoints = top5.map(item => ({
            label: item.nama_barang,
            y: Number(item.total_qty)
        }));

        if (topItemsChartInstance) {
            topItemsChartInstance.destroy();
        }

        topItemsChartInstance = new CanvasJS.Chart("topItemsChart", {
            animationEnabled: true,
            theme: "light2",
            title: { text: "" },
            legend: { verticalAlign: "bottom", horizontalAlign: "center", fontSize: 10 },
            data: [{
                type: "doughnut",
                showInLegend: true,
                indexLabel: "{label}",
                indexLabelFontSize: 9,
                indexLabelFontColor: "#6b7280",
                innerRadius: "65%",
                yValueFormatString: "#,##0 Unit",
                dataPoints: dataPoints,
                colors: ["#3b82f6", "#10b981", "#f59e0b", "#ef4444", "#8b5cf6"]
            }]
        });
        topItemsChartInstance.render();
    }

    function renderTopItems(data) {
        const list = document.getElementById('topItemsList');
        list.innerHTML = '';

        if(!data || data.length === 0) {
            list.innerHTML = '<div class="text-center py-8"><div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center"><svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg></div><p class="text-gray-500 text-sm">Belum ada data penjualan</p></div>';
            return;
        }

        const top5 = data.slice(0, 5);
        const medals = [
            { bg: 'bg-gradient-to-br from-yellow-400 to-yellow-500', text: 'text-yellow-700', icon: '🥇' },
            { bg: 'bg-gradient-to-br from-gray-300 to-gray-400', text: 'text-gray-700', icon: '🥈' },
            { bg: 'bg-gradient-to-br from-amber-600 to-amber-700', text: 'text-amber-700', icon: '🥉' },
            { bg: 'bg-gradient-to-br from-blue-400 to-blue-500', text: 'text-blue-700', icon: '🏅' },
            { bg: 'bg-gradient-to-br from-purple-400 to-purple-500', text: 'text-purple-700', icon: '🏅' }
        ];

        top5.forEach((item, index) => {
            const medal = medals[index] || medals[4];
            list.innerHTML += `
                <div class="flex items-center justify-between p-4 rounded-xl ${index === 0 ? 'bg-gradient-to-r from-yellow-50 to-amber-50 border-2 border-yellow-200' : 'bg-gray-50 hover:bg-gray-100 border border-gray-100'} transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full ${medal.bg} flex items-center justify-center text-lg shadow-md">
                            ${medal.icon}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">${item.nama_barang}</p>
                            <p class="text-xs text-gray-500">${item.total_qty} Unit Terjual</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-green-600">Rp ${Number(item.total_pendapatan).toLocaleString('id-ID')}</div>
                    </div>
                </div>
            `;
        });
    }

    fetchDashboardData();
</script>
@endsection
