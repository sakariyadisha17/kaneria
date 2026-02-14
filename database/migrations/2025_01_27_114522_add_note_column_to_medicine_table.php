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
        Schema::table('medicine', function (Blueprint $table) {
            $table->enum('note', ['જમ્યા પછી', 'જમ્યા પહેલાં'])
            ->nullable()
            ->default('જમ્યા પછી')
            ->collation('utf8mb4_unicode_ci')
            ->after('frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicine', function (Blueprint $table) {
            $table->dropColumn('note');

        });
    }
};
