<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamageReport extends Model
{
    protected $fillable = [
        'nama_pelapor',
        'kontak',
        'lokasi',
        'asset_id',
        'foto',
        'keterangan',
        'status',
        'seen',
        'forwarded_at',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
