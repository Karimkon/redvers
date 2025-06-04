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
        Schema::create('motorcycles', function (Blueprint $table) {
        $table->id();
        $table->enum('type', ['brand_new', 'retrofitted']);
        $table->decimal('cash_price', 12, 2);
        $table->decimal('hire_price_total', 12, 2);
        $table->decimal('daily_payment', 12, 2);
        $table->decimal('weekly_payment', 12, 2);
        $table->integer('duration_days'); // e.g. 730
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycles');
    }
};
