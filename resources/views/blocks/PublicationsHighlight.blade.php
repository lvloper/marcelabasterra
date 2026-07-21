@php
    use App\Models\ArticuloAcademico;
    use App\Models\Libro;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $limit = min(max((int) ($max_items ?? 6), 1), 12);
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
