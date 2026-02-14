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
        Schema::table('room_beds', function (Blueprint $table) {
            if (Schema::hasColumn('room_beds', 'patient_id')) {
                $table->dropForeign(['patient_id']); // Drop the foreign key constraint
                $table->dropColumn('patient_id');   // Drop the column
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_beds', function (Blueprint $table) {
            if (!Schema::hasColumn('room_beds', 'patient_id')) {
                $table->unsignedBigInteger('patient_id')->nullable();
                $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            }
        });
    }
};
