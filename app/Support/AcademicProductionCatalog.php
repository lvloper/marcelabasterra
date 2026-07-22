<?php

namespace App\Support;

use App\Models\ArticuloAcademico;
use App\Models\Blog;
use App\Models\Conferencia;
use App\Models\Entrevista;
use App\Models\Evento;
use App\Models\Libro;
use App\Models\PublicacionMedio;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class AcademicProductionCatalog
{
    public const CATEGORIES = [
        'articulos' => 'Artículos',
        'libros' => 'Libros',
        'noticias' => 'Noticias',
        'entrevistas' => 'Entrevistas',
        'prensa' => 'Prensa',
        'conferencias' => 'Conferencias',
        'videos' => 'Videos',
    ];

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function all(): Collection
    {
        return collect()
            ->concat($this->articles())
            ->concat($this->books())
            ->concat($this->news())
            ->concat($this->press())
            ->concat($this->legacyInterviews())
            ->concat($this->conferences())
            ->concat($this->events())
            ->unique(fn (array $item): string => Str::lower($item['title'].'|'.($item['date'] ?? '')))
            ->sortByDesc(fn (array $item): string => ($item['date'] ?? '0000-00-00').'|'.$item['key'])
            ->values();
    }

    /** @return Collection<int, array<string, mixed>> */
    public function academicPublications(): Collection
    {
        return collect()
            ->concat($this->articles())
            ->concat($this->books())
            ->unique(fn (array $item): string => Str::lower($item['title'].'|'.($item['date'] ?? '')))
            ->sortByDesc(fn (array $item): string => ($item['date'] ?? '0000-00-00').'|'.$item['key'])
            ->values();
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $items
     * @return Collection<int, array<string, mixed>>
     */
    public function filter(
        Collection $items,
        ?string $search = null,
        ?string $category = null,
        ?string $year = null,
        ?string $topic = null,
    ): Collection {
        $needle = Str::lower(trim((string) $search));
        $topicNeedle = Str::lower(trim((string) $topic));

        return $items
            ->when($needle !== '', fn (Collection $records): Collection => $records->filter(
                fn (array $item): bool => Str::contains(Str::lower(implode(' ', array_filter([
                    $item['title'], $item['summary'], $item['topic'], $item['institution'], $item['medium'],
                ]))), $needle)
            ))
            ->when(filled($category) && isset(self::CATEGORIES[$category]), fn (Collection $records): Collection => $records->filter(
                fn (array $item): bool => in_array($category, $item['categories'], true)
            ))
            ->when(filled($year), fn (Collection $records): Collection => $records->where('year', $year))
            ->when($topicNeedle !== '', fn (Collection $records): Collection => $records->filter(
                fn (array $item): bool => Str::contains(Str::lower((string) $item['topic']), $topicNeedle)
            ))
            ->values();
    }

    /** @return Collection<int, string> */
    public function years(Collection $items): Collection
    {
        return $items->pluck('year')->filter()->unique()->sortDesc()->values();
    }

    /** @return Collection<int, string> */
    public function topics(Collection $items): Collection
    {
        return $items->pluck('topic')->filter()->unique()->sort(SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    private function articles(): Collection
    {
        return ArticuloAcademico::query()->with('route')->isPublished()->get()->map(fn (ArticuloAcademico $item): array => $this->item(
            key: "article:{$item->id}",
            categories: ['articulos'],
            title: $item->title,
            date: $item->fecha_publicacion?->toDateString(),
            summary: $item->resumen,
            topic: $item->tematica,
            url: $item->document_url ?: $item->url,
            actionLabel: $item->document_url ? 'Leer o descargar' : 'Leer artículo',
            external: filled($item->document_url),
        ));
    }

    private function books(): Collection
    {
        return Libro::query()->with('route')->isPublished()->get()->map(fn (Libro $item): array => $this->item(
            key: "book:{$item->id}",
            categories: ['libros'],
            title: $item->title,
            date: $item->fecha_publicacion?->toDateString(),
            summary: $item->descripcion,
            topic: $item->area_tematica,
            url: $item->url,
            actionLabel: 'Ver libro',
            image: $item->portada,
            institution: $item->editorial,
            role: $item->autoria,
        ));
    }

    private function news(): Collection
    {
        return Blog::query()->with(['route', 'tags'])->isPublished()->get()->map(fn (Blog $item): array => $this->item(
            key: "news:{$item->id}",
            categories: ['noticias'],
            title: $item->title,
            date: $item->published_at?->toDateString(),
            summary: $item->description,
            topic: $item->tags->first()?->name,
            url: $item->url,
            actionLabel: 'Leer noticia',
            image: $item->image,
            institution: 'Marcela Basterra',
        ));
    }

    private function press(): Collection
    {
        return PublicacionMedio::query()->with('route')->isPublished()->get()->map(function (PublicacionMedio $item): array {
            $category = match ($item->tipo) {
                'entrevista' => 'entrevistas',
                'noticia' => 'noticias',
                default => 'prensa',
            };

            return $this->item(
                key: "press:{$item->id}",
                categories: [$category],
                title: $item->title,
                date: $item->fecha?->toDateString(),
                summary: $item->resumen,
                topic: $item->tematica,
                url: $item->enlace_externo ?: $item->url,
                actionLabel: $category === 'entrevistas' ? 'Ver entrevista' : 'Leer más',
                image: $item->route?->image,
                medium: $item->medio,
                external: filled($item->enlace_externo),
            );
        });
    }

    private function legacyInterviews(): Collection
    {
        return Entrevista::query()->with('route')->isPublished()->get()->map(fn (Entrevista $item): array => $this->item(
            key: "interview:{$item->id}",
            categories: array_values(array_filter(['entrevistas', filled($item->video) ? 'videos' : null])),
            title: $item->title,
            date: $item->fecha?->toDateString(),
            summary: $item->descripcion,
            url: $item->video ?: $item->enlace ?: $item->url,
            actionLabel: filled($item->video) ? 'Reproducir' : 'Ver entrevista',
            image: $item->route?->image,
            medium: $item->medio,
            external: filled($item->video) || filled($item->enlace),
        ));
    }

    private function conferences(): Collection
    {
        return Conferencia::query()->with('route')->isPublished()->get()->map(fn (Conferencia $item): array => $this->item(
            key: "conference:{$item->id}",
            categories: array_values(array_filter([
                'conferencias',
                Str::contains(Str::lower((string) $item->external_url), ['youtube.com', 'youtu.be']) ? 'videos' : null,
            ])),
            title: $item->title,
            date: $item->fecha?->toDateString(),
            summary: $item->descripcion,
            topic: $item->tematica,
            url: $item->external_url ?: $item->url,
            actionLabel: Str::contains(Str::lower((string) $item->external_url), ['youtube.com', 'youtu.be']) ? 'Reproducir' : 'Ver detalles',
            image: $item->imagen,
            institution: $item->institucion,
            location: $item->ubicacion,
            external: filled($item->external_url),
        ));
    }

    private function events(): Collection
    {
        return Evento::query()->with('route')->isPublished()->get()->map(fn (Evento $item): array => $this->item(
            key: "event:{$item->id}",
            categories: array_values(array_filter(['conferencias', filled($item->video) ? 'videos' : null])),
            title: $item->title,
            date: $item->fecha_inicio?->toDateString(),
            summary: $item->descripcion,
            topic: $item->tipo,
            url: $item->video ?: $item->url,
            actionLabel: filled($item->video) ? 'Reproducir' : 'Ver detalles',
            image: $item->imagen ?: $item->route?->image,
            institution: $item->institucion,
            location: $item->ubicacion,
            external: filled($item->video),
        ));
    }

    /** @return array<string, mixed> */
    private function item(
        string $key,
        array $categories,
        string $title,
        ?string $date,
        ?string $summary,
        ?string $url,
        string $actionLabel,
        ?string $topic = null,
        ?string $image = null,
        ?string $institution = null,
        ?string $medium = null,
        ?string $location = null,
        ?string $role = null,
        bool $external = false,
    ): array {
        $primaryCategory = $categories[0];

        return [
            'key' => $key,
            'categories' => $categories,
            'category' => $primaryCategory,
            'category_label' => self::CATEGORIES[$primaryCategory],
            'title' => $title,
            'date' => $date,
            'year' => $date ? substr($date, 0, 4) : null,
            'summary' => Str::squish(strip_tags((string) $summary)),
            'topic' => $topic,
            'url' => $url,
            'action_label' => $actionLabel,
            'image' => $image,
            'institution' => $institution,
            'medium' => $medium,
            'location' => $location,
            'role' => $role,
            'external' => $external,
        ];
    }
}
