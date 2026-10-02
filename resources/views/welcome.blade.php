@extends('layouts.app')

@section('content')
<div class="relative min-h-screen flex flex-col bg-transparent">
    <!-- HERO SECTION WRAPPER (Auto-fits mobile & desktop naturally) -->
    <header class="relative w-full overflow-hidden flex flex-col justify-between">
        <!-- Dynamic Hero Background Slider (Fills 100% of header wrapper) -->
        <div class="absolute inset-0 w-full h-full pointer-events-none select-none -z-0">
            <div id="hero-slider" class="relative w-full h-full">
                <!-- Slide 1: bg2 (City Skyline Fleet) - Starts Active with exact seamless sky color #86B0DA -->
                <div id="slide-bg2" class="absolute inset-0 w-full h-full transform translate-x-0 opacity-100 will-change-transform bg-[#86B0DA] overflow-hidden">
                    <img src="{{ asset('assets/images/bg2.jpg') }}" alt="Armada Kendaraan OtoKeep" class="w-full h-full object-contain object-bottom select-none">
                    <!-- Smooth Top Sky Blend -->
                    <div class="absolute top-0 left-0 right-0 h-32 bg-gradient-to-b from-[#86B0DA] via-[#86B0DA]/60 to-transparent pointer-events-none"></div>
                </div>

                <!-- Slide 2: bg (Red Crimson Fleet) - Next in Queue with exact seamless red color #861F20 -->
                <div id="slide-bg" class="absolute inset-0 w-full h-full transform -translate-x-full opacity-0 will-change-transform bg-[#861F20] overflow-hidden">
                    <img src="{{ asset('assets/images/bg.jpg') }}" alt="Armada OtoKeep Premium" class="w-full h-full object-contain object-bottom select-none">
                    <!-- Smooth Top Red Blend -->
                    <div class="absolute top-0 left-0 right-0 h-32 bg-gradient-to-b from-[#861F20] via-[#861F20]/70 to-transparent pointer-events-none"></div>
                </div>

                <!-- Subtle Bottom Ground Shadow (Never obscures tires) -->
                <div class="absolute bottom-0 left-0 right-0 h-4 bg-gradient-to-t from-slate-900/10 to-transparent pointer-events-none"></div>
            </div>
        </div>

        <!-- Navigation (Sticky in document flow - never overlaps hero content) -->
        <nav class="sticky top-0 z-50 w-full px-4 sm:px-6 lg:px-8 py-2 sm:py-3.5 bg-white/80 backdrop-blur-md transition-all">
            <div class="max-w-7xl mx-auto flex justify-between items-center bg-white/90 backdrop-blur-xl px-4 sm:px-6 py-2 sm:py-2.5 rounded-2xl border border-slate-200/80 shadow-soft-sm">
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

        <!-- Hero Content (Positioned in Upper Space with pb-60 sm:pb-56 so cars fit cleanly below on mobile without cutoff) -->
        <main class="relative z-10 flex-1 flex flex-col justify-start items-center text-center px-4 sm:px-6 max-w-4xl mx-auto pt-3 sm:pt-8 md:pt-12 pb-60 sm:pb-56 md:pb-44 w-full">
            <!-- Content Box with smooth crossfade transition -->
            <div id="hero-text-box" class="transition-all duration-300 transform translate-y-0 opacity-100 flex flex-col items-center w-full">
                <!-- Badge -->
                <div class="mb-2.5 sm:mb-4">
                    <span id="hero-badge" class="inline-flex items-center gap-1.5 px-3 sm:px-4 py-1 sm:py-1.5 bg-white/90 backdrop-blur-md text-blue-700 rounded-full text-[11px] sm:text-sm font-bold border border-blue-200/80 shadow-soft-sm transition-all duration-500">
                        <i id="hero-badge-icon" class="ph-fill ph-sparkle text-blue-500"></i>
                        <span id="hero-badge-text">#1 Kendaraan Maintenance Tracker</span>
                    </span>
                </div>

                <!-- Slogan Headline -->
                <h1 id="hero-title" class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-black mb-2 sm:mb-4 leading-tight sm:leading-[1.15] tracking-tight transition-all duration-500">
                    <span id="hero-title-prefix" class="text-slate-900 transition-colors duration-500">Gak Ada Lagi</span> <br>
                    <span id="hero-title-accent" class="text-blue-600 transition-colors duration-500">Drama Lupa Servis</span>
                </h1>

                <!-- Subtitle Description -->
                <p id="hero-subtitle" class="text-xs sm:text-base md:text-lg font-medium mb-4 sm:mb-6 max-w-xl mx-auto leading-relaxed transition-all duration-500 text-slate-700 px-2">
                    Pantau kondisi kendaraan kesayangan Anda secara real-time. Dapatkan pengingat servis cerdas dan tips perawatan ahli dalam satu genggaman modern.
                </p>
                
                <!-- CTA Buttons -->
                <div id="hero-cta-box" class="flex flex-col sm:flex-row gap-2 sm:gap-3.5 justify-center items-center w-full max-w-xs sm:max-w-none transition-all duration-500">
                    <a id="hero-btn-primary" href="{{ route('register') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 sm:px-8 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl font-bold text-xs sm:text-base transition-all shadow-md shadow-blue-500/20 flex items-center justify-center gap-2">
                        Mulai Sekarang <i class="ph-bold ph-arrow-right"></i>
                    </a>
                    <a id="hero-btn-secondary" href="#features" class="w-full sm:w-auto bg-white/95 hover:bg-white backdrop-blur-sm border border-slate-200 text-slate-700 px-6 sm:px-8 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl font-bold text-xs sm:text-base transition-all shadow-soft-sm flex items-center justify-center gap-2">
                        Lihat Fitur
                    </a>
                </div>
            </div>

            <!-- Slide Indicator Pills -->
            <div class="flex items-center gap-2 mt-4 sm:mt-6 z-20">
                <button type="button" onclick="goToHeroSlide(0)" id="dot-0" class="hero-slide-dot h-2 w-8 rounded-full bg-brand-600 transition-all duration-500 shadow-sm cursor-pointer" title="Armada Seri 1 (bg2)"></button>
                <button type="button" onclick="goToHeroSlide(1)" id="dot-1" class="hero-slide-dot h-2 w-2.5 rounded-full bg-slate-300 hover:bg-slate-400 transition-all duration-500 shadow-sm cursor-pointer" title="Armada Seri 2 (bg)"></button>
            </div>
        </main>
    </header>

    <!-- Fitur Unggulan Platform Showcase (Clean separation, no negative margin cutoffs) -->
    <section id="features" class="relative z-10 w-full max-w-5xl px-4 sm:px-6 mx-auto mb-16 sm:mb-20 mt-6 sm:mt-10 md:mt-14">
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

