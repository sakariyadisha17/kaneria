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
        Schema::create('room_shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('old_room_id')->nullable();
            $table->unsignedBigInteger('old_bed_id')->nullable();
            $table->unsignedBigInteger('new_room_id');
            $table->unsignedBigInteger('new_bed_id');
            $table->dateTime('shifted_at')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('old_room_id')->references('id')->on('rooms')->onDelete('set null');
            $table->foreign('old_bed_id')->references('id')->on('beds')->onDelete('set null');
            $table->foreign('new_room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('new_bed_id')->references('id')->on('beds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_shifts');
    }
};
