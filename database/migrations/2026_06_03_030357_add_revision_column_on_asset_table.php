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
            $table->string('registration_number')->after('id')->nullable();
            $table->foreignId('asset_sub_type_id')->nullable()->constrained('asset_sub_types')->nullOnDelete()->after('asset_type_id');
            $table->date('acquired_at')->after('asset_sub_type_id')->nullable();
            $table->bigInteger('acquisition_value')->after('acquired_at')->default(0);
            $table->string('acquisition_source')->after('acquisition_value')->nullable();
            $table->bigInteger('current_value')->after('acquisition_source')->default(0);
            $table->text('last_maintenance_photo')->after('last_maintenance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('registration_number');
            $table->dropForeign(['asset_sub_type_id']);
            $table->dropColumn('asset_sub_type_id');
            $table->dropColumn('acquired_at');
            $table->dropColumn('acquisition_value');
            $table->dropColumn('acquisition_source');
            $table->dropColumn('current_value');
            $table->dropColumn('last_maintenance_photo');
        });
    }
};
