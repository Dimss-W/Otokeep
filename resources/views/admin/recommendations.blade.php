@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-transparent text-slate-800 flex flex-col lg:flex-row antialiased">
    <!-- Dedicated Admin Sidebar Component -->
    @include('layouts.admin_sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto max-h-screen">
        <!-- Top App Bar -->
        <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-xl border-b border-slate-200/80 px-6 py-4 flex items-center justify-between gap-4 shadow-soft-sm">
            <div class="flex items-center gap-4">
                <button onclick="toggleAdminSidebar()" class="lg:hidden p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all border border-slate-200">
                    <i class="ph-bold ph-list text-xl"></i>
                </button>
                <div>
                    <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        CMS Rekomendasi & Tips
                    </h1>
                    <p class="text-xs text-slate-500 hidden sm:block">Kelola artikel edukasi teknis yang tampil di dashboard pengguna berdasarkan kategori kendaraan.</p>
                </div>
            </div>

            <button onclick="document.getElementById('modal-add-rec').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-sm flex items-center gap-2 text-sm">
                <i class="ph-bold ph-plus"></i>
                <span class="hidden sm:inline">Tambah Konten</span>
            </button>
        </header>

        <main class="flex-1 p-6 md:p-8 lg:p-10 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($recommendations as $rec)
                    <div class="bg-white overflow-hidden rounded-3xl border border-slate-200/80 relative group flex flex-col hover:border-emerald-500/40 hover:shadow-soft-md transition-all shadow-soft-sm">
                        @if($rec->image)
                            <img src="{{ asset($rec->image) }}" class="w-full h-48 object-cover border-b border-slate-100" alt="{{ $rec->title }}">
                        @else
                            <div class="w-full h-48 bg-slate-50 flex items-center justify-center border-b border-slate-100">
                                <i class="ph ph-image text-4xl text-slate-300"></i>
                            </div>
                        @endif
                        
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @if(($rec->vehicle_category ?? 'all') === 'motor')
                                        <span class="bg-blue-50 text-blue-700 border border-blue-200/80 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">Motor</span>
                                    @elseif(($rec->vehicle_category ?? 'all') === 'mobil')
                                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">Mobil</span>
                                    @else
                                        <span class="bg-purple-50 text-purple-700 border border-purple-200/80 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">Semua</span>
                                    @endif
                                    <span class="bg-slate-100 text-slate-700 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">{{ $rec->motor_type }}</span>
                                    <span class="bg-slate-100 text-slate-700 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">{{ $rec->fuel_system }}</span>
                                </div>
                                <h3 class="text-slate-900 font-bold text-lg mb-2 leading-snug">{{ $rec->title }}</h3>
                                <p class="text-slate-500 text-xs leading-relaxed mb-6 line-clamp-3">{{ $rec->content }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                                <span class="text-[11px] text-slate-400 font-mono">
                                    {{ $rec->created_at->format('d M Y') }}
                                </span>
                                <form action="{{ url('admin/recommendations/'.$rec->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 transition-colors p-2 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-100" onclick="return confirm('Hapus konten artikel ini?')" title="Hapus Artikel">
                                        <i class="ph-bold ph-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 bg-white p-12 rounded-3xl border border-slate-200/80 text-center shadow-soft-sm">
                        <i class="ph ph-article text-5xl text-slate-300 mb-3 block"></i>
                        <h4 class="text-base font-bold text-slate-800">Belum ada artikel rekomendasi.</h4>
                        <p class="text-xs text-slate-500 mt-1 mb-4">Tambahkan panduan perawatan untuk membantu pemilik kendaraan merawat mesin mereka.</p>
                        <button onclick="document.getElementById('modal-add-rec').classList.remove('hidden')" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                            Tambah Konten Pertama
                        </button>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</div>

<!-- Modal Add -->
<div id="modal-add-rec" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-white max-w-lg w-full p-8 rounded-3xl relative z-10 max-h-[90vh] overflow-y-auto border border-slate-200 shadow-2xl">
        <h3 class="text-2xl font-bold text-slate-900 mb-1">Tambah Rekomendasi Cerdas</h3>
        <p class="text-xs text-slate-500 mb-6">Tulis artikel tips atau panduan spesifikasi untuk pengguna.</p>
        <form action="{{ url('admin/recommendations') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">Kategori Kendaraan</label>
                <select name="vehicle_category" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    <option value="all">Semua Kendaraan (Motor & Mobil)</option>
                    <option value="motor">Khusus Sepeda Motor</option>
                    <option value="mobil">Khusus Mobil</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Tipe Transmisi</label>
                    <select name="motor_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        <option value="all">Semua Tipe</option>
                        <option value="matic">Matic / Automatic</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Sistem Bahan Bakar</label>
                    <select name="fuel_system" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        <option value="all">Semua Sistem</option>
                        <option value="injeksi">Injeksi / Bensin</option>
                        <option value="karbu">Karburator</option>
                        <option value="hybrid">Hybrid</option>
                        <option value="listrik">Listrik (EV)</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">Gambar Sampul (Opsional)</label>
                <input type="file" name="image" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-slate-700 text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">Judul Artikel</label>
                <input type="text" name="title" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" placeholder="Contoh: Takaran dan Spesifikasi Oli Matic 160cc">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">Isi Konten / Panduan</label>
                <textarea name="content" rows="4" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" placeholder="Tuliskan rekomendasi lengkap, merek part yang dianjurkan, dan estimasi waktu penggantian..."></textarea>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('modal-add-rec').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3.5 rounded-xl transition-all text-sm">Batal</button>
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-sm text-sm">Simpan Konten</button>
            </div>
        </form>
    </div>
</div>
@endsection
