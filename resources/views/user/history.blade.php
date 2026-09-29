@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-transparent flex flex-col lg:flex-row print:bg-white print:text-black antialiased text-slate-800">
    <div class="print:hidden">
        @include('layouts.sidebar')
    </div>

    <!-- Main Content -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto print:p-0 print:m-0">
        <!-- Multi-Vehicle Switcher Bar -->
        @if(isset($vehicles) && $vehicles->count() > 1)
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4 p-4 bg-gradient-to-r from-white via-white to-slate-50/80 rounded-2xl border border-slate-200/80 shadow-soft-sm print:hidden">
            <div class="flex items-center gap-3">
                <span class="text-xs uppercase font-bold text-slate-500 tracking-wider flex items-center gap-1.5">
                    <i class="ph-bold ph-garage text-blue-600"></i> Riwayat Kendaraan:
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
        </div>
        @endif

        <!-- Session Alert Notification -->
        @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50/60 border border-emerald-200/80 text-emerald-800 text-sm flex items-center justify-between shadow-soft-sm print:hidden animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-500/20">
                    <i class="ph-bold ph-check text-lg"></i>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 p-1">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-rose-50 to-amber-50/40 border border-rose-200/80 text-rose-800 text-sm shadow-soft-sm print:hidden animate-fade-in">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-rose-500 to-red-600 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm shadow-rose-500/20">
                    <i class="ph-bold ph-warning-circle text-lg"></i>
                </div>
                <div>
                    <span class="font-bold block mb-1">Terdapat data yang belum sesuai:</span>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <header class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1.5 print:text-black print:text-2xl tracking-tight">Buku Riwayat Servis Digital</h1>
                <p class="text-slate-500 text-sm print:text-gray-600">
                    Jejak pemeliharaan berkala untuk <span class="text-slate-900 font-bold print:text-black">{{ $vehicle->motor_name }}</span> (Odometer: {{ number_format($vehicle->current_km, 0, ',', '.') }} KM).
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 print:hidden">
                <!-- Tombol Tambah Catatan Servis Bengkel -->
                <button onclick="openAddServiceModal()" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-[0.98] text-white rounded-xl font-bold text-xs transition-all shadow-md shadow-blue-500/20 flex items-center gap-2">
                    <i class="ph-bold ph-plus-circle text-base"></i>
                    <span>Catat Servis Bengkel</span>
                </button>

                <!-- Tombol Print / Buku Servis PDF -->
                <a href="{{ route('history.service-book') }}" target="_blank" class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 rounded-xl font-bold text-xs transition-all border border-slate-200 shadow-soft-sm flex items-center gap-2 hover:border-blue-300">
                    <i class="ph-bold ph-file-pdf text-base text-rose-500"></i>
                    <span>Buku Servis (PDF)</span>
                </a>
            </div>
        </header>

        <!-- KARTU STATISTIK FINANSIAL / BIAYA PERAWATAN (EXPENSE TRACKER METRICS) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 print:hidden">
            <!-- Total Pengeluaran -->
            <div class="card-interactive bg-white p-5 rounded-3xl border border-slate-200/80 shadow-soft-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Total Biaya Perawatan</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="ph-bold ph-wallet text-base"></i>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight">
                    Rp {{ number_format($totalExpense, 0, ',', '.') }}
                </div>
                <p class="text-[10px] text-slate-500 mt-1">Akumulasi seluruh biaya bengkel</p>
            </div>

            <!-- Pengeluaran Tahun Ini -->
            <div class="card-interactive bg-white p-5 rounded-3xl border border-slate-200/80 shadow-soft-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Tahun {{ $analytics['currentYear'] }}</span>
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="ph-bold ph-calendar text-base"></i>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight">
                    Rp {{ number_format($analytics['yearlyExpense'], 0, ',', '.') }}
                </div>
                <p class="text-[10px] text-slate-500 mt-1">Total pengeluaran tahun ini</p>
            </div>

            <!-- Total Servis Selesai -->
            <div class="card-interactive bg-white p-5 rounded-3xl border border-slate-200/80 shadow-soft-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Kunjungan / Servis</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-base"></i>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight">
                    {{ $totalServices }} <span class="text-xs font-semibold text-slate-400">Pekerjaan</span>
                </div>
                <p class="text-[10px] text-slate-500 mt-1">Perawatan rutin & khusus</p>
            </div>

            <!-- Rata-rata Biaya Servis -->
            <div class="card-interactive bg-white p-5 rounded-3xl border border-slate-200/80 shadow-soft-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Rata-rata Biaya</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="ph-bold ph-chart-bar text-base"></i>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight">
                    Rp {{ number_format($avgExpense, 0, ',', '.') }}
                </div>
                <p class="text-[10px] text-slate-500 mt-1">Estimasi per satu kali perawatan</p>
            </div>
        </div>

        <!-- GRAFIK & ANALITIK PENGELUARAN (PERSONAL EXPENSE TRACKER) -->
        @if($totalServices > 0)
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-soft-sm mb-8 print:hidden">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="ph-bold ph-chart-pie-slice text-blue-600"></i>
                        Analitik Pengeluaran & Biaya Perawatan
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pemantauan anggaran servis kendaraan {{ $vehicle->motor_name }}.</p>
                </div>

                <!-- Highlight badges -->
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    @if($analytics['highestExpense'])
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-rose-50 text-rose-700 font-semibold border border-rose-100 text-[11px]">
                        <i class="ph-bold ph-trend-up"></i>
                        Servis Terbesar: <strong>Rp {{ number_format($analytics['highestExpense']->cost, 0, ',', '.') }}</strong> ({{ $analytics['highestExpense']->service_name }})
                    </span>
                    @endif
                    @if($analytics['mostFrequentItem'])
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 text-blue-700 font-semibold border border-blue-100 text-[11px]">
                        <i class="ph-bold ph-repeat"></i>
                        Paling Sering: <strong>{{ $analytics['mostFrequentItem']['name'] }}</strong> ({{ $analytics['mostFrequentItem']['count'] }}x)
                    </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                <!-- Grafik 1: Tren Bulanan Tahun Ini (7 Kolom) -->
                <div class="lg:col-span-7">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="ph-bold ph-calendar-blank text-indigo-600"></i>
                            Tren Pengeluaran Bulanan ({{ $analytics['currentYear'] }})
                        </h4>
                        <span class="text-[11px] text-slate-500 font-mono">Bulan Jan - Des</span>
                    </div>
                    <div class="h-[210px] w-full">
                        <canvas id="monthlyExpenseChart"></canvas>
                    </div>
                </div>

                <!-- Grafik 2: Alokasi Biaya per Kategori Komponen (5 Kolom) -->
                <div class="lg:col-span-5 lg:border-l lg:border-slate-100 lg:pl-6">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="ph-bold ph-pie-chart text-emerald-600"></i>
                            Komponen Terbanyak (Top 5)
                        </h4>
                        <span class="text-[11px] text-slate-500">Berdasarkan Total Biaya</span>
                    </div>
                    <div class="h-[210px] w-full flex items-center justify-center">
                        <canvas id="categoryExpenseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Official Print Header (Only visible on Print) -->
        <div class="hidden print:block mb-6 pb-4 border-b-2 border-gray-800">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-black tracking-tight text-gray-900">OTOKEEP - DIGITAL SERVICE LOGBOOK</h2>
                    <p class="text-xs text-gray-600">Dokumen Rekam Jejak Pemeliharaan Kendaraan Resmi</p>
                </div>
                <div class="text-right text-xs">
                    <p><strong>Kendaraan:</strong> {{ $vehicle->motor_name }} ({{ strtoupper($vehicle->vehicle_category) }})</p>
                    <p><strong>Odometer Terakhir:</strong> {{ number_format($vehicle->current_km, 0, ',', '.') }} KM</p>
                    <p><strong>Dicetak Pada:</strong> {{ now()->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- TABEL RIWAYAT SERVIS -->
        <div class="bg-white rounded-3xl overflow-hidden shadow-soft-sm border border-slate-200/80 print:shadow-none print:border print:border-gray-300">
            @if($history->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left print:text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200/80 print:bg-gray-100 print:text-black">
                            <tr>
                                <th class="px-6 py-4 text-slate-500 font-bold uppercase text-[11px] tracking-wider print:text-black">Tanggal</th>
                                <th class="px-6 py-4 text-slate-500 font-bold uppercase text-[11px] tracking-wider print:text-black">Komponen / Pekerjaan</th>
                                <th class="px-6 py-4 text-slate-500 font-bold uppercase text-[11px] tracking-wider print:text-black">Kilometer</th>
                                <th class="px-6 py-4 text-slate-500 font-bold uppercase text-[11px] tracking-wider print:text-black">Total Biaya</th>
                                <th class="px-6 py-4 text-slate-500 font-bold uppercase text-[11px] tracking-wider print:text-black">Bengkel & Catatan</th>
                                <th class="px-6 py-4 text-slate-500 font-bold uppercase text-[11px] tracking-wider text-right print:hidden">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 print:divide-gray-200 print:text-black">
                            @foreach($history as $item)
                                <tr class="hover:bg-slate-50/60 transition-colors print:hover:bg-transparent">
                                    <td class="px-6 py-4 text-slate-900 font-medium whitespace-nowrap print:text-black text-sm">
                                        <div class="flex items-center gap-2">
                                            <i class="ph-bold ph-calendar text-slate-400 print:hidden text-xs"></i>
                                            <span>{{ $item->service_date ? \Carbon\Carbon::parse($item->service_date)->format('d M Y') : '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($item->isCustom())
                                                <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 print:hidden border border-amber-200/60 shrink-0">
                                                    <i class="ph-bold ph-wrench text-base"></i>
                                                </div>
                                                <div>
                                                    <span class="text-slate-900 font-bold text-sm block print:text-black">{{ $item->custom_service_name ?: 'Servis Bengkel Khusus' }}</span>
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200/80 mt-0.5">
                                                        <i class="ph-fill ph-sparkle text-[9px]"></i> Servis Di Luar Kategori
                                                    </span>
                                                </div>
                                            @else
                                                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 print:hidden border border-blue-200/60 shrink-0">
                                                    <i class="ph-bold ph-nut text-base"></i>
                                                </div>
                                                <div>
                                                    <span class="text-slate-900 font-bold text-sm block print:text-black">{{ $item->category->name ?? 'Servis Berkala' }}</span>
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200/80 mt-0.5">
                                                        <i class="ph-bold ph-check text-[9px]"></i> Kategori Rutin
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-700 font-mono print:text-black whitespace-nowrap text-sm">
                                        {{ number_format($item->service_km, 0, ',', '.') }} KM
                                    </td>
                                    <td class="px-6 py-4 text-emerald-700 font-mono font-bold print:text-black whitespace-nowrap text-sm">
                                        @if($item->cost > 0)
                                            Rp {{ number_format($item->cost, 0, ',', '.') }}
                                        @else
                                            <span class="text-slate-400 font-normal italic text-xs print:text-gray-400">Gratis / Garansi</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-700 text-xs print:text-black max-w-xs">
                                        @if($item->workshop_name)
                                            <div class="font-bold text-slate-900 print:text-black flex items-center gap-1.5">
                                                <i class="ph-bold ph-storefront text-blue-600 print:hidden"></i>
                                                <span>{{ $item->workshop_name }}</span>
                                            </div>
                                        @endif
                                        @if($item->notes)
                                            <div class="text-slate-500 print:text-gray-600 mt-1 line-clamp-2 leading-relaxed">{{ $item->notes }}</div>
                                        @endif
                                        @if(!$item->workshop_name && !$item->notes)
                                            <span class="text-slate-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right print:hidden whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] px-2.5 py-1 rounded-full font-bold tracking-wider inline-flex items-center gap-1">
                                                <i class="ph-bold ph-check"></i> Selesai
                                            </span>
                                            <form action="{{ route('history.delete', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan servis ini dari riwayat?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Riwayat">
                                                    <i class="ph-bold ph-trash text-base"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-16 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4 border border-blue-100">
                        <i class="ph-bold ph-wrench text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Riwayat Servis Tercatat</h3>
                    <p class="text-slate-500 text-xs max-w-md mx-auto mb-6">
                        Catat setiap servis berkala atau perbaikan di bengkel (termasuk penggantian ban, aki, shockbreaker, dan sparepart lain) untuk memantau total pengeluaran dan kesehatan kendaraan Anda.
                    </p>
                    <button onclick="openAddServiceModal()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-all shadow-md shadow-blue-500/25 inline-flex items-center gap-2">
                        <i class="ph-bold ph-plus-circle text-base"></i>
                        <span>Catat Servis Pertama Sekarang</span>
                    </button>
                </div>
            @endif
        </div>
    </main>
</div>

<!-- MODAL TAMBAH RIWAYAT SERVIS BENGKEL & SPAREPART -->
<div id="modal-add-service" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm hidden transition-all duration-300">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200/80 max-h-[90vh] overflow-y-auto animate-scale-up">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 border border-blue-200/60 flex items-center justify-center font-bold">
                    <i class="ph-bold ph-wrench text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Catat Servis Bengkel</h3>
                    <p class="text-xs text-slate-500">Input riwayat servis rutin maupun servis di luar sistem</p>
                </div>
            </div>
            <button onclick="closeAddServiceModal()" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition-colors flex items-center justify-center">
                <i class="ph-bold ph-x text-base"></i>
            </button>
        </div>

        <form action="{{ route('history.record') }}" method="POST" id="form-add-service-history" class="space-y-4">
            @csrf

            <!-- Pilihan Kendaraan (Jika ada lebih dari 1 kendaraan) -->
            @if(isset($vehicles) && $vehicles->count() > 1)
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Kendaraan</label>
                <select name="vehicle_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 bg-white">
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ $v->id === $vehicle->id ? 'selected' : '' }}>
                            {{ $v->motor_name }} ({{ strtoupper($v->vehicle_category) }} - {{ number_format($v->current_km, 0, ',', '.') }} KM)
                        </option>
                    @endforeach
                </select>
            </div>
            @else
            <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
            @endif

            <!-- PILIHAN TIPE SERVIS: KATEGORI RESMI vs LUAR SISTEM (CUSTOM) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tipe Pekerjaan Servis</label>
                <div class="grid grid-cols-2 gap-2 p-1 bg-slate-100 rounded-2xl">
                    <label class="cursor-pointer">
                        <input type="radio" name="service_type" value="custom" checked onchange="toggleServiceTypeField()" class="sr-only peer">
                        <div class="py-2.5 px-3 rounded-xl text-xs font-bold text-center transition-all peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm text-slate-600 hover:text-slate-900 flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-sparkle text-sm"></i>
                            <span>Servis Luar Sistem</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="service_type" value="category" onchange="toggleServiceTypeField()" class="sr-only peer">
                        <div class="py-2.5 px-3 rounded-xl text-xs font-bold text-center transition-all peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm text-slate-600 hover:text-slate-900 flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-nut text-sm"></i>
                            <span>Komponen Rutin</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- FIELD SERVIS KUSTOM (DI LUAR SISTEM) -->
            <div id="field-custom-service">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Servis / Sparepart <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="custom_service_name" id="input-custom-service-name" placeholder="cth: Ganti Ban Tubeless Belakang, Kuras Tangki, Aki..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 placeholder:text-slate-400">
                
                <!-- Quick Suggestion Chips -->
                <div class="flex flex-wrap gap-1.5 mt-2">
                    <span class="text-[11px] text-slate-400 font-medium py-0.5">Pilihan cepat:</span>
                    <button type="button" onclick="setQuickServiceName('Ganti Ban Luar & Dalam')" class="text-[11px] bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 px-2 py-0.5 rounded-lg border border-slate-200 transition-colors">+ Ganti Ban</button>
                    <button type="button" onclick="setQuickServiceName('Ganti Aki (Baterai)')" class="text-[11px] bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 px-2 py-0.5 rounded-lg border border-slate-200 transition-colors">+ Aki</button>
                    <button type="button" onclick="setQuickServiceName('Servis Shockbreaker')" class="text-[11px] bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 px-2 py-0.5 rounded-lg border border-slate-200 transition-colors">+ Shockbreaker</button>
                    <button type="button" onclick="setQuickServiceName('Ganti Busi Iridium')" class="text-[11px] bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 px-2 py-0.5 rounded-lg border border-slate-200 transition-colors">+ Busi</button>
                    <button type="button" onclick="setQuickServiceName('Spooring & Balancing')" class="text-[11px] bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 px-2 py-0.5 rounded-lg border border-slate-200 transition-colors">+ Spooring</button>
                </div>
            </div>

            <!-- FIELD KATEGORI SISTEM (TERSEMBUNYI SAAT TIPE CUSTOM) -->
            <div id="field-category-service" class="hidden">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Pilih Kategori Komponen <span class="text-rose-500">*</span>
                </label>
                <select name="category_id" id="select-category-id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 bg-white">
                    <option value="">-- Pilih Komponen --</option>
                    @if(isset($categories))
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }} (Interval ~{{ number_format($cat->default_interval_km, 0, ',', '.') }} KM)</option>
                        @endforeach
                    @endif
                </select>
                
                <label class="flex items-center gap-2 mt-2 text-xs text-slate-600 cursor-pointer">
                    <input type="checkbox" name="sync_component_interval" value="1" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>Otomatis perbarui interval target servis komponen ini di Dashboard</span>
                </label>
            </div>

            <!-- TANGGAL SERVIS & ODOMETER (KM) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Servis</label>
                    <input type="date" name="service_date" value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kilometer ODO Saat Servis</label>
                    <div class="relative">
                        <input type="number" name="service_km" value="{{ $vehicle->current_km }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 pr-10 font-mono">
                        <span class="absolute right-3.5 top-2.5 text-xs font-bold text-slate-400">KM</span>
                    </div>
                </div>
            </div>

            <!-- TOTAL BIAYA (RP) & NAMA BENGKEL -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Total Biaya (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" name="cost" id="input-service-cost" value="0" min="0" step="500" oninput="formatRupiahPreview(this.value)" class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 font-mono">
                    </div>
                    <div id="cost-preview-text" class="text-[11px] text-emerald-600 font-bold mt-1">Rp 0 (Gratis / Garansi)</div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Bengkel / Toko</label>
                    <input type="text" name="workshop_name" placeholder="cth: AHASS 0918, Shop&Drive..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 placeholder:text-slate-400">
                </div>
            </div>

            <!-- CATATAN / DETAIL PEKERJAAN -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan & Rincian Sparepart</label>
                <textarea name="notes" rows="2" placeholder="cth: Termasuk ongkos pasang Rp 35.000, garansi toko 1 bulan, part bawaan disimpan." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 placeholder:text-slate-400 resize-none"></textarea>
            </div>

            <!-- TOMBOL AKSI SUBMIT -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeAddServiceModal()" class="px-4 py-2.5 rounded-xl text-slate-600 hover:text-slate-900 text-xs font-bold transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-md shadow-blue-500/25 flex items-center gap-2">
                    <i class="ph-bold ph-check text-base"></i>
                    <span>Simpan ke Buku Servis</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddServiceModal() {
        const modal = document.getElementById('modal-add-service');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            const input = document.getElementById('input-custom-service-name');
            if (input && !input.disabled) input.focus();
        }, 100);
    }

    function closeAddServiceModal() {
        const modal = document.getElementById('modal-add-service');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function toggleServiceTypeField() {
        const selectedType = document.querySelector('input[name="service_type"]:checked').value;
        const customField = document.getElementById('field-custom-service');
        const categoryField = document.getElementById('field-category-service');
        const customInput = document.getElementById('input-custom-service-name');
        const categorySelect = document.getElementById('select-category-id');

        if (selectedType === 'custom') {
            customField.classList.remove('hidden');
            categoryField.classList.add('hidden');
            if (customInput) customInput.focus();
        } else {
            customField.classList.add('hidden');
            categoryField.classList.remove('hidden');
            if (categorySelect) categorySelect.focus();
        }
    }

    function setQuickServiceName(name) {
        const input = document.getElementById('input-custom-service-name');
        if (input) {
            input.value = name;
            input.focus();
        }
    }

    function formatRupiahPreview(val) {
        const preview = document.getElementById('cost-preview-text');
        const num = parseInt(val, 10);
        if (isNaN(num) || num <= 0) {
            preview.innerText = 'Rp 0 (Gratis / Garansi)';
            preview.className = 'text-[11px] text-slate-500 mt-1';
        } else {
            preview.innerText = 'Total: Rp ' + num.toLocaleString('id-ID');
            preview.className = 'text-[11px] text-emerald-600 font-bold mt-1';
        }
    }

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddServiceModal();
        }
    });

    // Close on clicking backdrop
    document.getElementById('modal-add-service').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddServiceModal();
        }
    });

    // ==========================================
    // CHART.JS: PERSONAL EXPENSE TRACKER
    // ==========================================
    @if($totalServices > 0)
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyCanvas = document.getElementById('monthlyExpenseChart');
        if (monthlyCanvas) {
            const monthlyCtx = monthlyCanvas.getContext('2d');
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: @json($analytics['monthlyLabels']),
                    datasets: [{
                        label: 'Pengeluaran (Rp)',
                        data: @json($analytics['monthlyValues']),
                        backgroundColor: 'rgba(37, 99, 235, 0.85)',
                        hoverBackgroundColor: '#1D4ED8',
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
                                label: function(ctx) {
                                    return ' Pengeluaran: Rp ' + Number(ctx.raw || 0).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(val) {
                                    if (val >= 1000000) return (val / 1000000).toFixed(1) + ' Jt';
                                    if (val >= 1000) return (val / 1000).toFixed(0) + ' Rb';
                                    return val;
                                },
                                font: { size: 10 },
                                color: '#64748B'
                            },
                            grid: { color: 'rgba(226, 232, 240, 0.6)' },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: { font: { size: 10 }, color: '#64748B' }
                        }
                    }
                }
            });
        }

        const catCanvas = document.getElementById('categoryExpenseChart');
        if (catCanvas && @json(count($analytics['categoryLabels'])) > 0) {
            const catCtx = catCanvas.getContext('2d');
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($analytics['categoryLabels']),
                    datasets: [{
                        data: @json($analytics['categoryValues']),
                        backgroundColor: ['#2563EB', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899'],
                        borderColor: '#FFFFFF',
                        borderWidth: 2,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 8,
                                usePointStyle: true,
                                font: { size: 10, weight: 'bold' }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const val = ctx.raw || 0;
                                    const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                    return ` Rp ${Number(val).toLocaleString('id-ID')} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
    @endif
</script>

<style>
@media print {
    body {
        background: #fff !important;
        color: #000 !important;
    }
    aside, header button, .print\:hidden, #modal-add-service {
        display: none !important;
    }
    .print\:block {
        display: block !important;
    }
    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    th, td {
        border-bottom: 1px solid #ddd !important;
        padding: 8px !important;
    }
}
</style>
@endsection
