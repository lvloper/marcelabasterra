@php
    use App\Models\Conferencia;
    use App\Support\AcademicProductionCatalog;
    use App\Support\EventCatalog;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $conferenceIds = collect($conferencias ?? [])->filter()->map(fn ($id): int => (int) $id)->values();
    $displayMode = $display_mode ?? ($conferenceIds->isNotEmpty() ? 'videos' : 'activities');
    $limit = min(max((int) ($max_items ?? 12), 1), 50);
    $id = 'events-videos-'.Str::uuid();
    $imageUrl = static fn (?string $image): ?string => blank($image)
        ? null
        : (filter_var($image, FILTER_VALIDATE_URL) ? $image : Storage::url($image));

    if ($displayMode === 'videos') {
        if ($conferenceIds->isNotEmpty()) {
            $videoItems = Conferencia::query()->with('route')->isPublished()->whereIn('id', $conferenceIds)->get()
                ->sortBy(fn (Conferencia $item): int => $conferenceIds->search($item->id))
                ->map(fn (Conferencia $item): array => [
                    'title' => $item->title,
                    'type_label' => EventCatalog::TYPE_LABELS[$item->tipo] ?? Str::headline($item->tipo),
                    'institution' => $item->institucion,
                    'date' => $item->fecha?->toDateString(),
                    'image' => $item->imagen,
                    'url' => $item->external_url,
                ])->filter(fn (array $item): bool => filled($item['url']))->take($limit)->values();
        } else {
            $videoItems = app(AcademicProductionCatalog::class)->all()
                ->filter(fn (array $item): bool => in_array('videos', $item['categories'], true) && filled($item['url']))
                ->take($limit)->map(fn (array $item): array => [
                    'title' => $item['title'],
                    'type_label' => $item['category_label'],
                    'institution' => $item['institution'] ?: $item['medium'],
                    'date' => $item['date'],
                    'image' => $item['image'],
                    'url' => $item['url'],
                ])->values();
        }
    } else {
        $catalog = app(EventCatalog::class);
        $eventIds = collect($selected_events ?? [])->filter()->map(fn ($id): int => (int) $id)->values();
        $activityItems = $catalog->all(
            $eventIds->isNotEmpty() ? $eventIds : null,
            $conferenceIds->isNotEmpty() ? $conferenceIds : null,
            (bool) ($include_conferences ?? true),
        );
        $activityItems = $catalog->filter($activityItems, $status ?? 'all', array_values($event_types ?? []))->take($limit)->values();
        $years = $catalog->years($activityItems);
        $countries = $catalog->countries($activityItems);
        $types = $catalog->types($activityItems);
        $filterId = 'event-filters-'.Str::uuid();
    }
@endphp

@if ($displayMode === 'videos' && ($videoItems ?? collect())->isNotEmpty())
    <x-block class="border-y border-gray-2 bg-gray-3 py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            <header class="grid gap-8 lg:grid-cols-12 lg:items-end lg:gap-12">
                <div class="lg:col-span-7">
                    <p class="mb-4 font-source text-2xl text-gray">Archivo audiovisual</p>
                    @if ($title ?? null)<h2 class="max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary">{{ $title }}</h2>@endif
                </div>
                @if ($description ?? null)<p class="max-w-[46ch] font-source text-xl leading-relaxed text-gray lg:col-span-4 lg:col-start-9">{{ $description }}</p>@endif
            </header>

            <div class="relative mt-12">
                <swiper-container class="carousel-videos" slides-per-view="auto" space-between="24"
                    navigation-next-el=".{{ $id }}-swiper-button-next"
                    navigation-prev-el=".{{ $id }}-swiper-button-prev">
                    @foreach ($videoItems as $item)
                        @php($poster = $imageUrl($item['image']))
                        <swiper-slide class="{{ $loop->first ? 'carousel-videos-principal' : 'carousel-videos-item' }}">
                            <div class="group relative h-full">
                                <article class="flex h-full flex-col overflow-hidden border border-primary bg-white transition-colors duration-500 group-hover:bg-primary">
                                    <div class="flex items-baseline justify-between gap-4 border-b border-primary px-5 py-3 transition-colors duration-500 group-hover:border-white/40">
                                        @if($item['date'])<time datetime="{{ $item['date'] }}" class="font-source text-xs text-gray transition-colors duration-500 group-hover:text-gray-3">{{ Carbon::parse($item['date'])->locale('es')->translatedFormat('d \d\e F \d\e Y') }}</time>@endif
                                        <p class="ml-auto font-body text-xs text-gray transition-colors duration-500 group-hover:text-gray-3">{{ $item['type_label'] }}@if($item['institution']) · {{ $item['institution'] }}@endif</p>
                                    </div>
                                    @if ($poster)
                                        <div class="overflow-hidden border-b border-primary transition-colors duration-500 group-hover:border-white/40">
                                            <img src="{{ $poster }}" alt="" class="aspect-video w-full object-cover transition-transform duration-[2500ms] ease-out group-hover:scale-150 motion-reduce:transition-none" loading="lazy">
                                        </div>
                                    @endif
                                    <div class="flex flex-1 flex-col p-5">
                                        <h3 class="font-sans text-[clamp(1.15rem,1.35vw,1.4rem)] font-normal leading-[1.12] tracking-[-0.02em] text-primary transition-colors duration-500 group-hover:text-white">{{ $item['title'] }}</h3>
                                        <div class="mt-auto pt-5">
                                            <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" class="group/cta relative z-20 inline-flex min-h-11 items-center justify-center border border-primary px-4 font-body text-xs font-semibold text-primary transition-colors duration-300 group-hover:border-white group-hover:bg-white group-hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">REPRODUCIR <span class="ml-2 transition-transform duration-500 ease-out group-hover:translate-x-1.5" aria-hidden="true">→</span></a>
                                        </div>
                                    </div>
                                </article>
                                <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="Reproducir {{ $item['title'] }} (se abre en una pestaña nueva)" class="absolute inset-0 z-10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"></a>
                            </div>
                        </swiper-slide>
                    @endforeach
                </swiper-container>

                <button type="button" aria-label="Videos anteriores" class="{{ $id }}-swiper-button-prev absolute top-1/2 left-0 z-10 flex min-h-12 min-w-12 -translate-y-1/2 -translate-x-1/2 items-center justify-center border border-primary bg-white transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent swiper-custom-buttons">
                    <x-lucide-chevron-left class="h-6 w-6 stroke-2" />
                    <span class="sr-only">Retroceder</span>
                </button>
                <button type="button" aria-label="Ver más videos" class="{{ $id }}-swiper-button-next absolute top-1/2 right-0 z-10 flex min-h-12 min-w-12 -translate-y-1/2 translate-x-1/2 items-center justify-center border border-primary bg-white transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent swiper-custom-buttons">
                    <x-lucide-chevron-right class="h-6 w-6 stroke-2" />
                    <span class="sr-only">Avanzar</span>
                </button>
            </div>
        </div>
    </x-block>
