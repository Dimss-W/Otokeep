@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-transparent text-slate-800 flex flex-col lg:flex-row antialiased">
    @include('layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-6 md:p-8 lg:p-10 overflow-y-auto">
        <header class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight mb-1">Informasi & Tips Perawatan</h1>
                <p class="text-xs md:text-sm text-slate-500">
                    Kumpulan artikel & panduan khusus untuk <span class="text-slate-900 font-bold">{{ $vehicle->motor_name ?? 'kendaraan' }}</span> Anda.
                </p>
            </div>
            @if(isset($vehicle))
                <span class="px-4 py-2 rounded-2xl text-xs font-bold border {{ $vehicle->vehicle_category === 'mobil' ? 'bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 border-emerald-200' : 'bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 border-blue-200' }} flex items-center gap-2 shadow-soft-sm">
                    <i class="{{ $vehicle->vehicle_category === 'mobil' ? 'ph-bold ph-car' : 'ph-bold ph-motorcycle' }} text-base"></i>
                    Filter: {{ ucfirst($vehicle->vehicle_category) }} ({{ ucfirst($vehicle->type) }})
                </span>
            @endif
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @forelse($recommendations as $rec)
                <a href="{{ route('user.recommendations.show', $rec->id) }}" class="card-interactive bg-gradient-to-b from-white to-slate-50/50 overflow-hidden rounded-3xl border border-slate-200/80 group flex flex-col transition-all hover:shadow-soft-md hover:border-blue-500/40 shadow-soft-sm">
                    @if($rec->image)
                        <img src="{{ asset($rec->image) }}" class="w-full h-48 object-cover border-b border-slate-100 group-hover:scale-105 transition-transform duration-300" alt="{{ $rec->title }}">
                    @else
                        <div class="w-full h-48 bg-slate-50 flex items-center justify-center border-b border-slate-100">
                            <i class="ph ph-article text-5xl text-slate-300"></i>
                        </div>
                    @endif
                    
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="bg-blue-50 text-blue-700 border border-blue-200/80 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">
                                    {{ $rec->motor_type === 'all' ? 'Semua Kendaraan' : ucfirst($rec->motor_type) }}
                                </span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">
                                    {{ $rec->fuel_system === 'all' ? 'Semua Mesin' : ucfirst($rec->fuel_system) }}
                                </span>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors leading-snug">{{ $rec->title }}</h3>
                            <p class="text-slate-500 text-xs leading-relaxed mb-6 line-clamp-3">
                                {{ $rec->content }}
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-1.5 text-blue-600 font-bold text-xs pt-4 border-t border-slate-100 group-hover:translate-x-1 transition-transform">
                            <span>Baca Selengkapnya</span> <i class="ph ph-arrow-right"></i>
                        </div>
                    </div>
                </a>
            @empty
                <div class="md:col-span-3 bg-white p-12 rounded-3xl border border-slate-200/80 text-center shadow-soft-sm">
                    <i class="ph ph-article-ny-times text-6xl text-slate-300 mb-4 block"></i>
                    <h3 class="text-xl font-bold text-slate-900">Belum ada artikel tersedia.</h3>
                    <p class="text-slate-500 text-sm mt-1">Pantau terus halaman ini untuk tips & artikel teknis terbaru!</p>
                </div>
            @endforelse
        </div>
    </main>
</div>
@endsection
