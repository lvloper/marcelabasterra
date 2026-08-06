@props([
    'items' => [],
    'route' => null,
    'current' => null,
    'class' => '',
    'dark' => false,
])

@php
    $crumbs = [];

    if ($route) {
        $chain = [];
        $node = $route->parent;
        while ($node && ! in_array($node->full_slug, ['home'], true)) {
            $chain[] = $node;
            $node = $node->parent;
        }

        foreach (array_reverse($chain) as $ancestor) {
            $crumbs[] = ['label' => $ancestor->title, 'url' => url($ancestor->full_slug)];
        }

        $currentLabel = $current ?? $route->title;
    } else {
        $currentLabel = $current ?? (count($items) ? (string) last($items)['label'] : null);

        foreach (array_slice($items, 0, -1) as $item) {
            $crumbs[] = $item;
        }
    }

    $all = array_merge(
        [['label' => 'Inicio', 'url' => url('/')]],
        $crumbs,
        $currentLabel ? [['label' => $currentLabel]] : [],
    );
@endphp

@unless (count($all) <= 1)
    <nav aria-label="Migas de pan" class="{{ $class }}">
        <ol class="flex flex-wrap items-center gap-2 font-body text-sm">
            @foreach ($all as $index => $crumb)
                <li class="flex items-center gap-2">
                    @if (! empty($crumb['url']) && ! $loop->last)
                        <a wire:navigate href="{{ $crumb['url'] }}"
                            class="{{ $dark ? 'text-gray-3 hover:text-accent' : 'text-gray hover:text-accent' }} underline decoration-accent underline-offset-4 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">
                            {{ $crumb['label'] }}
                        </a>
                    @else
                        <span class="{{ $dark ? 'text-white' : 'text-primary' }}" @if ($loop->last) aria-current="page" @endif>{{ $crumb['label'] }}</span>
                    @endif

                    @unless ($loop->last)
                        <span aria-hidden="true" class="{{ $dark ? 'text-gray-3' : 'text-gray' }}">&rsaquo;</span>
                    @endunless
                </li>
            @endforeach
        </ol>
    </nav>

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                @foreach ($all as $index => $crumb)
                    {
                        "@type": "ListItem",
                        "position": {{ $index + 1 }},
                        "name": @json($crumb['label'])@if (! empty($crumb['url'])),
                        "item": @json($crumb['url'])
                        @endif
                    }{{ $loop->last ? '' : ',' }}
                @endforeach
            ]
        }
    </script>
@endunless
