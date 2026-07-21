<x-layout :notLayout="false">
    @php
        $pdfUrl = $articuloAcademico->archivo_pdf_url
            ?: ($articuloAcademico->archivo_pdf ? Storage::url($articuloAcademico->archivo_pdf) : null);
    @endphp

    <article>
        <header class="border-b border-primary bg-primary text-white">
            <div class="container mx-auto grid grid-cols-1 gap-12 py-16 lg:grid-cols-12 lg:py-24">
                <div class="lg:col-span-9">
                    <a href="{{ route('publications.articles') }}" class="inline-flex min-h-11 items-center gap-2 font-body text-base text-gray-3 focus:outline-none focus:ring-2 focus:ring-accent">← Volver al archivo académico</a>
                    <p class="mt-10 font-source text-lg text-gray-3">{{ $articuloAcademico->tematica }}</p>
                    <h1 class="mt-5 max-w-6xl font-sans text-4xl font-bold leading-[1.02] tracking-[-0.025em] sm:text-5xl lg:text-7xl">{{ $articuloAcademico->title }}</h1>
                </div>
                <div class="flex items-end lg:col-span-3">
                    <div class="w-full border-y border-white/60 py-6 lg:border-l lg:border-y-0 lg:pl-8">
                        <p class="font-body text-sm text-gray-3">Publicado</p>
                        <p class="mt-2 font-source text-5xl">{{ $articuloAcademico->fecha_publicacion?->year }}</p>
                    </div>
                </div>
            </div>
        </header>

        <section class="bg-[var(--color-surface-ivory)] py-16 lg:py-24">
            <div class="container mx-auto grid grid-cols-1 gap-12 lg:grid-cols-12">
                <aside class="lg:col-span-3">
                    <p class="border-t border-primary pt-4 font-source text-lg text-primary">Referencia bibliográfica</p>
                </aside>
                <div class="lg:col-span-7">
                    @if ($articuloAcademico->resumen)
                        <div class="max-w-[68ch] font-source text-2xl leading-relaxed text-gray">{!! $articuloAcademico->resumen !!}</div>
                    @endif

                    @if ($articuloAcademico->contenido)
                        <div class="mt-10 max-w-[68ch] border-t border-primary pt-10 font-source text-xl leading-[1.7] text-gray [&_p]:mb-6 [&_a]:text-primary [&_a]:underline">{!! $articuloAcademico->contenido !!}</div>
                    @endif

                    @if ($pdfUrl)
                        <div class="mt-12 border-t border-primary pt-8">
                            <a href="{{ $pdfUrl }}" target="_blank" rel="noopener" class="inline-flex min-h-12 items-center gap-3 border border-primary bg-primary px-6 font-body text-base text-white transition-colors hover:bg-white hover:text-primary focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-4">
                                Ver documento completo <span>↗</span>
                            </a>
                            <p class="mt-4 font-body text-sm text-gray">El PDF se abre en una pestaña nueva.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </article>
</x-layout>
