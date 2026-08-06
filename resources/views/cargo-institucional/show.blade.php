<x-layout :notLayout="false">
    @php
        $parentRoute = $cargoInstitucional->route->parent;
    @endphp

    <section class="relative bg-gray-3 overflow-hidden">
        <div class="absolute inset-0 bg-grid pointer-events-none z-0"></div>
        <div class="relative z-10 container mx-auto px-4 py-12 md:py-20">
            @if ($parentRoute)
                <x-breadcrumbs :items="[['label' => $parentRoute->title, 'url' => url($parentRoute->full_slug)]]" :current="$cargoInstitucional->cargo" class="mb-6" />
            @endif

            <div class="max-w-3xl mx-auto">
                <span class="text-xs font-semibold tracking-widest uppercase text-secondary mb-3 block font-sans">Cargo Institucional</span>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2 font-sans leading-tight">{{ $cargoInstitucional->cargo }}</h1>
                @if ($cargoInstitucional->institucion)
                    <p class="text-lg text-gray-600 mb-6 font-sans">{{ $cargoInstitucional->institucion }}</p>
                @endif

                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-8 font-sans">
                    @if ($cargoInstitucional->fecha_inicio)
                        <span class="flex items-center gap-1.5">
                            <x-lucide-calendar class="w-3.5 h-3.5" />
                            {{ $cargoInstitucional->fecha_inicio->format('Y') }}
                            @if ($cargoInstitucional->fecha_fin)
                                – {{ $cargoInstitucional->fecha_fin->format('Y') }}
                            @else
                                – Presente
                            @endif
                        </span>
                    @endif
                </div>

                @if ($cargoInstitucional->descripcion)
                    <div class="prose prose-lg max-w-none text-gray-700 font-source leading-relaxed
                                [&_p]:mb-4 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline">
                        {!! $cargoInstitucional->descripcion !!}
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layout>
