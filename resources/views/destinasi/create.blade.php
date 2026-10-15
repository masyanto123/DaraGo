@extends('layouts.admin')

@section('title', 'Tambah Destinasi Wisata')

@section('content')
    <div class="max-w-2xl">
        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
            <a href="{{ route('destinasi.index') }}" class="hover:underline">Destinasi Wisata</a>
            <span>/</span>
            <span class="text-slate-600">Tambah Baru</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
            Tambah Destinasi Wisata
        </h1>
        <p class="text-xs text-slate-400 mt-0.5 mb-6">
            Daftarkan tempat wisata baru beserta koordinat GPS dan aturan kunjungan
        </p>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <form method="POST" action="{{ route('destinasi.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="nama_tempat" class="block text-xs font-semibold text-slate-700 mb-1">Nama Tempat Wisata <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_tempat" id="nama_tempat" value="{{ old('nama_tempat') }}" required placeholder="Contoh: Candi Borobudur" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('nama_tempat') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('nama_tempat') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-xs font-semibold text-slate-700 mb-1">Latitude</label>
                        <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}" placeholder="-7.6078738" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('latitude') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('latitude') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-xs font-semibold text-slate-700 mb-1">Longitude</label>
                        <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}" placeholder="110.2037513" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('longitude') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('longitude') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi & Gambaran Tempat</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" placeholder="Informasi daya tarik, sejarah singkat, fasilitas umum..." class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('deskripsi') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="aturan_lokal" class="block text-xs font-semibold text-slate-700 mb-1">Aturan Lokal / Protokol Wisatawan</label>
                    <textarea name="aturan_lokal" id="aturan_lokal" rows="2" placeholder="Contoh: Wajib memakai alas kaki khusus, dilarang merokok..." class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('aturan_lokal') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">{{ old('aturan_lokal') }}</textarea>
                    @error('aturan_lokal') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('destinasi.index') }}" class="px-4 py-2 text-xs font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-[#223268] hover:bg-[#1a2754] text-white text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer">
                        Simpan Destinasi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
