@php
    $id = $id ?? 'text-' . uniqid();
    $widthClass = match ($width ?? 'container') {
        'narrow' => 'max-w-3xl',
        'wide' => 'max-w-7xl',
        default => 'max-w-5xl',
    };

    $imageUrl = null;
    if (isset($image) && $image) {
        $imgValue = is_array($image) ? ($image[0] ?? null) : $image;
        if ($imgValue) {
            $imageUrl = \Illuminate\Support\Facades\Storage::url($imgValue);
        }
    }
    $hasImage = ! empty($imageUrl);

    $htmlContent = $content ?? '';
    if (is_array($htmlContent)) {
        $renderTiptap = null;
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
        foreach ($htmlContent as $node) {
            $rendered .= $renderTiptap($node);
        }
        $htmlContent = $rendered;
    }
@endphp

<x-block class="py-12 md:py-16" id="{{ $id }}">
    <div class="container mx-auto px-4">
        <div class="mx-auto {{ $widthClass }} {{ $hasImage ? 'flex flex-col md:flex-row gap-8 md:gap-12' : '' }}">
            @if ($hasImage)
                <div class="shrink-0 w-full md:w-2/5 lg:w-1/3 text-reveal-img">
                    <img
                        src="{{ $imageUrl }}"
                        alt="{{ $title ?? '' }}"
                        class="w-full h-auto object-cover"
                    >
                </div>
            @endif

            <div class="{{ $hasImage ? 'flex-1' : '' }}">
                @if ($eyebrow ?? null)
                    <p class="mb-4 font-source text-2xl text-gray text-reveal">{{ $eyebrow }}</p>
                @endif

                @if ($title ?? null)
                    <h2 class="mb-6 max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary text-reveal">{{ $title }}</h2>
                @endif

                @if ($htmlContent)
                    <div class="prose prose-lg max-w-none text-gray-700 [&_p]:mb-4 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&_li]:mb-1 text-reveal">
                        {!! $htmlContent !!}
                    </div>
                @endif

                @php
                    $renderCta = function ($cta) {
                        if (empty($cta)) return null;
                        $url = '';
                        $target = '';
                        if (($cta['route_id'] ?? null) === '0' && ($cta['external_url'] ?? null)) {
                            $url = $cta['external_url'];
                            $target = ($cta['new_window'] ?? false) ? '_blank' : '';
                        } elseif ($cta['route_id'] ?? null) {
                            $ctaRoute = \App\Models\Route::find($cta['route_id']);
                            if ($ctaRoute) {
                                $url = url($ctaRoute->full_slug);
                                $target = ($cta['new_window'] ?? false) ? '_blank' : '';
                                if ($cta['anchor'] ?? null) {
                                    $url .= '#' . $cta['anchor'];
                                }
                            }
                        }
                        return $url ? ['url' => $url, 'label' => $cta['btn_label'] ?? 'Ver más', 'target' => $target] : null;
                    };
                    $ctaPrimary = $renderCta($cta_primary ?? null);
                    $ctaSecondary = $renderCta($cta_secondary ?? null);
                @endphp

                @if ($ctaPrimary || $ctaSecondary)
                    <div class="flex flex-wrap items-center gap-4 mt-6 text-reveal-cta">
                        @if ($ctaPrimary)
                            <a
                                href="{{ $ctaPrimary['url'] }}"
                                @if ($ctaPrimary['target']) target="{{ $ctaPrimary['target'] }}" rel="noopener noreferrer" @endif
                                class="inline-flex items-center px-6 py-3 rounded-lg bg-primary text-white font-semibold hover:bg-primary-hover transition-colors"
                            >
                                {{ $ctaPrimary['label'] }}
                            </a>
                        @endif

                        @if ($ctaSecondary)
                            <a
                                href="{{ $ctaSecondary['url'] }}"
                                @if ($ctaSecondary['target']) target="{{ $ctaSecondary['target'] }}" rel="noopener noreferrer" @endif
                                class="inline-flex items-center px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors"
                            >
                                {{ $ctaSecondary['label'] }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-block>

@pushOnce('scripts', 'block-text')
<style>
    #{{ $id }} .text-reveal-img {
        opacity: 0;
        transform: translateX(-40px);
    }
    #{{ $id }} .text-reveal {
        opacity: 0;
        transform: translateY(30px);
    }
    #{{ $id }} .text-reveal-cta {
        opacity: 0;
        transform: translateY(20px);
    }
</style>
<script>
    document.addEventListener('livewire:navigated', function() {
        const BLOCK = document.getElementById('{{ $id }}');
        if (!BLOCK) return;
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: BLOCK,
                start: 'top 80%',
                toggleActions: 'play none none none',
            },
            defaults: { ease: 'power3.out' },
        });

        tl.to('.text-reveal-img', { x: 0, opacity: 1, duration: 0.9 }, 0)
          .to('.text-reveal', { y: 0, opacity: 1, duration: 0.7, stagger: 0.15 }, 0.1)
          .to('.text-reveal-cta', { y: 0, opacity: 1, duration: 0.6 }, 0.5);
    });
</script>
@endPushOnce
