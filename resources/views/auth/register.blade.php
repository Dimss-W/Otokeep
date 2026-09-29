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
            <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-5">Daftar Akun Baru</h1>
            <p class="text-xs text-slate-500 mt-1">Buat akun untuk memantau servis kendaraan Anda</p>
        </div>

        <!-- Kartu Formulir Register -->
        <div class="card-interactive bg-gradient-to-b from-white via-white to-slate-50/70 p-7 sm:p-8 rounded-3xl border border-slate-200/80 shadow-soft-sm">
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input Nama -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <i class="ph ph-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full bg-slate-50/60 border @error('name') border-rose-400 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl py-2.5 pl-10 pr-3.5 text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all font-medium placeholder:text-slate-400"
                            placeholder="Nama Lengkap">
                    </div>
                    @error('name')
                        <span class="text-rose-600 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Email
                    </label>
                    <div class="relative">
                        <i class="ph ph-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full bg-slate-50/60 border @error('email') border-rose-400 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl py-2.5 pl-10 pr-3.5 text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all font-medium placeholder:text-slate-400"
                            placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <span class="text-rose-600 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Kata Sandi -->
                <div>
                    <label for="reg-password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <i class="ph ph-lock-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input type="password" id="reg-password" name="password" required
                            class="w-full bg-slate-50/60 border @error('password') border-rose-400 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl py-2.5 pl-10 pr-10 text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all font-medium placeholder:text-slate-400"
                            placeholder="Minimal 8 karakter">
                        <button type="button" onclick="togglePass('reg-password', 'reg-icon-1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 focus:outline-none">
                            <i id="reg-icon-1" class="ph ph-eye text-base"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-rose-600 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Konfirmasi Sandi -->
                <div>
                    <label for="reg-password-confirm" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Konfirmasi Kata Sandi
                    </label>
                    <div class="relative">
                        <i class="ph ph-lock-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input type="password" id="reg-password-confirm" name="password_confirmation" required
                            class="w-full bg-slate-50/60 border border-slate-200 rounded-xl py-2.5 pl-10 pr-10 text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all font-medium placeholder:text-slate-400"
                            placeholder="Ulangi kata sandi">
                        <button type="button" onclick="togglePass('reg-password-confirm', 'reg-icon-2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 focus:outline-none">
                            <i id="reg-icon-2" class="ph ph-eye text-base"></i>
                        </button>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-bold py-2.5 px-4 rounded-xl shadow-sm transition-all text-sm flex items-center justify-center gap-1.5">
                        <span>Daftar Akun</span>
                        <i class="ph-bold ph-arrow-right text-sm"></i>
                    </button>
                </div>
            </form>

            <!-- Link Masuk -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline ml-1">
                    Masuk di sini
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
    function togglePass(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
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
