<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Service;
use App\Models\ServiceHistory;
use App\Models\ChatMessage;
use App\Models\ServiceCategory;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data = $this->getFleetDashboardData();
        return view('admin.dashboard', $data);
    }

    public function realtimeData()
    {
        $data = $this->getFleetDashboardData();
        return response()->json(array_merge([
            'status' => 'success',
            'server_time' => now()->setTimezone('Asia/Jakarta')->format('H:i:s'),
        ], $data));
    }

    private function getFleetDashboardData(): array
    {
        // 1. Live system counts
        $userCount = User::where('role', 'user')->count();
        $motorCount = DB::table('vehicles')->where('vehicle_category', 'motor')->count();
        $mobilCount = DB::table('vehicles')->where('vehicle_category', 'mobil')->count();
        $vehicleCount = $motorCount + $mobilCount;
        $totalServicesActive = Service::count();
        $totalHistoryCount = ServiceHistory::count();
        $totalChatCount = ChatMessage::count();
        $categoriesCount = ServiceCategory::count();
        $recommendationsCount = Recommendation::count();

        // 2. Data Kendaraan: Sebaran Jarak Tempuh Odometer (Fleet Mileage Distribution)
        $buildOdoRanges = function($cat = null) {
            $query = DB::table('vehicles');
            if ($cat) {
                $query->where('vehicle_category', $cat);
            }
            return [
                'low' => (clone $query)->where('current_km', '<', 10000)->count(),
                'medium' => (clone $query)->whereBetween('current_km', [10000, 25000])->count(),
                'active' => (clone $query)->whereBetween('current_km', [25001, 50000])->count(),
                'high' => (clone $query)->where('current_km', '>', 50000)->count(),
                'total' => (clone $query)->count(),
            ];
        };

        $odoRanges = [
            'all' => $buildOdoRanges(),
            'motor' => $buildOdoRanges('motor'),
            'mobil' => $buildOdoRanges('mobil'),
        ];

        // 3. Data Kendaraan: Komposisi Tipe / Transmisi Armada (Matic, Manual, dll.)
        $typeStats = [
            'all' => DB::table('vehicles')
                ->select('type', 'vehicle_category', DB::raw('count(*) as aggregate'))
                ->groupBy('type', 'vehicle_category')
                ->orderBy('aggregate', 'desc')
                ->get(),
            'motor' => DB::table('vehicles')
                ->where('vehicle_category', 'motor')
                ->select('type', 'vehicle_category', DB::raw('count(*) as aggregate'))
                ->groupBy('type', 'vehicle_category')
                ->orderBy('aggregate', 'desc')
                ->get(),
            'mobil' => DB::table('vehicles')
                ->where('vehicle_category', 'mobil')
                ->select('type', 'vehicle_category', DB::raw('count(*) as aggregate'))
                ->groupBy('type', 'vehicle_category')
                ->orderBy('aggregate', 'desc')
                ->get(),
        ];

        // 4. Data Kendaraan: Model / Seri Terbanyak (Top Models hingga 15 item untuk filter Top 5/10/15)
        $popularModels = [
            'all' => DB::table('vehicles')
                ->select('motor_name', 'vehicle_category', DB::raw('count(*) as aggregate'))
                ->groupBy('motor_name', 'vehicle_category')
                ->orderBy('aggregate', 'desc')
                ->take(15)
                ->get(),
            'motor' => DB::table('vehicles')
                ->where('vehicle_category', 'motor')
                ->select('motor_name', 'vehicle_category', DB::raw('count(*) as aggregate'))
                ->groupBy('motor_name', 'vehicle_category')
                ->orderBy('aggregate', 'desc')
                ->take(15)
                ->get(),
            'mobil' => DB::table('vehicles')
                ->where('vehicle_category', 'mobil')
                ->select('motor_name', 'vehicle_category', DB::raw('count(*) as aggregate'))
                ->groupBy('motor_name', 'vehicle_category')
                ->orderBy('aggregate', 'desc')
                ->take(15)
                ->get(),
        ];

        // 5. Data Kendaraan: Tren Pendaftaran Armada (Hingga 90 Hari Terakhir)
        $last90Days = [];
        for ($i = 89; $i >= 0; $i--) {
            $dateString = Carbon::now()->subDays($i)->toDateString();
            $last90Days[$dateString] = 0;
        }

        $motorGrowthRaw = DB::table('vehicles')
            ->where('vehicle_category', 'motor')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as aggregate'))
            ->where('created_at', '>=', now()->subDays(90))
            ->groupBy('date')
            ->get()
            ->pluck('aggregate', 'date')
            ->toArray();

        $mobilGrowthRaw = DB::table('vehicles')
            ->where('vehicle_category', 'mobil')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as aggregate'))
            ->where('created_at', '>=', now()->subDays(90))
            ->groupBy('date')
            ->get()
            ->pluck('aggregate', 'date')
            ->toArray();

        $fleetGrowth = [];
        foreach ($last90Days as $date => $val) {
            $m = $motorGrowthRaw[$date] ?? 0;
            $c = $mobilGrowthRaw[$date] ?? 0;
            $fleetGrowth[] = [
                'date' => $date,
                'motor' => $m,
                'mobil' => $c,
                'total' => $m + $c,
            ];
        }

        return [
            'userCount' => $userCount,
            'motorCount' => $motorCount,
            'mobilCount' => $mobilCount,
            'vehicleCount' => $vehicleCount,
            'totalServicesActive' => $totalServicesActive,
            'totalHistoryCount' => $totalHistoryCount,
            'totalChatCount' => $totalChatCount,
            'categoriesCount' => $categoriesCount,
            'recommendationsCount' => $recommendationsCount,
            'odoRanges' => $odoRanges,
            'typeStats' => $typeStats,
            'popularModels' => $popularModels,
            'fleetGrowth' => $fleetGrowth,
        ];
    }

    public function deleteUser($id)
    {
        $user = User::where('role', '!=', 'admin')->findOrFail($id);
        $user->delete();

        return back()->with('success', "Pengguna {$user->name} ({$user->email}) berhasil dihapus beserta seluruh kendaraannya.");
    }

    public function categories()
    {
        $categories = ServiceCategory::orderBy('vehicle_category')->get();
        return view('admin.categories', compact('categories'));
    }

    public function addCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vehicle_category' => 'required|in:all,motor,mobil',
            'default_interval_km' => 'required|integer|min:100',
            'default_interval_months' => 'required|integer|min:1',
        ]);

        ServiceCategory::create($validated);
        return back()->with('success', 'Kategori servis berhasil ditambahkan!');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = ServiceCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vehicle_category' => 'required|in:all,motor,mobil',
            'default_interval_km' => 'required|integer|min:100',
            'default_interval_months' => 'required|integer|min:1',
        ]);

        $category->update($validated);

        return back()->with('success', "Kategori servis \"{$category->name}\" berhasil diperbarui!");
    }

    public function deleteCategory($id)
    {
        ServiceCategory::destroy($id);
        return back()->with('success', 'Kategori servis berhasil dihapus!');
    }

    public function recommendations()
    {
        $recommendations = Recommendation::orderBy('created_at', 'desc')->get();
        return view('admin.recommendations', compact('recommendations'));
    }

    public function addRecommendation(Request $request)
    {
        $validated = $request->validate([
            'vehicle_category' => 'required|in:all,motor,mobil',
            'motor_type' => 'required|string',
            'fuel_system' => 'required|string',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/recommendations'), $imageName);
            $validated['image'] = 'uploads/recommendations/' . $imageName;
        }

        Recommendation::create($validated);
        return back()->with('success', 'Artikel rekomendasi berhasil diterbitkan!');
    }

    public function deleteRecommendation($id)
    {
        Recommendation::destroy($id);
        return back()->with('success', 'Rekomendasi berhasil dihapus!');
    }
}
