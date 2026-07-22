<x-layout :notLayout="false">
    @php
        $parentRoute = $conferencia->route?->parent;
        $imageUrl = $conferencia->imagen
            ? (filter_var($conferencia->imagen, FILTER_VALIDATE_URL) ? $conferencia->imagen : \Illuminate\Support\Facades\Storage::url($conferencia->imagen))
            : null;
        $location = implode(' · ', array_filter([
            $conferencia->ubicacion,
            implode(', ', array_filter([$conferencia->ciudad, $conferencia->pais])),
        ]));
    @endphp

    <article class="bg-white">
        <header class="border-b border-primary bg-[var(--color-surface-ivory)]">
            <div class="mx-auto grid max-w-[1440px] lg:grid-cols-12">
                <div class="px-5 py-14 sm:px-8 sm:py-20 lg:col-span-8 lg:px-12 lg:py-24 xl:px-16">
                    @if ($parentRoute)
                        <a href="{{ url($parentRoute->full_slug) }}#agenda-y-archivo" class="group inline-flex min-h-11 items-center border-b border-primary font-body text-sm font-semibold text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"><span class="mr-2 transition-transform duration-200 group-hover:-translate-x-1" aria-hidden="true">←</span> Jornadas y Congresos</a>
                    @endif
                    <p class="mt-10 font-source text-sm text-primary"><span class="mr-3 text-accent" aria-hidden="true">—</span>{{ \App\Support\EventCatalog::TYPE_LABELS[$conferencia->tipo] ?? 'Conferencia' }}</p>
                    <h1 class="mt-5 max-w-[16ch] font-sans text-[clamp(3rem,6vw,6rem)] font-normal leading-[0.95] tracking-[-0.035em] text-primary">{{ $conferencia->title }}</h1>
                    @if ($conferencia->institucion)<p class="mt-8 max-w-[44ch] font-source text-[clamp(1.4rem,2.4vw,2rem)] leading-tight text-primary">{{ $conferencia->institucion }}</p>@endif
                </div>

                <dl class="border-t border-primary bg-primary p-6 text-white sm:p-10 lg:col-span-4 lg:border-l lg:border-t-0 lg:p-12">
                    @if ($conferencia->fecha)<div class="border-t border-white/40 py-5"><dt class="font-body text-sm text-gray-3">Fecha</dt><dd class="mt-2 font-source text-2xl">{{ $conferencia->fecha->locale('es')->translatedFormat('d \d\e F \d\e Y') }}</dd></div>@endif
                    @if ($location)<div class="border-t border-white/40 py-5"><dt class="font-body text-sm text-gray-3">Lugar</dt><dd class="mt-2 font-source text-xl">{{ $location }}</dd></div>@endif
                    @if ($conferencia->tematica)<div class="border-y border-white/40 py-5"><dt class="font-body text-sm text-gray-3">Tema</dt><dd class="mt-2 font-source text-xl">{{ $conferencia->tematica }}</dd></div>@endif
                </dl>
            </div>
        </header>

        <div class="mx-auto max-w-[1200px] px-5 py-16 sm:px-8 lg:py-24">
            @if ($imageUrl)<img src="{{ $imageUrl }}" alt="" class="aspect-video w-full object-cover" loading="eager">@endif
            <div class="mt-12 grid gap-10 lg:grid-cols-12">
                @if ($conferencia->descripcion)<div class="article-content max-w-[68ch] font-source text-xl leading-relaxed text-gray lg:col-span-8 [&_a]:text-primary [&_a]:underline">{!! $conferencia->descripcion !!}</div>@endif
                @if ($conferencia->external_url)
                    <div class="border-t border-primary pt-6 lg:col-span-3 lg:col-start-10">
                        <a href="{{ $conferencia->external_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-12 w-full items-center justify-between border border-primary bg-primary px-5 font-body text-base font-semibold text-white transition-colors duration-200 hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">{{ $conferencia->link_label }} <span aria-hidden="true">↗</span></a>
                    </div>
                @endif
            </div>
        </div>
    </article>
</x-layout>
