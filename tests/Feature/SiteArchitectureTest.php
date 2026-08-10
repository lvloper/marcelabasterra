<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Status;
use App\Models\Conferencia;
use App\Models\InstitucionAcademica;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Route;
use Database\Seeders\SiteArchitectureSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SiteArchitectureTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_builds_the_midpoint_navigation_without_losing_legacy_paths(): void
    {
        $home = $this->pageRoute('Home', 'home', blocks: [['type' => 'Hero', 'data' => []]]);
        $about = $this->pageRoute('Sobre mí', 'sobre-mi', blocks: [
            ['type' => 'Intro', 'data' => ['blockAnchor' => 'biografia']],
            ['type' => 'ContentList', 'data' => ['blockAnchor' => 'trayectoria-en-cifras']],
            ['type' => 'ContentList', 'data' => ['blockAnchor' => 'cargos']],
            ['type' => 'MediaText', 'data' => ['title' => 'Personalidad Destacada']],
            ['type' => 'CVAccess', 'data' => ['blockAnchor' => 'cv', 'documents' => []]],
        ]);
        $contact = $this->pageRoute('Contacto', 'contacto');
        $publications = $this->pageRoute('Publicaciones', 'publicaciones');
        $books = $this->pageRoute('Libros', 'libros', $publications);
        $articles = $this->pageRoute('Artículos académicos', 'articulos-academicos', $publications);
        $activity = $this->pageRoute('Actividad académica', 'actividad-academica');
        $this->pageRoute('Jornadas y Congresos', 'jornadas-y-congresos', blocks: [], parent: $activity);
        $this->pageRoute('Actualidad y Producción Académica', 'actualidad-y-produccion-academica');

        $this->institution('Universidad de Buenos Aires', 'UBA', $activity);
        $this->institution('Pontificia Universidad Católica Argentina', 'UCA', $activity);
        $conference = $this->conference('Conferencia constitucional');

        $this->seed(SiteArchitectureSeeder::class);
        $this->seed(SiteArchitectureSeeder::class);

        foreach ([
            '/actividad-academica',
            '/actividad-academica/docencia',
            '/actividad-academica/conferencias',
            '/actividad-academica/jornadas-y-congresos',
            '/publicaciones',
            '/publicaciones/libros',
            '/publicaciones/articulos-academicos',
            '/actualidad',
            '/cv',
            '/sobre-mi',
            '/contacto',
        ] as $path) {
            $this->get($path)->assertOk();
        }

        $this->get('/actividad-docente')->assertRedirect('/actividad-academica/docencia');
        $this->get('/libros')->assertRedirect('/publicaciones/libros');
        $this->get('/articulos-especializados')->assertRedirect('/publicaciones/articulos-academicos');
        $this->get('/actualidad-y-produccion-academica')->assertRedirect('/actualidad');
        $this->get('/trayectoria')->assertRedirect('/sobre-mi#trayectoria-en-cifras');
        $this->get('/programas')->assertRedirect('/actividad-academica/docencia#programas');

        $this->assertDatabaseHas('routes', [
            'id' => $conference->route->id,
            'full_slug' => 'actividad-academica/conferencias/conferencia-constitucional',
        ]);
        $this->assertDatabaseCount('docencias', 4);

        $items = collect(Menu::query()->where('slug', 'header')->firstOrFail()->items);
        $aboutItem = $items->firstWhere('_token', 'menu-sobre-mi');
        $this->assertSame(
            ['biografia', 'trayectoria-en-cifras', 'cargos', 'reconocimientos'],
            collect($aboutItem['children'])->pluck('route.anchor')->all(),
        );
        $this->assertSame(7, $items->count());
        $this->assertSame($home->id, (int) data_get($items->firstWhere('_token', 'menu-home'), 'route.route_id'));
        $this->assertSame($about->id, (int) data_get($items->firstWhere('_token', 'menu-sobre-mi'), 'route.route_id'));
        $this->assertSame($contact->id, (int) data_get($items->firstWhere('_token', 'menu-contacto'), 'route.route_id'));
        $this->assertSame($books->id, (int) data_get($items->firstWhere('_token', 'menu-publicaciones'), 'children.0.route.route_id'));
        $this->assertSame($articles->id, (int) data_get($items->firstWhere('_token', 'menu-publicaciones'), 'children.1.route.route_id'));
    }

    private function pageRoute(string $title, string $slug, ?Route $parent = null, array $blocks = []): Route
    {
        $page = Page::query()->create(['name' => $title, 'blocks' => $blocks]);

        return $page->route()->create([
            'title' => $title,
            'slug' => $slug,
            'full_slug' => trim(($parent ? $parent->getFullPath().'/' : '').$slug, '/'),
            'parent_id' => $parent?->id,
            'layout' => 'default',
            'status' => Status::Published,
        ]);
    }

    private function institution(string $title, string $acronym, Route $parent): InstitucionAcademica
    {
        $institution = InstitucionAcademica::query()->create([
            'sigla' => $acronym,
            'pais' => 'Argentina',
            'alcance' => 'nacional',
            'destacada' => true,
            'orden' => 1,
            'blocks' => [],
        ]);
        $institution->route()->create([
            'title' => $title,
            'slug' => 'institucion-'.str($acronym)->slug(),
            'full_slug' => $parent->getFullPath().'/institucion-'.str($acronym)->slug(),
            'parent_id' => $parent->id,
            'status' => Status::Published,
        ]);

        return $institution;
    }

    private function conference(string $title): Conferencia
    {
        $conference = Conferencia::query()->create([
            'tipo' => 'conferencia',
            'institucion' => 'Universidad de Buenos Aires',
            'fecha' => '2025-09-10',
            'descripcion' => '<p>Participación académica.</p>',
            'external_url' => 'https://www.youtube.com/watch?v=example',
            'destacado' => true,
        ]);
        $conference->route()->create([
            'title' => $title,
            'slug' => str($title)->slug(),
            'full_slug' => 'agenda/'.str($title)->slug(),
            'status' => Status::Published,
        ]);

        return $conference->fresh('route');
    }
}
