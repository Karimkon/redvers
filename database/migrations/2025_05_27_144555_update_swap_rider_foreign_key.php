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
        Schema::table('swaps', function (Blueprint $table) {
            $table->dropForeign(['rider_id']); // Remove old constraint
            $table->foreign('rider_id')->references('id')->on('users')->onDelete('cascade'); // New one
        });
    }

    public function down(): void
    {
        Schema::table('swaps', function (Blueprint $table) {
            $table->dropForeign(['rider_id']);
            $table->foreign('rider_id')->references('id')->on('riders')->onDelete('cascade');
        });
    }
};
