<?php

namespace Database\Seeders;

use App\Models\Counter;
use App\Models\Service;
use Illuminate\Database\Seeder;

class QueueSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate existing data to avoid duplicates
        Counter::truncate();
        Service::truncate();

        // Create Services
        Service::create([
            'name' => 'Pengaduan',
            'code' => 'ADUAN',
            'prefix' => 'A',
            'current_number' => 0,
            'description' => 'Layanan pengaduan dan keluhan masyarakat',
            'is_active' => true,
        ]);

        Service::create([
            'name' => 'Permohonan Informasi',
            'code' => 'INFO',
            'prefix' => 'B',
            'current_number' => 0,
            'description' => 'Layanan permohonan informasi publik',
            'is_active' => true,
        ]);

        Service::create([
            'name' => 'Konsultasi',
            'code' => 'KONSUL',
            'prefix' => 'C',
            'current_number' => 0,
            'description' => 'Layanan konsultasi dengan petugas humas',
            'is_active' => true,
        ]);

        // Create Counters (all counters serve all services)
        Counter::create(['name' => 'Loket 1', 'code' => 'L1']);
        Counter::create(['name' => 'Loket 2', 'code' => 'L2']);
    }
}
