<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "Battery", "Bike", "Spare Part"
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Optional: Seed a few defaults
        \DB::table('product_categories')->insert([
            ['name' => 'Battery'],
            ['name' => 'Bike'],
            ['name' => 'Spare Part'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
