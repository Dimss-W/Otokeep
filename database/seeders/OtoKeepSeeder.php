<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtoKeepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@otokeep.com'],
            [
                'name' => 'Admin OtoKeep',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Default Categories
        $categories = [
            'Oli Mesin',
            'Oli Gardan',
            'CVT / V-Belt',
            'Filter Udara',
            'Busi',
            'Throttle Body (TB)',
            'Kampas Rem',
            'Air Radiator'
        ];

        foreach ($categories as $cat) {
            \App\Models\ServiceCategory::firstOrCreate(['name' => $cat]);
        }
    }
}
