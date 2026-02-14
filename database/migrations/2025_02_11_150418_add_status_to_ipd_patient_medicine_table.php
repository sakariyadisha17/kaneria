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
            $table->tinyInteger('status')->default(0)->after('note'); // 0 = unchecked, 1 = checked

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_patient_medicine', function (Blueprint $table) {
            $table->dropColumn('status');

        });
    }
};
