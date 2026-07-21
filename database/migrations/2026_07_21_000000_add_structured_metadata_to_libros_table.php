<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('libros', function (Blueprint $table): void {
            $table->string('autoria')->nullable()->after('subtitulo');
            $table->string('area_tematica')->nullable()->after('editorial');
            $table->json('tomos')->nullable()->after('isbn');
        });
    }

    public function down(): void
    {
        Schema::table('libros', function (Blueprint $table): void {
            $table->dropColumn(['autoria', 'area_tematica', 'tomos']);
        });
    }
};
