<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_titles', function (Blueprint $table) {
            if (!Schema::hasColumn('job_titles', 'department_id')) {
                $table->unsignedInteger('department_id')->nullable()->after('hierarchy_level_id');
                $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('job_titles', function (Blueprint $table) {
            if (Schema::hasColumn('job_titles', 'department_id')) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            }
        });
    }
};
