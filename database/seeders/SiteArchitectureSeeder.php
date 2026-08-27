<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Conferencia;
use App\Models\Docencia;
use App\Models\InstitucionAcademica;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Redirection;
use App\Models\Route;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class SiteArchitectureSeeder extends Seeder
{
    private const LEGACY_CV_URL = 'https://marcelabasterra.com.ar/wp-content/uploads/2018/03/CV-Marcela-I-Basterra-Completo-y-actualizado-al-21-9-2017.pdf';

    public function run(): void
    {
        DB::transaction(function (): void {
            $activity = Route::query()->whereFullSlug('actividad-academica')->with('routable')->firstOrFail();
            $publications = Route::query()->whereFullSlug('publicaciones')->firstOrFail();
            $books = Route::query()->whereFullSlug('publicaciones/libros')->firstOrFail();
            $articles = Route::query()->whereFullSlug('publicaciones/articulos-academicos')->firstOrFail();
            $about = Route::query()->whereFullSlug('sobre-mi')->with('routable')->firstOrFail();
            $contact = Route::query()->whereFullSlug('contacto')->firstOrFail();
            $home = Route::query()->where('slug', 'home')->with('routable')->firstOrFail();

            $teaching = $this->pageRoute(
                slug: 'docencia',
                title: 'Docencia',
                parent: $activity,
                description: 'Actividad docente de grado, posgrado y doctorado, programas históricos y materiales académicos.',
            );
            $conferences = $this->pageRoute(
                slug: 'conferencias',
                title: 'Conferencias',
                parent: $activity,
                description: 'Conferencias, exposiciones, videos y agenda académica de la Dra. Marcela Basterra.',
            );
            $congresses = $this->pageRoute(
                slug: 'jornadas-y-congresos',
                title: 'Jornadas y Congresos',
                parent: $activity,
                description: 'Próximos eventos e historial de jornadas, congresos y encuentros académicos.',
            );
            $cv = $this->pageRoute(
                slug: 'cv',
                title: 'Currículum vitae',
                description: 'Currículum académico, institucional y profesional de la Dra. Marcela I. Basterra.',
            );
            $current = $this->actualityRoute();

            $historicalTeachingIds = $this->seedHistoricalTeaching($teaching);
            $this->moveDetailRoutes(Docencia::class, $teaching, 'Ruta docente reorganizada.');
            $this->moveDetailRoutes(InstitucionAcademica::class, $teaching, 'Institución académica reorganizada.');
            $this->moveDetailRoutes(Conferencia::class, $conferences, 'Conferencia reorganizada.');
            $this->retireDuplicatePostgraduatePage($teaching);

            $this->composeActivityPage($activity, $teaching, $conferences, $congresses, $cv);
            $this->composeTeachingPage($teaching, $historicalTeachingIds, $articles, $cv);
            $this->composeConferencesPage($conferences, $contact, $cv);
            $this->composeCongressPage($congresses, $current);
            $this->composeCvPage($cv);
            $this->updateActualityPage($current);
            $this->updateAboutPage($about);
            $this->updateHomePage($home, $cv, $publications, $current);

            $this->seedMenu(
                home: $home,
                about: $about,
                activity: $activity,
                teaching: $teaching,
                conferences: $conferences,
                congresses: $congresses,
                publications: $publications,
                books: $books,
                articles: $articles,
                current: $current,
                cv: $cv,
                contact: $contact,
            );
            $this->seedLegacyRedirects($teaching, $books, $articles, $current, $about);
        });
    }

    private function pageRoute(string $slug, string $title, ?Route $parent = null, ?string $description = null): Route
    {
        $route = Route::query()
            ->where('slug', $slug)
            ->where('routable_type', Page::class)
            ->with('routable')
            ->first();

        if (! $route) {
            $page = Page::query()->create(['name' => $title, 'blocks' => []]);
            $route = new Route;
            $route->routable()->associate($page);
        }

        $oldPath = $route->exists ? '/'.ltrim($route->getFullPath(), '/') : null;
        $newPath = trim(($parent ? $parent->getFullPath().'/' : '').$slug, '/');

        $route->fill([
            'title' => $title,
            'slug' => $slug,
            'layout' => 'default',
            'status' => Status::Published,
            'parent_id' => $parent?->id,
            'full_slug' => $newPath,
            'description' => $description,
        ])->save();

        if ($route->routable instanceof Page) {
            $route->routable->update(['name' => $title]);
        }

        if ($oldPath && $oldPath !== '/'.$newPath) {
            $this->redirect($oldPath, '/'.$newPath, 'Nueva arquitectura de navegación.');
        }

        return $route->fresh(['routable', 'parent']);
    }

    private function actualityRoute(): Route
    {
        $route = Route::query()
            ->where('routable_type', Page::class)
            ->where(function ($query): void {
                $query->whereIn('slug', ['actualidad', 'actualidad-y-medios', 'actualidad-y-produccion-academica'])
                    ->orWhereIn('full_slug', ['actualidad', 'actualidad-y-medios', 'actualidad-y-produccion-academica']);
            })
            ->with('routable')
            ->firstOrFail();
        $oldPath = '/'.ltrim($route->getFullPath(), '/');

        $route->update([
            'title' => 'Actualidad',
            'slug' => 'actualidad',
            'parent_id' => null,
            'full_slug' => 'actualidad',
            'description' => 'Noticias institucionales, prensa, entrevistas y archivo temático de Marcela Basterra.',
        ]);
        if ($route->routable instanceof Page) {
            $route->routable->update(['name' => 'Actualidad']);
        }
        if ($oldPath !== '/actualidad') {
            $this->redirect($oldPath, '/actualidad', 'Nombre breve para la sección de actualidad.');
        }

        return $route->fresh('routable');
    }

    /** @return array<int> */
    private function seedHistoricalTeaching(Route $parent): array
    {
        $uba = InstitucionAcademica::query()->where('sigla', 'UBA')->with('route')->firstOrFail();
        $uca = InstitucionAcademica::query()->where('sigla', 'UCA')->with('route')->firstOrFail();

        $definitions = [
            [$uba, 'Facultad de Derecho', 'Abogacía', 'Bases Constitucionales del Derecho Privado', 'Adjunta regular', 'Programa 2020', 'https://marcelabasterra.com.ar/wp-content/uploads/2020/08/UBA.-PROGRAMA.-Bases-constitucionales-del-derecho-privado.-2020-FINAL.docx.pdf'],
            [$uca, 'Facultad de Ciencias Sociales', 'Comunicación Periodística', 'Régimen Jurídico de la Información', 'Titular regular', 'Programa 2016', 'https://marcelabasterra.com.ar/wp-content/uploads/2016/07/Régimen-Jurídico-de-la-Informacion-BASTERRA-TARULLA-Lic-Per-mañana-y-noche-Final.pdf'],
        ];

        $ids = [];
        foreach ($definitions as $index => [$institution, $faculty, $program, $subject, $role, $period, $url]) {
            $teaching = Docencia::query()->updateOrCreate(
                [
                    'institucion_academica_id' => $institution->id,
                    'programa' => $program,
                    'materia' => $subject,
                ],
                [
                    'institucion' => $institution->title,
                    'facultad' => $faculty,
                    'catedra' => null,
                    'rol' => $role,
                    'nivel' => 'grado',
                    'modalidad' => 'presencial',
                    'periodo' => $period,
                    'enlace' => $url,
                    'vigente' => false,
                    'orden' => 100 + $index,
                    'descripcion' => 'Antecedente docente recuperado del sitio anterior. El enlace conserva el programa de la materia.',
                    'blocks' => [],
                ],
            );
            $slug = 'docencia-grado-'.Str::slug($institution->sigla.'-'.$subject);
            $detail = $teaching->route ?: new Route;
            $detail->fill([
                'title' => $subject.' · '.$institution->sigla,
                'slug' => $slug,
                'layout' => 'default',
                'status' => Status::Published,
                'parent_id' => $parent->id,
                'full_slug' => $parent->getFullPath().'/'.$slug,
                'description' => $program.' · '.$faculty.'. Antecedente histórico con programa disponible.',
            ]);
            $detail->routable()->associate($teaching);
            $detail->save();
            $ids[] = $teaching->id;
        }

        return $ids;
    }

    private function moveDetailRoutes(string $modelClass, Route $parent, string $reason): void
    {
        Route::query()->where('routable_type', $modelClass)->get()->each(function (Route $detail) use ($parent, $reason): void {
            $oldPath = '/'.ltrim($detail->getFullPath(), '/');
            $newPath = '/'.trim($parent->getFullPath().'/'.$detail->slug, '/');
            $detail->update([
                'parent_id' => $parent->id,
                'full_slug' => ltrim($newPath, '/'),
            ]);
            if ($oldPath !== $newPath) {
                $this->redirect($oldPath, $newPath, $reason);
            }
        });
    }

    private function composeActivityPage(Route $route, Route $teaching, Route $conferences, Route $congresses, Route $cv): void
    {
        $page = $route->routable;
        if (! $page instanceof Page) {
            return;
        }

        $route->update([
            'title' => 'Actividad académica',
            'description' => 'Docencia, conferencias, jornadas y congresos de la Dra. Marcela I. Basterra.',
        ]);
        $page->update([
            'name' => 'Actividad académica',
            'blocks' => [
                $this->block('Hero', [
                    'variant' => 'institutional',
                    'profile_photo' => null,
                    'image_alt' => '',
                    'badge' => 'Trayectoria académica',
                    'name' => 'Actividad académica',
                    'subtitle' => 'Docencia, investigación y participación pública.',
                    'description' => 'Un acceso ordenado a la actividad docente, las conferencias y el archivo de jornadas y congresos.',
                    'indicators' => [['label' => 'Docencia'], ['label' => 'Conferencias'], ['label' => 'Jornadas y congresos']],
                    'featured_positions' => [],
                    'cta_primary' => array_merge($this->routeAttrs($teaching), ['btn_label' => 'Ver docencia']),
                    'cta_secondary' => array_merge($this->routeAttrs($conferences), ['btn_label' => 'Ver conferencias']),
                    'cta_tertiary' => [],
                ], 'inicio'),
                $this->block('Cards', [
                    'title' => 'Explorar la actividad académica',
                    'description' => '<p>Tres recorridos complementarios, cada uno con su propio archivo y criterios editoriales.</p>',
                    'items' => [
                        ['label' => 'articulo', 'title' => 'Docencia', 'description' => 'Materias, posgrados, doctorados, programas y materiales para alumnos.', 'image' => null, 'route' => $this->routeAttrs($teaching, 'Ver docencia')],
                        ['label' => 'entrevista', 'title' => 'Conferencias', 'description' => 'Videos, exposiciones y agenda de intervenciones académicas.', 'image' => null, 'route' => $this->routeAttrs($conferences, 'Ver conferencias')],
                        ['label' => 'libro', 'title' => 'Jornadas y Congresos', 'description' => 'Próximos encuentros y archivo histórico de participaciones.', 'image' => null, 'route' => $this->routeAttrs($congresses, 'Ver jornadas y congresos')],
                    ],
                ], 'secciones'),
                $this->block('CTA', [
                    'title' => 'Trayectoria académica e institucional completa',
                    'text' => 'Consultá el currículum vitae para recorrer cargos, docencia, publicaciones y antecedentes.',
                    'button_label' => 'Ver CV',
                    'button_route' => $this->routeAttrs($cv),
                ], 'cv'),
            ],
        ]);
    }

    /** @param array<int> $historicalTeachingIds */
    private function composeTeachingPage(Route $route, array $historicalTeachingIds, Route $articles, Route $cv): void
    {
        $page = $route->routable;
        if (! $page instanceof Page) {
            return;
        }

        $page->update(['blocks' => [
            $this->block('Hero', [
                'variant' => 'listing',
                'profile_photo' => null,
                'image_alt' => '',
                'badge' => 'Actividad académica',
                'name' => 'Docencia',
                'subtitle' => 'Grado, posgrado y doctorado.',
                'description' => 'Actividad docente actual y antecedentes históricos, con acceso a programas y publicaciones especializadas.',
                'indicators' => [['label' => '13 Actividades vigentes'], ['label' => '8 Instituciones']],
                'featured_positions' => [],
                'cta_primary' => array_merge($this->routeAttrs($articles), ['btn_label' => 'Artículos para alumnos']),
                'cta_secondary' => [],
                'cta_tertiary' => [],
            ], 'actividad-docente'),
            $this->block('TeachingListing', [
                'title' => 'Materias de grado',
                'description' => 'Antecedentes recuperados del sitio anterior. Se presentan como históricos para no confundirlos con la actividad vigente.',
                'levels' => [],
                'scopes' => ['nacional'],
                'institutions' => [],
                'selected_items' => $historicalTeachingIds,
                'current_only' => false,
                'max_items' => 12,
                'show_description' => true,
                'show_institutions' => true,
                'student_resources' => [],
            ], 'materias'),
            $this->block('TeachingListing', [
                'title' => 'Posgrados y maestrías',
                'description' => 'Actividad docente vigente en instituciones nacionales e internacionales.',
                'levels' => ['posgrado', 'maestria'],
                'scopes' => ['nacional', 'internacional'],
                'institutions' => [],
                'selected_items' => [],
                'current_only' => true,
                'max_items' => 30,
                'show_description' => true,
                'show_institutions' => true,
                'student_resources' => [],
            ], 'posgrados'),
            $this->block('TeachingListing', [
                'title' => 'Doctorado',
                'description' => 'Cursos y seminarios doctorales vinculados con Derecho Constitucional, democracia y derechos fundamentales.',
                'levels' => ['doctorado'],
                'scopes' => ['nacional', 'internacional'],
                'institutions' => [],
                'selected_items' => [],
                'current_only' => true,
                'max_items' => 20,
                'show_description' => true,
                'show_institutions' => true,
                'student_resources' => [],
            ], 'doctorado'),
            $this->block('ContentList', [
                'title' => 'Programas históricos',
                'description' => 'Programas de materias recuperados del sitio anterior y conservados como documentación histórica.',
                'variant' => 'chronological',
                'source_mode' => 'manual',
                'institutional_positions' => [],
                'items_per_page' => 12,
                'items' => [
                    ['meta' => 'UBA', 'title' => 'Bases Constitucionales del Derecho Privado', 'text' => 'Abogacía · Facultad de Derecho · programa 2020.', 'url' => 'https://marcelabasterra.com.ar/wp-content/uploads/2020/08/UBA.-PROGRAMA.-Bases-constitucionales-del-derecho-privado.-2020-FINAL.docx.pdf', 'link_label' => 'Consultar programa'],
                    ['meta' => 'UCA', 'title' => 'Régimen Jurídico de la Información', 'text' => 'Comunicación Periodística · programa 2016.', 'url' => 'https://marcelabasterra.com.ar/wp-content/uploads/2016/07/Régimen-Jurídico-de-la-Informacion-BASTERRA-TARULLA-Lic-Per-mañana-y-noche-Final.pdf', 'link_label' => 'Consultar programa'],
                ],
            ], 'programas'),
            $this->block('ContentList', [
                'title' => 'Trayectoria docente',
                'description' => 'La estructura distingue actividad vigente y antecedentes documentados para conservar el archivo sin presentarlo como actualidad.',
                'variant' => 'metrics',
                'source_mode' => 'manual',
                'institutional_positions' => [],
                'items' => [
                    ['meta' => (string) Docencia::query()->where('vigente', true)->count(), 'title' => 'Actividades vigentes', 'text' => 'Registros actuales administrados desde el CMS.', 'url' => null, 'link_label' => null],
                    ['meta' => (string) InstitucionAcademica::query()->count(), 'title' => 'Instituciones', 'text' => 'Nacionales e internacionales.', 'url' => null, 'link_label' => null],
                    ['meta' => (string) count($historicalTeachingIds), 'title' => 'Materias históricas recuperadas', 'text' => 'Con sus programas originales disponibles.', 'url' => null, 'link_label' => null],
                ],
            ], 'trayectoria-docente'),
            $this->block('CTA', [
                'title' => 'Material para alumnos',
                'text' => 'Los artículos especializados se mantienen dentro de Publicaciones para evitar duplicados y conservar un único archivo académico.',
                'button_label' => 'Ver artículos académicos',
                'button_route' => $this->routeAttrs($articles),
            ], 'material-para-alumnos'),
            $this->block('CTA', [
                'title' => 'Consultar trayectoria completa',
                'text' => 'El CV reúne la actividad docente, institucional y editorial.',
                'button_label' => 'Ver CV',
                'button_route' => $this->routeAttrs($cv),
            ], 'cv'),
        ]]);
    }

    private function composeConferencesPage(Route $route, Route $contact, Route $cv): void
    {
        $page = $route->routable;
        if (! $page instanceof Page) {
            return;
        }
        $conferenceIds = Conferencia::query()->orderByDesc('fecha')->orderByDesc('id')->pluck('id')->all();

        $page->update(['blocks' => [
            $this->block('Hero', [
                'variant' => 'institutional',
                'profile_photo' => null,
                'image_alt' => '',
                'badge' => 'Actividad académica',
                'name' => 'Conferencias',
                'subtitle' => 'Intervenciones, exposiciones y conversaciones públicas.',
                'description' => 'Archivo audiovisual y agenda de participaciones académicas e institucionales.',
                'indicators' => [['label' => 'Videos'], ['label' => 'Exposiciones'], ['label' => 'Agenda']],
                'featured_positions' => [],
                'cta_primary' => array_merge($this->routeAttrs($contact), ['btn_label' => 'Invitaciones y contacto']),
                'cta_secondary' => [],
                'cta_tertiary' => [],
            ], 'inicio'),
            $this->block('EventsListing', [
                'title' => 'Videos y galería audiovisual',
                'description' => 'Conferencias y exposiciones disponibles en video o en sus fuentes institucionales.',
                'display_mode' => 'videos',
                'include_conferences' => true,
                'conferencias' => $conferenceIds,
                'status' => 'all',
                'event_types' => [],
                'selected_events' => [],
                'max_items' => 50,
                'show_image' => true,
                'show_description' => true,
                'show_filters' => false,
                'show_empty_fallback' => false,
                'fallback_route' => [],
                'fallback_label' => '',
                'empty_message' => 'Próximamente se incorporarán nuevas conferencias.',
            ], 'videos'),
            $this->block('EventsListing', [
                'title' => 'Agenda y archivo',
                'description' => 'Participaciones ordenadas cronológicamente. Los próximos compromisos aparecerán en primer lugar cuando sean confirmados.',
                'display_mode' => 'activities',
                'include_conferences' => true,
                'conferencias' => $conferenceIds,
                'status' => 'all',
                'event_types' => [],
                'selected_events' => [],
                'max_items' => 50,
                'show_image' => false,
                'show_description' => true,
                'show_filters' => true,
                'show_empty_fallback' => false,
                'fallback_route' => [],
                'fallback_label' => '',
                'empty_message' => 'No hay actividades que coincidan con los filtros seleccionados.',
            ], 'agenda'),
            $this->block('CTA', [
                'title' => 'Trayectoria de conferencias y actividad pública',
                'text' => 'Consultá el currículum vitae para acceder al detalle de antecedentes.',
                'button_label' => 'Ver CV',
                'button_route' => $this->routeAttrs($cv),
            ], 'cv'),
        ]]);
    }

    private function composeCongressPage(Route $route, Route $current): void
    {
        $page = $route->routable;
        if (! $page instanceof Page) {
            return;
        }
        $blocks = collect($page->blocks ?? [])
            ->reject(fn (array $block): bool => ($block['type'] ?? null) === 'CTA'
                && data_get($block, 'data.title') === 'Archivo histórico de jornadas y congresos')
            ->map(function (array $block): array {
                if (in_array($block['type'] ?? null, ['EventsHighlight', 'EventsListing'], true)) {
                    data_set($block, 'data.include_conferences', false);
                }
                if (($block['type'] ?? null) === 'EventsHighlight') {
                    data_set($block, 'data.blockAnchor', 'proximos');
                    data_set($block, 'data.blockTitle', 'Próximos eventos');
                }
                if (($block['type'] ?? null) === 'EventsListing') {
                    data_set($block, 'data.blockAnchor', 'historial');
                    data_set($block, 'data.blockTitle', 'Historial');
                    data_set($block, 'data.include_legacy_archive', true);
                    data_set($block, 'data.show_empty_fallback', false);
                }

                return $block;
            })->values()->all();
        $page->update(['blocks' => $blocks]);
    }

    private function composeCvPage(Route $route): void
    {
        $page = $route->routable;
        if (! $page instanceof Page) {
            return;
        }
        $page->update(['blocks' => [
            $this->block('Intro', [
                'title' => 'Currículum vitae',
                'heading_level' => 'h1',
                'summary' => '<p>Trayectoria académica, institucional y profesional de la Dra. Marcela I. Basterra.</p>',
                'photo' => null,
                'quote' => null,
                'quote_author' => null,
                'quote_role' => null,
                'cta_label' => null,
                'cta_route' => [],
            ], 'inicio'),
            $this->cvBlock('documento'),
        ]]);
    }

    private function updateActualityPage(Route $route): void
    {
        $page = $route->routable;
        if (! $page instanceof Page) {
            return;
        }
        $page->update([
            'name' => 'Actualidad',
            'blocks' => [
                $this->block('PressFeed', [
                    'title' => 'Actualidad',
                    'description' => 'Noticias, prensa y entrevistas.',
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
    }

    private function updateAboutPage(Route $route): void
    {
        $page = $route->routable;
        if (! $page instanceof Page) {
            return;
        }
        $blocks = collect($page->blocks ?? [])->map(function (array $block): array {
            if (($block['type'] ?? null) === 'MediaText') {
                data_set($block, 'data.blockAnchor', 'reconocimientos');
                data_set($block, 'data.blockTitle', 'Reconocimientos');
                foreach (['image', 'youtube_id', 'video_file', 'media_type', 'content', 'cta'] as $key) {
                    if (! array_key_exists($key, $block['data'] ?? [])) {
                        data_set($block, 'data.'.$key, $key === 'cta' ? [] : null);
                    }
                }
            }
            if (($block['type'] ?? null) === 'CVAccess') {
                data_set($block, 'data.documents', data_get($block, 'data.documents') ?: $this->cvDocuments());
            }

            return $block;
        })->values()->all();
        $page->update(['blocks' => $blocks]);
    }

    private function updateHomePage(Route $route, Route $cv, Route $publications, Route $current): void
    {
        $page = $route->routable;
        if (! $page instanceof Page) {
            return;
        }
        $blocks = collect($page->blocks ?? [])->map(function (array $block) use ($cv, $publications, $current): array {
            if (($block['type'] ?? null) === 'Hero') {
                data_set($block, 'data.cta_primary', array_merge($this->routeAttrs($cv), ['btn_label' => 'Ver CV']));
                data_set($block, 'data.cta_secondary', array_merge($this->routeAttrs($publications), ['btn_label' => 'Ver publicaciones']));
                data_set($block, 'data.cta_tertiary', array_merge($this->routeAttrs($current), ['btn_label' => 'Noticias y prensa']));
            }

            return $block;
        })->values()->all();
        $page->update(['blocks' => $blocks]);
    }

    private function seedMenu(
        Route $home,
        Route $about,
        Route $activity,
        Route $teaching,
        Route $conferences,
        Route $congresses,
        Route $publications,
        Route $books,
        Route $articles,
        Route $current,
        Route $cv,
        Route $contact,
    ): void {
        $actualityUrl = '/'.$current->getFullPath();

        Menu::query()->updateOrCreate(['slug' => 'header'], [
            'name' => 'Navegación principal',
            'items' => [
                $this->menuItem('menu-home', 'Inicio', $home),
                $this->menuItem('menu-sobre-mi', 'Sobre mí', $about, [
                    $this->menuItem('menu-biografia', 'Biografía', $about, anchor: 'biografia'),
                    $this->menuItem('menu-trayectoria', 'Trayectoria en cifras', $about, anchor: 'trayectoria-en-cifras'),
                    $this->menuItem('menu-cargos', 'Cargos', $about, anchor: 'cargos'),
                    $this->menuItem('menu-reconocimientos', 'Reconocimientos', $about, anchor: 'reconocimientos'),
                ]),
                $this->menuItem('menu-publicaciones', 'Publicaciones', $publications, [
                    $this->menuItem('menu-libros', 'Libros', $books),
                    $this->menuItem('menu-articulos', 'Artículos académicos', $articles),
                ]),
                $this->menuItem('menu-actividad', 'Actividad académica', $activity, [
                    $this->menuItem('menu-docencia', 'Docencia', $teaching),
                    $this->menuItem('menu-conferencias', 'Conferencias', $conferences),
                    $this->menuItem('menu-jornadas', 'Jornadas y Congresos', $congresses),
                ]),
                $this->menuItem('menu-actualidad', 'Actualidad', $current, [
                    $this->menuUrlItem('menu-actualidad-todas', 'Todas las publicaciones', $actualityUrl.'#archivo'),
                    $this->menuUrlItem('menu-actualidad-noticias', 'Noticias', $actualityUrl.'?tipo=noticias#archivo'),
                    $this->menuUrlItem('menu-actualidad-prensa', 'Prensa', $actualityUrl.'?tipo=prensa#archivo'),
                    $this->menuUrlItem('menu-actualidad-entrevistas', 'Entrevistas', $actualityUrl.'?tipo=entrevistas#archivo'),
                ]),
                $this->menuItem('menu-cv', 'CV', $cv, [
                    $this->menuItem('menu-cv-ver', 'Ver CV', $cv, anchor: 'documento'),
                    $this->menuUrlItem('menu-cv-descargar', 'Descargar PDF', '/storage/pdfs/cv/marcela-basterra-cv-completo-2026.pdf', true),
                ]),
                $this->menuItem('menu-contacto', 'Contacto', $contact),
            ],
        ]);
    }

    /** @param array<int, array<string, mixed>> $children */
    private function menuItem(string $token, string $label, Route $route, array $children = [], ?string $anchor = null): array
    {
        return [
            '_token' => $token,
            'label' => $label,
            'route' => $this->routeAttrs($route, anchor: $anchor),
            'children' => $children,
        ];
    }

    private function menuUrlItem(string $token, string $label, string $url, bool $newWindow = false): array
    {
        return [
            '_token' => $token,
            'label' => $label,
            'route' => [
                'external_url' => $url,
                'new_window' => $newWindow,
            ],
            'children' => [],
        ];
    }

    private function seedLegacyRedirects(Route $teaching, Route $books, Route $articles, Route $current, Route $about): void
    {
        $redirects = [
            '/home' => '/',
            '/actividad-docente' => '/'.$teaching->getFullPath(),
            '/libros' => '/'.$books->getFullPath(),
            '/articulos-especializados' => '/'.$articles->getFullPath(),
            '/actualidad-y-produccion-academica' => '/'.$current->getFullPath(),
            '/actualidad-y-medios' => '/'.$current->getFullPath(),
            '/novedades' => '/'.$current->getFullPath(),
            '/trayectoria' => '/'.$about->getFullPath().'#trayectoria-en-cifras',
            '/programas' => '/'.$teaching->getFullPath().'#programas',
        ];
        foreach ($redirects as $old => $new) {
            $this->redirect($old, $new, 'Compatibilidad con la navegación anterior.');
        }
    }

    private function retireDuplicatePostgraduatePage(Route $teaching): void
    {
        $postgraduateRoute = Route::query()
            ->where('full_slug', 'actividad-academica/posgrados')
            ->where('routable_type', Page::class)
            ->first();

        if ($postgraduateRoute) {
            $postgraduateRoute->update(['status' => Status::Archived]);
        }

        $this->redirect(
            '/actividad-academica/posgrados',
            '/'.$teaching->getFullPath().'#posgrados',
            'El contenido de posgrados se integró dentro de Docencia.',
        );
    }

    private function cvBlock(?string $anchor = null): array
    {
        return $this->block('CVAccess', [
            'title' => 'Currículum vitae completo',
            'description' => 'Versión de consulta preservada del sitio institucional anterior.',
            'documents' => $this->cvDocuments(),
        ], $anchor);
    }

    /** @return array<int, array<string, mixed>> */
    private function cvDocuments(): array
    {
        return [[
            'type' => 'full',
            'title' => 'CV completo de Marcela I. Basterra',
            'description' => 'Antecedentes académicos, institucionales y profesionales.',
            'file' => self::LEGACY_CV_URL,
            'updated_at' => '2018-03-01',
            'view_label' => 'Ver CV completo',
            'download_label' => 'Descargar PDF',
        ]];
    }

    /** @return array<string, mixed> */
    private function routeAttrs(Route $route, ?string $buttonLabel = null, ?string $anchor = null): array
    {
        return array_filter([
            'route_id' => $route->id,
            'external_url' => null,
            'anchor' => $anchor,
            'new_window' => false,
            'btn_label' => $buttonLabel,
        ], static fn ($value): bool => $value !== null);
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

    private function redirect(string $oldUrl, string $newUrl, string $description): void
    {
        if ($oldUrl === $newUrl) {
            return;
        }
        Redirection::query()->updateOrCreate(['old_url' => $oldUrl], [
            'new_url' => $newUrl,
            'redirect_code' => 301,
            'is_active' => true,
            'description' => $description,
        ]);
    }
}
