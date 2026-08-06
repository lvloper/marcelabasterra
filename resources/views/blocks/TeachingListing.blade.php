@php
    $selectedIds = collect($selected_items ?? [])->map(fn ($id) => (int) $id)->filter()->values();
    $levelFilter = collect($levels ?? [])->filter()->values();
    $scopeFilter = collect($scopes ?? ['nacional', 'internacional'])->filter()->values();
    $institutionFilter = collect($institutions ?? [])->map(fn ($id) => (int) $id)->filter()->values();
    $limit = min(max((int) ($max_items ?? 30), 1), 60);

    $teachingQuery = \App\Models\Docencia::query()
        ->with(['route', 'institucionAcademica.route'])
        ->whereHas('route', fn ($route) => $route->where('status', 'published'));

    if ($selectedIds->isNotEmpty()) {
        $teachingQuery->whereIn('id', $selectedIds);
    } else {
        $teachingQuery
            ->when($levelFilter->isNotEmpty(), fn ($query) => $query->whereIn('nivel', $levelFilter))
            ->when($institutionFilter->isNotEmpty(), fn ($query) => $query->whereIn('institucion_academica_id', $institutionFilter))
            ->when($scopeFilter->isNotEmpty(), fn ($query) => $query->whereHas(
                'institucionAcademica',
                fn ($institution) => $institution->whereIn('alcance', $scopeFilter),
            ));
    }

    if ($current_only ?? true) {
        $teachingQuery->where('vigente', true);
    }

    $teachingItems = $teachingQuery
        ->orderBy('orden')
        ->orderBy('id')
        ->limit($limit)
        ->get();

    if ($selectedIds->isNotEmpty()) {
        $teachingItems = $teachingItems
            ->sortBy(fn ($item) => $selectedIds->search($item->id))
            ->values();
    }

    $scopeLabels = [
        'nacional' => 'Universidades nacionales',
        'internacional' => 'Universidades internacionales',
    ];
    $levelLabels = [
        'posgrado' => 'Posgrados',
        'maestria' => 'Maestrías',
        'doctorado' => 'Doctorados',
        'grado' => 'Grado',
        'otro' => 'Otras actividades',
    ];
    $modalityLabels = [
        'presencial' => 'Presencial',
        'distancia' => 'A distancia',
        'hibrida' => 'Híbrida',
    ];
    $levelOrder = ['posgrado', 'maestria', 'doctorado', 'grado', 'otro'];
    $scopeGroups = collect(['nacional', 'internacional'])
        ->mapWithKeys(fn ($scope) => [
            $scope => $teachingItems->filter(fn ($item) => ($item->institucionAcademica?->alcance ?? 'nacional') === $scope),
        ])
        ->filter(fn ($items) => $items->isNotEmpty());
    $displayedInstitutions = $teachingItems
        ->pluck('institucionAcademica')
        ->filter()
        ->unique('id')
        ->filter(fn ($institution) => $institution->destacada)
        ->sortBy('orden')
        ->values();
@endphp

