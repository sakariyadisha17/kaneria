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
        Schema::create('advance_booking', function (Blueprint $table) {
            $table->id();
            $table->string('time')->nullable();
            $table->string('fullname');
            $table->string('phone');
            $table->string('address');
            $table->enum('status', ['Arrived', 'Pending','Report','Completed','Self Book'])->default('Pending');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_booking');
    }
};
