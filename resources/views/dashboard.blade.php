@extends('layouts.admin')

@section('title', 'Dasbor Operasional DaraGo')

@section('content')
<div class="space-y-6">

    <!-- ========================================================================= -->
    <!-- 1. HERO GRADIENT BANNER WITH 4 GLASS STATS CARDS (FLUX DESIGN - DARAGO DATA) -->
    <!-- ========================================================================= -->
    <div class="rounded-3xl p-6 sm:p-8 bg-gradient-to-r from-violet-600 via-indigo-600 to-sky-500 shadow-xl shadow-indigo-500/10 text-white relative overflow-hidden">
        
        <!-- Subtle Glow Background Decorative Shapes -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-sky-400/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 mb-6">
            <div class="flex items-center gap-2 text-white/80 text-xs font-medium mb-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                <span>•</span>
                <span>Sistem Operasional Aktif</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                Selamat datang kembali, {{ auth()->user()->name ?? 'Admin' }}
            </h1>
            <p class="text-sm text-white/80 mt-1 font-medium">
                Berikut ringkasan operasional perjalanan wisata & data master DaraGo hari ini.
            </p>
        </div>

        <!-- 4 Glassmorphic Stats Cards Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 relative z-10">
            
            <!-- STAT 1: TOTAL PENGGUNA -->
            <div class="glass-card glass-card-hover rounded-2xl p-5 transition-all duration-200">
                <div class="flex items-center gap-2 text-white/80 text-xs font-semibold mb-2">
                    <svg class="w-4 h-4 text-white/90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span>TOTAL PENGGUNA</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    {{ number_format($totalPengguna) }}
                </div>
                <div class="text-[11px] text-emerald-300 font-semibold mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                    <span>{{ $totalPengguna }} Pengguna Terdaftar</span>
                </div>
            </div>

            <!-- STAT 2: BIRO & MITRA TERVERIFIKASI -->
            <div class="glass-card glass-card-hover rounded-2xl p-5 transition-all duration-200">
                <div class="flex items-center gap-2 text-white/80 text-xs font-semibold mb-2">
                    <svg class="w-4 h-4 text-white/90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    <span>BIRO TERVERIFIKASI</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    {{ number_format($totalBiro) }}
                </div>
                <div class="text-[11px] text-emerald-300 font-semibold mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                    <span>{{ $totalBiro }} Biro Wisata Aktif</span>
                </div>
            </div>

            <!-- STAT 3: ARMADA PO BUS -->
            <div class="glass-card glass-card-hover rounded-2xl p-5 transition-all duration-200">
                <div class="flex items-center gap-2 text-white/80 text-xs font-semibold mb-2">
                    <svg class="w-4 h-4 text-white/90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                    <span>ARMADA PO BUS</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    {{ number_format($totalArmada) }}
                </div>
                <div class="text-[11px] text-emerald-300 font-semibold mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                    <span>{{ $totalArmada }} PO Bus & Kendaraan</span>
                </div>
            </div>

            <!-- STAT 4: TOTAL KARYAWAN & CREW -->
            <div class="glass-card glass-card-hover rounded-2xl p-5 transition-all duration-200">
                <div class="flex items-center gap-2 text-white/80 text-xs font-semibold mb-2">
                    <svg class="w-4 h-4 text-white/90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    <span>KARYAWAN & CREW</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    {{ number_format($totalKaryawan) }}
                </div>
                <div class="text-[11px] text-emerald-300 font-semibold mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                    <span>{{ $totalKaryawan }} Personel Tour Leader & Crew</span>
                </div>
            </div>

        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. MAIN 2-COLUMN SECTION (FLUX LAYOUT - DARAGO DATA) -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- LEFT COLUMN: CHART & MASTER DATA MODULES (8 COLS) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Card: Tren Pertumbuhan & Operasional Trip -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-sm" id="tripGrowthChartCard">
                
                <!-- Header with Period / View Switcher -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                            Pertumbuhan & Operasional Trip
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5 font-medium" id="chartSubtitle">
                            Tren operasional dan pelaksanaan perjalanan wisata bulanan
                        </p>
                    </div>

                    <!-- Switcher Toggle Pill -->
                    <div class="inline-flex p-1 bg-slate-100 rounded-xl self-start sm:self-auto border border-slate-200/50">
                        <button type="button" 
                                id="btnMonthlyChart"
                                onclick="switchChartMode('monthly')"
                                class="px-3.5 py-1 text-xs font-bold text-slate-800 bg-white rounded-lg shadow-2xs transition">
                            Bulanan
                        </button>
                        <button type="button" 
                                id="btnYearlyChart"
                                onclick="switchChartMode('yearly')"
                                class="px-3.5 py-1 text-xs font-semibold text-slate-500 hover:text-slate-800 rounded-lg transition">
                            Tahunan
                        </button>
                    </div>
                </div>

                <!-- Dynamic Vector SVG Line Chart with Gradient and Tooltip -->
                <div class="relative w-full h-[260px]">
                    
                    <!-- Dynamic Y-Axis Ticks -->
                    <div id="chartYAxis" class="absolute inset-y-0 left-0 w-10 flex flex-col justify-between text-[11px] text-slate-400 font-medium select-none pointer-events-none pb-8 text-right pr-2">
                        <span>50</span>
                        <span>38</span>
                        <span>25</span>
                        <span>12</span>
                        <span>0</span>
                    </div>

                    <!-- Chart Grid and Paths -->
                    <div class="ml-12 h-full flex flex-col justify-between">
                        
                        <!-- Canvas Container -->
                        <div class="relative h-[220px] w-full" id="chartCanvasContainer">
                            
                            <!-- Subtle Horizontal Grid Lines -->
                            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                                <div class="border-b border-slate-100 w-full"></div>
                                <div class="border-b border-slate-100 w-full"></div>
                                <div class="border-b border-slate-100 w-full"></div>
                                <div class="border-b border-slate-100 w-full"></div>
                                <div class="border-b border-slate-200/90 w-full"></div>
                            </div>

                            <!-- SVG Chart Path -->
                            <svg class="absolute inset-0 w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 1000 200">
                                <defs>
                                    <linearGradient id="daragoPurpleGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#7c3aed" stop-opacity="0.25"/>
                                        <stop offset="100%" stop-color="#7c3aed" stop-opacity="0.0"/>
                                    </linearGradient>
                                </defs>
                                
                                <!-- Vertical indicator line -->
                                <line id="chartIndicatorLine" x1="500" y1="20" x2="500" y2="200" stroke="#e2e8f0" stroke-width="1.5" stroke-dasharray="4 4" class="transition-all duration-300" />

                                <!-- Area Fill under the line -->
                                <path 
                                    id="chartAreaFill"
                                    d="" 
                                    fill="url(#daragoPurpleGrad)" 
                                    class="transition-all duration-500"
                                />

                                <!-- Smooth Violet Line Curve -->
                                <path 
                                    id="chartLineCurve"
                                    d="" 
                                    fill="none" 
                                    stroke="#7c3aed" 
                                    stroke-width="3" 
                                    stroke-linecap="round" 
                                    class="transition-all duration-500"
                                />

                                <!-- Interactive Data Point Dots Container -->
                                <g id="chartPointsGroup"></g>

                                <!-- Active Pinned Data Point Indicator -->
                                <circle id="chartActiveDot" cx="500" cy="100" r="5" fill="#ffffff" stroke="#7c3aed" stroke-width="3" class="transition-all duration-300" />
                            </svg>

                            <!-- Tooltip Box Pinned with Active Period Trip Data -->
                            <div id="chartTooltip" class="absolute -translate-x-1/2 -translate-y-full mb-3 bg-white rounded-xl shadow-lg border border-slate-100 px-3 py-1.5 pointer-events-none text-center transition-all duration-300 z-10 whitespace-nowrap">
                                <div id="chartTooltipLabel" class="text-[10px] text-slate-400 font-medium">Bulan Ini</div>
                                <div id="chartTooltipValue" class="text-xs font-bold text-violet-700">0 Trip Aktif</div>
                            </div>
                        </div>

                        <!-- Dynamic X-Axis Labels -->
                        <div id="chartXAxis" class="flex items-center justify-between text-xs font-medium text-slate-400 pt-2 select-none">
                            <!-- Populated dynamically via JS -->
                        </div>

                    </div>
                </div>

            </div>

            <!-- Card: Kelompok Data Master DaraGo (Recent Deployments Position) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                            Kelompok Data Master DaraGo
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5 font-medium">
                            Akses cepat ke katalog master data & operasional wisata
                        </p>
                    </div>
                    <a href="{{ route('trip.index') }}" class="text-xs font-semibold text-violet-600 hover:text-violet-700 flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <span>↗</span>
                    </a>
                </div>

                <!-- Quick Master Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Biro Perjalanan -->
                    <a href="{{ route('biro.index') }}" class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-violet-50/40 hover:border-violet-200 transition-all duration-200 group block">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-900 group-hover:text-violet-700 transition">
                                Biro Perjalanan
                            </span>
                            <span class="text-[10px] font-bold text-violet-600 bg-violet-100 px-2 py-0.5 rounded-full">
                                {{ $masterDataSummary['biro'] ?? 0 }} Biro
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mb-3 font-normal">Pusat biro tur & travel pariwisata.</p>
                        <div class="text-[11px] text-violet-600 font-semibold flex items-center gap-1">
                            <span>Kelola Biro</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </div>
                    </a>

                    <!-- Karyawan & Crew -->
                    <a href="{{ route('karyawan.index') }}" class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-violet-50/40 hover:border-violet-200 transition-all duration-200 group block">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-900 group-hover:text-violet-700 transition">
                                Karyawan & Crew
                            </span>
                            <span class="text-[10px] font-bold text-sky-600 bg-sky-100 px-2 py-0.5 rounded-full">
                                {{ $masterDataSummary['karyawan'] ?? 0 }} Personel
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mb-3 font-normal">Manajemen Tour Leader & kru operasional.</p>
                        <div class="text-[11px] text-sky-600 font-semibold flex items-center gap-1">
                            <span>Kelola Karyawan</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </div>
                    </a>

                    <!-- Armada Bus -->
                    <a href="{{ route('armada.index') }}" class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-violet-50/40 hover:border-violet-200 transition-all duration-200 group block">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-900 group-hover:text-violet-700 transition">
                                Armada Bus
                            </span>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full">
                                {{ $masterDataSummary['armada'] ?? 0 }} PO Bus
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mb-3 font-normal">Manajemen PO bus, armada & supir.</p>
                        <div class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                            <span>Kelola Armada</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: STATUS TRIP, RECENT USERS & FEATURE UTILIZATION (4 COLS) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Card: Status Operasional Trip (Sprint 24 Position with Real Trip Data) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 tracking-tight">
                            Status Operasional Trip
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5 font-medium">
                            {{ $totalTrip }} Total Trip Perjalanan
                        </p>
                    </div>
                    <a href="{{ route('trip.index') }}" class="text-xs font-semibold text-violet-600 hover:text-violet-700 flex items-center gap-1">
                        <span>Kelola Trip</span>
                        <span>↗</span>
                    </a>
                </div>

                @php
                    $sumTrips = $totalTrip > 0 ? $totalTrip : 1;
                    $compPct = round(($tripCompleted / $sumTrips) * 100);
                    $ongPct = round(($tripOngoing / $sumTrips) * 100);
                    $upPct = 100 - $compPct - $ongPct;
                    if ($totalTrip === 0) {
                        $compPct = 60; $ongPct = 25; $upPct = 15;
                    }
                @endphp

                <!-- Multi-segment Progress Bar: Selesai (Violet), Berjalan (Cyan), Mendatang (Amber) -->
                <div class="w-full bg-slate-100 rounded-full h-2.5 flex overflow-hidden my-4">
                    <div class="bg-violet-600 h-full" style="width: {{ $compPct }}%" title="Selesai ({{ $tripCompleted }})"></div>
                    <div class="bg-sky-500 h-full" style="width: {{ $ongPct }}%" title="Berjalan ({{ $tripOngoing }})"></div>
                    <div class="bg-amber-500 h-full" style="width: {{ $upPct }}%" title="Mendatang ({{ $tripUpcoming }})"></div>
                </div>

                <!-- Legend Indicator Row -->
                <div class="flex items-center justify-between text-[11px] font-semibold text-slate-500 pt-1">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-violet-600"></span>
                        <span>Selesai ({{ $tripCompleted }})</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                        <span>Berjalan ({{ $tripOngoing }})</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>Mendatang ({{ $tripUpcoming }})</span>
                    </div>
                </div>
            </div>

            <!-- Card: Aktivitas Pengguna Terbaru (Team Activity Position with Real Users) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 tracking-tight">
                            Aktivitas Pengguna
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5 font-medium">
                            Pengguna & akun yang baru bergabung
                        </p>
                    </div>
                    <a href="{{ route('users.index') }}" class="text-xs font-semibold text-violet-600 hover:text-violet-700 flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <span>↗</span>
                    </a>
                </div>

                <!-- Activity Feed Items from Real Users -->
                <div class="space-y-4">
                    @forelse($recentUsers as $userItem)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full {{ $userItem['avatar_class'] }} font-bold text-[11px] flex items-center justify-center shrink-0">
                                    {{ $userItem['initials'] }}
                                </div>
                                <div class="truncate">
                                    <div class="font-bold text-slate-900 truncate">{{ $userItem['name'] }}</div>
                                    <div class="text-slate-400 text-[11px] font-normal truncate">{{ $userItem['role_label'] }}</div>
                                </div>
                            </div>
                            <span class="text-[11px] text-slate-400 font-medium shrink-0 ml-2">
                                {{ $userItem['time_ago'] }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4 text-xs text-slate-400">
                            Belum ada aktivitas pengguna.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Card: Utilisasi Fitur Sistem DaraGo (Feature Adoption Position) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-sm">
                <div class="mb-4">
                    <h2 class="text-base font-bold text-slate-900 tracking-tight">
                        Utilisasi Sistem DaraGo
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium">
                        Kelengkapan komponen operasional wisata
                    </p>
                </div>

                <div class="space-y-3.5 pt-1">
                    @php
                        $karyawanPct = $totalKaryawan > 0 ? min(100, round(($totalKaryawan / max(1, $totalKaryawan)) * 100)) : 0;
                        $armadaPct = $totalArmada > 0 ? min(100, round(($totalArmada / max(1, $totalArmada)) * 100)) : 0;
                        $tripPct = $totalTrip > 0 ? min(100, round((($tripOngoing + $tripCompleted) / max(1, $totalTrip)) * 100)) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700">Karyawan & Tour Leader</span>
                            <span class="text-violet-600 font-bold">{{ $totalKaryawan }} Tersedia</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-violet-600 h-full rounded-full transition-all duration-500" style="width: {{ $totalKaryawan > 0 ? '100%' : '0%' }}"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700">Armada PO Bus</span>
                            <span class="text-sky-500 font-bold">{{ $totalArmada }} Terdaftar</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-sky-500 h-full rounded-full transition-all duration-500" style="width: {{ $totalArmada > 0 ? '100%' : '0%' }}"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700">Trip Operasional</span>
                            <span class="text-emerald-500 font-bold">{{ $totalTrip }} Terjadwal</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $totalTrip > 0 ? '100%' : '0%' }}"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
    // Injected Dynamic Data from Database / DashboardController
    const chartData = @json($chartData);
    let currentMode = 'monthly'; // 'monthly' | 'yearly'
    let selectedIndex = null;

    /**
     * Compute smooth cubic bezier SVG path string from points array
     */
    function getBezierCurvePath(points) {
        if (!points || points.length === 0) return '';
        if (points.length === 1) return `M ${points[0].x} ${points[0].y}`;

        let d = `M ${points[0].x} ${points[0].y}`;
        for (let i = 0; i < points.length - 1; i++) {
            const p0 = i > 0 ? points[i - 1] : points[i];
            const p1 = points[i];
            const p2 = points[i + 1];
            const p3 = i != points.length - 2 ? points[i + 2] : p2;

            // Tension smoothing factor
            const cp1x = p1.x + (p2.x - p0.x) / 6;
            const cp1y = p1.y + (p2.y - p0.y) / 6;
            const cp2x = p2.x - (p3.x - p1.x) / 6;
            const cp2y = p2.y - (p3.y - p1.y) / 6;

            d += ` C ${cp1x.toFixed(1)} ${cp1y.toFixed(1)}, ${cp2x.toFixed(1)} ${cp2y.toFixed(1)}, ${p2.x.toFixed(1)} ${p2.y.toFixed(1)}`;
        }
        return d;
    }

    /**
     * Render the chart dynamically for the given mode ('monthly' or 'yearly')
     */
    function renderDynamicChart(mode, activeIdx = null) {
        currentMode = mode;
        const data = chartData[mode];
        if (!data) return;

        const labels = data.labels;
        const counts = data.counts;
        const n = labels.length;

        // Default to the latest index (e.g., current month/year) if not specified
        if (activeIdx === null || activeIdx < 0 || activeIdx >= n) {
            activeIdx = n - 1;
        }
        selectedIndex = activeIdx;

        // 1. Calculate Maximum Y-Axis Ceiling dynamically
        const maxDataValue = Math.max(...counts, 0);
        let maxY = 10;
        if (maxDataValue > 10) {
            maxY = Math.ceil((maxDataValue * 1.25) / 5) * 5;
        } else if (maxDataValue > 5) {
            maxY = 10;
        } else if (maxDataValue > 0) {
            maxY = 5;
        }

        // Render Y-Axis Labels
        const yAxisContainer = document.getElementById('chartYAxis');
        if (yAxisContainer) {
            const step = maxY / 4;
            yAxisContainer.innerHTML = `
                <span>${Math.round(maxY)}</span>
                <span>${Math.round(step * 3)}</span>
                <span>${Math.round(step * 2)}</span>
                <span>${Math.round(step * 1)}</span>
                <span>0</span>
            `;
        }

        // 2. Compute (x, y) coordinates for SVG (viewBox: 0 0 1000 200)
        // SVG coordinate system: y=190 is zero, y=20 is max
        const svgHeight = 200;
        const zeroY = 190;
        const topY = 25;
        const usableHeight = zeroY - topY;

        const points = counts.map((count, i) => {
            const x = (i / (n - 1)) * 1000;
            const ratio = maxY > 0 ? (count / maxY) : 0;
            const y = zeroY - (ratio * usableHeight);
            return { x, y, count, label: labels[i], index: i };
        });

        // 3. Generate Curved Paths
        const linePathStr = getBezierCurvePath(points);
        const areaPathStr = linePathStr + ` L 1000 ${svgHeight} L 0 ${svgHeight} Z`;

        const chartLineCurve = document.getElementById('chartLineCurve');
        const chartAreaFill = document.getElementById('chartAreaFill');
        if (chartLineCurve) chartLineCurve.setAttribute('d', linePathStr);
        if (chartAreaFill) chartAreaFill.setAttribute('d', areaPathStr);

        // 4. Update Indicator line and dots
        const activePoint = points[activeIdx];
        const indicatorLine = document.getElementById('chartIndicatorLine');
        const activeDot = document.getElementById('chartActiveDot');

        if (indicatorLine) {
            indicatorLine.setAttribute('x1', activePoint.x);
            indicatorLine.setAttribute('x2', activePoint.x);
        }

        if (activeDot) {
            activeDot.setAttribute('cx', activePoint.x);
            activeDot.setAttribute('cy', activePoint.y);
        }

        // 5. Render interactive invisible hover circles or dots
        const pointsGroup = document.getElementById('chartPointsGroup');
        if (pointsGroup) {
            pointsGroup.innerHTML = '';
            points.forEach((pt, idx) => {
                // Little dot at point
                const dot = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                dot.setAttribute('cx', pt.x);
                dot.setAttribute('cy', pt.y);
                dot.setAttribute('r', idx === activeIdx ? '6' : '3.5');
                dot.setAttribute('fill', idx === activeIdx ? '#7c3aed' : '#ffffff');
                dot.setAttribute('stroke', '#7c3aed');
                dot.setAttribute('stroke-width', '2.5');
                dot.setAttribute('class', 'transition-all cursor-pointer');

                // Invisible large target for easy hover
                const hoverArea = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                hoverArea.setAttribute('cx', pt.x);
                hoverArea.setAttribute('cy', pt.y);
                hoverArea.setAttribute('r', '25');
                hoverArea.setAttribute('fill', 'transparent');
                hoverArea.setAttribute('class', 'cursor-pointer');
                hoverArea.addEventListener('mouseenter', () => renderDynamicChart(currentMode, idx));
                hoverArea.addEventListener('click', () => renderDynamicChart(currentMode, idx));

                pointsGroup.appendChild(dot);
                pointsGroup.appendChild(hoverArea);
            });
        }

        // 6. Update Pinned Tooltip
        const tooltip = document.getElementById('chartTooltip');
        const tooltipLabel = document.getElementById('chartTooltipLabel');
        const tooltipValue = document.getElementById('chartTooltipValue');

        if (tooltip && tooltipLabel && tooltipValue) {
            // Position tooltip in percentage
            const pctX = (activePoint.x / 1000) * 100;
            const pctY = (activePoint.y / 200) * 100;

            tooltip.style.left = `${pctX}%`;
            tooltip.style.top = `${pctY}%`;

            const isCurrentPeriod = (activeIdx === n - 1);
            tooltipLabel.textContent = isCurrentPeriod 
                ? (mode === 'monthly' ? 'Bulan Ini (' + activePoint.label + ')' : 'Tahun Ini (' + activePoint.label + ')')
                : (mode === 'monthly' ? 'Bulan ' + activePoint.label : 'Tahun ' + activePoint.label);

            tooltipValue.textContent = `${activePoint.count} Trip ${activePoint.count > 0 ? 'Aktif' : 'Tercatat'}`;
        }

        // 7. Update X-Axis labels
        const xAxisContainer = document.getElementById('chartXAxis');
        if (xAxisContainer) {
            xAxisContainer.innerHTML = '';
            labels.forEach((lbl, idx) => {
                const span = document.createElement('span');
                span.className = idx === activeIdx 
                    ? 'text-violet-600 font-bold cursor-pointer transition'
                    : 'text-slate-400 hover:text-slate-700 cursor-pointer transition';
                span.textContent = lbl;
                span.onclick = () => renderDynamicChart(currentMode, idx);
                xAxisContainer.appendChild(span);
            });
        }
    }

    /**
     * Switch view between Bulanan and Tahunan
     */
    function switchChartMode(mode) {
        const btnMonthly = document.getElementById('btnMonthlyChart');
        const btnYearly = document.getElementById('btnYearlyChart');
        const subtitle = document.getElementById('chartSubtitle');

        if (mode === 'monthly') {
            btnMonthly.className = 'px-3.5 py-1 text-xs font-bold text-slate-800 bg-white rounded-lg shadow-2xs transition';
            btnYearly.className = 'px-3.5 py-1 text-xs font-semibold text-slate-500 hover:text-slate-800 rounded-lg transition';
            if (subtitle) subtitle.textContent = 'Tren operasional dan pelaksanaan perjalanan wisata bulanan';
        } else {
            btnYearly.className = 'px-3.5 py-1 text-xs font-bold text-slate-800 bg-white rounded-lg shadow-2xs transition';
            btnMonthly.className = 'px-3.5 py-1 text-xs font-semibold text-slate-500 hover:text-slate-800 rounded-lg transition';
            if (subtitle) subtitle.textContent = 'Tren pertumbuhan trip operasional tahunan';
        }

        renderDynamicChart(mode, null);
    }

    // Initialize chart on page load
    document.addEventListener('DOMContentLoaded', function () {
        renderDynamicChart('monthly', null);
    });
</script>
@endsection
