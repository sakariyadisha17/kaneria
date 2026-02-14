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
        Schema::table('opd_letterhead', function (Blueprint $table) {
            $table->text('ecg')->nullable()->after('cvs'); 
            $table->text('rbs')->nullable()->after('ecg'); 


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opd_letterhead', function (Blueprint $table) {
            $table->dropColumn('ecg');
            $table->dropColumn('rbs');
        });
    }
};
