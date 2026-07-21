@php
    use App\Models\PublicacionMedio;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $allowedTypes = ['articulo', 'entrevista', 'noticia'];
    $configuredTypes = array_values(array_intersect($content_types ?? $allowedTypes, $allowedTypes));
    $configuredTypes = $configuredTypes ?: $allowedTypes;
    $configuredMedia = collect(explode(',', (string) ($media ?? '')))
        ->map(fn (string $item): string => trim($item))
        ->filter()
        ->values();
    $requestedType = request()->query('tipo');
    $requestedMedia = trim((string) request()->query('medio', ''));
    $activeTypes = ($show_filters ?? false) && in_array($requestedType, $configuredTypes, true)
        ? [$requestedType]
        : $configuredTypes;

    $selectedIds = collect($selected_items ?? [])
        ->filter(fn ($id): bool => filled($id))
        ->map(fn ($id): int => (int) $id)
        ->values();

    if ($selectedIds->isNotEmpty()) {
        $records = PublicacionMedio::with('route')
            ->whereIn('id', $selectedIds)
            ->get()
            ->sortBy(fn (PublicacionMedio $record): int => $selectedIds->search($record->id));
    } else {
        $records = PublicacionMedio::with('route')
            ->whereIn('tipo', $activeTypes)
            ->when($configuredMedia->isNotEmpty(), fn ($query) => $query->whereIn('medio', $configuredMedia->all()))
            ->when(($show_filters ?? false) && $requestedMedia !== '', fn ($query) => $query->where('medio', $requestedMedia))
            ->orderByDesc('fecha')
            ->limit((int) ($max_items ?? 6))
            ->get();
    }

    $items = $records
        ->filter(fn (PublicacionMedio $record): bool => (bool) $record->route && $record->route->status->value === 'published')
        ->map(function (PublicacionMedio $record): array {
            $externalUrl = $record->enlace_externo;
            $url = $externalUrl ?: $record->url;

            return [
                'type' => $record->tipo,
                'title' => $record->title,
                'medium' => $record->medio,
                'date' => $record->fecha,
                'summary' => trim(Str::squish(strip_tags((string) $record->resumen))),
                'image' => $record->route?->image,
                'url' => $url,
                'external' => filled($externalUrl),
            ];
        })
        ->values();

    $typeLabels = [
        'articulo' => 'Artículo en medios',
        'entrevista' => 'Entrevista',
        'noticia' => 'Noticia',
    ];
    $availableMedia = $configuredMedia->isNotEmpty()
        ? $configuredMedia
        : $records->pluck('medio')->filter()->unique()->sort()->values();
@endphp

@if ($items->isNotEmpty() || (($show_filters ?? false) && filled($empty_message ?? null)))
    <x-block class="bg-white py-14 md:py-16">
        <div class="container mx-auto px-5 md:px-12">
            <div class="grid gap-8 border-t border-[var(--color-border-light)] pt-6 lg:grid-cols-12 lg:gap-12">
                <div class="lg:col-span-3">
                    @if ($title ?? null)
                        <h2 class="max-w-md font-sans text-3xl leading-[1.02] tracking-[-0.03em] text-primary md:text-4xl">
                            {{ $title }}
                        </h2>
                    @endif

                    @if ($description ?? null)
                        <p class="mt-4 max-w-md font-source text-lg leading-relaxed text-[var(--color-gray)]">
                            {{ $description }}
                        </p>
                    @endif
                </div>

                <div class="lg:col-span-9">
                    @if ($show_filters ?? false)
                        <form method="get" class="mb-8 grid gap-4 border-y border-[var(--color-border-light)] py-4 md:grid-cols-[1fr_1fr_auto]">
                            <label class="grid gap-1 font-source text-sm text-primary">
                                Tipo
                                <select name="tipo" class="min-h-12 border border-[var(--color-border-light)] bg-white px-3 font-sans text-base text-[var(--color-gray)] focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-accent">
                                    <option value="">Todos</option>
                                    @foreach ($configuredTypes as $type)
                                        <option value="{{ $type }}" @selected($requestedType === $type)>{{ $typeLabels[$type] }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="grid gap-1 font-source text-sm text-primary">
                                Medio
                                <select name="medio" class="min-h-12 border border-[var(--color-border-light)] bg-white px-3 font-sans text-base text-[var(--color-gray)] focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-accent">
                                    <option value="">Todos</option>
                                    @foreach ($availableMedia as $medium)
                                        <option value="{{ $medium }}" @selected($requestedMedia === $medium)>{{ $medium }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <button type="submit" class="min-h-12 self-end border border-primary bg-primary px-5 font-sans text-base text-white transition-colors duration-200 hover:bg-white hover:text-primary focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-accent">
                                Aplicar filtros
                            </button>
                        </form>
                    @endif

                    @if ($items->isNotEmpty())
                        <div class="grid gap-0">
                            @foreach ($items as $index => $item)
                                <article class="group border-t border-[var(--color-border-light)] py-5 first:border-t-0">
                                    <div>
                                        <p class="font-source text-sm text-primary">
                                            {{ $typeLabels[$item['type']] }} · {{ $item['medium'] }}
                                        </p>
                                        <h3 class="mt-1 font-sans text-xl leading-tight tracking-[-0.02em] text-primary md:text-2xl">
                                            <a href="{{ $item['url'] }}" class="underline decoration-accent decoration-1 underline-offset-4 transition-colors hover:text-[var(--color-gray)] focus:outline focus:outline-2 focus:outline-offset-4 focus:outline-accent" @if ($item['external']) target="_blank" rel="noopener noreferrer" @endif>
                                                {{ $item['title'] }}@if ($item['external']) <span aria-hidden="true"> ↗</span>@endif
                                            </a>
                                        </h3>
                                        @if ($item['summary'])
                                            <p class="mt-2 line-clamp-2 max-w-[65ch] font-source text-base leading-relaxed text-[var(--color-gray)]">{{ $item['summary'] }}</p>
                                        @endif
                                        @if ($item['date'])
                                            <time datetime="{{ $item['date']->toDateString() }}" class="block whitespace-nowrap font-source text-sm text-[var(--color-gray)] md:pt-6">
                                                {{ $item['date']->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
                                            </time>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <p class="border-t border-[var(--color-border-light)] pt-5 font-source text-xl leading-relaxed text-[var(--color-gray)]">
                            {{ $empty_message }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </x-block>
@endif
