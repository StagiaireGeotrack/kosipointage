<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leave_roles', function (Blueprint $table) {
            $table->id();
            // ❌ PAS DE site_id POUR L'INSTANT
            $table->string('name')->unique();
            $table->string('label');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_roles');
    }
};