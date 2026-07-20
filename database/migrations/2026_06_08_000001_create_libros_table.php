<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('subtitulo')->nullable();
            $table->string('portada')->nullable();
            $table->text('descripcion')->nullable();
            $table->date('fecha_publicacion')->nullable();
            $table->string('editorial')->nullable();
            $table->string('isbn')->nullable();
            $table->json('enlaces')->nullable();
            $table->boolean('destacado')->default(false);
            $table->json('blocks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
