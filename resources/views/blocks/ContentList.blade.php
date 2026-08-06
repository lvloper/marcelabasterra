@php
    $variant = $variant ?? 'editorial';
    $sourceMode = $source_mode ?? 'manual';
    $isLiveAcademicContent = in_array($sourceMode, ['academic_articles', 'academic_publications'], true);
    $contentPaginator = null;

    if ($isLiveAcademicContent) {
        $contentItems = collect();
    } else {
        $contentItems = match ($sourceMode) {
        'institutional_positions' => \App\Models\CargoInstitucional::query()
            ->whereIn('id', $institutional_positions ?? [])
            ->with('route')
            ->get()
            ->sortBy(fn ($position) => array_search($position->id, $institutional_positions ?? [], true))
            ->map(fn ($position): array => [
                'meta' => $position->institucion,
                'title' => $position->cargo,
                'text' => $position->descripcion ? trim(strip_tags($position->descripcion)) : null,
                'url' => $position->institutional_url ?: ($position->route ? url($position->route->full_slug) : null),
                'link_label' => $position->institutional_url ? 'Consultar fuente institucional' : 'Ver cargo',
            ])
            ->values(),
        default => collect($items ?? [])
            ->filter(static fn ($item) => is_array($item) && collect($item)->filter()->isNotEmpty())
            ->values(),
        };
    }
@endphp

