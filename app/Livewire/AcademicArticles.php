<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\ArticuloAcademico;
use App\Support\AcademicProductionCatalog;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class AcademicArticles extends Component
{
    public string $source = 'articles';

    public int $perPage = 12;

    public int $visible = 12;

    public function mount(int $perPage = 12, string $source = 'articles'): void
    {
        $this->source = in_array($source, ['articles', 'publications'], true) ? $source : 'articles';
        $this->perPage = min(max($perPage, 6), 24);
        $this->visible = $this->perPage;
    }

    public function loadMore(): void
    {
        $this->visible = min($this->visible + $this->perPage, $this->total);
        unset($this->items);
    }

    #[Computed]
    public function items(): Collection
    {
        if ($this->source === 'publications') {
            return app(AcademicProductionCatalog::class)
                ->academicPublications()
                ->take($this->visible)
                ->values();
        }

        return ArticuloAcademico::query()
            ->with('route')
            ->whereHas('route', fn ($route) => $route->where('status', 'published'))
            ->orderByDesc('fecha_publicacion')
            ->orderByDesc('id')
            ->limit($this->visible)
            ->get()
            ->map(fn (ArticuloAcademico $article): array => [
                'key' => "article:{$article->id}",
                'category_label' => 'Artículo',
                'title' => $article->title,
                'date' => $article->fecha_publicacion?->toDateString(),
                'year' => $article->fecha_publicacion?->format('Y'),
                'summary' => $article->resumen ? trim(strip_tags($article->resumen)) : null,
                'topic' => $article->tematica,
                'url' => $article->document_url ?: $article->url,
                'action_label' => $article->document_url ? 'Leer o descargar' : 'Leer artículo',
                'external' => filled($article->document_url),
            ]);
    }

    #[Computed]
    public function total(): int
    {
        if ($this->source === 'publications') {
            return app(AcademicProductionCatalog::class)
                ->academicPublications()
                ->count();
        }

        return ArticuloAcademico::query()
            ->whereHas('route', fn ($route) => $route->where('status', 'published'))
            ->count();
    }

    public function render(): View
    {
        return view('livewire.academic-articles');
    }
}
