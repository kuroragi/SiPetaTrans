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
        Schema::table('trayeks', function (Blueprint $table) {
            $table->integer('armada_count')->nullable()->default(0);
            $table->integer('armada_active_count')->nullable()->default(0);
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trayeks', function (Blueprint $table) {
            $table->dropColumn(['armada_count', 'armada_active_count', 'description']);
        });
    }
};
