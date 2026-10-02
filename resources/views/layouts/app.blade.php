<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#830000">
    <title>{{ config('app.name', 'OtoKeep') }} - Gak Ada Lagi Drama Lupa Servis</title>

    <!-- Official OtoKeep Vector Favicon & Touch Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=3">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}?v=3">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/otokeep-icon.png') }}?v=4">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="OtoKeep">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#0F172A',
                        'navy-card': '#FFFFFF',
                        'navy-light': '#F1F5F9',
                        brand: {
                            50: '#FEF2F2',
                            100: '#FDE8E8',
                            200: '#FCD9D9',
                            300: '#F8A9A9',
                            400: '#BE2222',
                            500: '#A81010',
                            600: '#830000',
                            700: '#6C0000',
                            800: '#550000',
                            900: '#3D0000',
                        },
                        blue: {
                            50: '#FEF2F2',
                            100: '#FDE8E8',
                            200: '#FCD9D9',
                            300: '#F8A9A9',
                            400: '#BE2222',
                            500: '#A81010',
                            600: '#830000',
                            700: '#6C0000',
                            800: '#550000',
                            900: '#3D0000',
                        },
                        indigo: {
                            50: '#FEF2F2',
                            100: '#FDE8E8',
                            200: '#FCD9D9',
                            500: '#A81010',
                            600: '#6C0000',
                            700: '#550000',
                        },
                        orange: '#830000',
                        'orange-hover': '#6C0000',
                        'orange-glow': 'rgba(131, 0, 0, 0.15)',
                        emerald: {
                            50: '#ECFDF5',
                            100: '#D1FAE5',
                            400: '#34D399',
                            500: '#10B981',
                            600: '#059669',
                            700: '#047857',
                        },
                        cyan: {
                            50: '#ECFEFF',
                            100: '#CFFAFE',
                            400: '#22D3EE',
                            500: '#06B6D4',
                            600: '#0891B2',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    boxShadow: {
                        'soft-sm': '0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.02)',
                        'soft-md': '0 4px 12px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.03)',
                        'soft-lg': '0 10px 25px -4px rgba(15, 23, 42, 0.06), 0 8px 12px -6px rgba(15, 23, 42, 0.04)',
                        'soft-xl': '0 20px 35px -8px rgba(15, 23, 42, 0.08), 0 12px 16px -8px rgba(15, 23, 42, 0.04)',
                        'glow-brand': '0 8px 20px -4px rgba(131, 0, 0, 0.35)',
                        'glow-emerald': '0 8px 20px -4px rgba(16, 185, 129, 0.25)',
                    }
                }
            }
        }
    </script>
    <style>
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, 0.4) transparent;
        }
        *::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        *::-webkit-scrollbar-track {
            background: transparent;
        }
        *::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.35);
            border-radius: 9999px;
        }
        *::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 116, 139, 0.6);
        }
        .glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.85);
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
        }
        .glass-dark {
            background: #FFFFFF;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 2px 8px -1px rgba(15, 23, 42, 0.04);
        }
        html {
            overflow-x: hidden;
            scroll-behavior: smooth;
        }
        body {
            background-color: #F8FAFC;
            background-image: 
                radial-gradient(at 0% 0%, rgba(131, 0, 0, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(16, 185, 129, 0.07) 0px, transparent 45%),
                radial-gradient(at 50% 35%, rgba(131, 0, 0, 0.03) 0px, transparent 60%),
                radial-gradient(at 100% 100%, rgba(131, 0, 0, 0.04) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(6, 182, 212, 0.05) 0px, transparent 45%);
            background-attachment: fixed;
            color: #0F172A;
            min-height: 100vh;
        }

        /* Interactive Page Transitions & Animations (Silky Smooth) */
        @keyframes pageFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes cardFloatIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }

        .animate-page-enter {
            animation: pageFadeIn 0.22s ease-out both;
        }

        .stagger-1 { animation: cardFloatIn 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) 0.03s both; }
        .stagger-2 { animation: cardFloatIn 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) 0.06s both; }
        .stagger-3 { animation: cardFloatIn 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) 0.09s both; }
        .stagger-4 { animation: cardFloatIn 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) 0.12s both; }
        .stagger-5 { animation: cardFloatIn 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) 0.15s both; }

        /* Interactive Card Lift Micro-interactions (Refined & Butter Smooth) */
        .card-interactive {
            transition: transform 0.2s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.2s cubic-bezier(0.2, 0.8, 0.2, 1), border-color 0.2s ease;
            will-change: transform;
        }
        .card-interactive:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.07), 0 3px 8px -2px rgba(15, 23, 42, 0.03);
            border-color: rgba(131, 0, 0, 0.3);
        }
        .card-interactive:active {
            transform: translateY(0);
        }

        /* Interactive Button Press */
        button, a.btn, .btn-interactive {
            transition: all 0.18s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        button:active, a.btn:active, .btn-interactive:active {
            transform: scale(0.985);
        }
    </style>
    <!-- OneSignal SDK -->
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.sw.js" async=""></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      window.OneSignalDeferred = window.OneSignalDeferred || [];
      OneSignalDeferred.push(function(OneSignal) {
        OneSignal.init({
          appId: "{{ config('services.onesignal.app_id') }}",
        });
        @auth
            OneSignal.login("{{ auth()->id() }}");
        @endauth
      });

      // Native Browser Push Notifications Integration
      document.addEventListener('DOMContentLoaded', function() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
      });

      function showLocalNotification(title, body) {
        if ('Notification' in window) {
            if (Notification.permission === 'granted') {
                const options = {
                    body: body,
                    icon: 'https://cdn-icons-png.flaticon.com/512/3239/3239958.png',
                    badge: 'https://cdn-icons-png.flaticon.com/512/3239/3239958.png',
                    vibrate: [200, 100, 200],
                    tag: 'otokeep-alert'
                };
                new Notification(title, options);
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        showLocalNotification(title, body);
                    }
                });
            }
        }
      }
    </script>