<!-- Slide-Right Background & Adaptive Typography Engine (bg2 -> bg) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slideBg2 = document.getElementById('slide-bg2');
        const slideBg = document.getElementById('slide-bg');
        const textBox = document.getElementById('hero-text-box');
        const badgeEl = document.getElementById('hero-badge');
        const badgeIcon = document.getElementById('hero-badge-icon');
        const badgeText = document.getElementById('hero-badge-text');
        const titlePrefix = document.getElementById('hero-title-prefix');
        const titleAccent = document.getElementById('hero-title-accent');
        const subtitleEl = document.getElementById('hero-subtitle');
        const btnPrimary = document.getElementById('hero-btn-primary');
        const btnSecondary = document.getElementById('hero-btn-secondary');
        const dot0 = document.getElementById('dot-0');
        const dot1 = document.getElementById('dot-1');

        let currentSlide = 0; // 0 = bg2, 1 = bg
        let isAnimating = false;
        let slideInterval = null;

        // Slide Themes Config (Optimized contrast for each background)
        const slideThemes = [
            {
                // Slide 0: bg2 (City Skyline Fleet) - Crisp Dark Typography
                badgeClass: 'inline-flex items-center gap-1.5 px-3 sm:px-4 py-1 sm:py-1.5 bg-white/90 backdrop-blur-md text-blue-700 rounded-full text-[11px] sm:text-sm font-bold border border-blue-200/80 shadow-soft-sm transition-all duration-500',
                badgeIcon: 'ph-fill ph-sparkle text-blue-500',
                badgeText: '#1 Kendaraan Maintenance Tracker',
                titlePrefixClass: 'text-slate-900 transition-colors duration-500',
                titlePrefixText: 'Gak Ada Lagi',
                titleAccentClass: 'text-blue-600 transition-colors duration-500',
                titleAccentText: 'Drama Lupa Servis',
                subtitleClass: 'text-xs sm:text-base md:text-lg font-medium mb-5 sm:mb-8 max-w-xl mx-auto leading-relaxed transition-all duration-500 text-slate-700 px-2',
                subtitleText: 'Pantau kondisi kendaraan kesayangan Anda secara real-time. Dapatkan pengingat servis cerdas dan tips perawatan ahli dalam satu genggaman modern.',
                btnPrimaryClass: 'w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 sm:px-8 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl font-bold text-xs sm:text-base transition-all shadow-md shadow-blue-500/20 flex items-center justify-center gap-2',
                btnSecondaryClass: 'w-full sm:w-auto bg-white/95 hover:bg-white backdrop-blur-sm border border-slate-200 text-slate-700 px-6 sm:px-8 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl font-bold text-xs sm:text-base transition-all shadow-soft-sm flex items-center justify-center gap-2',
                dot0Class: 'hero-slide-dot h-2 w-8 rounded-full bg-brand-600 transition-all duration-500 shadow-sm cursor-pointer',
                dot1Class: 'hero-slide-dot h-2 w-2.5 rounded-full bg-slate-300 hover:bg-slate-400 transition-all duration-500 shadow-sm cursor-pointer'
            },
            {
                // Slide 1: bg (Red Crimson Fleet) - Pure White & Luminous Amber (Anti-Nabrak)
                badgeClass: 'inline-flex items-center gap-1.5 px-3 sm:px-4 py-1 sm:py-1.5 bg-white/20 backdrop-blur-md text-white rounded-full text-[11px] sm:text-sm font-bold border border-white/30 shadow-md transition-all duration-500',
                badgeIcon: 'ph-fill ph-sparkle text-amber-300',
                badgeText: 'Solusi Pemantauan Armada Terbaik',
                titlePrefixClass: 'text-white drop-shadow-[0_2px_14px_rgba(0,0,0,0.85)] font-black transition-colors duration-500',
                titlePrefixText: 'Gak Ada Lagi',
                titleAccentClass: 'text-amber-300 drop-shadow-[0_2px_16px_rgba(0,0,0,0.9)] font-black transition-colors duration-500',
                titleAccentText: 'Drama Lupa Servis',
                subtitleClass: 'text-xs sm:text-base md:text-lg font-semibold mb-5 sm:mb-8 max-w-xl mx-auto leading-relaxed transition-all duration-500 text-white drop-shadow-[0_2px_10px_rgba(0,0,0,0.9)] px-2',
                subtitleText: 'Kelola jadwal servis dan kesehatan seluruh unit kendaraan Anda dengan mudah. Notifikasi tepat waktu, riwayat digital lengkap, dan estimasi biaya transparan.',
                btnPrimaryClass: 'w-full sm:w-auto bg-white hover:bg-slate-100 text-[#771011] px-6 sm:px-8 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl font-extrabold text-xs sm:text-base transition-all shadow-2xl shadow-black/40 flex items-center justify-center gap-2',
                btnSecondaryClass: 'w-full sm:w-auto bg-white/20 hover:bg-white/30 backdrop-blur-md border border-white/40 text-white px-6 sm:px-8 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl font-bold text-xs sm:text-base transition-all shadow-md flex items-center justify-center gap-2',
                dot0Class: 'hero-slide-dot h-2 w-2.5 rounded-full bg-white/40 hover:bg-white/60 transition-all duration-500 shadow-sm cursor-pointer',
                dot1Class: 'hero-slide-dot h-2 w-8 rounded-full bg-white transition-all duration-500 shadow-md cursor-pointer'
            }
        ];

        function applyTheme(index) {
            const theme = slideThemes[index];
            if (!theme) return;

            // Smooth crossfade on text content
            if (textBox) {
                textBox.style.opacity = '0.3';
                textBox.style.transform = 'translateY(4px)';
            }

            setTimeout(() => {
                if (badgeEl) badgeEl.className = theme.badgeClass;
                if (badgeIcon) badgeIcon.className = theme.badgeIcon;
                if (badgeText) badgeText.innerText = theme.badgeText;

                if (titlePrefix) {
                    titlePrefix.className = theme.titlePrefixClass;
                    titlePrefix.innerText = theme.titlePrefixText;
                }
                if (titleAccent) {
                    titleAccent.className = theme.titleAccentClass;
                    titleAccent.innerText = theme.titleAccentText;
                }

                if (subtitleEl) {
                    subtitleEl.className = theme.subtitleClass;
                    subtitleEl.innerText = theme.subtitleText;
                }

                if (btnPrimary) btnPrimary.className = theme.btnPrimaryClass;
                if (btnSecondary) btnSecondary.className = theme.btnSecondaryClass;

                if (dot0) dot0.className = theme.dot0Class;
                if (dot1) dot1.className = theme.dot1Class;

                if (textBox) {
                    textBox.style.opacity = '1';
                    textBox.style.transform = 'translateY(0)';
                }
            }, 250);
        }

        function slideRightTo(nextIndex) {
            if (isAnimating || currentSlide === nextIndex) return;
            isAnimating = true;

            const outgoing = currentSlide === 0 ? slideBg2 : slideBg;
            const incoming = nextIndex === 0 ? slideBg2 : slideBg;

            // Incoming slide positioned at left (-100%)
            incoming.style.transition = 'none';
            incoming.style.transform = 'translateX(-100%)';
            incoming.style.opacity = '0';
            incoming.style.zIndex = '2';
            outgoing.style.zIndex = '1';
            
            // Force repaint
            void incoming.offsetWidth;

            // Apply theme transition synchronized with slide
            applyTheme(nextIndex);

            // Animate both moving rightward smoothly
            const duration = '1100ms cubic-bezier(0.25, 1, 0.5, 1)';
            outgoing.style.transition = `transform ${duration}, opacity 900ms ease-out`;
            incoming.style.transition = `transform ${duration}, opacity 900ms ease-in`;

            outgoing.style.transform = 'translateX(100%)';
            outgoing.style.opacity = '0';

            incoming.style.transform = 'translateX(0%)';
            incoming.style.opacity = '1';

            currentSlide = nextIndex;

            setTimeout(() => {
                outgoing.style.transition = 'none';
                outgoing.style.transform = 'translateX(-100%)';
                isAnimating = false;
            }, 1150);
        }

        window.goToHeroSlide = function(index) {
            slideRightTo(index);
            resetAutoSlide();
        };

        function nextHeroSlide() {
            const next = currentSlide === 0 ? 1 : 0;
            slideRightTo(next);
        }

        function startAutoSlide() {
            slideInterval = setInterval(nextHeroSlide, 5000);
        }

        function resetAutoSlide() {
            if (slideInterval) clearInterval(slideInterval);
            startAutoSlide();
        }

        startAutoSlide();
    });
</script>
@endsection
