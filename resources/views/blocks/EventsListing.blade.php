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
    <x-block class="border-y border-gray-2 bg-primary py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            <header class="grid gap-6 border-t border-white/40 pt-6 lg:grid-cols-12 lg:items-end lg:gap-12">
                <div class="lg:col-span-7">
                    <p class="mb-4 font-source text-sm text-white"><span class="mr-3 text-accent" aria-hidden="true">—</span>Archivo audiovisual</p>
                    @if ($title ?? null)<h2 class="max-w-[14ch] font-sans text-[clamp(2.75rem,5vw,4.75rem)] font-normal leading-[0.96] tracking-[-0.035em] text-white">{{ $title }}</h2>@endif
                </div>
                @if ($description ?? null)<p class="max-w-[46ch] font-source text-xl leading-relaxed text-gray-3 lg:col-span-4 lg:col-start-9">{{ $description }}</p>@endif
            </header>

            <ol class="mt-10 grid gap-x-7 gap-y-10 md:grid-cols-2 lg:grid-cols-6" aria-label="Videos">
                @foreach ($videoItems as $item)
                    @php($poster = $imageUrl($item['image']))
                    <li class="{{ $loop->index < 2 ? 'lg:col-span-3' : 'lg:col-span-2' }}">
                        <article class="group h-full border-t border-white/40 pt-5">
                            @if ($poster)
                                <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" class="block overflow-hidden bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent" aria-label="Reproducir {{ $item['title'] }} (se abre en una pestaña nueva)">
                                    <img src="{{ $poster }}" alt="" class="aspect-video w-full object-cover transition-transform duration-500 group-hover:scale-[1.02] motion-reduce:transition-none" loading="lazy">
                                </a>
                            @endif
                            <p class="mt-5 font-source text-sm text-gray-3">{{ $item['type_label'] }}@if($item['institution']) · {{ $item['institution'] }}@endif</p>
                            <h3 class="mt-2 font-sans text-[1.75rem] font-normal leading-[1.05] tracking-[-0.02em] text-white">{{ $item['title'] }}</h3>
                            <div class="mt-4 flex items-center justify-between gap-4">
                                @if($item['date'])<time datetime="{{ $item['date'] }}" class="font-source text-sm text-gray-3">{{ Carbon::parse($item['date'])->locale('es')->translatedFormat('d \d\e F \d\e Y') }}</time>@endif
                                <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-11 items-center border-b border-white font-body text-sm font-semibold text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">Reproducir ↗</a>
                            </div>
                        </article>
                    </li>
                @endforeach
            </ol>
        </div>
    </x-block>
