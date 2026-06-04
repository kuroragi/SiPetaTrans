<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'registration_number',
        'name',
        'asset_type_id',
        'asset_sub_type_id',
        'acquired_at',
        'acquisition_value',
        'acquisition_source',
        'current_value',
        'status',
        'latitude',
        'longitude',
        'location',
        'last_maintenance',
        'last_maintenance_photo',
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

    public function subType(): belongsTo
    {
        return $this->belongsTo(AssetSubType::class, 'asset_sub_type_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(AssetPhoto::class)->latest('photo_date');
    }

    public function maintenance(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class);
    }
}
