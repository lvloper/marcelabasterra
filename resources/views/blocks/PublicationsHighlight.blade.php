@php
    use App\Models\ArticuloAcademico;
    use App\Models\Libro;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $limit = min(max((int) ($max_items ?? 6), 1), 12);
    $layout = $layout ?? 'featured';
    $items = collect();

    if (($source_mode ?? 'manual') === 'latest') {
        $items = Libro::query()->with('route')->isPublished()
            ->orderByDesc('fecha_publicacion')->limit($limit)->get();
    } else {
        $bookIds = collect($libros ?? [])->map(fn ($id) => (int) $id);
        $articleIds = collect($articulos ?? [])->map(fn ($id) => (int) $id);
        $items = Libro::with('route')->whereIn('id', $bookIds)->get()
            ->sortBy(fn (Libro $book) => $bookIds->search($book->id))->values()
            ->concat(ArticuloAcademico::with('route')->whereIn('id', $articleIds)->get()
                ->sortBy(fn (ArticuloAcademico $article) => $articleIds->search($article->id)))
            ->take($limit);
    }

    $cta = is_array($cta_route ?? null) ? $cta_route : [];
    $booksCount = Libro::query()->isPublished()->count();
    $articlesCount = ArticuloAcademico::query()->has('route')->count();
    $topicsCount = ArticuloAcademico::query()->has('route')->whereNotNull('tematica')->distinct()->count('tematica');
@endphp

@if ($items->isNotEmpty())
@if ($layout === 'library_grid')
<x-block class="border-y border-gray-2 bg-[var(--color-surface-ivory)] py-16 sm:py-20 lg:py-24">
    <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
        <header class="grid gap-6 border-t border-primary pt-6 lg:grid-cols-12 lg:items-end lg:gap-12">
            <div class="lg:col-span-7">
                <p class="mb-4 font-[var(--font-editorial)] text-sm text-primary"><span class="mr-3 text-accent" aria-hidden="true">—</span>Biblioteca digital</p>
                @if ($title ?? null)<h2 class="max-w-[14ch] font-[var(--font-display)] text-[clamp(2.5rem,5vw,4.5rem)] font-normal leading-[0.98] tracking-[-0.03em] text-primary">{{ $title }}</h2>@endif
            </div>
            <div class="lg:col-span-4 lg:col-start-9">
                @if ($description ?? null)<p class="max-w-[46ch] font-[var(--font-editorial)] text-xl leading-relaxed text-gray">{{ $description }}</p>@endif
                @if ($cta_label ?? null)
                    <x-link :attrs="array_merge($cta, ['hideIfNull' => true])" class="group mt-6 inline-flex min-h-12 items-center border border-primary bg-primary px-6 font-[var(--font-body)] text-[1rem] font-semibold text-white hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                        {{ $cta_label }} <span class="ml-3 transition-transform group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span>
                    </x-link>
                @endif
            </div>
        </header>

        <ol class="mt-10 grid gap-7 sm:grid-cols-2 lg:grid-cols-4" aria-label="Libros destacados">
            @foreach ($items->filter(fn ($item) => $item instanceof Libro) as $book)
                <li>
                    <article class="group flex h-full flex-col border-t border-primary pt-5">
                        <a href="{{ $book->url }}" class="block bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent" tabindex="-1" aria-hidden="true">
                            @if ($book->portada)
                                <img src="{{ Storage::url($book->portada) }}" alt="" class="aspect-[4/5] w-full object-contain p-5 transition-transform duration-500 group-hover:scale-[1.015] motion-reduce:transition-none" loading="lazy">
                            @else
                                <span class="block aspect-[4/5] border border-gray-2" aria-hidden="true"></span>
                            @endif
                        </a>
                        <p class="mt-5 font-[var(--font-editorial)] text-sm text-primary">{{ $book->fecha_publicacion?->year ?: 'Sin año' }}@if($book->autoria) · {{ $book->autoria }}@endif</p>
                        <h3 class="mt-3 font-[var(--font-display)] text-[1.75rem] font-normal leading-[1.05] tracking-[-0.02em] text-primary">
                            <a href="{{ $book->url }}" class="focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">{{ $book->title }}</a>
                        </h3>
                        <a href="{{ $book->url }}" class="group/link mt-auto inline-flex min-h-12 w-fit items-center border-b border-primary pt-6 font-[var(--font-body)] text-sm font-semibold text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">Ver información <span class="ml-2 transition-transform group-hover/link:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span></a>
                    </article>
                </li>
            @endforeach
        </ol>
    </div>
