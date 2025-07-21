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
        Schema::create('expenditures', function (Blueprint $table) {
        $table->id();
        $table->string('category'); // e.g., salaries, rent, tax, transport
        $table->string('description')->nullable();
        $table->decimal('amount', 15, 2);
        $table->enum('payment_method', ['bank', 'petty_cash']);
        $table->date('date');
        $table->foreignId('attachment_id')->nullable()->constrained()->nullOnDelete();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenditures');
    }
};
