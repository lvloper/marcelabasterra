@php
    $htmlDescription = $description ?? '';
    if (is_array($htmlDescription)) {
        $renderTiptap = function ($node) use (&$renderTiptap): string {
            if (! is_array($node)) {
                return e((string) $node);
            }
            $type = $node['type'] ?? '';
            if ($type === 'text') {
                $txt = e($node['text'] ?? '');
                foreach ($node['marks'] ?? [] as $mark) {
                    $markType = is_array($mark) ? ($mark['type'] ?? '') : '';
                    $txt = match ($markType) {
                        'bold' => "<strong>{$txt}</strong>",
                        'italic' => "<em>{$txt}</em>",
                        'underline' => "<u>{$txt}</u>",
                        'strike' => "<s>{$txt}</s>",
                        'link' => '<a href="' . e(is_array($mark) ? ($mark['attrs']['href'] ?? '#') : '#') . '">' . $txt . '</a>',
                        default => $txt,
                    };
                }
                return $txt;
            }
            $children = '';
            foreach ($node['content'] ?? [] as $child) {
                $children .= $renderTiptap($child);
            }
            return match ($type) {
                'paragraph' => "<p>{$children}</p>",
                'heading' => '<h' . ($node['attrs']['level'] ?? 2) . ">{$children}</h" . ($node['attrs']['level'] ?? 2) . '>',
                'bulletList' => "<ul>{$children}</ul>",
                'orderedList' => "<ol>{$children}</ol>",
                'listItem' => "<li>{$children}</li>",
                'blockquote' => "<blockquote>{$children}</blockquote>",
                'codeBlock' => "<pre><code>{$children}</code></pre>",
                'horizontalRule' => '<hr>',
                'hardBreak' => '<br>',
                default => $children,
            };
        };
        $rendered = '';
        foreach ($htmlDescription as $node) {
            $rendered .= $renderTiptap($node);
        }
        $htmlDescription = $rendered;
    }

    $plainDescription = strip_tags((string) $htmlDescription);
    $words = preg_split('/\s+/u', $plainDescription, -1, PREG_SPLIT_NO_EMPTY);
    if (count($words) > 300) {
        $words = array_slice($words, 0, 300);
        $htmlDescription = '<p>' . implode(' ', $words) . '...</p>';
    }

    $blogPosts = null;
    $displayMode = $content_type ?? 'manual';
    $maxItems = (int) ($max_items ?? 9);

    if ($displayMode === 'latest') {
        $blogPosts = \App\Models\Blog::orderBy('is_featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->isPublished()
            ->take($maxItems)
            ->get();
    } elseif ($displayMode === 'tags' && !empty($selected_tags ?? [])) {
        $tagSlugs = [];
        foreach ($selected_tags as $tag) {
            if (is_string($tag)) {
                $decoded = json_decode($tag, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($decoded['es'])) {
                    $tagSlugs[] = $decoded['es'];
                } else {
                    $tagSlugs[] = $tag;
                }
            } else {
                $tagSlugs[] = (string) $tag;
            }
        }
        $tagSlugs = array_filter($tagSlugs);
        if (!empty($tagSlugs)) {
            $blogPosts = \App\Models\Blog::withAnyTags($tagSlugs)
                ->orderBy('is_featured', 'desc')
                ->orderBy('published_at', 'desc')
                ->isPublished()
                ->take($maxItems)
                ->get();
        }
    }
@endphp

<x-block class="py-12 md:py-16">
    <div class="container mx-auto px-4">
        <div class="mx-auto max-w-5xl">
        @if ($title ?? null)
            <h2 class="mb-4 max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary">{{ $title }}</h2>
        @endif

        @if ($htmlDescription)
            <div class="prose prose-lg max-w-3xl mx-auto text-gray-600 text-center mb-10">
                {!! $htmlDescription !!}
            </div>
        @endif

        @if ($blogPosts !== null && $blogPosts->isNotEmpty())
            @php
                $featured = $blogPosts->first();
                $rest = $blogPosts->slice(1);
            @endphp
            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                <div class="lg:w-9/12">
                    <div class="sticky top-8">
                        <a href="{{ $featured->url }}" class="block group">
                            <div class="overflow-hidden bg-gray-100 aspect-[16/9]">
                                <x-image :src="$featured->thumb" fit="cover"
                                    class="w-full h-full group-hover:scale-105 transition-transform duration-300"
                                    default="{{ asset('img/layout/default.webp') }}" />
                            </div>
                            <div class="mt-4 space-y-3">
                                <span class="text-xs text-primary uppercase font-bold tracking-wider">
                                    {{ $featured->published_at?->format('d/m/Y') ?? '' }}
                                </span>
                                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                                    {{ $featured->title }}
                                </h3>
                                <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                                    {{ $featured->short_description }}
                                </p>
                                <span class="inline-flex items-center text-sm font-medium text-primary">
                                    Seguir Leyendo
                                    <x-lucide-arrow-right class="ml-1 w-4 h-4" />
                                </span>
                            </div>
                        </a>
                    </div>
                </div>

                @if ($rest->isNotEmpty())
                    <div class="lg:w-3/12 flex flex-col gap-6 lg:border-l lg:border-gray-200 lg:pl-6">
                        @foreach ($rest as $item)
                            <a href="{{ $item->url }}" class="flex gap-3 group">
                                <div class="w-20 h-20 flex-shrink-0 overflow-hidden bg-gray-100">
                                    <x-image :src="$item->thumb" fit="cover"
                                        class="w-full h-full group-hover:scale-105 transition-transform duration-300"
                                        default="{{ asset('img/layout/default.webp') }}" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-xs text-primary uppercase font-bold tracking-wider">
                                        {{ $item->published_at?->format('d/m/Y') ?? '' }}
                                    </span>
                                    <h4 class="text-sm font-semibold text-gray-900 leading-tight line-clamp-2 mt-1">
                                        {{ $item->title }}
                                    </h4>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif ($displayMode === 'manual' && ($items ?? null))
            @php
                $featuredItem = $items[0] ?? null;
                $restItems = array_slice($items ?? [], 1);
            @endphp

            @if ($featuredItem)
                <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                    @php
                        $fi = $featuredItem;
                        $fiImage = $fi['image'] ?? null;
                        $fiImage = is_array($fiImage) ? ($fiImage[0] ?? null) : $fiImage;
                        $fiUrl = '';
                        $fiTarget = '';
                        $fiRoute = $fi['route'] ?? [];
                        if (($fiRoute['route_id'] ?? null) === '0' && ($fiRoute['external_url'] ?? null)) {
                            $fiUrl = $fiRoute['external_url'];
                            $fiTarget = ($fiRoute['new_window'] ?? false) ? '_blank' : '';
                        } elseif ($fiRoute['route_id'] ?? null) {
                            $r = \App\Models\Route::find($fiRoute['route_id']);
                            if ($r) {
                                $fiUrl = url($r->full_slug);
                                $fiTarget = ($fiRoute['new_window'] ?? false) ? '_blank' : '';
                                if ($fiRoute['anchor'] ?? null) {
                                    $fiUrl .= '#' . $fiRoute['anchor'];
                                }
                            }
                        }
                    @endphp

                    <div class="lg:w-9/12">
                        <div class="sticky top-8">
                            @if ($fiUrl)
                                <a href="{{ $fiUrl }}" @if ($fiTarget) target="{{ $fiTarget }}" rel="noopener noreferrer" @endif class="block group">
                            @else
                                <div class="group">
                            @endif
                                @if ($fiImage)
                                    <div class="overflow-hidden bg-gray-100 aspect-[16/9]">
                                        <img
                                            src="{{ \Illuminate\Support\Facades\Storage::url($fiImage) }}"
                                            alt="{{ $fi['title'] ?? '' }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        >
                                    </div>
                                @endif
                                <div class="mt-4 space-y-3">
                                    @if ($fi['label'] ?? null)
                                        <span class="inline-block text-xs font-bold uppercase tracking-wider text-primary bg-primary/10 px-3 py-1 rounded-full">
                                            {{ $fi['label'] }}
                                        </span>
                                    @endif
                                    @if ($fi['title'] ?? null)
                                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                                            {{ $fi['title'] }}
                                        </h3>
                                    @endif
                                    @if ($fi['description'] ?? null)
                                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                                            {{ $fi['description'] }}
                                        </p>
                                    @endif
                                    @if ($fiUrl)
                                        <span class="inline-flex items-center text-sm font-medium text-primary">
                                            {{ $fiRoute['btn_label'] ?? 'Ver más' }}
                                            <x-lucide-arrow-right class="ml-1 w-4 h-4" />
                                        </span>
                                    @endif
                                </div>
                            @if ($fiUrl)
                                </a>
                            @else
                                </div>
                            @endif
                        </div>
                    </div>

                    @if (count($restItems) > 0)
                        <div class="lg:w-3/12 flex flex-col gap-6 lg:border-l lg:border-gray-200 lg:pl-6">
                            @foreach ($restItems as $item)
                                @php
                                    $siImage = $item['image'] ?? null;
                                    $siImage = is_array($siImage) ? ($siImage[0] ?? null) : $siImage;
                                    $siUrl = '';
                                    $siTarget = '';
                                    $siRoute = $item['route'] ?? [];
                                    if (($siRoute['route_id'] ?? null) === '0' && ($siRoute['external_url'] ?? null)) {
                                        $siUrl = $siRoute['external_url'];
                                        $siTarget = ($siRoute['new_window'] ?? false) ? '_blank' : '';
                                    } elseif ($siRoute['route_id'] ?? null) {
                                        $r = \App\Models\Route::find($siRoute['route_id']);
                                        if ($r) {
                                            $siUrl = url($r->full_slug);
                                            $siTarget = ($siRoute['new_window'] ?? false) ? '_blank' : '';
                                            if ($siRoute['anchor'] ?? null) {
                                                $siUrl .= '#' . $siRoute['anchor'];
                                            }
                                        }
                                    }
                                @endphp

                                @if ($siUrl)
                                    <a href="{{ $siUrl }}" @if ($siTarget) target="{{ $siTarget }}" rel="noopener noreferrer" @endif class="group block overflow-hidden bg-gray-3 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                @else
                                    <div class="block overflow-hidden bg-gray-3">
                                @endif
                                    @if ($siImage)
                                        <div class="aspect-[4/3] overflow-hidden bg-gray-100">
                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::url($siImage) }}"
                                                alt="{{ $item['title'] ?? '' }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            >
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        @if ($item['label'] ?? null)
                                            <span class="inline-block text-xs font-bold uppercase tracking-wider text-primary bg-primary/10 px-2 py-0.5 rounded-full mb-1">
                                                {{ $item['label'] }}
                                            </span>
                                        @endif
                                        @if ($item['title'] ?? null)
                                            <h4 class="text-base font-semibold text-gray-900 mb-1 line-clamp-2 transition-colors duration-300 group-hover:text-primary">
                                                {{ $item['title'] }}
                                            </h4>
                                        @endif
                                        @if ($item['description'] ?? null)
                                            <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                                {{ $item['description'] }}
                                            </p>
                                        @endif
                                        @if ($siUrl)
                                            <span class="inline-flex items-center text-sm font-medium text-primary group-hover:underline" aria-hidden="true">
                                                {{ $siRoute['btn_label'] ?? 'Ver más' }}
                                                <x-lucide-arrow-right class="ml-1 w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
                                            </span>
                                        @endif
                                    </div>
                                @if ($siUrl)
                                    </a>
                                @else
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        @endif
        </div>
    </div>
</x-block>
