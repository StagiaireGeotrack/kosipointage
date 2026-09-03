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
    Schema::table('leave_roles', function (Blueprint $table) {
        $table->foreignId('site_id')
              ->nullable()
              ->after('id')
              ->constrained('entreprises_sieges')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('leave_roles', function (Blueprint $table) {
        $table->dropForeign(['site_id']);
        $table->dropColumn('site_id');
    });
}
};
