<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculation_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
            $table->string('name');              // ex: "Droits acquis", "Solde final"
            $table->text('formula');             // ex: "min(max_per_year, (max_per_year / 12) * mois_travailles)"
            $table->string('output_variable');   // ex: "entitlement", "balance"
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculation_rules');
    }
};