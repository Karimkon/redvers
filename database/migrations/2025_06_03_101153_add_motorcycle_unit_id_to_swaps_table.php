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
            $table->unsignedBigInteger('motorcycle_unit_id')->nullable()->after('battery_id');
            $table->foreign('motorcycle_unit_id')->references('id')->on('motorcycle_units')->onDelete('set null');
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
