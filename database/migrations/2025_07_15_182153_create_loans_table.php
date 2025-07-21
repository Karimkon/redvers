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
        Schema::create('loans', function (Blueprint $table) {
        $table->id();
        $table->string('lender');
        $table->decimal('amount', 15, 2);
        $table->decimal('interest_rate', 5, 2); // %
        $table->decimal('interest_paid', 15, 2)->default(0);
        $table->date('issued_date');
        $table->date('due_date');
        $table->enum('status', ['active', 'completed', 'defaulted'])->default('active');
        $table->foreignId('attachment_id')->nullable()->constrained()->nullOnDelete(); // Loan agreement
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
