<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f8fafc]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dasbor') - {{ config('app.name', 'DaraGo') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
    <script>
        // Check dark mode preference on load to avoid screen flicker
        if (localStorage.getItem('darago_theme') === 'dark' || (!('darago_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @stack('styles')
</head>
<body class="h-full antialiased text-slate-800 flex overflow-hidden bg-[#f8fafc]" id="app-body">

    <!-- ========================================================================= -->
    <!-- 1. LEFT SIDEBAR NAVIGATION (FLUX MODERN DASHBOARD STYLE) -->
    <!-- ========================================================================= -->
    <aside 
        id="mainSidebar" 
        class="w-64 bg-white border-r border-slate-200/80 flex flex-col shrink-0 h-screen select-none transition-all duration-300 z-30 fixed lg:static inset-y-0 left-0 -translate-x-full lg:translate-x-0"
    >
        <!-- Brand / Logo Header -->
        <div class="h-18 px-5 flex items-center justify-between border-b border-slate-100/90 relative">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                <!-- Flux Glowing Flower / Spark Icon -->
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-violet-600 via-indigo-600 to-sky-400 text-white flex items-center justify-center font-bold shadow-md shadow-indigo-200 group-hover:scale-105 transition-transform duration-200 shrink-0">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="5" r="2.5" />
                        <circle cx="12" cy="19" r="2.5" />
                        <circle cx="5" cy="12" r="2.5" />
                        <circle cx="19" cy="12" r="2.5" />
                        <circle cx="12" cy="12" r="2" />
                    </svg>
                </div>
                <div class="sidebar-text-target">
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-slate-900 tracking-tight text-lg">DaraGo</span>
                        <span class="text-[9px] font-semibold bg-violet-100 text-violet-700 px-1.5 py-0.5 rounded-full">PRO</span>
                    </div>
                    <span class="text-[9px] text-violet-500 font-bold -mt-0.5 block uppercase tracking-[0.18em]">DASHBOARD</span>
                </div>
            </a>

            <!-- Collapse Toggle Icon Button -->
            <button 
                type="button" 
                onclick="toggleSidebarCompact()" 
                id="sidebarCollapseBtn" 
                class="hidden lg:flex w-6 h-6 rounded-full border border-slate-200 bg-white hover:bg-slate-50 items-center justify-center text-slate-400 hover:text-slate-700 shadow-xs transition cursor-pointer" 
                title="Sembunyikan / Luaskan Menu"
            >
                <svg id="collapseIcon" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>

            <!-- Close button for mobile screen -->
            <button 
                type="button" 
                onclick="toggleMobileSidebar()" 
                class="lg:hidden p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links Container -->
        <div class="grow overflow-y-auto px-3.5 py-4 space-y-6" id="sidebarMenuContainer">
            
            <!-- SECTION 1: OVERVIEW -->
            <div class="menu-section">
                <div class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                    <span class="sidebar-text-target">OVERVIEW</span>
                    <svg class="w-3 h-3 text-slate-400 sidebar-text-target" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <!-- Dasbor (Active State styled with Flux purple pill) -->
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-violet-50 text-violet-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                        title="Dasbor"
                    >
                        <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-violet-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1.5" />
                            <rect x="14" y="3" width="7" height="7" rx="1.5" />
                            <rect x="14" y="14" width="7" height="7" rx="1.5" />
                            <rect x="3" y="14" width="7" height="7" rx="1.5" />
                        </svg>
                        <span class="sidebar-text-target">Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- SECTION 2: OPERASIONAL (TRIP & MANIFEST) -->
            <div class="menu-section">
                <div class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                    <span class="sidebar-text-target">OPERASIONAL</span>
                    <svg class="w-3 h-3 text-slate-400 sidebar-text-target" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <!-- Trip Perjalanan (With Badge like Orders in Flux) -->
                    <a 
                        href="{{ route('trip.index') }}" 
                        class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs transition duration-150 {{ request()->routeIs('trip.*') ? 'bg-violet-50 text-violet-700 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                        title="Trip Perjalanan"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('trip.*') ? 'text-violet-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                            <span class="sidebar-text-target">Trip Perjalanan</span>
                        </div>
                        <span class="sidebar-text-target bg-violet-600 text-white font-bold text-[10px] px-2 py-0.5 rounded-full shadow-xs">
                            Active
                        </span>
                    </a>
                </div>
            </div>

            <!-- SECTION 3: DATA MASTER (COMMERCE / REPOSITORIES) -->
            <div class="menu-section">
                <div class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                    <span class="sidebar-text-target">DATA MASTER</span>
                    <svg class="w-3 h-3 text-slate-400 sidebar-text-target" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <!-- Biro Perjalanan -->
                    <a 
                        href="{{ route('biro.index') }}" 
                        class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs transition duration-150 {{ request()->routeIs('biro.*') ? 'bg-violet-50 text-violet-700 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                        title="Biro Perjalanan"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('biro.*') ? 'text-violet-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                            <span class="sidebar-text-target">Biro Perjalanan</span>
                        </div>
                    </a>

                    <!-- Armada Bus -->
                    <a 
                        href="{{ route('armada.index') }}" 
                        class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs transition duration-150 {{ request()->routeIs('armada.*') ? 'bg-violet-50 text-violet-700 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                        title="Armada Bus"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('armada.*') ? 'text-violet-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                            <span class="sidebar-text-target">Armada Bus</span>
                        </div>
                    </a>

                    <!-- Karyawan & Crew -->
                    <a 
                        href="{{ route('karyawan.index') }}" 
                        class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs transition duration-150 {{ request()->routeIs('karyawan.*') ? 'bg-violet-50 text-violet-700 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                        title="Karyawan & Crew"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('karyawan.*') ? 'text-violet-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                            <span class="sidebar-text-target">Karyawan & Crew</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- SECTION 4: DEV TOOLS & USER MANAGEMENT -->
            <div class="menu-section">
                <div class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 flex items-center justify-between">
                    <span class="sidebar-text-target">DEV TOOLS</span>
                    <svg class="w-3 h-3 text-slate-400 sidebar-text-target" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <a 
                        href="{{ route('users.index') }}" 
                        class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs transition duration-150 {{ request()->routeIs('users.*') ? 'bg-violet-50 text-violet-700 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}"
                        title="Manajemen Pengguna"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('users.*') ? 'text-violet-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <span class="sidebar-text-target">Feature Flags & Akun</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        <!-- Sidebar Bottom User Profile Card (Matching Flux AS Avatar & Admin Details) -->
        <div class="p-3.5 border-t border-slate-100 bg-white">
            <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition group">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Blue Avatar Circle like Flux "AS" -->
                    <div class="w-9 h-9 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-xs">
                        @php
                            $nameParts = explode(' ', trim(auth()->user()->name ?? 'Admin'));
                            $initials = count($nameParts) >= 2 
                                ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                                : strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2));
                        @endphp
                        {{ $initials }}
                    </div>
                    <div class="min-w-0 sidebar-text-target">
                        <div class="text-xs font-bold text-slate-800 truncate leading-tight">
                            {{ auth()->user()->name ?? 'User' }}
                        </div>
                        <div class="text-[11px] text-slate-400 font-medium truncate">
                            {{ auth()->user()->role === 'superadmin' ? 'Admin' : ucfirst(auth()->user()->role ?? 'Admin') }}
                        </div>
                    </div>
                </div>

                <!-- Exit / Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="sidebar-text-target">
                    @csrf
                    <button 
                        type="submit" 
                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer" 
                        title="Keluar dari Sistem"
                    >
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile Overlay Backdrop -->
    <div 
        id="sidebarBackdrop" 
        onclick="toggleMobileSidebar()" 
        class="fixed inset-0 bg-slate-900/30 backdrop-blur-xs z-20 lg:hidden hidden transition-opacity duration-300"
    ></div>

    <!-- ========================================================================= -->
    <!-- 2. MAIN CONTENT AREA & TOP NAVBAR -->
    <!-- ========================================================================= -->
    <div class="grow flex flex-col h-screen overflow-hidden min-w-0">

        <!-- Top Header Navigation Bar (Flux White bar with Search & Action Controls) -->
        <header class="h-18 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-8 flex items-center justify-between shrink-0 z-10 gap-4">
            
            <!-- Mobile Menu Toggle & Search Bar -->
            <div class="flex items-center gap-3 grow max-w-xl">
                <button 
                    type="button" 
                    onclick="toggleMobileSidebar()" 
                    class="lg:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition cursor-pointer"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                    </svg>
                </button>

                <!-- Search Input with Keyboard Shortcut Icon (Matching Flux Search anything...) -->
                <div class="relative w-full max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" d="M20 20l-3.5-3.5"/>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        id="globalSearchInput" 
                        onkeyup="filterGlobalQuickSearch(event)"
                        placeholder="Search anything..." 
                        class="w-full pl-9 pr-12 py-2 text-xs bg-slate-50 hover:bg-slate-100/70 focus:bg-white border border-slate-200/70 focus:border-violet-500 rounded-xl text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all shadow-2xs"
                    >
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <kbd class="hidden sm:inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[10px] font-mono text-slate-400 bg-white border border-slate-200 rounded shadow-2xs">
                            ⌘K
                        </kbd>
                    </div>
                </div>
            </div>

            <!-- Right Controls: "+ New Order" CTA, Dark Mode, Notification, Profile -->
            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                
                <!-- "+ New Order" Primary CTA Button (Flux Purple Gradient Button) -->
                <div class="relative" id="quickActionDropdownWrapper">
                    <button 
                        type="button" 
                        onclick="toggleQuickActionMenu()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm shadow-indigo-300/50 hover:shadow-indigo-300 hover:shadow-md transition-all duration-200 cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span class="hidden sm:inline">+ New Order</span>
                        <span class="sm:hidden">+ Baru</span>
                    </button>

                    <!-- Quick Creation Dropdown Menu -->
                    <div 
                        id="quickActionMenu" 
                        class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 animate-fade-in"
                    >
                        <div class="px-3.5 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            Buat Data Baru
                        </div>
                        <a href="{{ route('trip.create') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-violet-50 hover:text-violet-700 transition">
                            <span class="w-6 h-6 rounded-lg bg-violet-100 text-violet-600 flex items-center justify-center text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            </span>
                            <span>Trip Perjalanan Baru</span>
                        </a>
                        <a href="{{ route('biro.create') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-violet-50 hover:text-violet-700 transition">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                            </span>
                            <span>Biro Perjalanan</span>
                        </a>
                    </div>
                </div>

                <!-- Dark/Light Mode Toggle Icon (Moon for light mode, Sun for dark mode) -->
                <button 
                    type="button" 
                    id="themeToggleBtn"
                    onclick="toggleDarkModeSim()" 
                    class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition cursor-pointer" 
                    title="Beralih Tema (Gelap / Terang)"
                >
                    <!-- Moon Icon (Shown in Light Mode) -->
                    <svg id="themeMoonIcon" class="w-4.5 h-4.5 block dark:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                    <!-- Sun Icon (Shown in Dark Mode) -->
                    <svg id="themeSunIcon" class="w-4.5 h-4.5 hidden dark:block text-amber-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                </button>

                <!-- Notifications with Red Dot (Flux Notification Icon) -->
                <div class="relative">
                    <button 
                        type="button" 
                        class="relative p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition cursor-pointer"
                        title="Notifikasi"
                    >
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                    </button>
                </div>

                <!-- User Profile Avatar Pill (AS) -->
                <div class="flex items-center gap-3 pl-2 sm:pl-3 border-l border-slate-200">
                    <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-xs hover:ring-2 hover:ring-violet-400 transition" title="{{ auth()->user()->name ?? 'User' }}">
                        {{ $initials }}
                    </a>
                </div>

            </div>
        </header>

        <!-- Scrollable Main Container -->
        <main class="grow overflow-y-auto px-4 sm:px-8 py-6 space-y-6 relative">

            <!-- Toast / Flash Notifications (Styled Modern Glass Alerts) -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50/90 border border-emerald-200/80 text-emerald-900 text-xs sm:text-sm flex items-center justify-between shadow-xs animate-fade-in backdrop-blur-sm">
                    <div class="flex items-center gap-2.5 font-medium">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50/90 border border-rose-200/80 text-rose-900 text-xs sm:text-sm flex items-center justify-between shadow-xs animate-fade-in backdrop-blur-sm">
                    <div class="flex items-center gap-2.5 font-medium">
                        <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <path stroke-linecap="round" d="M12 8v4m0 4h.01" />
                            </svg>
                        </div>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 p-1 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @yield('content')

            <!-- Floating Feedback Button (Matching Bottom Right of Flux Image) -->
            <div class="fixed bottom-6 right-6 z-40">
                <button 
                    type="button" 
                    onclick="openFeedbackModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-lg shadow-indigo-300 hover:shadow-indigo-400 hover:scale-105 transition-all duration-200 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>
                    <span>Feedback</span>
                </button>
            </div>

        </main>
    </div>

    <!-- Client Script for Sidebar Toggle & Controls -->
    <script>
        let isSidebarCompact = false;

        function toggleSidebarCompact() {
            const sidebar = document.getElementById('mainSidebar');
            const icon = document.getElementById('collapseIcon');
            const textTargets = document.querySelectorAll('.sidebar-text-target');

            isSidebarCompact = !isSidebarCompact;
            if (isSidebarCompact) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                icon.classList.add('rotate-180');
                textTargets.forEach(el => el.classList.add('hidden'));
            } else {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                icon.classList.remove('rotate-180');
                textTargets.forEach(el => el.classList.remove('hidden'));
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        function toggleQuickActionMenu() {
            const menu = document.getElementById('quickActionMenu');
            menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('quickActionDropdownWrapper');
            const menu = document.getElementById('quickActionMenu');
            if (wrapper && !wrapper.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        function filterGlobalQuickSearch(event) {
            if (event.key === 'Enter') {
                const query = event.target.value.toLowerCase().trim();
                if (query.includes('trip') || query.includes('pesanan') || query.includes('order')) {
                    window.location.href = "{{ route('trip.index') }}";
                } else if (query.includes('destinasi') || query.includes('wisata')) {
                    window.location.href = "{{ route('destinasi.index') }}";
                } else if (query.includes('biro') || query.includes('travel')) {
                    window.location.href = "{{ route('biro.index') }}";
                } else if (query.includes('bus') || query.includes('armada')) {
                    window.location.href = "{{ route('armada.index') }}";
                } else if (query.includes('user') || query.includes('pengguna')) {
                    window.location.href = "{{ route('users.index') }}";
                }
            }
        }

        function openFeedbackModal() {
            alert('Terima kasih telah menggunakan sistem DaraGo! Fitur feedback ini aktif dalam sesi Anda.');
        }

        function toggleDarkModeSim() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('darago_theme', isDark ? 'dark' : 'light');
        }
    </script>
    @stack('scripts')
</body>
</html>
