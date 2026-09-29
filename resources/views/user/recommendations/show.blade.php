@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-transparent text-slate-800 flex flex-col lg:flex-row antialiased">
    @include('layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-6 md:p-8 lg:p-10 overflow-y-auto">
        <div class="max-w-4xl mx-auto">
            <a href="{{ route('user.recommendations') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-900 mb-6 transition-colors text-sm font-semibold">
                <i class="ph ph-arrow-left"></i> Kembali ke Daftar Tips
            </a>

            <article class="card-interactive bg-gradient-to-b from-white to-slate-50/40 rounded-[2rem] border border-slate-200/80 overflow-hidden shadow-soft-sm">
                @if($recommendation->image)
                    <img src="{{ asset($recommendation->image) }}" class="w-full h-[360px] md:h-[420px] object-cover" alt="{{ $recommendation->title }}">
                @endif

                <div class="p-8 lg:p-12">
                    <div class="flex flex-wrap gap-2.5 mb-6">
                        <span class="bg-blue-50 text-blue-700 border border-blue-200/80 text-xs px-3 py-1.5 rounded-full font-bold uppercase tracking-wider">
                            {{ $recommendation->motor_type === 'all' ? 'Semua Kendaraan' : ucfirst($recommendation->motor_type) }}
                        </span>
                        <span class="bg-slate-100 text-slate-700 text-xs px-3 py-1.5 rounded-full font-bold uppercase tracking-wider">
                            {{ $recommendation->fuel_system === 'all' ? 'Semua Mesin' : ucfirst($recommendation->fuel_system) }}
                        </span>
                    </div>

                    <h1 class="text-2xl md:text-4xl font-black text-slate-900 mb-6 leading-tight tracking-tight">
                        {{ $recommendation->title }}
                    </h1>

                    <div class="prose max-w-none text-slate-700 leading-relaxed text-sm md:text-base whitespace-pre-line space-y-4 font-normal">
                        {{ $recommendation->content }}
                    </div>

                    <div class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 bg-blue-50 text-blue-600 border border-blue-200/80 rounded-full flex items-center justify-center font-bold">
                                <i class="ph-bold ph-shield-check text-xl"></i>
                            </div>
                            <div>
                                <span class="block text-slate-900 font-bold text-sm">Tim Ahli Mekanik OtoKeep</span>
                                <span class="block text-slate-400 text-xs">Dipublikasikan pada {{ $recommendation->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </main>
</div>
@endsection
