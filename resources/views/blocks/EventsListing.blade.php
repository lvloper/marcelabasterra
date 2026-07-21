@php
    $manualItems = collect($manual_items ?? [])->filter(fn ($item) => filled($item['title'] ?? null) && filled($item['url'] ?? null));
    $timezone = config('app.timezone', 'America/Argentina/Buenos_Aires');
    $today = now($timezone)->startOfDay();
    $selectedIds = collect($selected_events ?? [])
        ->filter(fn ($id) => filled($id))
        ->map(fn ($id) => (int) $id)
        ->values();
    $limit = min(max((int) ($max_items ?? 12), 1), 50);

    $eventsQuery = \App\Models\Evento::query()->with('route');

    if ($selectedIds->isNotEmpty()) {
        $events = $eventsQuery
            ->whereIn('id', $selectedIds)
            ->get()
            ->sortBy(fn (\App\Models\Evento $event) => $selectedIds->search($event->id))
            ->take($limit)
            ->values();
    } else {
        $events = match ($status ?? 'upcoming') {
            'past' => $eventsQuery->where('fecha_inicio', '<', $today)->orderByDesc('fecha_inicio'),
            'all' => $eventsQuery->orderByDesc('fecha_inicio'),
            default => $eventsQuery->where('fecha_inicio', '>=', $today)->orderBy('fecha_inicio'),
        };

        $events = $events
            ->when(filled($event_types ?? []), fn ($query) => $query->whereIn('tipo', $event_types))
            ->limit($limit)
            ->get();
    }

    $statusLabels = [
        'upcoming' => 'Próximas actividades',
        'past' => 'Actividades realizadas',
        'all' => 'Agenda y archivo',
    ];
    $typeLabels = [
        'jornada' => 'Jornada',
        'congreso' => 'Congreso',
        'clase' => 'Clase',
        'conferencia' => 'Conferencia',
        'exposicion' => 'Exposición',
        'panel' => 'Panel',
        'presentacion' => 'Presentación',
        'seminario' => 'Seminario',
        'taller' => 'Taller',
        'otro' => 'Actividad',
    ];
    $confirmationLabels = [
        'pendiente' => 'Confirmación pendiente',
        'confirmado' => 'Participación confirmada',
        'cancelado' => 'Actividad cancelada',
    ];
    $fallbackRoute = is_array($fallback_route ?? null) ? $fallback_route : [];
    $fallbackRouteId = $fallbackRoute['route_id'] ?? null;
    $hasFallbackDestination = (! empty($fallbackRoute['external_url']))
        || ((is_numeric($fallbackRouteId) && (int) $fallbackRouteId >= 1))
        || ((string) $fallbackRouteId === '-1' && ! empty($fallbackRoute['file']));
    $showFallback = (bool) ($show_empty_fallback ?? false) && $hasFallbackDestination;
@endphp

@if ($manualItems->isNotEmpty())
    <x-block class="border-y border-gray-2 bg-white py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            <header class="grid gap-6 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-8">
                    <p class="mb-5 font-source text-sm text-primary"><span class="mr-3 text-accent" aria-hidden="true">—</span>Archivo audiovisual</p>
                    @if ($title ?? null)<h2 class="max-w-[15ch] font-sans text-[clamp(2.75rem,5vw,4.75rem)] font-normal leading-[.96] tracking-[-.035em] text-primary">{{ $title }}</h2>@endif
                </div>
                @if ($description ?? null)<p class="font-source text-xl leading-snug text-gray lg:col-span-4">{{ $description }}</p>@endif
            </header>
            <ol class="mt-10 grid gap-x-7 gap-y-10 md:grid-cols-2 lg:grid-cols-6">
                @foreach ($manualItems as $item)
                    @php
                        $image = is_array($item['image'] ?? null) ? ($item['image'][0] ?? null) : ($item['image'] ?? null);
                        $youtubeId = null;
                        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/))([^&?/]+)~', $item['url'], $match)) $youtubeId = $match[1];
                        $imageUrl = $image ? \Illuminate\Support\Facades\Storage::url($image) : ($youtubeId ? "https://i.ytimg.com/vi/{$youtubeId}/hqdefault.jpg" : null);
                    @endphp
                    <li class="{{ $loop->index < 2 ? 'lg:col-span-3' : 'lg:col-span-2' }}">
                        <article class="group h-full">
                            @if ($imageUrl)<a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" class="block overflow-hidden bg-gray-3"><img src="{{ $imageUrl }}" alt="" class="aspect-video w-full object-cover transition-transform duration-500 group-hover:scale-[1.02] motion-reduce:transition-none" loading="lazy"></a>@endif
                            <div class="pt-4">
                                <p class="font-source text-sm text-primary">{{ ucfirst($item['type'] ?? 'conferencia') }}@if ($item['institution'] ?? null) · {{ $item['institution'] }}@endif</p>
                                <h3 class="mt-2 font-sans text-2xl font-normal leading-[1.05] tracking-[-.02em] text-primary">{{ $item['title'] }}</h3>
                                @if ($item['description'] ?? null)<p class="mt-3 line-clamp-2 font-body text-sm leading-relaxed text-gray">{{ $item['description'] }}</p>@endif
                                <div class="mt-4 flex items-center justify-between gap-4">
                                    @if ($item['date'] ?? null)<time datetime="{{ $item['date'] }}" class="font-source text-sm text-gray">{{ \Illuminate\Support\Carbon::parse($item['date'])->locale('es')->translatedFormat('d \d\e F \d\e Y') }}</time>@endif
                                    <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" class="border-b border-primary font-body text-sm text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">{{ $item['link_label'] ?? 'Ver conferencia' }} ↗</a>
                                </div>
                            </div>
                        </article>
                    </li>
                @endforeach
            </ol>
        </div>
    </x-block>
