@php
    use App\Support\EventCatalog;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Storage;

    $catalog = app(EventCatalog::class);
    $sourceMode = $source_mode ?? 'automatic';
    $eventIds = $sourceMode === 'selected'
        ? collect($eventos ?? [])->filter()->map(fn ($id): int => (int) $id)->values()
        : null;
    $conferenceIds = $sourceMode === 'selected'
        ? collect($conferencias ?? [])->filter()->map(fn ($id): int => (int) $id)->values()
        : null;
    $items = $catalog->all($eventIds, $conferenceIds, (bool) ($include_conferences ?? true));
    $featuredItems = $catalog->featured($items, min(max((int) ($max_items ?? 1), 1), 3));
    $imageUrl = static fn (?string $image): ?string => blank($image)
        ? null
        : (filter_var($image, FILTER_VALIDATE_URL) ? $image : Storage::url($image));
@endphp

@if ($featuredItems->isNotEmpty())
    <x-block class="border-y border-primary bg-white py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            <header class="grid gap-6 border-t border-primary pt-6 lg:grid-cols-12 lg:items-end lg:gap-12">
                <div class="lg:col-span-7">
                    <p class="mb-4 font-source text-2xl text-gray">En primer plano</p>
                    @if ($title ?? null)
                        <h2 class="max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary">{{ $title }}</h2>
                    @endif
                </div>
                @if ($description ?? null)
                    <p class="max-w-[48ch] font-source text-xl leading-relaxed text-gray lg:col-span-4 lg:col-start-9">{{ $description }}</p>
                @endif
            </header>

            <div class="mt-10 grid gap-12">
                @foreach ($featuredItems as $item)
                    @php($featuredImage = $imageUrl($item['image']))
                    <article class="grid border-t border-primary lg:grid-cols-12">
                        @if ($item['url'])
                            <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif class="group order-2 flex flex-col justify-between bg-gray-3 p-6 sm:p-10 lg:order-1 lg:col-span-5 lg:p-12 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                        @else
                            <div class="order-2 flex flex-col justify-between bg-gray-3 p-6 sm:p-10 lg:order-1 lg:col-span-5 lg:p-12">
                        @endif
                            <div>
                                <div class="flex flex-wrap items-center gap-x-5 gap-y-2 font-body text-sm text-primary">
                                    <span class="border-l border-accent pl-3 font-semibold">{{ $item['is_upcoming'] ? 'Próximo' : 'Evento más reciente' }}</span>
                                    <span>{{ $item['type_label'] }}</span>
                                </div>

                                @if ($item['date'])
                                    <time datetime="{{ $item['date'] }}" class="mt-8 block font-source text-[clamp(2rem,4vw,4rem)] leading-none text-primary">
                                        {{ Carbon::parse($item['date'])->locale('es')->translatedFormat('d M Y') }}
                                    </time>
                                @endif

                                <h3 class="mt-8 max-w-[18ch] font-sans text-[clamp(2rem,3.5vw,3.75rem)] font-normal leading-[1] tracking-[-0.025em] text-primary transition-colors duration-300 group-hover:text-primary/70">{{ $item['title'] }}</h3>

                                @if ($item['institution'])
                                    <p class="mt-5 font-source text-xl leading-snug text-primary">{{ $item['institution'] }}</p>
                                @endif

                                @if ($item['location'] || $item['topic'])
                                    <dl class="mt-6 grid gap-4 border-t border-primary/30 pt-5 font-body text-base text-gray">
                                        @if ($item['location'])
                                            <div><dt class="font-semibold text-primary">Lugar</dt><dd class="mt-1">{{ $item['location'] }}</dd></div>
                                        @endif
                                        @if ($item['topic'])
                                            <div><dt class="font-semibold text-primary">Tema</dt><dd class="mt-1">{{ $item['topic'] }}</dd></div>
                                        @endif
                                    </dl>
                                @endif

                                @if ($item['summary'])
                                    <p class="mt-6 max-w-[60ch] font-body text-base leading-relaxed text-gray">{{ $item['summary'] }}</p>
                                @endif
                            </div>

                            @if ($item['url'])
                                <span class="group/cta mt-10 inline-flex min-h-12 w-fit items-center border border-primary bg-primary px-6 font-body text-base font-medium text-white transition-colors duration-300 group-hover:bg-white group-hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent" aria-hidden="true">
                                    Ver más <span class="ml-3 transition-transform duration-300 group-hover/cta:translate-x-1" aria-hidden="true">{{ $item['external'] ? '↗' : '→' }}</span>
                                </span>
                            @endif
                        @if ($item['url'])
                            </a>
                        @else
                            </div>
                        @endif

                        <div class="order-1 min-h-[24rem] overflow-hidden bg-primary lg:order-2 lg:col-span-7 lg:min-h-[42rem]">
                            @if ($featuredImage)
                                <img src="{{ $featuredImage }}" alt="" class="h-full w-full object-cover transition-transform duration-700 hover:scale-[1.025] motion-reduce:transition-none" loading="lazy">
                            @else
                                <div class="flex h-full min-h-[24rem] items-end p-8 lg:p-12" aria-hidden="true">
                                    <span class="font-source text-[clamp(5rem,12vw,11rem)] leading-none text-white/20">{{ $item['year'] }}</span>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </x-block>
@elseif ($preview ?? false)
    <x-block-preview-empty
        :title="$title ?? 'Eventos destacados'"
        :description="$description ?? null"
        message="Seleccioná actividades o habilitá una fuente automática para completar la vista previa."
    />
@endif
