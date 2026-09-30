<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ServiceCategory;
use App\Models\Recommendation;
use App\Models\Vehicle;
use App\Models\Service;

class OtoKeepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin User
        User::firstOrCreate(
            ['email' => 'admin@otokeep.com'],
            [
                'name' => 'Admin OtoKeep',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'notification_time' => '08:00',
            ]
        );

        // 2. Demo User
        $demoUser = User::firstOrCreate(
            ['email' => 'dims@gmail.com'],
            [
                'name' => 'Dimas Wijanarko',
                'password' => bcrypt('password'),
                'role' => 'user',
                'notification_time' => '08:00',
            ]
        );

        // 3. Default Categories
        $categories = [
            ['name' => 'Oli Mesin', 'vehicle_category' => 'all', 'default_interval_km' => 2000, 'default_interval_months' => 3],
            ['name' => 'Oli Gardan', 'vehicle_category' => 'all', 'default_interval_km' => 2000, 'default_interval_months' => 3],
            ['name' => 'CVT / V-Belt', 'vehicle_category' => 'all', 'default_interval_km' => 2000, 'default_interval_months' => 3],
            ['name' => 'Filter Udara', 'vehicle_category' => 'all', 'default_interval_km' => 10000, 'default_interval_months' => 6],
            ['name' => 'Busi', 'vehicle_category' => 'all', 'default_interval_km' => 8000, 'default_interval_months' => 6],
            ['name' => 'Throttle Body (TB)', 'vehicle_category' => 'all', 'default_interval_km' => 8000, 'default_interval_months' => 6],
            ['name' => 'Kampas Rem', 'vehicle_category' => 'all', 'default_interval_km' => 8000, 'default_interval_months' => 6],
            ['name' => 'Air Radiator', 'vehicle_category' => 'all', 'default_interval_km' => 12000, 'default_interval_months' => 12],
            ['name' => 'Oli Mesin & Filter', 'vehicle_category' => 'mobil', 'default_interval_km' => 5000, 'default_interval_months' => 6],
            ['name' => 'Filter AC Kabin', 'vehicle_category' => 'mobil', 'default_interval_km' => 10000, 'default_interval_months' => 6],
            ['name' => 'Oli Transmisi', 'vehicle_category' => 'mobil', 'default_interval_km' => 20000, 'default_interval_months' => 12],
            ['name' => 'Radiator Coolant', 'vehicle_category' => 'mobil', 'default_interval_km' => 40000, 'default_interval_months' => 24],
            ['name' => 'Oli Gardan (CVT)', 'vehicle_category' => 'motor', 'default_interval_km' => 8000, 'default_interval_months' => 6],
            ['name' => 'V-Belt & Roller CVT', 'vehicle_category' => 'motor', 'default_interval_km' => 20000, 'default_interval_months' => 18],
            ['name' => 'Rantai & Gear Set', 'vehicle_category' => 'motor', 'default_interval_km' => 15000, 'default_interval_months' => 12],
        ];

        foreach ($categories as $cat) {
            ServiceCategory::firstOrCreate(
                ['name' => $cat['name']],
                $cat
            );
        }

        // 4. Default Vehicles for Demo User if none exist
        if ($demoUser->vehicles()->count() === 0) {
            Vehicle::create([
                'user_id' => $demoUser->id,
                'vehicle_category' => 'mobil',
                'motor_name' => 'HRV',
                'type' => 'matic',
                'fuel_system' => 'injeksi',
                'current_km' => 15200,
                'iot_token' => 'OTO-IOT-QRXB0HXP',
            ]);

            Vehicle::create([
                'user_id' => $demoUser->id,
                'vehicle_category' => 'motor',
                'motor_name' => 'Stylo 160',
                'type' => 'matic',
                'fuel_system' => 'injeksi',
                'current_km' => 8000,
                'iot_token' => 'OTO-IOT-RDDV56VG',
            ]);
        }
    }
}
