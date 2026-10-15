@extends('layouts.admin')

@section('title', 'Edit Peserta & Klien')

@section('content')
    <div class="max-w-2xl">
        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
            <a href="{{ route('peserta.index') }}" class="hover:underline">Peserta & Klien</a>
            <span>/</span>
            <span class="text-slate-600">Edit #{{ $peserta->id }}</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
            Edit Peserta & Klien
        </h1>
        <p class="text-xs text-slate-400 mt-0.5 mb-6">
            Perbarui data peserta {{ $peserta->user?->name }}
        </p>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <form method="POST" action="{{ route('peserta.update', $peserta) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Peserta <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $peserta->user?->name) }}" required class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('name') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email (Akun) <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $peserta->user?->email) }}" required class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('email') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password Baru (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" id="password" placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('password') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $peserta->user?->no_hp) }}" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('no_hp') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('no_hp') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="nama_instansi" class="block text-xs font-semibold text-slate-700 mb-1">Nama Asal Instansi / Sekolah <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_instansi" id="nama_instansi" value="{{ old('nama_instansi', $peserta->nama_instansi) }}" required class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('nama_instansi') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('nama_instansi') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="kontak_darurat" class="block text-xs font-semibold text-slate-700 mb-1">Kontak Darurat (Nama & No. Telp Keluarga)</label>
                    <input type="text" name="kontak_darurat" id="kontak_darurat" value="{{ old('kontak_darurat', $peserta->kontak_darurat) }}" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('kontak_darurat') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('kontak_darurat') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="catatan_medis" class="block text-xs font-semibold text-slate-700 mb-1">Catatan Medis & Alergi Khusus</label>
                    <textarea name="catatan_medis" id="catatan_medis" rows="2" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('catatan_medis') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">{{ old('catatan_medis', $peserta->catatan_medis) }}</textarea>
                    @error('catatan_medis') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('peserta.index') }}" class="px-4 py-2 text-xs font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-[#223268] hover:bg-[#1a2754] text-white text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer">
                        Perbarui Peserta
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
