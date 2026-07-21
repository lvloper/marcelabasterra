<x-layout :notLayout="false">
    <section class="border-b border-primary bg-primary text-white">
        <div class="container mx-auto grid grid-cols-1 gap-12 py-20 lg:grid-cols-12 lg:py-28">
            <div class="lg:col-span-8">
                <a href="{{ route('publications.index') }}" class="inline-flex min-h-11 items-center gap-2 font-body text-base text-gray-3 focus:outline-none focus:ring-2 focus:ring-accent">← Publicaciones</a>
                <h1 class="mt-10 font-sans text-5xl font-bold leading-[0.96] tracking-[-0.035em] sm:text-6xl lg:text-[6rem]">Libros</h1>
            </div>
            <div class="self-end border-l border-white/50 pl-8 lg:col-span-4">
                <p class="font-source text-2xl leading-relaxed text-gray-3">Obras de autoría, coautoría y dirección. Un recorrido por la producción bibliográfica.</p>
                <p class="mt-8 font-source text-4xl">{{ $books->count() }} <span class="font-body text-base">títulos</span></p>
            </div>
        </div>
    </section>

    <section class="bg-[var(--color-surface-ivory)] py-20 lg:py-28">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 gap-x-8 gap-y-16 sm:grid-cols-2 lg:grid-cols-12">
                @foreach ($books as $book)
                    <article class="group sm:col-span-1 lg:col-span-4 {{ $loop->first ? 'lg:col-span-5' : '' }}">
                        <a href="{{ $book->url }}" class="block focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-4 focus:ring-offset-[var(--color-surface-ivory)]">
                            <div class="flex aspect-[4/5] items-center justify-center border border-primary bg-white p-6 overflow-hidden">
                                @if ($book->portada)
                                    <img src="{{ Storage::url($book->portada) }}" alt="Portada de {{ $book->title }}" class="h-full w-full object-contain transition-transform duration-700 ease-out group-hover:scale-[1.025]">
                                @endif
                            </div>
                            <div class="border-b border-primary py-6">
                                <div class="flex items-start justify-between gap-5">
                                    <p class="font-source text-lg text-gray">{{ $book->fecha_publicacion?->year }}@if($book->editorial) · {{ $book->editorial }}@endif</p>
                                    <span class="font-body text-xl text-primary transition-transform group-hover:translate-x-2">→</span>
                                </div>
                                <h2 class="mt-3 font-sans text-2xl font-bold leading-tight text-primary lg:text-3xl">{{ $book->title }}</h2>
                                @if ($book->subtitulo)
                                    <p class="mt-4 line-clamp-2 font-source text-lg leading-relaxed text-gray">{{ $book->subtitulo }}</p>
                                @endif
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</x-layout>
