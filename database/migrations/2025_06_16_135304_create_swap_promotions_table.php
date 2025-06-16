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
        Schema::create('swap_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained('users');
            $table->foreignId('agent_id')->constrained('users');
            $table->dateTime('starts_at'); // ✅ changed from timestamp
            $table->dateTime('ends_at');   // ✅ changed from timestamp
            $table->decimal('amount', 10, 2)->default(25000);
            $table->string('payment_reference')->nullable();
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swap_promotions');
    }
};
