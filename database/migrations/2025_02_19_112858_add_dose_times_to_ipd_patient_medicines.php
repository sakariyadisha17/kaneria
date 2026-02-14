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
        Schema::table('ipd_patient_medicine', function (Blueprint $table) {
            $table->string('time1')->nullable()->after('status');
            $table->string('time2')->nullable()->after('time1');
            $table->string('time3')->nullable()->after('time2');
            $table->string('time4')->nullable()->after('time3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_patient_medicine', function (Blueprint $table) {
            $table->dropColumn(['time1', 'time2', 'time3', 'time4']);

        });
    }
};
