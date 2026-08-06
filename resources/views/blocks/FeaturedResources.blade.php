@php
    $typeMap = [
        'libro' => \App\Models\Libro::class,
        'articulo' => \App\Models\ArticuloAcademico::class,
        'entrevista' => \App\Models\Entrevista::class,
    ];

    $typeLabels = [
        'libro' => 'Libro',
        'articulo' => 'Artículo académico',
        'entrevista' => 'Entrevista',
    ];

    $resources = [];
    if (($source_mode ?? 'manual') === 'latest') {
        $resources = app(\App\Support\AcademicProductionCatalog::class)->all()
            ->take(min(max((int) ($max_items ?? 4), 1), 4))
            ->map(fn (array $item): array => [
                'type' => $item['category'],
                'type_label' => $item['category_label'],
                'title' => $item['title'],
                'description' => $item['summary'],
                'url' => $item['url'],
                'external' => $item['external'],
                'image' => $item['image'],
                'publisher' => $item['institution'] ?: $item['medium'],
                'published_at' => $item['date'],
            ])->all();
    } else {
        foreach (($items ?? []) as $item) {
            $type = $item['resource_type'] ?? null;
            $id = $item['resource_id'] ?? null;

            if (! $type || ! $id || ! isset($typeMap[$type])) {
                continue;
            }

            $model = $typeMap[$type]::find($id);
            $status = $model?->route?->status;
            $statusValue = is_object($status) ? $status->value : $status;

            $isAvailable = $type === 'articulo'
                ? in_array($statusValue, ['published', 'hidden'], true)
                : $statusValue === 'published';

            if (! $model || ! $model->route || ! $isAvailable) {
                continue;
            }

            $publishedAt = $type === 'entrevista'
                ? $model->fecha
                : $model->fecha_publicacion;

            $resources[] = [
                'type' => $type,
                'type_label' => $typeLabels[$type],
                'title' => $model->title,
                'description' => $model->descripcion ?? $model->resumen ?? $model->description ?? '',
                'url' => $type === 'articulo' ? ($model->document_url ?: $model->url) : $model->url,
                'external' => $type === 'articulo' && filled($model->document_url),
                'image' => $model->portada ?? $model->route?->image,
                'publisher' => $model->editorial ?? $model->medio ?? null,
                'published_at' => $publishedAt,
            ];
        }
    }

    $featuredResource = $resources[0] ?? null;
    $secondaryResources = array_slice($resources, 1);
    $imageUrl = static function ($image): ?string {
        if (blank($image)) {
            return null;
        }

        return filter_var($image, FILTER_VALIDATE_URL)
            ? $image
            : \Illuminate\Support\Facades\Storage::url($image);
    };
    $publicationDate = static function ($date): ?string {
        if (blank($date)) {
            return null;
        }

        try {
            return \Illuminate\Support\Carbon::parse($date)
                ->locale(app()->getLocale())
                ->translatedFormat('Y');
        } catch (\Throwable) {
            return null;
        }
    };
@endphp

