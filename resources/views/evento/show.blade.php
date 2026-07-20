<x-layout :notLayout="false">
    @php
        $parentRoute = $evento->route->parent;
        $isPast = $evento->fecha_fin && $evento->fecha_fin->isPast();
    @endphp

    <section class="relative bg-gray-3 overflow-hidden">
        <div class="absolute inset-0 bg-grid pointer-events-none z-0"></div>
        <div class="relative z-10 container mx-auto px-4 py-12 md:py-20">
            @if ($parentRoute)
                <a href="{{ url($parentRoute->full_slug) }}" class="inline-flex items-center gap-1.5 text-sm text-secondary hover:text-primary transition-colors mb-6 font-sans group">
                    <x-lucide-arrow-left class="w-3.5 h-3.5 group-hover:-translate-x-1 transition-transform" />
                    {{ $parentRoute->title }}
                </a>
            @endif

            <div class="max-w-3xl mx-auto">
                <div class="flex items-start gap-4 mb-6">
                    <div class="shrink-0 w-20 h-20 rounded-xl bg-primary/5 flex flex-col items-center justify-center text-center border border-primary/10">
                        @if ($evento->fecha_inicio)
                            <span class="text-xs font-bold text-primary uppercase leading-none font-sans">{{ $evento->fecha_inicio->shortLocaleMonth }}</span>
                            <span class="text-2xl font-bold text-primary leading-none font-sans">{{ $evento->fecha_inicio->day }}</span>
                        @else
                            <x-lucide-calendar class="w-8 h-8 text-primary" />
                        @endif
                    </div>
                    <div>
                        <span class="text-xs font-semibold tracking-widest uppercase text-secondary mb-1 block font-sans">
                            Evento {{ $isPast ? 'pasado' : '' }}
                        </span>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 font-sans leading-tight">{{ $evento->title }}</h1>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-8 font-sans">
                    @if ($evento->fecha_inicio)
                        <span class="flex items-center gap-1.5">
                            <x-lucide-clock class="w-3.5 h-3.5" />
                            {{ $evento->fecha_inicio->format('d/m/Y H:i') }}
                            @if ($evento->fecha_fin && $evento->fecha_fin->format('d/m/Y') != $evento->fecha_inicio->format('d/m/Y'))
                                → {{ $evento->fecha_fin->format('d/m/Y H:i') }}
                            @elseif ($evento->fecha_fin)
                                → {{ $evento->fecha_fin->format('H:i') }}
                            @endif
                        </span>
                    @endif
                    @if ($evento->ubicacion)
                        <span class="flex items-center gap-1.5">
                            <x-lucide-map-pin class="w-3.5 h-3.5" />
                            {{ $evento->ubicacion }}
                        </span>
                    @endif
                    @if ($evento->tipo)
                        <span class="bg-primary/5 text-primary px-3 py-1 rounded-sm text-xs font-bold">{{ $evento->tipo }}</span>
                    @endif
                </div>

                @if ($evento->route->image)
                    <div class="aspect-video rounded-2xl overflow-hidden bg-white border border-gray-200 mb-8">
                        <img src="{{ Storage::url($evento->route->image) }}" alt="{{ $evento->title }}"
                             class="w-full h-full object-cover">
                    </div>
                @endif

                @if ($evento->descripcion)
                    <div class="prose prose-lg max-w-none text-gray-700 font-source leading-relaxed
                                [&_p]:mb-4 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline">
                        {!! $evento->descripcion !!}
                    </div>
                @endif

                @if ($evento->enlace_inscripcion && !$isPast)
                    <div class="mt-8 pt-6 border-t border-gray-2">
                        <a href="{{ $evento->enlace_inscripcion }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 bg-accent text-white px-8 py-4 font-bold rounded-sm hover:bg-accent-hover transition-colors group">
                            <x-lucide-ticket class="w-4 h-4" />
                            Inscribirse
                            <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layout>
