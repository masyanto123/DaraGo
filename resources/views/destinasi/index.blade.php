@extends('layouts.admin')

@section('title', 'Data Master Destinasi Wisata')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
                <span>Data Master</span>
                <span>/</span>
                <span class="text-slate-600">Destinasi Wisata</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Destinasi Wisata Indonesia
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Katalog {{ number_format($destinasi->total()) }} objek wisata — dapat dijadwalkan dalam itinerary trip
            </p>
        </div>

        <a href="{{ route('destinasi.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#223268] hover:bg-[#1a2754] text-white text-xs font-semibold rounded-xl shadow-xs transition duration-150 cursor-pointer self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Destinasi</span>
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium px-4 py-3 rounded-xl flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter & Search --}}
    <form method="GET" action="{{ route('destinasi.index') }}" class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M20 20l-3.5-3.5"/></svg>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama wisata, provinsi, kota..."
                    class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                >
            </div>
            <select name="kategori" class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat }}" @selected(request('kategori') === $kat)>{{ ucwords($kat) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-[#223268] hover:bg-[#1a2754] text-white text-xs font-semibold rounded-xl transition cursor-pointer">
                Cari
            </button>
            @if(request('search') || request('kategori'))
                <a href="{{ route('destinasi.index') }}" class="px-4 py-2 text-xs text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3.5">#</th>
                        <th class="px-4 py-3.5">Nama Wisata</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="px-4 py-3.5">Lokasi</th>
                        <th class="px-4 py-3.5">Koordinat</th>
                        <th class="px-4 py-3.5">Deskripsi</th>
                        <th class="px-4 py-3.5 text-center">Jadwal</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($destinasi as $dest)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-4 py-3.5 font-mono font-medium text-slate-400 text-[11px]">{{ $dest->id }}</td>
                            <td class="px-4 py-3.5 font-semibold text-slate-900 max-w-[200px]">
                                <div class="flex items-start gap-1.5">
                                    <span class="text-amber-500 shrink-0 mt-0.5">📍</span>
                                    <span class="leading-snug">{{ $dest->nama_wisata }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($dest->kategori)
                                    <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-semibold text-[10px] capitalize">
                                        {{ $dest->kategori }}
                                    </span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-slate-500 text-[11px] max-w-[140px]">
                                @if($dest->kota_kabupaten || $dest->provinsi)
                                    <div>{{ $dest->kota_kabupaten }}</div>
                                    <div class="text-slate-400">{{ $dest->provinsi }}</div>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 font-mono text-[11px] text-slate-500">
                                @if($dest->latitude && $dest->longitude)
                                    <span>{{ number_format($dest->latitude, 4) }}</span><br>
                                    <span>{{ number_format($dest->longitude, 4) }}</span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-slate-500 max-w-[180px]">
                                @php
                                    $desc = $dest->deskripsi ?? '';
                                    $isGeneric = str_contains(strtolower($desc), 'deskripsi tidak ditemukan') || empty($desc);
                                @endphp
                                @if($isGeneric)
                                    <span class="text-slate-300 italic text-[11px]">Belum ada deskripsi</span>
                                @else
                                    <span class="text-[11px] line-clamp-2">{{ Str::limit($desc, 80) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-semibold text-[10px]">
                                    {{ $dest->jadwal_acara_count }} Acara
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('destinasi.edit', $dest) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('destinasi.destroy', $dest) }}" onsubmit="return confirm('Hapus destinasi {{ $dest->nama_wisata }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer" title="Hapus">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-slate-400">
                                @if(request('search') || request('kategori'))
                                    Tidak ada hasil untuk pencarian "<strong>{{ request('search') ?? request('kategori') }}</strong>".
                                @else
                                    Belum ada data destinasi wisata.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($destinasi->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $destinasi->links() }}
            </div>
        @endif
    </div>
@endsection
