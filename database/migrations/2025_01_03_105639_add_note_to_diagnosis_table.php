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
        Schema::table('diagnosis', function (Blueprint $table) {
            $table->text('note')->nullable()->collation('utf8mb4_unicode_ci')->after('diat');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnosis', function (Blueprint $table) {
            $table->dropColumn('note');

        });
    }
};
