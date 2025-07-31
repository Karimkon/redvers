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
        Schema::create('maintenances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('motorcycle_unit_id')->constrained()->onDelete('cascade');
        $table->text('reported_issue');
        $table->text('diagnosis')->nullable();
        $table->text('action_taken')->nullable();
        $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
        $table->foreignId('mechanic_id')->constrained('users')->onDelete('cascade');
        $table->date('repair_date')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
