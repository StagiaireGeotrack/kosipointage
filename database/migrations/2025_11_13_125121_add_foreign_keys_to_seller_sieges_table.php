<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seller_sieges', function (Blueprint $table) {
            $table->foreign(['SellerID'])->references(['ID'])->on('administration')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['SiegeID'])->references(['ID'])->on('entreprises_sieges')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_sieges', function (Blueprint $table) {
            $table->dropForeign('seller_sieges_sellerid_foreign');
            $table->dropForeign('seller_sieges_siegeid_foreign');
        });
    }
};
