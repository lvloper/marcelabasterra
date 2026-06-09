<x-layout :notLayout="false">
    @php
        $parentRoute = $articuloAcademico->route->parent;
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
                <span class="text-xs font-semibold tracking-widest uppercase text-secondary mb-3 block font-sans">Articulo Academico</span>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 font-sans leading-tight">{{ $articuloAcademico->title }}</h1>

                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-8 font-sans">
                    @if ($articuloAcademico->fecha_publicacion)
                        <span class="flex items-center gap-1.5">
                            <x-lucide-calendar class="w-3.5 h-3.5" />
                            {{ $articuloAcademico->fecha_publicacion->format('d/m/Y') }}
                        </span>
                    @endif
                    @if ($articuloAcademico->tematica)
                        <span class="bg-primary/5 text-primary px-3 py-1 rounded-sm text-xs font-bold">{{ $articuloAcademico->tematica }}</span>
                    @endif
                </div>

                @if ($articuloAcademico->resumen)
                    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
                        <h3 class="text-sm font-bold text-gray-700 mb-2 font-sans uppercase tracking-wider">Resumen</h3>
                        <div class="prose max-w-none text-gray-700 font-source leading-relaxed">
                            {!! $articuloAcademico->resumen !!}
                        </div>
                    </div>
                @endif

                @if ($articuloAcademico->contenido)
                    <div class="prose prose-lg max-w-none text-gray-700 font-source leading-relaxed
                                [&_p]:mb-4 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline
                                [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:mt-8 [&_h2]:mb-4
                                [&_h3]:text-xl [&_h3]:font-bold [&_h3]:mt-6 [&_h3]:mb-3">
                        {!! $articuloAcademico->contenido !!}
                    </div>
                @endif

                @if ($articuloAcademico->archivo_pdf)
                    <div class="mt-8 pt-6 border-t border-gray-2">
                        <a href="{{ Storage::url($articuloAcademico->archivo_pdf) }}" download
                           class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 font-bold rounded-sm hover:bg-primary-hover transition-colors group">
                            <x-lucide-file-text class="w-4 h-4" />
                            Descargar PDF
                            <x-lucide-download class="w-4 h-4 group-hover:translate-y-0.5 transition-transform" />
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layout>
