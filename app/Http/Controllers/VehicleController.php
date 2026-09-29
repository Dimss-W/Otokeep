<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\Recommendation;

class VehicleController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Jika user adalah Admin, arahkan langsung ke Dashboard Admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $vehicle = $user->active_vehicle ?? $user->vehicle;

        if (!$vehicle) {
            return redirect()->route('vehicle.register');
        }

        $vehicles = $user->vehicles;
        $services = Service::where('vehicle_id', $vehicle->id)->with('category')->get();
        
        return view('user.dashboard', compact('vehicle', 'vehicles', 'services'));
    }

    public function switchVehicle($id)
    {
        $vehicle = auth()->user()->vehicles()->findOrFail($id);
        session(['active_vehicle_id' => $vehicle->id]);
        return back()->with('success', "Beralih ke kendaraan: {$vehicle->motor_name}");
    }

    public function recommendations()
    {
        $vehicle = auth()->user()->active_vehicle ?? auth()->user()->vehicle;
        if (!$vehicle) {
            return redirect()->route('vehicle.register');
        }

        $recommendations = Recommendation::where(function($q) use ($vehicle) {
            $q->where('vehicle_category', $vehicle->vehicle_category)
              ->orWhere('vehicle_category', 'all');
        })->where(function($q) use ($vehicle) {
            $q->where('motor_type', $vehicle->type)
              ->orWhere('motor_type', 'all');
        })->where(function($q) use ($vehicle) {
            $q->where('fuel_system', $vehicle->fuel_system)
              ->orWhere('fuel_system', 'all');
        })->get();

        return view('user.recommendations.index', compact('recommendations', 'vehicle'));
    }

    public function recommendationDetail($id)
    {
        $recommendation = Recommendation::findOrFail($id);
        return view('user.recommendations.show', compact('recommendation'));
    }

    public function history()
    {
        $user = auth()->user();
        $vehicle = $user->active_vehicle ?? $user->vehicle;
        if (!$vehicle) {
            return redirect()->route('vehicle.register');
        }

        $history = \App\Models\ServiceHistory::where('vehicle_id', $vehicle->id)
            ->with('category')
            ->orderBy('service_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalExpense = $history->sum('cost');
        $totalServices = $history->count();
        $avgExpense = $totalServices > 0 ? (int) round($totalExpense / $totalServices) : 0;
        $vehicles = $user->vehicles;

        // Kategori servis untuk opsi dropdown modal
        $categories = ServiceCategory::where(function($q) use ($vehicle) {
            $q->where('vehicle_category', $vehicle->vehicle_category)
              ->orWhere('vehicle_category', 'all');
        })->orderBy('name')->get();

        // ==========================================
        // ANALITIK PENGELUARAN SERVIS (EXPENSE TRACKER)
        // ==========================================
        $currentYear = (int) now()->year;

        // 1. Total pengeluaran tahun berjalan (YTD)
        $yearlyExpense = $history->filter(function($h) use ($currentYear) {
            return $h->service_date && (int) $h->service_date->year === $currentYear;
        })->sum('cost');

        // 2. Distribusi pengeluaran per bulan tahun ini (Jan - Des)
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyExpenses = array_fill(0, 12, 0); // 0-indexed for Jan-Dec
        foreach ($history as $h) {
            if ($h->service_date && (int) $h->service_date->year === $currentYear) {
                $monthIndex = ((int) $h->service_date->month) - 1;
                if ($monthIndex >= 0 && $monthIndex < 12) {
                    $monthlyExpenses[$monthIndex] += (int) $h->cost;
                }
            }
        }

        // 3. Distribusi biaya berdasarkan kategori komponen servis
        $catGrouped = [];
        foreach ($history as $h) {
            $catName = $h->service_name;
            if (!isset($catGrouped[$catName])) {
                $catGrouped[$catName] = [
                    'name' => $catName,
                    'total' => 0,
                    'count' => 0,
                ];
            }
            $catGrouped[$catName]['total'] += (int) $h->cost;
            $catGrouped[$catName]['count']++;
        }

        // Urutkan kategori dari total biaya terbesar
        uasort($catGrouped, fn($a, $b) => $b['total'] <=> $a['total']);
        $topCategories = array_slice(array_values($catGrouped), 0, 5);

        // Highlight Rekor Servis
        $highestExpense = $history->sortByDesc('cost')->first();

        // Komponen paling sering dirawat
        $sortedByCount = $catGrouped;
        uasort($sortedByCount, fn($a, $b) => $b['count'] <=> $a['count']);
        $mostFrequentItem = !empty($sortedByCount) ? array_values($sortedByCount)[0] : null;

        $analytics = [
            'currentYear' => $currentYear,
            'yearlyExpense' => $yearlyExpense,
            'monthlyLabels' => $monthNames,
            'monthlyValues' => $monthlyExpenses,
            'categoryLabels' => array_column($topCategories, 'name'),
            'categoryValues' => array_column($topCategories, 'total'),
            'topCategories' => $topCategories,
            'highestExpense' => $highestExpense,
            'mostFrequentItem' => $mostFrequentItem,
        ];

        return view('user.history', compact(
            'history', 'vehicle', 'vehicles', 'categories',
            'totalExpense', 'totalServices', 'avgExpense', 'analytics'
        ));
    }

    public function exportServiceBookPdf()
    {
        $user = auth()->user();
        $vehicle = $user->active_vehicle ?? $user->vehicle;
        if (!$vehicle) {
            return redirect()->route('vehicle.register');
        }

        $history = \App\Models\ServiceHistory::where('vehicle_id', $vehicle->id)
            ->with('category')
            ->orderBy('service_date', 'asc') // Urutan kronologis untuk buku log resmi
            ->get();

        $totalExpense = $history->sum('cost');
        $totalServices = $history->count();
        $firstService = $history->first();
        $lastService = $history->last();

        // Nomor seri unik dokumen buku servis digital
        $docId = 'OTK-LOG-' . str_pad($vehicle->id, 4, '0', STR_PAD_LEFT) . '-' . now()->format('Ymd');

        return view('user.service_book_pdf', compact(
            'vehicle', 'history', 'totalExpense', 'totalServices',
            'firstService', 'lastService', 'docId', 'user'
        ));
    }

    public function recordHistory(Request $request)
    {
        $user = auth()->user();
        $vehicle = $user->active_vehicle ?? $user->vehicle;
        if (!$vehicle) {
            return redirect()->route('vehicle.register');
        }

        if ($request->filled('vehicle_id')) {
            $vehicle = $user->vehicles()->findOrFail($request->vehicle_id);
        }

        $request->validate([
            'service_type' => 'required|in:category,custom',
            'category_id' => 'required_if:service_type,category|nullable|exists:service_categories,id',
            'custom_service_name' => 'required_if:service_type,custom|nullable|string|max:255',
            'service_date' => 'required|date',
            'service_km' => 'required|integer|min:0',
            'cost' => 'required|numeric|min:0',
            'workshop_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ], [
            'custom_service_name.required_if' => 'Mohon tuliskan nama servis / pekerjaan bengkel.',
            'category_id.required_if' => 'Silakan pilih kategori komponen servis.',
            'cost.required' => 'Mohon masukkan total biaya servis (isi 0 jika gratis / garansi).',
        ]);

        $cost = (int) $request->cost;
        $categoryId = $request->service_type === 'category' ? $request->category_id : null;
        $customName = $request->service_type === 'custom' ? trim($request->custom_service_name) : null;

        // 1. Simpan riwayat servis
        $history = \App\Models\ServiceHistory::create([
            'vehicle_id' => $vehicle->id,
            'category_id' => $categoryId,
            'custom_service_name' => $customName,
            'service_date' => $request->service_date,
            'service_km' => $request->service_km,
            'cost' => $cost,
            'workshop_name' => $request->workshop_name,
            'notes' => $request->notes,
        ]);

        // 2. Jika ODO yang diinput lebih tinggi dari ODO saat ini, update odometer kendaraan
        if ($request->service_km > $vehicle->current_km) {
            $vehicle->update(['current_km' => $request->service_km]);
        }

        // 3. Jika servis kategori sistem dan sinkronisasi diaktifkan, update jadwal komponen
        if ($categoryId && $request->boolean('sync_component_interval', true)) {
            $category = ServiceCategory::find($categoryId);
            $service = Service::where('vehicle_id', $vehicle->id)
                ->where('category_id', $categoryId)
                ->first();

            if ($service && $category) {
                $nextInterval = $category->default_interval_km ?? 2500;
                $defaultMonths = $category->default_interval_months ?? 3;

                $service->update([
                    'last_service_km' => $request->service_km,
                    'target_km' => $request->service_km + $nextInterval,
                    'target_date' => now()->addMonths($defaultMonths)->toDateString(),
                    'last_notified_at' => null,
                ]);
            }
        }

        $displayName = $customName ?: ($history->category->name ?? 'Servis');
        return back()->with('success', "Riwayat servis \"{$displayName}\" berhasil dicatat ke buku servis!");
    }

    public function deleteHistory($id)
    {
        $user = auth()->user();
        $history = \App\Models\ServiceHistory::whereHas('vehicle', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($id);

        $name = $history->service_name;
        $history->delete();

        return back()->with('success', "Catatan riwayat servis \"{$name}\" berhasil dihapus.");
    }

    public function registerView()
    {
        $user = auth()->user();
        $hasVehicles = $user->vehicles()->count() > 0;
        return view('user.register_vehicle', compact('hasVehicles'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'vehicle_category' => 'required|in:motor,mobil',
            'motor_name' => 'required|string|max:255',
            'type' => 'required|in:matic,manual',
            'fuel_system' => 'required|in:injeksi,karbu,hybrid,listrik',
            'current_km' => 'required|integer|min:0',
            'stnk_tax_due_date' => 'nullable|date',
            'five_year_tax_due_date' => 'nullable|date',
        ]);

        $vehicle = Vehicle::create([
            'user_id' => auth()->id(),
            'vehicle_category' => $request->vehicle_category,
            'motor_name' => $request->motor_name,
            'type' => $request->type,
            'fuel_system' => $request->fuel_system,
            'current_km' => $request->current_km,
            'stnk_tax_due_date' => $request->stnk_tax_due_date,
            'five_year_tax_due_date' => $request->five_year_tax_due_date,
        ]);

        session(['active_vehicle_id' => $vehicle->id]);

        // Auto-generate Template Paket Servis Standar Pabrikan
        $this->seedDefaultServicesForVehicle($vehicle);

        return redirect()->route('dashboard')->with('success', "Kendaraan {$vehicle->motor_name} berhasil didaftarkan dan paket servis otomatis telah aktif!");
    }

    protected function seedDefaultServicesForVehicle(Vehicle $vehicle)
    {
        $presets = [];

        if ($vehicle->vehicle_category === 'motor') {
            $presets = [
                ['name' => 'Oli Mesin', 'interval_km' => 2000, 'months' => 2],
                ['name' => 'Kampas Rem', 'interval_km' => 8000, 'months' => 6],
                ['name' => 'Filter Udara', 'interval_km' => 10000, 'months' => 8],
                ['name' => 'Air Radiator', 'interval_km' => 12000, 'months' => 10],
            ];
            if ($vehicle->type === 'matic') {
                $presets[] = ['name' => 'Oli Gardan (CVT)', 'interval_km' => 8000, 'months' => 6];
                $presets[] = ['name' => 'V-Belt & Roller CVT', 'interval_km' => 20000, 'months' => 18];
            } else {
                $presets[] = ['name' => 'Rantai & Gear Set', 'interval_km' => 15000, 'months' => 12];
                $presets[] = ['name' => 'Kopling Manual', 'interval_km' => 20000, 'months' => 18];
            }
        } else {
            // Mobil
            $presets = [
                ['name' => 'Oli Mesin & Filter', 'interval_km' => 5000, 'months' => 6],
                ['name' => 'Filter AC Kabin', 'interval_km' => 10000, 'months' => 6],
                ['name' => 'Kampas Rem', 'interval_km' => 20000, 'months' => 12],
                ['name' => 'Oli Transmisi', 'interval_km' => 20000, 'months' => 12],
                ['name' => 'Radiator Coolant', 'interval_km' => 40000, 'months' => 24],
            ];
        }

        foreach ($presets as $preset) {
            $cat = ServiceCategory::firstOrCreate(
                ['name' => $preset['name']],
                [
                    'vehicle_category' => $vehicle->vehicle_category,
                    'default_interval_km' => $preset['interval_km'],
                    'default_interval_months' => $preset['months'],
                ]
            );

            Service::create([
                'vehicle_id' => $vehicle->id,
                'category_id' => $cat->id,
                'last_service_km' => $vehicle->current_km,
                'target_km' => $vehicle->current_km + $preset['interval_km'],
                'target_date' => now()->addMonths($preset['months'])->toDateString(),
            ]);
        }
    }

    public function updateOdometer(Request $request)
    {
        $request->validate([
            'current_km' => 'required|integer|min:0',
        ]);

        $user = auth()->user();
        $vehicle = $user->active_vehicle ?? $user->vehicle;

        // Validasi Pencegah Typo Penurunan Kilometer
        if ($request->current_km < $vehicle->current_km && !$request->boolean('confirm_lower_km')) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'warning_decrease',
                    'message' => "Kilometer yang Anda masukkan (" . number_format($request->current_km, 0, ',', '.') . " KM) lebih kecil dari odometer sebelumnya (" . number_format($vehicle->current_km, 0, ',', '.') . " KM).",
                    'old_km' => $vehicle->current_km,
                    'new_km' => $request->current_km
                ], 422);
            }
            return back()->with('warning_decrease', [
                'old_km' => $vehicle->current_km,
                'new_km' => $request->current_km
            ])->withInput();
        }

        $vehicle->update(['current_km' => $request->current_km]);

        // General Push Notification via OneSignal
        \App\Services\OneSignalService::sendNotification($user->id, "Odometer berhasil diperbarui ke " . number_format($request->current_km, 0, ',', '.') . " KM. Mantap bro!");

        $msg = "Odometer berhasil diperbarui ke " . number_format($request->current_km, 0, ',', '.') . " KM!";
        
        $services = Service::where('vehicle_id', $vehicle->id)->with('category')->get();

        foreach ($services as $service) {
            $remainingKm = $service->target_km - $vehicle->current_km;
            
            if ($remainingKm <= 200 && $remainingKm > 0) {
                $msg = "Waktunya Manjain Kendaraan lo! " . $service->category->name . " sisa " . $remainingKm . " KM lagi.";
                \App\Services\OneSignalService::sendNotification($user->id, $msg);
                break;
            } 
            elseif ($service->target_date) {
                $daysRemaining = now()->diffInDays($service->target_date, false);
                if ($daysRemaining <= 3 && $daysRemaining >= 0) {
                    $msg = "H-" . $daysRemaining . " Waktunya servis " . $service->category->name . "!";
                    \App\Services\OneSignalService::sendNotification($user->id, $msg);
                    break;
                }
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $msg,
                'current_km' => $vehicle->current_km,
            ]);
        }

        return back()->with('success', $msg);
    }

    public function scanOdometerAi(Request $request)
    {
        $request->validate([
            'speedometer_image' => 'required|image|max:10240',
        ]);

        $vehicle = auth()->user()->active_vehicle ?? auth()->user()->vehicle;
        if (!$vehicle) {
            return response()->json(['status' => 'error', 'message' => 'Kendaraan aktif tidak ditemukan.'], 404);
        }

        $imageFile = $request->file('speedometer_image');
        $imageData = base64_encode(file_get_contents($imageFile->getRealPath()));
        $mimeType = $imageFile->getMimeType();

        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Layanan AI Vision belum terkonfigurasi. Pastikan GEMINI_API_KEY aktif di file .env Anda.',
            ], 500);
        }

        try {
            $prompt = "Kamu adalah sistem OCR AI presisi tinggi untuk membaca speedometer motor dan mobil. Tugasmu adalah menganalisis foto speedometer/odometer ini dan mengekstrak total angka kilometer odometer utama (ODO).
Petunjuk:
1. Bedakan antara Trip Meter (TRIP A/B) dan Odometer Total (ODO). Ambil angka ODOMETER UTAMA.
2. Angka odometer bisa berupa layar digital LCD ataupun meteran roda putar analog (roller).
3. Hanya kembalikan JSON murni tanpa format markdown code block (jangan gunakan ```json) dengan struktur:
{\"odometer\": 12500, \"confidence\": \"high\", \"notes\": \"Terbaca jelas di panel speedometer\"}
Jika angka sama sekali tidak terbaca atau foto buram, kembalikan:
{\"odometer\": null, \"confidence\": \"none\", \"notes\": \"Angka speedometer kurang jelas atau buram. Mohon ambil foto lebih dekat dan fokus.\"}";

            $httpClient = app()->isLocal() ? \Illuminate\Support\Facades\Http::withoutVerifying() : \Illuminate\Support\Facades\Http::timeout(30);
            $configuredModel = config('services.gemini.model', 'gemini-2.5-flash');
            $candidateModels = array_values(array_unique([
                $configuredModel,
                'gemini-2.5-flash',
                'gemini-3.1-flash-lite',
                'gemini-3.5-flash',
                'gemini-3.5-flash-lite',
            ]));

            $response = null;
            $lastErrMessage = '';
            $isKeyInvalid = false;

            foreach ($candidateModels as $model) {
                try {
                    $resp = $httpClient->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt],
                                    [
                                        'inlineData' => [
                                            'mimeType' => $mimeType,
                                            'data' => $imageData,
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.1,
                            'responseMimeType' => 'application/json',
                        ]
                    ]);

                    if ($resp->successful()) {
                        $response = $resp;
                        break;
                    }

                    $errBody = $resp->json();
                    $lastErrMessage = $errBody['error']['message'] ?? ('Status: ' . $resp->status());
                    \Illuminate\Support\Facades\Log::warning("Gemini OCR {$model} failed: {$lastErrMessage}. Mengalihkan ke model cadangan...");

                    if (str_contains(strtolower($lastErrMessage), 'api key not valid') || str_contains(strtolower($lastErrMessage), 'api_key_invalid')) {
                        $isKeyInvalid = true;
                        break; // Stop immediately if key itself is invalid
                    }
                } catch (\Exception $subEx) {
                    $lastErrMessage = $subEx->getMessage();
                    \Illuminate\Support\Facades\Log::warning("Gemini OCR {$model} exception: {$lastErrMessage}");
                }
            }

            if (!$response || !$response->successful()) {
                return response()->json([
                    'status' => 'api_error',
                    'message' => $isKeyInvalid 
                        ? 'Kunci GEMINI_API_KEY di file .env tidak valid atau sudah kedaluwarsa. Silakan periksa Google AI Studio Anda.' 
                        : 'Layanan AI Vision mengalami kendala: ' . $lastErrMessage,
                    'is_key_invalid' => $isKeyInvalid,
                ], 400);
            }

            $resultJson = $response->json();
            $rawText = $resultJson['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $cleaned = trim(str_replace(['```json', '```'], '', $rawText));
            $parsed = json_decode($cleaned, true) ?? [];

            $detectedKm = isset($parsed['odometer']) ? (int) $parsed['odometer'] : null;

            if ($detectedKm !== null && $detectedKm >= 0) {
                return response()->json([
                    'status' => 'success',
                    'detected_km' => $detectedKm,
                    'previous_km' => $vehicle->current_km,
                    'vehicle_name' => $vehicle->motor_name,
                    'confidence' => $parsed['confidence'] ?? 'medium',
                    'notes' => $parsed['notes'] ?? 'Angka odometer berhasil dikenali secara otomatis oleh AI.',
                ]);
            } else {
                return response()->json([
                    'status' => 'warning',
                    'message' => $parsed['notes'] ?? 'AI tidak dapat membaca angka kilometer secara jelas. Pastikan foto speedometer cukup terang dan fokus.',
                ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kendala pemrosesan AI: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateTaxDates(Request $request)
    {
        $request->validate([
            'stnk_tax_due_date' => 'nullable|date',
            'five_year_tax_due_date' => 'nullable|date',
        ]);

        $vehicle = auth()->user()->active_vehicle ?? auth()->user()->vehicle;
        $vehicle->update([
            'stnk_tax_due_date' => $request->stnk_tax_due_date,
            'five_year_tax_due_date' => $request->five_year_tax_due_date,
        ]);

        return back()->with('success', 'Jadwal jatuh tempo pajak kendaraan berhasil diperbarui!');
    }

    public function updateNotificationTime(Request $request)
    {
        $request->validate([
            'notification_time' => 'required|string',
        ]);

        $user = auth()->user();
        $user->update([
            'notification_time' => $request->notification_time
        ]);

        // Reset cache pengingat hari ini agar jam yang baru dipilih bisa langsung aktif
        $cacheKey = "notif_sent_{$user->id}_" . now()->toDateString() . "_" . str_replace(':', '', $request->notification_time);
        cache()->forget($cacheKey);

        return back()->with('success', 'Jadwal notifikasi berhasil diatur ke jam ' . $request->notification_time . '!');
    }

    public function syncRealtime(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $vehicle = $user->active_vehicle ?? $user->vehicle;
        if (!$vehicle) {
            return response()->json(['status' => 'no_vehicle']);
        }

        $services = Service::where('vehicle_id', $vehicle->id)->with('category')->get();

        $urgentServices = [];
        $overdueServices = [];
        $nearestService = null;
        $minRemaining = PHP_INT_MAX;
        $servicesData = [];

        foreach ($services as $s) {
            $rem = $s->target_km - $vehicle->current_km;
            if ($rem < $minRemaining) {
                $minRemaining = $rem;
                $nearestService = $s;
            }

            if ($rem <= 0) {
                $overdueServices[] = $s->category->name;
            } elseif ($rem <= 500) {
                $urgentServices[] = $s->category->name . " (sisa " . number_format($rem, 0, ',', '.') . " KM)";
            }

            $servicesData[] = [
                'id' => $s->id,
                'name' => $s->category->name,
                'last_km' => $s->last_service_km,
                'target_km' => $s->target_km,
                'remaining' => $rem,
                'status' => $rem <= 0 ? 'TERLAMBAT' : ($rem <= 200 ? 'SEGERA SERVIS' : 'AMAN')
            ];
        }

        $clientTime = $request->get('client_time') ?? now()->format('H:i');
        $notifTime = $user->notification_time ?? '08:00';
        $forceCheck = $request->boolean('force_check');

        // Cache key per hari per jam agar tidak berulang-ulang
        $cacheKey = "notif_sent_{$user->id}_" . now()->toDateString() . "_" . str_replace(':', '', $notifTime);
        $alreadySentToday = cache()->has($cacheKey);

        $shouldNotify = false;
        $notifTitle = "";
        $notifBody = "";

        // Evaluasi apakah saatnya memunculkan notifikasi
        if (($clientTime === $notifTime && !$alreadySentToday) || $forceCheck) {
            $shouldNotify = true;
            if (!$forceCheck) {
                cache()->put($cacheKey, true, now()->endOfDay());
            }

            $vehCat = ($vehicle->vehicle_category === 'mobil') ? 'Mobil' : 'Motor';

            if (!empty($overdueServices)) {
                $notifTitle = "⚠️ Peringatan Servis {$vehicle->motor_name}!";
                $notifBody = "Komponen " . implode(', ', array_slice($overdueServices, 0, 2)) . " sudah melewati batas target! Segera jadwalkan servis.";
            } elseif (!empty($urgentServices)) {
                $notifTitle = "🔔 Jadwal Servis {$vehicle->motor_name}!";
                $notifBody = "Mendekati target servis: " . implode(', ', array_slice($urgentServices, 0, 2)) . ". Yuk rawat kendaraanmu.";
            } else {
                $notifTitle = "Laporan Berkala {$vehicle->motor_name} 🚗";
                $nearestText = $nearestService 
                    ? "Servis berikutnya: {$nearestService->category->name} (sisa " . number_format($minRemaining, 0, ',', '.') . " KM lagi)." 
                    : "Semua komponen terpantau aman.";
                $notifBody = "Odometer tercatat di " . number_format($vehicle->current_km, 0, ',', '.') . " KM dalam kondisi prima! {$nearestText}";
            }
        }

        return response()->json([
            'status' => 'success',
            'current_km' => $vehicle->current_km,
            'current_km_formatted' => number_format($vehicle->current_km, 0, ',', '.') . ' KM',
            'vehicle_name' => $vehicle->motor_name,
            'notification_time' => $notifTime,
            'client_time' => $clientTime,
            'should_notify' => $shouldNotify,
            'notification' => $shouldNotify ? [
                'title' => $notifTitle,
                'body' => $notifBody,
                'icon' => asset('favicon.png')
            ] : null,
            'services' => $servicesData
        ]);
    }

    public function updateOdometerIoT(Request $request)
    {
        $request->validate([
            'iot_token' => 'required|string',
            'current_km' => 'required|integer|min:0',
        ]);

        $vehicle = Vehicle::where('iot_token', $request->iot_token)->first();

        if (!$vehicle) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token IoT tidak valid. Silakan periksa konfigurasi perangkat Anda.'
            ], 404);
        }

        $vehicle->update(['current_km' => $request->current_km]);
        $user = $vehicle->user;

        $vehicleTypeStr = ($vehicle->vehicle_category === 'mobil') ? 'Mobil' : 'Motor';
        \App\Services\OneSignalService::sendNotification($user->id, "OBD-II Sync: Odometer " . $vehicleTypeStr . " " . $vehicle->motor_name . " diperbarui otomatis ke " . number_format($request->current_km, 0, ',', '.') . " KM.");

        $triggeredAlert = false;
        $alertMessage = "";

        $services = Service::where('vehicle_id', $vehicle->id)->with('category')->get();
        $updatedServices = [];

        foreach ($services as $service) {
            $remainingKm = $service->target_km - $vehicle->current_km;
            $statusText = 'AMAN';
            $bgColor = 'bg-green-500/10';
            $textColor = 'text-green-500';
            $borderColor = 'border-green-500';

            if ($remainingKm <= 0) {
                $statusText = 'TERLAMBAT';
                $bgColor = 'bg-red-500/10';
                $textColor = 'text-red-500';
                $borderColor = 'border-red-500';
            } elseif ($remainingKm < 200) {
                $statusText = 'SEGERA SERVIS';
                $bgColor = 'bg-orange/10';
                $textColor = 'text-orange';
                $borderColor = 'border-orange';
            }

            $updatedServices[] = [
                'id' => $service->id,
                'category_name' => $service->category->name,
                'last_service_km' => $service->last_service_km,
                'target_km' => $service->target_km,
                'remaining' => $remainingKm,
                'status_text' => $statusText,
                'bg_color' => $bgColor,
                'text_color' => $textColor,
                'border_color' => $borderColor,
            ];

            if ($remainingKm <= 200 && $remainingKm > 0 && !$triggeredAlert) {
                $triggeredAlert = true;
                $alertMessage = "Waktunya Manjain " . $vehicleTypeStr . " lo! Servis " . $service->category->name . " sisa " . $remainingKm . " KM lagi.";
                \App\Services\OneSignalService::sendNotification($user->id, $alertMessage);
            } elseif ($service->target_date && !$triggeredAlert) {
                $daysRemaining = now()->diffInDays($service->target_date, false);
                if ($daysRemaining <= 3 && $daysRemaining >= 0) {
                    $triggeredAlert = true;
                    $alertMessage = "H-" . $daysRemaining . " Waktunya servis " . $service->category->name . " untuk " . $vehicleTypeStr . " Anda!";
                    \App\Services\OneSignalService::sendNotification($user->id, $alertMessage);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Odometer berhasil disinkronisasi lewat IoT OBD-II!',
            'vehicle_name' => $vehicle->motor_name,
            'current_km' => $vehicle->current_km,
            'vehicle_category' => $vehicle->vehicle_category,
            'triggered_alert' => $triggeredAlert,
            'alert_message' => $alertMessage,
            'services' => $updatedServices,
        ]);
    }
}
