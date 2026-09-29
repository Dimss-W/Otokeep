<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Service;
use App\Services\OneSignalService;
use Carbon\Carbon;

class SendServiceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi otomatis untuk servis yang mendekati target tanggal atau KM dengan proteksi anti-spam';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentTime = now()->format('H:i');
        $this->info("Memulai pengecekan pengingat servis untuk jam: {$currentTime}");

        // Ambil service milik user yang jadwal notifikasinya adalah sekarang
        // Dan belum pernah dikirim notifikasi dalam 24 jam terakhir (Anti-spam)
        $services = Service::whereHas('vehicle.user', function($query) use ($currentTime) {
            $query->where('notification_time', $currentTime);
        })
        ->where(function($query) {
            $query->whereNull('last_notified_at')
                  ->orWhere('last_notified_at', '<', now()->subHours(24));
        })
        ->with(['vehicle.user', 'category'])
        ->get();

        if ($services->isEmpty()) {
            $this->info('Tidak ada jadwal notifikasi yang perlu dikirim untuk menit ini.');
            return;
        }

        foreach ($services as $service) {
            $user = $service->vehicle->user ?? null;
            if (!$user) continue;

            $shouldNotify = false;
            $message = "";

            $vehicleTypeStr = ($service->vehicle->vehicle_category === 'mobil') ? 'Mobil' : 'Motor';

            // 1. Cek Berdasarkan Tanggal (H-3 sampai H-0)
            if ($service->target_date) {
                $targetDate = Carbon::parse($service->target_date);
                $daysRemaining = now()->diffInDays($targetDate, false);

                if ($daysRemaining <= 3 && $daysRemaining >= 0) {
                    $shouldNotify = true;
                    $message = "Waktunya Manjain " . $vehicleTypeStr . " lo! Servis " . $service->category->name . " tinggal " . $daysRemaining . " hari lagi nih.";
                }
            }

            // 2. Cek Berdasarkan KM (Jika sisa <= 200 KM)
            $remainingKm = $service->target_km - $service->vehicle->current_km;
            if ($remainingKm <= 200 && $remainingKm > 0 && !$shouldNotify) {
                $shouldNotify = true;
                $message = "Odometer sudah mendekati target! Servis " . $service->category->name . " " . $vehicleTypeStr . " lo sisa " . $remainingKm . " KM lagi.";
            }

            if ($shouldNotify) {
                OneSignalService::sendNotification($user->id, $message);
                $service->update(['last_notified_at' => now()]);
                $this->line("Notifikasi dikirim ke User ID: " . $user->id . " - Pesan: " . $message);
            }
        }

        $this->info('Pengecekan selesai!');
    }
}
