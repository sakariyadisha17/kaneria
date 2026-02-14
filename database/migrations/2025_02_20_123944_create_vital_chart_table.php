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
        Schema::create('vital_chart', function (Blueprint $table) {
            $table->id();
            $table->dateTime('datetime')->nullable();
            $table->string('temp')->nullable();
            $table->string('pulse')->nullable();
            $table->string('bp')->nullable();
            $table->string('spo2')->nullable();
            $table->string('input')->nullable();
            $table->string('output')->nullable();
            $table->string('rbs')->nullable();
            $table->string('rt')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vital_chart');
    }
};
