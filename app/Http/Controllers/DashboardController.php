<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total assets
        $totalAssets = Asset::count();

        // Get assets by status
        $assetsByStatus = Asset::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Calculate percentages
        $goodPercentage = round(($assetsByStatus['baik'] ?? 0) / max($totalAssets, 1) * 100, 1);
        $needsRepairPercentage = round(($assetsByStatus['perlu_perbaikan'] ?? 0) / max($totalAssets, 1) * 100, 1);
        $damagedPercentage = round(($assetsByStatus['rusak'] ?? 0) / max($totalAssets, 1) * 100, 1);
        $maintenancePercentage = round(($assetsByStatus['dalam_pemeliharaan'] ?? 0) / max($totalAssets, 1) * 100, 1);

        // Get all assets for map
        $assets = Asset::with('type')->get();

        // Get damaged assets
        $damagedAssets = Asset::where('status', 'rusak')
            ->orWhere('status', 'perlu_perbaikan')
            ->latest()
            ->get();

        // Get asset types for bar chart
        $assetTypes = AssetType::with('assets')->get();

        $assetTypeLabels = [];
        $assetTypeGood = [];
        $assetTypeNeedsRepair = [];
        $assetTypeDamaged = [];
        $assetTypeMaintenance = [];

        foreach ($assetTypes as $type) {
            $assetTypeLabels[] = $type->name;
            $assetTypeGood[] = $type->assets()->where('status', 'baik')->count();
            $assetTypeNeedsRepair[] = $type->assets()->where('status', 'perlu_perbaikan')->count();
            $assetTypeDamaged[] = $type->assets()->where('status', 'rusak')->count();
            $assetTypeMaintenance[] = $type->assets()->where('status', 'dalam_pemeliharaan')->count();
        }

        // Get other stats
        $lastUpdate = now()->format('d M Y H:i');
        $dataAccuracy = 95.2;
        $activeUsers = User::count();

        return view('dashboard.index', [
            'totalAssets' => $totalAssets,
            'assetsByStatus' => $assetsByStatus,
            'goodPercentage' => $goodPercentage,
            'needsRepairPercentage' => $needsRepairPercentage,
            'damagedPercentage' => $damagedPercentage,
            'maintenancePercentage' => $maintenancePercentage,
            'assets' => $assets,
            'damagedAssets' => $damagedAssets,
            'assetTypeLabels' => $assetTypeLabels,
            'assetTypeGood' => $assetTypeGood,
            'assetTypeNeedsRepair' => $assetTypeNeedsRepair,
            'assetTypeDamaged' => $assetTypeDamaged,
            'assetTypeMaintenance' => $assetTypeMaintenance,
            'lastUpdate' => $lastUpdate,
            'dataAccuracy' => $dataAccuracy,
            'activeUsers' => $activeUsers,
        ]);
    }
}
