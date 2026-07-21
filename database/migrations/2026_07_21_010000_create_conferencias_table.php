<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conferencias', function (Blueprint $table): void {
            $table->id();
            $table->string('tipo')->default('conferencia');
            $table->string('institucion')->nullable();
            $table->date('fecha')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->string('url', 2048);
            $table->string('link_label')->default('Ver conferencia');
            $table->boolean('destacado')->default(false);
            $table->json('blocks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conferencias');
    }
};
