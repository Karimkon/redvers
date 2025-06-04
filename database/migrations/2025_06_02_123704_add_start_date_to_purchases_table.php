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
       Schema::table('purchases', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('status');
        });

        // Step 2: Backfill start_date for existing rows
        DB::table('purchases')->whereNull('start_date')->update([
            'start_date' => now(), // Or any appropriate default
        ]);

        // Step 3: Make it NOT NULL
        Schema::table('purchases', function (Blueprint $table) {
            $table->date('start_date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('start_date');
        });
    }
};
