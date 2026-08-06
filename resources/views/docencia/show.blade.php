<x-layout :notLayout="false">
    @php
        $parentRoute = $docencia->route->parent;
    @endphp

    <section class="relative bg-gray-3 overflow-hidden">
        <div class="absolute inset-0 bg-grid pointer-events-none z-0"></div>
        <div class="relative z-10 container mx-auto px-4 py-12 md:py-20">
            @if ($parentRoute)
                <x-breadcrumbs :items="[['label' => $parentRoute->title, 'url' => url($parentRoute->full_slug)]]" :current="$docencia->title" class="mb-6" />
            @endif

            <div class="max-w-3xl mx-auto">
                <span class="text-xs font-semibold tracking-widest uppercase text-secondary mb-3 block font-sans">Docencia</span>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2 font-sans leading-tight">{{ $docencia->title }}</h1>
                @if ($docencia->materia)
                    <p class="text-lg text-gray-600 mb-6 font-sans">{{ $docencia->materia }}</p>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                    @if ($docencia->institucion)
                        <div class="bg-white rounded-xl border border-gray-200 p-4">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block font-sans">Institucion</span>
                            <span class="text-sm text-gray-900 font-sans">{{ $docencia->institucion }}</span>
                        </div>
                    @endif
                    @if ($docencia->catedra)
                        <div class="bg-white rounded-xl border border-gray-200 p-4">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block font-sans">Catedra</span>
                            <span class="text-sm text-gray-900 font-sans">{{ $docencia->catedra }}</span>
                        </div>
                    @endif
                    @if ($docencia->nivel)
                        <div class="bg-white rounded-xl border border-gray-200 p-4">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block font-sans">Nivel</span>
                            <span class="text-sm text-gray-900 font-sans">{{ $docencia->nivel }}</span>
                        </div>
                    @endif
                </div>

                @if ($docencia->descripcion)
                    <div class="prose prose-lg max-w-none text-gray-700 font-source leading-relaxed
                                [&_p]:mb-4 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline">
                        {!! $docencia->descripcion !!}
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layout>
