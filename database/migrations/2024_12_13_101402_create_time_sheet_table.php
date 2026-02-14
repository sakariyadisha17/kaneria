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
        Schema::create('time_sheet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('time_id');
            $table->time('time_sheet'); 
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->foreign('time_id')->references('id')->on('times')->onDelete('cascade'); // Adjust table name as needed

        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_sheet');
    }
};
