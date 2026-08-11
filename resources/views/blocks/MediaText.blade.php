@php
    $layout = $layout ?? 'left';
    $mediaType = $media_type ?? 'image';
    $isLeft = $layout === 'left';
    $image = is_array($image ?? null) ? ($image[0] ?? null) : ($image ?? null);
    $videoFile = is_array($video_file ?? null) ? ($video_file[0] ?? null) : ($video_file ?? null);
    $hasMedia = ($mediaType === 'youtube' && filled($youtube_id ?? null))
        || ($mediaType === 'upload' && filled($videoFile))
        || ($mediaType === 'image' && filled($image));

    $htmlContent = $content ?? '';
    if (is_array($htmlContent)) {
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

    $ctaUrl = null;
    $ctaTarget = null;
    if (is_array($cta ?? null)) {
        if (($cta['route_id'] ?? null) === '0' && filled($cta['external_url'] ?? null)) {
            $ctaUrl = $cta['external_url'];
            $ctaTarget = ($cta['new_window'] ?? true) ? '_blank' : null;
        } elseif (($cta['route_id'] ?? null) === '-1' && filled($cta['file'] ?? null)) {
            $ctaUrl = \Illuminate\Support\Facades\Storage::url($cta['file']);
            $ctaTarget = ($cta['new_window'] ?? false) ? '_blank' : null;
        } elseif (filled($cta['route_id'] ?? null)) {
            $ctaRoute = \App\Models\Route::find($cta['route_id']);
            if ($ctaRoute) {
                $ctaUrl = url($ctaRoute->full_slug);
                $ctaTarget = ($cta['new_window'] ?? false) ? '_blank' : null;

                if (filled($cta['anchor'] ?? null)) {
                    $ctaUrl .= '#' . $cta['anchor'];
                }
            }
        }
    }
@endphp

{{-- v4 Bandera azul: superficie institucional, video en marco blanco, acento celeste --}}
<x-block class="bg-primary py-16 md:py-24">
    <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid items-start gap-10 lg:grid-cols-12 lg:gap-8">
            <div @class([
                'lg:col-span-7' => true,
                'lg:order-1' => $isLeft,
                'lg:order-2' => ! $isLeft,
            ])>
                @if ($hasMedia)
                    @if ($mediaType === 'youtube' && ($youtube_id ?? null))
                    <div class="aspect-video border border-white/60 bg-white">
                        <iframe
                            src="https://www.youtube-nocookie.com/embed/{{ rawurlencode($youtube_id) }}"
                            title="{{ $title ? 'Video: ' . $title : 'Video de YouTube' }}"
                            class="h-full w-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                        ></iframe>
                    </div>
                @elseif ($mediaType === 'upload' && $videoFile)
                    <div class="aspect-video border border-white/60 bg-white">
                        <video controls class="h-full w-full" preload="metadata">
                            <source src="{{ \Illuminate\Support\Facades\Storage::url($videoFile) }}" type="video/mp4">
                            Tu navegador no admite la reproducción de video.
                        </video>
                    </div>
                @elseif ($mediaType === 'image' && $image)
                    <div class="border border-white/60 bg-white">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($image) }}"
                            alt="{{ $title ?? '' }}"
                            class="h-auto w-full object-cover"
                        >
                    </div>
                    @endif
                @elseif ($preview ?? false)
                    <x-block-preview-empty
                        title="Multimedia"
                        message="Seleccioná una imagen o un video para completar esta columna."
                    />
                @endif
            </div>

            <div @class([
                'lg:col-span-4' => true,
                'lg:col-start-9 lg:order-2' => $hasMedia && $isLeft,
                'lg:col-start-1 lg:order-1' => $hasMedia && ! $isLeft,
                'lg:col-span-7 lg:col-start-1' => ! $hasMedia,
            ])>
                <span class="mb-6 block h-px w-16 bg-accent" aria-hidden="true"></span>

                @if ($title ?? null)
                    <h2 class="max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-white">
                        {{ $title }}
                    </h2>
                @endif

                @if ($htmlContent)
                    <div class="text-wysiwyg mt-6 max-w-[68ch] font-[var(--font-editorial)] text-lg leading-[1.6] text-gray-3 [&_a]:text-accent [&_a]:decoration-accent [&_a]:underline [&_a]:underline-offset-4 [&_p:not(:last-child)]:mb-5">
                        {!! $htmlContent !!}
                    </div>
                @endif

                @if ($ctaUrl)
                    <a
                        href="{{ $ctaUrl }}"
                        @if ($ctaTarget) target="{{ $ctaTarget }}" rel="noopener noreferrer" @endif
                        class="group mt-8 inline-flex min-h-12 items-center gap-3 border border-white bg-white px-6 py-3 font-[var(--font-body)] text-base font-semibold text-primary transition-colors duration-200 hover:bg-transparent hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none"
                    >
                        <span>{{ $cta['btn_label'] ?? 'Ver más' }}</span>
                        <span class="transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-block>
