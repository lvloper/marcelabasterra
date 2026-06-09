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

<x-block class="py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="flex flex-col {{ ($photo ?? null) ? 'md:flex-row gap-10 md:gap-16' : '' }} items-center">
            @if ($photo ?? null)
                <div class="w-full md:w-2/5 shrink-0">
                    <div class="aspect-[3/4] overflow-hidden rounded-2xl bg-gray-100">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($photo) }}"
                            alt="{{ $title ?? '' }}"
                            class="h-full w-full object-cover"
                        >
                    </div>
                </div>
            @endif

            <div class="{{ ($photo ?? null) ? 'w-full md:w-3/5' : 'w-full max-w-3xl mx-auto' }}">
                @if ($title ?? null)
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 font-sans">{{ $title }}</h2>
                @endif

                @if ($bioHtml)
                    <div class="prose prose-lg max-w-none text-gray-700 font-source [&_p]:mb-4 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline [&_strong]:font-bold [&_em]:italic leading-relaxed">
                        {!! $bioHtml !!}
                    </div>
                @endif

                @if ($bioUrl && ($cta_label ?? null))
                    <a href="{{ $bioUrl }}"
                       @if ($bioRoute['new_window'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                       class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 font-bold rounded-sm mt-8 hover:bg-primary-hover transition-colors group">
                        {{ $cta_label }}
                        <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-block>
