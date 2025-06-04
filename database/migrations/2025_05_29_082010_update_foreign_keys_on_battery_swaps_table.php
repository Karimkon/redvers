<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('battery_swaps', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['battery_id']);
            $table->dropForeign(['swap_id']);
            $table->dropForeign(['from_station_id']);
            $table->dropForeign(['to_station_id']);

            // ❗️Ensure the columns are nullable for setNull to work
            $table->unsignedBigInteger('from_station_id')->nullable()->change();
            $table->unsignedBigInteger('to_station_id')->nullable()->change();

            // Re-add foreign keys with correct actions
            $table->foreign('battery_id')->references('id')->on('batteries')->onDelete('cascade');
            $table->foreign('swap_id')->references('id')->on('swaps')->onDelete('cascade');
            $table->foreign('from_station_id')->references('id')->on('stations')->onDelete('set null');
            $table->foreign('to_station_id')->references('id')->on('stations')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('battery_swaps', function (Blueprint $table) {
            $table->dropForeign(['battery_id']);
            $table->dropForeign(['swap_id']);
            $table->dropForeign(['from_station_id']);
            $table->dropForeign(['to_station_id']);

            $table->foreign('battery_id')->references('id')->on('batteries');
            $table->foreign('swap_id')->references('id')->on('swaps');
            $table->foreign('from_station_id')->references('id')->on('stations');
            $table->foreign('to_station_id')->references('id')->on('stations');
        });
    }
};
