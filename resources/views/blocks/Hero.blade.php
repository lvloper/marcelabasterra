@php
$id = ($id ?? 'hero-' . uniqid()) . '-content';
$heroVariant = in_array($variant ?? 'editorial', ['editorial', 'institutional', 'portrait'], true)
    ? ($variant ?? 'editorial')
    : 'editorial';

$profilePhoto = null;
$profilePhotoValue = is_array($profile_photo ?? null) ? ($profile_photo[0] ?? null) : ($profile_photo ?? null);
if ($profilePhotoValue) {
    $profilePhoto = \Illuminate\Support\Facades\Storage::url($profilePhotoValue);
}

$heroBadge = $badge ?? '';
$heroName = $name ?? '';
$heroSubtitle = $subtitle ?? '';
$heroDescription = $description ?? '';
$heroImageAlt = $image_alt ?? '';
$heroIndicators = array_values(array_filter($indicators ?? [], fn ($item) => filled($item['label'] ?? null)));
$positionIds = collect($featured_positions ?? [])->map(fn ($id) => (int) $id);
$heroPositions = \App\Models\CargoInstitucional::query()->whereIn('id', $positionIds)->get()
    ->sortBy(fn ($position) => $positionIds->search($position->id))->values();

$parseIndicator = function ($label) {
    $label = trim($label);
    if (preg_match('/^([\d\+]+\+?)\s+(.+)$/', $label, $matches)) {
        return ['number' => $matches[1], 'text' => trim($matches[2])];
    }
    return ['number' => '', 'text' => $label];
};

$indicatorIcon = function ($text) {
    $text = mb_strtolower($text);
    if (str_contains($text, 'libro')) return 'book-open';
    if (str_contains($text, 'nota') || str_contains($text, 'prensa') || str_contains($text, 'artí') || str_contains($text, 'articulo')) return 'newspaper';
    if (str_contains($text, 'año')) return 'calendar';
    if (str_contains($text, 'confer')) return 'mic';
    if (str_contains($text, 'univ')) return 'graduation-cap';
    if (str_contains($text, 'public') || str_contains($text, 'revista')) return 'file-text';
    return 'star';
};

$resolveRoute = function ($data) {
    if (! is_array($data) || ! $data) return null;
    $url = null;
    $label = $data['btn_label'] ?? '';
    $newWindow = false;
    $download = null;
    if ((string) ($data['route_id'] ?? '') === '-1' && ($data['file'] ?? null)) {
        $file = is_array($data['file']) ? ($data['file'][0] ?? null) : $data['file'];
        $url = $file ? \Illuminate\Support\Facades\Storage::url($file) : null;
        $download = $data['download_name'] ?? basename((string) $file);
    } elseif ((string) ($data['route_id'] ?? '') === '0' && ($data['external_url'] ?? null)) {
        $url = $data['external_url'];
        $newWindow = $data['new_window'] ?? false;
    } elseif ($data['route_id'] ?? null) {
        $rt = \App\Models\Route::find($data['route_id']);
        if ($rt) {
            $url = url($rt->full_slug);
            if ($data['anchor'] ?? null) {
                $url .= '#' . $data['anchor'];
            }
            $newWindow = $data['new_window'] ?? false;
        }
    }
    return $url && $label ? compact('url', 'label', 'newWindow', 'download') : null;
};

$ctaPrimary = $resolveRoute($cta_primary ?? null);
$ctaSecondary = $resolveRoute($cta_secondary ?? null);
$ctaTertiary = $resolveRoute($cta_tertiary ?? null);
@endphp

@php
    $primaryButton = 'inline-flex min-h-12 items-center justify-center gap-3 border border-primary bg-primary px-6 py-3 font-body text-base font-medium text-white transition-colors duration-300 hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent';
    $secondaryButton = 'inline-flex min-h-12 items-center justify-center gap-3 border border-primary bg-transparent px-6 py-3 font-body text-base font-medium text-primary transition-colors duration-300 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent';
    $inverseButton = 'inline-flex min-h-12 items-center justify-center gap-3 border border-white bg-white px-6 py-3 font-body text-base font-medium text-primary transition-colors duration-300 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent';
