@extends('layouts.admin')

@section('title', 'Dasbor')

@section('content')
    <!-- Breadcrumb & Welcome Headline Header -->
    <div>
        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Dasbor</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
            Selamat datang kembali, {{ auth()->user()->name }}
        </h1>
        <p class="text-xs text-slate-400 mt-0.5">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    <!-- ========================================================================= -->
    <!-- 4 SUMMARY METRIC CARDS -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- CARD 1: TOTAL PENGGUNA -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between">
            <div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                    TOTAL PENGGUNA
                </div>
                <div class="text-2xl font-bold text-slate-900">
                    {{ $totalPengguna }}
                </div>
                <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                    <span class="font-medium text-slate-500">{{ $totalPengguna }}</span> Pengguna Terdaftar
                </div>
            </div>
            <div class="w-11 h-11 rounded-full bg-[#2563eb] text-white flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
        </div>

        <!-- CARD 2: BIRO & MITRA TERVERIFIKASI -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between">
            <div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                    TERVERIFIKASI
                </div>
                <div class="text-2xl font-bold text-slate-900">
                    {{ $totalBiro }}
                </div>
                <div class="text-[11px] text-slate-400 mt-1">
                    <span class="font-medium text-slate-500">{{ $totalBiro }}</span> Total Terverifikasi
                </div>
            </div>
            <div class="w-11 h-11 rounded-full bg-[#0284c7] text-white flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
            </div>
        </div>

        <!-- CARD 3: DESTINASI WISATA -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between">
            <div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                    DESTINASI WISATA
                </div>
                <div class="text-2xl font-bold text-slate-900">
                    {{ $totalDestinasi }}
                </div>
                <div class="text-[11px] text-slate-400 mt-1">
                    <span class="font-medium text-slate-500">{{ $totalDestinasi }}</span> Objek Wisata
                </div>
            </div>
            <div class="w-11 h-11 rounded-full bg-[#d97706] text-white flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
            </div>
        </div>

        <!-- CARD 4: TOTAL PESERTA & MANIFEST -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between">
            <div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                    TOTAL PESERTA & MANIFEST
                </div>
                <div class="text-2xl font-bold text-slate-900">
                    {{ $totalPeserta }}
                </div>
                <div class="text-[11px] text-slate-400 mt-1">
                    <span class="font-medium text-slate-500">{{ $totalManifest }}</span> Manifest Terjadwal
                </div>
            </div>
            <div class="w-11 h-11 rounded-full bg-[#475569] text-white flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
            </div>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 2-COLUMN SECTION: CHART (LEFT) & RECENT ACTIVITIES (RIGHT) -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- LEFT COLUMN: CHART CARD -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">
                        Pendaftaran & Operasional Trip
                    </h3>
                    <p class="text-xs text-slate-400">
                        Tren data perjalanan wisata & pendaftaran peserta
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#223268] text-white text-[11px] font-semibold shadow-xs cursor-pointer">
                        <span>Pendaftaran Trip</span>
                        <svg class="w-3 h-3 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#d97706] text-white text-[11px] font-semibold shadow-xs cursor-pointer">
                        <span>Last Months 12</span>
                        <svg class="w-3 h-3 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="relative w-full h-[270px] pt-4">
                <div class="absolute inset-y-4 left-0 w-8 flex flex-col justify-between text-[10px] text-slate-300 font-medium select-none pointer-events-none">
                    <span>2000</span>
                    <span>1500</span>
                    <span>1000</span>
                    <span>500</span>
                    <span>0</span>
                </div>

                <div class="ml-10 h-full flex flex-col justify-between">
                    <div class="relative h-[220px] w-full">
                        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                            <div class="border-b border-slate-100 w-full"></div>
                            <div class="border-b border-slate-100 w-full"></div>
                            <div class="border-b border-slate-100 w-full"></div>
                            <div class="border-b border-slate-100 w-full"></div>
                            <div class="border-b border-slate-200 w-full"></div>
                        </div>

                        <svg class="absolute inset-0 w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 1100 200">
                            <defs>
                                <linearGradient id="chartBlueGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#2563eb" stop-opacity="0.18"/>
                                    <stop offset="100%" stop-color="#2563eb" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>
                            <path 
                                d="M 0 196 L 100 196 L 200 196 L 300 196 L 400 196 L 500 196 L 600 196 L 700 196 L 800 196 L 900 196 L 1000 196 L 1100 20 L 1100 200 L 0 200 Z" 
                                fill="url(#chartBlueGrad)" 
                            />
                            <path 
                                d="M 0 196 L 100 196 L 200 196 L 300 196 L 400 196 L 500 196 L 600 196 L 700 196 L 800 196 L 900 196 L 1000 196 L 1100 20" 
                                fill="none" 
                                stroke="#2563eb" 
                                stroke-width="3" 
                                stroke-linecap="round" 
                                stroke-linejoin="round"
                            />
                            <circle cx="1100" cy="20" r="4.5" fill="#ffffff" stroke="#2563eb" stroke-width="2.5" />
                        </svg>
                    </div>

                    <div class="flex items-center justify-between text-[10px] font-semibold text-slate-400 uppercase pt-2 select-none">
                        <span>OCT</span>
                        <span>NOV</span>
                        <span>DEC</span>
                        <span>JAN</span>
                        <span>FEB</span>
                        <span>MAR</span>
                        <span>APR</span>
                        <span>MAY</span>
                        <span>JUN</span>
                        <span>JUL</span>
                        <span>AUG</span>
                        <span class="text-blue-600 font-bold">SEP</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: RECENT ACTIVITIES FEED -->
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">
                            Pendaftaran Terbaru
                        </h3>
                        <p class="text-xs text-slate-400">
                            Aktivitas pengguna terbaru
                        </p>
                    </div>
                    <a href="{{ route('users.index') }}" class="text-xs font-semibold text-[#2563eb] hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase tracking-wider pb-2.5 border-b border-slate-100">
                    <span>DETAIL PENGGUNA</span>
                    <span>TANGGAL BERGABUNG</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($recentUsers as $userItem)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full {{ $userItem['avatar_class'] }} font-bold flex items-center justify-center text-[11px] shrink-0">
                                    {{ $userItem['initials'] }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold text-slate-800 truncate">
                                        {{ $userItem['name'] }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 truncate">
                                        {{ $userItem['email'] }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-[11px] font-semibold text-slate-700">
                                    {{ $userItem['date_formatted'] }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $userItem['time_ago'] }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-slate-400">
                            Belum ada data aktivitas pengguna.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- MASTER DATA DIRECTORY QUICK ACCESS CARDS -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <span>📦 Kelompok Data Master Sistem DaraGo</span>
                    <span class="text-[10px] font-semibold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Kelola Data CRUD Lengkap</span>
                </h3>
                <p class="text-xs text-slate-400">
                    Akses cepat ke tabel master data & operasional wisata
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Biro Perjalanan -->
            <a href="{{ route('biro.index') }}" class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-100/60 hover:border-slate-200 transition group block">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-900 group-hover:text-indigo-600 transition flex items-center gap-1.5">
                        🏢 Biro Perjalanan
                    </span>
                    <span class="text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">{{ $masterDataSummary['biro'] }} Biro</span>
                </div>
                <p class="text-xs text-slate-500 mb-3">Pusat biro tur & travel penyelenggara kegiatan pariwisata.</p>
                <div class="text-[11px] text-indigo-600 font-semibold flex items-center gap-1">
                    <span>Kelola Biro Perjalanan</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </div>
            </a>

            <!-- Destinasi Wisata -->
            <a href="{{ route('destinasi.index') }}" class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-100/60 hover:border-slate-200 transition group block">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-900 group-hover:text-amber-600 transition flex items-center gap-1.5">
                        🏔️ Destinasi Wisata
                    </span>
                    <span class="text-[11px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">{{ $masterDataSummary['destinasi'] }} Objek</span>
                </div>
                <p class="text-xs text-slate-500 mb-3">Katalog tempat wisata lengkap dengan titik GPS & aturan lokal.</p>
                <div class="text-[11px] text-amber-600 font-semibold flex items-center gap-1">
                    <span>Kelola Destinasi Wisata</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </div>
            </a>

            <!-- Armada & Crew -->
            <a href="{{ route('armada.index') }}" class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-100/60 hover:border-slate-200 transition group block">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-900 group-hover:text-emerald-600 transition flex items-center gap-1.5">
                        🚌 Armada Bus
                    </span>
                    <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">{{ $masterDataSummary['armada'] }} Bus</span>
                </div>
                <p class="text-xs text-slate-500 mb-3">Manajemen bus pariwisata, PO armada, dan penugasan supir.</p>
                <div class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                    <span>Kelola Armada Bus</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </div>
            </a>
        </div>
    </div>
@endsection