@if ($isLiveAcademicContent || $contentItems->isNotEmpty())
    <x-block class="{{ $variant === 'metrics' ? 'bg-primary py-16 md:py-20' : ($variant === 'chronological' ? 'bg-white py-16 md:py-20 lg:py-24' : 'border-y border-gray-2 bg-white py-10 md:py-12') }}">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            @if (($title ?? null) || ($description ?? null))
                <header class="grid gap-4 md:grid-cols-12 md:gap-8">
                    @if ($title ?? null)
                        <h2 class="max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] {{ $variant === 'metrics' ? 'text-white' : 'text-primary' }} md:col-span-5">
                            {{ $title }}
                        </h2>
                    @endif

                    @if ($description ?? null)
                        <p class="max-w-[52ch] font-[var(--font-editorial)] text-xl leading-[1.55] {{ $variant === 'metrics' ? 'text-gray-3' : 'text-gray' }} md:col-span-6 md:col-start-7 md:text-2xl">
                            {{ $description }}
                        </p>
                    @endif
                </header>
            @endif

            @if ($variant === 'chronological')
                @if ($isLiveAcademicContent)
                    <livewire:academic-articles :per-page="min(max((int) ($items_per_page ?? 12), 6), 24)" :source="$sourceMode === 'academic_publications' ? 'publications' : 'articles'" :key="'academic-content-'.$id" />
                @else
                <ol class="mt-12 border-b border-primary/25" aria-label="{{ $title ?? 'Archivo cronológico' }}">
                    @foreach ($contentItems as $item)
                        <li class="grid gap-4 border-t border-primary/25 py-7 sm:grid-cols-12 sm:gap-8 md:py-9">
                            <div class="sm:col-span-2 lg:col-span-2">
                                @if ($item['meta'] ?? null)
                                    <time @if($item['date'] ?? null) datetime="{{ $item['date'] }}" @endif class="font-[var(--font-editorial)] text-[clamp(2rem,4vw,3.75rem)] leading-none text-primary">
                                        {{ $item['meta'] }}
                                    </time>
                                @endif
                                @if ($item['type_label'] ?? null)
                                    <p class="mt-2 font-[var(--font-body)] text-sm text-gray">{{ $item['type_label'] }}</p>
                                @endif
                            </div>
                            <div class="sm:col-span-7 lg:col-span-8">
                                @if ($item['title'] ?? null)
                                    <h3 class="max-w-[42ch] font-[var(--font-editorial)] text-xl leading-snug text-primary md:text-2xl">
                                        {{ $item['title'] }}
                                    </h3>
                                @endif
                                @if ($item['text'] ?? null)
                                    <p class="mt-3 max-w-[68ch] font-[var(--font-body)] text-base leading-[1.6] text-gray">
                                        {{ $item['text'] }}
                                    </p>
                                @endif
                            </div>
                            @if ($item['url'] ?? null)
                                <div class="sm:col-span-3 sm:text-right lg:col-span-2">
                                    <a href="{{ $item['url'] }}" @if($item['external'] ?? true) target="_blank" rel="noopener noreferrer" @endif class="group inline-flex min-h-12 items-center gap-2 border border-primary px-4 font-[var(--font-body)] text-sm font-semibold text-primary transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none" aria-label="{{ $item['link_label'] ?? 'Ver publicación' }}: {{ $item['title'] ?? 'publicación' }}{{ ($item['external'] ?? true) ? ' (se abre en una pestaña nueva)' : '' }}">
                                        <span>{{ $item['link_label'] ?? 'Descargar PDF' }}</span>
                                        <span class="transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">{{ ($item['external'] ?? true) ? '↗' : '→' }}</span>
                                    </a>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ol>
                @if ($contentPaginator?->hasPages())
                    <nav class="mt-8 flex flex-col gap-5 border-t border-primary pt-6 sm:flex-row sm:items-center sm:justify-between" aria-label="Paginación de publicaciones académicas">
                        <p class="font-[var(--font-body)] text-sm text-gray" aria-live="polite">
                            Página {{ $contentPaginator->currentPage() }} de {{ $contentPaginator->lastPage() }}
                            <span class="sr-only">· {{ $contentPaginator->total() }} publicaciones en total</span>
                        </p>
                        <div class="flex flex-wrap items-center gap-2">
                            @if ($contentPaginator->onFirstPage())
                                <span class="inline-flex min-h-14 items-center border border-gray-2 px-4 font-[var(--font-body)] text-sm text-gray-2" aria-disabled="true">Anterior</span>
                            @else
                                <a href="{{ $contentPaginator->previousPageUrl() }}" class="inline-flex min-h-14 items-center border border-primary px-4 font-[var(--font-body)] text-sm font-semibold text-primary transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none" rel="prev">Anterior</a>
                            @endif

                            @foreach (range(max(1, $contentPaginator->currentPage() - 2), min($contentPaginator->lastPage(), $contentPaginator->currentPage() + 2)) as $page)
                                <a href="{{ $contentPaginator->url($page) }}" @if($page === $contentPaginator->currentPage()) aria-current="page" @endif class="inline-flex min-h-14 min-w-14 items-center justify-center border px-3 font-[var(--font-body)] text-sm font-semibold transition-colors duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none {{ $page === $contentPaginator->currentPage() ? 'border-primary bg-primary text-white' : 'border-primary text-primary hover:bg-primary hover:text-white' }}">
                                    <span class="sr-only">Página </span>{{ $page }}
                                </a>
                            @endforeach

                            @if ($contentPaginator->hasMorePages())
                                <a href="{{ $contentPaginator->nextPageUrl() }}" class="inline-flex min-h-14 items-center border border-primary px-4 font-[var(--font-body)] text-sm font-semibold text-primary transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none" rel="next">Siguiente</a>
                            @else
                                <span class="inline-flex min-h-14 items-center border border-gray-2 px-4 font-[var(--font-body)] text-sm text-gray-2" aria-disabled="true">Siguiente</span>
                            @endif
                        </div>
                    </nav>
                @endif
                @endif
            @else
                <ol
                    class="grid {{ $variant === 'metrics' ? 'gap-x-6 sm:grid-cols-2 lg:grid-cols-12' : 'gap-x-10 md:grid-cols-2 lg:gap-x-16' }} {{ ($title ?? null) || ($description ?? null) ? 'mt-10 md:mt-14' : '' }}"
                    aria-label="{{ $title ?? 'Listado de contenido' }}"
                >
                    @foreach ($contentItems as $index => $item)
                        <li @class([
                            'py-6 md:py-7' => true,
                            'border-t border-primary/25' => $variant !== 'metrics',
                            'border-t border-white/40 lg:col-span-4 lg:py-8' => $variant === 'metrics',
                        ])>
                        @if ($item['meta'] ?? null)
                            <p class="mb-3 {{ $variant === 'metrics' ? 'font-[var(--font-editorial)] text-[clamp(2.75rem,5vw,5rem)] leading-none text-white' : 'font-[var(--font-body)] text-sm leading-relaxed text-gray' }}">
                                {{ $item['meta'] }}
                            </p>
                        @endif

                        @if ($item['title'] ?? null)
                            <h3 class="max-w-[30ch] {{ $variant === 'metrics' ? 'font-[var(--font-body)] text-lg font-medium leading-snug text-white' : 'font-[var(--font-display)] text-2xl font-normal leading-[1.08] tracking-[-0.015em] text-primary md:text-3xl' }}">
                                {{ $item['title'] }}
                            </h3>
                        @endif

                        @if ($item['text'] ?? null)
                            <p class="mt-3 max-w-[68ch] font-[var(--font-body)] text-[1rem] leading-[1.65] {{ $variant === 'metrics' ? 'text-gray-3' : 'text-gray' }}">
                                {{ $item['text'] }}
                            </p>
                        @endif

                        @if ($item['url'] ?? null)
                            <a
                                href="{{ $item['url'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-4 inline-flex min-h-12 items-center gap-2 font-[var(--font-body)] text-[1rem] font-semibold {{ $variant === 'metrics' ? 'text-white decoration-white/50' : 'text-primary decoration-gray-2' }} underline underline-offset-4 transition-colors duration-200 hover:decoration-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none"
                                aria-label="{{ $item['link_label'] ?? 'Ver más' }}: {{ $item['title'] ?? $item['meta'] ?? 'contenido' }} (se abre en una pestaña nueva)"
                            >
                                <span>{{ $item['link_label'] ?? 'Ver más' }}</span>
                                <span aria-hidden="true">↗</span>
                            </a>
                        @endif
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </x-block>
@endif
