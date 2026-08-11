<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Status;
use App\Models\Blog;
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

    public function test_catalog_reads_the_legacy_archive_without_copying_posts_into_events(): void
    {
        $post = Blog::query()->create([
            'content' => '<p>Crónica histórica de una actividad académica.</p>',
            'description' => 'Crónica histórica de una actividad académica.',
            'published_at' => '2018-06-28 12:00:00',
        ]);
        $post->route()->create([
            'title' => 'Jornada histórica sobre acceso a la información',
            'slug' => 'jornada-historica-acceso-informacion',
            'full_slug' => 'actualidad/jornada-historica-acceso-informacion',
            'status' => Status::Published,
        ]);
        $post->attachTag('Jornadas & Congresos');

        $items = app(EventCatalog::class)->all(null, null, false, true);

        $this->assertCount(1, $items);
        $this->assertSame('legacy:'.$post->id, $items->first()['key']);
        $this->assertSame('published', $items->first()['date_kind']);
        $this->assertFalse($items->first()['is_upcoming']);
        $this->assertDatabaseCount('eventos', 0);
    }

    public function test_seeder_builds_the_nested_page_filters_and_legacy_redirects(): void
    {
        $this->pageRoute('Actividad Académica', 'actividad-academica');
        $this->pageRoute('Jornadas y Congresos', 'jornadas-y-congresos');
        $this->event('Congreso constitucional', '2025-09-10');

        $post = Blog::query()->create([
            'content' => '<p>Crónica preservada en Actualidad.</p>',
            'description' => 'Crónica preservada en Actualidad.',
            'published_at' => '2018-06-28 12:00:00',
        ]);
        $post->route()->create([
            'title' => 'Jornada histórica preservada',
            'slug' => 'jornada-historica-preservada',
            'full_slug' => 'actualidad/jornada-historica-preservada',
            'status' => Status::Published,
        ]);
        $post->attachTag('Jornadas & Congresos');

        $this->seed(JornadasCongresosSeeder::class);

        $response = $this->get('/actividad-academica/jornadas-y-congresos');
        $response->assertOk()
            ->assertSee('Jornadas y Congresos')
            ->assertSee('Evento destacado')
            ->assertSee('Agenda y archivo de eventos')
            ->assertSee('Próximos')
            ->assertSee('Realizados')
            ->assertSee('Jornada histórica preservada')
            ->assertSee('Publicado el 28 de junio de 2018')
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
