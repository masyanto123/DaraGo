@extends('layouts.admin')

@section('title', 'Detail PO Bus: ' . $po->nama_po)

@section('content')
    <div class="space-y-6">

        <!-- Top Header & Breadcrumb -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
                    <a href="{{ route('armada.index') }}" class="hover:underline">PO & Armada Bus</a>
                    <span>/</span>
                    <span class="text-slate-600">Detail PO</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                    </span>
                    <span>{{ $po->nama_po }}</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Kelola profil PO, data unit armada kendaraan, dan daftar supir yang tersedia untuk penugasan trip
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('armada.edit', $po) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                    <span>Edit Profil PO</span>
                </a>
            </div>
        </div>

        <!-- PO Info Snapshot Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">KONTAK & TELEPON</div>
                <div class="text-sm font-bold text-slate-800 mt-1 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    <span>{{ $po->kontak ?? 'Belum ada kontak' }}</span>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TOTAL UNIT KENDARAAN</div>
                <div class="text-xl font-bold text-slate-900 mt-1 text-blue-600">
                    {{ $po->kendaraan->count() }} <span class="text-xs font-normal text-slate-500">Unit Bus</span>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TOTAL SUPIR TERDAFTAR</div>
                <div class="text-xl font-bold text-slate-900 mt-1 text-emerald-600">
                    {{ $po->supir->count() }} <span class="text-xs font-normal text-slate-500">Supir / Driver</span>
                </div>
            </div>
        </div>

        @if($po->alamat || $po->detail)
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs text-xs space-y-2">
                @if($po->alamat)
        @if($po->alamat || $po->detail)
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs text-xs space-y-2">
                @if($po->alamat)
                    <div>
                        <span class="font-bold text-slate-700">Alamat Garasi / Kantor:</span>
                        <span class="text-slate-600 ml-1">{{ $po->alamat }}</span>
                    </div>
                @endif
                @if($po->detail)
                    <div>
                        <span class="font-bold text-slate-700">Keterangan / Fasilitas PO:</span>
                        <span class="text-slate-600 ml-1">{{ $po->detail }}</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- 2 Column Section: Anakan Data Kendaraan & Anakan Data Supir -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- LEFT: ARMADA KENDARAAN BUS (7 cols) -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>Anakan Data: Armada Kendaraan (Unit Bus)</span>
                        </h3>
                        <p class="text-xs text-slate-400">
                            Daftar unit bus milik {{ $po->nama_po }} beserta nomor polisi dan kapasitas kursi
                        </p>
                    </div>
                    <button type="button" onclick="openModal('modalKendaraan')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <span>Tambah Kendaraan</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-2.5">Nomor Bus / Plat</th>
                                <th class="px-4 py-2.5">Tipe Bus & Fasilitas</th>
                                <th class="px-4 py-2.5 text-center">Kapasitas</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($po->kendaraan as $k)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-slate-900">{{ $k->nomor_bus }}</div>
                                        <div class="text-[10px] text-slate-400">ID Kendaraan #{{ $k->id }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-slate-800">{{ $k->tipe_bus ?? 'Standar Pariwisata' }}</div>
                                        <div class="text-[10px] text-slate-400 truncate max-w-xs" title="{{ $k->fasilitas }}">{{ $k->fasilitas ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px]">
                                            {{ $k->kapasitas ?? 50 }} Kursi
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <form method="POST" action="{{ route('armada.kendaraan.destroy', $k) }}" onsubmit="return confirm('Hapus unit kendaraan bus {{ $k->nomor_bus }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded text-slate-400 hover:text-red-600 hover:bg-red-50 transition cursor-pointer" title="Hapus Unit Kendaraan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-400">
                                        Belum ada data unit kendaraan bus di PO ini. Klik tombol "+ Tambah Kendaraan" di atas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RIGHT: SUPIR PO BUS (5 cols) - READ ONLY -->
            <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span>Anakan Data: Supir (Driver PO)</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-medium">Read Only</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Data supir tersinkronisasi otomatis dari Master Karyawan & Crew
                    </p>
                </div>

                <div class="space-y-3">
                    @forelse($po->supir as $s)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs flex items-center justify-between">
                            <div>
                                <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                    <span>{{ $s->nama_supir }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold {{ ($s->peran ?? 'Supir Utama') === 'Co-Supir' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $s->peran ?? 'Supir Utama' }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                                    <span>{{ $s->kontak ?? 'Belum ada kontak' }}</span>
                                </div>
                                @if($s->keterangan)
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $s->keterangan }}
                                    </div>
                                @endif
                            </div>
                            <span class="text-[10px] px-2 py-1 rounded bg-slate-200/70 text-slate-600 font-medium">
                                Tersambung
                            </span>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 text-center py-6">
                            Belum ada supir terdaftar untuk PO ini.<br>
                            <span class="text-[11px] text-slate-500 mt-1.5 inline-block">
                                Tambahkan supir baru dengan memilih PO ini di menu <a href="{{ route('karyawan.create') }}" class="text-indigo-600 underline font-semibold">Karyawan & Crew</a>.
                            </span>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- MODAL TAMBAH ARMADA KENDARAAN -->
    <div id="modalKendaraan" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900">Tambah Unit Kendaraan Bus</h3>
                <button type="button" onclick="closeModal('modalKendaraan')" class="p-1 rounded-full text-slate-400 hover:text-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('armada.kendaraan.store', $po) }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nomor Bus / Plat Kendaraan <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_bus" required placeholder="misal: BUS 01 (AB 7001 DA)" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Tipe / Karoseri Bus</label>
                    <input type="text" name="tipe_bus" placeholder="misal: Big Bus Jetbus 5 SHD, Medium Bus 35s, Sleeper" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Kapasitas Tempat Duduk (Seat)</label>
                    <input type="number" name="kapasitas" value="50" min="10" max="100" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Fasilitas Kendaraan</label>
                    <textarea name="fasilitas" rows="2" placeholder="misal: AC, Reclining Seat, Audio Karaoke, USB Charger, Toilet" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modalKendaraan')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-xs transition">Simpan Kendaraan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal('modalKendaraan');
            }
        });
    </script>
@endsection
