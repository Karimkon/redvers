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
            $table->unsignedBigInteger('battery_id')->nullable()->after('agent_id');
            $table->foreign('battery_id')->references('id')->on('batteries')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('swaps', function (Blueprint $table) {
            //
        });
    }
};
