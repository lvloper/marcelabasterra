<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conferencias', fn (Blueprint $table) => $table->renameColumn('url', 'external_url'));
    }

    public function down(): void
    {
        Schema::table('conferencias', fn (Blueprint $table) => $table->renameColumn('external_url', 'url'));
    }
};
