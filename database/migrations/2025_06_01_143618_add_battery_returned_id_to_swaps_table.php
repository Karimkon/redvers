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
            $table->foreignId('battery_returned_id')->nullable()->after('agent_id')->constrained('batteries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('swaps', function (Blueprint $table) {
            $table->dropForeign(['battery_returned_id']);
            $table->dropColumn('battery_returned_id');
        });

    }
};
