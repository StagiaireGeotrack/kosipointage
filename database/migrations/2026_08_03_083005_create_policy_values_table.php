<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('policy_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_policy_id')->constrained()->onDelete('cascade');
            $table->foreignId('rule_field_id')->constrained()->onDelete('cascade');
            $table->text('value');
            $table->timestamps();

            $table->unique(['leave_policy_id', 'rule_field_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('policy_values');
    }
};