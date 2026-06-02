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
            $table->boolean('is_sub_type_needed')->default(false)->after('description');
        });

        Schema::create('sub_asset_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('assets_type_id')->constrained('asset_types')->cascadeOnDelete();
            $table->string('name');
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_asset_types');
        Schema::table('asset_types', function (Blueprint $table) {
            $table->dropColumn('is_sub_type_needed');
        });
    }
};
