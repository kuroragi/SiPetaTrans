<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW asset_reports_view AS
            SELECT 
                a.id as asset_id,
                a.registration_number,
                a.name as asset_name,
                t.name as type_name,
                st.name as subtype_name,
                a.status,
                a.location,
                a.quantity,
                a.current_value as default_current_value,
                a.acquisition_value,
                a.acquisition_source,
                a.acquired_at as acquisition_date,
                a.asset_type_id
            FROM assets a
            LEFT JOIN asset_types t ON a.asset_type_id = t.id
            LEFT JOIN asset_sub_types st ON a.asset_sub_type_id = st.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS asset_reports_view");
    }
};
