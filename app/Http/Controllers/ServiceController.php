<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceHistory;
use App\Models\ServiceCategory;

class ServiceController extends Controller
{
    public function add(Request $request)
    {
        $vehicle = auth()->user()->active_vehicle ?? auth()->user()->vehicle;
        if (!$vehicle) {
            return redirect()->route('vehicle.register')->with('error', 'Silakan daftarkan kendaraan terlebih dahulu.');
        }

        $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'target_km' => 'required|integer|min:0',
            'target_date' => 'nullable|date',
        ]);

        // Cek apakah item servis untuk kategori ini sudah ada
        $existing = Service::where('vehicle_id', $vehicle->id)
            ->where('category_id', $request->category_id)
            ->first();

        if ($existing) {
            $existing->update([
                'last_service_km' => $vehicle->current_km,
                'target_km' => $request->target_km,
                'target_date' => $request->target_date,
                'last_notified_at' => null,
            ]);
            return back()->with('success', 'Jadwal servis berhasil diperbarui!');
        }

        Service::create([
            'vehicle_id' => $vehicle->id,
            'category_id' => $request->category_id,
            'last_service_km' => $vehicle->current_km,
            'target_km' => $request->target_km,
            'target_date' => $request->target_date,
        ]);

        return back()->with('success', 'Item servis baru berhasil ditambahkan!');
    }

    public function markDone(Request $request, $id)
    {
        $vehicle = auth()->user()->active_vehicle ?? auth()->user()->vehicle;
        if (!$vehicle) {
            return redirect()->route('vehicle.register');
        }

        // Keamanan (Fix IDOR): Pastikan servis milik kendaraan user yang sedang aktif
        $service = $vehicle->services()->with('category')->findOrFail($id);

        $request->validate([
            'cost' => 'nullable|numeric|min:0',
            'workshop_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'action' => 'nullable|in:rollover,remove',
            'next_interval_km' => 'nullable|integer|min:100',
            'next_target_date' => 'nullable|date',
        ]);

        $cost = $request->filled('cost') ? (int) $request->cost : 0;

        // 1. Simpan ke Riwayat Servis (dengan biaya & detail bengkel)
        ServiceHistory::create([
            'vehicle_id' => $vehicle->id,
            'category_id' => $service->category_id,
            'service_date' => $request->service_date ?? now(),
            'service_km' => $vehicle->current_km,
            'cost' => $cost,
            'workshop_name' => $request->workshop_name,
            'notes' => $request->notes,
        ]);

        // 2. Tentukan aksi: Rollover (Jadwal baru) atau Hapus permanen
        $action = $request->input('action', 'rollover');

        if ($action === 'remove') {
            $service->delete();
            return back()->with('success', "Servis {$service->category->name} selesai dan dihapus dari dashboard pemantauan.");
        }

        // Auto-Rollover: Set siklus interval berikutnya
        $defaultInterval = $service->category->default_interval_km ?? 2500;
        $intervalKm = $request->filled('next_interval_km') ? (int) $request->next_interval_km : $defaultInterval;
        $nextTargetKm = $vehicle->current_km + $intervalKm;

        $defaultMonths = $service->category->default_interval_months ?? 3;
        $nextTargetDate = $request->filled('next_target_date') 
            ? $request->next_target_date 
            : now()->addMonths($defaultMonths)->toDateString();

        $service->update([
            'last_service_km' => $vehicle->current_km,
            'target_km' => $nextTargetKm,
            'target_date' => $nextTargetDate,
            'last_notified_at' => null,
        ]);

        return back()->with('success', "Servis dicatat! Target berikutnya otomatis diatur ke " . number_format($nextTargetKm, 0, ',', '.') . " KM (+{$intervalKm} KM).");
    }
}
