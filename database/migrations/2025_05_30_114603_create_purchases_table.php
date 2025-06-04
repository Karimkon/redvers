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
        Schema::create('purchases', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('motorcycle_id')->constrained()->onDelete('cascade');
        $table->enum('purchase_type', ['cash', 'hire']);
        $table->decimal('initial_deposit', 12, 2)->nullable();
        $table->decimal('total_price', 12, 2);
        $table->decimal('amount_paid', 12, 2)->default(0);
        $table->decimal('remaining_balance', 12, 2);
        $table->enum('status', ['active', 'completed', 'defaulted'])->default('active');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
