@extends('layouts.admin')

@section('title', 'Edit Trip Perjalanan')

@section('content')
    <div class="max-w-2xl">
        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
            <a href="{{ route('trip.index') }}" class="hover:underline">Trip Perjalanan</a>
            <span>/</span>
            <span class="text-slate-600">Edit #{{ $trip->id }}</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
            Edit Trip Perjalanan
        </h1>
        <p class="text-xs text-slate-400 mt-0.5 mb-6">
            Perbarui data jadwal & status kegiatan trip
        </p>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <form method="POST" action="{{ route('trip.update', $trip) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="biro_id" class="block text-xs font-semibold text-slate-700 mb-1">Biro Perjalanan Penyelenggara <span class="text-red-500">*</span></label>
                    <select name="biro_id" id="biro_id" required class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('biro_id') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @foreach($biros as $b)
                            <option value="{{ $b->id }}" {{ old('biro_id', $trip->biro_id) == $b->id ? 'selected' : '' }}>
                                {{ $b->nama_biro }}
                            </option>
                        @endforeach
                    </select>
                    @error('biro_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="nama_kegiatan" class="block text-xs font-semibold text-slate-700 mb-1">Nama Kegiatan Trip <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan" value="{{ old('nama_kegiatan', $trip->nama_kegiatan) }}" required class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('nama_kegiatan') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('nama_kegiatan') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="tgl_mulai" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_mulai" id="tgl_mulai" value="{{ old('tgl_mulai', $trip->tgl_mulai->format('Y-m-d')) }}" required class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('tgl_mulai') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('tgl_mulai') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="tgl_selesai" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_selesai" id="tgl_selesai" value="{{ old('tgl_selesai', $trip->tgl_selesai->format('Y-m-d')) }}" required class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('tgl_selesai') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('tgl_selesai') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">Status Operasional <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('status') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        <option value="Upcoming" {{ old('status', $trip->status) === 'Upcoming' ? 'selected' : '' }}>Mendatang (Upcoming)</option>
                        <option value="Ongoing" {{ old('status', $trip->status) === 'Ongoing' ? 'selected' : '' }}>Sedang Berjalan (Ongoing)</option>
                        <option value="Completed" {{ old('status', $trip->status) === 'Completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                        <option value="Cancelled" {{ old('status', $trip->status) === 'Cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                    </select>
                    @error('status') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('trip.index') }}" class="px-4 py-2 text-xs font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-[#223268] hover:bg-[#1a2754] text-white text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer">
                        Perbarui Trip
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
