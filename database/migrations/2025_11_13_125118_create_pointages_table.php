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
        Schema::create('pointages', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedInteger('employee_id')->index('pointages_employee_id_foreign');
            $table->string('type_', 25);
            $table->string('auth_method', 25);
            $table->dateTime('timestamp_')->useCurrent();
            $table->decimal('latitude', 18, 8);
            $table->decimal('longitude', 18, 8);
            $table->text('photo_path')->nullable();
            $table->boolean('synced')->default(false);
            $table->unsignedInteger('SiegeID')->index('pointages_siegeid_foreign');
            $table->unsignedInteger('company_id')->index('fk_pointages_company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pointages');
    }
};
