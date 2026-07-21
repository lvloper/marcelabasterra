<?php

declare(strict_types=1);

namespace App\Console\Commands;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

final class ExportWordPressPosts extends Command
{
    protected $signature = 'wordpress:export-posts
        {--url=https://marcelabasterra.com.ar : URL base del WordPress}
        {--output= : Ruta del JSON de salida}
        {--limit=0 : Cantidad máxima de posts (0 exporta todos)}
        {--metadata-only : No visita las páginas públicas para extraer los módulos del tema}';

    protected $description = 'Exporta posts de WordPress combinando la REST API con los módulos HTML del tema viejo';

    public function handle(): int
    {
        $baseUrl = rtrim((string) $this->option('url'), '/');
        $limit = max(0, (int) $this->option('limit'));
        $metadataOnly = (bool) $this->option('metadata-only');
        $output = (string) ($this->option('output') ?: storage_path('app/imports/wordpress-posts.json'));

        try {
            $posts = $this->fetchPosts($baseUrl, $limit);
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf('Encontrados: %d posts.', count($posts)));

        $progress = $this->output->createProgressBar(count($posts));
        $progress->start();

        $exported = [];

        foreach ($posts as $post) {
            $exported[] = $this->normalizePost($post, $metadataOnly);
            $progress->advance();
        }

        $progress->finish();
        $this->newLine(2);

        $directory = dirname($output);
        if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            $this->error("No se pudo crear el directorio: {$directory}");

            return self::FAILURE;
        }

        $payload = [
            'source' => $baseUrl,
            'exported_at' => now()->toIso8601String(),
            'total' => count($exported),
            'posts' => $exported,
        ];

        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        file_put_contents($output, $json.PHP_EOL);

        $this->info("Exportación guardada en: {$output}");

        return self::SUCCESS;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchPosts(string $baseUrl, int $limit): array
    {
        $posts = [];
        $page = 1;

        do {
            $response = $this->client()->get("{$baseUrl}/wp-json/wp/v2/posts", [
                'page' => $page,
                'per_page' => 100,
                '_embed' => 1,
            ]);

            if ($response->failed()) {
                throw new RuntimeException("WordPress respondió HTTP {$response->status()} al consultar la página {$page}.");
            }

            $batch = $response->json();
            if (! is_array($batch)) {
                throw new RuntimeException('WordPress no devolvió una colección JSON válida.');
            }

            $posts = array_merge($posts, $batch);
            $totalPages = (int) $response->header('X-WP-TotalPages', '1');
            $page++;
        } while ($page <= $totalPages && ($limit === 0 || count($posts) < $limit));

        return $limit > 0 ? array_slice($posts, 0, $limit) : $posts;
    }

    /**
     * @param  array<string, mixed>  $post
     * @return array<string, mixed>
     */
    private function normalizePost(array $post, bool $metadataOnly): array
    {
        $embedded = is_array($post['_embedded'] ?? null) ? $post['_embedded'] : [];
        $termGroups = is_array($embedded['wp:term'] ?? null) ? $embedded['wp:term'] : [];
        $categories = $this->termNames($termGroups[0] ?? []);
        $tags = $this->termNames($termGroups[1] ?? []);
        $featuredMedia = $embedded['wp:featuredmedia'][0] ?? [];
        $link = (string) ($post['link'] ?? '');

        $page = $metadataOnly || $link === '' ? null : $this->extractPublicPage($link);

        return [
            'wordpress_id' => (int) ($post['id'] ?? 0),
            'date' => $post['date'] ?? null,
            'modified' => $post['modified'] ?? null,
            'slug' => (string) ($post['slug'] ?? ''),
            'title' => $this->decode((string) data_get($post, 'title.rendered', '')),
            'url' => $link,
            'categories' => $categories,
            'tags' => $tags,
            'featured_image' => is_array($featuredMedia) ? ($featuredMedia['source_url'] ?? null) : null,
            'api_content_html' => (string) data_get($post, 'content.rendered', ''),
            'theme_modules_html' => $page['modules_html'] ?? null,
            'plain_text' => $page['plain_text'] ?? null,
            'images' => $page['images'] ?? [],
            'documents' => $page['documents'] ?? [],
            'external_links' => $page['external_links'] ?? [],
            'scrape_error' => $page['error'] ?? null,
        ];
    }

    /**
     * @return array{modules_html?: string, plain_text?: string, images?: array<int, string>, documents?: array<int, string>, external_links?: array<int, string>, error?: string}
     */
    private function extractPublicPage(string $url): array
    {
        $response = $this->client()->get($url);

        if ($response->failed()) {
            return ['error' => "HTTP {$response->status()}"];
        }

        $previous = libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $loaded = $dom->loadHTML($response->body(), LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            return ['error' => 'HTML inválido'];
        }

        $xpath = new DOMXPath($dom);
        $modules = $xpath->query("//*[@id='main']/div[contains(concat(' ', normalize-space(@class), ' '), ' grid-module ')]");

        if ($modules === false) {
            return ['error' => 'No se pudieron localizar los módulos del tema'];
        }

        $html = '';
        $textParts = [];

        foreach ($modules as $module) {
            $html .= $dom->saveHTML($module) ?: '';

            if ($module instanceof DOMElement) {
                $text = trim(preg_replace('/\s+/u', ' ', $module->textContent) ?? '');
                if ($text !== '' && ! Str::contains($text, ['Compartir en Facebook', 'Más en '])) {
                    $textParts[] = $text;
                }
            }
        }

        $moduleQuery = "//*[@id='main']/div[contains(concat(' ', normalize-space(@class), ' '), ' grid-module ')]";
        $images = $this->attributeValues($xpath, "{$moduleQuery}//img[@src]", 'src');
        $links = $this->attributeValues($xpath, "{$moduleQuery}//a[@href]", 'href');
        $documents = array_values(array_filter($links, fn (string $link): bool => (bool) preg_match('/\.(pdf|docx?|xlsx?|pptx?)(?:\?.*)?$/i', $link)));
        $externalLinks = array_values(array_filter($links, function (string $link) use ($url, $documents): bool {
            return Str::startsWith($link, ['http://', 'https://'])
                && parse_url($link, PHP_URL_HOST) !== parse_url($url, PHP_URL_HOST)
                && ! in_array($link, $documents, true)
                && ! Str::contains($link, ['facebook.com/sharer', 'twitter.com/intent']);
        }));

        return [
            'modules_html' => trim($html),
            'plain_text' => implode("\n\n", array_values(array_unique($textParts))),
            'images' => array_values(array_unique($images)),
            'documents' => array_values(array_unique($documents)),
            'external_links' => array_values(array_unique($externalLinks)),
        ];
    }

    /**
     * @param  mixed  $terms
     * @return array<int, string>
     */
    private function termNames(mixed $terms): array
    {
        if (! is_array($terms)) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn (mixed $term): string => is_array($term) ? $this->decode((string) ($term['name'] ?? '')) : '',
            $terms,
        )));
    }

    /**
     * @return array<int, string>
     */
    private function attributeValues(DOMXPath $xpath, string $query, string $attribute): array
    {
        $nodes = $xpath->query($query);
        if ($nodes === false) {
            return [];
        }

        $values = [];
        foreach ($nodes as $node) {
            if ($node instanceof DOMElement) {
                $value = trim($node->getAttribute($attribute));
                if ($value !== '') {
                    $values[] = $value;
                }
            }
        }

        return $values;
    }

    private function decode(string $value): string
    {
        return html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function client(): PendingRequest
    {
        return Http::acceptJson()
            ->withUserAgent('MarcelaBasterraMigration/1.0')
            ->connectTimeout(10)
            ->timeout(30)
            ->retry(3, 500, throw: false);
    }
}
