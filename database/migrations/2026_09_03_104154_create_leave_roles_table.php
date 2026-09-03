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
            $table->string('name')->unique(); // manager, rh, drh, direction
            $table->string('label'); // Manager, RH, DRH, Direction
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_roles');
    }
};
