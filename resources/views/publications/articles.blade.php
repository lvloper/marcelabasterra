<x-layout :notLayout="false">
    <section class="border-b border-primary bg-white">
        <div class="container mx-auto grid grid-cols-1 gap-12 py-20 lg:grid-cols-12 lg:py-28">
            <div class="lg:col-span-8">
                <a href="{{ route('publications.index') }}" class="inline-flex min-h-11 items-center gap-2 font-body text-base text-gray focus:outline-none focus:ring-2 focus:ring-accent">← Publicaciones</a>
                <h1 class="mt-10 max-w-5xl font-sans text-5xl font-bold leading-[0.96] tracking-[-0.035em] text-primary sm:text-6xl lg:text-[5.5rem]">Artículos académicos</h1>
            </div>
            <div class="self-end border-l border-primary pl-8 lg:col-span-4">
                <p class="font-source text-2xl leading-relaxed text-gray">Investigación jurídica disponible para consulta y descarga.</p>
                <p class="mt-8 font-source text-4xl text-primary">{{ $articles->total() }} <span class="font-body text-base text-gray">resultados</span></p>
            </div>
        </div>
    </section>

    <section class="sticky top-[var(--header-height)] z-20 border-b border-primary bg-[var(--color-surface-ivory)]">
        <form action="{{ route('publications.articles') }}" method="GET" class="container mx-auto grid grid-cols-1 gap-px bg-primary lg:grid-cols-12" aria-label="Filtros de artículos académicos">
            <div class="bg-[var(--color-surface-ivory)] p-4 lg:col-span-5">
                <label for="publication-search" class="mb-2 block font-body text-sm text-primary">Buscar por título o referencia</label>
                <input id="publication-search" name="q" value="{{ $search }}" type="search" class="min-h-12 w-full border border-primary bg-white px-4 font-body text-base text-primary outline-none focus:ring-2 focus:ring-accent" placeholder="Ej.: acceso a la información">
            </div>
            <div class="bg-[var(--color-surface-ivory)] p-4 lg:col-span-2">
                <label for="publication-year" class="mb-2 block font-body text-sm text-primary">Año</label>
                <select id="publication-year" name="year" class="min-h-12 w-full border border-primary bg-white px-4 font-body text-base text-primary outline-none focus:ring-2 focus:ring-accent">
                    <option value="">Todos</option>
                    @foreach ($years as $availableYear)
                        <option value="{{ $availableYear }}" @selected($year === $availableYear)>{{ $availableYear }}</option>
                    @endforeach
                </select>
            </div>
            <div class="bg-[var(--color-surface-ivory)] p-4 lg:col-span-3">
                <label for="publication-topic" class="mb-2 block font-body text-sm text-primary">Temática</label>
                <select id="publication-topic" name="topic" class="min-h-12 w-full border border-primary bg-white px-4 font-body text-base text-primary outline-none focus:ring-2 focus:ring-accent">
                    <option value="">Todas</option>
                    @foreach ($topics as $availableTopic)
                        <option value="{{ $availableTopic }}" @selected($topic === $availableTopic)>{{ $availableTopic }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex bg-primary lg:col-span-2">
                <button type="submit" class="min-h-12 flex-1 px-5 font-body text-base text-white transition-colors hover:bg-white hover:text-primary focus:outline-none focus:ring-2 focus:ring-inset focus:ring-accent">Aplicar filtros →</button>
            </div>
        </form>
    </section>

    <section class="bg-gray-3 py-16 lg:py-24">
        <div class="container mx-auto">
            @if ($articles->isEmpty())
                <div class="border-y border-primary py-20 text-center">
                    <h2 class="font-sans text-3xl font-bold text-primary">No encontramos artículos con esos filtros.</h2>
                    <a href="{{ route('publications.articles') }}" class="mt-8 inline-flex min-h-12 items-center border border-primary px-6 font-body text-base text-primary hover:bg-primary hover:text-white">Limpiar filtros</a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($articles as $article)
                        <article class="group grid grid-cols-1 border border-primary bg-white transition-colors duration-300 hover:bg-[var(--color-surface-ivory)] lg:grid-cols-12">
                            <div class="border-b border-primary p-6 lg:col-span-2 lg:border-b-0 lg:border-r lg:p-8">
                                <p class="font-source text-4xl text-primary">{{ $article->fecha_publicacion?->year }}</p>
                                <p class="mt-3 font-body text-sm leading-relaxed text-gray">{{ $article->tematica }}</p>
                            </div>
                            <div class="p-6 lg:col-span-8 lg:p-8">
                                <h2 class="max-w-4xl font-sans text-2xl font-bold leading-tight text-primary lg:text-3xl">
                                    <a href="{{ $article->document_url }}" target="_blank" rel="noopener" class="focus:outline-none focus:ring-2 focus:ring-accent">{{ $article->title }}</a>
                                </h2>
                                @if ($article->resumen)
                                    <p class="mt-4 max-w-3xl font-source text-lg leading-relaxed text-gray">{{ strip_tags($article->resumen) }}</p>
                                @endif
                            </div>
                            <div class="flex items-end justify-between border-t border-primary p-6 lg:col-span-2 lg:border-l lg:border-t-0 lg:p-8">
                                <a href="{{ $article->document_url }}" target="_blank" rel="noopener" class="font-body text-sm text-gray focus:outline-none focus:ring-2 focus:ring-accent">Abrir PDF</a>
                                <span class="font-body text-2xl text-primary transition-transform group-hover:translate-x-2">↗</span>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($articles->hasPages())
                    <nav class="mt-12 flex items-center justify-between border-t border-primary pt-8" aria-label="Paginación de artículos">
                        @if ($articles->onFirstPage())
                            <span class="font-body text-base text-gray-2">← Anteriores</span>
                        @else
                            <a href="{{ $articles->previousPageUrl() }}" class="inline-flex min-h-11 items-center font-body text-base text-primary focus:outline-none focus:ring-2 focus:ring-accent">← Anteriores</a>
                        @endif
                        <span class="font-source text-lg text-gray">Página {{ $articles->currentPage() }} de {{ $articles->lastPage() }}</span>
                        @if ($articles->hasMorePages())
                            <a href="{{ $articles->nextPageUrl() }}" class="inline-flex min-h-11 items-center font-body text-base text-primary focus:outline-none focus:ring-2 focus:ring-accent">Siguientes →</a>
                        @else
                            <span class="font-body text-base text-gray-2">Siguientes →</span>
                        @endif
                    </nav>
                @endif
            @endif
        </div>
    </section>
</x-layout>
