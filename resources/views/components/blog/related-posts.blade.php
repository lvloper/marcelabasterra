@php
    $related = $blog->related();
@endphp

@if ($related->isNotEmpty())
    <section class="bg-primary py-16 text-white md:py-20" aria-labelledby="related-posts-title">
        <div class="container">
            <div class="mb-10 grid grid-cols-1 gap-5 border-b border-white/30 pb-7 md:grid-cols-12 md:items-end">
                <h2 id="related-posts-title"
                    class="font-sans text-[clamp(2.4rem,5vw,4.5rem)] font-bold leading-none md:col-span-8">
                    Seguir leyendo
                </h2>
                <p class="font-source text-lg text-gray-3 md:col-span-4 md:text-right">
                    Publicaciones relacionadas
                </p>
            </div>

            <div class="grid grid-cols-1 gap-10 md:grid-cols-3 md:gap-6">
                @foreach ($related as $item)
                    <article class="group border-t border-white/40 pt-5">
                        <a wire:navigate href="{{ $item->url }}"
                            class="block focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                            @if ($item->thumb)
                                <div class="mb-6 aspect-[4/3] overflow-hidden bg-secondary">
                                    <x-image :src="$item->thumb" :alt="$item->title" fit="cover"
                                        class="h-full w-full"
                                        imageClass="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.025] motion-reduce:transition-none" />
                                </div>
                            @endif

                            <time datetime="{{ $item->published_at?->toDateString() }}"
                                class="font-body text-sm text-gray-3">
                                {{ $item->published_at?->translatedFormat('d · m · Y') }}
                            </time>
                            <h3 class="mt-3 font-source text-2xl leading-tight text-white transition-colors group-hover:text-accent">
                                {{ $item->title }}
                            </h3>
                            <span class="mt-6 inline-block font-body text-sm text-white">Leer publicación →</span>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
