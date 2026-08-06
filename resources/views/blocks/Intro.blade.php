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

{{-- Split 5+7: título a la izquierda, cuerpo a la derecha, sin foto --}}
<x-block class="border-y border-gray-2 bg-gray-3 py-10 sm:py-14">
    <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-12 lg:items-start">
            <div class="lg:col-span-4">
                @if ($title ?? null)
                    <{{ $headingTag }} class="max-w-[14ch] font-sans text-[clamp(1.75rem,3vw,2.5rem)] font-normal leading-[1.05] tracking-[-0.025em] text-primary">{{ $title }}</{{ $headingTag }}>
                @endif

                @if ($bioUrl && ($cta_label ?? null))
                    <x-link :attrs="$bioRoute" class="group mt-6 hidden items-center gap-2 border-b border-primary pb-0.5 font-body text-sm text-primary transition-colors duration-300 hover:border-accent hover:text-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent lg:inline-flex">
                        {{ $cta_label }}
                        <span class="transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">→</span>
                    </x-link>
                @endif
            </div>

            <div class="lg:col-span-7 lg:col-start-6">
                @if ($bioHtml)
                    <div class="max-w-[62ch] font-source text-lg leading-relaxed text-gray [&_p]:mb-4 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline [&_strong]:font-bold">
                        {!! $bioHtml !!}
                    </div>
                @endif

                @if ($bioUrl && ($cta_label ?? null))
                    <x-link :attrs="$bioRoute" class="group mt-6 inline-flex items-center gap-2 border-b border-primary pb-0.5 font-body text-sm text-primary transition-colors duration-300 hover:border-accent hover:text-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent lg:hidden">
                        {{ $cta_label }}
                        <span class="transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">→</span>
                    </x-link>
                @endif
            </div>
        </div>
    </div>
</x-block>
