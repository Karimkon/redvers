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
        Schema::create('motorcycle_payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
        $table->date('payment_date');
        $table->decimal('amount', 12, 2);
        $table->enum('type', ['daily', 'weekly', 'lump_sum']);
        $table->string('note')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycle_payments');
    }
};
