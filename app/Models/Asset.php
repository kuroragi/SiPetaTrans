<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'name',
        'asset_type_id',
        'status',
        'latitude',
        'longitude',
        'location',
        'last_maintenance',
        'description',
        'quantity',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'last_maintenance' => 'date',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(AssetPhoto::class)->latest('photo_date');
    }
}
