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
        Schema::table('ipd_letterhead', function (Blueprint $table) {
            $table->text('patient_note')->nullable()->after('complaint');
            $table->unsignedBigInteger('diagnosis_id')->nullable()->after('patient_note');
            $table->foreign('diagnosis_id')->references('id')->on('diagnosis')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_letterhead', function (Blueprint $table) {
            $table->dropColumn('patient_note');
            $table->dropForeign(['diagnosis_id']);
            $table->dropColumn('diagnosis_id');
        });
    }
};
