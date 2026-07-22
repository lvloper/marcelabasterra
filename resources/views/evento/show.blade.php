<x-layout :notLayout="false">
    @php
        $parentRoute = $evento->route?->parent;
        $isPast = ($evento->fecha_fin ?: $evento->fecha_inicio)?->isPast() ?? false;
        $image = $evento->imagen ?: $evento->route?->image;
        $imageUrl = $image ? (filter_var($image, FILTER_VALIDATE_URL) ? $image : \Illuminate\Support\Facades\Storage::url($image)) : null;
        $location = implode(' · ', array_filter([
            $evento->ubicacion,
            implode(', ', array_filter([$evento->ciudad, $evento->pais])),
        ]));
    @endphp

    <article class="bg-white">
        <header class="border-b border-primary bg-[var(--color-surface-ivory)]">
            <div class="mx-auto grid max-w-[1440px] lg:grid-cols-12">
                <div class="px-5 py-14 sm:px-8 sm:py-20 lg:col-span-7 lg:px-12 lg:py-24 xl:px-16">
                    @if ($parentRoute)
                        <a href="{{ url($parentRoute->full_slug) }}#agenda-y-archivo" class="group inline-flex min-h-11 items-center border-b border-primary font-body text-sm font-semibold text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                            <span class="mr-2 transition-transform duration-200 group-hover:-translate-x-1" aria-hidden="true">←</span> Jornadas y Congresos
                        </a>
                    @endif

                    <p class="mt-10 font-source text-sm text-primary"><span class="mr-3 text-accent" aria-hidden="true">—</span>{{ \App\Support\EventCatalog::TYPE_LABELS[$evento->tipo] ?? 'Actividad académica' }} · {{ $isPast ? 'Realizado' : 'Próximo' }}</p>
                    <h1 class="mt-5 max-w-[14ch] font-sans text-[clamp(3rem,6vw,6rem)] font-normal leading-[0.95] tracking-[-0.035em] text-primary">{{ $evento->title }}</h1>

                    @if ($evento->institucion)
                        <p class="mt-8 max-w-[44ch] font-source text-[clamp(1.4rem,2.4vw,2rem)] leading-tight text-primary">{{ $evento->institucion }}</p>
                    @endif
                </div>

                <dl class="border-t border-primary bg-primary p-6 text-white sm:p-10 lg:col-span-5 lg:border-l lg:border-t-0 lg:p-12">
                    @if ($evento->fecha_inicio)
                        <div class="border-t border-white/40 py-5">
                            <dt class="font-body text-sm text-gray-3">Fecha</dt>
                            <dd class="mt-2 font-source text-2xl leading-tight">
                                {{ $evento->fecha_inicio->locale('es')->translatedFormat('d \d\e F \d\e Y · H:i') }}
                                @if ($evento->fecha_fin)
                                    <span class="mt-1 block text-lg text-gray-3">hasta {{ $evento->fecha_fin->locale('es')->translatedFormat('d \d\e F \d\e Y · H:i') }}</span>
                                @endif
                            </dd>
                        </div>
                    @endif
                    @if ($location)
                        <div class="border-t border-white/40 py-5"><dt class="font-body text-sm text-gray-3">Lugar</dt><dd class="mt-2 font-source text-xl">{{ $location }}</dd></div>
                    @endif
                    @if ($evento->tema ?: $evento->rol)
                        <div class="border-t border-white/40 py-5"><dt class="font-body text-sm text-gray-3">Tema o participación</dt><dd class="mt-2 font-source text-xl">{{ $evento->tema ?: $evento->rol }}</dd></div>
                    @endif
                    @if ($evento->modalidad)
                        <div class="border-y border-white/40 py-5"><dt class="font-body text-sm text-gray-3">Modalidad</dt><dd class="mt-2 font-source text-xl">{{ ucfirst($evento->modalidad) }}</dd></div>
                    @endif
                </dl>
            </div>
        </header>

        <div class="mx-auto max-w-[1440px] px-5 py-16 sm:px-8 lg:px-12 lg:py-24 xl:px-16">
            @if ($imageUrl)
                <img src="{{ $imageUrl }}" alt="" class="aspect-[16/9] w-full object-cover" loading="eager">
            @endif

            <div class="mt-12 grid gap-10 lg:grid-cols-12">
                @if ($evento->descripcion)
                    <div class="article-content max-w-[68ch] font-source text-xl leading-relaxed text-gray lg:col-span-8 [&_a]:text-primary [&_a]:underline">{!! $evento->descripcion !!}</div>
                @endif

                @if (($evento->enlace_inscripcion && ! $isPast) || $evento->video)
                    <div class="border-t border-primary pt-6 lg:col-span-3 lg:col-start-10">
                        <p class="font-source text-lg text-primary">Accesos</p>
                        <div class="mt-5 grid gap-3">
                            @if ($evento->enlace_inscripcion && ! $isPast)
                                <a href="{{ $evento->enlace_inscripcion }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-12 items-center justify-between border border-primary bg-primary px-5 font-body text-base font-semibold text-white transition-colors duration-200 hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">Inscribirse <span aria-hidden="true">↗</span></a>
                            @endif
                            @if ($evento->video)
                                <a href="{{ $evento->video }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-12 items-center justify-between border border-primary px-5 font-body text-base font-semibold text-primary transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">Ver video <span aria-hidden="true">↗</span></a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </article>
</x-layout>