@if ($featuredResource)
    <x-block class="bg-white py-16 md:py-20 lg:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            @if (filled($title ?? null) || filled($description ?? null))
                <header class="grid gap-6 border-t border-primary pt-6 md:gap-8 lg:grid-cols-12 lg:pt-8">
                    @if (filled($title ?? null))
                        <h2 class="max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary lg:col-span-7">
                            {{ $title }}
                        </h2>
                    @endif

                    @if (filled($description ?? null))
                        <p class="max-w-[42ch] self-end font-[var(--font-editorial)] text-xl leading-relaxed text-gray md:text-2xl lg:col-span-4 lg:col-start-9">
                            {{ $description }}
                        </p>
                    @endif
                </header>
            @endif

            @php
                $featuredImage = $imageUrl($featuredResource['image']);
                $featuredYear = $publicationDate($featuredResource['published_at']);
            @endphp

            <article class="mt-10 grid border-y border-primary lg:mt-12 lg:grid-cols-12">
                <a
                    href="{{ $featuredResource['url'] }}"
                    @if($featuredResource['external']) target="_blank" rel="noopener" @endif
                    class="group flex flex-col justify-between p-6 sm:p-8 lg:col-span-5 lg:p-12 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                >
                    <div>
                        <div class="flex flex-wrap items-baseline gap-x-4 gap-y-2 font-[var(--font-editorial)] text-base text-primary">
                            <span>{{ $featuredResource['type_label'] }}</span>
                            @if ($featuredResource['publisher'])
                                <span class="text-gray">{{ $featuredResource['publisher'] }}</span>
                            @endif
                            @if ($featuredYear)
                                <time datetime="{{ $featuredResource['published_at'] }}">{{ $featuredYear }}</time>
                            @endif
                        </div>

                        <h3 class="mt-8 max-w-[16ch] font-[var(--font-display)] text-[clamp(2.25rem,4vw,4rem)] font-normal leading-[0.98] tracking-[-0.03em] text-primary transition-colors duration-300 group-hover:text-primary/70">
                            {{ $featuredResource['title'] }}
                        </h3>

                        @if ($featuredResource['description'])
                            <p class="mt-6 max-w-[48ch] font-[var(--font-body)] text-base leading-relaxed text-gray md:text-md">
                                {{ $featuredResource['description'] }}
                            </p>
                        @endif
                    </div>

                    <span
                        class="group/cta mt-10 inline-flex min-h-12 w-fit items-center gap-3 border-b border-primary py-2 font-[var(--font-body)] text-base font-semibold text-primary transition-colors duration-200 group-hover:border-accent motion-reduce:transition-none"
                        aria-hidden="true"
                    >
                        <span>Ver publicación</span>
                        <span class="transition-transform duration-200 group-hover/cta:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span>
                    </span>
                </a>

                <a
                    href="{{ $featuredResource['url'] }}"
                    @if($featuredResource['external']) target="_blank" rel="noopener" @endif
                    class="group border-t border-primary bg-[var(--color-surface-ivory)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent lg:col-span-7 lg:border-l lg:border-t-0"
                    aria-label="Ver {{ $featuredResource['title'] }}"
                >
                    @if ($featuredImage)
                        <div class="h-full min-h-[22rem] overflow-hidden sm:min-h-[30rem] lg:min-h-[38rem]">
                            <img
                                src="{{ $featuredImage }}"
                                alt="{{ $featuredResource['title'] }}"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.025] motion-reduce:transition-none"
                            >
                        </div>
                    @else
                        <div class="min-h-[22rem] sm:min-h-[30rem] lg:min-h-[38rem]" aria-hidden="true"></div>
                    @endif
                </a>
            </article>

            @if (! empty($secondaryResources))
                <ol class="mt-8 grid gap-6 lg:grid-cols-3 lg:gap-8" aria-label="Más recursos destacados">
                    @foreach ($secondaryResources as $resource)
                        @php
                            $resourceImage = $imageUrl($resource['image']);
                            $resourceYear = $publicationDate($resource['published_at']);
                        @endphp

                        <li>
                            <a href="{{ $resource['url'] }}" @if($resource['external']) target="_blank" rel="noopener" @endif class="group flex h-full flex-col border-t border-primary pt-5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                @if ($resourceImage)
                                    <div class="overflow-hidden">
                                        <img
                                            src="{{ $resourceImage }}"
                                            alt="{{ $resource['title'] }}"
                                            class="aspect-[4/3] w-full object-cover transition-transform duration-500 group-hover:scale-[1.025] motion-reduce:transition-none"
                                        >
                                    </div>
                                @endif

                                <div class="{{ $resourceImage ? 'mt-6' : '' }} flex flex-1 flex-col">
                                    <p class="font-[var(--font-editorial)] text-base text-primary">
                                        {{ $resource['type_label'] }}@if ($resourceYear), <time datetime="{{ $resource['published_at'] }}">{{ $resourceYear }}</time>@endif
                                    </p>

                                    <h3 class="mt-3 font-[var(--font-display)] text-3xl font-normal leading-[1.04] tracking-[-0.02em] text-primary transition-colors duration-300 group-hover:text-primary/70">
                                        {{ $resource['title'] }}
                                    </h3>

                                    @if ($resource['description'])
                                        <p class="mt-4 font-[var(--font-body)] text-base leading-relaxed text-gray">
                                            {{ $resource['description'] }}
                                        </p>
                                    @endif

                                    <span class="group/cta mt-6 inline-flex min-h-12 w-fit items-center gap-3 border-b border-primary py-2 font-[var(--font-body)] text-base font-semibold text-primary transition-colors duration-200 group-hover:border-accent motion-reduce:transition-none" aria-hidden="true">
                                        <span>Ver publicación</span>
                                        <span class="transition-transform duration-200 group-hover/cta:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span>
                                    </span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </x-block>
@endif
