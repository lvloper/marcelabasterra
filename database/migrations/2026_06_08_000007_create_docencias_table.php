<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docencias', function (Blueprint $table) {
            $table->id();
            $table->string('institucion')->nullable();
            $table->string('materia')->nullable();
            $table->string('catedra')->nullable();
            $table->string('nivel')->nullable();
            $table->text('descripcion')->nullable();
            $table->json('blocks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docencias');
    }
};
