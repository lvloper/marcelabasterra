@php
    $bioHtml = $summary ?? '';
    $headingTag = ($heading_level ?? 'h2') === 'h1' ? 'h1' : 'h2';
    if (is_array($bioHtml)) {
        $renderTiptap = function ($node) use (&$renderTiptap): string {
            if (! is_array($node)) return e((string) $node);
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
        foreach ($bioHtml as $node) {
            $rendered .= $renderTiptap($node);
        }
        $bioHtml = $rendered;
    }

    $bioUrl = '';
    $bioRoute = $cta_route ?? [];
    if ($bioRoute['route_id'] ?? null) {
        $r = \App\Models\Route::find($bioRoute['route_id']);
        if ($r) {
            $bioUrl = url($r->full_slug);
            if ($bioRoute['anchor'] ?? null) $bioUrl .= '#' . $bioRoute['anchor'];
        }
    } elseif ($bioRoute['external_url'] ?? null) {
        $bioUrl = $bioRoute['external_url'];
    }
@endphp

<x-block class="bg-primary py-16 sm:py-20 lg:py-28 relative overflow-hidden">
    <div class="relative mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:gap-16 items-center">
            @php
                $photoValue = is_array($photo ?? null) ? ($photo[0] ?? null) : ($photo ?? null);
            @endphp
            @if ($photoValue)
                <div class="lg:col-span-4">
                    <div class="aspect-[3/4] overflow-hidden bg-gray-3">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($photoValue) }}"
                            alt="{{ $title ?? '' }}"
                            class="h-full w-full object-cover"
                        >
                    </div>
                </div>
            @endif

            <div class="{{ $photoValue ? 'lg:col-span-8' : 'lg:col-span-10 lg:col-start-2' }}">
                @if ($tag ?? null)
                    <div class="inline-block bg-white px-3 py-1 font-source text-sm tracking-wider text-primary">{{ $tag }}</div>
                @endif

                @if ($title ?? null)
                    <{{ $headingTag }} class="mt-4 font-sans text-[clamp(2.25rem,4.5vw,4rem)] font-normal leading-[.96] tracking-[-.035em] text-white">{{ $title }}</{{ $headingTag }}>
                @endif

                @if ($bioHtml)
                    <div class="mt-8 max-w-[68ch] font-source text-xl leading-relaxed text-white [&_p]:mb-5 [&_p:last-child]:mb-0 [&_a]:text-white [&_a]:underline [&_strong]:font-bold">
                        {!! $bioHtml !!}
                    </div>
                @endif

                @if ($bioUrl && ($cta_label ?? null))
                    <a href="{{ $bioUrl }}"
                       @if ($bioRoute['new_window'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                       class="group mt-12 inline-flex min-h-12 items-center border border-white bg-white px-6 font-body text-base text-primary hover:bg-transparent hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                        {{ $cta_label }}
                        <x-lucide-arrow-right class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" />
                    </a>
                @endif
            </div>
        </div>

        @if (isset($highlights) && is_array($highlights) && count($highlights) > 0)
            <div class="mt-16 pt-12 border-t border-white/20">
                <div class="grid grid-cols-2 gap-x-8 gap-y-10 md:grid-cols-4">
                    @foreach ($highlights as $index => $item)
                        @php $number = $item['number'] ?? ''; $label = $item['label'] ?? ''; @endphp
                        @if ($number || $label)
                            <div class="text-center {{ $index > 0 ? 'md:border-l md:border-white/20 md:pl-8' : '' }}">
                                <div class="font-source text-[clamp(2rem,3.5vw,3.5rem)] leading-none text-white">{{ $number }}</div>
                                <div class="mt-3 font-body text-sm text-white">{{ $label }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-block>