@elseif ($displayMode === 'activities')
    <x-block class="border-y border-gray-2 bg-[var(--color-surface-ivory)] py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16"
             @if (($show_filters ?? true) && $activityItems->isNotEmpty())
                 x-data="{ status: 'all', year: '', country: '', type: '', visible: {{ $activityItems->count() }}, matches(item) { return (this.status === 'all' || item.dataset.status === this.status) && (!this.year || item.dataset.year === this.year) && (!this.country || item.dataset.country === this.country) && (!this.type || item.dataset.type === this.type) } }"
             @endif>
            <header class="grid gap-6 border-t border-primary pt-6 lg:grid-cols-12 lg:items-end lg:gap-12">
                <div class="lg:col-span-7">
                    <p class="mb-4 font-source text-sm text-primary"><span class="mr-3 text-accent" aria-hidden="true">—</span>Agenda y archivo</p>
                    @if ($title ?? null)<h2 class="max-w-[15ch] font-sans text-[clamp(2.5rem,5vw,4.5rem)] font-normal leading-[0.98] tracking-[-0.03em] text-primary">{{ $title }}</h2>@endif
                </div>
                @if ($description ?? null)<p class="max-w-[48ch] font-source text-xl leading-relaxed text-gray lg:col-span-4 lg:col-start-9">{{ $description }}</p>@endif
            </header>

            @if (($show_filters ?? true) && $activityItems->isNotEmpty())
                <div id="{{ $filterId }}" class="mt-10 border-y border-primary py-6" aria-label="Filtros de eventos">
                    <div class="grid gap-6 lg:grid-cols-12 lg:items-end">
                        <fieldset class="lg:col-span-5">
                            <legend class="mb-3 font-body text-sm font-semibold text-primary">Estado</legend>
                            <div class="grid grid-cols-3">
                                @foreach (['all' => 'Todos', 'upcoming' => 'Próximos', 'past' => 'Realizados'] as $value => $label)
                                    <button type="button" @click="status = '{{ $value }}'" :aria-pressed="status === '{{ $value }}'" :class="status === '{{ $value }}' ? 'bg-primary text-white' : 'bg-transparent text-primary'" class="min-h-12 border border-primary px-3 font-body text-sm font-semibold transition-colors duration-200 first:border-r-0 last:border-l-0 focus-visible:z-10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">{{ $label }}</button>
                                @endforeach
                            </div>
                        </fieldset>

                        <div class="grid gap-5 sm:grid-cols-3 lg:col-span-7">
                            <label class="font-body text-sm font-semibold text-primary">Año
                                <select x-model="year" class="mt-3 min-h-12 w-full border border-primary bg-transparent px-3 font-body text-base font-normal text-primary focus:border-primary focus:ring-2 focus:ring-accent">
                                    <option value="">Todos los años</option>
                                    @foreach ($years as $year)<option value="{{ $year }}">{{ $year }}</option>@endforeach
                                </select>
                            </label>
                            <label class="font-body text-sm font-semibold text-primary">País
                                <select x-model="country" class="mt-3 min-h-12 w-full border border-primary bg-transparent px-3 font-body text-base font-normal text-primary focus:border-primary focus:ring-2 focus:ring-accent">
                                    <option value="">Todos los países</option>
                                    @foreach ($countries as $country)<option value="{{ $country }}">{{ $country }}</option>@endforeach
                                </select>
                            </label>
                            <label class="font-body text-sm font-semibold text-primary">Tipo de actividad
                                <select x-model="type" class="mt-3 min-h-12 w-full border border-primary bg-transparent px-3 font-body text-base font-normal text-primary focus:border-primary focus:ring-2 focus:ring-accent">
                                    <option value="">Todos los tipos</option>
                                    @foreach ($types as $type)<option value="{{ $type }}">{{ EventCatalog::TYPE_LABELS[$type] ?? Str::headline($type) }}</option>@endforeach
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
            @endif

            @if ($activityItems->isNotEmpty())
                <ol x-ref="list" @if (($show_filters ?? true)) x-effect="visible = Array.from($refs.list.children).filter(item => matches(item)).length" @endif class="{{ ($show_filters ?? true) ? 'mt-4' : 'mt-10' }} border-b border-primary" aria-label="Congresos, jornadas, seminarios y conferencias">
                    @foreach ($activityItems as $item)
                        <li data-status="{{ $item['is_upcoming'] ? 'upcoming' : 'past' }}" data-year="{{ $item['year'] }}" data-country="{{ $item['country'] }}" data-type="{{ $item['type'] }}" @if (($show_filters ?? true)) x-show="matches($el)" @endif class="grid gap-5 border-t border-primary py-7 md:grid-cols-12 md:gap-8 md:py-9">
                            <div class="md:col-span-2">
                                @if ($item['date'])
                                    <time datetime="{{ $item['date'] }}" class="font-source text-[clamp(1.75rem,3vw,3rem)] leading-none text-primary">{{ Carbon::parse($item['date'])->locale('es')->translatedFormat('d M Y') }}</time>
                                @else
                                    <p class="font-source text-lg text-gray">Fecha a confirmar</p>
                                @endif
                                @if ($item['is_upcoming'])<p class="mt-3 border-l border-accent pl-3 font-body text-sm font-semibold text-primary">Próximo</p>@endif
                            </div>
                            <div class="md:col-span-7 lg:col-span-8">
                                <p class="font-source text-sm text-primary">{{ $item['type_label'] }}@if($item['institution']) · {{ $item['institution'] }}@endif</p>
                                <h3 class="mt-2 max-w-[36ch] font-sans text-[clamp(1.75rem,3vw,2.75rem)] font-normal leading-[1.04] tracking-[-0.02em] text-primary">{{ $item['title'] }}</h3>
                                @if ($item['location'])<p class="mt-3 font-body text-base text-gray">{{ $item['location'] }}</p>@endif
                                @if ($item['topic'])<p class="mt-2 font-body text-base text-gray"><span class="font-semibold text-primary">Tema:</span> {{ $item['topic'] }}</p>@endif
                                @if (($show_description ?? true) && $item['summary'])<p class="mt-4 max-w-[68ch] font-body text-base leading-relaxed text-gray">{{ $item['summary'] }}</p>@endif
                            </div>
                            @if ($item['url'])
                                <div class="self-end md:col-span-3 md:text-right lg:col-span-2">
                                    <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif class="group inline-flex min-h-12 items-center border border-primary px-4 font-body text-sm font-semibold text-primary transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">Ver detalles <span class="ml-2 transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">{{ $item['external'] ? '↗' : '→' }}</span></a>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ol>

                @if (($show_filters ?? true))
                    <p x-show="visible === 0" x-cloak class="border-b border-primary py-10 font-source text-xl text-gray" aria-live="polite">{{ $empty_message ?? 'No hay actividades que coincidan con los filtros seleccionados.' }}</p>
                @endif
            @elseif ($show_empty_fallback ?? false)
                <p class="mt-10 border-y border-primary py-10 font-source text-xl text-gray">{{ $fallback_label ?? 'No hay actividades publicadas en este momento.' }}</p>
            @endif
        </div>
    </x-block>
@endif
