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
    Schema::table('job_titles', function (Blueprint $table) {
        $table->unsignedInteger('department_id')
            ->nullable()
            ->change();

        $table->foreign('department_id')
            ->references('id')
            ->on('departments')
            ->nullOnDelete();
    });
}


public function down()
{
    Schema::table('job_titles', function (Blueprint $table) {
        $table->dropForeign(['department_id']);
        $table->dropColumn('department_id');
    });
}

};
