@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-transparent flex flex-col lg:flex-row antialiased text-slate-800">
    @include('layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
        <!-- Multi-Vehicle Switcher Bar -->
        @if(isset($vehicles) && $vehicles->count() > 1)
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4 p-4 bg-gradient-to-r from-white via-white to-slate-50/80 rounded-2xl border border-slate-200/80 shadow-soft-sm">
            <div class="flex items-center gap-3">
                <span class="text-xs uppercase font-bold text-slate-500 tracking-wider flex items-center gap-1.5">
                    <i class="ph-bold ph-garage text-blue-600"></i> Kendaraan Aktif:
                </span>
                <div class="flex flex-wrap gap-2">
                    @foreach($vehicles as $v)
                    <form action="{{ route('vehicle.switch', $v->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $v->id === $vehicle->id ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/25' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            <i class="{{ $v->vehicle_category === 'mobil' ? 'ph-bold ph-car' : 'ph-bold ph-motorcycle' }}"></i>
                            {{ $v->motor_name }}
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('vehicle.register') }}" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 border border-blue-200/80">
                <i class="ph-bold ph-plus"></i> Tambah Kendaraan
            </a>
        </div>
        @endif

        <!-- TOP WELCOME & METRIC SECTION (Aligned & Balanced) -->
        <div class="stagger-1 grid grid-cols-1 lg:grid-cols-12 gap-5 mb-6">
            <!-- Left Welcome Hero Banner (7 cols) -->
            <div class="lg:col-span-7 bg-gradient-to-br from-white via-white to-blue-50/40 p-6 md:p-7 rounded-3xl border border-slate-200/80 shadow-soft-sm flex flex-col justify-between relative overflow-hidden group">
                <div class="absolute -right-10 -top-10 w-56 h-56 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 rounded-full blur-2xl group-hover:scale-110 transition-all pointer-events-none"></div>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border {{ $vehicle->vehicle_category === 'mobil' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                            <i class="{{ $vehicle->vehicle_category === 'mobil' ? 'ph-fill ph-car' : 'ph-fill ph-motorcycle' }} text-sm"></i>
                            <span>{{ ucfirst($vehicle->vehicle_category) }} • {{ $vehicle->motor_name }}</span>
                        </div>
                        <span class="text-xs font-mono text-slate-400 bg-slate-50 px-2.5 py-0.5 rounded-lg border border-slate-200/60">ID #{{ $vehicle->id }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Halo, {{ auth()->user()->name }} 👋
                    </h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1.5 leading-relaxed">
                        Pantau kondisi armada <span class="text-slate-900 font-bold">{{ $vehicle->motor_name }}</span> secara real-time. Semua jadwal servis berkala aktif dan otomatis diperbarui.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ route('vehicle.register') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3.5 py-2 rounded-xl border border-blue-200/80 transition-all shadow-sm">
                        <i class="ph-bold ph-plus-circle text-sm"></i> Tambah Kendaraan Lain
                    </a>
                    <span class="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Sistem Aktif & Terhubung
                    </span>
                </div>
            </div>

            <!-- Right 2 Metric Cards (5 cols: 2 columns grid side-by-side) -->
            <div class="lg:col-span-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Card 1: Odometer Sekarang -->
                <div class="card-interactive bg-gradient-to-br from-white via-white to-blue-50/50 p-5 rounded-3xl border border-slate-200/80 shadow-soft-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Odometer Sekarang</span>
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-500 to-indigo-600 text-white flex items-center justify-center text-base shadow-sm shadow-blue-500/20">
                                <i class="ph-bold ph-gauge"></i>
                            </div>
                        </div>
                        <div id="odometer-display-main" class="text-2xl font-black text-slate-900 font-mono tracking-tight">
                            {{ number_format($vehicle->current_km, 0, ',', '.') }} <span class="text-xs font-bold text-slate-400 font-sans">KM</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Jarak tempuh riil armada</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center gap-2">
                        <button onclick="openLiveCameraModal()" class="flex-1 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white py-2 px-2.5 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-camera"></i> Scan AI
                        </button>
                        <button onclick="document.getElementById('modal-odo').classList.remove('hidden')" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition-all" title="Ketik Manual">
                            <i class="ph-bold ph-pencil-simple text-base"></i>
                        </button>
                    </div>
                </div>

                <!-- Card 2: Jadwal Notifikasi -->
                <div class="card-interactive bg-gradient-to-br from-white via-white to-amber-50/50 p-5 rounded-3xl border border-slate-200/80 shadow-soft-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jadwal Notifikasi</span>
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-500 text-white flex items-center justify-center text-base shadow-sm shadow-amber-500/20">
                                <i class="ph-bold ph-bell-ringing"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-black text-slate-900 font-mono tracking-tight">
                            {{ auth()->user()->notification_time }} <span class="text-xs font-bold text-slate-400 font-sans">WIB</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Waktu kirim alarm harian</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-100">
                        <button onclick="document.getElementById('modal-notif-time').classList.remove('hidden')" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 py-2 px-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 border border-slate-200">
                            <i class="ph-bold ph-clock"></i> Ubah Jam
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- WIDGET STATUS PAJAK STNK & 5 TAHUNAN -->
        <section class="stagger-2 card-interactive bg-gradient-to-r from-white via-white to-purple-50/40 p-5 md:p-6 rounded-3xl shadow-soft-sm mb-6 border border-slate-200/80 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-500 to-indigo-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-purple-500/20">
                    <i class="ph-bold ph-identification-card text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        Pajak Kendaraan & STNK
                        <span class="text-[10px] bg-slate-100 border border-slate-200 px-2 py-0.5 rounded text-slate-600 font-mono font-semibold">{{ $vehicle->motor_name }}</span>
                    </h3>
                    <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs">
                        <!-- Pajak Tahunan -->
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500 font-medium">Pajak Tahunan:</span>
                            @if($vehicle->stnk_tax_due_date)
                                @php
                                    $taxDays = now()->diffInDays($vehicle->stnk_tax_due_date, false);
                                @endphp
                                @if($taxDays < 0)
                                    <span class="bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-0.5 rounded-full font-bold text-[11px]">Terlewat {{ abs($taxDays) }} hari!</span>
                                @elseif($taxDays <= 30)
                                    <span class="bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 rounded-full font-bold text-[11px]">H-{{ $taxDays }} (Segera Bayar)</span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full font-bold text-[11px]">{{ $vehicle->stnk_tax_due_date->format('d M Y') }} ({{ $taxDays }} hari lagi)</span>
                                @endif
                            @else
                                <span class="bg-slate-100 text-slate-500 border border-slate-200 px-2.5 py-0.5 rounded-full font-medium text-[11px]">Belum diatur</span>
                            @endif
                        </div>
                        <span class="text-slate-300 hidden md:inline">•</span>
                        <!-- Pajak 5 Tahunan (Plat) -->
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500 font-medium">Ganti Plat (5 Th):</span>
                            @if($vehicle->five_year_tax_due_date)
                                <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full font-bold text-[11px]">{{ $vehicle->five_year_tax_due_date->format('d M Y') }}</span>
                            @else
                                <span class="bg-slate-100 text-slate-500 border border-slate-200 px-2.5 py-0.5 rounded-full font-medium text-[11px]">Belum diatur</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('history.service-book') }}" target="_blank" class="px-3.5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 shrink-0 border border-slate-200 shadow-sm">
                    <i class="ph-bold ph-file-pdf text-sm text-rose-500"></i> Buku Servis (PDF)
                </a>
                <button onclick="document.getElementById('modal-tax-dates').classList.remove('hidden')" class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 shrink-0 border border-slate-200 shadow-sm">
                    <i class="ph-bold ph-calendar-plus text-sm text-blue-600"></i> Atur Tanggal Pajak
                </button>
            </div>
        </section>

        <!-- WIDGET INTEGRASI AI SPEEDOMETER SCANNER & SMART NOTIFIKASI -->
        <section class="stagger-3 card-interactive bg-gradient-to-br from-white via-white to-emerald-50/25 p-6 md:p-8 rounded-3xl shadow-soft-sm mb-8 border border-slate-200/80 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/10 rounded-full blur-[80px] -z-10 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-emerald-500/10 rounded-full blur-[80px] -z-10 pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- Kolom Kiri: AI Speedometer Camera Scanner (7 Kolom) -->
                <div class="lg:col-span-7 lg:border-r lg:border-slate-100 lg:pr-8 flex flex-col justify-between space-y-6">
                    <div>
                        <div class="flex items-center gap-3.5 mb-3">
                            <div class="w-11 h-11 bg-emerald-50 border border-emerald-200/80 rounded-2xl flex items-center justify-center text-emerald-600 shrink-0 shadow-soft-sm">
                                <i class="ph-bold ph-camera text-2xl"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xl font-bold text-slate-900">AI Speedometer Scanner</h3>
                                    <span class="bg-blue-50 text-blue-700 border border-blue-200/80 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider flex items-center gap-1">
                                        <i class="ph-fill ph-sparkle"></i> Gemini Vision
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Update odometer otomatis cukup dengan memfoto panel speedometer motor atau mobil Anda.</p>
                            </div>
                        </div>

                        <!-- Info Card Fitur -->
                        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex flex-col sm:flex-row gap-4 items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0 border border-blue-100">
                                    <i class="ph-bold ph-gauge text-lg"></i>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Odometer Tercatat</span>
                                    <span class="text-base font-black text-slate-900 font-mono" id="scanner-current-km-badge">{{ number_format($vehicle->current_km, 0, ',', '.') }} KM</span>
                                </div>
                            </div>
                            <div class="text-right sm:border-l sm:border-slate-200 sm:pl-4">
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Metode Deteksi</span>
                                <span class="text-xs font-semibold text-emerald-600 flex items-center gap-1">
                                    <i class="ph-bold ph-check"></i> OCR Digital & Analog
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Kamera & Galeri -->
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Hidden File Inputs -->
                            <input type="file" id="camera-scan-input" accept="image/*" capture="environment" class="hidden" onchange="handleSpeedometerImageSelected(this)">
                            <input type="file" id="gallery-scan-input" accept="image/*" class="hidden" onchange="handleSpeedometerImageSelected(this)">

                            <!-- Tombol Buka Kamera Langsung (Live Viewfinder) -->
                            <button type="button" onclick="openLiveCameraModal()" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2 group text-sm">
                                <i class="ph-bold ph-camera text-lg group-hover:scale-110 transition-transform"></i>
                                <span>Buka Kamera (Live Scan)</span>
                            </button>

                            <!-- Tombol Pilih dari Galeri -->
                            <button type="button" onclick="document.getElementById('gallery-scan-input').click()" class="w-full py-3.5 px-4 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-bold rounded-2xl transition-all flex items-center justify-center gap-2 text-sm shadow-sm">
                                <i class="ph-bold ph-image text-lg text-blue-600"></i>
                                <span>Unggah dari Galeri</span>
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-500 italic flex items-center gap-1.5">
                            <i class="ph ph-info text-blue-600"></i> Tips: Pastikan angka ODO terlihat jelas, tidak goyang, dan tidak tertutup pantulan cahaya.
                        </p>
                    </div>
                </div>

                <!-- Kolom Kanan: Web Push Notification & Direct WhatsApp Hub (5 Kolom) -->
                <div class="lg:col-span-5 flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center gap-2.5 mb-2">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center">
                                <i class="ph-bold ph-bell-ringing text-lg"></i>
                            </div>
                            <h4 class="text-sm font-bold text-slate-900">Pengingat Otomatis Layar HP</h4>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Aktifkan notifikasi browser agar HP berbunyi saat servis sudah dekat. 100% gratis tanpa biaya gateway SMS / WA.
                        </p>
                    </div>

                    <!-- Push Notification Status Box -->
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-medium">Izin Notifikasi HP</span>
                            <span id="push-status-badge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                Belum Diaktifkan
                            </span>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button type="button" onclick="requestBrowserNotificationPermission()" id="btn-request-push" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs transition-all flex items-center justify-center gap-1.5 shadow-soft-sm">
                                <i class="ph-bold ph-bell text-sm"></i> Izinkan Notifikasi
                            </button>
                            <button type="button" onclick="testBrowserNotification()" id="btn-test-push" class="py-2 px-3 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-semibold rounded-xl text-xs transition-all flex items-center justify-center gap-1.5" title="Coba Bunyikan Notifikasi">
                                <i class="ph-bold ph-play text-sm text-blue-600"></i> Test
                            </button>
                        </div>
                    </div>

                    <!-- Direct WhatsApp Booking Action -->
                    @php
                        $urgentService = $services->filter(function($s) use ($vehicle) {
                            return ($s->target_km - $vehicle->current_km) <= 500;
                        })->first();

                        $waText = "Halo Bengkel, saya ingin booking jadwal servis untuk kendaraan " . $vehicle->motor_name . " (Plat: " . ($vehicle->plate_number ?? 'Kendaraan Saya') . "). Odometer saat ini: " . number_format($vehicle->current_km, 0, ',', '.') . " KM.";
                        if ($urgentService) {
                            $waText .= " Komponen yang butuh servis: " . $urgentService->category->name . ". Apakah ada slot servis dalam waktu dekat?";
                        } else {
                            $waText .= " Ingin melakukan pemeriksaan dan servis berkala. Apakah ada jadwal kosong?";
                        }
                        $waUrl = "https://wa.me/?text=" . urlencode($waText);
                    @endphp
                    <div class="border-t border-slate-200/80 pt-3">
                        <a href="{{ $waUrl }}" target="_blank" class="w-full py-2.5 px-3 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-whatsapp-logo text-base text-emerald-600"></i>
                            <span>Booking Servis ke WhatsApp Bengkel</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Grid (Services) -->
        <div class="stagger-4 flex flex-wrap justify-between items-center gap-3 mb-6">
            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                <i class="ph-bold ph-nut text-blue-600"></i>
                Pemantauan Komponen Servis
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('user.history') }}" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 hover:text-blue-600 text-xs font-bold rounded-xl transition-all shadow-soft-sm border border-slate-200/80 flex items-center gap-1.5" title="Buka Riwayat & Catat Servis">
                    <i class="ph-bold ph-wrench text-blue-600 text-sm"></i>
                    <span>Catat Servis Bengkel</span>
                </a>
                <button onclick="document.getElementById('modal-add-service').classList.remove('hidden')" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/20 flex items-center gap-1.5">
                    <i class="ph-bold ph-plus text-sm"></i>
                    <span>Tambah Komponen</span>
                </button>
            </div>
        </div>

        <div class="stagger-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-12" id="service-cards-container">
            @forelse($services as $service)
                @php
                    $remaining = $service->target_km - $vehicle->current_km;
                    $statusText = 'AMAN';
                    $borderColor = 'border-emerald-500';
                    $badgeStyle = 'bg-emerald-50 text-emerald-700 border border-emerald-200';

                    if ($remaining <= 0) {
                        $statusText = 'TERLAMBAT';
                        $borderColor = 'border-rose-500';
                        $badgeStyle = 'bg-rose-50 text-rose-700 border border-rose-200';
                    } elseif ($remaining < 200) {
                        $statusText = 'SEGERA SERVIS';
                        $borderColor = 'border-amber-500';
                        $badgeStyle = 'bg-amber-50 text-amber-700 border border-amber-200';
                    }
                @endphp
                <div class="card-interactive bg-white border-l-4 {{ $borderColor }} p-6 rounded-2xl shadow-soft-sm flex flex-col justify-between border-t border-r border-b border-slate-200/80" id="service-card-{{ $service->id }}">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-11 h-11 bg-slate-50 border border-slate-200/80 rounded-xl flex items-center justify-center text-slate-600">
                                @if(str_contains(strtolower($service->category->name), 'oli'))
                                    <i class="ph ph-drop-half text-2xl text-blue-600"></i>
                                @elseif(str_contains(strtolower($service->category->name), 'radiator'))
                                    <i class="ph ph-drop text-2xl text-cyan-600"></i>
                                @elseif(str_contains(strtolower($service->category->name), 'rem'))
                                    <i class="ph ph-circle-dashed text-2xl text-rose-500"></i>
                                @elseif(str_contains(strtolower($service->category->name), 'cvt') || str_contains(strtolower($service->category->name), 'v-belt'))
                                    <i class="ph ph-gear-six text-2xl text-amber-500"></i>
                                @else
                                    <i class="ph ph-nut text-2xl text-slate-600"></i>
                                @endif
                            </div>
                            <span class="{{ $badgeStyle }} text-[10px] px-2.5 py-1 rounded-full font-bold tracking-wider" id="service-tag-{{ $service->id }}">
                                {{ $statusText }}
                            </span>
                        </div>
                        
                        <h3 class="text-base font-bold text-slate-900 mb-1">{{ $service->category->name }}</h3>
                        <p class="text-slate-500 text-xs mb-4">Servis Terakhir: {{ number_format($service->last_service_km, 0, ',', '.') }} KM</p>
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight" id="service-rem-{{ $service->id }}">
                                    @if($remaining > 0)
                                        {{ number_format($remaining, 0, ',', '.') }} <span class="text-xs font-semibold text-slate-400">KM LAGI</span>
                                    @else
                                        {{ number_format(abs($remaining), 0, ',', '.') }} <span class="text-xs font-semibold text-rose-500">KM TERLEWAT</span>
                                    @endif
                                </span>
                                <p class="text-[10px] text-slate-400 uppercase mt-1 font-medium" id="service-target-{{ $service->id }}">Target: {{ number_format($service->target_km, 0, ',', '.') }} KM</p>
                                @if($service->target_date)
                                    <p class="text-[10px] text-slate-500">Estimasi: {{ $service->target_date->format('d M Y') }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @if($remaining <= 500)
                                    @php
                                        $waCardText = "Halo Bengkel, saya mau booking servis " . $service->category->name . " untuk kendaraan " . $vehicle->motor_name . " (KM: " . number_format($vehicle->current_km, 0, ',', '.') . "). " . ($remaining > 0 ? "Sisa " . number_format($remaining, 0, ',', '.') . " KM lagi." : "Sudah terlewat " . number_format(abs($remaining), 0, ',', '.') . " KM!") . " Apakah ada jadwal kosong?";
                                    @endphp
                                    <a href="https://wa.me/?text={{ urlencode($waCardText) }}" target="_blank" class="bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white px-2.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1 border border-emerald-200 shadow-soft-sm" title="Chat WA Bengkel">
                                        <i class="ph-bold ph-whatsapp-logo text-base"></i>
                                    </a>
                                @endif
                                <button onclick="openDoneModal('{{ $service->id }}', '{{ $service->category->name }}', '{{ $service->category->default_interval_km ?? 2500 }}')" class="bg-slate-50 hover:bg-blue-600 hover:text-white text-slate-700 px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 border border-slate-200 shadow-soft-sm">
                                    <i class="ph-bold ph-check-circle text-base"></i> Selesai
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 lg:col-span-3 bg-white p-10 rounded-3xl text-center border border-slate-200/80 shadow-soft-sm">
                    <i class="ph ph-calendar-plus text-5xl text-slate-400 mb-4 block"></i>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Semua servis selesai!</h3>
                    <p class="text-slate-500 mb-6 text-sm">Tambahkan komponen baru jika ingin mulai memantau kembali.</p>
                    <button onclick="document.getElementById('modal-add-service').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-md shadow-blue-500/20 text-sm">
                        Tambah Item Servis
                    </button>
                </div>
            @endforelse

            <button onclick="document.getElementById('modal-add-service').classList.remove('hidden')" class="bg-white border-2 border-dashed border-slate-200 p-6 rounded-2xl flex flex-col items-center justify-center gap-2 hover:border-blue-400 hover:bg-blue-50/30 transition-all min-h-[160px] shadow-soft-sm group">
                <i class="ph ph-plus-circle text-3xl text-slate-400 group-hover:text-blue-600 transition-colors"></i>
                <span class="text-slate-600 group-hover:text-blue-700 font-semibold text-sm transition-colors">Tambah Komponen Lain</span>
            </button>
        </div>
    </main>
</div>

<!-- Modal Odometer -->
<div id="modal-odo" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-white max-w-sm w-full p-7 rounded-3xl relative z-10 border border-slate-200 shadow-2xl">
        <h3 class="text-xl font-bold text-slate-900 mb-1.5">Update Odometer</h3>
        <p class="text-xs text-slate-500 mb-5">Masukkan angka kilometer terkini yang tertera di speedometer.</p>
        <form action="{{ route('odometer.update') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Kilometer Saat Ini</label>
                <div class="relative">
                    <input type="number" name="current_km" value="{{ $vehicle->current_km }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 font-mono text-base">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">KM</span>
                </div>
            </div>
            <div class="flex gap-2.5">
                <button type="button" onclick="document.getElementById('modal-odo').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition-all text-xs">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all shadow-md shadow-blue-500/20 text-xs">Simpan KM</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pajak STNK & 5 Tahunan -->
<div id="modal-tax-dates" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-white max-w-sm w-full p-7 rounded-3xl relative z-10 border border-slate-200 shadow-2xl">
        <h3 class="text-xl font-bold text-slate-900 mb-1.5">Jatuh Tempo Pajak</h3>
        <p class="text-xs text-slate-500 mb-5">Catat tanggal jatuh tempo STNK agar tidak lupa dan terhindar dari denda tilang/pajak.</p>
        <form action="{{ route('vehicle.tax.update') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Pajak Tahunan STNK</label>
                <input type="date" name="stnk_tax_due_date" value="{{ $vehicle->stnk_tax_due_date ? $vehicle->stnk_tax_due_date->format('Y-m-d') : '' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 text-xs">
            </div>
            <div class="mb-5">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Pajak 5 Tahunan (Ganti Plat Kaleng)</label>
                <input type="date" name="five_year_tax_due_date" value="{{ $vehicle->five_year_tax_due_date ? $vehicle->five_year_tax_due_date->format('Y-m-d') : '' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 text-xs">
            </div>
            <div class="flex gap-2.5">
                <button type="button" onclick="document.getElementById('modal-tax-dates').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition-all text-xs">Batal</button>
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition-all shadow-md shadow-purple-600/20 text-xs">Simpan Tanggal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Selesaikan Servis (Smart Rollover & Expense Tracking) -->
<div id="modal-service-done" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-white max-w-md w-full p-7 rounded-3xl relative z-10 border border-slate-200 shadow-2xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                <i class="ph-bold ph-check-circle text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-900">Selesaikan Servis</h3>
                <p class="text-xs text-blue-600 font-semibold" id="done-service-name">Komponen</p>
            </div>
        </div>

        <form id="form-service-done" method="POST" action="">
            @csrf
            <!-- Biaya Servis (Rp) -->
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Total Biaya Servis (Opsional)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
                    <input type="number" name="cost" placeholder="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 pl-12 pr-4 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 text-sm font-mono">
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Dicatat ke riwayat pengeluaran perawatan kendaraan.</p>
            </div>

            <!-- Nama Bengkel -->
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Bengkel / Tempat Servis (Opsional)</label>
                <input type="text" name="workshop_name" placeholder="Contoh: AHASS Surya Motor / Servis Mandiri" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 text-xs">
            </div>

            <!-- Catatan Tambahan -->
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Khusus / Merek Part (Opsional)</label>
                <input type="text" name="notes" placeholder="Contoh: Pakai oli Motul Scooter Expert 10W-40" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 text-xs">
            </div>

            <!-- Opsi Rollover Siklus Berikutnya -->
            <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl mb-5 space-y-2.5">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" id="check-rollover" name="action" value="rollover" checked onchange="toggleRolloverInputs(this)" class="w-4 h-4 rounded text-blue-600 accent-blue-600 cursor-pointer">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Jadwalkan Servis Berikutnya Otomatis</span>
                        <span class="text-[10px] text-slate-500">Komponen tetap aktif di dashboard untuk siklus berikutnya.</span>
                    </div>
                </label>
                <div id="rollover-inputs" class="pt-2 border-t border-slate-200 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-600 font-medium">Interval Kilometer:</span>
                        <div class="flex items-center gap-1">
                            <span class="text-slate-400">+</span>
                            <input type="number" id="input-interval-km" name="next_interval_km" value="2000" class="w-24 bg-white border border-slate-200 rounded-lg py-1 px-2 text-right text-slate-900 font-mono text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <span class="text-slate-500 font-bold">KM</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-2.5">
                <button type="button" onclick="document.getElementById('modal-service-done').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition-all text-xs">Batal</button>
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition-all shadow-md shadow-emerald-500/20 text-xs">Konfirmasi Selesai</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Service -->
<div id="modal-add-service" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-white max-w-sm w-full p-7 rounded-3xl relative z-10 border border-slate-200 shadow-2xl">
        <h3 class="text-xl font-bold text-slate-900 mb-1.5">Tambah Komponen</h3>
        <p class="text-xs text-slate-500 mb-5">Pilih komponen kendaraan yang ingin dipantau jadwal servisnya.</p>
        <form action="{{ route('service.add') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Komponen</label>
                <select name="category_id" id="select-category" onchange="autoSuggestTargetKm(this)" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 text-xs">
                    @foreach(\App\Models\ServiceCategory::all() as $cat)
                        <option value="{{ $cat->id }}" data-interval="{{ $cat->default_interval_km ?? 2500 }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Target Kilometer Berikutnya</label>
                <input type="number" name="target_km" id="input-add-target-km" value="{{ $vehicle->current_km + 2000 }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 text-xs font-mono" placeholder="Contoh: 45000">
            </div>
            <div class="mb-5">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Target Tanggal Servis (Opsional)</label>
                <input type="date" name="target_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 text-xs">
            </div>
            <div class="flex gap-2.5">
                <button type="button" onclick="document.getElementById('modal-add-service').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition-all text-xs">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all shadow-md shadow-blue-500/20 text-xs">Tambah Item</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Notification Time -->
<div id="modal-notif-time" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-white max-w-sm w-full p-7 rounded-3xl relative z-10 border border-slate-200 shadow-2xl">
        <h3 class="text-xl font-bold text-slate-900 mb-1.5">Atur Jadwal Notifikasi</h3>
        <p class="text-xs text-slate-500 mb-5">Pilih jam pengingat push notification harian di perangkat Anda.</p>
        <form action="{{ route('notification.update') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Pilih Jam Pengingat</label>
                <input type="time" name="notification_time" value="{{ auth()->user()->notification_time }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 text-base">
                <p class="mt-2 text-[10px] text-slate-400 italic">*Notifikasi otomatis akan dikirim setiap hari pada jam ini jika servis mendekati target.</p>
            </div>
            <div class="flex gap-2.5">
                <button type="button" onclick="document.getElementById('modal-notif-time').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition-all text-xs">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all shadow-md shadow-blue-500/20 text-xs">Simpan Jam</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Live Camera Viewfinder (In-App WebRTC Camera - Centered & Non-scrolling) -->
<div id="modal-live-camera" class="fixed inset-0 z-[120] hidden flex items-center justify-center p-4 overflow-hidden select-none">
    <div class="fixed inset-0 bg-slate-950/85 backdrop-blur-md transition-opacity" onclick="closeLiveCameraModal()"></div>
    <div class="bg-slate-950 text-white max-w-sm sm:max-w-md w-full p-4 sm:p-5 rounded-3xl relative z-10 border border-white/10 shadow-2xl flex flex-col my-auto max-h-[92vh] overflow-hidden">
        <!-- Header Viewfinder -->
        <div class="flex justify-between items-center mb-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                <h3 class="text-xs font-bold text-white uppercase tracking-wider">Kamera Speedometer Live</h3>
            </div>
            <button type="button" onclick="closeLiveCameraModal()" class="p-1.5 text-slate-400 hover:text-white rounded-xl bg-white/5 hover:bg-white/10 transition-colors">
                <i class="ph ph-x text-base"></i>
            </button>
        </div>

        <!-- Live Stream Frame with Target Brackets Overlay -->
        <div class="relative w-full h-44 sm:h-48 bg-black rounded-2xl overflow-hidden border border-emerald-500/30 shadow-inner flex items-center justify-center">
            <video id="live-camera-feed" autoplay playsinline muted class="w-full h-full object-cover"></video>

            <!-- Visual Target HUD / Viewfinder Box -->
            <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center p-3">
                <div class="w-4/5 h-2/5 border-2 border-emerald-400/80 rounded-2xl relative shadow-[0_0_20px_rgba(16,185,129,0.3)] bg-emerald-500/5">
                    <!-- Corner Marks -->
                    <span class="absolute -top-1 -left-1 w-3.5 h-3.5 border-t-4 border-l-4 border-emerald-300 rounded-tl"></span>
                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 border-t-4 border-r-4 border-emerald-300 rounded-tr"></span>
                    <span class="absolute -bottom-1 -left-1 w-3.5 h-3.5 border-b-4 border-l-4 border-emerald-300 rounded-bl"></span>
                    <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 border-b-4 border-r-4 border-emerald-300 rounded-br"></span>
                    
                    <!-- Center Aim -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-[10px] font-mono font-bold text-emerald-300 bg-slate-950/85 px-2 py-0.5 rounded-full border border-emerald-400/30">
                            Posisikan Angka ODO Disini
                        </span>
                    </div>
                </div>
                <p class="text-[10px] text-white/80 mt-2 drop-shadow bg-black/60 px-3 py-0.5 rounded-full font-medium">
                    Arahkan kamera hingga angka ODO terlihat fokus & jelas
                </p>
            </div>
        </div>

        <!-- Shutter Controls & Options -->
        <div class="mt-3 flex items-center justify-between px-2">
            <!-- Tombol Switch Camera (Depan / Belakang) -->
            <button type="button" onclick="flipLiveCamera()" class="p-2.5 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-300 hover:text-white transition-all flex items-center gap-1.5 text-xs" title="Ganti Kamera">
                <i class="ph-bold ph-camera-rotate text-lg text-cyan-400"></i>
                <span class="hidden sm:inline">Putar</span>
            </button>

            <!-- Tombol Shutter Utama -->
            <button type="button" onclick="captureLiveSpeedometerPhoto()" class="w-13 h-13 sm:w-14 sm:h-14 rounded-full border-4 border-white/40 p-1 flex items-center justify-center hover:scale-105 active:scale-95 transition-transform shadow-lg shadow-emerald-500/30 group" title="Jepret Foto Odometer">
                <div class="w-full h-full rounded-full bg-emerald-500 group-hover:bg-emerald-400 flex items-center justify-center text-white transition-colors">
                    <i class="ph-bold ph-camera text-xl"></i>
                </div>
            </button>

            <!-- Tombol Fallback Galeri -->
            <button type="button" onclick="closeLiveCameraModal(); document.getElementById('gallery-scan-input').click();" class="p-2.5 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-300 hover:text-white transition-all flex items-center gap-1.5 text-xs" title="Pilih File dari Galeri">
                <i class="ph-bold ph-image text-lg text-emerald-400"></i>
                <span class="hidden sm:inline">Galeri</span>
            </button>
        </div>
    </div>
</div>

<!-- Hidden Canvas for Video Frame Capture -->
<canvas id="live-camera-canvas" class="hidden"></canvas>

<!-- Modal AI Speedometer Scanner (Centered & Strictly Non-Scrolling) -->
<div id="modal-ai-scanner" class="fixed inset-0 z-[120] hidden flex items-center justify-center p-4 overflow-hidden select-none">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeAiScannerModal()"></div>
    <div class="bg-white max-w-sm sm:max-w-md w-full p-4 sm:p-5 rounded-3xl relative z-10 border border-slate-200 shadow-2xl flex flex-col justify-between my-auto max-h-[92vh] overflow-hidden">
        
        <!-- Header Modal -->
        <div class="flex justify-between items-center mb-2.5">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-scan text-base sm:text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">Pemindai Odometer AI</h3>
                    <p class="text-[10px] sm:text-[11px] text-slate-500" id="ai-scanner-subtitle">Memproses pembacaan angka speedometer...</p>
                </div>
            </div>
            <button type="button" onclick="closeAiScannerModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-xl bg-slate-100 transition-colors">
                <i class="ph ph-x text-base"></i>
            </button>
        </div>

        <!-- Preview Image & Laser Scanning Animation Frame (Constrained Height & Centered) -->
        <div class="relative w-full h-32 sm:h-36 bg-slate-950 rounded-2xl overflow-hidden border border-slate-200 mb-3 flex items-center justify-center group shrink-0">
            <img id="scanner-preview-img" src="" alt="Speedometer Preview" class="w-full h-full object-contain">
            
            <!-- Laser Scanning Beam Overlay (Aktif saat loading) -->
            <div id="scanner-laser-beam" class="absolute inset-0 pointer-events-none flex flex-col justify-between">
                <div class="w-full h-1 bg-gradient-to-r from-transparent via-cyan-400 to-transparent shadow-[0_0_15px_#22d3ee] animate-laser"></div>
                <div class="absolute inset-0 bg-cyan-500/10 backdrop-brightness-110 flex items-center justify-center">
                    <div class="bg-slate-900/90 border border-cyan-500/30 px-3 py-1.5 rounded-xl text-center shadow-lg">
                        <i class="ph-bold ph-spinner animate-spin text-lg text-cyan-400 block mx-auto mb-0.5"></i>
                        <span class="text-xs font-semibold text-white">AI Sedang Membaca Odometer...</span>
                        <span class="text-[10px] text-slate-300 block">Deteksi angka digital & analog</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hasil Deteksi AI -->
        <div id="scanner-result-container" class="hidden space-y-2.5">
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-3">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-semibold text-emerald-800 flex items-center gap-1.5">
                        <i class="ph-bold ph-check-circle text-sm text-emerald-600"></i> Odometer Terdeteksi:
                    </span>
                    <span id="ai-confidence-badge" class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                        Akurasi Tinggi
                    </span>
                </div>
                
                <div class="flex items-center gap-2">
                    <input type="number" id="ai-detected-km-input" class="w-full bg-white border border-emerald-300 rounded-xl px-3 py-1.5 text-lg font-black text-slate-900 font-mono focus:outline-none focus:ring-2 focus:ring-emerald-400" min="0">
                    <span class="text-xs font-bold text-slate-500 font-mono shrink-0">KM</span>
                </div>
                <p class="text-[10px] text-slate-500 mt-1 italic" id="ai-scan-notes">
                    Koreksi angka di atas secara manual jika terdapat angka yang kurang tepat.
                </p>
            </div>

            <!-- Tombol Konfirmasi Simpan -->
            <div class="flex gap-2">
                <button type="button" onclick="closeAiScannerModal()" class="flex-1 py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all text-xs">
                    Batal
                </button>
                <button type="button" onclick="submitDetectedOdometer()" id="btn-submit-detected-km" class="flex-1 py-2.5 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-md shadow-emerald-500/20 flex items-center justify-center gap-1.5 text-xs">
                    <i class="ph-bold ph-check text-sm"></i>
                    <span>Simpan Odometer</span>
                </button>
            </div>
        </div>

        <!-- State Gagal / Error / Konfirmasi Cepat -->
        <div id="scanner-error-container" class="hidden space-y-2.5">
            <div class="bg-amber-50/90 border border-amber-200 rounded-2xl p-3">
                <div class="flex items-start gap-2 mb-2">
                    <i class="ph-bold ph-warning-circle text-amber-500 text-lg shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 leading-tight" id="scanner-error-title">Verifikasi Kilometer</h4>
                        <p class="text-[11px] text-slate-600 leading-snug mt-0.5" id="scanner-error-desc">Ketikkan angka ODO yang tertera untuk konfirmasi:</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="number" id="ai-fallback-km-input" class="w-full bg-white border border-amber-300 rounded-xl px-3 py-1.5 text-lg font-black text-slate-900 font-mono focus:outline-none focus:ring-2 focus:ring-amber-400" placeholder="Contoh: 14800" min="0">
                    <span class="text-xs font-bold text-slate-500 font-mono shrink-0">KM</span>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="closeAiScannerModal()" class="flex-1 py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all text-xs">
                    Tutup
                </button>
                <button type="button" onclick="submitFallbackOdometer()" id="btn-submit-fallback-km" class="flex-1 py-2.5 px-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md shadow-blue-500/20 flex items-center justify-center gap-1.5 text-xs">
                    <i class="ph-bold ph-check text-sm"></i> Simpan KM
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes laserSweep {
    0% { transform: translateY(0); opacity: 0.8; }
    50% { transform: translateY(110px); opacity: 1; }
    100% { transform: translateY(0); opacity: 0.8; }
}
.animate-laser {
    animation: laserSweep 1.8s ease-in-out infinite;
}
</style>

<!-- Script Copy, Modal Done, AI Scanner, & Web Push Notifikasi -->
<script>
    const currentVehicleKm = {{ $vehicle->current_km }};

    function openDoneModal(serviceId, serviceName, defaultInterval) {
        const modal = document.getElementById('modal-service-done');
        const form = document.getElementById('form-service-done');
        const nameEl = document.getElementById('done-service-name');
        const intervalInput = document.getElementById('input-interval-km');

        form.action = "{{ url('/service/done') }}/" + serviceId;
        nameEl.innerText = serviceName;
        intervalInput.value = defaultInterval || 2000;

        modal.classList.remove('hidden');
    }

    function toggleRolloverInputs(checkbox) {
        const container = document.getElementById('rollover-inputs');
        if (checkbox.checked) {
            checkbox.value = 'rollover';
            container.classList.remove('hidden');
        } else {
            checkbox.value = 'remove';
            container.classList.add('hidden');
        }
    }

    function autoSuggestTargetKm(selectEl) {
        const option = selectEl.options[selectEl.selectedIndex];
        const interval = parseInt(option.getAttribute('data-interval')) || 2500;
        document.getElementById('input-add-target-km').value = currentVehicleKm + interval;
    }

    function copyToClipboard(text, successMsg) {
        navigator.clipboard.writeText(text).then(() => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                background: '#1E293B',
                color: '#fff',
            });
            Toast.fire({
                icon: 'success',
                title: successMsg
            });
        });
    }

    // --- LIVE CAMERA & AI SPEEDOMETER SCANNER LOGIC ---
    let liveCameraStream = null;
    let currentFacingMode = 'environment'; // Default: Kamera belakang
    let selectedScanFile = null;

    function lockModalScroll() {
        document.body.classList.add('overflow-hidden');
    }

    function unlockModalScroll() {
        const liveCam = document.getElementById('modal-live-camera');
        const aiScan = document.getElementById('modal-ai-scanner');
        const isLiveCamOpen = liveCam && !liveCam.classList.contains('hidden');
        const isAiScanOpen = aiScan && !aiScan.classList.contains('hidden');
        if (!isLiveCamOpen && !isAiScanOpen) {
            document.body.classList.remove('overflow-hidden');
        }
    }

    async function openLiveCameraModal() {
        const modal = document.getElementById('modal-live-camera');
        const video = document.getElementById('live-camera-feed');

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            // Jika browser tidak mendukung getUserMedia (misal insecure context HTTP non-localhost), fallback ke input file
            document.getElementById('camera-scan-input').click();
            return;
        }

        try {
            stopCameraStream();
            lockModalScroll();
            modal.classList.remove('hidden');

            const constraints = {
                video: {
                    facingMode: { ideal: currentFacingMode },
                    width: { ideal: 1920 },
                    height: { ideal: 1080 }
                },
                audio: false
            };

            liveCameraStream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = liveCameraStream;
            await video.play();
        } catch (err) {
            console.warn('Live camera error, fallback to file input:', err);
            closeLiveCameraModal();
            // Fallback otomatis ke file picker kamera
            document.getElementById('camera-scan-input').click();
        }
    }

    function stopCameraStream() {
        if (liveCameraStream) {
            liveCameraStream.getTracks().forEach(track => track.stop());
            liveCameraStream = null;
        }
        const video = document.getElementById('live-camera-feed');
        if (video) {
            video.srcObject = null;
        }
    }

    function closeLiveCameraModal() {
        stopCameraStream();
        document.getElementById('modal-live-camera').classList.add('hidden');
        unlockModalScroll();
    }

    async function flipLiveCamera() {
        currentFacingMode = (currentFacingMode === 'environment') ? 'user' : 'environment';
        await openLiveCameraModal();
    }

    function captureLiveSpeedometerPhoto() {
        const video = document.getElementById('live-camera-feed');
        const canvas = document.getElementById('live-camera-canvas');
        if (!video || !video.videoWidth) return;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        canvas.toBlob(blob => {
            closeLiveCameraModal();
            if (blob) {
                processSpeedometerFileOrBlob(blob, 'speedometer_live_capture.jpg');
            }
        }, 'image/jpeg', 0.92);
    }

    function handleSpeedometerImageSelected(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        processSpeedometerFileOrBlob(file, file.name);
    }

    async function compressSpeedometerImage(fileOrBlob, maxDim = 1280, quality = 0.85) {
        return new Promise((resolve) => {
            if (!fileOrBlob || (fileOrBlob.size && fileOrBlob.size < 350 * 1024)) {
                return resolve(fileOrBlob);
            }
            const img = new Image();
            const reader = new FileReader();
            reader.onload = (e) => {
                img.onload = () => {
                    let w = img.width;
                    let h = img.height;
                    if (w > maxDim || h > maxDim) {
                        if (w > h) {
                            h = Math.round((h * maxDim) / w);
                            w = maxDim;
                        } else {
                            w = Math.round((w * maxDim) / h);
                            h = maxDim;
                        }
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = w;
                    canvas.height = h;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, w, h);
                    canvas.toBlob((blob) => {
                        resolve(blob || fileOrBlob);
                    }, 'image/jpeg', quality);
                };
                img.onerror = () => resolve(fileOrBlob);
                img.src = e.target.result;
            };
            reader.onerror = () => resolve(fileOrBlob);
            reader.readAsDataURL(fileOrBlob);
        });
    }

    async function processSpeedometerFileOrBlob(fileOrBlob, filename = 'speedometer.jpg') {
        selectedScanFile = fileOrBlob;

        // Reset UI State Modal AI Scanner
        const modal = document.getElementById('modal-ai-scanner');
        const previewImg = document.getElementById('scanner-preview-img');
        const laserBeam = document.getElementById('scanner-laser-beam');
        const resultBox = document.getElementById('scanner-result-container');
        const errorBox = document.getElementById('scanner-error-container');
        const subtitle = document.getElementById('ai-scanner-subtitle');

        previewImg.src = URL.createObjectURL(fileOrBlob);
        laserBeam.classList.remove('hidden');
        resultBox.classList.add('hidden');
        errorBox.classList.add('hidden');
        subtitle.innerText = 'AI Vision Gemini sedang membaca panel speedometer...';

        lockModalScroll();
        modal.classList.remove('hidden');

        // Optimasi: Kompresi cepat di browser sebelum dikirim ke endpoint AI
        const compressedBlob = await compressSpeedometerImage(fileOrBlob);

        // Kirim foto ke endpoint scan OCR AI
        const formData = new FormData();
        formData.append('speedometer_image', compressedBlob, filename);
        formData.append('_token', '{{ csrf_token() }}');

        fetch("{{ route('odometer.scan') }}", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async res => {
            const data = await res.json();
            laserBeam.classList.add('hidden');
            if (res.ok && data.status === 'success') {
                subtitle.innerText = 'Odometer berhasil dianalisis!';
                resultBox.classList.remove('hidden');
                document.getElementById('ai-detected-km-input').value = data.detected_km;
                document.getElementById('ai-confidence-badge').innerText = (data.confidence || 'Tinggi') + ' Confidence';
                if (data.notes) {
                    document.getElementById('ai-scan-notes').innerText = data.notes;
                }
            } else {
                subtitle.innerText = 'Verifikasi Angka';
                errorBox.classList.remove('hidden');
                document.getElementById('scanner-error-title').innerText = data.is_key_invalid ? 'Kunci Gemini API Perlu Diperbarui' : (data.status === 'warning' ? 'Odometer Kurang Jelas' : 'Gagal Memproses Otomatis');
                document.getElementById('scanner-error-desc').innerText = data.message || 'Layanan AI mengalami kendala. Anda dapat langsung mengonfirmasi kilometer di bawah ini.';
                setTimeout(() => {
                    const fallbackInput = document.getElementById('ai-fallback-km-input');
                    if (fallbackInput) fallbackInput.focus();
                }, 300);
            }
        })
        .catch(err => {
            laserBeam.classList.add('hidden');
            errorBox.classList.remove('hidden');
            document.getElementById('scanner-error-title').innerText = 'Koneksi Terputus';
            document.getElementById('scanner-error-desc').innerText = 'Terjadi kendala jaringan. Anda tetap dapat menginput kilometer dari foto di atas.';
            setTimeout(() => {
                const fallbackInput = document.getElementById('ai-fallback-km-input');
                if (fallbackInput) fallbackInput.focus();
            }, 300);
        });
    }

    function closeAiScannerModal() {
        document.getElementById('modal-ai-scanner').classList.add('hidden');
        document.getElementById('camera-scan-input').value = '';
        document.getElementById('gallery-scan-input').value = '';
        unlockModalScroll();
    }

    function submitFallbackOdometer() {
        const kmVal = parseInt(document.getElementById('ai-fallback-km-input').value);
        if (isNaN(kmVal) || kmVal < 0) {
            Swal.fire({
                icon: 'error',
                title: 'Angka Tidak Valid',
                text: 'Silakan masukkan angka kilometer yang terlihat pada foto speedometer Anda.',
                background: '#1E293B',
                color: '#fff'
            });
            return;
        }

        const btn = document.getElementById('btn-submit-fallback-km');
        btn.disabled = true;
        btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';

        const formData = new FormData();
        formData.append('current_km', kmVal);
        formData.append('_token', '{{ csrf_token() }}');

        fetch("{{ route('odometer.update') }}", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async res => {
            const data = await res.json();
            if (res.ok && data.status === 'success') {
                closeAiScannerModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Odometer Berhasil Diperbarui! 🎉',
                    text: `Kilometer tercatat di ${new Intl.NumberFormat('id-ID').format(kmVal)} KM. Jadwal servis telah dikalkulasi ulang.`,
                    background: '#1E293B',
                    color: '#fff'
                }).then(() => {
                    window.location.reload();
                });
            } else if (data.status === 'warning_decrease') {
                closeAiScannerModal();
                Swal.fire({
                    title: 'Konfirmasi Penurunan KM',
                    html: data.message + '<br><br>Apakah Anda sengaja menurunkan kilometer?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#64748B',
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal',
                    background: '#1E293B',
                    color: '#fff'
                }).then(result => {
                    if (result.isConfirmed) {
                        const confirmForm = new FormData();
                        confirmForm.append('current_km', kmVal);
                        confirmForm.append('confirm_lower_km', '1');
                        confirmForm.append('_token', '{{ csrf_token() }}');

                        fetch("{{ route('odometer.update') }}", {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: confirmForm
                        }).then(() => window.location.reload());
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: data.message || 'Terjadi kendala saat menyimpan odometer.',
                    background: '#1E293B',
                    color: '#fff'
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="ph-bold ph-check text-base"></i> Simpan Odometer';
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Koneksi Gagal',
                text: 'Gagal menghubungi server.',
                background: '#1E293B',
                color: '#fff'
            });
            btn.disabled = false;
            btn.innerHTML = '<i class="ph-bold ph-check text-base"></i> Simpan Odometer';
        });
    }

    function submitDetectedOdometer() {
        const kmVal = parseInt(document.getElementById('ai-detected-km-input').value);
        if (isNaN(kmVal) || kmVal < 0) {
            Swal.fire({
                icon: 'error',
                title: 'Angka Tidak Valid',
                text: 'Masukkan angka kilometer yang valid.',
                background: '#1E293B',
                color: '#fff'
            });
            return;
        }

        const btn = document.getElementById('btn-submit-detected-km');
        btn.disabled = true;
        btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';

        const formData = new FormData();
        formData.append('current_km', kmVal);
        formData.append('_token', '{{ csrf_token() }}');

        fetch("{{ route('odometer.update') }}", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async res => {
            const data = await res.json();
            if (res.ok && data.status === 'success') {
                closeAiScannerModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Odometer Berhasil Diperbarui! 🎉',
                    text: `Kilometer tercatat di ${new Intl.NumberFormat('id-ID').format(kmVal)} KM. Jadwal servis telah dikalkulasi ulang.`,
                    background: '#1E293B',
                    color: '#fff'
                }).then(() => {
                    window.location.reload();
                });
            } else if (data.status === 'warning_decrease') {
                closeAiScannerModal();
                Swal.fire({
                    title: 'Konfirmasi Penurunan KM',
                    html: data.message + '<br><br>Apakah Anda sengaja menurunkan kilometer (misal karena reset atau ganti speedometer)?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#64748B',
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal',
                    background: '#1E293B',
                    color: '#fff'
                }).then(result => {
                    if (result.isConfirmed) {
                        const confirmForm = new FormData();
                        confirmForm.append('current_km', kmVal);
                        confirmForm.append('confirm_lower_km', '1');
                        confirmForm.append('_token', '{{ csrf_token() }}');

                        fetch("{{ route('odometer.update') }}", {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: confirmForm
                        }).then(() => window.location.reload());
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: data.message || 'Terjadi kendala saat menyimpan odometer.',
                    background: '#1E293B',
                    color: '#fff'
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="ph-bold ph-check text-base"></i> Simpan Odometer';
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Koneksi Gagal',
                text: 'Gagal menghubungi server.',
                background: '#1E293B',
                color: '#fff'
            });
            btn.disabled = false;
            btn.innerHTML = '<i class="ph-bold ph-check text-base"></i> Simpan Odometer';
        });
    }

    // --- WEB PUSH & BROWSER NOTIFICATION ---
    function updatePushStatusUI() {
        const badge = document.getElementById('push-status-badge');
        const reqBtn = document.getElementById('btn-request-push');
        if (!('Notification' in window)) {
            if (badge) {
                badge.innerText = 'Tidak Didukung';
                badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-500/10 text-slate-400 border border-slate-500/20';
            }
            if (reqBtn) reqBtn.style.display = 'none';
            return;
        }

        if (Notification.permission === 'granted') {
            if (badge) {
                badge.innerText = 'Aktif (Diizinkan)';
                badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
            }
            if (reqBtn) {
                reqBtn.innerHTML = '<i class="ph-bold ph-check text-sm"></i> Notifikasi Aktif';
                reqBtn.classList.replace('text-cyan-300', 'text-emerald-400');
                reqBtn.classList.replace('border-cyan-500/30', 'border-emerald-500/30');
            }
        } else if (Notification.permission === 'denied') {
            if (badge) {
                badge.innerText = 'Diblokir Browser';
                badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20';
            }
        } else {
            if (badge) {
                badge.innerText = 'Belum Diaktifkan';
                badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20';
            }
        }
    }

    function requestBrowserNotificationPermission() {
        if (!('Notification' in window)) {
            Swal.fire({
                icon: 'info',
                title: 'Browser Tidak Mendukung',
                text: 'Browser Anda belum mendukung Web Notification API. Silakan gunakan Google Chrome atau Safari.',
                background: '#1E293B',
                color: '#fff'
            });
            return;
        }

        Notification.requestPermission().then(permission => {
            updatePushStatusUI();
            if (permission === 'granted') {
                new Notification('Otokeep - Pengingat Aktif! 🔔', {
                    body: 'Sistem pengingat servis otomatis sudah aktif di perangkat ini. Kami akan memberi tahu Anda saat servis tiba.',
                    icon: '/favicon.png'
                });
                Swal.fire({
                    icon: 'success',
                    title: 'Pengingat Berhasil Diaktifkan!',
                    text: 'Notifikasi otomatis kini akan muncul langsung di layar HP/Desktop Anda saat kendaraan butuh servis.',
                    background: '#1E293B',
                    color: '#fff'
                });
            }
        });
    }

    function testBrowserNotification() {
        if (!('Notification' in window) || Notification.permission !== 'granted') {
            requestBrowserNotificationPermission();
            return;
        }
        checkAndSyncRealtime(true);
    }

    // --- REALTIME BACKGROUND AUTO-REFRESH & SILENT NOTIFICATION SYNC ---
    let lastNotifiedMinute = sessionStorage.getItem('otokeep_last_notified_minute') || '';

    function playNotificationChime() {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
            osc.frequency.setValueAtTime(880, audioCtx.currentTime + 0.15); // A5
            gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.6);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.6);
        } catch (e) {}
    }

    function checkAndSyncRealtime(forceCheck = false) {
        const now = new Date();
        const currentHours = String(now.getHours()).padStart(2, '0');
        const currentMinutes = String(now.getMinutes()).padStart(2, '0');
        const currentMinuteStr = `${currentHours}:${currentMinutes}`;

        // Cek silent sync ke backend tanpa reload browser
        fetch(`{{ route('dashboard.sync') }}?client_time=${currentMinuteStr}&force_check=${forceCheck ? '1' : '0'}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // 1. Silent Data Sync (Perbarui angka Odometer secara halus di background jika ada perubahan)
                if (data.current_km_formatted) {
                    const odoMain = document.getElementById('odometer-display-main');
                    if (odoMain && odoMain.innerText.trim() !== data.current_km_formatted) {
                        odoMain.innerText = data.current_km_formatted;
                    }
                    const odoBadge = document.getElementById('scanner-current-km-badge');
                    if (odoBadge && odoBadge.innerText.trim() !== data.current_km_formatted) {
                        odoBadge.innerText = data.current_km_formatted;
                    }
                }

                // 2. Evaluasi Notifikasi Pengingat
                if (data.should_notify && data.notification && (forceCheck || lastNotifiedMinute !== currentMinuteStr)) {
                    lastNotifiedMinute = currentMinuteStr;
                    sessionStorage.setItem('otokeep_last_notified_minute', currentMinuteStr);

                    // Bunyikan Audio Chime
                    playNotificationChime();

                    // Tampilkan Web Push Notification di Layar HP / Desktop
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification(data.notification.title, {
                            body: data.notification.body,
                            icon: data.notification.icon || '/favicon.png',
                            badge: '/favicon.png',
                            vibrate: [200, 100, 200]
                        });
                    }

                    // Tampilkan juga Toast In-App Elegan
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 9000,
                        timerProgressBar: true,
                        background: '#0F172A',
                        color: '#fff',
                    });

                    Toast.fire({
                        icon: 'info',
                        title: data.notification.title,
                        text: data.notification.body
                    });
                }
            }
        })
        .catch(err => {
            // Silent error failover: jangan ganggu tampilan user
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        updatePushStatusUI();
        // Cek langsung saat buka halaman
        checkAndSyncRealtime(false);
        // Interval auto-refresh diam-diam di background setiap 15 detik (tanpa refresh halaman yang terlihat)
        setInterval(() => {
            checkAndSyncRealtime(false);
        }, 15000);
    });
</script>

<!-- SweetAlert2 Typo Decrease Warning -->
@if(session('warning_decrease'))
@php $dec = session('warning_decrease'); @endphp
<script>
    Swal.fire({
        title: 'Konfirmasi Penurunan KM',
        html: `Kilometer yang Anda masukkan (<b>${new Intl.NumberFormat('id-ID').format({{ $dec['new_km'] }})} KM</b>) lebih kecil dari odometer sebelumnya (<b>${new Intl.NumberFormat('id-ID').format({{ $dec['old_km'] }})} KM</b>).<br><br>Apakah Anda sengaja menurunkan kilometer (misal karena reset atau ganti speedometer)?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#F97316',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Ya, Simpan Nilai Ini',
        cancelButtonText: 'Batal / Salah Ketik',
        background: '#1E293B',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form with confirmation flag
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("odometer.update") }}';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const kmInput = document.createElement('input');
            kmInput.type = 'hidden';
            kmInput.name = 'current_km';
            kmInput.value = '{{ $dec["new_km"] }}';
            form.appendChild(kmInput);

            const confirmInput = document.createElement('input');
            confirmInput.type = 'hidden';
            confirmInput.name = 'confirm_lower_km';
            confirmInput.value = '1';
            form.appendChild(confirmInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
</script>
@endif
@endsection
