@extends('layouts.admin')

@section('title', 'Tambah Karyawan / Crew')

@section('content')
    <div class="max-w-2xl">
        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
            <a href="{{ route('karyawan.index') }}" class="hover:underline">Karyawan & Crew</a>
            <span>/</span>
            <span class="text-slate-600">Tambah Baru</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
            Tambah Karyawan & Crew Lapangan
        </h1>
        <p class="text-xs text-slate-400 mt-0.5 mb-6">
            Pilih kategori personil (Karyawan Biro atau Driver PO Bus) untuk menghubungkannya dengan data master.
        </p>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <form method="POST" action="{{ route('karyawan.store') }}" class="space-y-4">
                @csrf

                <!-- 1. KATEGORI PERSONIL -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Kategori Personil <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="relative flex items-center p-3.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/40">
                            <input type="radio" name="kategori_personil" value="karyawan_biro" onchange="toggleKategoriPersonil()" {{ old('kategori_personil', 'karyawan_biro') === 'karyawan_biro' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                            <div class="ml-3">
                                <span class="block text-xs font-bold text-slate-900">Karyawan / Staf Biro</span>
                                <span class="block text-[10px] text-slate-500">Tour Leader, Admin & Staf Biro</span>
                            </div>
                        </label>

                        <label class="relative flex items-center p-3.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/40">
                            <input type="radio" name="kategori_personil" value="driver_po" onchange="toggleKategoriPersonil()" {{ old('kategori_personil') === 'driver_po' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                            <div class="ml-3">
                                <span class="block text-xs font-bold text-slate-900">Karyawan PO Bus</span>
                                <span class="block text-[10px] text-slate-500">Admin PO, Supir Utama & Co-Supir</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Rian Hidayat / Pak Joko Susilo" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('name') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email (Login Akun) <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="driver@darago.id" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('email') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('password') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="08123456789" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('no_hp') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('no_hp') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">Hak Akses Sistem <span class="text-red-500">*</span></label>
                        <select name="role" id="role" required onchange="toggleKategoriPersonil()" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('role') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Staf Pengelola Biro / PO)</option>
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User (Driver / Crew Lapangan)</option>
                        </select>
                        @error('role') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- DYNAMIC FIELD 1: AFILIASI BIRO PERJALANAN (Untuk Karyawan Biro) -->
                <div id="containerBiro">
                    <label for="biro_id" class="block text-xs font-semibold text-slate-700 mb-1">Biro Perjalanan <span class="text-red-500">*</span></label>
                    <select name="biro_id" id="biro_id" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('biro_id') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition font-medium">
                        <option value="">-- Pilih Biro Perjalanan --</option>
                        @foreach($biros as $b)
                            <option value="{{ $b->id }}" {{ old('biro_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->nama_biro }}
                            </option>
                        @endforeach
                    </select>
                    @if($biros->isEmpty())
                        <p class="text-[11px] text-amber-600 mt-1">
                            Belum ada Biro Perjalanan terdaftar. Tambahkan di menu <a href="{{ route('biro.create') }}" class="underline font-bold">Data Master Biro</a>.
                        </p>
                    @endif
                    @error('biro_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- DYNAMIC FIELD 2: AFILIASI PO BUS & PERAN DRIVER (Untuk Driver PO) -->
                <div id="containerPo" class="hidden space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="po_id" class="block text-xs font-semibold text-slate-700 mb-1">Perusahaan Otobus (PO Bus) <span class="text-red-500">*</span></label>
                            <select name="po_id" id="po_id" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('po_id') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition font-medium">
                                <option value="">-- Pilih PO Bus dari Data Master --</option>
                                @foreach($poList as $po)
                                    <option value="{{ $po->id }}" {{ old('po_id') == $po->id ? 'selected' : '' }}>
                                        {{ $po->nama_po }}
                                    </option>
                                @endforeach
                            </select>
                            @if($poList->isEmpty())
                                <p class="text-[11px] text-amber-600 mt-1">
                                    Belum ada PO Bus terdaftar. Tambahkan di menu <a href="{{ route('armada.create') }}" target="_blank" class="underline font-bold">Data Master Armada Bus</a>.
                                </p>
                            @endif
                            @error('po_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="peran_driver" class="block text-xs font-semibold text-slate-700 mb-1">Peran Driver PO <span id="peranDriverRequired" class="text-red-500">*</span></label>
                            <select name="peran_driver" id="peran_driver" disabled class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition font-medium disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400">
                                <option value="Supir Utama" {{ old('peran_driver') === 'Supir Utama' ? 'selected' : '' }}>Supir Utama (Driver 1)</option>
                                <option value="Co-Supir" {{ old('peran_driver') === 'Co-Supir' ? 'selected' : '' }}>Co-Supir / Kernet (Driver 2)</option>
                            </select>
                        </div>
                    </div>
                    <div id="driverInfo" class="hidden p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-[11px] text-emerald-800 flex items-center gap-2">
                        <span>Driver yang didaftarkan di sini akan <strong>otomatis tersambung</strong> dan muncul di daftar <strong>Anakan Data: Supir PO</strong> pada menu Armada Bus.</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('karyawan.index') }}" class="px-4 py-2 text-xs font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-[#223268] hover:bg-[#1a2754] text-white text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleKategoriPersonil() {
            const selected = document.querySelector('input[name="kategori_personil"]:checked').value;
            const containerBiro = document.getElementById('containerBiro');
            const selectBiro = document.getElementById('biro_id');
            const containerPo = document.getElementById('containerPo');
            const selectPo = document.getElementById('po_id');
            const selectRole = document.getElementById('role');
            const selectPeranDriver = document.getElementById('peran_driver');
            const peranDriverRequired = document.getElementById('peranDriverRequired');
            const driverInfo = document.getElementById('driverInfo');
            const isDriver = selected === 'driver_po' && selectRole.value === 'user';

            if (selected === 'driver_po') {
                containerBiro.classList.add('hidden');
                selectBiro.required = false;

                containerPo.classList.remove('hidden');
                selectPo.required = true;
            } else {
                containerPo.classList.add('hidden');
                selectPo.required = false;

                containerBiro.classList.remove('hidden');
                selectBiro.required = true;
            }

            selectPeranDriver.disabled = !isDriver;
            selectPeranDriver.required = isDriver;
            peranDriverRequired.classList.toggle('hidden', !isDriver);
            driverInfo.classList.toggle('hidden', !isDriver);
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleKategoriPersonil();
        });
    </script>
@endsection
