<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetMonitoring extends Model
{
    protected $fillable = [
        'asset_id',
        'photo_path',
        'condition',
        'notes',
        'photo_date',
        'captured_by',
    ];

    protected $casts = [
        'photo_date' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
