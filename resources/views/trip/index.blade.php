@extends('layouts.admin')

@section('title', 'Sistem Operasional Trip Perjalanan')

@section('content')
    <!-- Hero Header Banner with Gradient -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-violet-600 via-indigo-600 to-sky-500 p-6 sm:p-7 shadow-lg shadow-indigo-500/15 text-white">
        <!-- Subtle Glow Background Decorative Shapes -->
        <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-sky-400/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-1.5 text-xs text-white/80 font-medium mb-1.5">
                    <span>Sistem Operasional</span>
                    <span>/</span>
                    <span class="text-white font-semibold">Trip Perjalanan</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                    Trip Perjalanan
                </h1>
                <p class="text-xs text-white/80 mt-1 max-w-xl">
                    Kelola paket tur & perjalanan wisata, alokasi armada bus, manifest presensi, dan itinerary perjalanan dengan mudah.
                </p>
            </div>

            <a href="{{ route('trip.create') }}" class="inline-flex items-center px-4 py-2.5 bg-white/15 hover:bg-white/25 active:bg-white/30 backdrop-blur-md text-white text-xs font-semibold rounded-xl border border-white/20 shadow-sm transition duration-150 cursor-pointer self-start sm:self-auto hover:scale-[1.02]">
                <span>Buat Trip Baru</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">ID</th>
                        <th class="px-6 py-3.5">Nama Kegiatan Trip</th>
                        <th class="px-6 py-3.5">Biro Penyelenggara</th>
                        <th class="px-6 py-3.5">Jadwal Pelaksanaan</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                        <th class="px-6 py-3.5 text-center">Armada & Manifest</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($trips as $trip)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-mono font-medium text-slate-400">#{{ $trip->id }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('trip.show', $trip) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition block">
                                    {{ $trip->nama_kegiatan }}
                                </a>
                                <span class="text-[11px] text-slate-400">{{ $trip->jadwal_acara_count }} Acara Itinerary</span>
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                {{ $trip->biro?->nama_biro ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $trip->tgl_mulai->format('d M Y') }} s/d {{ $trip->tgl_selesai->format('d M Y') }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $trip->tgl_mulai->diffInDays($trip->tgl_selesai) + 1 }} Hari
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($trip->status === 'Ongoing')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-[9.5px] border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Sedang Berjalan
                                    </span>
                                @elseif($trip->status === 'Upcoming')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-semibold text-[9.5px] border border-blue-200">
                                        Mendatang
                                    </span>
                                @elseif($trip->status === 'Completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold text-[9.5px] border border-slate-200">
                                        Selesai
                                    </span>
                                @elseif($trip->status === 'Cancelled')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 font-semibold text-[9.5px] border border-rose-200">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold text-[9.5px]">
                                        {{ $trip->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-semibold text-[10px]">
                                    {{ $trip->armada_count }} Bus • {{ $trip->manifest_count }} Peserta
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('trip.show', $trip) }}" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Lihat Detail & Manifest">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('trip.edit', $trip) }}" class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('trip.destroy', $trip) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus trip ini? Semua data manifest dan armada terkait akan terhapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                Belum ada data trip perjalanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($trips->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $trips->links() }}
            </div>
        @endif
    </div>
@endsection
