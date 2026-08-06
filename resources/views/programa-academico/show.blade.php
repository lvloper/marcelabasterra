<x-layout :notLayout="false">
    @php
        $parentRoute = $programaAcademico->route->parent;
    @endphp

    <section class="relative bg-gray-3 overflow-hidden">
        <div class="absolute inset-0 bg-grid pointer-events-none z-0"></div>
        <div class="relative z-10 container mx-auto px-4 py-12 md:py-20">
            @if ($parentRoute)
                <x-breadcrumbs :items="[['label' => $parentRoute->title, 'url' => url($parentRoute->full_slug)]]" :current="$programaAcademico->title" class="mb-6" />
            @endif

            <div class="max-w-3xl mx-auto">
                <span class="text-xs font-semibold tracking-widest uppercase text-secondary mb-3 block font-sans">Programa Academico</span>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 font-sans leading-tight">{{ $programaAcademico->title }}</h1>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                    @if ($programaAcademico->institucion)
                        <div class="bg-white rounded-xl border border-gray-200 p-4">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block font-sans">Institucion</span>
                            <span class="text-sm text-gray-900 font-sans">{{ $programaAcademico->institucion }}</span>
                        </div>
                    @endif
                    @if ($programaAcademico->fecha_inicio)
                        <div class="bg-white rounded-xl border border-gray-200 p-4">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block font-sans">Periodo</span>
                            <span class="text-sm text-gray-900 font-sans">
                                {{ $programaAcademico->fecha_inicio->format('Y') }}
                                @if ($programaAcademico->fecha_fin)
                                    – {{ $programaAcademico->fecha_fin->format('Y') }}
                                @endif
                            </span>
                        </div>
                    @endif
                </div>

                @if ($programaAcademico->descripcion)
                    <div class="prose prose-lg max-w-none text-gray-700 font-source leading-relaxed
                                [&_p]:mb-4 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline">
                        {!! $programaAcademico->descripcion !!}
                    </div>
                @endif

                @if ($programaAcademico->enlace)
                    <div class="mt-8 pt-6 border-t border-gray-2">
                        <a href="{{ $programaAcademico->enlace }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 font-bold rounded-sm hover:bg-primary-hover transition-colors group">
                            <x-lucide-external-link class="w-4 h-4" />
                            Mas informacion
                            <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layout>
