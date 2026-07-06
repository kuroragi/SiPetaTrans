<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trayek extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'distance',
        'coordinate',
        'classification',
        'color',
        'route_type',
        'armada_count',
        'armada_active_count',
        'description',
    ];

    protected $casts = [
        'coordinate' => 'array',
        'distance' => 'decimal:2',
    ];
}
