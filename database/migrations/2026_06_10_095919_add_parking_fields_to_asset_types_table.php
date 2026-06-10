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
        Schema::table('asset_types', function (Blueprint $table) {
            $table->enum('geometry', ['point', 'polygon', 'polyline'])->default('point')->after('description');
            $table->string('asset_category')->default('general_asset')->after('geometry');
            $table->boolean('is_active')->default(true)->after('asset_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_types', function (Blueprint $table) {
            $table->dropColumn(['geometry', 'asset_category', 'is_active']);
        });
    }
};
