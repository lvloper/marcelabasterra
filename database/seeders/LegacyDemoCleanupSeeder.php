<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Entrevista;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class LegacyDemoCleanupSeeder extends Seeder
{
    /** @var array<int, string> */
    private const DEMO_BLOG_SLUGS = [
        'como-empezar-con-el-cms-base',
        'buenas-practicas-paginas-modulares',
        'checklist-antes-de-publicar-contenido',
        'nuevos-bloques-disponibles',
    ];

    public function run(): void
    {
        DB::transaction(function (): void {
            Entrevista::query()
                ->where('descripcion', 'like', 'Entrevista ejemplo %')
                ->where('medio', 'like', 'Medio %')
                ->whereNull('enlace')
                ->whereNull('video')
                ->whereDoesntHave('route')
                ->get()
                ->each->delete();

            Blog::query()
                ->whereNull('published_at')
                ->whereDoesntHave('tags')
                ->whereHas('route', fn ($query) => $query->whereIn('slug', self::DEMO_BLOG_SLUGS))
                ->get()
                ->each->delete();
        });
    }
}
