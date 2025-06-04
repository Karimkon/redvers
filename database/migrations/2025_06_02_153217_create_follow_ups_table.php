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
        Schema::table('follow_ups', function (Blueprint $table) {
            // First check if 'purchase_id' doesn't already exist
            if (!Schema::hasColumn('follow_ups', 'purchase_id')) {
                $table->foreignId('purchase_id')
                      ->after('id')
                      ->nullable()
                      ->constrained()
                      ->onDelete('cascade');
            }

            // Add a contact timestamp field
            if (!Schema::hasColumn('follow_ups', 'contacted_at')) {
                $table->timestamp('contacted_at')->nullable()->after('status');
            }

            // Optional: If you want to drop 'user_id', but ONLY if it exists
            if (Schema::hasColumn('follow_ups', 'user_id')) {
                $table->dropColumn('user_id'); // don't drop FK directly
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_ups', function (Blueprint $table) {
            if (Schema::hasColumn('follow_ups', 'purchase_id')) {
                $table->dropForeign(['purchase_id']);
                $table->dropColumn('purchase_id');
            }

            if (Schema::hasColumn('follow_ups', 'contacted_at')) {
                $table->dropColumn('contacted_at');
            }

            // Optionally restore user_id
            if (!Schema::hasColumn('follow_ups', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            }
        });
    }
};
