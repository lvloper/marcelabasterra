<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Status;
use App\Models\ArticuloAcademico;
use App\Models\Route;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

final class ImportWordPressAcademicArticles extends Command
{
    protected $signature = 'wordpress:import-academic-articles
        {--url=https://marcelabasterra.com.ar/articulos-especializados/ : Página fuente}
        {--apply : Guarda los artículos; sin esta opción sólo muestra el diagnóstico}';

    protected $description = 'Importa artículos académicos y sus URLs de PDF desde el WordPress anterior';

    public function handle(): int
    {
        try {
            $articles = $this->extract((string) $this->option('url'));
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $topics = collect($articles)->countBy('topic')->sortDesc();
        $this->info(sprintf('Detectados: %d artículos, %d PDFs y %d temáticas.', count($articles), count(array_filter($articles, fn (array $article): bool => $article['pdf_url'] !== '')), $topics->count()));
        $this->table(['Temática', 'Artículos'], $topics->map(fn (int $count, string $topic): array => [$topic, $count])->values()->all());

        if (! $this->option('apply')) {
            $this->warn('Vista previa: no se modificó la base. Usá --apply para importar.');

            return self::SUCCESS;
        }

        $parent = Route::whereFullSlug('publicaciones/articulos-academicos')->first()
            ?: Route::find(config('cms-routes.publicaciones_parent_id'));
        if (! $parent) {
            $this->error('No existe la ruta padre configurada para Publicaciones.');

            return self::FAILURE;
        }

        $placeholders = ArticuloAcademico::doesntHave('route')
            ->where('resumen', 'like', 'Artículo ejemplo %')
            ->orderBy('id')
            ->get();

        DB::transaction(function () use ($articles, $parent, $placeholders): void {
            foreach ($articles as $index => $data) {
                $article = ArticuloAcademico::where('archivo_pdf_url', $data['pdf_url'])->first();
                if (! $article) {
                    $article = $placeholders->get($index) ?? new ArticuloAcademico();
                }

                $article->fill([
                    'resumen' => $data['description'],
                    'contenido' => null,
                    'fecha_publicacion' => "{$data['year']}-01-01",
                    'tematica' => $data['topic'],
                    'archivo_pdf' => null,
                    'archivo_pdf_url' => $data['pdf_url'],
                    'destacado' => false,
                ]);
                $article->save();

                $slug = $this->uniqueSlug($data['title'], $data['year'], $article);
                $routeData = [
                    'title' => $data['title'],
                    'slug' => $slug,
                    'layout' => 'default',
                    'status' => Status::Draft,
                    'parent_id' => $parent->id,
                    'full_slug' => "{$parent->full_slug}/{$slug}",
                    'description' => $data['description'],
                ];

                if ($article->route) {
                    $article->route->update($routeData);
                } else {
                    $article->route()->create($routeData);
                }
            }
        });

        $this->info(sprintf('Importación terminada: %d artículos académicos.', count($articles)));

        return self::SUCCESS;
    }

    /** @return array<int, array{title: string, description: string, year: int, topic: string, pdf_url: string}> */
    private function extract(string $url): array
    {
        $response = Http::withUserAgent('MarcelaBasterraMigration/1.0')->timeout(30)->retry(3, 500, throw: false)->get($url);
        if ($response->failed()) {
            throw new RuntimeException("La página fuente respondió HTTP {$response->status()}.");
        }

        $previous = libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $loaded = $dom->loadHTML($response->body(), LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            throw new RuntimeException('No se pudo interpretar el HTML de artículos especializados.');
        }

        $xpath = new DOMXPath($dom);
        $stacks = $xpath->query("//div[contains(concat(' ', normalize-space(@class), ' '), ' stack ')]");
        if ($stacks === false) {
            throw new RuntimeException('No se encontró la estructura de módulos del WordPress anterior.');
        }

        $topic = '';
        $articles = [];

        foreach ($stacks as $stack) {
            if (! $stack instanceof DOMElement) {
                continue;
            }

            $postModules = $xpath->query(".//div[contains(concat(' ', normalize-space(@class), ' '), ' fullpost-module ')]", $stack);
            if ($postModules === false || $postModules->length === 0) {
                $candidate = $this->clean($stack->textContent);
                if ($this->isTopic($candidate)) {
                    $topic = $candidate;
                }
                continue;
            }

            $grids = $xpath->query(".//div[contains(concat(' ', normalize-space(@class), ' '), ' fullpost-module ')]//div[contains(concat(' ', normalize-space(@class), ' '), ' grid-module ')]", $stack);
            if ($grids === false) {
                continue;
            }

            foreach ($grids as $grid) {
                if (! $grid instanceof DOMElement) {
                    continue;
                }

                $yearText = $this->nodeText($xpath, ".//div[contains(concat(' ', normalize-space(@class), ' '), ' col-10 ')]", $grid);
                $title = $this->nodesText($xpath, ".//div[contains(concat(' ', normalize-space(@class), ' '), ' col-70 ')]//div[contains(concat(' ', normalize-space(@class), ' '), ' lb-0-5 ')]//strong", $grid);
                if ($title === '') {
                    $title = $this->nodeText($xpath, ".//div[contains(concat(' ', normalize-space(@class), ' '), ' col-70 ')]//div[contains(concat(' ', normalize-space(@class), ' '), ' text-item ')]", $grid);
                }
                $description = $this->nodeText($xpath, ".//div[contains(concat(' ', normalize-space(@class), ' '), ' col-70 ')]//div[contains(concat(' ', normalize-space(@class), ' '), ' small ')]", $grid);
                $link = $xpath->query(".//a[contains(translate(@href, 'PDF', 'pdf'), '.pdf')]", $grid)?->item(0);

                if (! preg_match('/\b(19|20)\d{2}\b/', $yearText, $yearMatch) || $title === '' || ! $link instanceof DOMElement) {
                    continue;
                }

                $articles[] = [
                    'title' => $title,
                    'description' => $description,
                    'year' => (int) $yearMatch[0],
                    'topic' => $topic,
                    'pdf_url' => $link->getAttribute('href'),
                ];
            }
        }

        if ($articles === []) {
            throw new RuntimeException('La página no produjo ningún artículo importable.');
        }

        return $articles;
    }

    private function isTopic(string $text): bool
    {
        return $text !== ''
            && mb_strlen($text) <= 100
            && ! Str::contains($text, ['Índice', 'Volver arriba', 'Ver PDF']);
    }

    private function nodeText(DOMXPath $xpath, string $query, DOMElement $context): string
    {
        return $this->clean($xpath->query($query, $context)?->item(0)?->textContent ?? '');
    }

    private function nodesText(DOMXPath $xpath, string $query, DOMElement $context): string
    {
        $nodes = $xpath->query($query, $context);
        if ($nodes === false) {
            return '';
        }

        $parts = [];
        foreach ($nodes as $node) {
            $text = $this->clean($node->textContent);
            if ($text !== '') {
                $parts[] = $text;
            }
        }

        return $this->clean(implode(' ', $parts));
    }

    private function clean(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');
    }

    private function uniqueSlug(string $title, int $year, ArticuloAcademico $article): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $conflicts = fn (string $candidate): bool => Route::where('slug', $candidate)
            ->when($article->route, fn ($query) => $query->whereKeyNot($article->route->id))
            ->exists();

        if ($conflicts($slug)) {
            $slug = "{$base}-{$year}";
        }

        $suffix = 2;
        while ($conflicts($slug)) {
            $slug = "{$base}-{$year}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
