@php
    use App\Models\PublicacionMedio;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $sourceMode = $source_mode ?? 'media_publications';
    $layoutMode = in_array(($layout ?? 'featured'), ['featured', 'archive'], true) ? ($layout ?? 'featured') : 'featured';
    $headingTag = ($heading_level ?? 'h2') === 'h1' ? 'h1' : 'h2';
    $itemHeadingTag = $headingTag === 'h1' ? 'h2' : 'h3';
    $limit = min(max((int) ($max_items ?? 6), 1), 24);
    $searchTerm = ($layoutMode === 'archive' && ($show_search ?? false))
        ? trim((string) request()->query('buscar', ''))
        : '';
    $requestedType = trim((string) request()->query('tipo', ''));
    $requestedMedia = trim((string) request()->query('medio', ''));
    $requestedTopic = trim((string) request()->query('tema', ''));
    $configuredMedia = collect(explode(',', (string) ($media ?? '')))->map(fn (string $item): string => trim($item))->filter()->values();
    $typeLabels = [
        'articulo' => 'Artículo en medios',
        'entrevista' => 'Entrevista',
        'noticia' => 'Noticia',
        'prensa' => 'Prensa',
        'entrevistas' => 'Entrevista',
        'noticias' => 'Noticia institucional',
    ];

    if ($sourceMode === 'unified_news') {
        $configuredTypes = ['noticias', 'entrevistas', 'prensa'];
        $catalog = app(\App\Support\AcademicProductionCatalog::class);
        $catalogItems = $catalog->newsAndMedia()->filter(fn (array $item): bool => in_array($item['category'], $configuredTypes, true));
        $availableMedia = $catalogItems->map(fn (array $item) => $item['medium'] ?: $item['institution'])->filter()->unique()->sort()->values();
        $availableTopics = $catalog->topics($catalogItems);
        if (($show_filters ?? false) && in_array($requestedType, $configuredTypes, true)) {
            $catalogItems = $catalogItems->where('category', $requestedType);
        }
        if ($configuredMedia->isNotEmpty()) {
            $catalogItems = $catalogItems->filter(fn (array $item): bool => in_array($item['medium'] ?: $item['institution'], $configuredMedia->all(), true));
        }
        if (($show_filters ?? false) && $requestedMedia !== '') {
            $catalogItems = $catalogItems->filter(fn (array $item): bool => ($item['medium'] ?: $item['institution']) === $requestedMedia);
        }
        if (($show_filters ?? false) && $requestedTopic !== '') {
            $catalogItems = $catalogItems->filter(fn (array $item): bool => $item['topic'] === $requestedTopic);
        }
        $items = $catalogItems->map(fn (array $item): array => [
            'type' => $item['category'],
            'title' => $item['title'],
            'medium' => $item['medium'] ?: $item['institution'],
            'date' => $item['date'] ? Carbon::parse($item['date']) : null,
            'summary' => $item['summary'],
            'topic' => $item['topic'],
            'image' => $item['image'],
            'url' => $item['url'],
            'external' => $item['external'],
        ])->values();
    } else {
        $allowedTypes = ['articulo', 'entrevista', 'noticia'];
        $configuredTypes = array_values(array_intersect($content_types ?? $allowedTypes, $allowedTypes)) ?: $allowedTypes;
        $activeTypes = ($show_filters ?? false) && in_array($requestedType, $configuredTypes, true) ? [$requestedType] : $configuredTypes;
        $selectedIds = collect($selected_items ?? [])->filter()->map(fn ($id): int => (int) $id)->values();
        $records = $selectedIds->isNotEmpty()
            ? PublicacionMedio::with('route')->whereIn('id', $selectedIds)->get()->sortBy(fn (PublicacionMedio $record): int => $selectedIds->search($record->id))
            : PublicacionMedio::with('route')->whereIn('tipo', $activeTypes)
                ->when($configuredMedia->isNotEmpty(), fn ($query) => $query->whereIn('medio', $configuredMedia->all()))
                ->when(($show_filters ?? false) && $requestedMedia !== '', fn ($query) => $query->where('medio', $requestedMedia))
                ->orderByDesc('fecha')
                ->when($layoutMode !== 'archive', fn ($query) => $query->limit($limit))
                ->get();
        $items = $records->filter(fn (PublicacionMedio $record): bool => $record->route?->status->value === 'published')->map(fn (PublicacionMedio $record): array => [
            'type' => $record->tipo,
            'title' => $record->title,
            'medium' => $record->medio,
            'date' => $record->fecha,
            'summary' => Str::squish(strip_tags((string) $record->resumen)),
            'topic' => $record->tematica,
            'image' => $record->route?->image,
            'url' => $record->enlace_externo ?: $record->url,
            'external' => filled($record->enlace_externo),
        ])->values();
        $availableMedia = $configuredMedia->isNotEmpty() ? $configuredMedia : $records->pluck('medio')->filter()->unique()->sort()->values();
        $availableTopics = $records->pluck('tematica')->filter()->unique()->sort()->values();
    }

    if ($searchTerm !== '') {
        $needle = Str::lower($searchTerm);
        $items = $items->filter(fn (array $item): bool => Str::contains(
            Str::lower(implode(' ', array_filter([
                $item['title'],
                $item['summary'],
                $item['medium'],
                $item['topic'],
                $typeLabels[$item['type']] ?? $item['type'],
            ]))),
            $needle,
        ))->values();
    }

    $totalItems = $items->count();
    $archivePaginator = null;

    if ($layoutMode === 'archive') {
        $pageName = 'noticias';
        $lastPage = max(1, (int) ceil($totalItems / $limit));
        $currentPage = min(max((int) request()->query($pageName, 1), 1), $lastPage);
        $pageItems = $items->forPage($currentPage, $limit)->values();
        $archivePaginator = new LengthAwarePaginator(
            $pageItems,
            $totalItems,
            $limit,
            $currentPage,
            ['path' => url()->current(), 'pageName' => $pageName],
        );
        $archivePaginator->appends(request()->except($pageName))->fragment($id ?? 'archivo');
        $items = collect($archivePaginator->items());
    } else {
        $items = $items->take($limit)->values();
    }

    $imageUrl = static fn (?string $image): ?string => blank($image)
        ? null
        : (filter_var($image, FILTER_VALIDATE_URL) ? $image : Storage::url($image));
    $hasActiveFilters = $searchTerm !== '' || $requestedType !== '' || $requestedMedia !== '' || $requestedTopic !== '';
