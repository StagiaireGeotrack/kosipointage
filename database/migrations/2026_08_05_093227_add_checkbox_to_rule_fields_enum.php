<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE rule_fields MODIFY COLUMN field_type ENUM('number','boolean','select','checkbox','text','formula') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE rule_fields MODIFY COLUMN field_type ENUM('number','boolean','select','text','formula') NOT NULL");
    }
};