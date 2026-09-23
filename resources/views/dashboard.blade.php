<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name', 'DaraGo') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Top Navigation -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-2xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-sm shadow-indigo-500/20">
                    D
                </div>
                <div>
                    <span class="font-bold text-slate-900 tracking-tight text-base">DaraGo</span>
                    <span class="text-xs text-slate-400 block -mt-0.5">Control Panel</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <!-- User Profile Pill -->
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-semibold flex items-center justify-center text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <div class="text-xs font-semibold text-slate-800 leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[11px] text-slate-400">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button 
                        type="submit" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg border border-slate-200 hover:border-red-200 transition duration-150 cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="grow max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Welcome Banner -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-medium text-slate-400">Selamat datang,</span>
                        @if(auth()->user()->role === 'superadmin')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">
                                👑 Super Admin
                            </span>
                        @elseif(auth()->user()->role === 'admin')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                🛡️ Admin
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                👤 User
                            </span>
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Anda berhasil login ke sistem DaraGo menggunakan akun yang tersimpan di database.</p>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-center">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Session Aktif
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Account Info -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 mb-1">Informasi Akun</h3>
                <div class="space-y-1 text-xs text-slate-500">
                    <div><span class="text-slate-400">ID Database:</span> #{{ auth()->user()->id }}</div>
                    <div><span class="text-slate-400">Email:</span> {{ auth()->user()->email }}</div>
                    <div><span class="text-slate-400">Role:</span> <strong class="text-slate-700 uppercase">{{ auth()->user()->role }}</strong></div>
                </div>
            </div>

            <!-- Database Status -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 mb-1">Status Database</h3>
                <div class="space-y-1 text-xs text-slate-500">
                    <div><span class="text-slate-400">Koneksi:</span> PostgreSQL (Neon)</div>
                    <div><span class="text-slate-400">Tabel:</span> users</div>
                    <div><span class="text-slate-400">Keamanan Password:</span> BCRYPT (Hashed)</div>
                </div>
            </div>

            <!-- Permissions / Scope -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 mb-1">Akses & Hak Istimewa</h3>
                <div class="space-y-1 text-xs text-slate-500">
                    @if(auth()->user()->isSuperAdmin())
                        <div class="text-purple-700 font-medium">✓ Akses Penuh Superadministrator</div>
                        <div>✓ Manajemen Pengguna & Admin</div>
                        <div>✓ Konfigurasi Sistem</div>
                    @else
                        <div class="text-blue-700 font-medium">✓ Akses Administrator</div>
                        <div>✓ Manajemen Data Operasional</div>
                        <div>— Pembatasan Pengaturan Root</div>
                    @endif
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-slate-200/80 py-4 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} DaraGo. Sistem Autentikasi Laravel 12 & Tailwind CSS.
    </footer>
</body>
</html>
