<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articulos_academicos', function (Blueprint $table) {
            $table->id();
            $table->text('resumen')->nullable();
            $table->longText('contenido')->nullable();
            $table->date('fecha_publicacion')->nullable();
            $table->string('tematica')->nullable();
            $table->string('archivo_pdf')->nullable();
            $table->boolean('destacado')->default(false);
            $table->json('blocks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articulos_academicos');
    }
};