@endphp

<x-block class="block-Hero overflow-hidden">
    <div id="{{ $id }}" data-hero-variant="{{ $heroVariant }}">
        @if ($heroVariant === 'editorial')
            <div class="relative bg-white">
                <div class="mx-auto grid max-w-[1440px] grid-cols-1 lg:grid-cols-12">
                    <div class="hero-fused-top order-1 flex flex-col justify-center bg-white px-5 py-16 pt-16 sm:px-8 lg:col-span-7 lg:bg-transparent lg:px-12 lg:py-24 lg:pt-20 xl:px-16">
                        @if ($heroBadge)
                            <p class="hero-reveal mb-10 flex items-center gap-4 font-source text-sm text-primary">
                                <span class="h-px w-12 bg-accent" aria-hidden="true"></span>
                                {{ $heroBadge }}
                            </p>
                        @endif

                        @if ($heroName)
                            <div class="mb-12 hero-logo">
                                <img src="{{ asset('img/Logos/logo-sin-tagline.svg') }}" alt="{{ $heroName }}" class="h-16 w-auto sm:h-20 lg:h-28">
                            </div>
                        @endif

                        <div class="hero-reveal">
                            @if ($heroSubtitle)
                                <p class="max-w-3xl font-source text-[clamp(1.35rem,2vw,1.8rem)] leading-tight text-primary">{{ $heroSubtitle }}</p>
                            @endif
                            @if ($heroDescription)
                                <p class="mt-5 max-w-3xl font-body text-base leading-relaxed text-gray">{{ $heroDescription }}</p>
                            @endif
                        </div>

                        @if ($heroPositions->isNotEmpty())
                            <div class="hero-reveal mt-12 border-t border-primary pt-8">
                                <ul class="grid gap-x-8 gap-y-6">
                                    @foreach ($heroPositions as $position)
                                        <li>
                                            @if ($position->institutional_url)
                                                <a href="{{ $position->institutional_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between gap-4 font-sans text-xl leading-tight text-primary underline-offset-4 hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                                    <span>{{ $position->cargo }}</span>
                                                    <x-lucide-square-arrow-out-up-right class="h-5 w-5 shrink-0 text-accent" aria-hidden="true" />
                                                </a>
                                            @else
                                                <p class="font-sans text-xl leading-tight text-primary">{{ $position->cargo }}</p>
                                            @endif
                                            <p class="mt-1 font-source text-base leading-snug text-gray">{{ $position->institucion }}</p>
                                            @if ($position->fecha_fin === null && $position->fecha_inicio)
                                                <p class="mt-1 font-body text-sm text-gray">Vigente desde {{ $position->fecha_inicio->year }}</p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($ctaPrimary || $ctaSecondary || $ctaTertiary)
                            <div class="hero-reveal mt-12 flex flex-wrap items-center gap-4">
                                @if ($ctaPrimary)
                                    <a href="{{ $ctaPrimary['url'] }}" @if ($ctaPrimary['newWindow']) target="_blank" rel="noopener noreferrer" @endif @if ($ctaPrimary['download']) download="{{ $ctaPrimary['download'] }}" @endif class="{{ $primaryButton }} group">
                                        {{ $ctaPrimary['label'] }} <span class="transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">→</span>
                                    </a>
                                @endif
                                @if ($ctaSecondary)
                                    <a href="{{ $ctaSecondary['url'] }}" @if ($ctaSecondary['newWindow']) target="_blank" rel="noopener noreferrer" @endif @if ($ctaSecondary['download']) download="{{ $ctaSecondary['download'] }}" @endif class="{{ $secondaryButton }} group">
                                        {{ $ctaSecondary['label'] }} <span class="transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">→</span>
                                    </a>
                                @endif
                                @if ($ctaTertiary)
                                    <a href="{{ $ctaTertiary['url'] }}" @if ($ctaTertiary['newWindow']) target="_blank" rel="noopener noreferrer" @endif @if ($ctaTertiary['download']) download="{{ $ctaTertiary['download'] }}" @endif class="group inline-flex min-h-12 items-center gap-3 border-b border-primary font-body text-base text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                        {{ $ctaTertiary['label'] }} <span class="transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">→</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="relative order-2 min-h-[24rem] border-t border-gray-2 bg-white lg:col-span-5 lg:border-t-0 lg:bg-white lg:min-h-auto">
                        @if ($profilePhoto)
                            <div class="hero-image-mask h-full min-h-[28rem] overflow-hidden">
                                <img src="{{ $profilePhoto }}" alt="{{ $heroImageAlt }}" class="hero-photo h-full w-full object-cover object-center" fetchpriority="high">
                            </div>
                        @else
                            <div class="flex min-h-28 items-end p-6 lg:min-h-[28rem] lg:p-12" aria-hidden="true">
                                <span class="font-source text-7xl text-primary/20">MB</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="pointer-events-none absolute right-0 top-0 hidden h-full w-40 hero-dot-pattern lg:block" aria-hidden="true"></div>
            </div>

        @elseif ($heroVariant === 'institutional')
            <div class="bg-primary text-white">
                <div class="mx-auto grid min-h-[calc(100svh-5rem)] max-w-[1440px] grid-cols-1 lg:grid-cols-12">
                    <div class="flex flex-col px-5 py-16 sm:px-8 lg:col-span-8 lg:justify-between lg:px-12 lg:py-20 xl:px-16">
                        <div>
                            @if ($heroBadge)
                                <p class="hero-reveal mb-12 flex items-center gap-4 font-source text-sm text-white">
                                    <span class="h-px w-12 bg-accent" aria-hidden="true"></span>{{ $heroBadge }}
                                </p>
                            @endif
                            @if ($heroName)
                                <h1 class="hero-reveal max-w-[12ch] font-sans text-[clamp(3.25rem,7.5vw,7rem)] font-normal leading-[0.92] tracking-[-0.035em] text-white">{{ $heroName }}</h1>
                            @endif
                        </div>

                        <div class="hero-reveal mt-16 grid gap-8 border-t border-white/30 pt-8 md:grid-cols-2">
                            <div>
                                @if ($heroSubtitle)
                                    <p class="max-w-md font-source text-[clamp(1.4rem,2.4vw,2rem)] leading-tight text-white">{{ $heroSubtitle }}</p>
                                @endif
                                @if ($heroDescription)
                                    <p class="mt-5 max-w-lg font-body text-base leading-relaxed text-gray-3">{{ $heroDescription }}</p>
                                @endif
                            </div>
                            @if ($ctaPrimary || $ctaSecondary || $ctaTertiary)
                                <div class="flex flex-col items-start gap-3 md:items-end">
                                    @foreach ([$ctaPrimary, $ctaSecondary, $ctaTertiary] as $index => $cta)
                                        @if ($cta)
                                            <a href="{{ $cta['url'] }}" @if ($cta['newWindow']) target="_blank" rel="noopener noreferrer" @endif @if ($cta['download']) download="{{ $cta['download'] }}" @endif class="{{ $index === 0 ? $inverseButton : 'group inline-flex min-h-12 items-center gap-3 border-b border-white font-body text-base text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent' }} group">
                                                {{ $cta['label'] }} <span class="transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">→</span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-white/30 lg:col-span-4 lg:border-l lg:border-t-0">
                        @if ($profilePhoto)
                            <div class="hero-image-mask h-full min-h-[30rem] overflow-hidden">
                                <img src="{{ $profilePhoto }}" alt="{{ $heroImageAlt }}" class="hero-photo h-full w-full object-cover" fetchpriority="high">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        @else
            <div class="bg-[var(--color-surface-ivory)]">
                <div class="mx-auto max-w-[1440px] px-5 py-12 sm:px-8 lg:px-12 lg:py-20 xl:px-16">
                    @if ($heroBadge)
                        <p class="hero-reveal mb-8 border-b border-primary pb-4 font-source text-sm text-primary">{{ $heroBadge }}</p>
                    @endif
                    <div class="grid grid-cols-1 gap-0 lg:grid-cols-12">
                        <div class="order-2 bg-white p-6 sm:p-10 lg:order-1 lg:col-span-5 lg:flex lg:flex-col lg:justify-between lg:p-12">
                            <div>
                                @if ($heroName)
                                    <h1 class="hero-reveal font-sans text-[clamp(3rem,5.6vw,5.75rem)] font-normal leading-[0.95] tracking-[-0.035em] text-primary">{{ $heroName }}</h1>
                                @endif
                                @if ($heroSubtitle)
                                    <p class="hero-reveal mt-8 border-l border-accent pl-5 font-source text-[clamp(1.35rem,2.1vw,1.8rem)] leading-tight text-primary">{{ $heroSubtitle }}</p>
                                @endif
                                @if ($heroDescription)
                                    <p class="hero-reveal mt-6 max-w-[52ch] font-body text-base leading-relaxed text-gray">{{ $heroDescription }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="hero-image-mask order-1 min-h-[28rem] overflow-hidden bg-gray-3 lg:order-2 lg:col-span-7 lg:min-h-[42rem]">
                            @if ($profilePhoto)
                                <img src="{{ $profilePhoto }}" alt="{{ $heroImageAlt }}" class="hero-photo h-full w-full object-cover object-top" fetchpriority="high">
                            @endif
                        </div>
                    </div>

                    @if ($ctaPrimary || $ctaSecondary || $ctaTertiary)
                        <div class="hero-reveal flex flex-wrap items-center gap-4 border-t border-primary bg-white px-6 py-6 sm:px-10 lg:px-12">
                            @foreach ([$ctaPrimary, $ctaSecondary, $ctaTertiary] as $index => $cta)
                                @if ($cta)
                                    <a href="{{ $cta['url'] }}" @if ($cta['newWindow']) target="_blank" rel="noopener noreferrer" @endif @if ($cta['download']) download="{{ $cta['download'] }}" @endif class="{{ $index === 0 ? $primaryButton : $secondaryButton }} group">
                                        {{ $cta['label'] }} <span class="transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">→</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if (! empty($heroIndicators))
            <div class="bg-gradient-to-b from-gray-3/40 to-transparent">
                <div class="mx-auto max-w-[1440px] px-5 py-14 sm:px-8 lg:px-12 lg:py-20 xl:px-16">
                    <div class="hero-reveal grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($heroIndicators as $item)
                            @php
                                $parsed = $parseIndicator($item['label']);
                                $iconName = $indicatorIcon($parsed['text']);
                            @endphp
                            <div class="group relative overflow-hidden border border-gray-2 bg-white p-6 transition-all duration-300 hover:-translate-y-1 hover:border-primary/20 hover:shadow-lg sm:p-8">
                                <div class="mb-4">
                                    <x-dynamic-component :component="'lucide-' . $iconName" class="h-5 w-5 text-accent" aria-hidden="true" />
                                </div>
                                @if ($parsed['number'])
                                    <p class="font-source text-[clamp(2.25rem,3vw,3.25rem)] font-semibold leading-none text-primary">{{ $parsed['number'] }}</p>
                                @endif
                                <p class="font-body text-sm leading-snug text-gray @if (!$parsed['number']) mt-0 @else mt-2 @endif">{{ $parsed['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if ($heroVariant !== 'editorial' && $heroPositions->isNotEmpty())
        <section class="border-t border-primary bg-white" aria-label="Cargos institucionales destacados">
            <div class="mx-auto grid max-w-[1440px] lg:grid-cols-12">
                <p class="px-5 py-7 font-source text-sm text-primary sm:px-8 lg:col-span-3 lg:px-12 xl:px-16">Cargos destacados</p>
                <ul class="border-t border-gray-2 lg:col-span-9 lg:border-l lg:border-t-0">
                    @foreach ($heroPositions as $position)
                        <li class="grid gap-4 border-b border-gray-2 px-5 py-7 last:border-b-0 sm:px-8 md:grid-cols-[1fr_auto] lg:px-12">
                            <div>
                                <p class="font-sans text-2xl leading-tight text-primary">{{ $position->cargo }}</p>
                                <p class="mt-2 font-source text-lg leading-snug text-gray">{{ $position->institucion }}</p>
                                @if ($position->fecha_fin === null && $position->fecha_inicio)<p class="mt-2 font-body text-sm text-gray">Vigente desde {{ $position->fecha_inicio->year }}</p>@endif
                            </div>
                            @if ($position->institutional_url)<a href="{{ $position->institutional_url }}" target="_blank" rel="noopener noreferrer" class="self-end border-b border-primary font-body text-sm text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">Fuente ↗</a>@endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @pushOnce('scripts', 'block-hero')
        <style>
            .block-Hero .hero-dot-pattern { background-image: radial-gradient(circle, rgba(90,107,115,0.15) 1.5px, transparent 1.5px); background-size: 28px 28px; }
            .blocks-container > .block-Hero:first-child [data-hero-variant="editorial"] .hero-fused-top { padding-top: 0; }
            .block-Hero .hero-reveal,
            .block-Hero .hero-image-mask { opacity: 0; transform: translateY(20px); }
            .block-Hero .hero-image-mask { transform: translateY(0); clip-path: inset(100% 0 0 0); }
            @media (prefers-reduced-motion: reduce) {
                .block-Hero .hero-reveal,
                .block-Hero .hero-image-mask { opacity: 1 !important; transform: none !important; clip-path: none !important; }
                .block-Hero .hero-photo { transition: none !important; }
            }
        </style>
    @endPushOnce

    <script>
        (() => {
            const initHero = () => {
                const block = document.getElementById(@js($id));
                if (!block || block.dataset.heroAnimated === 'true') return;
                block.dataset.heroAnimated = 'true';

                const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const reveals = block.querySelectorAll('.hero-reveal');
                const masks = block.querySelectorAll('.hero-image-mask');

                if (reduceMotion || typeof window.gsap === 'undefined') {
                    reveals.forEach((element) => element.style.cssText += ';opacity:1;transform:none');
                    masks.forEach((element) => element.style.cssText += ';opacity:1;transform:none;clip-path:none');
                    return;
                }

                if (reveals.length > 0) {
                    window.gsap.to(reveals, { opacity: 1, y: 0, duration: 0.8, stagger: 0.09, ease: 'power3.out', delay: 0.1 });
                }
                if (masks.length > 0) {
                    window.gsap.to(masks, { opacity: 1, clipPath: 'inset(0% 0 0 0)', duration: 1.1, ease: 'power3.inOut', delay: 0.15 });
                }

                const heroLogo = block.querySelector('.hero-logo');
                if (heroLogo) {
                    const onScroll = () => {
                        if (!document.body.contains(heroLogo)) {
                            window.removeEventListener('scroll', onScroll);
                            return;
                        }
                        const logoRect = heroLogo.getBoundingClientRect();
                        const logoBottom = logoRect.bottom;
                        const startFadeAt = 120;
                        const endFadeAt = 40;
                        const progress = Math.max(0, Math.min(1, (startFadeAt - logoBottom) / (startFadeAt - endFadeAt)));
                        heroLogo.style.opacity = '' + (1 - progress);
                        heroLogo.style.transform = `translateY(${-progress * 40}px) scale(${1 - progress * 0.3})`;
                    };
                    window.addEventListener('scroll', onScroll, { passive: true });
                    onScroll();
                }
            };

            document.readyState === 'loading'
                ? document.addEventListener('DOMContentLoaded', initHero, { once: true })
                : initHero();
            document.addEventListener('livewire:navigated', initHero);
        })();
    </script>
</x-block>
