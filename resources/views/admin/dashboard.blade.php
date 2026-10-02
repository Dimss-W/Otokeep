@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col lg:flex-row antialiased">
    <!-- Dedicated Admin Sidebar Component -->
    @include('layouts.admin_sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto max-h-screen bg-slate-50/60">
        <!-- Top App Bar (Solid, Clean & Crisp) -->
        <header class="sticky top-0 z-30 bg-white border-b border-slate-200/90 px-6 py-4 flex items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-4">
                <!-- Hamburger Button (Mobile Only) -->
                <button onclick="toggleAdminSidebar()" class="lg:hidden p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all border border-slate-200">
                    <i class="ph-bold ph-list text-xl"></i>
                </button>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/images/otokeep-icon.png') }}?v=4" alt="OtoKeep Icon" class="w-9 h-9 object-contain hidden sm:block">
                    <div>
                        <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                            <span>Pusat Kendali Admin</span>
                            <span class="text-[10px] bg-emerald-50 text-emerald-700 font-mono font-bold px-2 py-0.5 rounded-full border border-emerald-200">MONITORING</span>
                        </h1>
                        <p class="text-xs text-slate-500 hidden sm:block">Pemantauan performa platform, statistik armada kendaraan, dan manajemen konten.</p>
                    </div>
                </div>
            </div>

            <!-- Header Right Section -->
            <div class="flex items-center gap-3">
                <!-- Live Realtime Sync Status Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-50/90 border border-emerald-200 text-xs text-emerald-800 font-medium shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="font-bold text-[11px] tracking-wide">LIVE</span>
                    <span id="realtime-sync-time" class="font-mono text-[11px] text-emerald-700 hidden sm:inline">{{ now()->setTimezone('Asia/Jakarta')->format('H:i:s') }} WIB</span>
                    <button type="button" onclick="fetchRealtimeDashboardData(true)" id="btn-manual-sync" title="Sinkronkan Data Sekarang" class="ml-0.5 p-1 hover:bg-emerald-100 rounded-lg text-emerald-700 transition-all">
                        <i class="ph-bold ph-arrows-clockwise text-xs" id="icon-manual-sync"></i>
                    </button>
                </div>

                <!-- Admin Profile Avatar -->
                <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-white font-black text-xs shadow-sm">
                        AD
                    </div>
                    <div class="hidden xl:block text-left">
                        <span class="text-xs font-bold text-slate-900 block leading-tight">Admin OtoKeep</span>
                        <span class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Superadmin
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body Container (Clean Spacing) -->
        <main class="flex-1 p-5 md:p-8 space-y-6 max-w-7xl w-full mx-auto">

            <!-- 4 Top KPI Stat Cards (Simpel, Kompak & Rapi) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Pengguna Terdaftar -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-soft-sm hover:shadow-soft-md transition-all flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Pengguna</span>
                        <div id="kpi-user-count" class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ number_format($userCount) }}</div>
                        <span class="text-[11px] text-slate-500 mt-0.5 block">Akun pengguna aktif</span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0 border border-blue-100">
                        <i class="ph-bold ph-users"></i>
                    </div>
                </div>

                <!-- Card 2: Armada Kendaraan -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-soft-sm hover:shadow-soft-md transition-all flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Armada Terdaftar</span>
                        <div id="kpi-vehicle-count" class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ number_format($vehicleCount) }}</div>
                        <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-2">
                            <span class="text-blue-600 font-bold"><span id="kpi-motor-count">{{ $motorCount }}</span> Motor</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-emerald-600 font-bold"><span id="kpi-mobil-count">{{ $mobilCount }}</span> Mobil</span>
                        </div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0 border border-indigo-100">
                        <i class="ph-bold ph-car-profile"></i>
                    </div>
                </div>

                <!-- Card 3: Pemantauan Servis -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-soft-sm hover:shadow-soft-md transition-all flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Pemantauan Servis</span>
                        <div id="kpi-services-count" class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ number_format($totalServicesActive) }}</div>
                        <span class="text-[11px] text-slate-500 mt-0.5 block">
                            <span class="text-emerald-600 font-bold" id="kpi-history-count">{{ number_format($totalHistoryCount) }}</span> Log riwayat selesai
                        </span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0 border border-emerald-100">
                        <i class="ph-bold ph-wrench"></i>
                    </div>
                </div>

                <!-- Card 4: Sesi AI & Master -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-soft-sm hover:shadow-soft-md transition-all flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Interaksi AI Bang OTO</span>
                        <div id="kpi-chat-count" class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ number_format($totalChatCount) }}</div>
                        <span class="text-[11px] text-slate-500 mt-0.5 block">
                            <span class="text-amber-600 font-bold" id="kpi-categories-count">{{ $categoriesCount }}</span> Master Kategori
                        </span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0 border border-amber-100">
                        <i class="ph-bold ph-robot"></i>
                    </div>
                </div>
            </div>

            <!-- GRAFIK UTAMA (HERO CHART): Tren Pertumbuhan Armada Kendaraan -->
            <div class="bg-white p-6 md:p-7 rounded-3xl border border-slate-200/80 shadow-soft-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="ph-bold ph-trend-up text-brand-600"></i>
                            Tren Pendaftaran Unit Armada
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5" id="trend-summary-text">Laju penambahan unit kendaraan baru harian.</p>
                    </div>

                    <!-- Clean Single-Row Timeframe Filter (3 Pilihan Utama: 7H, 30H, 90H) -->
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                        <button type="button" onclick="setTrendDays(7)" id="btn-trend-7" class="px-3 py-1 text-xs font-bold rounded-lg transition-all text-slate-500 hover:text-slate-900">7 Hari</button>
                        <button type="button" onclick="setTrendDays(30)" id="btn-trend-30" class="px-3 py-1 text-xs font-bold rounded-lg transition-all bg-white text-brand-700 shadow-sm">30 Hari</button>
                        <button type="button" onclick="setTrendDays(90)" id="btn-trend-90" class="px-3 py-1 text-xs font-bold rounded-lg transition-all text-slate-500 hover:text-slate-900">90 Hari</button>
                    </div>
                </div>

                <div class="h-[270px] w-full">
                    <canvas id="fleetGrowthChart"></canvas>
                </div>
            </div>

            <!-- GRID 2 KOLOM: Model Terpopuler & Karakteristik Armada (Tidak Numpuk) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- KARTU 1: Model Kendaraan Terpopuler (Satu baris filter rapi) -->
                <div class="bg-white p-6 md:p-7 rounded-3xl border border-slate-200/80 shadow-soft-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                    <i class="ph-bold ph-star text-amber-500"></i>
                                    Model Kendaraan Terpopuler
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">Peringkat seri atau tipe kendaraan dominan.</p>
                            </div>

                            <!-- Single Clean Filter: Semua, Motor, Mobil -->
                            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                                <button type="button" onclick="setPopCategory('all')" id="btn-pop-cat-all" class="px-2.5 py-1 text-xs font-bold rounded-lg transition-all bg-white text-amber-700 shadow-sm">Semua</button>
                                <button type="button" onclick="setPopCategory('motor')" id="btn-pop-cat-motor" class="px-2.5 py-1 text-xs font-bold rounded-lg transition-all text-slate-500 hover:text-slate-900">Motor</button>
                                <button type="button" onclick="setPopCategory('mobil')" id="btn-pop-cat-mobil" class="px-2.5 py-1 text-xs font-bold rounded-lg transition-all text-slate-500 hover:text-slate-900">Mobil</button>
                            </div>
                        </div>

                        <div class="h-[260px]">
                            @if(count($popularModels['all']) > 0)
                                <canvas id="popularModelsChart"></canvas>
                            @else
                                <div class="h-full flex flex-col items-center justify-center text-center p-6 text-slate-400">
                                    <i class="ph ph-database text-4xl mb-2 text-slate-300"></i>
                                    <p class="text-xs">Belum ada model kendaraan terdaftar.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- KARTU 2: Karakteristik Armada (Tab Bersih: Odometer vs Transmisi) -->
                <div class="bg-white p-6 md:p-7 rounded-3xl border border-slate-200/80 shadow-soft-sm flex flex-col justify-between">
                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                    <i class="ph-bold ph-speedometer text-brand-600"></i>
                                    Karakteristik Armada
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5" id="char-subtitle">Distribusi jarak tempuh (odometer) riil armada.</p>
                            </div>

                            <!-- Tab Switcher: Odometer vs Tipe Transmisi -->
                            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                                <button type="button" onclick="switchCharTab('odometer')" id="btn-tab-odo" class="px-2.5 py-1 text-xs font-bold rounded-lg transition-all bg-white text-brand-700 shadow-sm">
                                    Odometer
                                </button>
                                <button type="button" onclick="switchCharTab('transmission')" id="btn-tab-trans" class="px-2.5 py-1 text-xs font-bold rounded-lg transition-all text-slate-500 hover:text-slate-900">
                                    Transmisi
                                </button>
                            </div>
                        </div>

                        <!-- PANEL 1: ODOMETER -->
                        <div id="panel-odometer" class="space-y-4">
                            <!-- Category Filter Odometer -->
                            <div class="flex justify-end">
                                <div class="flex items-center gap-1 bg-slate-50 border border-slate-200/80 p-0.5 rounded-lg text-[11px]">
                                    <button type="button" onclick="switchOdometerCategory('all')" id="btn-odo-all" class="px-2 py-0.5 font-bold rounded bg-white text-brand-700 shadow-xs">Semua</button>
                                    <button type="button" onclick="switchOdometerCategory('motor')" id="btn-odo-motor" class="px-2 py-0.5 font-bold rounded text-slate-500 hover:text-slate-800">Motor</button>
                                    <button type="button" onclick="switchOdometerCategory('mobil')" id="btn-odo-mobil" class="px-2 py-0.5 font-bold rounded text-slate-500 hover:text-slate-800">Mobil</button>
                                </div>
                            </div>

                            <div class="relative h-[160px] flex items-center justify-center">
                                @if($vehicleCount > 0)
                                    <canvas id="odometerRangeChart"></canvas>
                                @else
                                    <p class="text-xs text-slate-400">Belum ada armada terdaftar.</p>
                                @endif
                            </div>

                            <!-- Clean 4-Metric Grid -->
                            <div class="grid grid-cols-4 gap-2 pt-2 border-t border-slate-100 text-center">
                                <div class="bg-emerald-50/70 border border-emerald-100 p-2 rounded-xl">
                                    <span class="block text-[10px] text-emerald-700 font-bold">&lt; 10k KM</span>
                                    <span id="odo-val-low" class="text-xs font-black font-mono text-emerald-900">{{ $odoRanges['all']['low'] }}</span>
                                </div>
                                <div class="bg-blue-50/70 border border-blue-100 p-2 rounded-xl">
                                    <span class="block text-[10px] text-blue-700 font-bold">10k - 25k</span>
                                    <span id="odo-val-medium" class="text-xs font-black font-mono text-blue-900">{{ $odoRanges['all']['medium'] }}</span>
                                </div>
                                <div class="bg-amber-50/70 border border-amber-100 p-2 rounded-xl">
                                    <span class="block text-[10px] text-amber-700 font-bold">25k - 50k</span>
                                    <span id="odo-val-active" class="text-xs font-black font-mono text-amber-900">{{ $odoRanges['all']['active'] }}</span>
                                </div>
                                <div class="bg-rose-50/70 border border-rose-100 p-2 rounded-xl">
                                    <span class="block text-[10px] text-rose-700 font-bold">&gt; 50k KM</span>
                                    <span id="odo-val-high" class="text-xs font-black font-mono text-rose-900">{{ $odoRanges['all']['high'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- PANEL 2: TRANSMISI (Hidden by default) -->
                        <div id="panel-transmission" class="hidden space-y-4">
                            <!-- Category Filter Transmisi -->
                            <div class="flex justify-end">
                                <div class="flex items-center gap-1 bg-slate-50 border border-slate-200/80 p-0.5 rounded-lg text-[11px]">
                                    <button type="button" onclick="switchVehicleTypeCategory('all')" id="btn-type-all" class="px-2 py-0.5 font-bold rounded bg-white text-brand-700 shadow-xs">Semua</button>
                                    <button type="button" onclick="switchVehicleTypeCategory('motor')" id="btn-type-motor" class="px-2 py-0.5 font-bold rounded text-slate-500 hover:text-slate-800">Motor</button>
                                    <button type="button" onclick="switchVehicleTypeCategory('mobil')" id="btn-type-mobil" class="px-2 py-0.5 font-bold rounded text-slate-500 hover:text-slate-800">Mobil</button>
                                </div>
                            </div>

                            <div class="h-[200px]">
                                <canvas id="vehicleTypeChart"></canvas>
                            </div>

                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <span>Perbandingan Transmisi</span>
                                <span id="type-badge-count" class="text-brand-700 bg-brand-50 px-2.5 py-0.5 rounded-full font-bold border border-brand-200 font-mono">{{ count($typeStats['all']) }} Varian</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- SHORTCUTS MANAJEMEN KONTEN (Ringkas & Bersih) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <!-- Card Konten 1: Kategori Servis -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-brand-500/30 hover:shadow-soft-md transition-all shadow-soft-sm flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-xl shrink-0">
                            <i class="ph-bold ph-squares-four"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">Master Kategori Servis</h4>
                            <p class="text-xs text-slate-500">Komponen servis standar (Oli, CVT, Rem, dll.).</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.categories') }}" class="px-3.5 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center gap-1 shrink-0">
                        <span>Buka</span> <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>

                <!-- Card Konten 2: CMS Rekomendasi & Tips -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-brand-500/30 hover:shadow-soft-md transition-all shadow-soft-sm flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-brand-50 text-brand-600 border border-brand-100 flex items-center justify-center text-xl shrink-0">
                            <i class="ph-bold ph-article"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">CMS Rekomendasi & Tips</h4>
                            <p class="text-xs text-slate-500">Artikel edukasi dan tips teknis kendaraan.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.recommendations') }}" class="px-3.5 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center gap-1 shrink-0">
                        <span>Buka</span> <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- Chart.js Fleet Data Scripts (REALTIME, SIMPLE & UNCLUTTERED) -->
<script>
    const formatDate = (dateStr) => {
        if (!dateStr || !dateStr.includes('-')) return dateStr;
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    };

    Chart.defaults.color = '#64748B';
    Chart.defaults.font.family = '"Plus Jakarta Sans", Inter, sans-serif';
    Chart.defaults.font.size = 11;

    // Helper for pill active styling
    function setActivePill(buttonId, groupIds, activeClass = 'bg-white text-brand-700 shadow-sm') {
        groupIds.forEach(id => {
            const btn = document.getElementById(id);
            if (!btn) return;
            if (id === buttonId) {
                btn.className = 'px-3 py-1 text-xs font-bold rounded-lg transition-all ' + activeClass;
            } else {
                btn.className = 'px-3 py-1 text-xs font-bold rounded-lg transition-all text-slate-500 hover:text-slate-900';
            }
        });
    }

    // Tab switcher between Odometer and Transmission
    function switchCharTab(tab) {
        const panelOdo = document.getElementById('panel-odometer');
        const panelTrans = document.getElementById('panel-transmission');
        const btnOdo = document.getElementById('btn-tab-odo');
        const btnTrans = document.getElementById('btn-tab-trans');
        const subtitle = document.getElementById('char-subtitle');

        if (tab === 'odometer') {
            panelOdo.classList.remove('hidden');
            panelTrans.classList.add('hidden');
            btnOdo.className = 'px-2.5 py-1 text-xs font-bold rounded-lg transition-all bg-white text-brand-700 shadow-sm';
            btnTrans.className = 'px-2.5 py-1 text-xs font-bold rounded-lg transition-all text-slate-500 hover:text-slate-900';
            if (subtitle) subtitle.innerText = 'Distribusi jarak tempuh (odometer) riil armada.';
            if (odoChart) odoChart.resize();
        } else {
            panelOdo.classList.add('hidden');
            panelTrans.classList.remove('hidden');
            btnTrans.className = 'px-2.5 py-1 text-xs font-bold rounded-lg transition-all bg-white text-brand-700 shadow-sm';
            btnOdo.className = 'px-2.5 py-1 text-xs font-bold rounded-lg transition-all text-slate-500 hover:text-slate-900';
            if (subtitle) subtitle.innerText = 'Proporsi jenis transmisi matic dan manual armada.';
            if (typeChart) typeChart.resize();
        }
    }

    // Active state tracker for live updates
    let currentOdoCat = 'all';
    let currentTypeCat = 'all';
    let currentPopCategory = 'all';
    let currentTrendDays = 30;

    // Global Data References
    let allOdoData = @json($odoRanges);
    let allTypeStats = @json($typeStats);
    let allPopularModels = @json($popularModels);
    let allFleetTrend = @json($fleetGrowth);

    // ==========================================
    // 1. Chart Hero: Tren Pendaftaran Armada
    // ==========================================
    let fleetChart = null;
    const fleetCanvas = document.getElementById('fleetGrowthChart');
    if (fleetCanvas) {
        const fleetCtx = fleetCanvas.getContext('2d');
        const initialTrendSlice = allFleetTrend.slice(-currentTrendDays);

        fleetChart = new Chart(fleetCtx, {
            type: 'line',
            data: {
                labels: initialTrendSlice.map(f => f.date),
                datasets: [
                    {
                        label: 'Armada Motor',
                        data: initialTrendSlice.map(f => f.motor),
                        borderColor: '#830000',
                        backgroundColor: 'rgba(131, 0, 0, 0.08)',
                        borderWidth: 2.5,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 2.5,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#830000'
                    },
                    {
                        label: 'Armada Mobil',
                        data: initialTrendSlice.map(f => f.mobil),
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.08)',
                        borderWidth: 2.5,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 2.5,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#10B981'
                    },
                    {
                        label: 'Total Armada',
                        data: initialTrendSlice.map(f => f.total),
                        borderColor: '#8B5CF6',
                        backgroundColor: 'transparent',
                        borderWidth: 1.5,
                        borderDash: [4, 4],
                        tension: 0.35,
                        fill: false,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        pointBackgroundColor: '#8B5CF6'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: { boxWidth: 10, usePointStyle: true, font: { weight: 'bold' }, color: '#475569' }
                    },
                    tooltip: {
                        callbacks: {
                            title: (items) => {
                                if (!items.length) return '';
                                const d = new Date(items[0].label);
                                return d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.8)' },
                        border: { display: false },
                        ticks: { stepSize: 1, color: '#64748B' }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { 
                            color: '#64748B',
                            maxTicksLimit: 12,
                            callback: function(val) { return formatDate(this.getLabelForValue(val)); } 
                        }
                    }
                }
            }
        });

        updateTrendSummaryText(initialTrendSlice);
    }

    function renderFleetTrendChart() {
        if (!fleetChart || !allFleetTrend) return;
        const sliced = allFleetTrend.slice(-currentTrendDays);
        fleetChart.data.labels = sliced.map(f => f.date);
        fleetChart.data.datasets[0].data = sliced.map(f => f.motor);
        fleetChart.data.datasets[1].data = sliced.map(f => f.mobil);
        fleetChart.data.datasets[2].data = sliced.map(f => f.total);
        fleetChart.update();
        updateTrendSummaryText(sliced);
    }

    function setTrendDays(days) {
        currentTrendDays = days;
        renderFleetTrendChart();
        setActivePill(`btn-trend-${days}`, ['btn-trend-7', 'btn-trend-30', 'btn-trend-90'], 'bg-white text-brand-700 shadow-sm');
    }

    function updateTrendSummaryText(sliced) {
        const totalRegistered = sliced.reduce((acc, curr) => acc + curr.total, 0);
        const motorTotal = sliced.reduce((acc, curr) => acc + curr.motor, 0);
        const mobilTotal = sliced.reduce((acc, curr) => acc + curr.mobil, 0);
        const el = document.getElementById('trend-summary-text');
        if (el) {
            el.innerHTML = `Pendaftaran ${currentTrendDays} hari terakhir: <strong class="text-slate-800">${totalRegistered} unit</strong> (<span class="text-blue-600 font-semibold">${motorTotal} Motor</span>, <span class="text-emerald-600 font-semibold">${mobilTotal} Mobil</span>).`;
        }
    }

    // ==========================================
    // 2. Chart Model Kendaraan Terpopuler
    // ==========================================
    let popChart = null;
    const popCanvas = document.getElementById('popularModelsChart');
    if (popCanvas && allPopularModels.all && allPopularModels.all.length > 0) {
        const popCtx = popCanvas.getContext('2d');
        const initialPopSlice = allPopularModels.all.slice(0, 8);

        popChart = new Chart(popCtx, {
            type: 'bar',
            data: {
                labels: initialPopSlice.map(p => p.motor_name),
                datasets: [{
                    label: 'Unit Terdaftar',
                    data: initialPopSlice.map(p => p.aggregate),
                    backgroundColor: 'rgba(245, 158, 11, 0.85)',
                    borderColor: '#F59E0B',
                    borderWidth: 1.5,
                    borderRadius: 8,
                    maxBarThickness: 28
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ` ${ctx.raw} unit terdaftar`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.8)' },
                        border: { display: false },
                        ticks: { color: '#64748B' }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { 
                            color: '#64748B',
                            maxRotation: 35,
                            minRotation: 0,
                            autoSkip: false
                        }
                    }
                }
            }
        });
    }

    function renderPopularChart() {
        if (!popChart) return;
        const source = (allPopularModels && allPopularModels[currentPopCategory]) || (allPopularModels && allPopularModels.all) || [];
        const sliced = source.slice(0, 8);

        popChart.data.labels = sliced.map(p => p.motor_name);
        popChart.data.datasets[0].data = sliced.map(p => p.aggregate);
        popChart.update();
    }

    function setPopCategory(category) {
        currentPopCategory = category;
        renderPopularChart();
        setActivePill(`btn-pop-cat-${category}`, ['btn-pop-cat-all', 'btn-pop-cat-motor', 'btn-pop-cat-mobil'], 'bg-white text-amber-700 shadow-sm');
    }

    // ==========================================
    // 3. Chart Odometer (Donut)
    // ==========================================
    let odoChart = null;
    @if($vehicleCount > 0)
    const odoCanvas = document.getElementById('odometerRangeChart');
    if (odoCanvas) {
        const odoCtx = odoCanvas.getContext('2d');
        odoChart = new Chart(odoCtx, {
            type: 'doughnut',
            data: {
                labels: ['< 10rb KM', '10rb - 25rb KM', '25rb - 50rb KM', '> 50rb KM'],
                datasets: [{
                    data: [
                        allOdoData.all.low,
                        allOdoData.all.medium,
                        allOdoData.all.active,
                        allOdoData.all.high
                    ],
                    backgroundColor: ['#10B981', '#830000', '#F59E0B', '#F43F5E'],
                    borderColor: '#FFFFFF',
                    borderWidth: 3,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const val = ctx.raw || 0;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${val.toLocaleString('id-ID')} unit (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    function switchOdometerCategory(category, changePill = true) {
        currentOdoCat = category;
        const odo = (allOdoData && allOdoData[category]) || (allOdoData && allOdoData.all);
        if (odo && odoChart) {
            odoChart.data.datasets[0].data = [odo.low, odo.medium, odo.active, odo.high];
            odoChart.update();
        }
        if (odo) {
            const lowEl = document.getElementById('odo-val-low');
            const medEl = document.getElementById('odo-val-medium');
            const actEl = document.getElementById('odo-val-active');
            const highEl = document.getElementById('odo-val-high');
            if (lowEl) lowEl.innerText = odo.low.toLocaleString('id-ID');
            if (medEl) medEl.innerText = odo.medium.toLocaleString('id-ID');
            if (actEl) actEl.innerText = odo.active.toLocaleString('id-ID');
            if (highEl) highEl.innerText = odo.high.toLocaleString('id-ID');
        }

        if (changePill) {
            setActivePill(`btn-odo-${category}`, ['btn-odo-all', 'btn-odo-motor', 'btn-odo-mobil'], 'bg-white text-brand-700 shadow-xs');
        }
    }
    @endif

    // ==========================================
    // 4. Chart Transmisi & Tipe
    // ==========================================
    let typeChart = null;
    const typeCanvas = document.getElementById('vehicleTypeChart');
    if (typeCanvas && allTypeStats.all && allTypeStats.all.length > 0) {
        const typeCtx = typeCanvas.getContext('2d');
        typeChart = new Chart(typeCtx, {
            type: 'bar',
            data: {
                labels: allTypeStats.all.map(t => (t.type ? t.type.toUpperCase() : 'STANDAR') + ' (' + (t.vehicle_category === 'mobil' ? 'Mobil' : 'Motor') + ')'),
                datasets: [{
                    label: 'Jumlah Unit',
                    data: allTypeStats.all.map(t => t.aggregate),
                    backgroundColor: 'rgba(16, 185, 129, 0.85)',
                    borderColor: '#10B981',
                    borderWidth: 1.5,
                    borderRadius: 8,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ` ${ctx.raw} unit terdaftar`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.8)' },
                        border: { display: false },
                        ticks: { color: '#64748B' }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#64748B' }
                    }
                }
            }
        });
    }

    function switchVehicleTypeCategory(category, changePill = true) {
        currentTypeCat = category;
        const items = (allTypeStats && allTypeStats[category]) || (allTypeStats && allTypeStats.all) || [];
        if (typeChart) {
            typeChart.data.labels = items.map(t => (t.type ? t.type.toUpperCase() : 'STANDAR') + ' (' + (t.vehicle_category === 'mobil' ? 'Mobil' : 'Motor') + ')');
            typeChart.data.datasets[0].data = items.map(t => t.aggregate);
            
            if (category === 'motor') {
                typeChart.data.datasets[0].backgroundColor = 'rgba(131, 0, 0, 0.85)';
                typeChart.data.datasets[0].borderColor = '#830000';
            } else if (category === 'mobil') {
                typeChart.data.datasets[0].backgroundColor = 'rgba(14, 165, 233, 0.85)';
                typeChart.data.datasets[0].borderColor = '#0EA5E9';
            } else {
                typeChart.data.datasets[0].backgroundColor = 'rgba(16, 185, 129, 0.85)';
                typeChart.data.datasets[0].borderColor = '#10B981';
            }

            typeChart.update();
        }
        const badge = document.getElementById('type-badge-count');
        if (badge) badge.innerText = `${items.length} Varian`;
        
        if (changePill) {
            setActivePill(`btn-type-${category}`, ['btn-type-all', 'btn-type-motor', 'btn-type-mobil'], 'bg-white text-brand-700 shadow-xs');
        }
    }

    // ==========================================
    // 5. Realtime Live Sync Engine
    // ==========================================
    let isRealtimeSyncing = false;

    async function fetchRealtimeDashboardData(isManual = false) {
        if (isRealtimeSyncing) return;
        isRealtimeSyncing = true;

        const syncIcon = document.getElementById('icon-manual-sync');
        if (isManual && syncIcon) {
            syncIcon.classList.add('animate-spin');
        }

        try {
            const response = await fetch("{{ route('admin.dashboard.realtime') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('HTTP ' + response.status);
            const data = await response.json();

            if (data.status === 'success') {
                const formatNum = (num) => Number(num).toLocaleString('id-ID');

                // 1. Update Top Stat Cards Live
                const userEl = document.getElementById('kpi-user-count');
                const vehEl = document.getElementById('kpi-vehicle-count');
                const motorEl = document.getElementById('kpi-motor-count');
                const mobilEl = document.getElementById('kpi-mobil-count');
                const srvEl = document.getElementById('kpi-services-count');
                const histEl = document.getElementById('kpi-history-count');
                const chatEl = document.getElementById('kpi-chat-count');
                const catEl = document.getElementById('kpi-categories-count');
                const syncTimeEl = document.getElementById('realtime-sync-time');

                if (userEl) userEl.innerText = formatNum(data.userCount);
                if (vehEl) vehEl.innerText = formatNum(data.vehicleCount);
                if (motorEl) motorEl.innerText = data.motorCount;
                if (mobilEl) mobilEl.innerText = data.mobilCount;
                if (srvEl) srvEl.innerText = formatNum(data.totalServicesActive);
                if (histEl) histEl.innerText = formatNum(data.totalHistoryCount);
                if (chatEl) chatEl.innerText = formatNum(data.totalChatCount);
                if (catEl) catEl.innerText = data.categoriesCount;
                if (syncTimeEl && data.server_time) syncTimeEl.innerText = `${data.server_time} WIB`;

                // 2. Update Global Chart Datasets
                allOdoData = data.odoRanges;
                allTypeStats = data.typeStats;
                allPopularModels = data.popularModels;
                allFleetTrend = data.fleetGrowth;

                // 3. Re-render Charts with Active User Filters
                renderFleetTrendChart();
                renderPopularChart();
                @if($vehicleCount > 0)
                switchOdometerCategory(currentOdoCat, false);
                @endif
                switchVehicleTypeCategory(currentTypeCat, false);
            }
        } catch (err) {
            console.warn('Realtime dashboard sync:', err);
        } finally {
            isRealtimeSyncing = false;
            if (isManual && syncIcon) {
                setTimeout(() => syncIcon.classList.remove('animate-spin'), 400);
            }
        }
    }

    // Auto-poll every 5 seconds for real-time synchronization
    const realtimePoller = setInterval(() => fetchRealtimeDashboardData(false), 5000);

    // Sync immediately when admin focuses back on tab
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            fetchRealtimeDashboardData(false);
        }
    });
</script>
@endsection