@elseif ($displayMode === 'activities')
    <x-block class="border-y border-gray-2 bg-gray-3 py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16"
             @if (($show_filters ?? true) && $activityItems->isNotEmpty())
                 x-data="{ status: 'all', year: '', country: '', type: '', visible: {{ $activityItems->count() }}, matches(item) { return (this.status === 'all' || item.dataset.status === this.status) && (!this.year || item.dataset.year === this.year) && (!this.country || item.dataset.country === this.country) && (!this.type || item.dataset.type === this.type) } }"
             @endif>
            <header class="grid gap-8 lg:grid-cols-12 lg:items-end lg:gap-12">
                <div class="lg:col-span-7">
                    <p class="mb-4 font-source text-2xl text-gray">Agenda y archivo</p>
                    @if ($title ?? null)<h2 class="max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary">{{ $title }}</h2>@endif
                </div>
                @if ($description ?? null)<p class="max-w-[48ch] font-source text-xl leading-relaxed text-gray lg:col-span-4 lg:col-start-9">{{ $description }}</p>@endif
            </header>

            @if (($show_filters ?? true) && $activityItems->isNotEmpty())
                <div id="{{ $filterId }}" class="mt-12 border border-gray-2 bg-white px-6 py-6" aria-label="Filtros de eventos">
                    <div class="grid gap-6 lg:grid-cols-12 lg:items-end">
                        <fieldset class="lg:col-span-5">
                            <legend class="mb-3 font-body text-sm font-semibold text-primary">Estado</legend>
                            <div class="grid grid-cols-3">
                                @foreach (['all' => 'Todos', 'upcoming' => 'Próximos', 'past' => 'Realizados'] as $value => $label)
                                    <button type="button" @click="status = '{{ $value }}'" :aria-pressed="status === '{{ $value }}'" :class="status === '{{ $value }}' ? 'border-primary bg-primary text-white' : 'border-gray-2 bg-transparent text-primary hover:border-primary'" class="min-h-12 border border-gray-2 px-3 font-body text-sm font-semibold transition-colors duration-200 first:border-r-0 last:border-l-0 focus-visible:z-10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">{{ $label }}</button>
                                @endforeach
                            </div>
                        </fieldset>

                        <div class="grid gap-5 sm:grid-cols-3 lg:col-span-7">
                            <label class="font-body text-sm font-semibold text-primary">Año
                                <select x-model="year" class="mt-3 min-h-12 w-full border border-gray-2 bg-transparent px-3 font-body text-base font-normal text-primary focus:border-primary focus:ring-2 focus:ring-accent">
                                    <option value="">Todos los años</option>
                                    @foreach ($years as $year)<option value="{{ $year }}">{{ $year }}</option>@endforeach
                                </select>
                            </label>
                            <label class="font-body text-sm font-semibold text-primary">País
                                <select x-model="country" class="mt-3 min-h-12 w-full border border-gray-2 bg-transparent px-3 font-body text-base font-normal text-primary focus:border-primary focus:ring-2 focus:ring-accent">
                                    <option value="">Todos los países</option>
                                    @foreach ($countries as $country)<option value="{{ $country }}">{{ $country }}</option>@endforeach
                                </select>
                            </label>
                            <label class="font-body text-sm font-semibold text-primary">Tipo de actividad
                                <select x-model="type" class="mt-3 min-h-12 w-full border border-gray-2 bg-transparent px-3 font-body text-base font-normal text-primary focus:border-primary focus:ring-2 focus:ring-accent">
                                    <option value="">Todos los tipos</option>
                                    @foreach ($types as $type)<option value="{{ $type }}">{{ EventCatalog::TYPE_LABELS[$type] ?? Str::headline($type) }}</option>@endforeach
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
            @endif

            @if ($activityItems->isNotEmpty())
                <ol x-ref="list" @if (($show_filters ?? true)) x-effect="visible = Array.from($refs.list.children).filter(item => matches(item)).length" @endif class="{{ ($show_filters ?? true) ? 'mt-6' : 'mt-12' }} grid gap-6 md:grid-cols-2 lg:grid-cols-3" aria-label="Congresos, jornadas, seminarios y conferencias">
                    @foreach ($activityItems as $item)
                        @php($poster = ($show_image ?? true) ? $imageUrl($item['image']) : null)
                        <li data-status="{{ $item['is_upcoming'] ? 'upcoming' : 'past' }}" data-year="{{ $item['year'] }}" data-country="{{ $item['country'] }}" data-type="{{ $item['type'] }}" @if (($show_filters ?? true)) x-show="matches($el)" @endif>
                            <article class="group relative flex h-full flex-col border border-gray-2 bg-white transition-colors duration-300 hover:border-primary">
                                @if ($item['url'])
                                    <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif aria-label="Ver detalles: {{ $item['title'] }}" class="absolute inset-0 z-10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"></a>
                                @endif
                                @if ($poster)
                                    <div class="overflow-hidden bg-gray-3">
                                        <img src="{{ $poster }}" alt="" class="aspect-[3/2] w-full object-cover transition-transform duration-500 group-hover:scale-[1.02] motion-reduce:transition-none" loading="lazy">
                                    </div>
                                @endif
                                <div class="flex flex-1 flex-col p-6">
                                    @if ($item['date'])
                                        <div class="flex items-center gap-3">
                                            <time datetime="{{ $item['date'] }}" class="font-source text-sm text-primary">{{ Carbon::parse($item['date'])->locale('es')->translatedFormat('d \d\e F \d\e Y') }}</time>
                                            @if ($item['is_upcoming'])<span class="border-l border-accent pl-3 font-body text-sm font-semibold text-primary">Próximo</span>@endif
                                        </div>
                                    @elseif ($item['is_upcoming'])
                                        <span class="border-l border-accent pl-3 font-body text-sm font-semibold text-primary">Próximo</span>
                                    @endif
                                    <p class="mt-4 font-body text-sm text-gray">{{ $item['type_label'] }}@if($item['institution']) · {{ $item['institution'] }}@endif</p>
                                    <h3 class="mt-2 font-sans text-[1.45rem] font-normal leading-[1.08] tracking-[-0.02em] text-primary transition-colors duration-300 group-hover:text-primary/70">{{ $item['title'] }}</h3>
                                    @if ($item['location'])<p class="mt-3 font-body text-base text-gray">{{ $item['location'] }}</p>@endif
                                    @if (($show_description ?? true) && $item['summary'])<p class="mt-3 font-body text-base leading-relaxed text-gray">{{ Str::limit($item['summary'], 180) }}</p>@endif
                                    @if ($item['url'])
                                        <div class="mt-auto pt-6">
                                            <span class="relative z-20 inline-flex min-h-11 items-center border-b border-primary font-body text-sm font-semibold text-primary transition-colors duration-200 group-hover:border-accent group-hover:text-accent" aria-hidden="true">Ver detalles <span class="ml-2 transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">{{ $item['external'] ? '↗' : '→' }}</span></span>
                                        </div>
                                    @endif
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ol>

                @if (($show_filters ?? true))
                    <p x-show="visible === 0" x-cloak class="border border-gray-2 bg-white px-6 py-10 font-source text-xl text-gray" aria-live="polite">{{ $empty_message ?? 'No hay actividades que coincidan con los filtros seleccionados.' }}</p>
                @endif
            @elseif ($show_empty_fallback ?? false)
                <p class="mt-12 border border-gray-2 bg-white px-6 py-10 font-source text-xl text-gray">{{ $fallback_label ?? 'No hay actividades publicadas en este momento.' }}</p>
            @endif
        </div>
    </x-block>
@endif
