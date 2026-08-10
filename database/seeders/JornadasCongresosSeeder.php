<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Evento;
use App\Models\Page;
use App\Models\Redirection;
use App\Models\Route;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class JornadasCongresosSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $parent = Route::query()
                ->where('slug', 'actividad-academica')
                ->where('routable_type', Page::class)
                ->firstOrFail();
            $route = Route::query()
                ->where('slug', 'jornadas-y-congresos')
                ->where('routable_type', Page::class)
                ->with('routable')
                ->firstOrFail();
            $page = $route->routable;

            if (! $page instanceof Page) {
                return;
            }

            $oldSectionPath = '/'.ltrim($route->getRawOriginal('full_slug') ?: $route->slug, '/');
            $newSectionPath = '/actividad-academica/jornadas-y-congresos';

            $route->update([
                'title' => 'Jornadas y Congresos',
                'parent_id' => $parent->id,
                'full_slug' => ltrim($newSectionPath, '/'),
                'description' => 'Participación de la Dra. Marcela Basterra en congresos, jornadas, seminarios y encuentros académicos nacionales e internacionales.',
                'image' => 'blog/imported-72.jpg',
            ]);

            $page->update([
                'name' => 'Jornadas y Congresos',
                'blocks' => [
                    $this->block('Hero', [
                        'variant' => 'portrait',
                        'profile_photo' => 'blog/imported-72.jpg',
                        'image_alt' => 'Marcela Basterra durante una exposición académica en el Salón Rojo de la Facultad de Derecho de la Universidad de Buenos Aires.',
                        'badge' => 'Actividad académica',
                        'name' => 'Jornadas y Congresos',
                        'subtitle' => 'Participación académica nacional e internacional.',
                        'description' => 'Espacio dedicado a la participación de la Dra. Marcela Basterra en congresos, jornadas, seminarios y encuentros académicos nacionales e internacionales.',
                        'indicators' => [
                            ['label' => 'Congresos'],
                            ['label' => 'Jornadas'],
                            ['label' => 'Seminarios y conferencias'],
                        ],
                        'featured_positions' => [],
                        'cta_primary' => [],
                        'cta_secondary' => [],
                        'cta_tertiary' => [],
                    ], 'inicio'),
                    $this->block('EventsHighlight', [
                        'title' => 'Evento destacado',
                        'description' => 'La próxima participación confirmada o, cuando no hay actividades programadas, el encuentro más reciente del archivo.',
                        'source_mode' => 'automatic',
                        'eventos' => [],
                        'conferencias' => [],
                        'include_conferences' => false,
                        'max_items' => 1,
                        'show_past' => true,
                    ], 'destacado'),
                    $this->block('EventsListing', [
                        'title' => 'Agenda y archivo de eventos',
                        'description' => 'Próximas actividades e historial reunidos en un único listado cronológico para recorrer por año, país y tipo de participación.',
                        'display_mode' => 'activities',
                        'include_conferences' => false,
                        'conferencias' => [],
                        'status' => 'all',
                        'event_types' => [],
                        'selected_events' => [],
                        'max_items' => 50,
                        'show_image' => false,
                        'show_description' => true,
                        'show_filters' => true,
                        'show_empty_fallback' => true,
                        'fallback_route' => [],
                        'fallback_label' => 'Próximamente se publicarán nuevas actividades.',
                        'empty_message' => 'No hay actividades que coincidan con los filtros seleccionados.',
                    ], 'agenda-y-archivo'),
                ],
            ]);

            $this->moveDetailRoutes(Evento::class, $route, $newSectionPath);

            if ($oldSectionPath !== $newSectionPath) {
                $this->redirect($oldSectionPath, $newSectionPath, 'Nueva jerarquía de Jornadas y Congresos.');
            }
            $this->redirect('/agenda', $newSectionPath.'#agenda-y-archivo', 'Agenda unificada dentro de Jornadas y Congresos.');
        });
    }

    private function moveDetailRoutes(string $modelClass, Route $parent, string $sectionPath): void
    {
        Route::query()
            ->where('routable_type', $modelClass)
            ->get()
            ->each(function (Route $detail) use ($parent, $sectionPath): void {
                $oldPath = '/'.ltrim($detail->getRawOriginal('full_slug') ?: $detail->getFullPath(), '/');
                $newPath = rtrim($sectionPath, '/').'/'.$detail->slug;

                $detail->update([
                    'parent_id' => $parent->id,
                    'full_slug' => ltrim($newPath, '/'),
                ]);

                if ($oldPath !== $newPath) {
                    $this->redirect($oldPath, $newPath, 'Ruta histórica de actividad académica.');
                }
            });
    }

    private function redirect(string $oldUrl, string $newUrl, string $description): void
    {
        Redirection::query()->updateOrCreate(
            ['old_url' => $oldUrl],
            [
                'new_url' => $newUrl,
                'redirect_code' => 301,
                'is_active' => true,
                'description' => $description,
            ],
        );
    }

    /** @param array<string, mixed> $data */
    private function block(string $type, array $data, ?string $anchor = null): array
    {
        return [
            'type' => $type,
            'data' => array_merge([
                'blockTitle' => $anchor,
                'blockAnchor' => $anchor,
                'mb' => 'mb-0',
                'mdMb' => 'md:mb-0',
                'clases' => [],
                'styles' => [],
                'stylesMd' => [],
                'hidden' => false,
            ], $data),
        ];
    }
}
