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

            // Foreign key to product
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');

            // Component or cost description (e.g. Motor, Battery cell)
            $table->text('description')->nullable();

            // Cost details
            $table->decimal('unit_cost', 15, 2); // Cost per unit
            $table->integer('quantity');         // e.g. 2 motors
            $table->decimal('total_cost', 15, 2)->storedAs('unit_cost * quantity');

            // Other metadata
            $table->date('date');
            $table->foreignId('attachment_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('c_o_g_s');
    }
};
