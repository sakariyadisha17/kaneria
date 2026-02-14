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
        Schema::table('vital_chart', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_id')->after('id');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade'); // Adjust table name as needed

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vital_chart', function (Blueprint $table) {
            $table->dropColumn('patient_id');

        });
    }
};
