<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Redirection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicProductionPageSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $page = Page::query()
                ->whereIn('name', ['Actividad', 'Actualidad', 'Actualidad y Medios', 'Actualidad y Producción Académica'])
                ->orWhereHas('route', fn ($query) => $query->whereIn('slug', [
                    'actualidad',
                    'actualidad-y-medios',
                    'actualidad-y-produccion-academica',
                ]))
                ->with('route')
                ->firstOrFail();

            $block = static fn (string $type, array $data, ?string $anchor = null): array => [
                'type' => $type,
                'data' => array_merge([
                    'blockTitle' => null,
                    'blockAnchor' => $anchor,
                    'mb' => 'mb-0',
                    'mdMb' => 'md:mb-0',
                    'clases' => [],
                    'styles' => [],
                    'stylesMd' => [],
                    'hidden' => false,
                ], $data),
            ];

            $page->update([
                'name' => 'Actualidad',
                'blocks' => [
                    $block('PressFeed', [
                        'title' => 'Noticias · Prensa · Entrevistas',
                        'description' => 'Un archivo cronológico de noticias institucionales, participaciones en medios y entrevistas de Marcela Basterra.',
                        'layout' => 'archive',
                        'heading_level' => 'h1',
                        'source_mode' => 'unified_news',
                        'content_types' => ['articulo', 'entrevista', 'noticia'],
                        'media' => '',
                        'selected_items' => [],
                        'max_items' => 12,
                        'show_filters' => true,
                        'show_search' => true,
                        'show_image' => true,
                        'empty_message' => 'No encontramos resultados para esta búsqueda.',
                    ], 'archivo'),
                ],
            ]);

            $page->route->update([
                'title' => 'Actualidad',
                'slug' => 'actualidad',
                'full_slug' => 'actualidad',
                'description' => 'Noticias institucionales, participaciones en prensa y entrevistas de la Dra. Marcela Basterra, ordenadas cronológicamente.',
                'image' => 'blog/imported-72.jpg',
            ]);

            Redirection::query()->updateOrCreate(
                ['old_url' => '/actualidad-y-medios'],
                [
                    'new_url' => '/actualidad',
                    'redirect_code' => 301,
                    'is_active' => true,
                    'description' => 'Renombre de la sección Actualidad y Producción Académica.',
                ],
            );

            Redirection::query()->updateOrCreate(
                ['old_url' => '/actualidad-y-produccion-academica'],
                [
                    'new_url' => '/actualidad',
                    'redirect_code' => 301,
                    'is_active' => true,
                    'description' => 'Nombre breve para la sección de actualidad.',
                ],
            );
        });
    }
}
