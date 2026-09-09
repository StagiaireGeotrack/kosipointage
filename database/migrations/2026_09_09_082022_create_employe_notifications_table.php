<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('employe_notifications', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('employee_id');
        $table->string('type');          // leave_pending, leave_approved, leave_rejected
        $table->string('title');
        $table->text('message');
        $table->unsignedBigInteger('leave_request_id')->nullable();
        $table->boolean('is_read')->default(false);
        $table->timestamp('read_at')->nullable();
        $table->timestamps();

        $table->foreign('employee_id')->references('ID')->on('employe')->onDelete('cascade');
        $table->foreign('leave_request_id')->references('id')->on('leave_requests')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employe_notifications');
    }
};
