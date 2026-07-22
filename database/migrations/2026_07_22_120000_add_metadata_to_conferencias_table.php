<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conferencias', function (Blueprint $table): void {
            $table->string('ubicacion')->nullable()->after('institucion');
            $table->string('tematica')->nullable()->after('ubicacion');
        });
    }

    public function down(): void
    {
        Schema::table('conferencias', function (Blueprint $table): void {
            $table->dropColumn(['ubicacion', 'tematica']);
        });
    }
};
