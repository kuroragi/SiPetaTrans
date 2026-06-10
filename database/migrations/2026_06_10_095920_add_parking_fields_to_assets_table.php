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
        Schema::table('assets', function (Blueprint $table) {
            $table->json('coordinates')->nullable()->after('status');
            $table->enum('vehicle_type', ['R2', 'R4', 'R2/R4'])->nullable()->after('quantity');
            $table->integer('r2')->nullable()->after('vehicle_type');
            $table->integer('r4')->nullable()->after('r2');
            $table->enum('tariff_type', ['flat', 'progresive'])->nullable()->after('r4');
            $table->string('manager')->nullable()->after('tariff_type');
            
            // Modify existing columns
            $table->decimal('latitude', 10, 7)->nullable()->change();
            $table->decimal('longitude', 10, 7)->nullable()->change();
            $table->enum('status', ['baik', 'perlu_perbaikan', 'rusak', 'dalam_pemeliharaan'])->nullable()->default('baik')->change();
            $table->bigInteger('acquisition_value')->nullable()->default(0)->change();
            $table->bigInteger('current_value')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['coordinates', 'vehicle_type', 'r2', 'r4', 'tariff_type', 'manager']);
            $table->enum('status', ['baik', 'perlu_perbaikan', 'rusak', 'dalam_pemeliharaan'])->nullable(false)->change();
            $table->bigInteger('acquisition_value')->after('acquired_at')->default(0)->change();
            $table->bigInteger('current_value')->after('acquisition_source')->default(0)->change();
        });
    }
};