</x-block>
@else
<x-block class="border-y border-gray-2 bg-[var(--color-surface-ivory)] py-14 sm:py-16 lg:py-20">
    <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid gap-12 lg:grid-cols-2 lg:items-stretch lg:gap-16">
            <div>
                @foreach ($items as $item)
                @php
                    $isBook = $item instanceof Libro;
                    $image = $isBook ? $item->portada : $item->route?->image;
                    $url = $isBook ? $item->url : ($item->document_url ?: $item->url);
                    $descriptionText = Str::squish(strip_tags((string) ($isBook ? $item->descripcion : $item->resumen)));
                @endphp
                <article class="grid gap-7 sm:grid-cols-[minmax(12rem,0.75fr)_1.25fr] sm:items-end">
                    <div class="bg-white">
                        @if ($image)
                            <img src="{{ Storage::url($image) }}" alt="Portada de {{ $item->title }}" class="mx-auto aspect-[4/5] max-h-[29rem] w-full object-contain p-5" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                        @else
                            <div class="aspect-[4/5] border border-gray-2 bg-white" aria-hidden="true"></div>
                        @endif
                    </div>
                    <div class="pb-2">
                        @if ($show_type_label ?? true)<p class="font-source text-sm text-primary">{{ $isBook ? 'Libro' : 'Artículo académico' }} · {{ $item->fecha_publicacion?->year }}</p>@endif
                        <h3 class="mt-3 font-sans text-[clamp(2rem,3vw,3.25rem)] font-normal leading-[1] tracking-[-.03em] text-primary">{{ $item->title }}</h3>
                        @if ($isBook && $item->autoria)<p class="mt-4 font-source text-lg text-primary">{{ $item->autoria }}</p>@endif
                        @if ($descriptionText)<p class="mt-4 font-body text-sm leading-relaxed text-gray">{{ Str::limit($descriptionText, 170) }}</p>@endif
                        <a href="{{ $url }}" class="group mt-5 inline-flex min-h-10 items-center border-b border-primary font-body text-sm text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">Ver ficha <span class="ml-2 transition-transform group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span></a>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="flex flex-col justify-between border-l border-primary pl-7 sm:pl-10 lg:pl-12">
                <div>
                    <p class="font-source text-sm text-primary">Archivo editorial</p>
                    @if ($title ?? null)<h2 class="mt-4 max-w-[12ch] font-sans text-[clamp(2.5rem,4vw,4.25rem)] font-normal leading-[.96] tracking-[-.035em] text-primary">{{ $title }}</h2>@endif
                    @if ($description ?? null)<p class="mt-5 max-w-[38ch] font-source text-lg leading-relaxed text-gray">{{ $description }}</p>@endif
                    <dl class="mt-8 grid grid-cols-3 border-y border-primary">
                        @foreach ([['Libros', $booksCount], ['Artículos', $articlesCount], ['Áreas', $topicsCount]] as [$label, $count])
                            <div class="px-3 py-5 first:pl-0 last:pr-0 [&+&]:border-l [&+&]:border-primary sm:px-5">
                                <dt class="font-body text-xs text-gray">{{ $label }}</dt>
                                <dd class="mt-1 font-source text-3xl text-primary">{{ $count }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
                @if ($cta_label ?? null)
                    <x-link :attrs="array_merge($cta, ['hideIfNull' => true])" class="group mt-8 inline-flex min-h-12 w-fit items-center border border-primary bg-primary px-6 font-body text-base text-white hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                        {{ $cta_label }} <span class="ml-3 transition-transform group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span>
                    </x-link>
                @endif
            </div>
        </div>
    </div>
</x-block>
@endif
@endif
