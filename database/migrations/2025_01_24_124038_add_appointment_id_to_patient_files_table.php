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
        Schema::table('patient_files', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id')->after('patient_id'); // Allow nulls if appointment is optional
            $table->foreign('appointment_id')->references('id')->on('advance_booking')->onDelete('cascade'); // Add onDelete action for referential integrity
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_files', function (Blueprint $table) {
            $table->dropColumn('appointment_id'); // Drop the column
        });
    }
};
