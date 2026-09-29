<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $vehicle = $user->active_vehicle ?? $user->vehicle;
        $messages = $user->chatMessages()->get();

        return view('user.chat', compact('vehicle', 'messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $apiKey = config('services.gemini.api_key');
        $userMessage = $request->message;
        $user = auth()->user();
        $vehicle = $user->active_vehicle ?? $user->vehicle;

        // Buat konteks cerdas dari spesifikasi kendaraan user
        $vehicleContext = "PENGGUNA BELUM MENDAFTARKAN KENDARAAN.";
        if ($vehicle) {
            $catLabel = $vehicle->vehicle_category === 'mobil' ? 'Mobil' : 'Motor';
            $vehicleContext = "DATA KENDARAAN AKTIF PENGGUNA:
- Jenis Kendaraan: {$catLabel}
- Model/Nama: {$vehicle->motor_name}
- Tipe Transmisi: {$vehicle->type}
- Sistem Bahan Bakar: {$vehicle->fuel_system}
- Odometer Saat Ini: " . number_format($vehicle->current_km, 0, ',', '.') . " KM.
Gunakan data spesifik kendaraan di atas saat mendiagnosa, memberikan anjuran takaran/spesifikasi oli, jadwal servis, dan solusi teknis.";
        }

        $systemInstruction = "Kamu adalah Bang OTO, asisten mekanik digital handal dari aplikasi OtoKeep. Kamu sangat ahli dalam mekanikal motor dan mobil, trouble diagnosis, tips perawatan berkala, dan perkiraan biaya servis di Indonesia. Jawablah dengan gaya bahasa yang santai, akrab, ramah, dan solutif layaknya mekanik bengkel senior yang jujur. Slogan OtoKeep adalah 'Gak Ada Lagi Drama Lupa Servis'. Selalu ingatkan pengguna untuk rutin mengecek odometer mereka.\n\n" . $vehicleContext;

        try {
            $httpClient = app()->isLocal() ? Http::withoutVerifying() : Http::timeout(25);
            
            // Ambil history obrolan sebelumnya untuk conversational context
            $recentHistory = ChatMessage::where('user_id', $user->id)
                ->latest()
                ->take(8)
                ->get()
                ->reverse();

            // Bangun percakapan multiturn dengan role yang bergantian secara ketat (user -> model -> user)
            $contents = [];
            $lastRole = null;
            foreach ($recentHistory as $msg) {
                $role = $msg->role === 'model' ? 'model' : 'user';
                // Hindari duplikasi role berurutan agar Gemini API tidak menolak multiturn
                if ($role === $lastRole) {
                    continue;
                }
                // Percakapan harus diawali dengan user
                if (empty($contents) && $role !== 'user') {
                    continue;
                }
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $msg->message]]
                ];
                $lastRole = $role;
            }

            // Jika item terakhir sebelum pesan baru adalah user, buang agar pesan baru user menjadi penutupnya
            if (!empty($contents) && end($contents)['role'] === 'user') {
                array_pop($contents);
            }

            // Sisipkan pesan user saat ini
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $userMessage]]
            ];

            // Daftar model terverifikasi aktif & stabil (gratis & cepat)
            $candidateModels = array_values(array_unique([
                config('services.gemini.model', 'gemini-2.5-flash'),
                'gemini-2.5-flash',
                'gemini-3.1-flash-lite',
                'gemini-3.5-flash',
                'gemini-3.5-flash-lite',
            ]));

            $reply = null;
            $lastError = 'Tidak ada respon dari server AI';

            foreach ($candidateModels as $model) {
                try {
                    $response = $httpClient->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                        'system_instruction' => [
                            'parts' => [['text' => $systemInstruction]]
                        ],
                        'contents' => $contents
                    ]);

                    $result = $response->json();

                    if ($response->successful()) {
                        $reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? "Waduh, Bang OTO lagi agak bingung nih. Coba tanya lagi ya bro!";
                        break; // Berhasil, keluar dari loop
                    }

                    $lastError = $result['error']['message'] ?? ('HTTP ' . $response->status());
                    \Log::warning("Gemini Chat model {$model} error ({$response->status()}): {$lastError}. Mengalihkan ke model cadangan...");
                } catch (\Exception $subEx) {
                    $lastError = $subEx->getMessage();
                    \Log::warning("Gemini Chat model {$model} exception: {$lastError}. Mengalihkan ke model cadangan...");
                }
            }

            if ($reply !== null) {
                // Simpan pesan user dan balasan AI ke database secara konsisten
                ChatMessage::create([
                    'user_id' => $user->id,
                    'role' => 'user',
                    'message' => $userMessage,
                ]);

                ChatMessage::create([
                    'user_id' => $user->id,
                    'role' => 'model',
                    'message' => $reply,
                ]);

                return response()->json(['reply' => $reply]);
            }

            $fallbackReply = "Waduh bro, Bang OTO lagi ada gangguan koneksi ke bengkel pusat ({$lastError}). Coba ulangi sebentar lagi ya!";
            return response()->json(['reply' => $fallbackReply], 500);

        } catch (\Exception $e) {
            \Log::error('Gemini Exception: ' . $e->getMessage());
            return response()->json(['reply' => "Aduh sorry bro, bengkel pusat lagi sibuk: " . $e->getMessage()], 500);
        }
    }

    public function clearHistory()
    {
        ChatMessage::where('user_id', auth()->id())->delete();
        return back()->with('success', 'Riwayat obrolan dengan Bang OTO berhasil dibersihkan.');
    }
}
