<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE damage_reports MODIFY COLUMN status ENUM('baru', 'ditindak_lanjuti', 'selesai') DEFAULT 'baru'");
        // Update existing 'dalam_proses' to 'ditindak_lanjuti'
        DB::statement("UPDATE damage_reports SET status = 'ditindak_lanjuti' WHERE status = 'dalam_proses'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE damage_reports SET status = 'dalam_proses' WHERE status = 'ditindak_lanjuti'");
        DB::statement("ALTER TABLE damage_reports MODIFY COLUMN status ENUM('baru', 'dalam_proses', 'selesai') DEFAULT 'baru'");
    }
};
