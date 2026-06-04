<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(){
        return view('reports.index');
    }

    public function print(Request $request){
        $request->validate([
            'from' => ['required'],
            'to' =>  ['required'],
            'type' =>  ['required'],
        ]);

        if($request->type === 'asset'){
            return $this->print_asset($request->from, $request->to);
        }
    }

    protected function print_asset($from, $to){
        $assets = Asset::with(['type', 'subType'])->get();
        return view('pdf.asset', [
            'assets' => $assets
        ]);
    }
}
