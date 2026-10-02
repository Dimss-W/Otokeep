@extends('layouts.app')

@section('content')
<div class="relative min-h-screen flex flex-col bg-[#F8FAFC]">
    <!-- HERO SECTION (Fit like original photo across all devices, zero sidebars, all cars visible) -->
    <header class="relative w-full overflow-hidden bg-[#861F20]">
        <!-- Background: bg.jpg Responsive Fit (object-contain on mobile, object-cover on desktop) -->
        <div class="absolute inset-0 w-full h-full pointer-events-none select-none -z-0">
            <img src="{{ asset('assets/images/bg.jpg') }}" alt="Armada OtoKeep Premium" class="w-full h-full object-contain md:object-cover object-bottom select-none">
            <!-- Subtle Vignette for Text Contrast -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/20 pointer-events-none"></div>
        </div>

        <!-- Navigation (Sticky in document flow) -->
        <nav class="sticky top-0 z-50 w-full px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3.5 bg-white/90 backdrop-blur-md transition-all border-b border-white/10">
            <div class="max-w-7xl mx-auto flex justify-between items-center bg-white/95 backdrop-blur-xl px-4 sm:px-6 py-2 sm:py-2.5 rounded-2xl border border-slate-200/80 shadow-soft-sm">
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('assets/images/otokeep-logo-horizontal.png') }}?v=5" alt="OtoKeep Logo" class="h-7 sm:h-9 w-auto object-contain">
                </a>
                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 font-bold hover:text-blue-700 transition-colors flex items-center gap-1.5 px-3 sm:px-4 py-1.5 sm:py-2 bg-blue-50 rounded-xl border border-blue-200 text-xs sm:text-sm">
                                <i class="ph-bold ph-shield-check text-base sm:text-lg"></i>
                                <span class="hidden sm:inline">Dashboard Admin</span>
                                <span class="sm:hidden">Admin</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-white bg-blue-600 hover:bg-blue-700 px-4 sm:px-5 py-1.5 sm:py-2 rounded-xl font-bold transition-all shadow-sm text-xs sm:text-sm">Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-blue-600 font-semibold px-2 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3.5 sm:px-5 py-1.5 sm:py-2.5 rounded-xl font-bold transition-all shadow-sm text-xs sm:text-sm whitespace-nowrap">Daftar Sekarang</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Text Content (Positioned in Upper Space with pb-60 to pb-80 so cars fit cleanly at bottom) -->
        <main class="relative z-10 flex flex-col justify-start items-center text-center px-4 sm:px-6 max-w-4xl mx-auto pt-4 sm:pt-8 md:pt-12 pb-56 sm:pb-64 md:pb-72 lg:pb-80 w-full">
            <!-- Badge -->
            <div class="mb-3 sm:mb-4">
                <span class="inline-flex items-center gap-1.5 px-3.5 sm:px-4 py-1 sm:py-1.5 bg-white/20 backdrop-blur-md text-white rounded-full text-[11px] sm:text-sm font-bold border border-white/30 shadow-md">
                    <i class="ph-fill ph-sparkle text-amber-300"></i> #1 Kendaraan Maintenance Tracker
                </span>
            </div>

            <!-- Slogan Headline -->
            <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-black mb-3 sm:mb-4 leading-tight sm:leading-[1.15] tracking-tight">
                <span class="text-white drop-shadow-[0_2px_14px_rgba(0,0,0,0.85)] font-black">Gak Ada Lagi</span> <br>
                <span class="text-amber-300 drop-shadow-[0_2px_16px_rgba(0,0,0,0.9)] font-black">Drama Lupa Servis</span>
            </h1>

            <!-- Subtitle Description -->
            <p class="text-xs sm:text-base md:text-lg font-medium mb-5 sm:mb-7 max-w-xl mx-auto leading-relaxed text-white drop-shadow-[0_2px_10px_rgba(0,0,0,0.9)] px-2">
                Pantau kondisi kendaraan kesayangan Anda secara real-time. Dapatkan pengingat servis cerdas dan tips perawatan ahli dalam satu genggaman modern.
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-4 justify-center items-center w-full max-w-xs sm:max-w-none">
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-white hover:bg-slate-100 text-[#771011] px-6 sm:px-8 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl font-extrabold text-xs sm:text-base transition-all shadow-xl shadow-black/40 flex items-center justify-center gap-2">
                    Mulai Sekarang <i class="ph-bold ph-arrow-right"></i>
                </a>
                <a href="#features" class="w-full sm:w-auto bg-white/20 hover:bg-white/30 backdrop-blur-md border border-white/40 text-white px-6 sm:px-8 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl font-bold text-xs sm:text-base transition-all shadow-md flex items-center justify-center gap-2">
                    Lihat Fitur
                </a>
            </div>
        </main>
    </header>

    <!-- Fitur Unggulan Platform Showcase (Clean separation, no overlap or cutoffs) -->
    <section id="features" class="relative z-10 w-full max-w-5xl px-4 sm:px-6 mx-auto mb-16 sm:mb-20 mt-8 sm:mt-14 md:mt-16">
        <div class="bg-white p-4 md:p-6 rounded-[2rem] shadow-soft-xl border border-slate-200/80">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Feature 1: AI Speedometer Scanner & Web Push -->
                <div class="bg-slate-50/70 p-6 rounded-2xl border border-slate-200/80 flex flex-col justify-between hover:bg-white hover:shadow-soft-md transition-all">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-4">
                            <i class="ph-bold ph-camera text-2xl"></i>
                        </div>
                        <h3 class="text-slate-900 font-bold text-lg mb-2">AI Speedometer Scanner</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Cukup foto spidometer motor/mobil via kamera smartphone, AI otomatis mengenali angka kilometer tanpa repot ketik manual.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center gap-2 text-[11px] text-blue-600 font-bold">
                        <i class="ph-bold ph-bell-ringing"></i> Pengingat Layar HP 100% Gratis
                    </div>
                </div>

                <!-- Feature 2: Siklus Servis Presisi -->
                <div class="bg-slate-50/70 p-6 rounded-2xl border border-slate-200/80 flex flex-col justify-between hover:bg-white hover:shadow-soft-md transition-all">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                            <i class="ph-bold ph-wrench text-2xl"></i>
                        </div>
                        <h3 class="text-slate-900 font-bold text-lg mb-2">Manajemen Servis & Pajak</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Auto-rollover interval servis berkala, pencatatan riwayat bengkel, serta hitung mundur jatuh tempo PKB/STNK.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center gap-2 text-[11px] text-emerald-600 font-bold">
                        <i class="ph-bold ph-check-circle"></i> Pengingat Berkala Otomatis
                    </div>
                </div>

                <!-- Feature 3: Konsultasi AI -->
                <div class="bg-slate-50/70 p-6 rounded-2xl border border-slate-200/80 flex flex-col justify-between hover:bg-white hover:shadow-soft-md transition-all">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-4">
                            <i class="ph-bold ph-robot text-2xl"></i>
                        </div>
                        <h3 class="text-slate-900 font-bold text-lg mb-2">AI Mekanik Bang OTO</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Konsultasi teknis dan diagnosis keluhan kendaraan interaktif yang didukung kecerdasan buatan Gemini AI.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center gap-2 text-[11px] text-purple-600 font-bold">
                        <i class="ph-bold ph-check-circle"></i> Analisis Otomotif Cerdas
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
