<?php
// database/migrations/2026_08_12_110000_create_leave_type_siege_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leave_type_siege_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('SiegeID');
            $table->unsignedInteger('leave_type_id'); // le type global d'origine
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('forked_type_id')->nullable(); // la copie locale
            $table->timestamps();

            $table->unique(['SiegeID', 'leave_type_id']);
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
            $table->foreign('forked_type_id')->references('id')->on('leave_types')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_type_siege_settings');
    }
};