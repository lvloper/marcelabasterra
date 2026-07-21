<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publicaciones_medios', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('medio');
            $table->date('fecha')->nullable();
            $table->text('resumen')->nullable();
            $table->string('enlace_externo')->nullable();
            $table->string('autoria')->nullable();
            $table->string('tematica')->nullable();
            $table->boolean('destacado')->default(false);
            $table->json('blocks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publicaciones_medios');
    }
};
