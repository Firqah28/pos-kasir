<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $globalSettings['store_name'] ?? config('app.name', 'POS Laravel') }}</title>
    @if(!empty($globalSettings['store_logo']))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $globalSettings['store_logo']) }}">
    @endif
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2563eb">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="KIOS POS">
    <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
    <!-- Tailwind CSS (CDN for compatibility with previous codebase) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd',
                            400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                            800: '#1e40af', 900: '#1e3a8a',
                        },
                        accent: {
                            50: '#fef2f2', 100: '#fee2e2', 200: '#fecaca', 300: '#fca5a5',
                            400: '#f87171', 500: '#ef4444', 600: '#dc2626', 700: '#b91c1c',
                            800: '#991b1b', 900: '#7f1d1d',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-in-out',
                        'slide-up': 'slideUp 0.4s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'scale-in': 'scaleIn 0.2s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { transform: 'translateY(20px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } },
                        slideDown: { '0%': { transform: 'translateY(-10px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } },
                        scaleIn: { '0%': { transform: 'scale(0.95)', opacity: '0' }, '100%': { transform: 'scale(1)', opacity: '1' } },
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'soft-lg': '0 10px 40px -10px rgba(0, 0, 0, 0.1), 0 20px 25px -5px rgba(0, 0, 0, 0.05)',
                        'glow': '0 0 20px rgba(37, 99, 235, 0.3)',
                        'glow-green': '0 0 20px rgba(34, 197, 94, 0.3)',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        :root { --sidebar-width: 16rem; --sidebar-mini-width: 4.5rem; }
        .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 3px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Gradient backgrounds */
        .gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .gradient-blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .gradient-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .gradient-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .gradient-sidebar { background: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%); }
        
        /* Card hover effects */
        .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        
        /* Table row animation */
        .table-row-enter { animation: slideUp 0.3s ease-out forwards; }
        
        /* Loading skeleton */
        .skeleton { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite; }
        @keyframes loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        @media (min-width: 768px) {
            #sidebar { width: var(--sidebar-width); }
            .main-content-wrapper { margin-left: var(--sidebar-width); }
            body.sidebar-mini #sidebar { width: var(--sidebar-mini-width); }
            body.sidebar-mini .main-content-wrapper { margin-left: var(--sidebar-mini-width); }
            body.sidebar-mini .sidebar-text { display: none; }
            body.sidebar-mini .sidebar-header-text { display: none; }
            body.sidebar-mini .sidebar-nav-item { justify-content: center; padding-left: 0; padding-right: 0; }
            body.sidebar-mini .sidebar-nav-item svg { margin-right: 0; }
            body.sidebar-mini #sidebar-header { justify-content: center; padding: 0.5rem; }
        }

        @media (max-width: 767px) {
            #sidebar { width: var(--sidebar-width); transform: translateX(-100%); }
            body.sidebar-open #sidebar { transform: translateX(0); display: flex !important; }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background-color: rgba(31, 41, 55, 0.75); z-index: 45; }
            body.sidebar-open .sidebar-overlay { display: block; }
            .main-content-wrapper { margin-left: 0 !important; }
        }

        .responsive-table-wrapper { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100">
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <script>
        (function() {
            const isMini = localStorage.getItem('sidebar-mini') === 'true';
            if (isMini && window.innerWidth >= 768) document.body.classList.add('sidebar-mini');
        })();
    </script>

    <div class="min-h-full flex">
        <!-- Sidebar - Modern Design -->
        <div id="sidebar" class="fixed inset-y-0 left-0 z-50 gradient-sidebar text-white shadow-2xl flex flex-col sidebar-transition overflow-hidden">
            <!-- Sidebar Header -->
            <div id="sidebar-header" class="flex h-20 shrink-0 items-center justify-between border-b border-white/10 bg-white/5 px-4 backdrop-blur-sm">
                <div class="sidebar-header-text flex flex-col justify-center">
                    <div class="flex items-center space-x-3">
                        @if(isset($globalSettings['store_logo']))
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-lg overflow-hidden p-1">
                                <img src="{{ asset('storage/' . $globalSettings['store_logo']) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center shadow-lg">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72l1.189-1.19A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                                </svg>
                            </div>
                        @endif
                        <div>
                            @php
                                $storeName = $globalSettings['store_name'] ?? 'Toko POS Laravel';
                                $words = explode(' ', $storeName);
                                $firstPart = implode(' ', array_slice($words, 0, min(2, count($words))));
                                $secondPart = implode(' ', array_slice($words, min(2, count($words))));
                            @endphp
                            <h1 class="text-lg font-bold tracking-tight leading-tight text-white line-clamp-1">{{ $firstPart }}</h1>
                            @if($secondPart)
                                <span class="text-xs text-blue-200 font-semibold tracking-wide line-clamp-1">{{ $secondPart }}</span>
                            @endif
                        </div>
                    </div>
                    @if(auth()->check())
                    <div class="mt-2 flex items-center space-x-2">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                            <span class="text-xs font-bold text-white">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</span>
                        </div>
                        <span class="text-xs text-blue-100 font-medium">{{ auth()->user()->username }}</span>
                    </div>
                    @endif
                </div>
                <button onclick="toggleSidebar()" class="hidden md:block p-2 rounded-lg hover:bg-white/10 focus:outline-none transition-colors">
                    <svg id="toggleIcon" class="h-5 w-5 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path id="iconExpand" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        <path id="iconCollapse" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
                <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg hover:bg-white/10 focus:outline-none transition-colors">
                    <svg class="h-5 w-5 text-blue-200" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Sidebar Navigation -->
            <div class="flex flex-1 flex-col overflow-y-auto py-4">
                <nav class="flex-1 px-3 space-y-1">
                    <a href="{{ route('dashboard') ?? '#' }}" class="sidebar-nav-item group flex items-center rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} transition-all">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} md:mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                        <span class="sidebar-text font-semibold">Dashboard</span>
                    </a>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('pembelian.index') ?? '#' }}" class="sidebar-nav-item group flex items-center rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('pembelian.index') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} transition-all">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('pembelian.index') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} md:mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="sidebar-text font-semibold">Pembelian</span>
                    </a>
                    @endif

                    <a href="{{ route('kasir.index') ?? '#' }}" class="sidebar-nav-item group flex items-center rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('kasir.index') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} transition-all">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('kasir.index') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} md:mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                        <span class="sidebar-text font-semibold">Kasir</span>
                    </a>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('barang.index') ?? '#' }}" class="sidebar-nav-item group flex items-center rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('barang.index') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} transition-all">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('barang.index') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} md:mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                        <span class="sidebar-text font-semibold">Barang</span>
                    </a>
                    <a href="{{ route('kategori.index') ?? '#' }}" class="sidebar-nav-item group flex items-center rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('kategori.index') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} transition-all">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('kategori.index') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} md:mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
                        <span class="sidebar-text font-semibold">Kategori</span>
                    </a>
                    <a href="{{ route('supplier.index') ?? '#' }}" class="sidebar-nav-item group flex items-center rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('supplier.index') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} transition-all">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('supplier.index') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} md:mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                        <span class="sidebar-text font-semibold">Supplier</span>
                    </a>
                    <a href="{{ route('laporan.index') ?? '#' }}" class="sidebar-nav-item group flex items-center rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('laporan.index') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} transition-all">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('laporan.index') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} md:mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                        <span class="sidebar-text font-semibold">Laporan</span>
                    </a>
                    @endif
                </nav>
            </div>
            
            <!-- Logout Button -->
            <div class="border-t border-white/10 p-4 bg-white/5">
                <form action="{{ route('logout') ?? '#' }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="sidebar-nav-item group flex w-full items-center rounded-xl px-3 py-3 text-sm font-medium text-red-200 hover:bg-red-500/20 hover:text-white transition-all">
                        <svg class="h-5 w-5 shrink-0 text-red-300 group-hover:text-white md:mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                        <span class="sidebar-text font-semibold">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 main-content-wrapper sidebar-transition flex flex-col h-screen overflow-hidden">
            <!-- Topbar for mobile and desktop -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center justify-between gap-x-4 border-b border-gray-200 bg-white/80 backdrop-blur-md px-4 shadow-sm sm:gap-x-6 sm:px-6">
                <!-- Mobile menu button -->
                <button type="button" onclick="toggleSidebar()" class="-m-2.5 p-2.5 text-gray-700 md:hidden border rounded-lg hover:bg-gray-50 focus:outline-none">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                </button>
                
                <div class="flex-1 font-bold text-lg text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 md:hidden">
                    @php
                        $storeName = $globalSettings['store_name'] ?? 'Toko POS Laravel';
                        $words = explode(' ', $storeName);
                        $firstPart = implode(' ', array_slice($words, 0, min(2, count($words))));
                    @endphp
                    {{ $firstPart }}
                </div>
                
                <!-- Spacer for desktop left side -->
                <div class="hidden md:block flex-1"></div>

                <!-- Right side (Profile logo) -->
                @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="flex items-center gap-x-4 lg:gap-x-6">
                    <a href="{{ route('profil.index') }}" class="flex items-center gap-x-2 rounded-full p-1 hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200" title="Edit Profil Toko">
                        @if(isset($globalSettings['store_logo']))
                            <img class="h-8 w-8 rounded-full bg-white object-contain border border-gray-200 p-0.5 shadow-sm" src="{{ asset('storage/' . $globalSettings['store_logo']) }}" alt="Logo">
                        @else
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center border border-blue-200 shadow-sm">
                                <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            </div>
                        @endif
                        <span class="hidden md:flex md:items-center pr-2">
                            <span class="text-sm font-semibold leading-6 text-gray-700" aria-hidden="true">{{ auth()->user()->name }}</span>
                        </span>
                    </a>
                </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto w-full p-6">
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="py-4 px-6 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} <strong class="text-blue-600">{{ $globalSettings['store_name'] ?? 'KIOS PUTRA TUNGGAL' }}</strong>. All rights reserved.
            </footer>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const body = document.body;
            if (window.innerWidth < 768) {
                body.classList.toggle('sidebar-open');
            } else {
                const isMini = body.classList.toggle('sidebar-mini');
                localStorage.setItem('sidebar-mini', isMini);
                updateSidebarIcons(isMini);
            }
        }

        function updateSidebarIcons(isMini) {
            const iconExpand = document.getElementById('iconExpand');
            const iconCollapse = document.getElementById('iconCollapse');
            if (!iconExpand || !iconCollapse) return;

            if (isMini) {
                iconExpand.classList.remove('hidden');
                iconCollapse.classList.add('hidden');
            } else {
                iconExpand.classList.add('hidden');
                iconCollapse.classList.remove('hidden');
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            if (window.innerWidth >= 768) {
                const isMini = localStorage.getItem('sidebar-mini') === 'true';
                updateSidebarIcons(isMini);
            }
        });
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
    </script>
</body>
</html>
