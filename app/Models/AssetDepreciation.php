<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDepreciation extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'depreciation_date',
        'value',
        'notes',
    ];

    protected $casts = [
        'depreciation_date' => 'date',
        'value' => 'integer',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
