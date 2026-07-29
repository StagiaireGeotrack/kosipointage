<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->string('name', 255);
            $table->string('code', 50)->nullable();
            $table->string('color', 7)->nullable(); // hex #RRGGBB
            $table->enum('unit', ['days', 'half_days', 'hours'])->default('days');
            $table->boolean('deducts_balance')->default(true);
            $table->enum('requires_attachment', ['never', 'always', 'from_duration'])->default('never');
            $table->decimal('attachment_threshold', 5, 2)->nullable(); // en jours si from_duration
            $table->boolean('approval_required')->default(true);
            $table->boolean('allow_negative_balance')->default(false);
            $table->decimal('negative_limit', 5, 2)->default(0);
            $table->enum('visibility_level', ['all', 'manager', 'rh', 'direction'])->default('all');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('company_id')->references('ID')->on('entreprises_sieges')->onDelete('cascade');

            $table->index(['company_id', 'is_active']);
            $table->index(['company_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
