<x-layout :notLayout="false">
    @php
        $parentRoute = $dossierPrensa->route->parent;
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
                <span class="text-xs font-semibold tracking-widest uppercase text-secondary mb-3 block font-sans">Dossier de Prensa</span>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 font-sans leading-tight">{{ $dossierPrensa->title }}</h1>

                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-8 font-sans">
                    @if ($dossierPrensa->fecha)
                        <span class="flex items-center gap-1.5">
                            <x-lucide-calendar class="w-3.5 h-3.5" />
                            {{ $dossierPrensa->fecha->format('d/m/Y') }}
                        </span>
                    @endif
                </div>

                @if ($dossierPrensa->archivo)
                    <div class="mb-8">
                        <a href="{{ Storage::url($dossierPrensa->archivo) }}" download
                           class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 font-bold rounded-sm hover:bg-primary-hover transition-colors group">
                            <x-lucide-file-text class="w-4 h-4" />
                            Descargar dossier
                            <x-lucide-download class="w-4 h-4 group-hover:translate-y-0.5 transition-transform" />
                        </a>
                    </div>
                @endif

                @if ($dossierPrensa->descripcion)
                    <div class="prose prose-lg max-w-none text-gray-700 font-source leading-relaxed
                                [&_p]:mb-4 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline">
                        {!! $dossierPrensa->descripcion !!}
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layout>
