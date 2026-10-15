@extends('layouts.admin')

@section('title', 'Data Master Karyawan & Crew')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
                <span>Data Master</span>
                <span>/</span>
                <span class="text-slate-600">Karyawan & Crew</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Karyawan & Crew Lapangan
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Kelola data akun dan penugasan tour leader, driver/supir, dan staf operasional
            </p>
        </div>

        <a href="{{ route('karyawan.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#223268] hover:bg-[#1a2754] text-white text-xs font-semibold rounded-xl shadow-xs transition duration-150 cursor-pointer self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Karyawan / Crew</span>
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">ID</th>
                        <th class="px-6 py-3.5">Nama & Kontak</th>
                        <th class="px-6 py-3.5">Posisi / Jabatan</th>
                        <th class="px-6 py-3.5">Afiliasi Perusahaan (Biro / PO Bus)</th>
                        <th class="px-6 py-3.5">Role Sistem</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($karyawans as $karyawan)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-mono font-medium text-slate-400">#{{ $karyawan->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $karyawan->user?->name ?? 'User terhapus' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $karyawan->user?->email }} • {{ $karyawan->user?->no_hp ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-800 font-medium text-[11px]">
                                    {{ $karyawan->posisi }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                @if($karyawan->po_id)
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-semibold text-[10px]">🚌 PO: {{ $karyawan->po?->nama_po }}</span>
                                        @if($karyawan->supirPo)
                                            <span class="text-[9px] text-slate-400 font-mono">({{ $karyawan->supirPo->peran }})</span>
                                        @endif
                                    </div>
                                @elseif($karyawan->biro_id)
                                    <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-semibold text-[10px]">🏢 Biro: {{ $karyawan->biro?->nama_biro }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($karyawan->user?->role === 'admin')
                                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-semibold text-[10px]">Admin</span>
                                @elseif($karyawan->user?->role === 'superadmin')
                                    <span class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 font-semibold text-[10px]">Super Admin</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold text-[10px]">User</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('karyawan.edit', $karyawan) }}" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('karyawan.destroy', $karyawan) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data personil ini? Akun login terkait juga akan terhapus.');">
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
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                Belum ada data karyawan / crew.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($karyawans->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $karyawans->links() }}
            </div>
        @endif
    </div>
@endsection
