<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['asset_id', 'maintenance_type', 'status', 'start_date', 'end_date', 'cost', 'description'])]
class AssetMaintenance extends Model
{
    public function asset(): BelongsTo {
        return $this->belongsTo(Asset::class);
    }
}
