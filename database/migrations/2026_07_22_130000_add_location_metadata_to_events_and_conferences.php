<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table): void {
            $table->string('ciudad')->nullable()->after('ubicacion');
            $table->string('pais')->nullable()->after('ciudad');
            $table->string('tema')->nullable()->after('rol');
            $table->index(['fecha_inicio', 'tipo'], 'eventos_fecha_tipo_index');
            $table->index('pais', 'eventos_pais_index');
        });

        Schema::table('conferencias', function (Blueprint $table): void {
            $table->string('ciudad')->nullable()->after('ubicacion');
            $table->string('pais')->nullable()->after('ciudad');
            $table->index(['fecha', 'tipo'], 'conferencias_fecha_tipo_index');
            $table->index('pais', 'conferencias_pais_index');
        });
    }

    public function down(): void
    {
        Schema::table('conferencias', function (Blueprint $table): void {
            $table->dropIndex('conferencias_fecha_tipo_index');
            $table->dropIndex('conferencias_pais_index');
            $table->dropColumn(['ciudad', 'pais']);
        });

        Schema::table('eventos', function (Blueprint $table): void {
            $table->dropIndex('eventos_fecha_tipo_index');
            $table->dropIndex('eventos_pais_index');
            $table->dropColumn(['ciudad', 'pais', 'tema']);
        });
    }
};
