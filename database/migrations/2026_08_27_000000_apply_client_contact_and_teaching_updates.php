<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const UBA_LAW_URL = 'https://www.derecho.uba.ar/';

    private const X_URL = 'https://x.com/marcelabasterra';

    private const INSTAGRAM_URL = 'https://www.instagram.com/marcelabasterra/';

    public function up(): void
    {
        DB::transaction(function (): void {
            $ucesInstitutionIds = Schema::hasTable('instituciones_academicas')
                ? DB::table('instituciones_academicas')->where('sigla', 'UCES')->pluck('id')
                : collect();
            $ucesTeachingIds = Schema::hasTable('docencias')
                ? DB::table('docencias')->whereIn('institucion_academica_id', $ucesInstitutionIds)->pluck('id')
                : collect();

            $this->removeContactChannels();
            $this->removeUcesPageReferences($ucesTeachingIds->all());

            if (Schema::hasTable('routes') && $ucesTeachingIds->isNotEmpty()) {
                DB::table('routes')
                    ->where('routable_type', 'App\\Models\\Docencia')
                    ->whereIn('routable_id', $ucesTeachingIds)
                    ->delete();
            }

            if (Schema::hasTable('docencias') && $ucesInstitutionIds->isNotEmpty()) {
                DB::table('docencias')->whereIn('institucion_academica_id', $ucesInstitutionIds)->delete();
            }

            if (Schema::hasTable('routes') && $ucesInstitutionIds->isNotEmpty()) {
                DB::table('routes')
                    ->where('routable_type', 'App\\Models\\InstitucionAcademica')
                    ->whereIn('routable_id', $ucesInstitutionIds)
                    ->delete();
            }

            if (Schema::hasTable('instituciones_academicas')) {
                DB::table('instituciones_academicas')->whereIn('id', $ucesInstitutionIds)->delete();

                $ubaId = DB::table('instituciones_academicas')->where('sigla', 'UBA')->value('id');
                if ($ubaId) {
                    DB::table('instituciones_academicas')->where('id', $ubaId)->update([
                        'sitio_web' => self::UBA_LAW_URL,
                        'updated_at' => now(),
                    ]);

                    if (Schema::hasTable('docencias')) {
                        DB::table('docencias')
                            ->where('institucion_academica_id', $ubaId)
                            ->whereIn('enlace', [
                                'https://www.uba.ar/',
                                'https://uba.ar/',
                                'https://www.cbc.uba.ar/',
                                'https://cbc.uba.ar/',
                            ])
                            ->update([
                                'enlace' => self::UBA_LAW_URL,
                                'updated_at' => now(),
                            ]);
                    }
                }
            }

            $this->refreshTeachingMetrics();
        });
    }

    public function down(): void
    {
        // Las bajas solicitadas por el cliente son cambios editoriales deliberados:
        // un rollback técnico no debe reponer datos de contacto ni contenido retirado.
    }

    private function removeContactChannels(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        $contactPageId = Schema::hasTable('routes')
            ? DB::table('routes')
                ->where('routable_type', 'App\\Models\\Page')
                ->where('full_slug', 'contacto')
                ->value('routable_id')
            : null;

        DB::table('pages')->whereNotNull('blocks')->orderBy('id')->each(function (object $page) use ($contactPageId): void {
            $blocks = json_decode((string) $page->blocks, true);
            if (! is_array($blocks)) {
                return;
            }

            $changed = false;
            $blocks = collect($blocks)
                ->reject(function (array $block) use (&$changed): bool {
                    if (($block['type'] ?? null) !== 'ContactForm') {
                        return false;
                    }

                    $changed = true;

                    return true;
                })
                ->map(function (array $block) use ($page, $contactPageId, &$changed): array {
                    if ((int) $page->id !== (int) $contactPageId || ($block['type'] ?? null) !== 'Cards') {
                        return $block;
                    }

                    $items = collect($block['data']['items'] ?? [])
                        ->reject(function (array $item): bool {
                            $title = mb_strtolower((string) ($item['title'] ?? ''));

                            return $title === 'email' || str_contains($title, 'linkedin');
                        })
                        ->map(function (array $item): array {
                            $title = mb_strtolower((string) ($item['title'] ?? ''));
                            if ($title === 'twitter' || $title === 'x' || str_contains($title, 'x (twitter)')) {
                                $item['title'] = 'X (Twitter)';
                                $item['description'] = '@marcelabasterra';
                                $item['route'] = [
                                    'btn_label' => 'Seguir',
                                    'route_id' => '0',
                                    'external_url' => self::X_URL,
                                    'file' => null,
                                    'download_name' => null,
                                    'anchor' => null,
                                    'new_window' => true,
                                ];
                            }
                            if ($title === 'instagram') {
                                $item['description'] = '@marcelabasterra';
                                $item['route'] = [
                                    'btn_label' => 'Seguir',
                                    'route_id' => '0',
                                    'external_url' => self::INSTAGRAM_URL,
                                    'file' => null,
                                    'download_name' => null,
                                    'anchor' => null,
                                    'new_window' => true,
                                ];
                            }

                            return $item;
                        })
                        ->values();

                    if (! $items->contains(fn (array $item): bool => str_contains(mb_strtolower((string) ($item['title'] ?? '')), 'twitter'))) {
                        $items->prepend([
                            'title' => 'X (Twitter)',
                            'description' => '@marcelabasterra',
                            'image' => null,
                            'route' => [
                                'btn_label' => 'Seguir',
                                'route_id' => '0',
                                'external_url' => self::X_URL,
                                'file' => null,
                                'download_name' => null,
                                'anchor' => null,
                                'new_window' => true,
                            ],
                        ]);
                    }

                    if (! $items->contains(fn (array $item): bool => mb_strtolower((string) ($item['title'] ?? '')) === 'instagram')) {
                        $items->push([
                            'title' => 'Instagram',
                            'description' => '@marcelabasterra',
                            'image' => null,
                            'route' => [
                                'btn_label' => 'Seguir',
                                'route_id' => '0',
                                'external_url' => self::INSTAGRAM_URL,
                                'file' => null,
                                'download_name' => null,
                                'anchor' => null,
                                'new_window' => true,
                            ],
                        ]);
                    }

                    $block['data']['title'] = 'Redes sociales';
                    $block['data']['items'] = $items->all();
                    $changed = true;

                    return $block;
                })
                ->values()
                ->all();

            if ($changed) {
                DB::table('pages')->where('id', $page->id)->update([
                    'blocks' => json_encode($blocks, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    /** @param array<int, int> $ucesTeachingIds */
    private function removeUcesPageReferences(array $ucesTeachingIds): void
    {
        if (! Schema::hasTable('pages') || ! Schema::hasTable('routes')) {
            return;
        }

        $pageId = DB::table('routes')
            ->where('routable_type', 'App\\Models\\Page')
            ->where('full_slug', 'actividad-academica/docencia')
            ->value('routable_id');
        $page = $pageId ? DB::table('pages')->find($pageId) : null;
        $blocks = $page ? json_decode((string) $page->blocks, true) : null;

        if (! is_array($blocks)) {
            return;
        }

        $blocks = collect($blocks)->map(function (array $block) use ($ucesTeachingIds): array {
            if (($block['type'] ?? null) === 'TeachingListing') {
                $block['data']['selected_items'] = collect($block['data']['selected_items'] ?? [])
                    ->reject(fn (mixed $id): bool => in_array((int) $id, $ucesTeachingIds, true))
                    ->values()
                    ->all();
            }

            if (($block['type'] ?? null) === 'ContentList') {
                $block['data']['items'] = collect($block['data']['items'] ?? [])
                    ->reject(function (array $item): bool {
                        $searchable = implode(' ', [
                            (string) ($item['meta'] ?? ''),
                            (string) ($item['title'] ?? ''),
                            (string) ($item['text'] ?? ''),
                            (string) ($item['url'] ?? ''),
                        ]);

                        return str_contains(mb_strtoupper($searchable), 'UCES');
                    })
                    ->values()
                    ->all();
            }

            return $block;
        })->all();

        DB::table('pages')->where('id', $pageId)->update([
            'blocks' => json_encode($blocks, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'updated_at' => now(),
        ]);
    }

    private function refreshTeachingMetrics(): void
    {
        if (! Schema::hasTable('pages') || ! Schema::hasTable('routes')) {
            return;
        }

        $pageId = DB::table('routes')
            ->where('routable_type', 'App\\Models\\Page')
            ->where('full_slug', 'actividad-academica/docencia')
            ->value('routable_id');
        $page = $pageId ? DB::table('pages')->find($pageId) : null;
        $blocks = $page ? json_decode((string) $page->blocks, true) : null;

        if (! is_array($blocks)) {
            return;
        }

        $institutionCount = Schema::hasTable('instituciones_academicas')
            ? DB::table('instituciones_academicas')->count()
            : 0;
        $historicalCount = Schema::hasTable('docencias')
            ? DB::table('docencias')->where('vigente', false)->count()
            : 0;

        $blocks = collect($blocks)->map(function (array $block) use ($institutionCount, $historicalCount): array {
            if (($block['type'] ?? null) === 'Hero') {
                $block['data']['indicators'] = collect($block['data']['indicators'] ?? [])->map(function (array $indicator) use ($institutionCount): array {
                    if (preg_match('/^\d+\s+Instituciones$/iu', (string) ($indicator['label'] ?? ''))) {
                        $indicator['label'] = "{$institutionCount} Instituciones";
                    }

                    return $indicator;
                })->all();
            }

            if (($block['type'] ?? null) === 'ContentList') {
                $block['data']['items'] = collect($block['data']['items'] ?? [])->map(function (array $item) use ($institutionCount, $historicalCount): array {
                    if (($item['title'] ?? null) === 'Instituciones') {
                        $item['meta'] = (string) $institutionCount;
                    }
                    if (($item['title'] ?? null) === 'Materias históricas recuperadas') {
                        $item['meta'] = (string) $historicalCount;
                    }

                    return $item;
                })->all();
            }

            return $block;
        })->all();

        DB::table('pages')->where('id', $pageId)->update([
            'blocks' => json_encode($blocks, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'updated_at' => now(),
        ]);
    }
};
