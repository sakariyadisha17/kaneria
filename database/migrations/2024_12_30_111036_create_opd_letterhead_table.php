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
        Schema::create('opd_letterhead', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->unsignedBigInteger('appointment_id');
            $table->foreign('appointment_id')->references('id')->on('advance_booking');
            $table->string('bp')->nullable();
            $table->string('pulse')->nullable();
            $table->string('spo2')->nullable();
            $table->string('temp')->nullable();
            $table->string('rs')->nullable();
            $table->string('cvs')->nullable();
            $table->string('ecg')->nullable();
            $table->string('rbs')->nullable();
            $table->text('report')->nullable();
            $table->text('complaint')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_letterhead');
    }
};
