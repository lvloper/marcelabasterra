@php
    use App\Models\PublicacionMedio;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $sourceMode = $source_mode ?? 'media_publications';
    $limit = min(max((int) ($max_items ?? 6), 1), 24);
    $requestedType = trim((string) request()->query('tipo', ''));
    $requestedMedia = trim((string) request()->query('medio', ''));
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
        $catalogItems = $catalog->all()->filter(fn (array $item): bool => in_array($item['category'], $configuredTypes, true));
        if (($show_filters ?? false) && in_array($requestedType, $configuredTypes, true)) {
            $catalogItems = $catalogItems->where('category', $requestedType);
        }
        if ($configuredMedia->isNotEmpty()) {
            $catalogItems = $catalogItems->filter(fn (array $item): bool => in_array($item['medium'] ?: $item['institution'], $configuredMedia->all(), true));
        }
        if (($show_filters ?? false) && $requestedMedia !== '') {
            $catalogItems = $catalogItems->filter(fn (array $item): bool => ($item['medium'] ?: $item['institution']) === $requestedMedia);
        }
        $items = $catalogItems->take($limit)->map(fn (array $item): array => [
            'type' => $item['category'],
            'title' => $item['title'],
            'medium' => $item['medium'] ?: $item['institution'],
            'date' => $item['date'] ? Carbon::parse($item['date']) : null,
            'summary' => $item['summary'],
            'image' => $item['image'],
            'url' => $item['url'],
            'external' => $item['external'],
        ])->values();
        $availableMedia = $catalogItems->map(fn (array $item) => $item['medium'] ?: $item['institution'])->filter()->unique()->sort()->values();
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
                ->orderByDesc('fecha')->limit($limit)->get();
        $items = $records->filter(fn (PublicacionMedio $record): bool => $record->route?->status->value === 'published')->map(fn (PublicacionMedio $record): array => [
            'type' => $record->tipo,
            'title' => $record->title,
            'medium' => $record->medio,
            'date' => $record->fecha,
            'summary' => Str::squish(strip_tags((string) $record->resumen)),
            'image' => $record->route?->image,
            'url' => $record->enlace_externo ?: $record->url,
            'external' => filled($record->enlace_externo),
        ])->values();
        $availableMedia = $configuredMedia->isNotEmpty() ? $configuredMedia : $records->pluck('medio')->filter()->unique()->sort()->values();
    }

    $imageUrl = static fn (?string $image): ?string => blank($image)
        ? null
        : (filter_var($image, FILTER_VALIDATE_URL) ? $image : Storage::url($image));
@endphp

@if ($items->isNotEmpty() || (($show_filters ?? false) && filled($empty_message ?? null)))
    <x-block class="border-y border-gray-2 bg-gray-3 py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            <header class="grid gap-8 border-t border-primary pt-6 lg:grid-cols-12 lg:gap-12">
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
