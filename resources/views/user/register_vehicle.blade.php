@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-transparent px-6 py-12 relative overflow-hidden">
    <!-- Subtle Modern Decors -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/15 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-500/15 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-2xl w-full relative z-10">
        @if(isset($hasVehicles) && $hasVehicles)
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-all shadow-soft-sm">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
        @endif

        <div class="text-center mb-8">
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                {{ isset($hasVehicles) && $hasVehicles ? 'Tambah Kendaraan Baru' : 'Satu Langkah Lagi!' }}
            </h2>
            <p class="text-slate-500 mt-2 text-sm md:text-base">
                Daftarkan kendaraan Anda. Paket jadwal servis berkala otomatis langsung aktif di dashboard.
            </p>
        </div>

        <div class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-200/80 shadow-soft-xl">
            <form action="{{ route('vehicle.register') }}" method="POST" id="vehicle-form" class="space-y-8">
                @csrf
                
                <!-- Vehicle Category Selection -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-4 text-center">Pilih Kategori Kendaraan</label>
                    <input type="hidden" name="vehicle_category" id="vehicle_category" value="motor">
                    
                    <div class="grid grid-cols-2 gap-4 max-w-md mx-auto">
                        <!-- Card Motor -->
                        <div onclick="selectCategory('motor')" id="card-motor" class="cursor-pointer p-6 border-2 border-brand-600 bg-blue-50/70 rounded-2xl text-center transition-all hover:scale-[1.02] shadow-soft-sm">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 text-brand-600" id="icon-motor-wrapper">
                                <i class="ph ph-motorcycle text-4xl"></i>
                            </div>
                            <span class="text-slate-900 font-bold block text-base" id="text-motor-title">Sepeda Motor</span>
                            <span class="text-slate-500 text-xs mt-1 block">Skutik, Manual, Sport</span>
                        </div>
                        
                        <!-- Card Mobil -->
                        <div onclick="selectCategory('mobil')" id="card-mobil" class="cursor-pointer p-6 border-2 border-slate-200 bg-slate-50/60 rounded-2xl text-center transition-all hover:scale-[1.02] hover:border-slate-300">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400" id="icon-mobil-wrapper">
                                <i class="ph ph-car text-4xl"></i>
                            </div>
                            <span class="text-slate-600 font-bold block text-base" id="text-mobil-title">Mobil</span>
                            <span class="text-slate-400 text-xs mt-1 block">Sedan, SUV, MPV, EV</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vehicle Name Input -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2" id="label-name">Nama Motor</label>
                        <input type="text" name="motor_name" id="motor_name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3.5 px-4 text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all placeholder:text-slate-400 text-sm" placeholder="Contoh: Honda Vario 160">
                    </div>

                    <!-- Transmission Type -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3" id="label-type">Tipe Motor</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="matic" class="hidden peer" checked required>
                                <div class="p-4 border border-slate-200 bg-slate-50/60 rounded-xl text-center peer-checked:border-brand-600 peer-checked:bg-blue-50/80 transition-all text-slate-500 peer-checked:text-brand-700">
                                    <i class="ph ph-scooter text-2xl block mb-1" id="icon-trans-matic"></i>
                                    <span class="text-sm font-bold" id="text-trans-matic">Matic</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="manual" class="hidden peer">
                                <div class="p-4 border border-slate-200 bg-slate-50/60 rounded-xl text-center peer-checked:border-brand-600 peer-checked:bg-blue-50/80 transition-all text-slate-500 peer-checked:text-brand-700">
                                    <i class="ph ph-motorcycle text-2xl block mb-1" id="icon-trans-manual"></i>
                                    <span class="text-sm font-bold">Manual</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Fuel System / Fuel Category -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Sistem Bahan Bakar</label>
                        <!-- Motor Fuel Systems (Default) -->
                        <div class="grid grid-cols-2 gap-3" id="fuel-system-motor-wrapper">
                            <label class="cursor-pointer">
                                <input type="radio" name="fuel_system_motor" value="injeksi" class="hidden peer" checked onclick="syncFuelSystem('injeksi')">
                                <div class="p-4 border border-slate-200 bg-slate-50/60 rounded-xl text-center peer-checked:border-brand-600 peer-checked:bg-blue-50/80 transition-all text-slate-500 peer-checked:text-brand-700">
                                    <i class="ph ph-drop text-2xl block mb-1"></i>
                                    <span class="text-sm font-bold">Injeksi</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="fuel_system_motor" value="karbu" class="hidden peer" onclick="syncFuelSystem('karbu')">
                                <div class="p-4 border border-slate-200 bg-slate-50/60 rounded-xl text-center peer-checked:border-brand-600 peer-checked:bg-blue-50/80 transition-all text-slate-500 peer-checked:text-brand-700">
                                    <i class="ph ph-fire text-2xl block mb-1"></i>
                                    <span class="text-sm font-bold">Karbu</span>
                                </div>
                            </label>
                        </div>

                        <!-- Mobil Fuel Systems -->
                        <div class="grid grid-cols-2 gap-2 hidden" id="fuel-system-mobil-wrapper">
                            <label class="cursor-pointer">
                                <input type="radio" name="fuel_system_mobil" value="injeksi" class="hidden peer" onclick="syncFuelSystem('injeksi')">
                                <div class="p-3 border border-slate-200 bg-slate-50/60 rounded-xl text-center peer-checked:border-emerald-600 peer-checked:bg-emerald-50/80 transition-all text-xs text-slate-500 peer-checked:text-emerald-700">
                                    <span class="font-bold block">Bensin / Injeksi</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="fuel_system_mobil" value="karbu" class="hidden peer" onclick="syncFuelSystem('karbu')">
                                <div class="p-3 border border-slate-200 bg-slate-50/60 rounded-xl text-center peer-checked:border-emerald-600 peer-checked:bg-emerald-50/80 transition-all text-xs text-slate-500 peer-checked:text-emerald-700">
                                    <span class="font-bold block">Karburator</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="fuel_system_mobil" value="hybrid" class="hidden peer" onclick="syncFuelSystem('hybrid')">
                                <div class="p-3 border border-slate-200 bg-slate-50/60 rounded-xl text-center peer-checked:border-emerald-600 peer-checked:bg-emerald-50/80 transition-all text-xs text-slate-500 peer-checked:text-emerald-700">
                                    <span class="font-bold block">Hybrid</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="fuel_system_mobil" value="listrik" class="hidden peer" onclick="syncFuelSystem('listrik')">
                                <div class="p-3 border border-slate-200 bg-slate-50/60 rounded-xl text-center peer-checked:border-emerald-600 peer-checked:bg-emerald-50/80 transition-all text-xs text-slate-500 peer-checked:text-emerald-700">
                                    <span class="font-bold block">Listrik (EV)</span>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Actual Hidden Input for DB save -->
                        <input type="hidden" name="fuel_system" id="fuel_system" value="injeksi">
                    </div>

                    <!-- Current Odometer Reading -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kilometer Saat Ini (Odometer)</label>
                        <div class="relative">
                            <input type="number" name="current_km" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3.5 px-4 text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all placeholder:text-slate-400 text-sm font-mono" placeholder="0">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">KM</span>
                        </div>
                    </div>

                    <!-- Optional Tax Dates -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Pajak Tahunan STNK (Opsional)</label>
                        <input type="date" name="stnk_tax_due_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Pajak Plat 5 Tahunan (Opsional)</label>
                        <input type="date" name="five_year_tax_due_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-sm">
                    </div>

                    <!-- Preset Service Notice -->
                    <div class="md:col-span-2 p-4 bg-emerald-50/80 border border-emerald-200/80 rounded-2xl flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-magic-wand text-lg"></i>
                        </div>
                        <p class="text-xs text-slate-700 leading-relaxed">
                            <strong class="text-slate-900">Paket Servis Otomatis:</strong> Setelah disimpan, OtoKeep akan otomatis menyiapkan pemantauan oli mesin, kampas rem, cairan radiator, dan komponen berkala lainnya sesuai standar pabrikan kendaraan Anda!
                        </p>
                    </div>

                    <div class="md:col-span-2 mt-2">
                        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-4 rounded-xl shadow-md shadow-brand-500/20 transition-all text-base flex items-center justify-center gap-2">
                            Simpan & Masuk ke Dashboard <i class="ph ph-arrow-right font-bold"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function selectCategory(category) {
        document.getElementById('vehicle_category').value = category;
        
        const cardMotor = document.getElementById('card-motor');
        const cardMobil = document.getElementById('card-mobil');
        
        const labelName = document.getElementById('label-name');
        const inputName = document.getElementById('motor_name');
        const labelType = document.getElementById('label-type');
        
        const textTransMatic = document.getElementById('text-trans-matic');
        const iconTransMatic = document.getElementById('icon-trans-matic');
        const iconTransManual = document.getElementById('icon-trans-manual');
        
        const fuelMotor = document.getElementById('fuel-system-motor-wrapper');
        const fuelMobil = document.getElementById('fuel-system-mobil-wrapper');
        const actualFuelInput = document.getElementById('fuel_system');
        
        if (category === 'motor') {
            // Update Card classes
            cardMotor.className = "cursor-pointer p-6 border-2 border-brand-600 bg-blue-50/70 rounded-2xl text-center transition-all hover:scale-[1.02] shadow-soft-sm";
            cardMobil.className = "cursor-pointer p-6 border-2 border-slate-200 bg-slate-50/60 rounded-2xl text-center transition-all hover:scale-[1.02] hover:border-slate-300";
            
            // Internal styling
            document.getElementById('icon-motor-wrapper').className = "w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 text-brand-600";
            document.getElementById('text-motor-title').className = "text-slate-900 font-bold block text-base";

            document.getElementById('icon-mobil-wrapper').className = "w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400";
            document.getElementById('text-mobil-title').className = "text-slate-600 font-bold block text-base";
            
            // Labels and placeholders
            labelName.innerText = "Nama Motor";
            inputName.placeholder = "Contoh: Honda Vario 160";
            labelType.innerText = "Tipe Motor";
            
            textTransMatic.innerText = "Matic";
            iconTransMatic.className = "ph ph-scooter text-2xl block mb-1";
            iconTransManual.className = "ph ph-motorcycle text-2xl block mb-1";
            
            // Fuel wrappers
            fuelMotor.classList.remove('hidden');
            fuelMobil.classList.add('hidden');
            
            // Reset active radio values
            const activeRadio = document.querySelector('input[name="fuel_system_motor"]:checked');
            actualFuelInput.value = activeRadio ? activeRadio.value : 'injeksi';
            
        } else {
            // Update Card classes
            cardMotor.className = "cursor-pointer p-6 border-2 border-slate-200 bg-slate-50/60 rounded-2xl text-center transition-all hover:scale-[1.02] hover:border-slate-300";
            cardMobil.className = "cursor-pointer p-6 border-2 border-emerald-600 bg-emerald-50/70 rounded-2xl text-center transition-all hover:scale-[1.02] shadow-soft-sm";
            
            // Internal styling
            document.getElementById('icon-motor-wrapper').className = "w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400";
            document.getElementById('text-motor-title').className = "text-slate-600 font-bold block text-base";

            document.getElementById('icon-mobil-wrapper').className = "w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3 text-emerald-600";
            document.getElementById('text-mobil-title').className = "text-slate-900 font-bold block text-base";
            
            // Labels and placeholders
            labelName.innerText = "Nama Mobil";
            inputName.placeholder = "Contoh: Toyota Avanza Veloz";
            labelType.innerText = "Tipe Transmisi";
            
            textTransMatic.innerText = "Automatic";
            iconTransMatic.className = "ph ph-gauge text-2xl block mb-1";
            iconTransManual.className = "ph ph-steering-wheel text-2xl block mb-1";
            
            // Fuel wrappers
            fuelMotor.classList.add('hidden');
            fuelMobil.classList.remove('hidden');
            
            // Set active radio or select a default one
            let activeRadio = document.querySelector('input[name="fuel_system_mobil"]:checked');
            if (!activeRadio) {
                const radios = document.getElementsByName('fuel_system_mobil');
                if (radios.length > 0) {
                    radios[0].checked = true;
                    activeRadio = radios[0];
                }
            }
            actualFuelInput.value = activeRadio ? activeRadio.value : 'injeksi';
        }
    }
    
    function syncFuelSystem(value) {
        document.getElementById('fuel_system').value = value;
    }
</script>
@endsection
