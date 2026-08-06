@php
    use App\Models\Libro;
    use Illuminate\Support\Facades\Storage;

    $libro = null;
    $imageUrl = null;

    if (isset($libro_id) && $libro_id) {
        $libro = Libro::with('route')->find((int) $libro_id);
    }

    $rawImage = $image ?? $libro?->portada;
    if (is_array($rawImage)) {
        $rawImage = $rawImage[0] ?? null;
    }

    $imageUrl = $rawImage ? Storage::url($rawImage) : null;

    $displayDate = $date ?? $libro?->fecha_publicacion?->year;
    $bookTitle = $libro?->title;
    $bookAuthor = $libro?->autoria;
    $bookSubtitle = $subtitle ?? $libro?->subtitulo;
    $bookPublisher = $publisher ?? $libro?->editorial;

    $ctaLabel = $cta_label ?? 'Ver publicación';
    $ctaRoute = $cta_route ?? ['routeName' => 'publications.books'];

    $otherBooks = Libro::with('route')
        ->when($libro_id ?? null, fn ($q) => $q->where('id', '!=', (int) $libro_id))
        ->isPublished()
        ->orderByDesc('fecha_publicacion')
        ->limit(4)
        ->get();
@endphp

@if ($imageUrl)
<x-block class="bg-primary">

    <div class="mx-auto max-w-[1440px] px-5 pb-16 pt-12 sm:px-8 lg:px-12 xl:px-16">

        <div class="grid gap-12 lg:grid-cols-12 lg:gap-8 xl:gap-12">

            <div class="flex flex-col justify-center text-left lg:col-span-4 order-1">
                @if ($title ?? null)
                    <p class="mb-4 font-source text-2xl text-gray-3">{{ $title }}</p>
                @endif

                @if ($bookAuthor)
                    <p class="font-[var(--font-editorial)] text-lg font-bold text-white/80">{{ $bookAuthor }}</p>
                @endif

                @if ($bookTitle)
                    <h3 class="mt-3 max-w-[18ch] font-[var(--font-display)] text-[clamp(1.5rem,2.5vw,2.25rem)] font-bold leading-[.95] tracking-[-.02em] text-white">{{ $bookTitle }}</h3>
                @endif

                @if ($bookSubtitle)
                    <p class="mt-4 max-w-[45ch] font-[var(--font-editorial)] text-base leading-relaxed text-gray-3">{{ $bookSubtitle }}</p>
                @endif

                @if ($bookPublisher || $displayDate)
                    <div class="mt-6 border-t border-white/20 pt-4">
                        <p class="font-[var(--font-editorial)] text-sm font-bold uppercase tracking-widest text-white/60">
                            @if ($bookPublisher){{ $bookPublisher }}@endif
                            @if ($bookPublisher && $displayDate) · @endif
                            @if ($displayDate){{ $displayDate }}@endif
                        </p>
                    </div>
                @endif

                <a href="{{ $libro?->url ?? route('publications.books') }}" class="group mt-8 inline-flex min-h-12 w-fit items-center justify-center gap-3 border border-white bg-white px-6 py-3 font-body text-base font-medium text-primary transition-colors duration-300 hover:bg-transparent hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                    {{ $ctaLabel }} <span class="transition-transform duration-300 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span>
                </a>
            </div>

            <div class="flex items-center justify-center lg:col-span-4 order-3 lg:order-2">
                <div class="w-full max-w-xs">
                    <img
                        src="{{ $imageUrl }}"
                        alt="{{ $bookTitle ?? '' }}"
                        class="w-full object-contain"
                        loading="eager"
                    >
                </div>
            </div>

            <div class="flex flex-col text-left lg:col-span-4 order-2 lg:order-3">
                @if ($otherBooks->isNotEmpty())
                    <h3 class="font-[var(--font-editorial)] text-xs font-semibold uppercase tracking-wider text-gray-3">Otros libros publicados</h3>
                    <ul class="mt-6 divide-y divide-white/10">
                        @foreach ($otherBooks as $other)
                            <li>
                                <a href="{{ $other->url }}" class="group flex items-start gap-4 py-4 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                    @if ($other->portada)
                                        <img src="{{ Storage::url($other->portada) }}" alt="" class="h-20 w-14 flex-shrink-0 border border-white/20 object-contain bg-white" loading="lazy">
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <p class="font-[var(--font-editorial)] text-xs text-white/60">{{ $other->fecha_publicacion?->year ?? 'Sin año' }}</p>
                                        <p class="mt-0.5 font-[var(--font-display)] text-sm leading-tight text-white transition-colors group-hover:text-white/70">{{ $other->title }}</p>
                                    </div>
                                    <span class="mt-1 flex-shrink-0 text-white/40 transition-transform group-hover:translate-x-1" aria-hidden="true">→</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="mt-14 text-center">
            <a href="{{ route('publications.books') }}" class="group inline-flex min-h-12 items-center justify-center gap-3 border border-white bg-transparent px-6 py-3 font-body text-base font-medium text-white transition-colors duration-300 hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                Ver todos los libros publicados <span class="transition-transform duration-300 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</x-block>
@endif
