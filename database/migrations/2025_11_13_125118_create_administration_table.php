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
        Schema::create('administration', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('Identifiant_email');
            $table->text('Password_')->nullable();
            $table->boolean('IsSuperAdmin')->default(false);
            $table->boolean('IsSeller')->default(false);
            $table->unsignedInteger('SiegeID')->nullable()->index('administration_siegeid_foreign');
            $table->timestamps();
            $table->rememberToken();
            $table->boolean('Actived')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administration');
    }
};
