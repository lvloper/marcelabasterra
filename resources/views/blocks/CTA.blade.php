@php
    $buttonRoute = is_array($button_route ?? null) ? $button_route : [];
@endphp

<x-block class="bg-primary py-10 sm:py-12">
    <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col gap-7 md:flex-row md:items-center md:justify-between">
            <div>
                @if ($title ?? false)
                    <h2 class="font-sans text-[clamp(1.9rem,3vw,2.75rem)] font-normal leading-tight tracking-[-0.025em] text-white">{{ $title }}</h2>
                @endif

                @if ($text ?? false)
                    <p class="mt-3 max-w-[60ch] font-source text-lg leading-relaxed text-gray-3">{{ $text }}</p>
                @endif
            </div>

            @if ($button_label ?? false)
                <div class="shrink-0">
                    <x-link :attrs="array_merge($buttonRoute, ['hideIfNull' => true])" class="group inline-flex min-h-12 items-center justify-center border border-white bg-white px-6 py-3 font-body text-base font-medium text-primary transition-colors duration-300 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none">
                        {{ $button_label }} <span class="ml-3 transition-transform duration-300 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span>
                    </x-link>
                </div>
            @endif
        </div>
    </div>
</x-block>
