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
        Schema::create('ipd_patient_medicine', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ipd_letter_id');
            $table->foreign('ipd_letter_id')->references('id')->on('ipd_letterhead');
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->string('name')->nullable();
            $table->string('quantity')->nullable();
            $table->string('frequency')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_patient_medicine');
    }
};
