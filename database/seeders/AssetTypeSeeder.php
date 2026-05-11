<?php

namespace Database\Seeders;

use App\Models\AssetType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Rambu Lalu Lintas',
                'icon' => 'fa-circle-info',
                'color' => '#3B82F6',
                'description' => 'Rambu-rambu lalu lintas untuk keselamatan jalan',
            ],
            [
                'name' => 'Halte',
                'icon' => 'fa-bus',
                'color' => '#F59E0B',
                'description' => 'Halte atau tempat perhentian kendaraan umum',
            ],
            [
                'name' => 'APILL',
                'icon' => 'fa-traffic-light',
                'color' => '#EF4444',
                'description' => 'Alat Pemberi Isyarat Lalu Lintas',
            ],
            [
                'name' => 'Marka Jalan',
                'icon' => 'fa-road',
                'color' => '#8B5CF6',
                'description' => 'Marka atau garis penanda di jalan',
            ],
            [
                'name' => 'PAJ',
                'icon' => 'fa-camera',
                'color' => '#10B981',
                'description' => 'Perangkat Alat Jalan (CCTV, sensor, dll)',
            ],
        ];

        foreach ($types as $type) {
            AssetType::create($type);
        }
    }
}
