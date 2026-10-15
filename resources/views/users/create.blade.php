@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')

@section('content')
    <div class="max-w-2xl">
        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1">
            <a href="{{ route('users.index') }}" class="hover:underline">Manajemen Pengguna</a>
            <span>/</span>
            <span class="text-slate-600">Tambah Baru</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
            Tambah Pengguna Baru
        </h1>
        <p class="text-xs text-slate-400 mt-0.5 mb-6">
            Buat akun pengguna baru dan tentukan peran serta hak akses sistem
        </p>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Admin Baru" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('name') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="user@darago.id" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('email') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
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
                        <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">Peran (Role Sistem) <span class="text-red-500">*</span></label>
                        <select name="role" id="role" required class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('role') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User (Peserta / Driver)</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Pengelola Operasional / TL)</option>
                            <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Super Admin (Akses Penuh)</option>
                        </select>
                        @error('role') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="08123456789" class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border @error('no_hp') border-red-300 @else border-slate-200 @enderror rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('no_hp') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 text-xs font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-[#223268] hover:bg-[#1a2754] text-white text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
