<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nin')->nullable()->after('phone');
            $table->string('profile_photo')->nullable()->after('nin');
            $table->string('id_front')->nullable()->after('profile_photo');
            $table->string('id_back')->nullable()->after('id_front');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nin', 'profile_photo', 'id_front', 'id_back']);
        });
    }
};
