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
        Schema::create('depreciations', function (Blueprint $table) {
        $table->id();
        $table->string('asset_name');
        $table->decimal('initial_value', 15, 2);
        $table->decimal('depreciation_rate', 5, 2); // e.g. 20 = 20%
        $table->integer('lifespan_months')->nullable(); // optional
        $table->date('start_date');
        $table->text('note')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depreciations');
    }
};
