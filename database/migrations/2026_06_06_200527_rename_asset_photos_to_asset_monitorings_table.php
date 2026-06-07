<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('asset_photos', 'asset_monitorings');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('asset_monitorings', 'asset_photos');
    }
};
