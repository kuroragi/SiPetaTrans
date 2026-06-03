<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetSubType extends Model
{
    protected $fillable = [
        'asset_type_id',
        'name',
        'color',
    ];

    public function assetType()
    {
        return $this->belongsTo(AssetType::class);
    }
}
