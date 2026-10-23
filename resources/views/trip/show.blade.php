@extends('layouts.admin')

@section('title', 'Detail Trip Operasional')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/WPeE=" crossorigin=""></script>
    <style>
        .leaflet-popup-content-wrapper {
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .custom-pin-marker {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            color: white;
            font-weight: 800;
            font-size: 11px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.25);
            border: 2px solid white;
            transition: transform 0.15s ease;
        }
        .custom-pin-marker:hover {
            transform: scale(1.15);
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
                    <a href="{{ route('trip.index') }}" class="hover:underline">Trip Perjalanan</a>
                    <span>/</span>
                    <span class="text-slate-600">Detail #{{ $trip->id }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                    <span>{{ $trip->nama_kegiatan }}</span>
                    @if($trip->status === 'Ongoing')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-xs border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Sedang Berjalan (Ongoing)
                        </span>
                    @elseif($trip->status === 'Upcoming')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 font-semibold text-xs border border-blue-200">
                            Mendatang (Upcoming)
                        </span>
                    @elseif($trip->status === 'Completed')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200">
                            Selesai (Completed)
                        </span>
                    @elseif($trip->status === 'Cancelled')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 font-semibold text-xs border border-rose-200">
                            Dibatalkan (Cancelled)
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold text-xs">
                            {{ $trip->status }}
                        </span>
                    @endif
                </h1>
                <p class="text-xs text-slate-400 mt-0.5">
                    Diselenggarakan oleh <strong class="text-slate-600">{{ $trip->biro?->nama_biro }}</strong> • {{ $trip->tgl_mulai->format('d M Y') }} s/d {{ $trip->tgl_selesai->format('d M Y') }}
                </p>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('trip.edit', $trip) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                    <span>Edit Trip</span>
                </a>
            </div>
        </div>

        <!-- Metric Snapshot for this Trip -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="text-[10px] font-bold text-slate-400 uppercase">ARMADA BUS</div>
                <div class="text-xl font-bold text-slate-900 mt-1">{{ $trip->armada->count() }} Bus</div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="text-[10px] font-bold text-slate-400 uppercase">PESERTA TERDAFTAR</div>
                <div class="text-xl font-bold text-slate-900 mt-1">{{ $trip->manifest->count() }} Orang</div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="text-[10px] font-bold text-slate-400 uppercase">AGENDA ITINERARY</div>
                <div class="text-xl font-bold text-slate-900 mt-1">{{ $trip->jadwalAcara->count() }} Kegiatan</div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="text-[10px] font-bold text-slate-400 uppercase">DOKUMENTASI</div>
                <div class="text-xl font-bold text-slate-900 mt-1">{{ $trip->dokumentasi->count() }} Media</div>
            </div>
        </div>

        @php
            $existingJadwalStops = $trip->jadwalAcara->map(function ($acara) {
                $kegiatan = $acara->kegiatan;
                $lokasi = $kegiatan;
                $deskripsi = '';
                if (str_contains($kegiatan, ' — ')) {
                    $parts = explode(' — ', $kegiatan, 2);
                    $lokasi = trim($parts[0] ?? '');
                    $deskripsi = trim($parts[1] ?? '');
                } elseif ($acara->destinasi) {
                    $lokasi = $acara->destinasi->nama_tempat;
                    if ($acara->kegiatan !== $acara->destinasi->nama_tempat) {
                        $deskripsi = $acara->kegiatan;
                    }
                }

                $lat = $acara->latitude ?? $acara->destinasi?->latitude ?? null;
                $lng = $acara->longitude ?? $acara->destinasi?->longitude ?? null;

                return [
                    'lokasi' => $lokasi,
                    'deskripsi' => $deskripsi,
                    'destinasi_id' => $acara->destinasi_id ? (string) $acara->destinasi_id : '',
                    'latitude' => $lat !== null ? (float) $lat : null,
                    'longitude' => $lng !== null ? (float) $lng : null,
                    'jam_mulai' => $acara->waktu_mulai ? $acara->waktu_mulai->format('H:i') : '08:00',
                    'jam_selesai' => $acara->waktu_selesai ? $acara->waktu_selesai->format('H:i') : '10:00',
                ];
            })->values();

            $validRouteStops = $existingJadwalStops->filter(fn($s) => !empty($s['latitude']) && !empty($s['longitude']))->values();
        @endphp

        <!-- 2 Column Section: Manifest Peserta & Itinerary Schedule -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- LEFT: MANIFEST PESERTA & PRESENSI (7 cols) -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">
                            Manifest & Presensi Peserta
                        </h3>
                        <p class="text-xs text-slate-400">
                            Daftar pembagian kursi bus dan checklist presensi realtime
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="openModal('modalManifest')" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-xs transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            <span>Tambah Peserta</span>
                        </button>
                        <button type="button" onclick="openQrModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h1.5v1.5h-1.5v-1.5zM13.5 18h1.5v1.5h-1.5v-1.5zM16.5 15h1.5v1.5h-1.5v-1.5zM18 13.5h1.5v1.5H18v-1.5zM18 18h1.5v1.5H18v-1.5zM15 16.5h1.5v1.5H15v-1.5z" />
                            </svg>
                            <span>QR Code</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-2.5">Peserta</th>
                                <th class="px-4 py-2.5">Armada / Kursi</th>
                                <th class="px-4 py-2.5 text-center">Status Presensi</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($trip->manifest as $m)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">{{ $m->peserta?->user?->name ?? 'Peserta' }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $m->peserta?->nama_instansi }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-slate-800">{{ $m->armada?->nomor_bus ?? 'Belum ada bus' }}</div>
                                        <span class="text-[10px] bg-indigo-50 text-indigo-700 font-semibold px-1.5 py-0.2 rounded">Kursi {{ $m->nomor_kursi ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <form method="POST" action="{{ route('manifest.presensi', $m) }}">
                                             @csrf
                                             @method('PATCH')
                                             <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold transition cursor-pointer {{ $m->status_presensi ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}" title="Klik untuk ubah presensi">
                                                 <span>{{ $m->status_presensi ? 'Hadir' : 'Belum Hadir' }}</span>
                                             </button>
                                         </form>
                                     </td>
                                     <td class="px-4 py-3 text-right">
                                        <form method="POST" action="{{ route('trip.manifest.destroy', $m) }}" onsubmit="return confirm('Hapus peserta ini dari manifest?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded text-slate-400 hover:text-red-600 hover:bg-red-50 transition cursor-pointer" title="Hapus dari manifest">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-400">
                                        Belum ada data manifest peserta di trip ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RIGHT: ITINERARY & ARMADA (5 cols) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- ARMADA BUS ASSIGNMENT -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-slate-900">
                            Armada Bus
                        </h3>
                        <button type="button" onclick="openModal('modalArmada')" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            <span>Tambah Bus</span>
                        </button>
                    </div>
                    <div class="space-y-3">
                        @forelse($trip->armada as $bus)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs relative group">
                                <div class="flex items-center justify-between font-bold text-slate-900 mb-1">
                                    <span>{{ $bus->nomor_bus }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] text-slate-500 font-normal">{{ $bus->manifest->count() }} Peserta</span>
                                        <form method="POST" action="{{ route('trip.armada.destroy', $bus) }}" onsubmit="return confirm('Hapus armada bus ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-red-600 transition cursor-pointer p-0.5" title="Hapus Bus">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="text-[11px] text-slate-500 font-medium">{{ $bus->po?->nama_po ?? $bus->nama_po ?? 'PO Pariwisata' }}</div>
                                <div class="mt-2 pt-2 border-t border-slate-200/60 text-[10px] text-slate-400 flex items-center justify-between">
                                    <span>Driver: <strong class="text-slate-700">{{ $bus->nama_driver }}</strong></span>
                                    <span>TL: <strong class="text-slate-700">{{ $bus->tourLeader?->user?->name ?? '-' }}</strong></span>
                                </div>
                            </div>
                        @empty
                            <div class="text-xs text-slate-400 text-center py-4">Belum ada armada bus yang dialokasikan.</div>
                        @endforelse
                    </div>
                </div>

                <!-- ITINERARY TIMELINE -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-slate-900">
                            Jadwal Acara (Itinerary)
                        </h3>
                        @if($trip->jadwalAcara->isEmpty())
                            <button type="button" onclick="openModal('modalJadwal')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                <span>Tambahkan Jadwal Acara</span>
                            </button>
                        @else
                            <button type="button" onclick="openModal('modalJadwal')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                                <span>Edit Jadwal</span>
                            </button>
                        @endif
                    </div>
                    <div class="space-y-3">
                        @forelse($trip->jadwalAcara as $acara)
                            <div class="flex gap-3 text-xs justify-between group">
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 mt-1"></div>
                                        <div class="grow w-0.5 bg-slate-200 my-1"></div>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $acara->kegiatan }}</div>
                                        <div class="text-[11px] text-indigo-600 font-medium">{{ $acara->destinasi?->nama_tempat ?? ($acara->latitude ? 'Titik Terkoordinasi Peta' : 'Lokasi Terbuka') }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $acara->waktu_mulai->format('d M, H:i') }} - {{ $acara->waktu_selesai->format('H:i') }} WIB</div>
                                        @if($acara->latitude && $acara->longitude)
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $acara->latitude }},{{ $acara->longitude }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] text-teal-600 hover:text-teal-700 hover:underline font-semibold mt-0.5">
                                                <span>Buka di Peta Navigasi</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('trip.jadwal.destroy', $acara) }}" onsubmit="return confirm('Hapus jadwal ini?')" class="inline self-start">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-300 hover:text-red-600 transition cursor-pointer p-1" title="Hapus Jadwal">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="text-xs text-slate-400 text-center py-4">Belum ada jadwal acara tercatat.</div>
                        @endforelse
                    </div>
                </div>

                <!-- PETA RUTE PERJALANAN (INTERACTIVE ROUTE MAP) -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs space-y-3" id="cardPetaRute">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                                <span>Peta Rute Perjalanan</span>
                            </h3>
                            <p class="text-[11px] text-slate-400">Jalur rute kronologis itinerary agenda trip</p>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span id="routeStopBadge" class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $validRouteStops->count() > 0 ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'bg-slate-100 text-slate-500' }}">
                                {{ $validRouteStops->count() }} Titik Terhubung
                            </span>
                            <button type="button" onclick="fitRouteMapBounds()" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer" title="Reset Zoom / Pusatkan Peta">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Map Container with Leaflet -->
                    <div class="relative w-full rounded-xl border border-slate-200 overflow-hidden bg-slate-50">
                        <div id="itineraryRouteMap" class="w-full h-80 z-0"></div>

                        <!-- Routing Loading Overlay -->
                        <div id="routeLoadingOverlay" class="absolute inset-0 bg-white/75 backdrop-blur-2xs flex items-center justify-center gap-2 text-xs font-semibold text-slate-700 z-10 hidden">
                            <svg class="w-4 h-4 text-teal-600 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Menghubungkan jalur rute jalan...</span>
                        </div>
                    </div>

                    <!-- Route Metrics & Navigation Row -->
                    <div id="routeMetricsContainer" class="pt-2 border-t border-slate-100 flex flex-col gap-2">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <div>
                                    <div class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">ESTIMASI JARAK</div>
                                    <div class="font-bold text-slate-800" id="routeTotalDistance">-</div>
                                </div>
                                <div class="w-px h-6 bg-slate-200"></div>
                                <div>
                                    <div class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">WAKTU TEMPUH</div>
                                    <div class="font-bold text-slate-800" id="routeTotalDuration">-</div>
                                </div>
                            </div>
                            <div id="googleMapsAllBtn">
                                <!-- Dynamic link to Google Maps Directions -->
                            </div>
                        </div>

                        <div id="routeNoticeBox" class="text-[10px] text-slate-500">
                            <!-- Helpful notices & warnings if some stops lack coordinates -->
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- MODAL QR CODE JOIN ROOM MOBILE APP -->
    <div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 text-center transform transition-all">
            <!-- Close Button -->
            <button type="button" onclick="closeQrModal()" class="absolute top-4 right-4 p-1.5 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Header -->
            <div class="inline-flex p-3 rounded-2xl bg-indigo-50 text-indigo-600 mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h1.5v1.5h-1.5v-1.5zM13.5 18h1.5v1.5h-1.5v-1.5zM16.5 15h1.5v1.5h-1.5v-1.5zM18 13.5h1.5v1.5H18v-1.5zM18 18h1.5v1.5H18v-1.5zM15 16.5h1.5v1.5H15v-1.5z" />
                </svg>
            </div>
            
            <h3 class="text-lg font-extrabold text-slate-900">QR Code Join Trip</h3>
            <p class="text-xs text-slate-500 mt-1 mb-4">Pindai QR ini menggunakan aplikasi mobile <strong>DaraGo Client</strong> untuk masuk ke room trip ini.</p>

            <!-- QR Container -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 inline-block mb-4 shadow-inner">
                <img id="qrCodeImg" src="" alt="QR Code Join Trip" class="w-48 h-48 mx-auto rounded-lg shadow-sm border border-white">
            </div>

            <!-- Detail Information -->
            <div class="bg-indigo-50/60 rounded-xl p-3 text-left text-xs mb-4 space-y-1.5 border border-indigo-100">
                <div class="flex justify-between text-slate-500">
                    <span>Kode Room:</span>
                    <strong class="text-indigo-700 font-mono" id="joinCodeText">DARAGO-TRIP-{{ $trip->id }}</strong>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Trip:</span>
                    <strong class="text-slate-800 truncate max-w-[180px]">{{ $trip->nama_kegiatan }}</strong>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Biro:</span>
                    <strong class="text-slate-800 truncate max-w-[180px]">{{ $trip->biro?->nama_biro ?? '-' }}</strong>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="copyJoinCode()" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs transition cursor-pointer">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 011.927-.184"/></svg>
                    <span id="copyBtnText">Salin Kode</span>
                </button>
                <button type="button" onclick="downloadQrCode()" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    <span>Unduh QR</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH ARMADA BUS -->
    <div id="modalArmada" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span>Tambah Armada Bus ke Trip</span>
                </h3>
                <button type="button" onclick="closeModal('modalArmada')" class="p-1 rounded-full text-slate-400 hover:text-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('trip.armada.store', $trip) }}" class="space-y-4 text-xs">
                @csrf

                <!-- Trip Schedule & Biro Context Banner -->
                <div class="p-3 rounded-2xl bg-indigo-50/60 border border-indigo-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-slate-700">
                    <div class="flex items-center gap-2">
                        <span class="p-1.5 rounded-xl bg-white text-indigo-600 shadow-2xs font-bold text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        </span>
                        <div>
                            <div class="text-[10px] text-indigo-500 font-semibold uppercase tracking-wider">Jadwal Berlangsung Trip</div>
                            <div class="text-xs font-bold text-slate-900">{{ $trip->tgl_mulai ? $trip->tgl_mulai->format('d M Y') : '-' }} s/d {{ $trip->tgl_selesai ? $trip->tgl_selesai->format('d M Y') : '-' }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[10px] text-slate-400 font-medium">Biro Penyelenggara:</div>
                        <div class="text-xs font-bold text-indigo-700">{{ $trip->biro?->nama_biro ?? 'Biro Mandiri' }}</div>
                    </div>
                </div>

                <!-- 1. PILIH PERUSAHAAN OTOBUS (PO BUS) -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">
                        Pilih Perusahaan Otobus (PO) <span class="text-red-500">*</span>
                    </label>
                    <select name="po_id" id="trip_po_id" onchange="onPoBusChange()" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                        <option value="">-- Pilih PO Bus dari Data Master --</option>
                        @foreach($poBusList as $p)
                            @php
                                $totalK = $p->kendaraan->count();
                                $availableK = $p->kendaraan->filter(fn($k) => !isset($busyKendaraan[$k->id]) && !in_array($k->id, $alreadyInTripKendaraanIds))->count();
                            @endphp
                            <option value="{{ $p->id }}" {{ $availableK === 0 && $totalK > 0 ? 'class=text-slate-400' : '' }}>
                                {{ $p->nama_po }} — ({{ $availableK }} dari {{ $totalK }} Bus Siap Tugas)
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">
                        Ketersediaan armada otomatis dihitung berdasarkan bentrok tanggal trip. Belum ada PO? <a href="{{ route('armada.create') }}" target="_blank" class="text-indigo-600 underline font-medium">Tambah PO Bus</a>
                    </p>
                </div>

                <!-- 2. PILIH ARMADA KENDARAAN (ANAKAN PO) -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 flex items-center justify-between">
                        <span>Pilih Unit Armada Kendaraan <span class="text-red-500">*</span></span>
                        <span id="kendaraanCountBadge" class="text-[10px] text-slate-400 font-normal">Pilih PO terlebih dahulu</span>
                    </label>
                    <select name="kendaraan_id" id="trip_kendaraan_id" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                        <option value="">-- Pilih Unit Armada Kendaraan --</option>
                    </select>

                    <!-- Input manual jika unit belum didaftarkan di PO -->
                    <div id="manualBusContainer" class="mt-2 hidden">
                        <label class="block text-[10px] font-medium text-slate-500 mb-0.5">Atau Masukkan Nomor Bus / Plat Manual:</label>
                        <input type="text" name="nomor_bus" id="trip_nomor_bus_manual" placeholder="Contoh: BUS 01 (B 7123 KAA)" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs">
                    </div>
                </div>

                <!-- 3. PILIH SUPIR & TOUR LEADER -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">
                            Supir (Driver dari PO Terpilih)
                        </label>
                        <select name="supir_po_id" id="trip_supir_po_id" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih Supir PO --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 flex items-center justify-between">
                            <span>Tour Leader (Biro: {{ $trip->biro?->nama_biro ? Str::limit($trip->biro->nama_biro, 18) : 'Biro' }})</span>
                        </label>
                        <select name="tour_leader_id" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                            <option value="">-- Pilih Tour Leader --</option>
                            @forelse($tourLeaderList as $k)
                                @php
                                    $isAlreadyInTrip = in_array($k->id, $alreadyInTripTourLeaderIds ?? []);
                                    $isBusy = isset($busyTourLeaders[$k->id]);
                                    $conflictInfo = $isBusy ? $busyTourLeaders[$k->id] : null;
                                @endphp
                                <option value="{{ $k->id }}" {{ $isAlreadyInTrip || $isBusy ? 'disabled' : '' }} class="{{ $isAlreadyInTrip || $isBusy ? 'text-slate-400 bg-slate-100' : 'text-slate-800' }}">
                                    @if($isAlreadyInTrip)
                                        {{ $k->user?->name }} ({{ $k->posisi }}) — (Sudah Memimpin Bus Lain di Trip Ini)
                                    @elseif($isBusy)
                                        {{ $k->user?->name }} ({{ $k->posisi }}) — [Sedang Bertugas: {{ $conflictInfo['trip_name'] }}]
                                    @else
                                        {{ $k->user?->name }} ({{ $k->posisi }}) - Tersedia
                                    @endif
                                </option>
                            @empty
                                <option value="" disabled>-- Tidak ada Tour Leader di biro ini --</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modalArmada')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-xs transition">Simpan Bus ke Trip</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH JADWAL ACARA (GOOGLE MAPS STYLE ROUTE DESIGN) -->
    <div id="modalJadwal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="relative w-full max-w-xl bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-7">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="p-2 rounded-xl bg-teal-50 text-teal-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        </span>
                        <span>{{ $trip->jadwalAcara->isEmpty() ? 'Tambah Jadwal & Rute Perjalanan' : 'Edit Jadwal & Rute Perjalanan' }}</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">Susun urutan lokasi & agenda kegiatan trip secara interaktif</p>
                </div>
                <button type="button" onclick="closeModal('modalJadwal')" class="p-1.5 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('trip.jadwal.store', $trip) }}" id="formJadwal" class="space-y-4">
                @csrf

                <!-- Dynamic Validation Alert Box -->
                <div id="jadwalValidationAlert" class="hidden p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    <div id="jadwalValidationMessage" class="font-medium grow"></div>
                    <button type="button" onclick="hideJadwalAlert()" class="p-1 text-rose-400 hover:text-rose-600 rounded-md transition ml-1" title="Tutup">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Google Maps Route Input Container -->
                <div id="jadwalStopsContainer" class="space-y-3">
                    <!-- Dynamic Stop Rows rendered via JavaScript -->
                </div>

                <!-- Add Stop Button (Google Maps Style) -->
                <div class="pt-2 flex items-center gap-3">
                    <button type="button" onclick="addJadwalStop()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-slate-700 hover:bg-slate-100 font-semibold text-xs transition cursor-pointer group border border-dashed border-slate-300 hover:border-slate-400">
                        <span class="w-6 h-6 rounded-full border border-slate-700 flex items-center justify-center font-bold text-slate-800 text-sm group-hover:scale-105 transition">
                            +
                        </span>
                        <span class="text-slate-700 font-semibold">Tambahkan tujuan</span>
                    </button>
                </div>

                <!-- Modal Actions -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div class="text-[11px] text-slate-400">
                        <span id="stopCountText">0 tujuan ditambahkan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="closeModal('modalJadwal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-xs hover:bg-slate-50 transition cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs shadow-md shadow-teal-600/20 transition cursor-pointer">{{ $trip->jadwalAcara->isEmpty() ? 'Simpan Rangkaian Jadwal' : 'Simpan Perubahan Jadwal' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH MANIFEST PESERTA -->
    <div id="modalManifest" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900">Tambah Peserta ke Manifest</h3>
                <button type="button" onclick="closeModal('modalManifest')" class="p-1 rounded-full text-slate-400 hover:text-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('trip.manifest.store', $trip) }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Peserta / Klien <span class="text-red-500">*</span></label>
                    <select name="peserta_id" required class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih Peserta --</option>
                        @foreach($pesertaList as $p)
                            <option value="{{ $p->id }}">{{ $p->user?->name }} ({{ $p->nama_instansi }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Armada Bus <span class="text-red-500">*</span></label>
                    <select name="armada_id" required class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih Bus --</option>
                        @foreach($trip->armada as $bus)
                            <option value="{{ $bus->id }}">{{ $bus->nomor_bus }} ({{ $bus->nama_po ?? 'PO' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nomor Kursi</label>
                    <input type="text" name="nomor_kursi" placeholder="misal: 12A" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modalManifest')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-semibold shadow-xs transition">Simpan Peserta</button>
                </div>
            </form>
        </div>
    </div>
    <!-- JS SCRIPT UNTUK GENERATE QR CODE & MODAL CONTROL & OPENSTREETMAP ROUTE STOPS -->
    <script>
        const poBusData = @json($poBusList);
        const destinasiWisataData = @json($destinasiList);
        const existingJadwalData = @json($existingJadwalStops);
        const busyKendaraanData = @json($busyKendaraan);
        const busySupirPoData = @json($busySupirPo);
        const busyTourLeadersData = @json($busyTourLeaders);
        const alreadyInTripKendaraanIdsData = @json($alreadyInTripKendaraanIds);
        const alreadyInTripSupirPoIdsData = @json($alreadyInTripSupirPoIds);

        const defaultEmptyStops = [
            { lokasi: '', deskripsi: '', destinasi_id: '', latitude: null, longitude: null, jam_mulai: '08:00', jam_selesai: '10:00' },
            { lokasi: '', deskripsi: '', destinasi_id: '', latitude: null, longitude: null, jam_mulai: '10:00', jam_selesai: '11:30' }
        ];

        // State for Google Maps style Route Itinerary
        let jadwalStops = (existingJadwalData && existingJadwalData.length > 0)
            ? JSON.parse(JSON.stringify(existingJadwalData))
            : JSON.parse(JSON.stringify(defaultEmptyStops));

        let osmDebounceTimer = null;

        function timeToMinutes(t) {
            if (!t) return 0;
            const parts = t.split(':');
            return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
        }

        function minutesToTime(m) {
            m = Math.max(0, Math.min(m, 23 * 60 + 59));
            const h = Math.floor(m / 60);
            const min = m % 60;
            return `${String(h).padStart(2, '0')}:${String(min).padStart(2, '0')}`;
        }

        function showJadwalAlert(msg) {
            const alertBox = document.getElementById('jadwalValidationAlert');
            const msgBox = document.getElementById('jadwalValidationMessage');
            if (alertBox && msgBox) {
                msgBox.textContent = msg;
                alertBox.classList.remove('hidden');
                setTimeout(() => {
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 50);
            }
        }

        function hideJadwalAlert() {
            const alertBox = document.getElementById('jadwalValidationAlert');
            if (alertBox) alertBox.classList.add('hidden');
        }

        function cascadeAdjustSubsequentStops(fromIndex) {
            for (let i = fromIndex + 1; i < jadwalStops.length; i++) {
                const prevEnd = jadwalStops[i - 1].jam_selesai;
                const prevEndMin = timeToMinutes(prevEnd);
                const currStartMin = timeToMinutes(jadwalStops[i].jam_mulai);
                const currEndMin = timeToMinutes(jadwalStops[i].jam_selesai);
                let duration = currEndMin - currStartMin;
                if (duration <= 0) duration = 90;

                // Jika jam mulai agenda berikutnya mendahului jam selesai agenda sebelumnya, dorong maju otomatis
                if (currStartMin < prevEndMin) {
                    jadwalStops[i].jam_mulai = prevEnd;
                    jadwalStops[i].jam_selesai = minutesToTime(prevEndMin + duration);
                }
            }
        }

        function onJamMulaiChange(index, newStartTime) {
            hideJadwalAlert();
            if (!newStartTime) return;

            // Jika bukan agenda pertama, cegah mulai sebelum agenda sebelumnya selesai
            if (index > 0) {
                const prevEnd = jadwalStops[index - 1].jam_selesai;
                if (timeToMinutes(newStartTime) < timeToMinutes(prevEnd)) {
                    showJadwalAlert(`Agenda #${index + 1} tidak bisa dimulai pukul ${newStartTime} karena agenda #${index} baru selesai pukul ${prevEnd}. Jam mulai otomatis disesuaikan ke ${prevEnd}.`);
                    newStartTime = prevEnd;
                }
            }

            const oldStart = jadwalStops[index].jam_mulai;
            const oldEnd = jadwalStops[index].jam_selesai;
            let duration = timeToMinutes(oldEnd) - timeToMinutes(oldStart);
            if (duration <= 0) duration = 90;

            jadwalStops[index].jam_mulai = newStartTime;

            // Jika jam selesai menjadi <= jam mulai baru, dorong jam selesai sesuai durasi
            if (timeToMinutes(jadwalStops[index].jam_selesai) <= timeToMinutes(newStartTime)) {
                jadwalStops[index].jam_selesai = minutesToTime(timeToMinutes(newStartTime) + duration);
            }

            // Sesuaikan agenda berikutnya secara berantai
            cascadeAdjustSubsequentStops(index);
            renderJadwalStops();
        }

        function onJamSelesaiChange(index, newEndTime) {
            hideJadwalAlert();
            if (!newEndTime) return;

            const startTime = jadwalStops[index].jam_mulai;
            if (timeToMinutes(newEndTime) <= timeToMinutes(startTime)) {
                showJadwalAlert(`Jam selesai agenda #${index + 1} harus lebih lambat dari jam mulai (${startTime}). Waktu otomatis disesuaikan.`);
                newEndTime = minutesToTime(timeToMinutes(startTime) + 60);
            }

            jadwalStops[index].jam_selesai = newEndTime;

            // Sesuaikan agenda berikutnya secara berantai
            cascadeAdjustSubsequentStops(index);
            renderJadwalStops();
        }

        function adjustStopStartTime(index, targetTime) {
            onJamMulaiChange(index, targetTime);
        }

        function renderJadwalStops() {
            const container = document.getElementById('jadwalStopsContainer');
            const countText = document.getElementById('stopCountText');
            if (!container) return;

            container.innerHTML = '';

            if (countText) {
                countText.textContent = `${jadwalStops.length} titik lokasi & agenda ditambahkan`;
            }

            jadwalStops.forEach((stop, index) => {
                const isLast = index === jadwalStops.length - 1;
                const prevStop = index > 0 ? jadwalStops[index - 1] : null;
                const prevEndTime = prevStop ? prevStop.jam_selesai : null;
                const isConflict = prevStop && (timeToMinutes(stop.jam_mulai) < timeToMinutes(prevEndTime));

                const row = document.createElement('div');
                row.className = 'flex items-start gap-3 relative group';

                // Icon Timeline HTML
                let iconHtml = '';
                if (!isLast) {
                    iconHtml = `
                        <div class="flex flex-col items-center self-stretch pt-3 shrink-0">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-700 bg-white flex items-center justify-center z-10">
                                <div class="w-1.5 h-1.5 rounded-full bg-slate-700"></div>
                            </div>
                            <div class="w-0.5 grow border-l-2 border-dotted border-slate-300 my-1"></div>
                        </div>
                    `;
                } else {
                    iconHtml = `
                        <div class="flex flex-col items-center self-stretch pt-2 shrink-0">
                            <div class="w-6 h-6 flex items-center justify-center z-10 text-red-500">
                                <svg class="w-6 h-6 fill-current text-red-500" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            </div>
                        </div>
                    `;
                }

                // Placeholder text
                let placeholderText = 'Cari lokasi (Kantor, Gedung, Tempat Wisata, Jalan)...';
                if (index === 0) placeholderText = 'Titik Awal (misal: Kantor Medika Digital Nusantara)';
                else if (isLast) placeholderText = 'Destinasi Akhir Perjalanan';
                else placeholderText = `Titik Singgah #${index}`;

                row.innerHTML = `
                    ${iconHtml}

                    <div class="grow bg-white border ${isConflict ? 'border-amber-300 ring-1 ring-amber-300 bg-amber-50/15' : 'border-slate-300'} rounded-2xl p-3 shadow-xs hover:border-slate-400 focus-within:border-teal-600 focus-within:ring-2 focus-within:ring-teal-600/20 transition space-y-2 relative">
                        
                        <!-- Top Row: OpenStreetMap Live Search Location Input -->
                        <div class="relative">
                            <div class="flex items-center gap-2">
                                <input type="text" 
                                    id="osm_input_${index}"
                                    name="stops[${index}][lokasi]" 
                                    value="${stop.lokasi || ''}" 
                                    placeholder="${placeholderText}" 
                                    required 
                                    autocomplete="off"
                                    class="grow text-xs font-bold text-slate-900 placeholder-slate-400 focus:outline-none bg-transparent"
                                    oninput="onOsmSearchInput(${index}, this.value)">
                                
                                <input type="hidden" name="stops[${index}][destinasi_id]" id="destinasi_id_${index}" value="${stop.destinasi_id || ''}">
                                <input type="hidden" name="stops[${index}][latitude]" id="latitude_${index}" value="${(stop.latitude !== null && stop.latitude !== undefined) ? stop.latitude : ''}">
                                <input type="hidden" name="stops[${index}][longitude]" id="longitude_${index}" value="${(stop.longitude !== null && stop.longitude !== undefined) ? stop.longitude : ''}">
                                
                                <div id="coord_badge_${index}" class="shrink-0">
                                    ${(stop.latitude && stop.longitude) ? `
                                        <span class="inline-flex items-center gap-1 text-[9px] text-teal-700 bg-teal-50 px-1.5 py-0.5 rounded font-semibold border border-teal-100" title="Koordinat: ${stop.latitude}, ${stop.longitude}">
                                            Terhubung Peta (${Number(stop.latitude).toFixed(3)}, ${Number(stop.longitude).toFixed(3)})
                                        </span>
                                    ` : `
                                        <span class="inline-flex items-center gap-1 text-[9px] text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded font-medium border border-slate-200">
                                            Lokasi Kustom
                                        </span>
                                    `}
                                </div>

                                <div id="osm_spinner_${index}" class="hidden">
                                    <svg class="w-4 h-4 text-teal-600 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                </div>
                                <div id="osm_icon_${index}">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- OpenStreetMap Results Dropdown -->
                            <div id="osm_dropdown_${index}" class="absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 overflow-hidden divide-y divide-slate-100 max-h-60 overflow-y-auto hidden">
                                <!-- Results injected dynamically -->
                            </div>
                        </div>

                        <!-- Bottom Row: Time Pickers (Jam Mulai - Jam Selesai) & Detail Deskripsi -->
                        <div class="pt-2 border-t border-slate-100 space-y-1.5">
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 text-[11px]">
                                <!-- Jam Inputs -->
                                <div class="flex items-center gap-1.5 shrink-0 ${isConflict ? 'bg-amber-100/70 border-amber-300 ring-1 ring-amber-300' : 'bg-slate-50 border-slate-200'} px-2 py-1 rounded-xl border transition">
                                    <input type="time" 
                                        name="stops[${index}][jam_mulai]" 
                                        id="jam_mulai_${index}"
                                        value="${stop.jam_mulai || '08:00'}" 
                                        ${prevEndTime ? `min="${prevEndTime}"` : ''}
                                        required
                                        title="${prevEndTime ? `Minimal pukul ${prevEndTime} (setelah agenda sebelumnya selesai)` : 'Jam mulai agenda'}"
                                        class="bg-transparent text-slate-800 font-semibold focus:outline-none text-[11px]"
                                        onchange="onJamMulaiChange(${index}, this.value)">
                                    <span class="text-slate-400 font-bold">-</span>
                                    <input type="time" 
                                        name="stops[${index}][jam_selesai]" 
                                        id="jam_selesai_${index}"
                                        value="${stop.jam_selesai || '10:00'}" 
                                        min="${stop.jam_mulai || '08:00'}"
                                        required
                                        title="Jam selesai agenda"
                                        class="bg-transparent text-slate-800 font-semibold focus:outline-none text-[11px]"
                                        onchange="onJamSelesaiChange(${index}, this.value)">
                                </div>

                                <!-- Detail / Deskripsi Agenda -->
                                <div class="grow">
                                    <input type="text" 
                                        name="stops[${index}][deskripsi]" 
                                        value="${stop.deskripsi || ''}" 
                                        placeholder="Detail agenda (contoh: Penjemputan, Makan Siang, Foto Bersama)"
                                        class="w-full px-2.5 py-1 border border-slate-200 rounded-xl text-slate-700 bg-slate-50/50 focus:outline-none focus:bg-white focus:border-teal-500 text-[11px]"
                                        oninput="jadwalStops[${index}].deskripsi = this.value">
                                </div>
                            </div>

                            <!-- Conflict or Sequence Guide Indicator -->
                            ${isConflict ? `
                                <div class="flex flex-wrap items-center justify-between gap-1 text-[10px] bg-amber-50 px-2 py-1 rounded-lg border border-amber-200">
                                    <span class="text-amber-800 font-semibold">
                                        Peringatan: Jam mulai (${stop.jam_mulai}) bentrok! Agenda #${index} selesai pukul <strong>${prevEndTime}</strong>.
                                    </span>
                                    <button type="button" 
                                        onclick="adjustStopStartTime(${index}, '${prevEndTime}')" 
                                        class="px-2 py-0.5 rounded-md bg-teal-600 hover:bg-teal-700 text-white font-bold cursor-pointer transition shadow-2xs">
                                        Sesuaikan ke ${prevEndTime}
                                    </button>
                                </div>
                            ` : (index > 0 ? `
                                <div class="text-[10px] text-slate-400 flex items-center gap-1 pl-1">
                                    <span>↪ Agenda dimulai setelah agenda #${index} selesai (pukul <strong>${prevEndTime}</strong>)</span>
                                </div>
                            ` : '')}
                        </div>

                    </div>

                    <!-- Remove Button -->
                    <button type="button" 
                        onclick="removeJadwalStop(${index})" 
                        class="p-1 rounded-full text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition cursor-pointer shrink-0 mt-2 ${jadwalStops.length <= 1 ? 'hidden' : ''}" 
                        title="Hapus tujuan ini">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                `;

                container.appendChild(row);
            });
        }

        // OpenStreetMap Search with Debounce & Suggestions
        function onOsmSearchInput(index, query) {
            jadwalStops[index].lokasi = query;

            const dropdown = document.getElementById(`osm_dropdown_${index}`);
            const spinner = document.getElementById(`osm_spinner_${index}`);
            const icon = document.getElementById(`osm_icon_${index}`);

            if (!query || query.trim().length < 2) {
                if (dropdown) dropdown.classList.add('hidden');
                if (spinner) spinner.classList.add('hidden');
                if (icon) icon.classList.remove('hidden');
                return;
            }

            clearTimeout(osmDebounceTimer);
            if (spinner) spinner.classList.remove('hidden');
            if (icon) icon.classList.add('hidden');

            osmDebounceTimer = setTimeout(() => {
                fetchOsmNominatim(index, query.trim());
            }, 350);
        }

        async function fetchOsmNominatim(index, query) {
            const dropdown = document.getElementById(`osm_dropdown_${index}`);
            const spinner = document.getElementById(`osm_spinner_${index}`);
            const icon = document.getElementById(`osm_icon_${index}`);

            try {
                // 1. Check local internal destination database matches first
                const localMatches = destinasiWisataData.filter(d => 
                    d.nama_tempat.toLowerCase().includes(query.toLowerCase()) ||
                    (d.kota && d.kota.toLowerCase().includes(query.toLowerCase()))
                ).slice(0, 3);

                // 2. Fetch OpenStreetMap Nominatim results
                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&addressdetails=1&limit=5`;
                const response = await fetch(url, {
                    headers: { 'Accept-Language': 'id,en' }
                });
                const osmResults = await response.json();

                let html = '';

                // Render local destination matches
                if (localMatches.length > 0) {
                    localMatches.forEach(item => {
                        const title = `${item.nama_tempat}`;
                        const sub = `Destinasi Wisata • ${item.kota || 'Indonesia'}`;
                        const latVal = (item.latitude !== null && item.latitude !== undefined) ? item.latitude : 'null';
                        const lngVal = (item.longitude !== null && item.longitude !== undefined) ? item.longitude : 'null';
                        html += `
                            <div class="p-2.5 hover:bg-teal-50 cursor-pointer flex items-center gap-2.5 transition text-left" 
                                onclick="selectLocationOption(${index}, '${escapeQuotes(title)}', '${item.id}', ${latVal}, ${lngVal})">
                                <div class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center shrink-0 text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-xs font-bold text-slate-800 truncate">${title}</div>
                                    <div class="text-[10px] text-slate-400 truncate">${sub}</div>
                                </div>
                            </div>
                        `;
                    });
                }

                // Render OpenStreetMap results
                if (osmResults && osmResults.length > 0) {
                    osmResults.forEach(item => {
                        const parts = item.display_name.split(',');
                        const mainTitle = parts[0] || item.name || query;
                        const subAddress = parts.slice(1, 4).join(',').trim();
                        const latVal = item.lat ? item.lat : 'null';
                        const lonVal = item.lon ? item.lon : 'null';

                        html += `
                            <div class="p-2.5 hover:bg-slate-50 cursor-pointer flex items-center gap-2.5 transition text-left" 
                                onclick="selectLocationOption(${index}, '${escapeQuotes(mainTitle)}', null, ${latVal}, ${lonVal})">
                                <div class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-xs font-semibold text-slate-800 truncate">${mainTitle}</div>
                                    <div class="text-[10px] text-slate-400 truncate">${subAddress || item.display_name}</div>
                                </div>
                            </div>
                        `;
                    });
                }

                // If no results found at all
                if (!html) {
                    html = `
                        <div class="p-3 text-center text-xs text-slate-600 hover:bg-slate-100 cursor-pointer font-medium transition" onclick="selectLocationOption(${index}, '${escapeQuotes(query)}', null, null, null)">
                            Gunakan "<strong>${query}</strong>" sebagai nama lokasi kustom
                        </div>
                    `;
                }

                dropdown.innerHTML = html;
                dropdown.classList.remove('hidden');

            } catch (err) {
                console.warn('Nominatim error:', err);
                if (dropdown) dropdown.classList.add('hidden');
            } finally {
                if (spinner) spinner.classList.add('hidden');
                if (icon) icon.classList.remove('hidden');
            }
        }

        function escapeQuotes(str) {
            return str.replace(/'/g, "\\'").replace(/"/g, '&quot;');
        }

        function selectLocationOption(index, locationName, destinasiId = null, lat = null, lon = null) {
            jadwalStops[index].lokasi = locationName;
            jadwalStops[index].destinasi_id = destinasiId || '';
            jadwalStops[index].latitude = (lat !== null && lat !== undefined && lat !== '') ? parseFloat(lat) : null;
            jadwalStops[index].longitude = (lon !== null && lon !== undefined && lon !== '') ? parseFloat(lon) : null;

            const input = document.getElementById(`osm_input_${index}`);
            const hiddenDest = document.getElementById(`destinasi_id_${index}`);
            const hiddenLat = document.getElementById(`latitude_${index}`);
            const hiddenLng = document.getElementById(`longitude_${index}`);
            const dropdown = document.getElementById(`osm_dropdown_${index}`);

            if (input) input.value = locationName;
            if (hiddenDest) hiddenDest.value = destinasiId || '';
            if (hiddenLat) hiddenLat.value = (lat !== null && lat !== undefined) ? lat : '';
            if (hiddenLng) hiddenLng.value = (lon !== null && lon !== undefined) ? lon : '';
            if (dropdown) dropdown.classList.add('hidden');

            updateCoordBadge(index);
        }

        function updateCoordBadge(index) {
            const badge = document.getElementById(`coord_badge_${index}`);
            if (!badge) return;
            const stop = jadwalStops[index];
            if (stop && stop.latitude && stop.longitude) {
                badge.className = 'shrink-0';
                badge.innerHTML = `<span class="inline-flex items-center gap-1 text-[9px] text-teal-700 bg-teal-50 px-1.5 py-0.5 rounded font-semibold border border-teal-100" title="Koordinat: ${stop.latitude}, ${stop.longitude}">Terhubung Peta (${Number(stop.latitude).toFixed(3)}, ${Number(stop.longitude).toFixed(3)})</span>`;
            } else {
                badge.className = 'shrink-0';
                badge.innerHTML = `<span class="inline-flex items-center gap-1 text-[9px] text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded font-medium border border-slate-200">Lokasi Kustom</span>`;
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#jadwalStopsContainer')) {
                document.querySelectorAll('[id^="osm_dropdown_"]').forEach(d => d.classList.add('hidden'));
            }
        });

        function addJadwalStop() {
            hideJadwalAlert();
            const lastIndex = jadwalStops.length - 1;
            let nextStart = '08:00';
            let nextEnd = '10:00';
            if (lastIndex >= 0 && jadwalStops[lastIndex].jam_selesai) {
                // Agenda baru otomatis dimulai tepat setelah agenda sebelumnya selesai
                nextStart = jadwalStops[lastIndex].jam_selesai;
                const nextStartMin = timeToMinutes(nextStart);
                nextEnd = minutesToTime(nextStartMin + 90);
            }

            jadwalStops.push({ lokasi: '', deskripsi: '', destinasi_id: '', latitude: null, longitude: null, jam_mulai: nextStart, jam_selesai: nextEnd });
            renderJadwalStops();
        }

        function removeJadwalStop(index) {
            hideJadwalAlert();
            if (jadwalStops.length > 1) {
                jadwalStops.splice(index, 1);
                if (index < jadwalStops.length) {
                    cascadeAdjustSubsequentStops(Math.max(0, index - 1));
                }
                renderJadwalStops();
            }
        }

        // Initialize stops on page load and setup submit validation
        document.addEventListener('DOMContentLoaded', function() {
            renderJadwalStops();

            const formJadwal = document.getElementById('formJadwal');
            if (formJadwal) {
                formJadwal.addEventListener('submit', function(e) {
                    hideJadwalAlert();

                    for (let i = 0; i < jadwalStops.length; i++) {
                        const stop = jadwalStops[i];
                        const lokasi = (stop.lokasi || '').trim();
                        if (!lokasi) {
                            e.preventDefault();
                            showJadwalAlert(`Harap isi nama lokasi/tujuan untuk Agenda #${i + 1}.`);
                            const input = document.getElementById(`osm_input_${i}`);
                            if (input) input.focus();
                            return false;
                        }

                        if (timeToMinutes(stop.jam_selesai) <= timeToMinutes(stop.jam_mulai)) {
                            e.preventDefault();
                            showJadwalAlert(`Agenda #${i + 1} ("${lokasi}"): Jam selesai (${stop.jam_selesai}) harus lebih lambat dari jam mulai (${stop.jam_mulai}).`);
                            return false;
                        }

                        if (i > 0) {
                            const prevStop = jadwalStops[i - 1];
                            if (timeToMinutes(stop.jam_mulai) < timeToMinutes(prevStop.jam_selesai)) {
                                e.preventDefault();
                                showJadwalAlert(`Agenda #${i + 1} ("${lokasi}") tidak dapat dimulai pukul ${stop.jam_mulai} karena agenda sebelumnya ("${prevStop.lokasi || '#' + i}") baru selesai pukul ${prevStop.jam_selesai}.`);
                                const input = document.getElementById(`jam_mulai_${i}`);
                                if (input) input.focus();
                                return false;
                            }
                        }
                    }
                });
            }
        });

        function onPoBusChange() {
            const poSelect = document.getElementById('trip_po_id');
            const kendaraanSelect = document.getElementById('trip_kendaraan_id');
            const supirSelect = document.getElementById('trip_supir_po_id');
            const badge = document.getElementById('kendaraanCountBadge');
            const manualContainer = document.getElementById('manualBusContainer');
            const manualInput = document.getElementById('trip_nomor_bus_manual');

            const selectedPoId = poSelect.value;

            // Reset
            kendaraanSelect.innerHTML = '<option value="">-- Pilih Unit Armada Kendaraan --</option>';
            supirSelect.innerHTML = '<option value="">-- Pilih Supir PO --</option>';

            if (!selectedPoId) {
                badge.textContent = 'Pilih PO terlebih dahulu';
                badge.className = 'text-[10px] text-slate-400 font-normal';
                manualContainer.classList.add('hidden');
                manualInput.required = false;
                return;
            }

            const po = poBusData.find(item => item.id == selectedPoId);
            if (po) {
                const kList = po.kendaraan || [];
                const sList = po.supir || [];

                let availableKendaraanCount = 0;

                if (kList.length > 0) {
                    kList.forEach(k => {
                        const opt = document.createElement('option');
                        opt.value = k.id;
                        const plat = k.plat_nomor ? ` (${k.plat_nomor})` : '';
                        const seat = k.kapasitas_kursi ? ` [${k.kapasitas_kursi} Kursi]` : '';

                        const isAlreadyInTrip = alreadyInTripKendaraanIdsData.includes(k.id);
                        const isBusy = busyKendaraanData && busyKendaraanData[k.id];

                        if (isAlreadyInTrip) {
                            opt.disabled = true;
                            opt.textContent = `${k.nomor_bus}${plat} — (Sudah Dialokasikan di Trip Ini)`;
                            opt.className = 'text-slate-400 bg-slate-100';
                        } else if (isBusy) {
                            opt.disabled = true;
                            opt.textContent = `${k.nomor_bus}${plat} — [Sedang Bertugas di "${isBusy.trip_name}"]`;
                            opt.className = 'text-amber-700 bg-amber-50 font-medium';
                        } else {
                            availableKendaraanCount++;
                            opt.textContent = `${k.nomor_bus}${plat} - ${k.tipe_bus || 'Bus'}${seat} (Tersedia)`;
                            opt.className = 'text-slate-800 font-semibold';
                        }

                        kendaraanSelect.appendChild(opt);
                    });

                    if (availableKendaraanCount > 0) {
                        badge.textContent = `${availableKendaraanCount} dari ${kList.length} unit bus siap bertugas`;
                        badge.className = 'text-[10px] text-emerald-600 font-bold';
                    } else {
                        badge.textContent = `0 dari ${kList.length} unit tersedia (Semua bus sedang bertugas pada tanggal ini)`;
                        badge.className = 'text-[10px] text-rose-600 font-bold';
                    }

                    manualContainer.classList.add('hidden');
                    manualInput.required = false;
                } else {
                    badge.textContent = 'Belum ada unit bus terdaftar di PO ini';
                    badge.className = 'text-[10px] text-slate-400 font-normal';
                    manualContainer.classList.remove('hidden');
                    manualInput.required = true;
                }

                if (sList.length > 0) {
                    sList.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s.id;
                        const phone = s.kontak ? ` (${s.kontak})` : (s.no_hp ? ` (${s.no_hp})` : '');
                        const peran = s.peran ? ` [${s.peran}]` : ' [Supir Utama]';

                        const isAlreadyInTrip = alreadyInTripSupirPoIdsData.includes(s.id);
                        const isBusy = busySupirPoData && busySupirPoData[s.id];

                        if (isAlreadyInTrip) {
                            opt.disabled = true;
                            opt.textContent = `${s.nama_supir}${peran} — (Sudah Terdaftar di Trip Ini)`;
                            opt.className = 'text-slate-400 bg-slate-100';
                        } else if (isBusy) {
                            opt.disabled = true;
                            opt.textContent = `${s.nama_supir}${peran} — [Sedang Bertugas di "${isBusy.trip_name}"]`;
                            opt.className = 'text-amber-700 bg-amber-50 font-medium';
                        } else {
                            opt.textContent = `${s.nama_supir}${peran}${phone} (Tersedia)`;
                            opt.className = 'text-slate-800';
                        }

                        supirSelect.appendChild(opt);
                    });
                }
            }
        }

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            if (id === 'modalJadwal') {
                jadwalStops = (existingJadwalData && existingJadwalData.length > 0)
                    ? JSON.parse(JSON.stringify(existingJadwalData))
                    : JSON.parse(JSON.stringify(defaultEmptyStops));
                renderJadwalStops();
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        const qrPayload = JSON.stringify({
            app: "DaraGo",
            action: "join_trip",
            trip_id: {{ $trip->id }},
            trip_code: "DARAGO-TRIP-{{ $trip->id }}",
            nama_kegiatan: @json($trip->nama_kegiatan),
            biro: @json($trip->biro?->nama_biro ?? '')
        });

        const encodedData = encodeURIComponent(qrPayload);
        const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodedData}&color=1e1b4b&bgcolor=ffffff&margin=10`;

        function openQrModal() {
            document.getElementById('qrCodeImg').src = qrApiUrl;
            document.getElementById('qrModal').classList.remove('hidden');
        }

        function closeQrModal() {
            document.getElementById('qrModal').classList.add('hidden');
        }

        function copyJoinCode() {
            const code = document.getElementById('joinCodeText').innerText;
            navigator.clipboard.writeText(code).then(() => {
                const copyText = document.getElementById('copyBtnText');
                copyText.innerText = 'Tersalin!';
                setTimeout(() => copyText.innerText = 'Salin Kode', 2000);
            });
        }

        function downloadQrCode() {
            const a = document.createElement('a');
            a.href = qrApiUrl;
            a.download = `QR-Code-Trip-${ @json($trip->id) }.png`;
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeQrModal();
                closeModal('modalArmada');
                closeModal('modalJadwal');
                closeModal('modalManifest');
            }
        });
    </script>
@endsection

@push('scripts')
<script>
    // ─── Route Waypoints Data (from PHP) ───────────────────────────────────────
    const routeWaypoints = @json($validRouteStops);

    // ─── Leaflet Map State ─────────────────────────────────────────────────────
    let itineraryMap = null;
    let routePolyline = null;

    // ─── Init Map ──────────────────────────────────────────────────────────────
    function initItineraryMap() {
        const mapEl = document.getElementById('itineraryRouteMap');
        if (!mapEl) return;

        if (routeWaypoints.length === 0) {
            mapEl.style.display = 'flex';
            mapEl.style.flexDirection = 'column';
            mapEl.style.alignItems = 'center';
            mapEl.style.justifyContent = 'center';
            mapEl.style.gap = '6px';
            mapEl.style.color = '#94a3b8';
            mapEl.style.fontSize = '11px';
            mapEl.style.padding = '20px';
            mapEl.innerHTML = '<span style="text-align:center">Belum ada titik agenda dengan koordinat peta.<br>Pilih lokasi dari hasil pencarian OSM saat mengisi jadwal acara.</span>';
            renderRouteNotice();
            return;
        }

        const centerLat = parseFloat(routeWaypoints[0].latitude);
        const centerLng = parseFloat(routeWaypoints[0].longitude);

        itineraryMap = L.map('itineraryRouteMap', {
            zoomControl: true,
            scrollWheelZoom: true,
        }).setView([centerLat, centerLng], 12);

        // OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(itineraryMap);

        // Marker colour palette
        const palette = ['#0f766e', '#0284c7', '#7c3aed', '#b45309', '#dc2626', '#0369a1', '#059669'];
        const bounds = [];

        routeWaypoints.forEach((stop, i) => {
            const isLast = i === routeWaypoints.length - 1;
            const color = isLast ? '#dc2626' : palette[i % palette.length];
            const num = i + 1;

            const icon = L.divIcon({
                className: '',
                html: `<div class="custom-pin-marker" style="background:${color};width:28px;height:28px;font-size:12px;">${num}</div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 14],
                popupAnchor: [0, -16],
            });

            const jamInfo = (stop.jam_mulai && stop.jam_selesai)
                ? `${stop.jam_mulai} – ${stop.jam_selesai} WIB`
                : '';
            const descPart = stop.deskripsi
                ? `<div style="font-size:11px;color:#64748b;margin-top:2px">${stop.deskripsi}</div>`
                : '';
            const timePart = jamInfo
                ? `<div style="font-size:10px;color:#0f766e;margin-top:3px;font-weight:600">${jamInfo}</div>`
                : '';

            L.marker([parseFloat(stop.latitude), parseFloat(stop.longitude)], { icon })
                .addTo(itineraryMap)
                .bindPopup(`<div style="min-width:160px;padding:2px 0"><strong style="font-size:12px;color:#0f172a">${num}. ${stop.lokasi}</strong>${descPart}${timePart}</div>`);

            bounds.push([parseFloat(stop.latitude), parseFloat(stop.longitude)]);
        });

        // Fit all markers in view
        if (bounds.length > 1) {
            itineraryMap.fitBounds(bounds, { padding: [40, 40] });
        }

        // Draw road route if ≥2 waypoints
        if (routeWaypoints.length >= 2) {
            fetchOsrmRoute();
        }

        renderRouteNotice();
        buildGoogleMapsLink();
    }

    // ─── OSRM Road-Route Fetcher ───────────────────────────────────────────────
    async function fetchOsrmRoute() {
        const overlay = document.getElementById('routeLoadingOverlay');
        if (overlay) overlay.classList.remove('hidden');

        try {
            const coords = routeWaypoints
                .map(s => `${parseFloat(s.longitude)},${parseFloat(s.latitude)}`)
                .join(';');
            const url = `https://router.project-osrm.org/route/v1/driving/${coords}?overview=full&geometries=geojson`;

            const res = await fetch(url, { signal: AbortSignal.timeout(12000) });
            const data = await res.json();

            if (data.code === 'Ok' && data.routes && data.routes[0]) {
                const route = data.routes[0];

                // Draw dashed polyline
                if (routePolyline) itineraryMap.removeLayer(routePolyline);
                routePolyline = L.geoJSON(route.geometry, {
                    style: { color: '#0f766e', weight: 4, opacity: 0.80, dashArray: '10,5' },
                }).addTo(itineraryMap);

                // Re-fit including route
                const routeBounds = L.geoJSON(route.geometry).getBounds();
                if (routeBounds.isValid()) {
                    itineraryMap.fitBounds(routeBounds, { padding: [40, 40] });
                }

                // Show metrics
                const distKm = (route.distance / 1000).toFixed(1);
                const totalMin = Math.round(route.duration / 60);
                const durText = totalMin >= 60
                    ? `${Math.floor(totalMin / 60)} jam ${totalMin % 60} mnt`
                    : `${totalMin} menit`;

                const distEl = document.getElementById('routeTotalDistance');
                const durEl  = document.getElementById('routeTotalDuration');
                if (distEl) distEl.textContent = `${distKm} km`;
                if (durEl)  durEl.textContent  = durText;
            }
        } catch (err) {
            console.warn('[OSRM] Routing error:', err);
            // Map still shows with point-to-point straight lines as fallback
            if (routeWaypoints.length >= 2) {
                const latlngs = routeWaypoints.map(s => [parseFloat(s.latitude), parseFloat(s.longitude)]);
                if (routePolyline) itineraryMap.removeLayer(routePolyline);
                routePolyline = L.polyline(latlngs, {
                    color: '#0f766e', weight: 3, opacity: 0.65, dashArray: '6,6',
                }).addTo(itineraryMap);
            }
        } finally {
            if (overlay) overlay.classList.add('hidden');
        }
    }

    // ─── Notice Box ────────────────────────────────────────────────────────────
    function renderRouteNotice() {
        const noticeEl = document.getElementById('routeNoticeBox');
        if (!noticeEl) return;

        const totalStops  = (typeof existingJadwalData !== 'undefined') ? existingJadwalData.length : 0;
        const linkedStops = routeWaypoints.length;

        if (linkedStops === 0) {
            noticeEl.innerHTML = '<span style="color:#f59e0b">Tidak ada titik agenda yang terhubung ke peta. Pilih lokasi dari pencarian OSM saat menyimpan jadwal.</span>';
        } else if (linkedStops < totalStops) {
            noticeEl.innerHTML = `<span style="color:#d97706">${totalStops - linkedStops} dari ${totalStops} agenda belum memiliki koordinat peta. Perbarui jadwal dan pilih lokasi melalui pencarian untuk melengkapinya.</span>`;
        } else {
            noticeEl.innerHTML = `<span style="color:#0f766e">Semua ${linkedStops} titik agenda terhubung ke peta rute.</span>`;
        }
    }

    // ─── Google Maps Full-Route Link ───────────────────────────────────────────
    function buildGoogleMapsLink() {
        const btn = document.getElementById('googleMapsAllBtn');
        if (!btn || routeWaypoints.length === 0) return;

        const first = routeWaypoints[0];
        const last  = routeWaypoints[routeWaypoints.length - 1];

        const origin = `${parseFloat(first.latitude)},${parseFloat(first.longitude)}`;
        const dest   = `${parseFloat(last.latitude)},${parseFloat(last.longitude)}`;
        const middle = routeWaypoints.slice(1, -1)
            .map(s => `${parseFloat(s.latitude)},${parseFloat(s.longitude)}`)
            .join('|');

        let gmUrl = `https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${dest}`;
        if (middle) gmUrl += `&waypoints=${encodeURIComponent(middle)}`;
        gmUrl += '&travelmode=driving';

        btn.innerHTML = `<a href="${gmUrl}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-[10px] font-bold transition shadow-sm">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/></svg>
            Navigasi Semua Rute ↗
        </a>`;
    }

    // ─── Fit Bounds Helper (called by reset-zoom button) ──────────────────────
    function fitRouteMapBounds() {
        if (!itineraryMap || routeWaypoints.length === 0) return;
        const bounds = routeWaypoints.map(s => [parseFloat(s.latitude), parseFloat(s.longitude)]);
        itineraryMap.fitBounds(bounds, { padding: [40, 40] });
    }

    // ─── Boot ──────────────────────────────────────────────────────────────────
    // @stack('scripts') runs after DOMContentLoaded fires, so call directly.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initItineraryMap);
    } else {
        initItineraryMap();
    }
</script>
@endpush
