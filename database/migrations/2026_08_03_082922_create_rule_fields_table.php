<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rule_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
            $table->string('field_key');              // ex: max_per_year, allow_half_day
            $table->enum('field_type', ['number', 'boolean', 'select', 'text', 'formula']);
            $table->string('label');                   // "Jours maximum", "Demi-journée autorisée"
            $table->text('default_value')->nullable();
            $table->json('options')->nullable();      // pour les select: [{"value":"manager","label":"Manager"}]
            $table->json('validation')->nullable();   // {"min":0,"max":365,"required":true,"step":0.5}
            $table->json('conditions')->nullable();    // visibilité conditionnelle future
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['leave_type_id', 'field_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rule_fields');
    }
};