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
            ['name' => 'Rambu Batas Kecepatan 40 km', 'asset_type_id' => 1, 'status' => 'baik', 'latitude' => -6.2088, 'longitude' => 106.8456, 'location' => 'Jl. Jend. Sudirman', 'quantity' => 3],
            ['name' => 'Rambu Dilarang Parkir', 'asset_type_id' => 1, 'status' => 'perlu_perbaikan', 'latitude' => -6.2095, 'longitude' => 106.8470, 'location' => 'Jl. Gatot Subroto', 'quantity' => 5],
            ['name' => 'Rambu One Way', 'asset_type_id' => 1, 'status' => 'rusak', 'latitude' => -6.2105, 'longitude' => 106.8480, 'location' => 'Jl. Imam Bonjol', 'quantity' => 2],
            // Halte (Type 2)
            ['name' => 'Halte Jom Gadang', 'asset_type_id' => 2, 'status' => 'baik', 'latitude' => -6.2120, 'longitude' => 106.8490, 'location' => 'Jl. Perwira', 'quantity' => 1],
            ['name' => 'Halte Simpang Air Kuning', 'asset_type_id' => 2, 'status' => 'dalam_pemeliharaan', 'latitude' => -6.2130, 'longitude' => 106.8500, 'location' => 'Simpang Air Kuning', 'quantity' => 1],
            // APILL (Type 3)
            ['name' => 'APILL Sudirman - Gatot', 'asset_type_id' => 3, 'status' => 'baik', 'latitude' => -6.2140, 'longitude' => 106.8510, 'location' => 'Persimpangan', 'quantity' => 1],
            ['name' => 'APILL Jln Ahmad Yani', 'asset_type_id' => 3, 'status' => 'perlu_perbaikan', 'latitude' => -6.2150, 'longitude' => 106.8520, 'location' => 'Jl. Ahmad Yani', 'quantity' => 1],
            // Marka Jalan (Type 4)
            ['name' => 'Marka Jalan Putih', 'asset_type_id' => 4, 'status' => 'baik', 'latitude' => -6.2160, 'longitude' => 106.8530, 'location' => 'Jl. Jend. Sudirman', 'quantity' => 500],
            ['name' => 'Marka Jalan Kuning', 'asset_type_id' => 4, 'status' => 'rusak', 'latitude' => -6.2170, 'longitude' => 106.8540, 'location' => 'Jl. Veteran', 'quantity' => 200],
            // PAJ (Type 5)
            ['name' => 'CCTV Sudirman 1', 'asset_type_id' => 5, 'status' => 'baik', 'latitude' => -6.2180, 'longitude' => 106.8550, 'location' => 'Jl. Jend. Sudirman', 'quantity' => 1],
            ['name' => 'CCTV Gatot Subroto', 'asset_type_id' => 5, 'status' => 'baik', 'latitude' => -6.2190, 'longitude' => 106.8560, 'location' => 'Jl. Gatot Subroto', 'quantity' => 1],
        ];

        foreach ($assets as $asset) {
            Asset::create($asset);
        }
    }
}
