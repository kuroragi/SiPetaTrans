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
        Schema::table('asset_maintenances', function (Blueprint $table) {
            $table->dropColumn(['maintenance_date', 'status_before', 'status_after']);
            $table->enum('maintenance_type', ['rutin', 'perbaikan'])->after('asset_id');
            $table->enum('status', ['sedang_berjalan', 'selesai'])->after('maintenance_type');
            $table->date('start_date')->after('status');
            $table->date('end_date')->nullable()->after('start_date');
            $table->decimal('cost', 15, 2)->nullable()->after('end_date');
            $table->text('description')->nullable()->after('cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_maintenances', function (Blueprint $table) {
            $table->dropColumn(['maintenance_type', 'status', 'start_date', 'end_date', 'cost', 'description']);
            $table->date('maintenance_date');
            $table->enum('status_before', ['baik', 'perlu perbaikan', 'rusak']);
            $table->enum('status_after', ['baik', 'perlu perbaikan', 'rusak']);
        });
    }
};
