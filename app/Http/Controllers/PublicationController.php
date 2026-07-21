<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ArticuloAcademico;
use App\Models\Libro;
use App\Models\Route as CmsRoute;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;

final class PublicationController extends Controller
{
    public function index(): View
    {
        $route = $this->route('publicaciones');
        $latestBook = Libro::with('route')->isPublished()->orderByDesc('fecha_publicacion')->first();

        return view('publications.index', [
            'route' => $route,
            'latestBook' => $latestBook,
            'booksCount' => Libro::query()->isPublished()->count(),
            'articlesCount' => ArticuloAcademico::has('route')->count(),
            'topicsCount' => ArticuloAcademico::has('route')->distinct()->count('tematica'),
        ]);
    }

    public function books(): View
    {
        $route = $this->route('publicaciones/libros');
        $books = Libro::with('route')->isPublished()->orderByDesc('fecha_publicacion')->get();

        return view('publications.books', compact('route', 'books'));
    }

    public function articles(Request $request): View
    {
        $route = $this->route('publicaciones/articulos-academicos');
        $search = trim((string) $request->string('q'));
        $year = $request->integer('year') ?: null;
        $topic = trim((string) $request->string('topic'));

        $articles = ArticuloAcademico::query()
            ->with('route')
            ->whereHas('route')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('resumen', 'like', "%{$search}%")
                        ->orWhereHas('route', fn ($route) => $route->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($year, fn ($query) => $query->whereYear('fecha_publicacion', $year))
            ->when($topic !== '', fn ($query) => $query->where('tematica', $topic))
            ->orderByDesc('fecha_publicacion')
            ->orderByDesc('id')
            ->paginate(18)
            ->withQueryString();

        $years = ArticuloAcademico::query()->whereHas('route')
            ->whereNotNull('fecha_publicacion')
            ->orderByDesc('fecha_publicacion')
            ->pluck('fecha_publicacion')
            ->map(fn ($date): int => $date->year)
            ->unique()
            ->values();
        $topics = ArticuloAcademico::query()->whereHas('route')
            ->whereNotNull('tematica')
            ->distinct()
            ->orderBy('tematica')
            ->pluck('tematica');

        return view('publications.articles', compact('route', 'articles', 'years', 'topics', 'search', 'year', 'topic'));
    }

    private function route(string $fullSlug): CmsRoute
    {
        $route = CmsRoute::whereFullSlug($fullSlug)->firstOrFail();
        ViewFacade::share('route', $route);
        ViewFacade::share('notPreview', true);
        ViewFacade::share('index', false);
        ViewFacade::share('isModal', false);
        ViewFacade::share('layout', 'default');

        return $route;
    }
}
