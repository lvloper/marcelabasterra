<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->string('institucion')->nullable()->after('ubicacion');
            $table->string('rol')->nullable()->after('institucion');
            $table->string('modalidad')->nullable()->after('rol');
            $table->string('estado_confirmacion')->nullable()->after('modalidad');
            $table->string('imagen')->nullable()->after('estado_confirmacion');
            $table->string('video')->nullable()->after('imagen');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn([
                'institucion',
                'rol',
                'modalidad',
                'estado_confirmacion',
                'imagen',
                'video',
            ]);
        });
    }
};
