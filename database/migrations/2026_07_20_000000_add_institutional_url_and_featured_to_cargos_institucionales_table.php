<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cargos_institucionales', function (Blueprint $table) {
            $table->string('institutional_url', 2048)->nullable()->after('institucion');
            $table->boolean('featured')->default(false)->after('institutional_url');
        });
    }

    public function down(): void
    {
        Schema::table('cargos_institucionales', function (Blueprint $table) {
            $table->dropColumn(['institutional_url', 'featured']);
        });
    }
};
