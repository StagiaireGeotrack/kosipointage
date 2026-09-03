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
    Schema::table('leave_workflows', function (Blueprint $table) {
        $table->foreignId('leave_type_id')->nullable()->after('site_id')->constrained('leave_types')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('leave_workflows', function (Blueprint $table) {
        $table->dropForeign(['leave_type_id']);
        $table->dropColumn('leave_type_id');
    });
}
};
