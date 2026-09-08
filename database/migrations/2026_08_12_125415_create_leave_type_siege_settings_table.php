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
        Schema::create('leave_type_siege_settings', function (Blueprint $table) {
            $table->id();

            // entreprises_sieges.ID = INT UNSIGNED
            $table->unsignedInteger('SiegeID');

            // leave_types.id = BIGINT UNSIGNED
            $table->unsignedBigInteger('leave_type_id');

            $table->boolean('is_visible')->default(true);

            // leave_types.id = BIGINT UNSIGNED
            $table->unsignedBigInteger('forked_type_id')->nullable();

            $table->timestamps();

            $table->unique(['SiegeID', 'leave_type_id']);

            $table->foreign('leave_type_id')
                ->references('id')
                ->on('leave_types')
                ->onDelete('cascade');

            $table->foreign('forked_type_id')
                ->references('id')
                ->on('leave_types')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_type_siege_settings');
    }
};
