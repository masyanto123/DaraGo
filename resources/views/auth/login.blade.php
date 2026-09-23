<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in - {{ config('app.name', 'DaraGo') }}</title>
    
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
<body class="h-full bg-[#f8fafc] text-slate-800 antialiased relative overflow-x-hidden flex flex-col justify-center items-center py-12 px-4 sm:px-6">

    <!-- Ambient Concentric Circular Vector Rings matching reference -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden flex items-center justify-center z-0">
        <!-- Center Concentric Rings -->
        <svg class="absolute w-[1400px] h-[1400px] text-slate-200/70" viewBox="0 0 1400 1400" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="700" cy="700" r="230" stroke="currentColor" stroke-width="1.2" />
            <circle cx="700" cy="700" r="370" stroke="currentColor" stroke-width="1.2" />
            <circle cx="700" cy="700" r="510" stroke="currentColor" stroke-width="1.2" />
            <circle cx="700" cy="700" r="650" stroke="currentColor" stroke-width="1.2" opacity="0.6" />
        </svg>

        <!-- Top Left Arc -->
        <svg class="absolute -top-[200px] -left-[200px] w-[900px] h-[900px] text-slate-200/50" viewBox="0 0 900 900" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="450" cy="450" r="380" stroke="currentColor" stroke-width="1.2" />
            <circle cx="450" cy="450" r="260" stroke="currentColor" stroke-width="1.2" />
        </svg>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-[420px]">

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] px-7 py-9 sm:px-9 sm:py-10 transition-all duration-200">
            
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Sign in</h1>
                <p class="text-[13px] text-slate-400 mt-1 mb-7">Please sign in to continue</p>
            </div>

            <!-- Error Notification -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200/70 text-red-600 text-xs sm:text-[13px] flex items-start gap-2.5">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path stroke-linecap="round" d="M12 8v4m0 4h.01"/>
                    </svg>
                    <div>
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Google Sign-in Button -->
            <button type="button" class="w-full flex items-center justify-center gap-3 py-2.5 px-4 bg-white border border-slate-200 rounded-xl text-xs sm:text-[13px] font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition duration-150 shadow-xs cursor-pointer">
                <!-- Google G Logo SVG -->
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
                </svg>
                <span>Continue with Google</span>
            </button>

            <!-- Divider -->
            <div class="relative my-6 flex items-center">
                <div class="grow border-t border-slate-200"></div>
                <span class="shrink-0 px-3 text-[11px] sm:text-xs text-slate-400 font-normal">or sign in with email</span>
                <div class="grow border-t border-slate-200"></div>
            </div>

            <!-- Email & Password Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <!-- Envelope Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            placeholder="Email" 
                            class="w-full pl-10 pr-3.5 py-2.5 text-xs sm:text-sm bg-white border @error('email') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl placeholder:text-slate-400 text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition duration-150"
                        >
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <!-- Lock Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required 
                            placeholder="Password" 
                            class="w-full pl-10 pr-10 py-2.5 text-xs sm:text-sm bg-white border @error('password') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror rounded-xl placeholder:text-slate-400 text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition duration-150"
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility()" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                            aria-label="Toggle password visibility"
                        >
                            <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between pt-1">
                    <label class="inline-flex items-center cursor-pointer select-none">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember" 
                            class="w-4 h-4 rounded border-slate-300 text-[#4f46e5] focus:ring-[#4f46e5]/30 cursor-pointer"
                        >
                        <span class="ml-2 text-xs sm:text-[13px] text-slate-600">Remember me</span>
                    </label>

                    <a href="#" class="text-xs sm:text-[13px] text-slate-600 hover:text-[#4f46e5] font-medium transition">
                        Forgot password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full py-2.5 px-4 bg-[#5046e5] hover:bg-[#4338ca] active:scale-[0.99] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-[0_4px_14px_0_rgba(79,70,229,0.35)] hover:shadow-[0_6px_20px_rgba(79,70,229,0.25)] transition duration-150 cursor-pointer mt-2"
                >
                    Login
                </button>
            </form>

            <!-- Sign Up Footer -->
            <div class="mt-6 text-center text-xs sm:text-[13px] text-slate-500">
                Don't have an account? 
                <a href="#" class="font-medium text-slate-900 underline underline-offset-2 hover:text-[#5046e5] transition">
                    Sign up
                </a>
            </div>

        </div>

        <!-- Quick Demo Credentials Helper -->
        <div class="mt-6 bg-white/80 backdrop-blur-sm rounded-xl border border-slate-200/60 p-3.5 shadow-xs text-xs">
            <div class="flex items-center justify-between text-slate-500 font-medium mb-2">
                <span class="flex items-center gap-1.5 text-slate-600 font-semibold">
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                    Akun Database Tersedia (Klik untuk auto-fill):
                </span>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <button 
                    type="button" 
                    onclick="fillCredentials('superadmin@darago.id', 'Password123!')"
                    class="flex flex-col items-start p-2 rounded-lg bg-indigo-50/70 hover:bg-indigo-100/70 border border-indigo-100 text-left transition cursor-pointer"
                >
                    <span class="font-semibold text-indigo-700 text-[11px] flex items-center gap-1">
                        👑 Super Admin
                    </span>
                    <span class="text-[10px] text-slate-500 truncate w-full">superadmin@darago.id</span>
                </button>

                <button 
                    type="button" 
                    onclick="fillCredentials('admin@darago.id', 'Password123!')"
                    class="flex flex-col items-start p-2 rounded-lg bg-slate-100/80 hover:bg-slate-200/70 border border-slate-200 text-left transition cursor-pointer"
                >
                    <span class="font-semibold text-slate-700 text-[11px] flex items-center gap-1">
                        🛡️ Admin
                    </span>
                    <span class="text-[10px] text-slate-500 truncate w-full">admin@darago.id</span>
                </button>
            </div>
        </div>

    </div>

    <!-- Small bottom indicator -->
    <div class="fixed bottom-3 left-3 text-[10px] text-slate-400 flex items-center gap-1.5 opacity-60">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
        DaraGo Auth System
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                `;
            }
        }

        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
