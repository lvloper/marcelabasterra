<x-layout :notLayout="false">
    @php
        $parentRoute = $entrevista->route->parent;
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
                <span class="text-xs font-semibold tracking-widest uppercase text-secondary mb-3 block font-sans">Entrevista</span>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 font-sans leading-tight">{{ $entrevista->title }}</h1>

                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-8 font-sans">
                    @if ($entrevista->fecha)
                        <span class="flex items-center gap-1.5">
                            <x-lucide-calendar class="w-3.5 h-3.5" />
                            {{ $entrevista->fecha->format('d/m/Y') }}
                        </span>
                    @endif
                    @if ($entrevista->medio)
                        <span class="flex items-center gap-1.5">
                            <x-lucide-newspaper class="w-3.5 h-3.5" />
                            {{ $entrevista->medio }}
                        </span>
                    @endif
                </div>

                @if ($entrevista->route->image)
                    <div class="aspect-video rounded-2xl overflow-hidden bg-white border border-gray-200 mb-8">
                        <img src="{{ Storage::url($entrevista->route->image) }}" alt="{{ $entrevista->title }}"
                             class="w-full h-full object-cover">
                    </div>
                @endif

                @if ($entrevista->descripcion)
                    <div class="prose prose-lg max-w-none text-gray-700 font-source leading-relaxed
                                [&_p]:mb-4 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline">
                        {!! $entrevista->descripcion !!}
                    </div>
                @endif

                <div class="flex flex-wrap gap-3 mt-8 pt-6 border-t border-gray-2">
                    @if ($entrevista->enlace)
                        <a href="{{ $entrevista->enlace }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 font-bold rounded-sm hover:bg-primary-hover transition-colors group">
                            <x-lucide-external-link class="w-4 h-4" />
                            Ver entrevista original
                            <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                        </a>
                    @endif
                    @if ($entrevista->video)
                        <a href="{{ $entrevista->video }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 border border-primary text-primary px-6 py-3 font-bold rounded-sm hover:bg-primary hover:text-white transition-colors group">
                            <x-lucide-play class="w-4 h-4" />
                            Ver video
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-layout>
