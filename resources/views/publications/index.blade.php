<x-layout :notLayout="false">
    <section class="border-b border-primary bg-[var(--color-surface-ivory)]">
        <div class="container mx-auto grid min-h-[72vh] grid-cols-1 items-end gap-12 py-20 lg:grid-cols-12 lg:py-28">
            <div class="lg:col-span-8">
                <p class="mb-8 font-source text-base text-primary">Archivo editorial · Libros y pensamiento jurídico</p>
                <h1 class="max-w-5xl font-sans text-5xl font-bold leading-[0.96] tracking-[-0.035em] text-primary sm:text-6xl lg:text-[6.5rem]">
                    Publicaciones
                </h1>
                <p class="mt-10 max-w-3xl font-source text-2xl leading-relaxed text-gray lg:text-3xl">
                    Una biblioteca de libros y artículos que reúne décadas de investigación constitucional, producción académica y debate público.
                </p>
            </div>

            <dl class="grid grid-cols-3 border-y border-primary lg:col-span-4 lg:grid-cols-1 lg:border-y-0 lg:border-l">
                <div class="px-4 py-6 lg:px-8">
                    <dt class="font-body text-sm text-gray">Libros</dt>
                    <dd class="mt-2 font-source text-4xl text-primary">{{ $booksCount }}</dd>
                </div>
                <div class="border-x border-primary px-4 py-6 lg:border-x-0 lg:border-y lg:px-8">
                    <dt class="font-body text-sm text-gray">Artículos</dt>
                    <dd class="mt-2 font-source text-4xl text-primary">{{ $articlesCount }}</dd>
                </div>
                <div class="px-4 py-6 lg:px-8">
                    <dt class="font-body text-sm text-gray">Áreas temáticas</dt>
                    <dd class="mt-2 font-source text-4xl text-primary">{{ $topicsCount }}</dd>
                </div>
            </dl>
        </div>
    </section>

    <section class="bg-white py-20 lg:py-28">
        <div class="container mx-auto">
            <div class="mb-14 grid grid-cols-1 gap-8 border-b border-gray-2 pb-10 lg:grid-cols-12">
                <h2 class="font-sans text-4xl font-bold leading-none text-primary lg:col-span-7 lg:text-6xl">Dos formas de recorrer la obra</h2>
                <p class="max-w-2xl font-source text-xl leading-relaxed text-gray lg:col-span-5">Explorá el catálogo bibliográfico o ingresá al archivo académico por año y tema.</p>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <a href="{{ route('publications.books') }}" class="group border border-primary bg-primary p-8 text-white transition-colors duration-300 hover:bg-white hover:text-primary focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-4 lg:col-span-7 lg:p-12">
                    <span class="font-source text-lg">01 · Catálogo editorial</span>
                    <h3 class="mt-16 max-w-2xl font-sans text-4xl font-bold leading-tight lg:text-6xl">Libros</h3>
                    <p class="mt-6 max-w-xl font-source text-xl leading-relaxed text-gray-3 group-hover:text-gray">Portadas, datos editoriales, ISBN y enlaces de consulta.</p>
                    <span class="mt-12 inline-flex min-h-12 items-center gap-3 border-t border-current pt-4 font-body text-base">Explorar libros <span class="transition-transform group-hover:translate-x-2">→</span></span>
                </a>

                <a href="{{ route('publications.articles') }}" class="group border border-primary bg-[var(--color-surface-ivory)] p-8 text-primary transition-colors duration-300 hover:bg-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-4 lg:col-span-5 lg:p-12">
                    <span class="font-source text-lg">02 · Archivo de investigación</span>
                    <h3 class="mt-16 font-sans text-4xl font-bold leading-tight lg:text-5xl">Artículos académicos</h3>
                    <p class="mt-6 font-source text-xl leading-relaxed text-gray group-hover:text-gray-3">Documentos completos en PDF, organizados por fecha y temática.</p>
                    <span class="mt-12 inline-flex min-h-12 items-center gap-3 border-t border-current pt-4 font-body text-base">Consultar archivo <span class="transition-transform group-hover:translate-x-2">→</span></span>
                </a>
            </div>
        </div>
    </section>

    @if ($latestBook)
        <section class="border-y border-primary bg-gray-3 py-20 lg:py-28">
            <div class="container mx-auto grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-center">
                <div class="lg:col-span-4 lg:col-start-2">
                    @if ($latestBook->portada)
                        <div class="border border-primary bg-white p-5">
                            <img src="{{ Storage::url($latestBook->portada) }}" alt="Portada de {{ $latestBook->title }}" class="mx-auto max-h-[34rem] w-full object-contain">
                        </div>
                    @endif
                </div>
                <div class="lg:col-span-6 lg:col-start-7">
                    <p class="font-source text-lg text-primary">Libro destacado · {{ $latestBook->fecha_publicacion?->year }}</p>
                    <h2 class="mt-5 font-sans text-4xl font-bold leading-tight text-primary lg:text-6xl">{{ $latestBook->title }}</h2>
                    @if ($latestBook->subtitulo)
                        <p class="mt-6 font-source text-2xl leading-relaxed text-gray">{{ $latestBook->subtitulo }}</p>
                    @endif
                    <a href="{{ $latestBook->url }}" class="mt-10 inline-flex min-h-12 items-center gap-3 border border-primary bg-primary px-6 font-body text-base text-white transition-colors hover:bg-white hover:text-primary focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-4">Ver ficha <span>→</span></a>
                </div>
            </div>
        </section>
    @endif
</x-layout>
