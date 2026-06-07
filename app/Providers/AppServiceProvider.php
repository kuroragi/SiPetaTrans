<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useTailwind();
        
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            $newDamageReportsCount = \App\Models\DamageReport::where('status', 'baru')->count();
            
            $damagedAssetsCount = \App\Models\AssetMonitoring::whereIn('id', function($query) {
                $query->selectRaw('MAX(id)')->from('asset_monitorings')->groupBy('asset_id');
            })->where('condition', 'rusak')->count();

            $view->with(compact('newDamageReportsCount', 'damagedAssetsCount'));
        });
    }
}
