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
       Schema::create('motorcycle_units', function (Blueprint $table) {
        $table->id();
        $table->foreignId('motorcycle_id')->constrained()->onDelete('cascade'); // Link to plan
        $table->string('number_plate')->unique();
        $table->enum('status', ['available', 'assigned', 'damaged'])->default('available');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycle_units');
    }
};
