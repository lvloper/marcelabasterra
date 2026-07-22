@php
    $searchMode = $mode ?? 'global';
@endphp

@if ($searchMode === 'academic_catalog')
    @php
        $catalog = app(\App\Support\AcademicProductionCatalog::class);
        $allItems = $catalog->all();
        $query = trim((string) request()->query('buscar', ''));
        $category = trim((string) request()->query('categoria', ''));
        $year = trim((string) request()->query('anio', ''));
        $topic = trim((string) request()->query('tema', ''));
        $filteredItems = $catalog->filter($allItems, $query, $category, $year, $topic);
        $perPage = min(max((int) ($items_per_page ?? 12), 6), 24);
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage('catalogo');
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $filteredItems->forPage($currentPage, $perPage)->values(),
            $filteredItems->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->except('catalogo'),
                'pageName' => 'catalogo',
            ],
        );
        $years = $catalog->years($allItems);
        $topics = $catalog->topics($allItems);
        $formId = ($id ?? 'catalogo-academico').'-filtros';
        $resultId = ($id ?? 'catalogo-academico').'-resultados';
        $imageUrl = static function (?string $image): ?string {
            if (blank($image)) return null;
            return filter_var($image, FILTER_VALIDATE_URL) ? $image : \Illuminate\Support\Facades\Storage::url($image);
        };
    @endphp

    <x-block class="border-y border-gray-2 bg-[var(--color-surface-ivory)] py-14 sm:py-16 lg:py-20">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            <header class="grid gap-6 border-b border-primary pb-8 lg:grid-cols-12 lg:items-end lg:gap-12">
                <div class="lg:col-span-7">
                    <p class="mb-4 flex items-center gap-3 font-[var(--font-editorial)] text-sm text-primary">
                        <span class="h-px w-10 bg-accent" aria-hidden="true"></span>
                        Archivo consultable
                    </p>
                    @if ($title ?? null)
                        <h2 class="max-w-[15ch] font-[var(--font-display)] text-[clamp(2.5rem,5vw,4.75rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary">{{ $title }}</h2>
                    @endif
                </div>
                @if ($description ?? null)
                    <p class="max-w-[48ch] font-[var(--font-editorial)] text-xl leading-relaxed text-gray lg:col-span-4 lg:col-start-9">{{ $description }}</p>
                @endif
            </header>

            <form id="{{ $formId }}" method="get" action="{{ request()->url() }}" role="search" class="mt-8" aria-controls="{{ $resultId }}">
                <fieldset>
                    <legend class="font-[var(--font-body)] text-base font-semibold text-primary">Filtrar por categoría</legend>
                    <div class="mt-3 grid grid-cols-2 border-l border-t border-primary sm:grid-cols-4 lg:grid-cols-8">
                        @foreach (['' => 'Todos'] + \App\Support\AcademicProductionCatalog::CATEGORIES as $value => $label)
                            <label class="relative flex min-h-12 cursor-pointer items-center justify-center border-b border-r border-primary px-3 text-center font-[var(--font-body)] text-sm text-primary has-[:checked]:bg-primary has-[:checked]:text-white">
                                <input type="radio" name="categoria" value="{{ $value }}" class="sr-only" @checked($category === $value)>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="mt-6 grid gap-5 md:grid-cols-2 lg:grid-cols-[2fr_1fr_1.4fr_auto] lg:items-end">
                    <label class="grid min-w-0 gap-2 font-[var(--font-body)] text-sm font-semibold text-primary">
                        Buscar en títulos y descripciones
                        <input type="search" name="buscar" value="{{ $query }}" autocomplete="off" class="min-h-12 w-full min-w-0 border border-primary bg-white px-4 font-[var(--font-body)] text-[1rem] font-normal text-gray placeholder:text-gray focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent" placeholder="Ej.: acceso a la información">
                    </label>
                    <label class="grid min-w-0 gap-2 font-[var(--font-body)] text-sm font-semibold text-primary">
                        Año
                        <select name="anio" class="min-h-12 w-full min-w-0 border border-primary bg-white px-4 font-[var(--font-body)] text-[1rem] font-normal text-gray focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">
                            <option value="">Todos los años</option>
                            @foreach ($years as $availableYear)
                                <option value="{{ $availableYear }}" @selected($year === (string) $availableYear)>{{ $availableYear }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="grid min-w-0 gap-2 font-[var(--font-body)] text-sm font-semibold text-primary">
                        Tema
                        <select name="tema" class="min-h-12 w-full min-w-0 border border-primary bg-white px-4 font-[var(--font-body)] text-[1rem] font-normal text-gray focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">
                            <option value="">Todos los temas</option>
                            @foreach ($topics as $availableTopic)
                                <option value="{{ $availableTopic }}" @selected($topic === (string) $availableTopic)>{{ $availableTopic }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center border border-primary bg-primary px-6 font-[var(--font-body)] text-[1rem] font-semibold text-white transition-colors duration-200 hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none">
                        Aplicar filtros
                    </button>
                </div>
            </form>

            <div id="{{ $resultId }}" class="mt-10" tabindex="-1">
                <div class="flex flex-wrap items-baseline justify-between gap-4 border-t border-primary pt-5">
                    <p class="font-[var(--font-editorial)] text-xl text-primary" aria-live="polite">
                        {{ $filteredItems->count() }} {{ $filteredItems->count() === 1 ? 'resultado' : 'resultados' }}
                    </p>
                    @if ($query !== '' || $category !== '' || $year !== '' || $topic !== '')
                        <a href="{{ request()->url() }}#{{ $formId }}" class="inline-flex min-h-11 items-center border-b border-primary font-[var(--font-body)] text-sm text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">Limpiar filtros</a>
                    @endif
                </div>

                @if ($paginator->isNotEmpty())
                    <ol class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3" aria-label="Resultados del archivo">
                        @foreach ($paginator as $item)
                            @php $cardImage = $imageUrl($item['image']); @endphp
                            <li>
                                <article class="group flex h-full flex-col border border-gray-2 bg-white transition-colors duration-300 hover:border-primary motion-reduce:transition-none">
                                    @if ($cardImage)
                                        <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif class="block overflow-hidden border-b border-gray-2 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent" tabindex="-1" aria-hidden="true">
                                            <img src="{{ $cardImage }}" alt="" class="aspect-[16/9] w-full object-cover transition-transform duration-500 group-hover:scale-[1.02] motion-reduce:transition-none" loading="lazy">
                                        </a>
                                    @endif
                                    <div class="flex flex-1 flex-col p-6">
                                        <p class="font-[var(--font-editorial)] text-sm text-primary">
                                            {{ $item['category_label'] }}
                                            @if ($item['year']) · <time datetime="{{ $item['date'] }}">{{ $item['year'] }}</time>@endif
                                        </p>
                                        <h3 class="mt-3 font-[var(--font-display)] text-[1.75rem] font-normal leading-[1.05] tracking-[-0.02em] text-primary">
                                            <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif class="focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">{{ $item['title'] }}</a>
                                        </h3>
                                        @if ($item['institution'] || $item['medium'])
                                            <p class="mt-3 font-[var(--font-body)] text-sm text-gray">{{ $item['institution'] ?: $item['medium'] }}</p>
                                        @endif
                                        @if ($item['summary'])
                                            <p class="mt-4 line-clamp-3 font-[var(--font-body)] text-[1rem] leading-relaxed text-gray">{{ \Illuminate\Support\Str::limit($item['summary'], 180) }}</p>
                                        @endif
                                        <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif class="group/link mt-auto inline-flex min-h-12 w-fit items-center border-b border-primary pt-6 font-[var(--font-body)] text-sm font-semibold text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                            {{ $item['action_label'] }} <span class="ml-2 transition-transform group-hover/link:translate-x-1 motion-reduce:transition-none" aria-hidden="true">{{ $item['external'] ? '↗' : '→' }}</span>
                                            @if($item['external'])<span class="sr-only"> (se abre en una pestaña nueva)</span>@endif
                                        </a>
                                    </div>
                                </article>
                            </li>
                        @endforeach
                    </ol>

                    @if ($paginator->hasPages())
                        <nav class="mt-8 flex items-center justify-between border-t border-primary pt-6" aria-label="Paginación del archivo">
                            @if ($paginator->onFirstPage())
                                <span class="inline-flex min-h-12 items-center border border-gray-2 px-4 font-[var(--font-body)] text-sm text-gray" aria-disabled="true">Anterior</span>
                            @else
                                <a href="{{ $paginator->previousPageUrl() }}#{{ $resultId }}" rel="prev" class="inline-flex min-h-12 items-center border border-primary px-4 font-[var(--font-body)] text-sm text-primary hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">Anterior</a>
                            @endif
                            <p class="font-[var(--font-body)] text-sm text-gray">Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</p>
                            @if ($paginator->hasMorePages())
                                <a href="{{ $paginator->nextPageUrl() }}#{{ $resultId }}" rel="next" class="inline-flex min-h-12 items-center border border-primary px-4 font-[var(--font-body)] text-sm text-primary hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">Siguiente</a>
                            @else
                                <span class="inline-flex min-h-12 items-center border border-gray-2 px-4 font-[var(--font-body)] text-sm text-gray" aria-disabled="true">Siguiente</span>
                            @endif
                        </nav>
                    @endif
                @else
                    <p class="mt-6 border-y border-primary py-8 font-[var(--font-editorial)] text-xl leading-relaxed text-gray">No encontramos contenidos con esos filtros. Probá ampliar la búsqueda.</p>
                @endif
            </div>
        </div>
    </x-block>
@else
    @php
        $searchTerm = '';
        $segments = array_filter(explode('/', request()->path()));
        $lastSegment = end($segments);
        if ($lastSegment && $lastSegment !== '/' && strlen($lastSegment) > 2) {
            $searchTerm = str_replace(['-', '_'], ' ', $lastSegment);
        }
    @endphp

    <x-block class="border-y border-gray-2 bg-white py-14 md:py-16">
        <div class="mx-auto max-w-[800px] px-5 sm:px-8">
            @if ($title ?? null)
                <h2 class="font-[var(--font-display)] text-[clamp(2.5rem,5vw,4.5rem)] font-normal leading-[0.98] tracking-[-0.03em] text-primary">{{ $title }}</h2>
            @endif
            @if ($description ?? null)
                <p class="mt-5 font-[var(--font-editorial)] text-xl leading-relaxed text-gray">{{ $description }}</p>
            @endif
            <div class="mt-8">
                <livewire:search-component :isFullPage="true" :autoSearch="!empty($searchTerm)" :initialSearch="$searchTerm" />
            </div>
        </div>
    </x-block>
@endif
