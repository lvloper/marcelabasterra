<x-layout :notLayout="false">
    <article>
        <header class="border-b border-primary bg-[var(--color-surface-ivory)]">
            <div class="container mx-auto grid grid-cols-1 gap-12 py-16 lg:grid-cols-12 lg:items-center lg:py-24">
                <div class="order-2 lg:order-1 lg:col-span-7">
                    <a href="{{ route('publications.books') }}" class="inline-flex min-h-11 items-center gap-2 font-body text-base text-gray focus:outline-none focus:ring-2 focus:ring-accent">← Volver a Libros</a>
                    <p class="mt-10 font-source text-lg text-primary">Libro · {{ $libro->fecha_publicacion?->year }}</p>
                    <h1 class="mt-5 font-sans text-4xl font-bold leading-[1.02] tracking-[-0.025em] text-primary sm:text-5xl lg:text-7xl">{{ $libro->title }}</h1>

                    @if ($libro->subtitulo)
                        <p class="mt-8 max-w-3xl font-source text-2xl leading-relaxed text-gray">{{ $libro->subtitulo }}</p>
                    @endif

                    <dl class="mt-10 grid grid-cols-1 border-y border-primary sm:grid-cols-3">
                        @if ($libro->editorial)
                            <div class="py-5 sm:pr-6">
                                <dt class="font-body text-sm text-gray">Editorial</dt>
                                <dd class="mt-2 font-source text-lg text-primary">{{ $libro->editorial }}</dd>
                            </div>
                        @endif
                        @if ($libro->fecha_publicacion)
                            <div class="border-y border-primary py-5 sm:border-x sm:border-y-0 sm:px-6">
                                <dt class="font-body text-sm text-gray">Año</dt>
                                <dd class="mt-2 font-source text-lg text-primary">{{ $libro->fecha_publicacion->year }}</dd>
                            </div>
                        @endif
                        @if ($libro->isbn)
                            <div class="py-5 sm:pl-6">
                                <dt class="font-body text-sm text-gray">ISBN</dt>
                                <dd class="mt-2 font-source text-lg text-primary">{{ $libro->isbn }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <div class="order-1 lg:order-2 lg:col-span-4 lg:col-start-9">
                    @if ($libro->portada)
                        <div class="border border-primary bg-white p-5">
                            <img src="{{ Storage::url($libro->portada) }}" alt="Portada de {{ $libro->title }}" class="mx-auto max-h-[38rem] w-full object-contain">
                        </div>
                    @endif
                </div>
            </div>
        </header>

        <section class="bg-white py-16 lg:py-24">
            <div class="container mx-auto grid grid-cols-1 gap-12 lg:grid-cols-12">
                <div class="lg:col-span-3">
                    <p class="border-t border-primary pt-4 font-source text-lg text-primary">Sobre esta obra</p>
                </div>
                <div class="lg:col-span-7">
                    @if ($libro->descripcion)
                        <div class="max-w-[68ch] font-source text-xl leading-[1.7] text-gray [&_p]:mb-6">{!! $libro->descripcion !!}</div>
                    @endif

                    @if ($libro->enlaces && $libro->enlaces->isNotEmpty())
                        <div class="mt-12 border-t border-primary pt-8">
                            @foreach ($libro->enlaces as $enlace)
                                <a href="{{ $enlace['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-12 items-center gap-3 border border-primary bg-primary px-6 font-body text-base text-white transition-colors hover:bg-white hover:text-primary focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-4">
                                    {{ $enlace['label'] ?? 'Más información' }} <span>↗</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </article>
</x-layout>
