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
        Schema::create('batteries', function (Blueprint $table) {
        $table->id();
        $table->string('serial_number')->unique();
        $table->enum('status', ['in_stock', 'in_use', 'charging', 'damaged'])->default('in_stock');
        $table->foreignId('current_station_id')->nullable()->constrained('stations')->nullOnDelete();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batteries');
    }
};
