<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

use App\Models\AssetType;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view reports', only: ['index']),
            new Middleware('permission:print reports', only: ['print']),
        ];
    }
    public function index(){
        $assetTypes = AssetType::all();
        return view('reports.index', compact('assetTypes'));
    }

    public function print(Request $request){
        $request->validate([
            'date' => ['required', 'date'],
            'type' => ['required'],
            'format' => ['required', 'in:pdf,excel'],
            'assetType' => ['nullable'],
            'status' => ['nullable'],
        ]);

        if($request->type === 'asset'){
            return $this->print_asset($request->date, $request->format, $request->assetType, $request->status);
        }
    }

    protected function print_asset($date, $format, $assetTypeId, $status){
        $targetDate = Carbon::parse($date)->endOfDay();
        
        if ($format === 'pdf') {
            $query = Asset::with(['type', 'subType', 'depreciations']);
            
            if ($assetTypeId) {
                $query->where('asset_type_id', $assetTypeId);
            }
            
            if ($status) {
                $query->where('status', $status);
            }
            
            $assets = $query->get()->map(function ($asset) use ($targetDate) {
                $depreciation = $asset->depreciations->where('depreciation_date', '<=', $targetDate->format('Y-m-d'))->first();
                if ($depreciation) {
                    $asset->current_value = $depreciation->value;
                } else {
                    $asset->current_value = $asset->acquisition_value;
                }
                return $asset;
            });

            $pdf = Pdf::loadView('pdf.asset', [
                'assets' => $assets
            ]);
            $pdf->setPaper([0, 0, 612.283, 935.433], 'landscape');
            return $pdf->stream('Laporan_Asset.pdf');
        } else {
            // Excel using SQL View
            $query = DB::table('asset_reports_view');
            
            if ($assetTypeId) {
                $query->where('asset_type_id', $assetTypeId);
            }
            
            if ($status) {
                $query->where('status', $status);
            }

            // Adding subquery to get the dynamic current value based on the date
            $query->select('*')
                ->selectSub(function ($query) use ($targetDate) {
                    $query->select('value')
                        ->from('asset_depreciations')
                        ->whereColumn('asset_id', 'asset_reports_view.asset_id')
                        ->where('depreciation_date', '<=', $targetDate->format('Y-m-d'))
                        ->orderByDesc('depreciation_date')
                        ->limit(1);
                }, 'calculated_value');

            $rawAssets = $query->get();

            // Format data to match blade expectations
            $assets = $rawAssets->map(function ($row) {
                return (object)[
                    'registration_number' => "'" . $row->registration_number,
                    'name' => $row->asset_name,
                    'type' => (object)['name' => $row->type_name],
                    'subtype' => (object)['name' => $row->subtype_name],
                    'status' => $row->status,
                    'location' => $row->location,
                    'quantity' => $row->quantity,
                    'current_value' => $row->calculated_value ?? $row->acquisition_value,
                    'acquisition_source' => $row->acquisition_source,
                    'acquisition_date' => $row->acquisition_date,
                ];
            });

            $html = view('pdf.asset', [
                'assets' => $assets
            ])->render();
            
            return response($html)
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment; filename="Laporan_Asset.xls"');
        }
    }
}
