<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['asset_id', 'maintenance_date', 'status_before', 'status_after'])]
class AssetMaintenance extends Model
{
    public function asset(): BelongsTo {
        return $this->belongsTo(Asset::class);
    }
}
