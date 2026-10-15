<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f8fafc]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dasbor') - {{ config('app.name', 'DaraGo') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        /* Custom subtle scrollbars */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full antialiased text-slate-800 flex overflow-hidden bg-[#f8fafc]">

    <!-- ========================================================================= -->
    <!-- 1. LEFT SIDEBAR NAVIGATION -->
    <!-- ========================================================================= -->
    <aside class="w-64 bg-white border-r border-slate-200/90 flex flex-col shrink-0 h-screen select-none transition-all duration-300 z-30">
        
        <!-- Brand / Logo Header -->
        <div class="h-16 px-5 flex items-center justify-between border-b border-slate-100">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-[#223268] text-white flex items-center justify-center font-bold text-base shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                </div>
                <div>
                    <span class="font-bold text-slate-900 tracking-tight text-base flex items-center gap-1">
                        DaraGo
                    </span>
                    <span class="text-[10px] text-slate-400 font-medium -mt-1 block uppercase tracking-wider">Travel Admin</span>
                </div>
            </a>

            <!-- Collapse Icon -->
            <button type="button" class="text-slate-400 hover:text-slate-600 p-1 rounded-md hover:bg-slate-50 transition cursor-pointer" title="Menu">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                </svg>
            </button>
        </div>

        <!-- Search Menu Input -->
        <div class="px-4 py-3">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"/>
                        <path stroke-linecap="round" d="M20 20l-3.5-3.5"/>
                    </svg>
                </span>
                <input 
                    type="text" 
                    id="menuSearchInput" 
                    onkeyup="filterSidebarMenu()" 
                    placeholder="Cari menu..." 
                    class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50/80 hover:bg-slate-100/80 focus:bg-white border border-slate-200/80 rounded-lg text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 focus:border-indigo-500 transition"
                >
            </div>
        </div>

        <!-- Navigation Links Container -->
        <div class="grow overflow-y-auto px-3 py-2 space-y-5" id="sidebarMenuContainer">
            
            <!-- SECTION 1: UTAMA -->
            <div class="menu-section">
                <div class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                    Utama
                </div>
                <div class="space-y-1">
                    <!-- Dasbor -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-[#223268] text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }} text-xs transition duration-150">
                        <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }} shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1.5" />
                            <rect x="14" y="3" width="7" height="7" rx="1.5" />
                            <rect x="14" y="14" width="7" height="7" rx="1.5" />
                            <rect x="3" y="14" width="7" height="7" rx="1.5" />
                        </svg>
                        <span>Dasbor</span>
                    </a>
                </div>
            </div>

            <!-- SECTION 2: USER MANAGEMENT -->
            <div class="menu-section">
                <div class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                    User Management
                </div>
                <div class="space-y-1">
                    <a href="{{ route('users.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request()->routeIs('users.*') ? 'bg-[#223268] text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }} text-xs transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-400' }} shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <span>Manajemen Pengguna</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- SECTION 3: DATA MASTER (CRUD SEEDED GROUPS) -->
            <div class="menu-section">
                <div class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                    <span>Data Master</span>
                    <span class="text-[9px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded">CRUD</span>
                </div>
                <div class="space-y-1">
                    <!-- Biro Perjalanan -->
                    <a href="{{ route('biro.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request()->routeIs('biro.*') ? 'bg-[#223268] text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }} text-xs transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('biro.*') ? 'text-white' : 'text-slate-400' }} shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                            <span>Biro Perjalanan</span>
                        </div>
                    </a>

                    <!-- Destinasi Wisata -->
                    <a href="{{ route('destinasi.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request()->routeIs('destinasi.*') ? 'bg-[#223268] text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }} text-xs transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('destinasi.*') ? 'text-white' : 'text-slate-400' }} shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <span>Destinasi Wisata</span>
                        </div>
                    </a>

                    <!-- Armada Bus -->
                    <a href="{{ route('armada.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request()->routeIs('armada.*') ? 'bg-[#223268] text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }} text-xs transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('armada.*') ? 'text-white' : 'text-slate-400' }} shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                            <span>Armada Bus</span>
                        </div>
                    </a>

                    <!-- Karyawan / Crew -->
                    <a href="{{ route('karyawan.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request()->routeIs('karyawan.*') ? 'bg-[#223268] text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }} text-xs transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('karyawan.*') ? 'text-white' : 'text-slate-400' }} shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                            <span>Karyawan & Crew</span>
                        </div>
                    </a>

                    <!-- Peserta / Klien -->
                    <a href="{{ route('peserta.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request()->routeIs('peserta.*') ? 'bg-[#223268] text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }} text-xs transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('peserta.*') ? 'text-white' : 'text-slate-400' }} shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <span>Peserta & Klien</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- SECTION 4: SISTEM OPERASIONAL / TRIP -->
            <div class="menu-section">
                <div class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                    Sistem Operasional
                </div>
                <div class="space-y-1">
                    <a href="{{ route('trip.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request()->routeIs('trip.*') ? 'bg-[#223268] text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }} text-xs transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('trip.*') ? 'text-white' : 'text-slate-400' }} shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                            <span>Trip Perjalanan</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        <!-- Sidebar Bottom Logout -->
        <div class="p-3 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ========================================================================= -->
    <!-- 2. MAIN CONTENT AREA WITH TOP NAVBAR -->
    <!-- ========================================================================= -->
    <div class="grow flex flex-col h-screen overflow-hidden">

        <!-- Top Header Navigation Bar -->
        <header class="h-16 bg-white border-b border-slate-200/80 px-6 sm:px-8 flex items-center justify-between shrink-0 z-20">
            
            <!-- Left: Breadcrumb / Back button -->
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" class="hover:text-slate-800 transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>

            <!-- Right Controls: Language, Search, Notification, Profile -->
            <div class="flex items-center gap-5">
                
                <button type="button" class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition cursor-pointer">
                    <span class="text-sm">🇮🇩</span>
                    <span>ID</span>
                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div class="relative">
                    <button type="button" class="relative text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-50 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>
                </div>

                <!-- User Profile Pill -->
                <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
                            {{ auth()->user()->role === 'superadmin' ? 'SUPER ADMIN' : strtoupper(auth()->user()->role) }}
                        </div>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-[#d97706] text-white font-bold flex items-center justify-center text-sm shadow-xs ring-2 ring-amber-100">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>

            </div>
        </header>

        <!-- Scrollable Main Container -->
        <main class="grow overflow-y-auto px-6 sm:px-8 py-6 space-y-6">

            <!-- Toast / Flash Notifications -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs sm:text-sm flex items-center justify-between shadow-xs animate-fade-in">
                    <div class="flex items-center gap-2.5 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-red-50 border border-red-200/80 text-red-800 text-xs sm:text-sm flex items-center justify-between shadow-xs animate-fade-in">
                    <div class="flex items-center gap-2.5 font-medium">
                        <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <path stroke-linecap="round" d="M12 8v4m0 4h.01" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')

        </main>

    </div>

    <!-- Client Script for Search Filter -->
    <script>
        function filterSidebarMenu() {
            const input = document.getElementById('menuSearchInput').value.toLowerCase();
            const sections = document.querySelectorAll('#sidebarMenuContainer .menu-section');

            sections.forEach(section => {
                const links = section.querySelectorAll('a');
                let hasVisibleLink = false;

                links.forEach(link => {
                    const text = link.textContent.toLowerCase();
                    if (text.includes(input)) {
                        link.style.display = 'flex';
                        hasVisibleLink = true;
                    } else {
                        link.style.display = 'none';
                    }
                });

                if (hasVisibleLink || input === '') {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
