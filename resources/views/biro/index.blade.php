@extends('layouts.admin')

@section('title', 'Data Master Biro Perjalanan')

@section('content')
    <div class="space-y-6">

        <!-- Hero Header Banner with Gradient (Matching Dashboard Container Theme) -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-violet-600 via-indigo-600 to-sky-500 p-6 sm:p-7 shadow-lg shadow-indigo-500/15 text-white">
            <!-- Subtle Glow Background Decorative Shapes -->
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-sky-400/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-1.5 text-xs text-white/80 font-medium mb-1.5">
                        <a href="{{ route('dashboard') }}" class="hover:underline">Dasbor</a>
                        <span>/</span>
                        <span class="text-white font-semibold">Biro Perjalanan</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                        Biro Perjalanan
                    </h1>
                    <p class="text-xs text-white/80 mt-1 max-w-xl">
                        Kelola daftar biro tour & travel yang bermitra dan beroperasi di sistem DaraGo
                    </p>
                </div>

                <a href="{{ route('biro.create') }}" class="inline-flex items-center px-4 py-2.5 bg-white/15 hover:bg-white/25 active:bg-white/30 backdrop-blur-md text-white text-xs font-semibold rounded-xl border border-white/20 shadow-sm transition duration-150 cursor-pointer self-start sm:self-auto hover:scale-[1.02]">
                    <span>Tambah Biro</span>
                </a>
            </div>
        </div>

        <!-- Search Bar Input (Matching Reference Design) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-3 shadow-xs">
            <form method="GET" action="{{ route('biro.index') }}" class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"/>
                        <path stroke-linecap="round" d="M20 20l-3.5-3.5"/>
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari biro perjalanan..." 
                    class="w-full pl-10 pr-24 py-2 text-xs bg-slate-50 hover:bg-slate-100/60 focus:bg-white border border-slate-200/80 focus:border-indigo-500 rounded-xl text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition"
                >
                @if(request('search'))
                    <a href="{{ route('biro.index') }}" class="absolute inset-y-0 right-16 pr-2 flex items-center text-xs text-slate-400 hover:text-slate-600 font-semibold">
                        Batal
                    </a>
                @endif
                <button type="submit" class="absolute inset-y-1.5 right-1.5 px-3 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition cursor-pointer">
                    Cari
                </button>
            </form>
        </div>

        <!-- 2-Column Cards Grid (Matching Company / Perusahaan Card in Reference) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @forelse($biros as $biro)
                @php
                    $trimmedName = trim($biro->nama_biro);
                    $prefix = '';
                    $badgeBg = 'bg-slate-700 text-white';

                    if (stripos($trimmedName, 'PT ') === 0 || stripos($trimmedName, 'PT. ') === 0) {
                        $prefix = 'PT';
                        $badgeBg = 'bg-[#334155] text-white';
                    } elseif (stripos($trimmedName, 'CV ') === 0 || stripos($trimmedName, 'CV. ') === 0) {
                        $prefix = 'CV';
                        $badgeBg = 'bg-[#84cc16] text-white';
                    } elseif (stripos($trimmedName, 'PO ') === 0 || stripos($trimmedName, 'PO. ') === 0) {
                        $prefix = 'PO';
                        $badgeBg = 'bg-[#6366f1] text-white';
                    } else {
                        // Initials
                        $parts = explode(' ', $trimmedName);
                        $prefix = strtoupper(substr($parts[0] ?? 'B', 0, 2));
                        $badgeBg = 'bg-[#4f46e5] text-white';
                    }
                @endphp

                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <!-- Card Header: Entity Badge & Active Indicator -->
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl {{ $badgeBg }} font-bold text-sm flex items-center justify-center shadow-xs select-none shrink-0">
                                {{ $prefix }}
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase bg-emerald-50 text-emerald-600 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                ACTIVE
                            </span>
                        </div>

                        <!-- Biro Name -->
                        <h2 class="text-base font-bold text-slate-900 tracking-tight leading-snug">
                            {{ $biro->nama_biro }}
                        </h2>

                        <!-- Details (Phone & Address with clean vector icons) -->
                        <div class="mt-2.5 space-y-1 text-xs text-slate-500 font-medium">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                                <span>{{ $biro->kontak ?? 'Belum ada kontak terdaftar' }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                <span class="truncate" title="{{ $biro->alamat }}">
                                    {{ $biro->alamat ?? 'Alamat kantor belum diisi' }}
                                </span>
                            </div>
                        </div>

                        <!-- Stats Inner Box (LOWONGAN / PELAMAR style -> TOTAL TRIP / TOTAL CREW) -->
                        <div class="mt-4 bg-slate-50/70 border border-slate-100 rounded-2xl p-3.5 grid grid-cols-2 divide-x divide-slate-200">
                            <div class="text-center px-2">
                                <div class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">
                                    TOTAL TRIP
                                </div>
                                <div class="text-xl font-bold text-slate-900 mt-0.5">
                                    {{ $biro->trip_perjalanan_count ?? 0 }}
                                </div>
                            </div>

                            <div class="text-center px-2">
                                <div class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">
                                    TOTAL CREW
                                </div>
                                <div class="text-xl font-bold text-slate-900 mt-0.5">
                                    {{ $biro->karyawan_count ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons Row (Lihat, Ubah, Hapus) -->
                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center gap-2">
                        <!-- Lihat / Detail Button -->
                        <a href="{{ route('trip.index', ['biro_id' => $biro->id]) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-blue-50/70 hover:bg-blue-100/70 text-blue-700 text-xs font-semibold transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Lihat</span>
                        </a>

                        <!-- Ubah Button -->
                        <a href="{{ route('biro.edit', $biro) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                            </svg>
                            <span>Ubah</span>
                        </a>

                        <!-- Hapus Button -->
                        <form method="POST" action="{{ route('biro.destroy', $biro) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus biro {{ $biro->nama_biro }}? Semua trip terkait juga akan dihapus.');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-xl text-rose-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Hapus Biro">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 bg-white rounded-3xl border border-slate-200/80 p-12 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Tidak ada data biro perjalanan</h3>
                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau tambahkan biro perjalanan baru.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($biros->hasPages())
            <div class="pt-4">
                {{ $biros->links() }}
            </div>
        @endif

    </div>
@endsection
