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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id');
            $table->string('fullname');
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('referred_by')->nullable();
            $table->decimal('charges', 10, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('complaints')->nullable();
            $table->json('discharge_medication')->nullable();
            $table->enum('payment_type',['Cash', 'Online', 'Debit'])->default('Cash');
            $table->enum('status', ['Arrived', 'Pending', 'Report', 'Completed'])->default('Arrived');
            $table->timestamps();
            
            $table->foreign('appointment_id')->references('id')->on('advance_booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
