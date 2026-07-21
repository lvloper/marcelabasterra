@php
    $bioHtml = $summary ?? '';
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

<x-block class="bg-white py-16 sm:py-20 lg:py-28">
    <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:gap-16">
            @if ($photo ?? null)
                <div class="lg:col-span-4">
                    <div class="aspect-[3/4] overflow-hidden bg-gray-3">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($photo) }}"
                            alt="{{ $title ?? '' }}"
                            class="h-full w-full object-cover"
                        >
                    </div>
                </div>
            @endif

            <div class="{{ ($photo ?? null) ? 'lg:col-span-8' : 'lg:col-span-10 lg:col-start-2' }} border-t border-primary pt-7">
                @if ($title ?? null)
                    <h2 class="max-w-[12ch] font-sans text-[clamp(2.75rem,5vw,5rem)] font-normal leading-[.96] tracking-[-.035em] text-primary">{{ $title }}</h2>
                @endif

                @if ($bioHtml)
                    <div class="mt-8 max-w-[68ch] font-source text-xl leading-relaxed text-gray [&_p]:mb-5 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline [&_strong]:font-bold">
                        {!! $bioHtml !!}
                    </div>
                @endif

                @if ($bioUrl && ($cta_label ?? null))
                    <a href="{{ $bioUrl }}"
                       @if ($bioRoute['new_window'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                       class="group mt-9 inline-flex min-h-12 items-center border border-primary bg-primary px-6 font-body text-base text-white hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                        {{ $cta_label }}
                        <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-block>
