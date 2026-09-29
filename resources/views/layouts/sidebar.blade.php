<!-- Mobile Top Bar for User Role (Visible only on mobile screens < lg) -->
<header class="lg:hidden sticky top-0 z-40 bg-white/95 backdrop-blur-xl border-b border-slate-200/80 px-4 py-3 flex items-center justify-between shadow-soft-sm">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        <img src="{{ asset('assets/images/otokeep-logo-horizontal.png') }}?v=5" alt="OtoKeep Logo" class="h-8 w-auto object-contain">
    </a>
    <div class="flex items-center gap-2">
        <a href="{{ route('user.chat') }}" class="p-2 rounded-xl bg-blue-50 text-blue-600 border border-blue-200/80 relative transition-colors hover:bg-blue-100" title="Tanya Bang OTO">
            <i class="ph-bold ph-robot text-xl"></i>
            <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        </a>
        <button onclick="toggleUserSidebar()" class="p-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors" title="Buka Menu">
            <i class="ph-bold ph-list text-xl"></i>
        </button>
    </div>
</header>

<!-- Mobile Overlay Backdrop -->
<div id="user-sidebar-backdrop" onclick="toggleUserSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden lg:hidden transition-all duration-300"></div>

<!-- User Sidebar (Fixed Off-Canvas on Mobile, Full-Height Sticky Sidebar on Desktop) -->
<aside id="user-sidebar" class="fixed top-0 bottom-0 left-0 w-72 bg-white border-r border-slate-200/80 flex flex-col justify-between py-6 px-4 z-50 transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:shrink-0 overflow-y-auto shadow-soft-sm select-none">
    <div class="space-y-6">
        <!-- Logo Header -->
        <div class="flex items-center justify-between px-2 pt-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('assets/images/otokeep-logo-horizontal.png') }}?v=5" alt="OtoKeep Logo" class="h-9 w-auto object-contain transition-transform group-hover:scale-[1.02]">
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-200/80 px-1.5 py-0.5 rounded-md">v2.0</span>
            </a>
            <button onclick="toggleUserSidebar()" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-700 rounded-xl bg-slate-100 transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>

        <!-- User Profile Card -->
        @php
            $currentActiveVehicle = auth()->user()->active_vehicle ?? auth()->user()->vehicle ?? null;
        @endphp
        <div class="p-3.5 bg-slate-50/90 border border-slate-200/80 rounded-2xl shadow-soft-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-blue-500/25 shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="overflow-hidden flex-1 min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="text-sm font-bold text-slate-900 block truncate leading-tight">{{ auth()->user()->name }}</span>
                    </div>
                    <span class="text-xs text-slate-500 truncate block mt-0.5">{{ auth()->user()->email }}</span>
                </div>
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]" title="Akun Aktif"></span>
            </div>

            @if($currentActiveVehicle)
            <div class="mt-3 pt-2.5 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                <span class="text-slate-500 flex items-center gap-1.5 font-medium truncate">
                    <i class="ph-bold {{ $currentActiveVehicle->vehicle_category === 'mobil' ? 'ph-car' : 'ph-motorcycle' }} text-blue-600"></i>
                    <span class="truncate">{{ $currentActiveVehicle->motor_name }}</span>
                </span>
                <span class="font-mono font-bold text-slate-700 shrink-0 bg-white px-2 py-0.5 rounded-lg border border-slate-200/60">
                    {{ number_format($currentActiveVehicle->current_km, 0, ',', '.') }} KM
                </span>
            </div>
            @endif
        </div>

        <!-- Navigation Links -->
        <nav class="space-y-1.5">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2 block">
                Menu Utama
            </span>

            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                <i class="ph-bold ph-gauge text-xl {{ request()->routeIs('dashboard') ? 'text-white' : 'text-blue-600' }}"></i>
                <span>Dashboard Armada</span>
            </a>
            
            <a href="{{ route('user.history') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('user.history') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                <i class="ph-bold ph-clock-counter-clockwise text-xl {{ request()->routeIs('user.history') ? 'text-white' : 'text-emerald-600' }}"></i>
                <span>Riwayat Servis</span>
            </a>

            <a href="{{ route('user.chat') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('user.chat') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                <i class="ph-bold ph-robot text-xl {{ request()->routeIs('user.chat') ? 'text-white' : 'text-purple-600' }}"></i>
                <div class="flex-1 flex items-center justify-between">
                    <span>Tanya Bang OTO</span>
                    <span class="text-[9px] {{ request()->routeIs('user.chat') ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-700' }} font-mono font-bold px-1.5 py-0.5 rounded">AI</span>
                </div>
            </a>

            <a href="{{ route('user.recommendations') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('user.recommendations*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                <i class="ph-bold ph-lightbulb-filament text-xl {{ request()->routeIs('user.recommendations*') ? 'text-white' : 'text-amber-500' }}"></i>
                <span>Tips & Panduan</span>
            </a>
        </nav>
    </div>

    <!-- Bottom Actions (Pinned to Bottom) -->
    <div class="mt-auto pt-6 border-t border-slate-200/80 space-y-2.5">
        <a href="{{ route('vehicle.register') }}" class="flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100/90 border border-blue-200/80 transition-all shadow-sm">
            <i class="ph-bold ph-plus-circle text-base"></i>
            <span>Tambah Kendaraan Baru</span>
        </a>

        <!-- PWA Manual Install Trigger Button -->
        <button type="button" id="sidebar-pwa-install-btn" onclick="triggerPwaInstall()" class="hidden items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200/90 border border-slate-200 transition-all w-full">
            <i class="ph-bold ph-download-simple text-base text-blue-600"></i>
            <span>Pasang Aplikasi (PWA)</span>
        </button>

        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="flex items-center justify-center gap-2 px-3.5 py-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50/80 w-full rounded-xl transition-all font-semibold text-xs border border-transparent hover:border-rose-200/60">
                <i class="ph-bold ph-sign-out text-base"></i>
                <span>Keluar Akun</span>
            </button>
        </form>

        <p class="text-[10px] text-slate-400 text-center pt-1 font-medium">OtoKeep • Smart Vehicle Care</p>
    </div>
</aside>

<script>
    function toggleUserSidebar() {
        const sidebar = document.getElementById('user-sidebar');
        const backdrop = document.getElementById('user-sidebar-backdrop');
        if (!sidebar || !backdrop) return;
        const isHidden = sidebar.classList.contains('-translate-x-full');

        if (isHidden) {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        }
    }
</script>
