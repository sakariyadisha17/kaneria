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
            $table->json('diagnosis')->nullable()->after('cvs'); // Add 'diagnosis' column after 'cvs'

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opd_letterhead', function (Blueprint $table) {
            $table->dropColumn('diagnosis');

        });
    }
};
