<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Servis Digital - {{ $vehicle->motor_name }} ({{ $docId }})</title>

    <!-- Official Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=3">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
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
                            500: '#A81010',
                            600: '#6C0000',
                            700: '#550000',
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: #0F172A;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        @page {
            size: A4 portrait;
            margin: 12mm 14mm 14mm 14mm;
        }

        @media print {
            body {
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .print-border {
                border-color: #CBD5E1 !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen antialiased text-slate-800">

    <!-- TOP ACTION BAR (Hanya Tampil di Layar / Hidden on Print) -->
    <header class="no-print sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200/80 shadow-sm py-3 px-4 sm:px-8">
        <div class="max-w-4xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('user.history') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 text-xs font-bold transition-all">
                    <i class="ph-bold ph-arrow-left"></i>
                    <span>Kembali ke Riwayat</span>
                </a>
                <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>
                <span class="text-xs font-bold text-slate-600 hidden sm:inline-flex items-center gap-1.5">
                    <i class="ph-bold ph-file-text text-blue-600"></i>
                    Buku Rekam Jejak Servis Digital Resmi
                </span>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-blue-500/25 flex items-center justify-center gap-2 transition-all">
                    <i class="ph-bold ph-printer text-base"></i>
                    <span>Cetak / Unduh PDF</span>
                </button>
            </div>
        </div>
    </header>

    <!-- NOTIFIKASI PANDUAN UNDUH PDF (Screen Only) -->
    <div class="no-print max-w-4xl mx-auto px-4 mt-4">
        <div class="p-3.5 bg-blue-50/80 border border-blue-200/70 rounded-2xl flex items-center justify-between gap-3 text-xs text-blue-800">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-info text-sm"></i>
                </div>
                <span><strong>Tips Simpan File PDF:</strong> Pada jendela cetak browser yang muncul, pilih opsi <em>Destination: "Save as PDF" (Simpan sebagai PDF)</em>.</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-800">
                <i class="ph-bold ph-x text-sm"></i>
            </button>
        </div>
    </div>

    <!-- DOCUMENT CONTAINER (A4 FORMAT) -->
    <main class="max-w-4xl mx-auto my-6 p-6 sm:p-10 bg-white rounded-3xl shadow-soft-md border border-slate-200/80 print:shadow-none print:border-none print:m-0 print:p-0">

        <!-- KOP DOKUMEN RESMI OTOKEEP -->
        <div class="pb-6 border-b-2 border-slate-900 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-700 text-white flex items-center justify-center shadow-md shadow-blue-500/20 shrink-0">
                        <i class="ph-bold ph-gauge text-3xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-black tracking-tight text-slate-900">OTOKEEP</span>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-extrabold text-[10px] tracking-wider uppercase border border-emerald-200">
                                Verified Logbook
                            </span>
                        </div>
                        <h1 class="text-sm font-extrabold text-slate-700 tracking-wide uppercase mt-0.5">
                            Buku Rekam Jejak Pemeliharaan Kendaraan
                        </h1>
                        <p class="text-[11px] text-slate-500">
                            Dokumen resmi rekapitulasi servis, penggantian suku cadang, dan catatan odometer.
                        </p>
                    </div>
                </div>

                <!-- Nomor Seri Dokumen & QR Verification -->
                <div class="text-left sm:text-right border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100 w-full sm:w-auto">
                    <span class="block text-[10px] uppercase font-bold tracking-wider text-slate-400">No. Sertifikat Digital</span>
                    <span class="block text-xs font-black font-mono text-slate-900">{{ $docId }}</span>
                    <span class="block text-[11px] text-slate-500 mt-0.5">
                        Diterbitkan: <strong>{{ now()->translatedFormat('d F Y') }}</strong>
                    </span>
                </div>
            </div>
        </div>

        <!-- IDENTITAS KENDARAAN & PEMILIK (KARTU SERTIFIKAT) -->
        <div class="mb-6 bg-slate-50/80 rounded-2xl p-5 border border-slate-200/90 print:bg-slate-50">
            <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-200/70">
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                    <i class="ph-bold ph-identification-card text-blue-600 text-base"></i>
                    Informasi Kendaraan & Kepemilikan
                </h2>
                <span class="text-[11px] text-slate-500 font-mono">
                    ID Akun: #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Nama Kendaraan</span>
                    <span class="font-extrabold text-slate-900 text-sm">{{ $vehicle->motor_name }}</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Kategori & Transmisi</span>
                    <span class="font-bold text-slate-800 capitalize">
                        {{ $vehicle->vehicle_category === 'mobil' ? 'Mobil' : 'Sepeda Motor' }} ({{ strtoupper($vehicle->type) }})
                    </span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Sistem Bahan Bakar</span>
                    <span class="font-bold text-slate-800 capitalize">{{ $vehicle->fuel_system }}</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Odometer Terkini</span>
                    <span class="font-black font-mono text-blue-700 text-sm">
                        {{ number_format($vehicle->current_km, 0, ',', '.') }} KM
                    </span>
                </div>

                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Nama Pemilik</span>
                    <span class="font-bold text-slate-800">{{ $user->name }}</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Email Pemilik</span>
                    <span class="font-medium text-slate-700 truncate block">{{ $user->email }}</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Pajak Tahunan STNK</span>
                    <span class="font-medium text-slate-800">
                        {{ $vehicle->stnk_tax_due_date ? $vehicle->stnk_tax_due_date->format('d/m/Y') : 'Belum diatur' }}
                    </span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Pajak 5 Tahunan (Plat)</span>
                    <span class="font-medium text-slate-800">
                        {{ $vehicle->five_year_tax_due_date ? $vehicle->five_year_tax_due_date->format('d/m/Y') : 'Belum diatur' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- RINGKASAN METRIK PERAWATAN (EXECUTIVE SUMMARY) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <div class="p-3.5 bg-blue-50/60 border border-blue-100 rounded-2xl text-center">
                <span class="block text-[10px] uppercase font-bold text-blue-700">Total Kunjungan Servis</span>
                <span class="text-xl font-black font-mono text-blue-900">{{ $totalServices }}</span>
                <span class="block text-[10px] text-blue-600 mt-0.5">Catatan Masuk</span>
            </div>

            <div class="p-3.5 bg-emerald-50/60 border border-emerald-100 rounded-2xl text-center">
                <span class="block text-[10px] uppercase font-bold text-emerald-700">Total Akumulasi Biaya</span>
                <span class="text-xl font-black font-mono text-emerald-900">
                    Rp {{ number_format($totalExpense, 0, ',', '.') }}
                </span>
                <span class="block text-[10px] text-emerald-600 mt-0.5">Investasi Perawatan</span>
            </div>

            <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-center">
                <span class="block text-[10px] uppercase font-bold text-slate-500">Servis Pertama Dicatat</span>
                <span class="text-xs font-bold text-slate-900 block mt-1">
                    {{ $firstService && $firstService->service_date ? $firstService->service_date->format('d/m/Y') : '-' }}
                </span>
                <span class="block text-[10px] font-mono text-slate-500">
                    {{ $firstService ? number_format($firstService->service_km, 0, ',', '.') . ' KM' : '-' }}
                </span>
            </div>

            <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-center">
                <span class="block text-[10px] uppercase font-bold text-slate-500">Servis Terakhir Dicatat</span>
                <span class="text-xs font-bold text-slate-900 block mt-1">
                    {{ $lastService && $lastService->service_date ? $lastService->service_date->format('d/m/Y') : '-' }}
                </span>
                <span class="block text-[10px] font-mono text-slate-500">
                    {{ $lastService ? number_format($lastService->service_km, 0, ',', '.') . ' KM' : '-' }}
                </span>
            </div>
        </div>

        <!-- TABEL REKAM JEJAK SERVIS DETAIL -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">
                    Daftar Catatan Perawatan & Penggantian Suku Cadang
                </h3>
                <span class="text-[11px] text-slate-500">Diurutkan kronologis (terawal s/d terbaru)</span>
            </div>

            <div class="border border-slate-200 rounded-2xl overflow-hidden print-border">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-100/90 text-slate-700 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                            <th class="py-2.5 px-3 w-10 text-center">No</th>
                            <th class="py-2.5 px-3 w-24">Tanggal</th>
                            <th class="py-2.5 px-3 w-24 text-right">Odometer</th>
                            <th class="py-2.5 px-3">Komponen / Pekerjaan</th>
                            <th class="py-2.5 px-3">Bengkel Pelaksana</th>
                            <th class="py-2.5 px-3">Catatan / Keterangan</th>
                            <th class="py-2.5 px-3 w-28 text-right">Biaya (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($history as $index => $item)
                        <tr class="{{ $index % 2 === 1 ? 'bg-slate-50/50' : 'bg-white' }} hover:bg-blue-50/30 transition-colors">
                            <td class="py-2.5 px-3 text-center text-slate-400 font-mono text-[11px]">{{ $index + 1 }}</td>
                            <td class="py-2.5 px-3 font-semibold text-slate-900 whitespace-nowrap">
                                {{ $item->service_date ? $item->service_date->format('d/m/Y') : '-' }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-700 whitespace-nowrap">
                                {{ number_format($item->service_km, 0, ',', '.') }} KM
                            </td>
                            <td class="py-2.5 px-3">
                                <span class="font-extrabold text-slate-900 block">{{ $item->service_name }}</span>
                                @if(!$item->isCustom())
                                    <span class="text-[10px] text-blue-600 font-semibold">Komponen Rekomendasi</span>
                                @else
                                    <span class="text-[10px] text-slate-400 font-medium">Pekerjaan Khusus</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-slate-700">
                                {{ $item->workshop_name ?: 'Bengkel Mandiri / Umum' }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-500 text-[11px]">
                                {{ $item->notes ?: '-' }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                Rp {{ number_format($item->cost, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada riwayat servis yang tercatat untuk kendaraan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-100 border-t-2 border-slate-300 font-black text-slate-900">
                            <td colspan="6" class="py-3 px-3 text-right text-xs uppercase tracking-wider">
                                Total Pengeluaran Perawatan:
                            </td>
                            <td class="py-3 px-3 text-right font-mono text-sm text-blue-700 whitespace-nowrap">
                                Rp {{ number_format($totalExpense, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- DIGITAL ENDORSEMENT, LEGAL DISCLAIMER, & DIGITAL STAMP -->
        <div class="pt-6 border-t-2 border-slate-200 grid grid-cols-1 sm:grid-cols-2 gap-6 items-end">
            <!-- Sisi Kiri: Disclaimer & Verifikasi Sistem -->
            <div class="space-y-2 text-[10px] text-slate-500">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                        <i class="ph-bold ph-shield-check"></i>
                    </div>
                    <span class="font-extrabold text-slate-800 uppercase tracking-wider">Jaminan Rekam Jejak Terverifikasi</span>
                </div>
                <p class="leading-relaxed">
                    Dokumen ini dihasilkan secara otomatis oleh sistem <strong>Otokeep Smart Maintenance Platform</strong> berdasarkan data transaksi servis dan pembacaan odometer riil pemilik kendaraan. Dokumen ini dapat digunakan sebagai lampiran rekam jejak servis resmi (*service record*) saat transaksi jual beli kendaraan.
                </p>
                <div class="font-mono text-[9px] text-slate-400">
                    Checksum Hash: {{ substr(sha1($docId . $totalExpense . $vehicle->id), 0, 24) }} • Otokeep Cloud ID
                </div>
            </div>

            <!-- Sisi Kanan: Cap Stempel Digital & Kolom Tanda Tangan -->
            <div class="flex items-center justify-end gap-6 text-center">
                <!-- Cap Stempel Digital -->
                <div class="w-28 h-28 rounded-full border-4 border-dashed border-blue-600/70 p-2 flex flex-col items-center justify-center rotate-[-8deg] bg-blue-50/40 select-none print:border-blue-700">
                    <span class="text-[8px] font-black uppercase tracking-widest text-blue-700 leading-tight">OTOKEEP</span>
                    <i class="ph-bold ph-seal-check text-2xl text-blue-600 my-0.5"></i>
                    <span class="text-[7px] font-black uppercase text-blue-800 tracking-wider">VERIFIED LOGBOOK</span>
                    <span class="text-[7px] font-mono text-blue-600">{{ now()->format('d/m/Y') }}</span>
                </div>

                <!-- Tanda Tangan Pemilik Kendaraan -->
                <div class="w-36 text-center">
                    <span class="block text-[10px] uppercase font-bold text-slate-400 mb-12">Pemilik Kendaraan</span>
                    <div class="border-b border-slate-400 mx-2"></div>
                    <span class="block text-xs font-bold text-slate-900 mt-1 truncate">{{ $user->name }}</span>
                    <span class="block text-[9px] text-slate-500">Terdaftar di Otokeep</span>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER COPYRIGHT (Screen Only) -->
    <footer class="no-print text-center py-6 text-xs text-slate-400">
        &copy; {{ date('Y') }} Otokeep • Sistem Pemantauan Servis & Riwayat Kendaraan Digital.
    </footer>

</body>
</html>
