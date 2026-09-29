<!-- Mobile Overlay Backdrop -->
<div id="admin-sidebar-backdrop" onclick="toggleAdminSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden lg:hidden transition-all duration-300"></div>

<!-- Admin Sidebar Drawer -->
<aside id="admin-sidebar" class="fixed top-0 bottom-0 left-0 w-72 bg-white border-r border-slate-200/80 flex flex-col justify-between py-6 px-4 z-50 transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:shrink-0 overflow-y-auto shadow-soft-sm select-none">
    <div>
        <!-- Brand Logo Header -->
        <div class="flex items-center justify-between px-2 mb-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center group">
                <img src="{{ asset('assets/images/otokeep-logo-horizontal.png') }}?v=5" alt="OtoKeep Logo" class="h-11 w-auto object-contain">
            </a>
            <!-- Mobile Close Button -->
            <button onclick="toggleAdminSidebar()" class="lg:hidden text-slate-400 hover:text-slate-700 p-2 rounded-xl bg-slate-100 transition-all">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>

        <!-- Admin Profile Mini-Card -->
        <div class="mb-6 p-3 bg-slate-50 border border-slate-200/80 rounded-2xl flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600/10 border border-blue-600/20 flex items-center justify-center text-blue-700 font-bold text-sm">
                AD
            </div>
            <div class="overflow-hidden flex-1">
                <span class="text-sm font-bold text-slate-900 block truncate">{{ auth()->user()->name }}</span>
                <span class="text-xs text-slate-500 truncate block">{{ auth()->user()->email }}</span>
            </div>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        </div>

        <!-- Navigation Menu -->
        <nav class="space-y-6">
            <div>
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase px-3 mb-2 block">
                    Overview & Fleet Analytics
                </span>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3.5 px-3.5 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }} rounded-xl text-sm transition-all">
                        <i class="ph-bold ph-chart-line-up text-xl"></i>
                        <span>Dashboard Armada</span>
                    </a>
                </div>
            </div>

            <div>
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase px-3 mb-2 block">
                    Manajemen Konten
                </span>
                <div class="space-y-1">
                    <a href="{{ route('admin.categories') }}" class="flex items-center gap-3.5 px-3.5 py-3 {{ request()->routeIs('admin.categories*') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }} rounded-xl text-sm transition-all">
                        <i class="ph-bold ph-squares-four text-xl"></i>
                        <span>Kategori Servis</span>
                    </a>
                    <a href="{{ route('admin.recommendations') }}" class="flex items-center gap-3.5 px-3.5 py-3 {{ request()->routeIs('admin.recommendations*') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }} rounded-xl text-sm transition-all">
                        <i class="ph-bold ph-article text-xl"></i>
                        <span>CMS Rekomendasi & Tips</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Sidebar Bottom Footer -->
    <div class="pt-6 border-t border-slate-200/80 space-y-3">
        <div class="flex items-center justify-between px-3 text-[11px] text-slate-500 font-mono">
            <span>Status Sistem:</span>
            <span class="text-emerald-600 font-bold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Online
            </span>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="flex items-center justify-center gap-2 px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 w-full rounded-xl transition-all font-semibold text-xs border border-rose-200/60">
                <i class="ph-bold ph-sign-out text-base"></i>
                <span>Keluar Akun</span>
            </button>
        </form>
    </div>
</aside>

<script>
    function toggleAdminSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const backdrop = document.getElementById('admin-sidebar-backdrop');
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
