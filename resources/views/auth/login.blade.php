@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-transparent flex flex-col justify-center items-center px-4 sm:px-6 py-12 antialiased text-slate-800 relative overflow-hidden">

    <!-- Ambient Gradient Blobs (Safe, non-overflow) -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Container Utama Simpel & Elegan -->
    <div class="w-full max-w-sm relative z-10">

        <!-- Logo & Header Minimalis -->
        <div class="text-center mb-8">
            <a href="/" class="inline-block transition-transform hover:scale-105 active:scale-95">
                <img src="{{ asset('assets/images/otokeep-logo-horizontal.png') }}?v=5" alt="OtoKeep" class="h-9 w-auto mx-auto object-contain">
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-5">Masuk ke Akun</h1>
            <p class="text-xs text-slate-500 mt-1">Masukkan email dan kata sandi Anda</p>
        </div>

        <!-- Kartu Formulir Login -->
        <div class="card-interactive bg-gradient-to-b from-white via-white to-slate-50/70 p-7 sm:p-8 rounded-3xl border border-slate-200/80 shadow-soft-sm">
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Email
                    </label>
                    <div class="relative">
                        <i class="ph ph-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-slate-50/60 border @error('email') border-rose-400 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl py-2.5 pl-10 pr-3.5 text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all font-medium placeholder:text-slate-400"
                            placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <span class="text-rose-600 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Kata Sandi -->
                <div>
                    <label for="password-input" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <i class="ph ph-lock-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input type="password" id="password-input" name="password" required
                            class="w-full bg-slate-50/60 border @error('password') border-rose-400 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl py-2.5 pl-10 pr-10 text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all font-medium placeholder:text-slate-400"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 focus:outline-none">
                            <i id="password-toggle-icon" class="ph ph-eye text-base"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-rose-600 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-slate-600 hover:text-slate-900">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500/20">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-bold py-2.5 px-4 rounded-xl shadow-sm transition-all text-sm flex items-center justify-center gap-1.5">
                        <span>Masuk</span>
                        <i class="ph-bold ph-arrow-right text-sm"></i>
                    </button>
                </div>
            </form>

            <!-- Link Daftar -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline ml-1">
                    Daftar di sini
                </a>
            </div>
        </div>

        <!-- Tombol Kembali ke Beranda -->
        <div class="text-center mt-6">
            <a href="/" class="text-xs text-slate-400 hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                <i class="ph ph-arrow-left"></i> Kembali ke beranda
            </a>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const input = document.getElementById('password-input');
        const icon = document.getElementById('password-toggle-icon');
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('ph-eye');
            icon.classList.add('ph-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('ph-eye-slash');
            icon.classList.add('ph-eye');
        }
    }
</script>
@endsection
