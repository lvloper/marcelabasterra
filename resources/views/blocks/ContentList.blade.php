@php
    $contentItems = collect($items ?? [])
        ->filter(static fn ($item) => is_array($item) && collect($item)->filter()->isNotEmpty())
        ->values();
@endphp

@if ($contentItems->isNotEmpty())
    <x-block class="border-y border-gray-2 bg-white py-10 md:py-12">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
            @if (($title ?? null) || ($description ?? null))
                <header class="grid gap-4 md:grid-cols-12 md:gap-8">
                    @if ($title ?? null)
                        <h2 class="font-[var(--font-editorial)] text-xl font-normal leading-tight text-primary md:col-span-4 md:text-2xl">
                            {{ $title }}
                        </h2>
                    @endif

                    @if ($description ?? null)
                        <p class="max-w-[68ch] font-[var(--font-body)] text-[1rem] leading-[1.65] text-gray md:col-span-8">
                            {{ $description }}
                        </p>
                    @endif
                </header>
            @endif

            <ol
                class="grid gap-x-10 md:grid-cols-2 lg:gap-x-16 {{ ($title ?? null) || ($description ?? null) ? 'mt-8 md:mt-10' : '' }}"
                aria-label="{{ $title ?? 'Listado de contenido' }}"
            >
                @foreach ($contentItems as $item)
                    <li class="border-t border-primary/25 py-6 md:py-7">
                        @if ($item['meta'] ?? null)
                            <p class="mb-3 font-[var(--font-body)] text-sm leading-relaxed text-gray">
                                {{ $item['meta'] }}
                            </p>
                        @endif

                        @if ($item['title'] ?? null)
                            <h3 class="max-w-[28ch] font-[var(--font-display)] text-2xl font-normal leading-[1.08] tracking-[-0.015em] text-primary md:text-3xl">
                                {{ $item['title'] }}
                            </h3>
                        @endif

                        @if ($item['text'] ?? null)
                            <p class="mt-3 max-w-[68ch] font-[var(--font-body)] text-[1rem] leading-[1.65] text-gray">
                                {{ $item['text'] }}
                            </p>
                        @endif

                        @if ($item['url'] ?? null)
                            <a
                                href="{{ $item['url'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-4 inline-flex min-h-12 items-center gap-2 font-[var(--font-body)] text-[1rem] font-semibold text-primary underline decoration-gray-2 underline-offset-4 transition-colors duration-200 hover:decoration-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none"
                                aria-label="{{ $item['link_label'] ?? 'Ver más' }}: {{ $item['title'] ?? $item['meta'] ?? 'contenido' }} (se abre en una pestaña nueva)"
                            >
                                <span>{{ $item['link_label'] ?? 'Ver más' }}</span>
                                <span aria-hidden="true">↗</span>
                            </a>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </x-block>
@endif
