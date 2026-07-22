<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instituciones_academicas', function (Blueprint $table): void {
            $table->id();
            $table->string('sigla')->nullable();
            $table->string('pais')->nullable();
            $table->string('alcance')->default('nacional');
            $table->string('sitio_web', 2048)->nullable();
            $table->string('logo')->nullable();
            $table->boolean('destacada')->default(true);
            $table->unsignedSmallInteger('orden')->default(0);
            $table->json('blocks')->nullable();
            $table->timestamps();
        });

        Schema::table('docencias', function (Blueprint $table): void {
            $table->foreignId('institucion_academica_id')
                ->nullable()
                ->after('id')
                ->constrained('instituciones_academicas')
                ->nullOnDelete();
            $table->string('facultad')->nullable()->after('institucion');
            $table->string('programa')->nullable()->after('facultad');
            $table->string('rol')->nullable()->after('catedra');
            $table->string('modalidad')->nullable()->after('nivel');
            $table->string('periodo')->nullable()->after('modalidad');
            $table->string('enlace', 2048)->nullable()->after('periodo');
            $table->boolean('vigente')->default(true)->after('enlace');
            $table->unsignedSmallInteger('orden')->default(0)->after('vigente');
        });
    }

    public function down(): void
    {
        Schema::table('docencias', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('institucion_academica_id');
            $table->dropColumn([
                'facultad',
                'programa',
                'rol',
                'modalidad',
                'periodo',
                'enlace',
                'vigente',
                'orden',
            ]);
        });

        Schema::dropIfExists('instituciones_academicas');
    }
};
