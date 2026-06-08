<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate Home records to Pages
        $homes = DB::table('home')->get();
        foreach ($homes as $home) {
            $pageId = DB::table('pages')->insertGetId([
                'name' => $home->name,
                'blocks' => $home->blocks,
                'created_at' => $home->created_at,
                'updated_at' => $home->updated_at,
            ]);

            DB::table('routes')
                ->where('routable_type', 'App\\Models\\Home')
                ->where('routable_id', $home->id)
                ->update([
                    'routable_type' => 'App\\Models\\Page',
                    'routable_id' => $pageId,
                ]);
        }

        // Migrate Contacto records to Pages
        $contactos = DB::table('contacto')->get();
        foreach ($contactos as $contacto) {
            $pageId = DB::table('pages')->insertGetId([
                'name' => $contacto->name,
                'blocks' => $contacto->blocks,
                'created_at' => $contacto->created_at,
                'updated_at' => $contacto->updated_at,
            ]);

            DB::table('routes')
                ->where('routable_type', 'App\\Models\\Contacto')
                ->where('routable_id', $contacto->id)
                ->update([
                    'routable_type' => 'App\\Models\\Page',
                    'routable_id' => $pageId,
                ]);
        }

        // Migrate SobreMi records to Pages
        $sobreMis = DB::table('sobre_mi')->get();
        foreach ($sobreMis as $sobreMi) {
            $pageId = DB::table('pages')->insertGetId([
                'name' => $sobreMi->name,
                'blocks' => $sobreMi->blocks,
                'created_at' => $sobreMi->created_at,
                'updated_at' => $sobreMi->updated_at,
            ]);

            DB::table('routes')
                ->where('routable_type', 'App\\Models\\SobreMi')
                ->where('routable_id', $sobreMi->id)
                ->update([
                    'routable_type' => 'App\\Models\\Page',
                    'routable_id' => $pageId,
                ]);
        }

        // Drop old tables
        Schema::dropIfExists('home');
        Schema::dropIfExists('contacto');
        Schema::dropIfExists('sobre_mi');
    }

    public function down(): void
    {
        // Recreate tables (minimal — data loss expected on rollback)
        if (!Schema::hasTable('home')) {
            Schema::create('home', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->json('blocks')->nullable();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('contacto')) {
            Schema::create('contacto', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->json('blocks')->nullable();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('sobre_mi')) {
            Schema::create('sobre_mi', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->json('blocks')->nullable();
                $table->timestamps();
            });
        }
    }
};