@endphp

@if ($layoutMode === 'archive')
    <x-block class="bg-white py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            <header class="grid gap-8 border-b border-primary pb-12 lg:grid-cols-12 lg:gap-12 lg:pb-16">
                <div class="lg:col-span-8">
                    <p class="mb-4 font-source text-2xl text-gray">Archivo editorial</p>
                    @if ($title ?? null)
                        <{{ $headingTag }} class="max-w-[15ch] font-sans text-[clamp(3rem,7vw,6.75rem)] font-normal leading-[0.92] tracking-[-0.04em] text-primary">
                            {{ $title }}
                        </{{ $headingTag }}>
                    @endif
                </div>
                @if ($description ?? null)
                    <div class="self-end lg:col-span-4">
                        <span class="mb-6 block h-px w-20 bg-accent" aria-hidden="true"></span>
                        <p class="max-w-[44ch] font-source text-xl leading-relaxed text-gray">{{ $description }}</p>
                    </div>
                @endif
            </header>

            @if (($show_search ?? false) || ($show_filters ?? false))
                <form action="{{ url()->current() }}#{{ $id ?? 'archivo' }}" method="get" class="mt-10 grid gap-4 border-b border-gray-2 pb-10 md:grid-cols-2 xl:grid-cols-4" aria-label="Filtrar noticias, prensa y entrevistas">
                    @if ($show_search ?? false)
                        <label for="press-search-{{ $id ?? 'archive' }}" class="grid gap-2 font-body text-[15px] font-semibold text-primary md:col-span-2 xl:col-span-4">
                            Buscar en el archivo
                            <input
                                id="press-search-{{ $id ?? 'archive' }}"
                                type="search"
                                name="buscar"
                                value="{{ $searchTerm }}"
                                placeholder="Título, medio, tema o palabra clave"
                                class="min-h-12 w-full border border-gray-2 bg-white px-4 font-body text-[16px] font-normal text-primary placeholder:text-gray focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                            >
                        </label>
                    @endif
                    @if ($show_filters ?? false)
                        <label class="grid gap-2 font-body text-[15px] font-semibold text-primary">
                            Tipo
                            <select name="tipo" class="min-h-12 border border-gray-2 bg-white px-3 font-body text-[16px] font-normal text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">
                                <option value="">Noticias, prensa y entrevistas</option>
                                @foreach ($configuredTypes as $type)
                                    <option value="{{ $type }}" @selected($requestedType === $type)>{{ $typeLabels[$type] }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="grid gap-2 font-body text-[15px] font-semibold text-primary">
                            Tema histórico
                            <select name="tema" class="min-h-12 border border-gray-2 bg-white px-3 font-body text-[16px] font-normal text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">
                                <option value="">Todos los temas</option>
                                @foreach ($availableTopics as $topic)
                                    <option value="{{ $topic }}" @selected($requestedTopic === $topic)>{{ $topic }}</option>
                                @endforeach
                            </select>
                        </label>
                    @endif
                    <div class="flex items-end gap-3">
                        <button type="submit" class="inline-flex min-h-12 flex-1 items-center justify-center border border-primary bg-primary px-7 font-body text-[16px] font-semibold text-white transition-colors duration-200 hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                            Aplicar
                        </button>
                    </div>
                    @if ($hasActiveFilters)
                        <div class="flex items-end">
                            <a href="{{ url()->current() }}#{{ $id ?? 'archivo' }}" class="inline-flex min-h-12 w-full items-center justify-center border border-primary px-6 font-body text-[16px] text-primary transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                Limpiar filtros
                            </a>
                        </div>
                    @endif
                </form>
            @endif

            <div class="mt-10 flex flex-wrap items-baseline justify-between gap-4">
                <p class="font-source text-xl text-primary">
                    {{ number_format($totalItems, 0, ',', '.') }} {{ $totalItems === 1 ? 'resultado' : 'resultados' }}
                </p>
                @if ($searchTerm !== '')
                    <p class="font-body text-[15px] text-gray">Búsqueda: <span class="font-semibold text-primary">{{ $searchTerm }}</span></p>
                @elseif ($requestedTopic !== '')
                    <p class="font-body text-[15px] text-gray">Tema: <span class="font-semibold text-primary">{{ $requestedTopic }}</span></p>
                @endif
            </div>

            @if ($items->isNotEmpty())
                <ol class="mt-6 border-t border-primary" aria-label="Noticias, prensa y entrevistas">
                    @foreach ($items as $item)
                        @php
                            $cardImage = ($show_image ?? true) ? $imageUrl($item['image']) : null;
                            $archiveType = match ($item['type']) {
                                'noticias', 'noticia' => 'Noticias',
                                'entrevistas', 'entrevista' => 'Entrevistas',
                                default => 'Prensa',
                            };
                        @endphp
                        <li class="border-b border-gray-2">
                            <article class="group grid gap-6 py-8 sm:py-10 lg:grid-cols-12 lg:gap-8">
                                <div class="lg:col-span-2">
                                    <p class="font-source text-lg text-primary">{{ $archiveType }}</p>
                                    @if ($item['date'])
                                        <time datetime="{{ $item['date']->toDateString() }}" class="mt-2 block font-body text-[14px] text-gray">
                                            {{ $item['date']->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
                                        </time>
                                    @endif
                                    @if ($item['medium'])
                                        <p class="mt-2 font-body text-[14px] leading-snug text-gray">{{ $item['medium'] }}</p>
                                    @endif
                                </div>

                                <div class="{{ $cardImage ? 'lg:col-span-7' : 'lg:col-span-10' }}">
                                    <{{ $itemHeadingTag }} class="max-w-[34ch] font-sans text-[clamp(1.6rem,2.6vw,2.65rem)] font-normal leading-[1.02] tracking-[-0.025em] text-primary">
                                        <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif class="transition-colors duration-200 hover:text-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                            {{ $item['title'] }}
                                        </a>
                                    </{{ $itemHeadingTag }}>
                                    @if ($item['summary'])
                                        <p class="mt-4 line-clamp-3 max-w-[65ch] font-source text-lg leading-relaxed text-gray">{{ $item['summary'] }}</p>
                                    @endif
                                    <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif class="mt-6 inline-flex min-h-11 items-center border-b border-primary font-body text-[15px] font-semibold text-primary transition-colors duration-200 hover:border-accent hover:text-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                        {{ $archiveType === 'Entrevistas' ? 'Ver entrevista' : 'Leer más' }}
                                        <span class="ml-2 transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">{{ $item['external'] ? '↗' : '→' }}</span>
                                    </a>
                                </div>

                                @if ($cardImage)
                                    <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif class="overflow-hidden focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent lg:col-span-3" aria-label="{{ $item['title'] }}">
                                        <img src="{{ $cardImage }}" alt="" class="aspect-[4/3] w-full object-cover transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-[1.025] motion-reduce:transition-none" loading="lazy">
                                    </a>
                                @endif
                            </article>
                        </li>
                    @endforeach
                </ol>

                @if ($archivePaginator && $archivePaginator->lastPage() > 1)
                    <nav class="mt-10 flex flex-col gap-5 border-t border-primary pt-8 sm:flex-row sm:items-center sm:justify-between" aria-label="Paginación del archivo">
                        <p class="font-source text-lg text-gray">
                            Página {{ $archivePaginator->currentPage() }} de {{ $archivePaginator->lastPage() }}
                        </p>
                        <div class="flex flex-wrap items-center gap-2">
                            @if ($archivePaginator->onFirstPage())
                                <span class="inline-flex min-h-11 items-center border border-gray-2 px-4 font-body text-[14px] text-gray" aria-disabled="true">Anterior</span>
                            @else
                                <a href="{{ $archivePaginator->previousPageUrl() }}" class="inline-flex min-h-11 items-center border border-primary px-4 font-body text-[14px] text-primary transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">← Anterior</a>
                            @endif

                            @php
                                $visiblePages = collect(range(1, $archivePaginator->lastPage()))
                                    ->filter(fn (int $page): bool => $page === 1 || $page === $archivePaginator->lastPage() || abs($page - $archivePaginator->currentPage()) <= 2)
                                    ->values();
                                $previousVisiblePage = null;
                            @endphp
                            @foreach ($visiblePages as $page)
                                @if ($previousVisiblePage !== null && $page - $previousVisiblePage > 1)
                                    <span class="px-1 font-source text-gray" aria-hidden="true">…</span>
                                @endif
                                @if ($page === $archivePaginator->currentPage())
                                    <span class="inline-flex min-h-11 min-w-11 items-center justify-center border border-primary bg-primary px-3 font-body text-[14px] text-white" aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $archivePaginator->url($page) }}" class="inline-flex min-h-11 min-w-11 items-center justify-center border border-primary px-3 font-body text-[14px] text-primary transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent" aria-label="Ir a la página {{ $page }}">{{ $page }}</a>
                                @endif
                                @php $previousVisiblePage = $page; @endphp
                            @endforeach

                            @if ($archivePaginator->hasMorePages())
                                <a href="{{ $archivePaginator->nextPageUrl() }}" class="inline-flex min-h-11 items-center border border-primary px-4 font-body text-[14px] text-primary transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">Siguiente →</a>
                            @else
                                <span class="inline-flex min-h-11 items-center border border-gray-2 px-4 font-body text-[14px] text-gray" aria-disabled="true">Siguiente</span>
                            @endif
                        </div>
                    </nav>
                @endif
            @else
                <div class="mt-8 border-y border-gray-2 py-12">
                    <p class="max-w-[52ch] font-source text-xl leading-relaxed text-gray">{{ $empty_message ?? 'No encontramos resultados para esta búsqueda.' }}</p>
                </div>
            @endif
        </div>
    </x-block>
@elseif ($items->isNotEmpty() || (($show_filters ?? false) && filled($empty_message ?? null)))
    <x-block class="border-y border-gray-2 bg-gray-3 py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            <header class="grid gap-8 lg:grid-cols-12 lg:gap-12">
                <div class="lg:col-span-5">
                    @if ($title ?? null)
                        <h2 class="max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary">{!! $title !!}</h2>
                    @endif
                </div>
                @if ($description ?? null)
                    <p class="max-w-[52ch] font-source text-xl leading-relaxed text-gray lg:col-span-5 lg:col-start-8">{{ $description }}</p>
                @endif
            </header>

            @if ($show_filters ?? false)
                <form method="get" class="mt-10 grid gap-4 border border-gray-2 bg-white px-6 py-6 md:grid-cols-[1fr_1fr_auto]" aria-label="Filtros de noticias y medios">
                    <label class="grid gap-2 font-body text-sm font-semibold text-primary">
                        Tipo
                        <select name="tipo" class="min-h-12 border border-gray-2 bg-transparent px-3 font-body text-[1rem] font-normal text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">
                            <option value="">Todos</option>
                            @foreach ($configuredTypes as $type)
                                <option value="{{ $type }}" @selected($requestedType === $type)>{{ $typeLabels[$type] }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="grid gap-2 font-body text-sm font-semibold text-primary">
                        Medio o institución
                        <select name="medio" class="min-h-12 border border-gray-2 bg-transparent px-3 font-body text-[1rem] font-normal text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">
                            <option value="">Todos</option>
                            @foreach ($availableMedia as $medium)
                                <option value="{{ $medium }}" @selected($requestedMedia === $medium)>{{ $medium }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit" class="min-h-12 self-end border border-primary bg-primary px-6 font-body text-[1rem] font-semibold text-white hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">Aplicar filtros</button>
                </form>
            @endif

            @if ($items->isNotEmpty())
                @php $first = $items->first(); @endphp
                <div class="mt-12 grid gap-x-8 gap-y-10 lg:grid-cols-12">
                    {{-- Featured — sticky, 9 cols --}}
                    @php $cardImage = ($show_image ?? true) ? $imageUrl($first['image']) : null; @endphp
                    <div class="lg:col-span-9 lg:sticky lg:top-16 lg:self-start">
                        <article class="group relative border border-primary bg-white transition-colors duration-500 group-hover:bg-primary">
                            <div class="flex items-baseline justify-between gap-4 border-b border-primary px-5 py-3 transition-colors duration-500 group-hover:border-white/40">
                                @if ($first['date'])
                                    <time datetime="{{ $first['date']->toDateString() }}" class="font-source text-xs text-gray transition-colors duration-500 group-hover:text-gray-3">{{ $first['date']->locale('es')->translatedFormat('d \d\e F \d\e Y') }}</time>
                                @endif
                                <p class="ml-auto font-body text-xs text-gray transition-colors duration-500 group-hover:text-gray-3">
                                    {{ $typeLabels[$first['type']] ?? ucfirst($first['type']) }}@if($first['medium']) · {{ $first['medium'] }}@endif
                                </p>
                            </div>
                            @if ($cardImage)
                                <div class="overflow-hidden border-b border-primary transition-colors duration-500 group-hover:border-white/40">
                                    <img src="{{ $cardImage }}" alt="" class="aspect-video w-full object-cover transition-transform duration-[2500ms] ease-out group-hover:scale-150 motion-reduce:transition-none" loading="lazy">
                                </div>
                            @endif
                            <div class="p-6 sm:p-8">
                                <h3 class="max-w-[28ch] font-sans text-[clamp(1.375rem,2.25vw,2rem)] font-normal leading-[1.04] tracking-[-0.02em] text-primary transition-colors duration-500 group-hover:text-white">
                                    <a href="{{ $first['url'] }}" @if($first['external']) target="_blank" rel="noopener noreferrer" @endif class="focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">{{ $first['title'] }}</a>
                                </h3>
                                @if ($first['summary'])
                                    <p class="mt-4 line-clamp-3 max-w-[65ch] font-body text-base leading-relaxed text-gray transition-colors duration-500 group-hover:text-gray-3">{{ $first['summary'] }}</p>
                                @endif
                                <div class="mt-6">
                                    <a href="{{ $first['url'] }}" @if($first['external']) target="_blank" rel="noopener noreferrer" @endif class="group/cta relative z-20 inline-flex min-h-11 items-center justify-center border border-primary px-4 font-body text-xs font-semibold text-primary transition-colors duration-300 group-hover:border-white group-hover:bg-white group-hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                        Leer más <span class="ml-2 transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">{{ $first['external'] ? '↗' : '→' }}</span>
                                    </a>
                                </div>
                            </div>
                            <a href="{{ $first['url'] }}" @if($first['external']) target="_blank" rel="noopener noreferrer" @endif aria-label="Leer más: {{ $first['title'] }}" class="absolute inset-0 z-10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"></a>
                        </article>
                    </div>

                    {{-- Sidebar — scrollable, 3 cols --}}
                    @if ($items->count() > 1)
                        <ol class="lg:col-span-3 flex flex-col gap-y-8" aria-label="Más noticias">
                            @foreach ($items->skip(1) as $item)
                                @php $cardImage = ($show_image ?? true) ? $imageUrl($item['image']) : null; @endphp
                                <li>
                                    <article class="group relative flex h-full flex-col border border-primary bg-white transition-colors duration-500 group-hover:bg-primary">
                                        <div class="flex items-baseline justify-between gap-4 border-b border-primary px-5 py-3 transition-colors duration-500 group-hover:border-white/40">
                                            @if ($item['date'])
                                                <time datetime="{{ $item['date']->toDateString() }}" class="font-source text-xs text-gray transition-colors duration-500 group-hover:text-gray-3">{{ $item['date']->locale('es')->translatedFormat('d M Y') }}</time>
                                            @endif
                                            <p class="ml-auto font-body text-xs text-gray transition-colors duration-500 group-hover:text-gray-3">
                                                {{ $typeLabels[$item['type']] ?? ucfirst($item['type']) }}@if($item['medium']) · {{ $item['medium'] }}@endif
                                            </p>
                                        </div>
                                        @if ($cardImage)
                                            <div class="overflow-hidden border-b border-primary transition-colors duration-500 group-hover:border-white/40">
                                                <img src="{{ $cardImage }}" alt="" class="aspect-video w-full object-cover transition-transform duration-[2500ms] ease-out group-hover:scale-150 motion-reduce:transition-none" loading="lazy">
                                            </div>
                                        @endif
                                        <div class="flex flex-1 flex-col p-5">
                                            <h3 class="max-w-[30ch] font-sans text-[clamp(1.125rem,1.75vw,1.5rem)] font-normal leading-[1.1] tracking-[-0.02em] text-primary transition-colors duration-500 group-hover:text-white">
                                                <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif class="focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">{{ $item['title'] }}</a>
                                            </h3>
                                            @if ($item['summary'])
                                                <p class="mt-3 line-clamp-3 font-body text-base leading-relaxed text-gray transition-colors duration-500 group-hover:text-gray-3">{{ $item['summary'] }}</p>
                                            @endif
                                            <div class="mt-auto pt-5">
                                                <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif class="group/cta relative z-20 inline-flex min-h-11 items-center justify-center border border-primary px-4 font-body text-xs font-semibold text-primary transition-colors duration-300 group-hover:border-white group-hover:bg-white group-hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                                    Leer más <span class="ml-2 transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">{{ $item['external'] ? '↗' : '→' }}</span>
                                                </a>
                                            </div>
                                        </div>
                                        <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif aria-label="Leer más: {{ $item['title'] }}" class="absolute inset-0 z-10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"></a>
                                    </article>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            @else
                <p class="mt-8 border border-gray-2 bg-white px-6 py-10 font-source text-xl text-gray">{{ $empty_message }}</p>
            @endif
        </div>
    </x-block>
@endif