@elseif ($events->isNotEmpty() || $showFallback)
    <x-block class="border-y border-gray-2 bg-[var(--color-surface-ivory)] py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            @if ($title ?? $description ?? false)
                <div class="grid grid-cols-1 gap-8 border-b border-primary pb-10 lg:grid-cols-12 lg:items-end lg:gap-12">
                    <div class="lg:col-span-7">
                        <p class="mb-5 flex items-center gap-3 font-source text-sm leading-none text-primary">
                            <span class="h-px w-10 bg-accent" aria-hidden="true"></span>
                            {{ $statusLabels[$status ?? 'upcoming'] ?? $statusLabels['upcoming'] }}
                        </p>
                        @if ($title ?? false)
                            <h2 class="max-w-[15ch] font-sans text-[clamp(2.5rem,5vw,4.5rem)] font-normal leading-[0.98] tracking-[-0.03em] text-primary">{{ $title }}</h2>
                        @endif
                    </div>
                    @if ($description ?? false)
                        <p class="max-w-[48ch] font-source text-xl leading-snug text-gray sm:text-2xl lg:col-span-4 lg:col-start-9">{{ $description }}</p>
                    @endif
                </div>
            @endif

            @if ($events->isNotEmpty())
                <ol class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-2 lg:mt-12 lg:grid-cols-12 lg:gap-8">
                    @foreach ($events as $event)
                        @php
                            $eventTitle = $event->title ?: 'Actividad';
                            $eventUrl = $event->route ? $event->url : null;
                            $eventImage = $event->imagen ?: $event->route?->image;
                            $eventImageUrl = $eventImage ? \Illuminate\Support\Facades\Storage::url($eventImage) : null;
                            $eventDescription = \Illuminate\Support\Str::squish(strip_tags($event->descripcion ?? ''));
                            $meta = array_filter([
                                $event->institucion,
                                $event->ubicacion,
                                $event->modalidad ? ucfirst($event->modalidad) : null,
                            ]);
                            $columnClass = $loop->first
                                ? 'md:col-span-2 lg:col-span-7'
                                : 'lg:col-span-5';
                        @endphp
                        <li class="{{ $columnClass }}">
                            <article class="group flex h-full flex-col border border-gray-2 bg-white transition-colors duration-300 motion-reduce:transition-none {{ $eventUrl ? 'hover:border-primary' : '' }}">
                                @if ($show_image ?? true)
                                    @if ($eventImageUrl)
                                        <div class="aspect-[16/9] overflow-hidden bg-gray-3">
                                            <img
                                                src="{{ $eventImageUrl }}"
                                                alt="{{ $eventTitle }}"
                                                class="h-full w-full object-cover transition-transform duration-500 motion-reduce:transition-none {{ $eventUrl ? 'group-hover:scale-[1.02]' : '' }}"
                                                loading="lazy"
                                            >
                                        </div>
                                    @endif
                                @endif

                                <div class="flex flex-1 flex-col p-6 sm:p-8">
                                    <div class="flex items-start justify-between gap-5 border-b border-gray-2 pb-5">
                                        <p class="font-source text-xl leading-none text-primary sm:text-2xl">
                                            @if ($event->fecha_inicio)
                                                <time datetime="{{ $event->fecha_inicio->toDateString() }}">{{ $event->fecha_inicio->locale('es')->isoFormat('D MMM') }}</time>
                                            @else
                                                <span>Sin fecha</span>
                                            @endif
                                        </p>
                                        @if ($event->tipo)
                                            <p class="font-body text-sm leading-snug text-gray">{{ $typeLabels[$event->tipo] ?? $event->tipo }}</p>
                                        @endif
                                    </div>

                                    <div class="pt-6">
                                        @if ($eventUrl)
                                            <a href="{{ $eventUrl }}" class="group inline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                                <h3 class="font-sans text-3xl font-normal leading-[1.04] tracking-[-0.02em] text-primary transition-colors duration-300 group-hover:text-gray motion-reduce:transition-none">{{ $eventTitle }}</h3>
                                            </a>
                                        @else
                                            <h3 class="font-sans text-3xl font-normal leading-[1.04] tracking-[-0.02em] text-primary">{{ $eventTitle }}</h3>
                                        @endif

                                        @if ($event->rol)
                                            <p class="mt-4 font-source text-lg leading-snug text-primary">{{ $event->rol }}</p>
                                        @endif

                                        @if ($meta)
                                            <p class="mt-4 font-body text-base leading-relaxed text-gray">{{ implode(' · ', $meta) }}</p>
                                        @endif

                                        @if (($show_description ?? true) && $eventDescription)
                                            <p class="mt-5 max-w-[58ch] font-body text-base leading-relaxed text-gray">{{ $eventDescription }}</p>
                                        @endif
                                    </div>

                                    <div class="mt-8 flex flex-wrap items-end justify-between gap-x-5 gap-y-4 border-t border-gray-2 pt-5">
                                        <div class="font-body text-sm leading-snug text-gray">
                                            @if ($event->fecha_inicio)
                                                <p>
                                                    <time datetime="{{ $event->fecha_inicio->toIso8601String() }}">{{ $event->fecha_inicio->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY, HH:mm') }}</time>
                                                    @if ($event->fecha_fin)
                                                        <span aria-hidden="true"> — </span>
                                                        <time datetime="{{ $event->fecha_fin->toIso8601String() }}">{{ $event->fecha_fin->locale('es')->isoFormat($event->fecha_inicio->isSameDay($event->fecha_fin) ? 'HH:mm' : 'D [de] MMMM, HH:mm') }}</time>
                                                    @endif
                                                </p>
                                            @endif
                                            @if ($event->estado_confirmacion)
                                                <p class="mt-2 text-primary">{{ $confirmationLabels[$event->estado_confirmacion] ?? $event->estado_confirmacion }}</p>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap gap-4 font-body text-base">
                                            @if ($eventUrl)
                                                <a href="{{ $eventUrl }}" class="group inline-flex min-h-11 items-center border-b border-primary text-primary transition-colors duration-300 hover:text-gray focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none">
                                                    Ver actividad <span class="ml-2 transition-transform duration-300 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span>
                                                </a>
                                            @endif
                                            @if ($event->enlace_inscripcion)
                                                <a href="{{ $event->enlace_inscripcion }}" target="_blank" rel="noopener noreferrer" class="group inline-flex min-h-11 items-center border-b border-primary text-primary transition-colors duration-300 hover:text-gray focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none">
                                                    Inscripción <span class="ml-2 transition-transform duration-300 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">↗</span>
                                                </a>
                                            @endif
                                            @if ($event->video)
                                                <a href="{{ $event->video }}" target="_blank" rel="noopener noreferrer" class="group inline-flex min-h-11 items-center border-b border-primary text-primary transition-colors duration-300 hover:text-gray focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none">
                                                    Ver video <span class="ml-2 transition-transform duration-300 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">↗</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ol>
            @elseif ($showFallback)
                <div class="mt-10 max-w-[720px] border-l border-accent pl-6 sm:mt-12 sm:pl-8">
                    <x-link :attrs="array_merge($fallbackRoute, ['hideIfNull' => true])" class="group inline-flex min-h-12 items-center border-b border-primary font-body text-base text-primary transition-colors duration-300 hover:text-gray focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none">
                        {{ $fallback_label ?: 'Ver actividades realizadas' }} <span class="ml-3 transition-transform duration-300 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span>
                    </x-link>
                </div>
            @endif
        </div>
    </x-block>
@endif
