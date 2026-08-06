@php
    use Illuminate\Support\Facades\Storage;

    $bioHtml = $summary ?? '';
    $headingTag = ($heading_level ?? 'h1') === 'h1' ? 'h1' : 'h2';
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

    $introPhoto = $photo ?? null;
    if (is_array($introPhoto)) {
        $introPhoto = $introPhoto[0] ?? null;
    }

    $photoUrl = $introPhoto ? Storage::url($introPhoto) : null;

    if (! $photoUrl) {
        $homeRouteId = \App\Models\Configuration::getValue('home_route_id')['route']['route_id'] ?? null;
        $home = $homeRouteId
            ? \App\Models\Route::find((int) $homeRouteId)
            : \App\Models\Route::where('slug', 'home')->first();
        if ($home && $home->routable) {
            foreach ($home->routable->blocks as $homeBlock) {
                if (($homeBlock['type'] ?? '') === 'Hero') {
                    $heroPhoto = $homeBlock['data']['profile_photo'] ?? null;
                    if (is_array($heroPhoto)) {
                        $heroPhoto = $heroPhoto[0] ?? null;
                    }
                    if ($heroPhoto) {
                        $photoUrl = Storage::url($heroPhoto);
                    }
                    break;
                }
            }
        }
    }
@endphp

{{-- Intro asimétrico con retrato: foto 4:5 en columna izquierda, texto en columna de lectura --}}
<x-block class="bg-white py-16 md:py-24">
    <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid items-start gap-12 lg:grid-cols-12 lg:items-stretch lg:gap-8">
            <div class="order-2 lg:order-1 lg:col-span-4 lg:flex lg:items-end">
                @if ($photoUrl)
                    <div class="lg:sticky lg:bottom-0 lg:w-full">
                        <figure>
                            <img
                                src="{{ $photoUrl }}"
                                alt="Retrato de Marcela Basterra"
                                class="block aspect-[5/6] h-auto w-full object-cover"
                            >
                        </figure>

                        @if (filled($quote ?? null))
                            <blockquote class="relative mt-0 border-t-2 border-primary bg-gray-3 px-8 py-8">
                                <span
                                    class="absolute right-8 top-4 font-[var(--font-editorial)] text-[clamp(5rem,8vw,8rem)] leading-none text-accent"
                                    aria-hidden="true"
                                >
                                    &ldquo;
                                </span>
                                <p class="max-w-[30ch] font-[var(--font-editorial)] text-lg italic leading-[1.5] text-primary lg:text-xl">
                                    {{ $quote }}
                                </p>
                                @if (filled($quote_author ?? null))
                                    <footer class="mt-6">
                                        <p class="font-body text-sm font-semibold tracking-wide text-primary">
                                            {{ $quote_author }}
                                        </p>
                                        @if (filled($quote_role ?? null))
                                            <p class="mt-1 font-body text-xs uppercase tracking-[0.08em] text-gray">
                                                {{ $quote_role }}
                                            </p>
                                        @endif
                                    </footer>
                                @endif
                            </blockquote>
                        @endif
                    </div>
                @elseif (filled($highlights ?? null) && is_array($highlights))
                    <ol class="border-t border-primary" aria-label="Datos destacados">
                        @foreach ($highlights as $highlight)
                            <li class="flex items-baseline gap-6 border-b border-gray-2 py-6">
                                @if (filled($highlight['number'] ?? null))
                                    <p class="font-[var(--font-editorial)] text-[clamp(2rem,4vw,3.5rem)] leading-none text-primary">
                                        {{ $highlight['number'] }}
                                    </p>
                                @endif
                                @if (filled($highlight['label'] ?? null))
                                    <p class="max-w-[24ch] font-body text-base leading-snug text-gray">
                                        {{ $highlight['label'] }}
                                    </p>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>

            <div class="order-1 lg:order-2 lg:col-span-7 lg:col-start-6">
                @if ($tag ?? null)
                    <p class="mb-4 font-[var(--font-editorial)] text-2xl text-gray">{{ $tag }}</p>
                @endif
                @if ($title ?? null)
                    <{{ $headingTag }} class="mt-16 max-w-[12ch] font-sans text-[clamp(3rem,6.5vw,6rem)] font-normal leading-[0.94] tracking-[-0.035em] text-primary sm:mt-24 lg:mt-32">
                        {{ $title }}
                    </{{ $headingTag }}>
                @endif

                <span class="my-8 block h-px w-24 bg-accent" aria-hidden="true"></span>

                @if ($bioHtml)
                    <div class="max-w-[62ch] font-[var(--font-editorial)] text-xl leading-[1.65] text-gray [&_p]:mb-5 [&_p:last-child]:mb-0 [&_a]:text-primary [&_a]:underline [&_strong]:font-bold">
                        {!! $bioHtml !!}
                    </div>
                @endif

                @if ($bioUrl && ($cta_label ?? null))
                    <x-link :attrs="$bioRoute" class="group mt-8 inline-flex items-center gap-2 border-b border-primary pb-0.5 font-body text-base text-primary transition-colors duration-300 hover:border-accent hover:text-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                        {{ $cta_label }}
                        <span class="transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">→</span>
                    </x-link>
                @endif
            </div>
        </div>
    </div>
</x-block>
