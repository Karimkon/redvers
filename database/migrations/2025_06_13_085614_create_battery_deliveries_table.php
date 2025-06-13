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
       Schema::create('battery_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_code')->nullable(); // e.g. TRUCK-KLA-001
            $table->foreignId('battery_id')->constrained()->onDelete('cascade');
            $table->foreignId('delivered_to_agent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('station_id')->constrained()->onDelete('cascade');
            $table->string('delivered_by')->nullable(); // Optional: Truck name or Driver
            $table->boolean('received')->default(false);
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battery_deliveries');
    }
};
