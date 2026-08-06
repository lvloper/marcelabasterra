<x-layout :notLayout="false">
@include('publications.partials.books-intro', ['totalLibros' => $books->count()])

@php
    $categories = ['autoria' => 'Libros', 'coautoria' => 'Libros en coautoría', 'direccion' => 'Dirección de libros'];
    $grouped = $books->groupBy(fn ($book) => $book->categoria ?? 'autoria');
@endphp

<section class="bg-gray-3 py-20 lg:py-28">
    <div class="container mx-auto space-y-20 lg:space-y-28">
        @foreach ($categories as $key => $label)
            @if ($grouped->has($key) && $grouped[$key]->isNotEmpty())
                <div>
                    <div class="mb-10 border-t border-primary pt-6">
                        <h2 class="font-sans text-3xl font-normal leading-tight text-primary lg:text-4xl">{{ $label }}</h2>
                    </div>
                    <div class="grid grid-cols-1 gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-12 lg:gap-y-12">
                        @foreach ($grouped[$key] as $book)
                            <article class="group flex flex-col sm:col-span-1 lg:col-span-6">
                                <a href="{{ $book->url }}" class="flex h-full flex-col gap-6 sm:flex-row sm:items-center focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-4 focus:ring-offset-gray-3">
                                    <div class="aspect-[4/5] w-full shrink-0 overflow-hidden sm:w-36 lg:w-44">
                                        @if ($book->portada)
                                            <img src="{{ Storage::url($book->portada) }}" alt="Portada de {{ $book->title }}" class="h-full w-full object-contain transition-transform duration-700 ease-out group-hover:scale-[1.03] motion-reduce:transition-none">
                                        @else
                                            <div class="flex h-full w-full flex-col items-start justify-between bg-primary p-5 text-white">
                                                <span class="font-source text-[10px] tracking-[0.18em] text-gray-3">Obra reciente</span>
                                                <span class="font-source text-3xl leading-none" aria-hidden="true">MB</span>
                                                <span class="block h-px w-6 bg-accent" aria-hidden="true"></span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1 border-b border-primary pb-6">
                                        <p class="mb-2 flex min-h-5 items-center gap-2 font-source text-sm {{ $book->destacado ? 'text-accent' : 'text-transparent' }}" @if(!$book->destacado) aria-hidden="true" @endif>
                                            <span class="inline-block h-px w-5 {{ $book->destacado ? 'bg-accent' : 'bg-transparent' }}" aria-hidden="true"></span>
                                            Destacado
                                        </p>
                                        <div class="flex items-baseline justify-between gap-4">
                                            <p class="min-w-0 font-source text-base text-gray">{{ $book->fecha_publicacion?->year }}@if($book->editorial)<span> · {{ $book->editorial }}</span>@endif</p>
                                            <span class="shrink-0 font-body text-lg text-primary transition-transform duration-300 group-hover:translate-x-2" aria-hidden="true">→</span>
                                        </div>
                                        <h2 class="mt-2.5 font-sans text-lg font-bold leading-snug text-balance text-primary lg:text-xl">{{ $book->title }}</h2>
                                        @if ($book->subtitulo)
                                            <p class="mt-2.5 line-clamp-2 font-source text-base leading-relaxed text-gray">{{ $book->subtitulo }}</p>
                                        @endif
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</section>
</x-layout>
