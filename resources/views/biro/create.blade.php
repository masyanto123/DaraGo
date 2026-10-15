@extends('layouts.admin')

@section('title', 'Tambah Biro Perjalanan')

@section('content')
    <div class="max-w-2xl">
        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
            <a href="{{ route('biro.index') }}" class="hover:underline">Biro Perjalanan</a>
            <span>/</span>
            <span class="text-slate-600">Tambah Baru</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
            Tambah Biro Perjalanan
        </h1>
        <p class="text-xs text-slate-400 mt-0.5 mb-6">
            Daftarkan mitra biro perjalanan baru ke dalam sistem
        </p>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <form method="POST" action="{{ route('biro.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="nama_biro" class="block text-xs font-semibold text-slate-700 mb-1">Nama Biro Perjalanan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_biro" id="nama_biro" value="{{ old('nama_biro') }}" required placeholder="Contoh: PT DaraGo Tour & Travel Nusantara" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('nama_biro') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('nama_biro') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="kontak" class="block text-xs font-semibold text-slate-700 mb-1">Kontak / Telepon / WhatsApp</label>
                    <input type="text" name="kontak" id="kontak" value="{{ old('kontak') }}" placeholder="Contoh: +62 812-3456-7890" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('kontak') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('kontak') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="alamat" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Kantor</label>
                    <textarea name="alamat" id="alamat" rows="3" placeholder="Alamat lengkap kantor biro..." class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('alamat') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">{{ old('alamat') }}</textarea>
                    @error('alamat') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="logo_url" class="block text-xs font-semibold text-slate-700 mb-1">URL Logo (Opsional)</label>
                    <input type="url" name="logo_url" id="logo_url" value="{{ old('logo_url') }}" placeholder="https://example.com/logo.png" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('logo_url') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('logo_url') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('biro.index') }}" class="px-4 py-2 text-xs font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-[#223268] hover:bg-[#1a2754] text-white text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer">
                        Simpan Biro
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
