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
        Schema::table('administration', function (Blueprint $table) {
            $table->boolean('IsManager')->default(0)->after('IsSeller');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administration', function (Blueprint $table) {
            $table->dropColumn('IsManager');
        });
    }
};
