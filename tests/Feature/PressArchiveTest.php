<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Status;
use App\Models\Blog;
use App\Models\Page;
use App\Models\PublicacionMedio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PressArchiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_archive_searches_and_paginates_unified_news(): void
    {
        $page = Page::query()->create([
            'name' => 'Actividad',
            'blocks' => [[
                'type' => 'PressFeed',
                'data' => [
                    'blockAnchor' => 'archivo',
                    'title' => 'Noticias · Prensa · Entrevistas',
                    'description' => 'Archivo editorial de prueba.',
                    'layout' => 'archive',
                    'heading_level' => 'h1',
                    'source_mode' => 'unified_news',
                    'content_types' => ['articulo', 'entrevista', 'noticia'],
                    'max_items' => 12,
                    'show_filters' => true,
                    'show_search' => true,
                    'show_image' => false,
                    'empty_message' => 'No encontramos resultados para esta búsqueda.',
                ],
            ]],
        ]);
        $page->route()->create([
            'title' => 'Actividad',
            'slug' => 'actualidad-y-produccion-academica',
            'full_slug' => 'actualidad-y-produccion-academica',
            'status' => Status::Published,
        ]);

        foreach (range(1, 13) as $index) {
            $title = $index === 5 ? 'Cobertura constitucional especial / Clarín' : sprintf('Noticia de archivo %02d', $index);
            $post = Blog::query()->create([
                'content' => '<p>Contenido de prueba.</p>',
                'description' => "Resumen editorial {$index}.",
                'published_at' => sprintf('2024-01-%02d 12:00:00', $index),
            ]);
            $post->route()->create([
                'title' => $title,
                'slug' => str($title)->slug(),
                'full_slug' => 'novedades/'.str($title)->slug(),
                'status' => Status::Published,
            ]);
        }

        $pressItem = PublicacionMedio::query()->create([
            'tipo' => 'articulo',
            'medio' => 'Clarín',
            'fecha' => '2024-01-05',
            'resumen' => '<p>Versión de prensa de la cobertura especial.</p>',
            'enlace_externo' => 'https://example.com/prensa-especial',
        ]);
        $pressItem->route()->create([
            'title' => 'Cobertura constitucional especial',
            'slug' => 'cobertura-constitucional-especial-prensa',
            'full_slug' => 'prensa/cobertura-constitucional-especial-prensa',
            'status' => Status::Published,
        ]);

        $this->get('/actualidad-y-produccion-academica')
            ->assertOk()
            ->assertSee('Actividad')
            ->assertSee('<h1', false)
            ->assertSee('Noticias · Prensa · Entrevistas')
            ->assertSee('13 resultados')
            ->assertSee('Noticia de archivo 13')
            ->assertDontSee('Noticia de archivo 01')
            ->assertSee('Página 1 de 2')
            ->assertSee('?tipo=noticias#archivo', false)
            ->assertSee('?tipo=entrevistas#archivo', false)
            ->assertSee('?tipo=prensa#archivo', false)
            ->assertSee('noticias=2#archivo', false);

        $this->get('/actualidad-y-produccion-academica?noticias=2')
            ->assertOk()
            ->assertSee('Noticia de archivo 01')
            ->assertSee('Página 2 de 2');

        $this->get('/actualidad-y-produccion-academica?buscar=especial')
            ->assertOk()
            ->assertSee('1 resultado')
            ->assertSee('Cobertura constitucional especial')
            ->assertSee('https://example.com/prensa-especial', false)
            ->assertDontSee('Noticia de archivo 04');

        $this->get('/actualidad-y-produccion-academica?tipo=prensa')
            ->assertOk()
            ->assertSee('1 resultado')
            ->assertSee('Cobertura constitucional especial')
            ->assertSee('aria-current="page"', false)
            ->assertSee('tipo=prensa#archivo', false);
    }
}
