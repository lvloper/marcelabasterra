<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articulos_academicos', function (Blueprint $table): void {
            $table->string('archivo_pdf_url')->nullable()->after('archivo_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('articulos_academicos', function (Blueprint $table): void {
            $table->dropColumn('archivo_pdf_url');
        });
    }
};
