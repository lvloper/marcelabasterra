<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var array<int, string> */
    private const RETIRED_PAGE_SLUGS = [
        'muestra-bloques',
        'novedades',
        'programas',
        'trayectoria',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('pages') || ! Schema::hasTable('routes')) {
            return;
        }

        DB::transaction(function (): void {
            $retiredRoutes = DB::table('routes')
                ->where('routable_type', 'App\\Models\\Page')
                ->whereIn('full_slug', self::RETIRED_PAGE_SLUGS)
                ->get(['id', 'routable_id']);

            if ($retiredRoutes->isNotEmpty()) {
                DB::table('routes')
                    ->whereIn('id', $retiredRoutes->pluck('id'))
                    ->update([
                        'status' => 'archived',
                        'updated_at' => now(),
                    ]);

                DB::table('pages')
                    ->whereIn('id', $retiredRoutes->pluck('routable_id')->filter())
                    ->update([
                        'blocks' => '[]',
                        'updated_at' => now(),
                    ]);
            }

            $publicationsPageId = DB::table('routes')
                ->where('routable_type', 'App\\Models\\Page')
                ->where('full_slug', 'publicaciones')
                ->value('routable_id');

            if ($publicationsPageId) {
                DB::table('pages')->where('id', $publicationsPageId)->update([
                    'blocks' => '[]',
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        // El contenido retirado era ficticio, legado redirigido o ignorado por una
        // vista especializada. Un rollback no debe volver a publicarlo.
    }
};
