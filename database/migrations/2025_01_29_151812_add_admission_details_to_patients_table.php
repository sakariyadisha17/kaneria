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
        Schema::table('patients', function (Blueprint $table) {
            $table->dateTime('admit_datetime')->nullable();
            $table->string('room_type')->nullable();
            $table->string('bed')->nullable();
            $table->string('admit_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['admit_datetime', 'room_type', 'bed', 'admit_status']);

        });
    }
};
