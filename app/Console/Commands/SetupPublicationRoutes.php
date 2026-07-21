<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Status;
use App\Models\ArticuloAcademico;
use App\Models\Libro;
use App\Models\Page;
use App\Models\Route;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class SetupPublicationRoutes extends Command
{
    protected $signature = 'publications:setup-routes';

    protected $description = 'Crea los índices de Publicaciones y anida las fichas de libros y artículos';

    public function handle(): int
    {
        $parent = Route::find(config('cms-routes.publicaciones_parent_id'));
        if (! $parent) {
            $this->error('No existe la ruta padre Publicaciones.');

            return self::FAILURE;
        }

        DB::transaction(function () use ($parent): void {
            $booksRoute = $this->indexRoute($parent, 'Libros', 'libros');
            $articlesRoute = $this->indexRoute($parent, 'Artículos académicos', 'articulos-academicos');

            Libro::with('route')->each(function (Libro $book) use ($booksRoute): void {
                $book->route?->update([
                    'parent_id' => $booksRoute->id,
                    'full_slug' => "publicaciones/libros/{$book->route->slug}",
                    'status' => Status::Published,
                ]);
            });

            ArticuloAcademico::with('route')->each(function (ArticuloAcademico $article) use ($articlesRoute): void {
                $article->route?->update([
                    'parent_id' => $articlesRoute->id,
                    'full_slug' => "publicaciones/articulos-academicos/{$article->route->slug}",
                    'status' => Status::Published,
                ]);
            });

            $this->line("Libros: {$booksRoute->getFullPath()}");
            $this->line("Artículos: {$articlesRoute->getFullPath()}");
        });

        $this->info('Jerarquía de Publicaciones actualizada.');

        return self::SUCCESS;
    }

    private function indexRoute(Route $parent, string $title, string $slug): Route
    {
        $existing = Route::where('parent_id', $parent->id)->where('slug', $slug)->first();
        if ($existing) {
            $existing->update([
                'title' => $title,
                'status' => Status::Published,
                'full_slug' => "publicaciones/{$slug}",
            ]);

            return $existing;
        }

        $page = Page::create(['name' => $title, 'blocks' => []]);

        return $page->route()->create([
            'title' => $title,
            'slug' => $slug,
            'layout' => 'default',
            'status' => Status::Published,
            'parent_id' => $parent->id,
            'full_slug' => "publicaciones/{$slug}",
            'description' => $title,
        ]);
    }
}
