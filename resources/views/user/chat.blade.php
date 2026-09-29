@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-transparent flex flex-col lg:flex-row antialiased text-slate-800">
    @include('layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 flex flex-col max-h-screen min-w-0">
        <header class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1 flex items-center gap-2.5">
                    <span>Tanya Bang OTO</span>
                    <span class="text-[11px] bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 border border-blue-200/80 px-2.5 py-0.5 rounded-full font-bold">AI MEKANIK</span>
                </h1>
                <p class="text-slate-500 text-xs">Konsultasi kerusakan, tips servis, diagnosa suara mesin, dan spesifikasi oli.</p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('user.chat.clear') }}" method="POST" onsubmit="return confirm('Hapus seluruh riwayat obrolan dengan Bang OTO?')">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 bg-white hover:bg-rose-50 text-slate-600 hover:text-rose-600 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5 border border-slate-200 shadow-soft-sm" title="Hapus Riwayat">
                        <i class="ph-bold ph-trash text-sm"></i> Bersihkan Obrolan
                    </button>
                </form>
                <div class="flex items-center gap-2 bg-white px-3.5 py-2 rounded-xl border border-slate-200/80 shadow-soft-sm">
                    <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-slate-700 text-xs font-semibold">Bang OTO Siap</span>
                </div>
            </div>
        </header>

        <!-- Vehicle Context Banner -->
        @if($vehicle)
        <div class="mb-4 p-3.5 bg-gradient-to-r from-white via-white to-blue-50/40 rounded-2xl border border-slate-200/80 shadow-soft-sm flex items-center justify-between gap-4 text-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-base shadow-sm shadow-blue-500/20">
                    <i class="{{ $vehicle->vehicle_category === 'mobil' ? 'ph-bold ph-car' : 'ph-bold ph-motorcycle' }}"></i>
                </div>
                <div>
                    <span class="text-slate-500">Kendaraan Aktif:</span>
                    <strong class="text-slate-900 ml-1 font-bold">{{ $vehicle->motor_name }}</strong>
                    <span class="text-slate-500 ml-2 font-mono text-[11px]">({{ number_format($vehicle->current_km, 0, ',', '.') }} KM • {{ ucfirst($vehicle->type) }} • {{ ucfirst($vehicle->fuel_system) }})</span>
                </div>
            </div>
            <span class="text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full font-bold">
                Konteks Terhubung
            </span>
        </div>
        @endif

        <!-- Chat Container -->
        <div class="flex-1 bg-white rounded-3xl overflow-hidden flex flex-col shadow-soft-md border border-slate-200/80 relative min-h-0">
            <!-- Messages Area -->
            <div id="chat-messages" class="flex-1 p-5 md:p-6 overflow-y-auto space-y-4 scrollbar-hide bg-gradient-to-b from-slate-50/60 to-blue-50/15">
                <!-- Welcome Message Default -->
                <div class="flex items-start gap-3.5">
                    <div class="w-9 h-9 bg-emerald-600 rounded-xl flex-shrink-0 flex items-center justify-center text-white shadow-soft-sm">
                        <i class="ph-bold ph-wrench text-base"></i>
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none max-w-[85%] border border-slate-200/80 shadow-soft-sm">
                        <p class="text-slate-800 text-sm leading-relaxed">
                            Halo bro! Kenalin, gue <strong>Bang OTO</strong>. Ada kendala apa di <strong>{{ $vehicle->motor_name ?? 'kendaraan' }}</strong> lo hari ini? Atau mau tanya takaran oli, keluhan bunyi kasar, atau estimasi servis? Tumpahin aja di sini, gue bantu analisa! 🔧
                        </p>
                    </div>
                </div>

                <!-- Render Existing Chat Messages From Database -->
                @if(isset($messages) && $messages->count() > 0)
                    @foreach($messages as $msg)
                        <div class="flex items-start gap-3.5 {{ $msg->role === 'user' ? 'flex-row-reverse' : '' }}">
                            <div class="w-9 h-9 {{ $msg->role === 'user' ? 'bg-blue-600 text-white' : 'bg-emerald-600 text-white' }} rounded-xl flex-shrink-0 flex items-center justify-center shadow-soft-sm">
                                <i class="{{ $msg->role === 'user' ? 'ph-bold ph-user text-base' : 'ph-bold ph-wrench text-base' }}"></i>
                            </div>
                            <div class="{{ $msg->role === 'user' ? 'bg-blue-600 rounded-tr-none text-white' : 'bg-white rounded-tl-none border border-slate-200/80 text-slate-800 shadow-soft-sm' }} p-4 rounded-2xl max-w-[85%] text-sm leading-relaxed">
                                {!! nl2br(e($msg->message)) !!}
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Quick Action Prompt Chips -->
            <div class="px-5 py-2.5 bg-white border-t border-slate-200/80 flex gap-2 overflow-x-auto scrollbar-hide">
                <button type="button" onclick="sendQuickPrompt('Kapan jadwal ideal ganti oli untuk {{ $vehicle->motor_name ?? 'kendaraan saya' }}?')" class="px-3.5 py-1.5 bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 rounded-full text-xs whitespace-nowrap transition-all border border-slate-200 font-medium">
                    💡 Jadwal Ganti Oli
                </button>
                <button type="button" onclick="sendQuickPrompt('Kenapa ada suara gredek/kasar saat awal tarikan gas?')" class="px-3.5 py-1.5 bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 rounded-full text-xs whitespace-nowrap transition-all border border-slate-200 font-medium">
                    ⚙️ Suara Gredek / Kasar
                </button>
                <button type="button" onclick="sendQuickPrompt('Rekomendasi spesifikasi dan kekentalan oli terbaik buat motor ini apa ya?')" class="px-3.5 py-1.5 bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 rounded-full text-xs whitespace-nowrap transition-all border border-slate-200 font-medium">
                    🛢️ Rekomendasi Oli
                </button>
                <button type="button" onclick="sendQuickPrompt('Berapa estimasi biaya servis berkala di bengkel resmi?')" class="px-3.5 py-1.5 bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 rounded-full text-xs whitespace-nowrap transition-all border border-slate-200 font-medium">
                    💰 Estimasi Biaya Servis
                </button>
            </div>

            <!-- Input Area -->
            <div class="p-4 md:p-5 bg-white border-t border-slate-200/80">
                <form id="chat-form" class="flex gap-3">
                    @csrf
                    <input type="text" id="user-input" placeholder="Tulis keluhan atau pertanyaan kendaraan lo di sini..." class="flex-1 bg-slate-50 border border-slate-200/80 rounded-2xl px-5 py-3.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 focus:bg-white transition-all text-sm placeholder:text-slate-400" autocomplete="off">
                    <button type="submit" id="send-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 md:px-8 py-3.5 rounded-2xl font-bold transition-all shadow-md shadow-blue-500/25 flex items-center gap-2 text-sm shrink-0">
                        <span>Kirim</span>
                        <i class="ph-bold ph-paper-plane-tilt text-base"></i>
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
    const chatForm = document.getElementById('chat-form');
    const userInput = document.getElementById('user-input');
    const chatMessages = document.getElementById('chat-messages');
    const sendBtn = document.getElementById('send-btn');

    // Auto-scroll to bottom of messages on page load
    chatMessages.scrollTop = chatMessages.scrollHeight;

    function formatMessageText(text) {
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/\n/g, '<br>');
    }

    function appendMessage(role, text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = role === 'user' ? 'flex items-start gap-3.5 flex-row-reverse' : 'flex items-start gap-3.5';
        
        const icon = role === 'user' ? 
            '<div class="w-9 h-9 bg-blue-600 rounded-xl flex-shrink-0 flex items-center justify-center text-white shadow-soft-sm"><i class="ph-bold ph-user text-base"></i></div>' : 
            '<div class="w-9 h-9 bg-emerald-600 rounded-xl flex-shrink-0 flex items-center justify-center text-white shadow-soft-sm"><i class="ph-bold ph-wrench text-base"></i></div>';
        
        const bubbleClass = role === 'user' ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white text-slate-800 rounded-tl-none border border-slate-200/80 shadow-soft-sm';
        
        messageDiv.innerHTML = `
            ${icon}
            <div class="${bubbleClass} p-4 rounded-2xl max-w-[85%] text-sm leading-relaxed">
                <p>${formatMessageText(text)}</p>
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function sendQuickPrompt(promptText) {
        userInput.value = promptText;
        chatForm.dispatchEvent(new Event('submit'));
    }

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = userInput.value.trim();
        if (!message) return;

        appendMessage('user', message);
        userInput.value = '';
        
        userInput.disabled = true;
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<span>Menganalisa...</span>';

        try {
            const response = await fetch('{{ route("user.chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            
            if (response.ok && data.reply) {
                appendMessage('bot', data.reply);
            } else {
                appendMessage('bot', data.reply || "Waduh sorry bro, koneksi lagi agak padat. Coba tanya lagi ya!");
            }

        } catch (error) {
            appendMessage('bot', "Aduh sorry bro, bengkel pusat lagi agak sibuk. Coba periksa koneksi internet Anda lalu ulangi!");
        } finally {
            userInput.disabled = false;
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<span>Kirim</span> <i class="ph-bold ph-paper-plane-tilt"></i>';
            userInput.focus();
        }
    });
</script>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection
