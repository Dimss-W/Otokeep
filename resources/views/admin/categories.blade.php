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
                        Master Kategori Servis
                    </h1>
                    <p class="text-xs text-slate-500 hidden sm:block">Atur komponen servis standar pabrikan untuk sepeda motor dan mobil.</p>
                </div>
            </div>

            <button onclick="document.getElementById('modal-add-cat').classList.remove('hidden')" class="bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-700 hover:to-brand-800 text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-md shadow-brand-500/20 flex items-center gap-2 text-sm">
                <i class="ph-bold ph-plus"></i>
                <span class="hidden sm:inline">Tambah Kategori</span>
            </button>
        </header>

        <main class="flex-1 p-6 md:p-8 lg:p-10 space-y-6">

            <!-- Flash Session Alerts -->
            @if(session('success'))
            <div class="p-4 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/80 text-emerald-800 text-sm flex items-center justify-between shadow-soft-sm animate-fade-in">
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
            <div class="p-4 rounded-2xl bg-gradient-to-r from-rose-50 to-amber-50 border border-rose-200/80 text-rose-800 text-sm shadow-soft-sm animate-fade-in">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-rose-500 to-red-600 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm shadow-rose-500/20">
                        <i class="ph-bold ph-warning-circle text-lg"></i>
                    </div>
                    <div>
                        <span class="font-bold block mb-1">Gagal menyimpan data:</span>
                        <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-soft-sm">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-white via-white to-blue-50/20">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="ph-bold ph-squares-four text-blue-600"></i>
                            Daftar Master Komponen Servis
                        </h3>
                        <p class="text-xs text-slate-500">Pilihan komponen yang otomatis disarankan ke pengguna saat servis berkala.</p>
                    </div>
                    <span class="text-xs font-mono bg-blue-50 text-blue-700 border border-blue-200/80 px-3 py-1 rounded-xl font-bold">
                        {{ $categories->count() }} Kategori Aktif
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/80 border-b border-slate-200/80 text-xs uppercase font-bold text-slate-500 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Nama Komponen</th>
                                <th class="px-6 py-4">Target Kendaraan</th>
                                <th class="px-6 py-4">Interval Standar Pabrikan</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($categories as $cat)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4 text-slate-400 font-mono text-xs">#{{ $cat->id }}</td>
                                <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center font-bold text-xs shrink-0">
                                        <i class="ph-bold ph-nut"></i>
                                    </div>
                                    {{ $cat->name }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($cat->vehicle_category === 'motor')
                                        <span class="bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 border border-blue-200/80 text-xs px-2.5 py-0.5 rounded-full font-bold">Motor</span>
                                    @elseif($cat->vehicle_category === 'mobil')
                                        <span class="bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 border border-emerald-200/80 text-xs px-2.5 py-0.5 rounded-full font-bold">Mobil</span>
                                    @else
                                        <span class="bg-gradient-to-r from-purple-50 to-indigo-50 text-purple-700 border border-purple-200/80 text-xs px-2.5 py-0.5 rounded-full font-bold">Semua Kendaraan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-slate-700 text-xs">
                                    +{{ number_format($cat->default_interval_km ?? 2500, 0, ',', '.') }} KM <span class="text-slate-400">atau</span> {{ $cat->default_interval_months ?? 3 }} Bulan
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Tombol Edit Kategori -->
                                        <button type="button" 
                                            onclick="openEditCategoryModal({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ $cat->vehicle_category }}', {{ $cat->default_interval_km ?? 2500 }}, {{ $cat->default_interval_months ?? 3 }})" 
                                            class="text-blue-600 hover:text-blue-800 p-2 bg-blue-50 hover:bg-blue-100 border border-blue-200/80 rounded-xl transition-all" 
                                            title="Edit Kategori">
                                            <i class="ph-bold ph-pencil-simple text-base"></i>
                                        </button>

                                        <!-- Tombol Hapus Kategori -->
                                        <form action="{{ url('admin/categories/'.$cat->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 p-2 bg-rose-50 hover:bg-rose-100 border border-rose-100 rounded-xl transition-all" onclick="return confirm('Hapus kategori ini?')" title="Hapus Kategori">
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
            </div>
        </main>
    </div>
</div>

<!-- Modal Add -->
<div id="modal-add-cat" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-white max-w-sm w-full p-8 rounded-3xl relative z-10 border border-slate-200 shadow-2xl animate-scale-up">
        <h3 class="text-2xl font-bold text-slate-900 mb-1">Tambah Kategori</h3>
        <p class="text-xs text-slate-500 mb-6">Tambahkan nama komponen dan interval rekomendasi servis.</p>
        <form action="{{ url('admin/categories') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm transition-all" placeholder="Contoh: Oli Mesin">
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Target Kendaraan</label>
                <select name="vehicle_category" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm transition-all bg-white">
                    <option value="all">Semua Kendaraan</option>
                    <option value="motor">Khusus Sepeda Motor</option>
                    <option value="mobil">Khusus Mobil</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Interval Standar (KM)</label>
                <input type="number" name="default_interval_km" value="2500" min="100" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-mono transition-all">
            </div>
            <div class="mb-6">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Interval Standar (Bulan)</label>
                <input type="number" name="default_interval_months" value="3" min="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-mono transition-all">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modal-add-cat').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3.5 rounded-xl transition-all text-sm">Batal</button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-700 hover:to-brand-800 text-white font-bold py-3.5 rounded-xl transition-all shadow-md shadow-brand-500/20 text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div id="modal-edit-cat" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeEditCategoryModal()"></div>
    <div class="bg-white max-w-sm w-full p-8 rounded-3xl relative z-10 border border-slate-200 shadow-2xl animate-scale-up">
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-2xl font-bold text-slate-900">Edit Kategori</h3>
            <button type="button" onclick="closeEditCategoryModal()" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center">
                <i class="ph-bold ph-x text-base"></i>
            </button>
        </div>
        <p class="text-xs text-slate-500 mb-6">Perbarui nama komponen atau interval rekomendasi servis.</p>

        <form id="form-edit-cat" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" id="edit-cat-name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm transition-all" placeholder="Contoh: Oli Mesin">
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Target Kendaraan</label>
                <select name="vehicle_category" id="edit-cat-vehicle-category" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm transition-all bg-white">
                    <option value="all">Semua Kendaraan</option>
                    <option value="motor">Khusus Sepeda Motor</option>
                    <option value="mobil">Khusus Mobil</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Interval Standar (KM)</label>
                <input type="number" name="default_interval_km" id="edit-cat-interval-km" required min="100" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-mono transition-all">
            </div>
            <div class="mb-6">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Interval Standar (Bulan)</label>
                <input type="number" name="default_interval_months" id="edit-cat-interval-months" required min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-mono transition-all">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeEditCategoryModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3.5 rounded-xl transition-all text-sm">Batal</button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-700 hover:to-brand-800 text-white font-bold py-3.5 rounded-xl transition-all shadow-md shadow-brand-500/20 text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditCategoryModal(id, name, target, km, months) {
        const modal = document.getElementById('modal-edit-cat');
        const form = document.getElementById('form-edit-cat');
        
        form.action = "{{ url('admin/categories') }}/" + id;
        document.getElementById('edit-cat-name').value = name;
        document.getElementById('edit-cat-vehicle-category').value = target;
        document.getElementById('edit-cat-interval-km').value = km;
        document.getElementById('edit-cat-interval-months').value = months;

        modal.classList.remove('hidden');
    }

    function closeEditCategoryModal() {
        document.getElementById('modal-edit-cat').classList.add('hidden');
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditCategoryModal();
            const addModal = document.getElementById('modal-add-cat');
            if (addModal) addModal.classList.add('hidden');
        }
    });
</script>
@endsection
