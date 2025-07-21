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
        Schema::create('c_o_g_s', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->onDelete('cascade'); // FK
        $table->text('description')->nullable(); // e.g., motor, battery cell, controller
        $table->decimal('unit_cost', 15, 2);     // Cost of one piece
        $table->integer('quantity');             // e.g., 2 motors
        $table->date('date');
        $table->decimal('total_cost', 15, 2)->storedAs('unit_cost * quantity');
        $table->foreignId('attachment_id')->nullable()->constrained()->nullOnDelete();
        $table->timestamps();
    });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_o_g_s');
    }
};
