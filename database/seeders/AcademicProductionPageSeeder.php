<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Redirection;
use App\Models\Route;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicProductionPageSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $page = Page::query()
                ->whereIn('name', ['Actualidad y Medios', 'Actualidad y Producción Académica'])
                ->orWhereHas('route', fn ($query) => $query->whereIn('slug', [
                    'actualidad-y-medios',
                    'actualidad-y-produccion-academica',
                ]))
                ->with('route')
                ->firstOrFail();

            $publicationsRoute = Route::query()->where('slug', 'publicaciones')->whereNull('parent_id')->first();
            $booksRoute = Route::query()->whereFullSlug('publicaciones/libros')->first();

            $routeData = static fn (?Route $route, ?string $label = null): array => [
                'btn_label' => $label,
                'route_id' => $route ? (string) $route->id : null,
                'external_url' => null,
                'file' => null,
                'download_name' => null,
                'anchor' => null,
                'new_window' => false,
            ];

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
                'name' => 'Actualidad y Producción Académica',
                'blocks' => [
                    $block('Hero', [
                        'variant' => 'editorial',
                        'profile_photo' => 'blog/imported-72.jpg',
                        'image_alt' => 'Marcela Basterra durante una exposición académica en el Salón Rojo de la Facultad de Derecho de la Universidad de Buenos Aires.',
                        'badge' => 'Archivo vivo · Producción académica e intervención pública',
                        'name' => 'Actualidad y Producción Académica',
                        'subtitle' => 'Publicaciones, conferencias, entrevistas y actividad institucional.',
                        'description' => 'Un espacio que reúne las publicaciones, conferencias, entrevistas, actividades institucionales y participaciones más relevantes de la Dra. Marcela Basterra.',
                        'indicators' => [
                            ['label' => 'Artículos y libros'],
                            ['label' => 'Prensa y entrevistas'],
                            ['label' => 'Conferencias y videos'],
                        ],
                        'featured_positions' => [],
                        'cta_primary' => $routeData($publicationsRoute, 'Explorar publicaciones'),
                        'cta_secondary' => $routeData(null),
                        'cta_tertiary' => $routeData(null),
                    ], 'inicio'),
                    $block('Search', [
                        'mode' => 'academic_catalog',
                        'title' => 'Encontrá una publicación, actividad o intervención',
                        'description' => 'Buscá por palabra clave y combiná categorías, año y tema para recorrer el archivo.',
                        'items_per_page' => 12,
                    ], 'explorar'),
                    $block('FeaturedResources', [
                        'title' => 'Contenido destacado',
                        'description' => 'La publicación o intervención más reciente del archivo, acompañada por una selección de novedades.',
                        'source_mode' => 'latest',
                        'max_items' => 4,
                        'items' => [],
                    ], 'destacado'),
                    $block('ContentList', [
                        'title' => 'Publicaciones',
                        'description' => 'Libros, artículos y documentos académicos ordenados cronológicamente y enlazados a su ficha o documento original.',
                        'variant' => 'chronological',
                        'source_mode' => 'academic_publications',
                        'institutional_positions' => [],
                        'items_per_page' => 10,
                        'items' => [],
                    ], 'publicaciones-academicas'),
                    $block('PressFeed', [
                        'title' => 'Noticias y Medios',
                        'description' => 'Noticias institucionales, apariciones en prensa, entrevistas y reconocimientos reunidos en una misma lectura editorial.',
                        'source_mode' => 'unified_news',
                        'content_types' => ['articulo', 'entrevista', 'noticia'],
                        'media' => '',
                        'selected_items' => [],
                        'max_items' => 6,
                        'show_filters' => false,
                        'show_image' => true,
                        'empty_message' => 'No hay noticias o participaciones publicadas en este momento.',
                    ], 'noticias-y-medios'),
                    $block('EventsListing', [
                        'title' => 'Conferencias y Actividades',
                        'description' => 'Congresos, jornadas, clases magistrales, exposiciones y seminarios en orden cronológico; las próximas actividades aparecen primero.',
                        'display_mode' => 'activities',
                        'include_conferences' => true,
                        'conferencias' => [],
                        'status' => 'all',
                        'event_types' => [],
                        'selected_events' => [],
                        'max_items' => 8,
                        'show_image' => false,
                        'show_description' => true,
                        'show_empty_fallback' => true,
                        'fallback_route' => [],
                        'fallback_label' => 'Próximamente se publicarán nuevas actividades.',
                    ], 'conferencias-y-actividades'),
                    $block('PublicationsHighlight', [
                        'title' => 'Biblioteca Digital',
                        'description' => 'Una selección de libros publicados, con acceso a la información editorial de cada obra.',
                        'layout' => 'library_grid',
                        'source_mode' => 'latest',
                        'libros' => [],
                        'articulos' => [],
                        'max_items' => 4,
                        'show_type_label' => true,
                        'cta_label' => 'Ver todos los libros',
                        'cta_route' => $routeData($booksRoute),
                    ], 'biblioteca-digital'),
                    $block('EventsListing', [
                        'title' => 'Videos',
                        'description' => 'Conferencias, entrevistas y clases disponibles para reproducir bajo demanda.',
                        'display_mode' => 'videos',
                        'include_conferences' => true,
                        'conferencias' => [],
                        'status' => 'all',
                        'event_types' => [],
                        'selected_events' => [],
                        'max_items' => 6,
                        'show_image' => true,
                        'show_description' => false,
                        'show_empty_fallback' => false,
                        'fallback_route' => [],
                        'fallback_label' => null,
                    ], 'videos'),
                ],
            ]);

            $page->route->update([
                'title' => 'Actualidad y Producción Académica',
                'slug' => 'actualidad-y-produccion-academica',
                'full_slug' => 'actualidad-y-produccion-academica',
                'description' => 'Publicaciones, conferencias, entrevistas, actividades institucionales y participaciones relevantes de la Dra. Marcela Basterra.',
                'image' => 'blog/imported-72.jpg',
            ]);

            Redirection::query()->updateOrCreate(
                ['old_url' => '/actualidad-y-medios'],
                [
                    'new_url' => '/actualidad-y-produccion-academica',
                    'redirect_code' => 301,
                    'is_active' => true,
                    'description' => 'Renombre de la sección Actualidad y Producción Académica.',
                ],
            );
        });
    }
}