@if ($teachingItems->isNotEmpty())
    <x-block class="bg-gray-3 py-16 md:py-20 lg:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            <header class="grid gap-6 border-t border-primary pt-6 lg:grid-cols-12 lg:gap-8 lg:pt-8">
                @if ($title ?? null)
                    <h2 class="max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary lg:col-span-7">
                        {{ $title }}
                    </h2>
                @endif
                @if ($description ?? null)
                    <p class="max-w-[48ch] self-end font-[var(--font-editorial)] text-xl leading-[1.55] text-gray md:text-2xl lg:col-span-4 lg:col-start-9">
                        {{ $description }}
                    </p>
                @endif
            </header>

            <div class="mt-12 grid gap-12 lg:grid-cols-12 lg:gap-10">
                <div class="space-y-12 lg:col-span-9">
                    <dl class="grid border-l border-t border-primary/25 sm:grid-cols-3" aria-label="Resumen de actividad docente">
                        @foreach ($levelOrder as $level)
                            @php
                                $levelCount = $teachingItems->where('nivel', $level)->count();
                            @endphp
                            @if ($levelCount > 0)
                                <div class="border-b border-r border-primary/25 bg-white p-5 sm:p-6">
                                    <dt class="font-[var(--font-body)] text-sm font-semibold text-gray">{{ $levelLabels[$level] ?? ucfirst($level) }}</dt>
                                    <dd class="mt-3 font-[var(--font-editorial)] text-4xl leading-none text-primary">{{ $levelCount }}</dd>
                                </div>
                            @endif
                        @endforeach
                    </dl>

                    <div class="grid gap-10 xl:grid-cols-2 xl:gap-12">
                        @foreach ($scopeGroups as $scope => $scopeItems)
                            <section aria-labelledby="{{ $id }}-{{ $scope }}">
                                <header class="flex min-h-20 items-end justify-between gap-4 border-b border-primary pb-5">
                                    <h3 id="{{ $id }}-{{ $scope }}" class="max-w-[18ch] font-[var(--font-display)] text-3xl font-normal leading-[1.02] tracking-[-.02em] text-primary md:text-4xl">
                                        {{ $scopeLabels[$scope] ?? ucfirst($scope) }}
                                    </h3>
                                    <span class="pb-1 font-[var(--font-body)] text-sm text-gray">{{ $scopeItems->groupBy('institucion_academica_id')->count() }} instituciones</span>
                                </header>

                                <div>
                                    @foreach ($scopeItems->groupBy('institucion_academica_id') as $institutionItems)
                                        @php
                                            $institution = $institutionItems->first()->institucionAcademica;
                                            $institutionKey = $institution?->id ?? \Illuminate\Support\Str::slug((string) $institutionItems->first()->institucion);
                                        @endphp
                                        <details class="group border-b border-primary/25 bg-transparent open:bg-white">
                                            <summary class="flex min-h-24 cursor-pointer list-none items-center justify-between gap-5 py-5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent [&::-webkit-details-marker]:hidden">
                                                <span>
                                                    <span class="block font-[var(--font-editorial)] text-xl leading-tight text-primary md:text-2xl">
                                                        {{ $institution?->title ?: $institutionItems->first()->institucion }}
                                                    </span>
                                                    <span class="mt-2 block font-[var(--font-body)] text-sm text-gray">
                                                        {{ $institution?->pais }} · {{ $institutionItems->count() }} {{ $institutionItems->count() === 1 ? 'actividad' : 'actividades' }}
                                                    </span>
                                                </span>
                                                <span class="flex min-h-11 min-w-11 shrink-0 items-center justify-center border border-primary font-[var(--font-body)] text-xl text-primary transition-transform duration-200 group-open:rotate-45 motion-reduce:transition-none" aria-hidden="true">+</span>
                                            </summary>

                                            <div class="border-t border-primary/15 px-5 pb-7 pt-6 sm:px-6">
                                                @if ($institutionItems->first()->facultad)
                                                    <p class="mb-6 font-[var(--font-body)] text-sm text-gray">{{ $institutionItems->first()->facultad }}</p>
                                                @endif
                                                <div class="space-y-8">
                                                    @foreach ($levelOrder as $level)
                                                        @php
                                                            $levelItems = $institutionItems->where('nivel', $level);
                                                        @endphp
                                                        @if ($levelItems->isNotEmpty())
                                                            <section aria-labelledby="{{ $id }}-{{ $scope }}-{{ $institutionKey }}-{{ $level }}">
                                                                <h4 id="{{ $id }}-{{ $scope }}-{{ $institutionKey }}-{{ $level }}" class="font-[var(--font-body)] text-sm font-semibold text-primary">
                                                                    {{ $levelLabels[$level] ?? ucfirst($level) }}
                                                                </h4>
                                                                <ul class="mt-4 space-y-6">
                                                                    @foreach ($levelItems as $item)
                                                                        <li class="border-l border-accent pl-4">
                                                                            <p class="font-[var(--font-editorial)] text-lg leading-snug text-primary">{{ $item->programa ?: $item->title }}</p>
                                                                            @if ($item->materia)
                                                                                <p class="mt-2 font-[var(--font-body)] text-base leading-relaxed text-gray">{{ $item->materia }}</p>
                                                                            @endif
                                                                            <p class="mt-3 font-[var(--font-body)] text-sm text-gray">
                                                                                {{ collect([$item->periodo, $modalityLabels[$item->modalidad] ?? $item->modalidad])->filter()->implode(' · ') }}
                                                                            </p>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </section>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </details>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>

                @if (! empty($student_resources ?? []))
                    <aside class="lg:col-span-3" aria-labelledby="{{ $id }}-student-resources">
                        <div class="border-t border-primary bg-white p-6 lg:sticky lg:top-28 lg:p-7">
                            <h3 id="{{ $id }}-student-resources" class="font-[var(--font-display)] text-3xl font-normal leading-tight text-primary">
                                Material para alumnos
                            </h3>
                            <nav class="mt-6" aria-label="Material para alumnos">
                                <ul class="divide-y divide-primary/20 border-b border-primary/20">
                                    @foreach ($student_resources as $resource)
                                        @if (($resource['label'] ?? null) && ($resource['url'] ?? null))
                                            <li>
                                                <a href="{{ $resource['url'] }}" target="_blank" rel="noopener noreferrer" class="group flex min-h-14 items-center justify-between gap-4 py-3 font-[var(--font-body)] text-base font-semibold leading-snug text-primary transition-colors duration-200 hover:text-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none">
                                                    <span>{{ $resource['label'] }}</span>
                                                    <span class="shrink-0 text-accent transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">↗</span>
                                                    <span class="sr-only"> (se abre en una pestaña nueva)</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                    </aside>
                @endif
            </div>

            @if (($show_institutions ?? true) && $displayedInstitutions->isNotEmpty())
                <section class="mt-16 border-t border-primary pt-7 md:mt-20" aria-labelledby="{{ $id }}-institutions">
                    <div class="grid gap-6 lg:grid-cols-12">
                        <div class="lg:col-span-4">
                            <h3 id="{{ $id }}-institutions" class="max-w-[18ch] font-[var(--font-display)] text-3xl font-normal leading-tight text-primary md:text-4xl">
                                Universidades e instituciones
                            </h3>
                        </div>
                        <p class="max-w-[48ch] font-[var(--font-editorial)] text-lg leading-relaxed text-gray lg:col-span-6 lg:col-start-7">
                            Instituciones en las que ha desarrollado actividades docentes, de investigación y formación académica.
                        </p>
                    </div>

                    <ul class="mt-9 grid grid-cols-2 border-l border-t border-primary/25 sm:grid-cols-3 lg:grid-cols-4" aria-label="Instituciones académicas">
                        @foreach ($displayedInstitutions as $institution)
                            @php
                                $logoValue = is_array($institution->logo) ? ($institution->logo[0] ?? null) : $institution->logo;
                                $logoUrl = $logoValue
                                    ? (filter_var($logoValue, FILTER_VALIDATE_URL) ? $logoValue : \Illuminate\Support\Facades\Storage::url($logoValue))
                                    : null;
                            @endphp
                            <li class="border-b border-r border-primary/25 bg-white">
                                <a href="{{ $institution->sitio_web ?: $institution->url }}" @if($institution->sitio_web) target="_blank" rel="noopener noreferrer" @endif class="group flex min-h-44 flex-col justify-between p-5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-accent sm:min-h-48 sm:p-6">
                                    @if ($logoUrl)
                                        <img src="{{ $logoUrl }}" alt="{{ $institution->title }}" class="h-16 w-full object-contain object-left grayscale transition duration-300 group-hover:grayscale-0 motion-reduce:transition-none" loading="lazy">
                                    @else
                                        <span class="font-[var(--font-editorial)] text-[clamp(2rem,4vw,3.5rem)] leading-none text-primary" aria-hidden="true">
                                            {{ $institution->sigla ?: \Illuminate\Support\Str::of($institution->title)->explode(' ')->filter()->take(3)->map(fn ($word) => \Illuminate\Support\Str::substr($word, 0, 1))->implode('') }}
                                        </span>
                                    @endif
                                    <span class="mt-6 font-[var(--font-body)] text-sm font-semibold leading-snug text-primary">
                                        {{ $institution->title }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </x-block>
@endif
