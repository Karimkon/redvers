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
        Schema::create('investors', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->decimal('contribution', 15, 2);
        $table->decimal('ownership_percentage', 5, 2)->nullable(); // Optional
        $table->enum('payment_method', ['bank', 'petty_cash']);
        $table->date('date');
        $table->foreignId('attachment_id')->nullable()->constrained()->nullOnDelete(); // Receipt
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
