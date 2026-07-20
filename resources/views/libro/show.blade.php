<x-layout :notLayout="false">
    @php
        $parentRoute = $libro->route->parent;
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

            <div class="flex flex-col md:flex-row gap-8 md:gap-12">
                <div class="w-full md:w-2/5 shrink-0">
                    @if ($libro->portada)
                        <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-white border border-gray-200">
                            <img src="{{ Storage::url($libro->portada) }}" alt="{{ $libro->title }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="aspect-[3/4] rounded-2xl bg-white border border-gray-200 flex items-center justify-center">
                            <x-lucide-book-open class="w-16 h-16 text-gray-300" />
                        </div>
                    @endif
                </div>

                <div class="w-full md:w-3/5">
                    <span class="text-xs font-semibold tracking-widest uppercase text-secondary mb-3 block font-sans">Libro</span>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 font-sans leading-tight">{{ $libro->title }}</h1>

                    @if ($libro->subtitulo)
                        <p class="text-lg text-gray-600 mb-6 font-sans">{{ $libro->subtitulo }}</p>
                    @endif

                    <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-8 font-sans">
                        @if ($libro->fecha_publicacion)
                            <span class="flex items-center gap-1.5">
                                <x-lucide-calendar class="w-3.5 h-3.5" />
                                {{ $libro->fecha_publicacion->format('Y') }}
                            </span>
                        @endif
                        @if ($libro->editorial)
                            <span class="flex items-center gap-1.5">
                                <x-lucide-building class="w-3.5 h-3.5" />
                                {{ $libro->editorial }}
                            </span>
                        @endif
                        @if ($libro->isbn)
                            <span class="flex items-center gap-1.5">
                                <x-lucide-barcode class="w-3.5 h-3.5" />
                                ISBN: {{ $libro->isbn }}
                            </span>
                        @endif
                    </div>

                    @if ($libro->descripcion)
                        <div class="prose prose-lg max-w-none text-gray-700 font-source leading-relaxed
                                    [&_p]:mb-4 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline">
                            {!! $libro->descripcion !!}
                        </div>
                    @endif

                    @if ($libro->enlaces && $libro->enlaces->isNotEmpty())
                        <div class="mt-8 pt-6 border-t border-gray-2">
                            <h3 class="text-sm font-bold text-gray-700 mb-3 font-sans uppercase tracking-wider">Enlaces</h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($libro->enlaces as $enlace)
                                    <a href="{{ $enlace['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1.5 text-sm text-primary hover:underline font-sans">
                                        <x-lucide-external-link class="w-3.5 h-3.5" />
                                        {{ $enlace['label'] ?? 'Ver enlace' }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-layout>
