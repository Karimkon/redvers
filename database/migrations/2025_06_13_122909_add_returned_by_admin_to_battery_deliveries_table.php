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
        Schema::table('battery_deliveries', function (Blueprint $table) {
        $table->unsignedBigInteger('returned_by_admin_id')->nullable()->after('returned_to_admin');
        $table->foreign('returned_by_admin_id')->references('id')->on('users')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('battery_deliveries', function (Blueprint $table) {
            //
        });
    }
};
