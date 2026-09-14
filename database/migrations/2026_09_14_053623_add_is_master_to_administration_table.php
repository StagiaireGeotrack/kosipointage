<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('administration', function (Blueprint $table) {
            $table->tinyInteger('IsMaster')->default(0)->after('IsSupervisor');
        });
    }

    public function down(): void
    {
        Schema::table('administration', function (Blueprint $table) {
            $table->dropColumn('IsMaster');
        });
    }
};
