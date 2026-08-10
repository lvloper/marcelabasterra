<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Status;
use App\Models\Conferencia;
use App\Models\Evento;
use App\Models\Page;
use App\Models\Route;
use App\Support\EventCatalog;
use Database\Seeders\JornadasCongresosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class JornadasCongresosTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_selects_the_next_event_and_falls_back_to_the_latest_past_event(): void
    {
        Carbon::setTestNow('2026-07-22 12:00:00');

        $past = $this->conference('Encuentro realizado', '2025-09-10');
        $next = $this->conference('Próximo congreso', '2026-09-10');

        $catalog = app(EventCatalog::class);
        $this->assertSame('Próximo congreso', $catalog->featured($catalog->all())->first()['title']);
        $this->assertSame(
            ['Encuentro realizado'],
            $catalog->all(null, collect([$past->id]))->pluck('title')->all(),
        );

        $next->route()->delete();
        $this->assertSame('Encuentro realizado', $catalog->featured($catalog->all())->first()['title']);

        $past->delete();
        Carbon::setTestNow();
    }

    public function test_seeder_builds_the_nested_page_filters_and_legacy_redirects(): void
    {
        $this->pageRoute('Actividad Académica', 'actividad-academica');
        $this->pageRoute('Jornadas y Congresos', 'jornadas-y-congresos');
        $this->event('Congreso constitucional', '2025-09-10');

        $this->seed(JornadasCongresosSeeder::class);

        $response = $this->get('/actividad-academica/jornadas-y-congresos');
        $response->assertOk()
            ->assertSee('Jornadas y Congresos')
            ->assertSee('Evento destacado')
            ->assertSee('Agenda y archivo de eventos')
            ->assertSee('Próximos')
            ->assertSee('Realizados')
            ->assertSee('Todos los países')
            ->assertSee('Tipo de actividad');

        $this->get('/jornadas-y-congresos')
            ->assertRedirect('/actividad-academica/jornadas-y-congresos');
        $this->get('/agenda')
            ->assertRedirect('/actividad-academica/jornadas-y-congresos#agenda-y-archivo');

        $this->assertDatabaseHas('routes', [
            'slug' => 'congreso-constitucional',
            'full_slug' => 'actividad-academica/jornadas-y-congresos/congreso-constitucional',
        ]);
    }

    private function event(string $title, string $date): Evento
    {
        $event = Evento::query()->create([
            'tipo' => 'congreso',
            'institucion' => 'Universidad de Buenos Aires',
            'ciudad' => 'Buenos Aires',
            'pais' => 'Argentina',
            'tema' => 'Derecho Constitucional',
            'fecha_inicio' => $date,
            'descripcion' => '<p>Participación académica.</p>',
            'destacado' => true,
        ]);
        $event->route()->create([
            'title' => $title,
            'slug' => str($title)->slug(),
            'full_slug' => 'agenda/'.str($title)->slug(),
            'status' => Status::Published,
        ]);

        return $event->fresh('route');
    }

    private function conference(string $title, string $date): Conferencia
    {
        $conference = Conferencia::query()->create([
            'tipo' => 'conferencia',
            'institucion' => 'Universidad de Buenos Aires',
            'ciudad' => 'Buenos Aires',
            'pais' => 'Argentina',
            'tematica' => 'Derecho Constitucional',
            'fecha' => $date,
            'descripcion' => '<p>Participación académica.</p>',
            'external_url' => 'https://example.com/'.str($title)->slug(),
            'link_label' => 'Ver conferencia',
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

    private function pageRoute(string $title, string $slug): Route
    {
        $page = Page::query()->create(['name' => $title, 'blocks' => []]);

        return $page->route()->create([
            'title' => $title,
            'slug' => $slug,
            'full_slug' => $slug,
            'status' => Status::Published,
        ]);
    }
}