</head>
<body class="font-sans antialiased selection:bg-blue-600 selection:text-white">
    <!-- Interactive Top Loading Bar (YouTube / GitHub style) -->
    <div id="otokeep-page-loader" class="fixed top-0 left-0 right-0 h-1 z-[9999] pointer-events-none opacity-0 transition-opacity duration-200">
        <div id="otokeep-loader-bar" class="h-full bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-500 w-0 shadow-sm shadow-blue-500/50"></div>
    </div>

    <!-- Page Content Container with Fade-in Animation -->
    <div id="page-content-wrapper" class="animate-page-enter">
        @yield('content')
    </div>

    <!-- Page Transition & Navigation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('otokeep-page-loader');
            const loaderBar = document.getElementById('otokeep-loader-bar');

            // Initial load completion effect
            if (loader && loaderBar) {
                loader.style.opacity = '1';
                loaderBar.style.transition = 'width 0.28s ease-out';
                loaderBar.style.width = '100%';
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => { 
                        loaderBar.style.transition = 'none';
                        loaderBar.style.width = '0%'; 
                    }, 200);
                }, 220);
            }

            // Interactive Transition on Link Click
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (!link) return;

                const href = link.getAttribute('href');
                if (!href) return;

                // Ignore anchors, JS links, blank targets, or downloads
                if (
                    href.startsWith('#') || 
                    href.startsWith('javascript:') || 
                    link.getAttribute('target') === '_blank' || 
                    link.hasAttribute('download') ||
                    e.ctrlKey || e.metaKey || e.shiftKey
                ) {
                    return;
                }

                // Check same origin
                try {
                    const targetUrl = new URL(link.href, window.location.origin);
                    if (targetUrl.origin === window.location.origin && targetUrl.pathname !== window.location.pathname) {
                        // Smoothly advance loader bar without jarring layout changes
                        if (loader && loaderBar) {
                            loader.style.opacity = '1';
                            loaderBar.style.transition = 'width 0.45s cubic-bezier(0.22, 1, 0.36, 1)';
                            loaderBar.style.width = '70%';
                        }
                    }
                } catch (err) {}
            });

            // Reset when navigating back/forward (BFCache)
            window.addEventListener('pageshow', function() {
                if (loader && loaderBar) {
                    loader.style.opacity = '0';
                    loaderBar.style.width = '0%';
                }
            });
        });
    </script>

    @if(session('success'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: '#FFFFFF',
            color: '#0F172A',
            customClass: {
                popup: 'shadow-lg border border-slate-200 rounded-2xl'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    </script>
    @endif

    <!-- PWA INSTALL PROMPT BANNER (Non-intrusive) -->
    <div id="pwa-install-banner" class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 sm:w-96 z-50 transform translate-y-36 opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-white/95 backdrop-blur-md p-4 rounded-2xl border border-slate-200/90 shadow-soft-lg flex items-center justify-between gap-3.5">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/images/otokeep-icon.png') }}" alt="Otokeep Icon" class="w-10 h-10 rounded-xl shadow-xs shrink-0 object-contain bg-slate-50 p-1 border border-slate-100">
                <div>
                    <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                        <span>Pasang Aplikasi Otokeep</span>
                        <span class="px-1.5 py-0.2 rounded bg-blue-50 text-blue-700 text-[9px] font-black uppercase">PWA</span>
                    </h4>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">
                        Akses cepat dari layar utama HP / PC tanpa browser.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                <button type="button" id="btn-pwa-dismiss" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-all text-xs" title="Nanti saja">
                    <i class="ph-bold ph-x"></i>
                </button>
                <button type="button" id="btn-pwa-install" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold rounded-xl text-xs shadow-sm shadow-blue-500/25 transition-all">
                    Install
                </button>
            </div>
        </div>
    </div>

    <!-- PWA iOS Instructions Modal (If on Safari iOS) -->
    <div id="pwa-ios-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 max-w-sm w-full shadow-soft-xl border border-slate-200/80 text-center animate-scale-in">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3 text-2xl">
                <i class="ph-bold ph-apple-logo"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1">Pasang di iPhone / iPad</h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-4">
                Untuk memasang Otokeep ke layar utama iOS:
            </p>
            <div class="bg-slate-50 p-3.5 rounded-2xl text-left space-y-2 text-xs text-slate-700 mb-5 border border-slate-200/60 font-medium">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">1</span>
                    <span>Tekan tombol <strong>Bagikan (Share)</strong> <i class="ph-bold ph-export inline text-sm text-blue-600"></i> di bar bawah Safari.</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">2</span>
                    <span>Gulir ke bawah dan pilih <strong>"Tambah ke Layar Utama" (Add to Home Screen)</strong>.</span>
                </div>
            </div>
            <button type="button" onclick="closePwaIosModal()" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-xs transition-all">
                Mengerti
            </button>
        </div>
    </div>

    <!-- PWA Core Engine Script -->
    <script>
        let deferredPwaPrompt = null;
        const pwaBanner = document.getElementById('pwa-install-banner');
        const pwaInstallBtn = document.getElementById('btn-pwa-install');
        const pwaDismissBtn = document.getElementById('btn-pwa-dismiss');
        const sidebarPwaBtn = document.getElementById('sidebar-pwa-install-btn');

        // 1. Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').catch(function(err) {
                    console.warn('PWA service worker reg error:', err);
                });
            });
        }

        // Check if running in standalone PWA mode
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
        const isIos = /iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());

        function showPwaBanner() {
            if (!pwaBanner || isStandalone) return;
            const dismissedUntil = localStorage.getItem('pwa_dismissed_until');
            if (dismissedUntil && Date.now() < parseInt(dismissedUntil, 10)) {
                return;
            }
            pwaBanner.classList.remove('translate-y-36', 'opacity-0', 'pointer-events-none');
            pwaBanner.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
        }

        function hidePwaBanner(rememberDismiss = true) {
            if (!pwaBanner) return;
            pwaBanner.classList.add('translate-y-36', 'opacity-0', 'pointer-events-none');
            pwaBanner.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
            if (rememberDismiss) {
                // Dismiss for 7 days
                localStorage.setItem('pwa_dismissed_until', Date.now() + (7 * 24 * 60 * 60 * 1000));
            }
        }

        // Catch beforeinstallprompt on Chromium browsers
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPwaPrompt = e;

            if (sidebarPwaBtn) {
                sidebarPwaBtn.classList.remove('hidden');
                sidebarPwaBtn.classList.add('flex');
            }

            setTimeout(showPwaBanner, 3000);
        });

        if (pwaInstallBtn) {
            pwaInstallBtn.addEventListener('click', async () => {
                hidePwaBanner(false);
                if (deferredPwaPrompt) {
                    deferredPwaPrompt.prompt();
                    const { outcome } = await deferredPwaPrompt.userChoice;
                    if (outcome === 'accepted') {
                        if (sidebarPwaBtn) sidebarPwaBtn.style.display = 'none';
                    }
                    deferredPwaPrompt = null;
                } else if (isIos) {
                    openPwaIosModal();
                }
            });
        }

        if (pwaDismissBtn) {
            pwaDismissBtn.addEventListener('click', () => hidePwaBanner(true));
        }

        window.triggerPwaInstall = function() {
            if (deferredPwaPrompt) {
                deferredPwaPrompt.prompt();
            } else if (isIos) {
                openPwaIosModal();
            } else {
                showPwaBanner();
            }
        };

        window.openPwaIosModal = function() {
            const modal = document.getElementById('pwa-ios-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        };

        window.closePwaIosModal = function() {
            const modal = document.getElementById('pwa-ios-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        };
    </script>
</body>
</html>
