<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assets = [
            // Rambu Lalu Lintas (Type 1)
            ['name' => 'Rambu Batas Kecepatan 40 km', 'asset_type_id' => 1, 'status' => 'baik', 'latitude' => -0.3127, 'longitude' => 100.3729, 'location' => 'Jl. Jend. Sudirman', 'quantity' => 3],
            ['name' => 'Rambu Dilarang Parkir', 'asset_type_id' => 1, 'status' => 'perlu_perbaikan', 'latitude' => -0.3080, 'longitude' => 100.3707, 'location' => 'Jl. Haji Usmar Ismail', 'quantity' => 5],
            ['name' => 'Rambu One Way', 'asset_type_id' => 1, 'status' => 'rusak', 'latitude' => -0.3030, 'longitude' => 100.369870, 'location' => 'Jl. Minangkabau', 'quantity' => 2],
            // Halte (Type 2)
            ['name' => 'Halte Jam Gadang', 'asset_type_id' => 2, 'status' => 'baik', 'latitude' => -0.305597, 'longitude' => 100.369045, 'location' => 'Jl. Perwira', 'quantity' => 1],
            ['name' => 'Halte Simpang Air Kuning', 'asset_type_id' => 2, 'status' => 'dalam_pemeliharaan', 'latitude' => -0.313749, 'longitude' => 100.384607, 'location' => 'Simpang Air Kuning', 'quantity' => 1],
            // APILL (Type 3)
            ['name' => 'APILL Sudirman - Haji Usmar', 'asset_type_id' => 3, 'status' => 'baik', 'latitude' => -0.308088, 'longitude' => 100.370927, 'location' => 'Persimpangan', 'quantity' => 1],
            // Marka Jalan (Type 4)
            ['name' => 'Marka Jalan Putih', 'asset_type_id' => 4, 'status' => 'baik', 'latitude' => -0.292490, 'longitude' => 100.367020, 'location' => 'Jl. Veteran 84 - 68', 'quantity' => 500],
            // PAJ (Type 5)
            ['name' => 'CCTV Lapangan Kantin', 'asset_type_id' => 5, 'status' => 'baik', 'latitude' => -0.312701, 'longitude' => 100.372928, 'location' => 'Jl. Jend. Sudirman', 'quantity' => 1],
            ['name' => 'CCTV DPRD', 'asset_type_id' => 5, 'status' => 'baik', 'latitude' => -0.307266, 'longitude' => 100.369633, 'location' => 'Jl. Haji Usmar Ismail', 'quantity' => 1],
        ];

        foreach ($assets as $asset) {
            Asset::create($asset);
        }
    }
}
