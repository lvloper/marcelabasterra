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

<x-block class="block-Hero overflow-hidden bg-white">
    <div id="{{ $id }}" data-hero-variant="{{ $heroVariant }}">
        @if ($heroVariant === 'editorial')
            <div class="mx-auto grid max-w-[1440px] grid-cols-1 lg:min-h-[calc(100svh-5rem)] lg:grid-cols-12">
                <div class="order-1 flex flex-col justify-center px-5 py-16 sm:px-8 lg:col-span-7 lg:px-12 lg:py-24 xl:px-16">
                    @if ($heroBadge)
                        <p class="hero-reveal mb-8 flex items-center gap-4 font-source text-sm text-primary">
                            <span class="h-px w-12 bg-accent" aria-hidden="true"></span>
                            {{ $heroBadge }}
                        </p>
                    @endif

                    @if ($heroName)
                        <h1 class="hero-reveal max-w-[11ch] font-sans text-[clamp(3.25rem,7vw,7rem)] font-normal leading-[0.94] tracking-[-0.035em] text-primary">
                            {{ $heroName }}
                        </h1>
                    @endif

                    <div class="hero-reveal mt-8 grid gap-6 border-t border-gray-2 pt-6 md:grid-cols-2 lg:max-w-3xl">
                        @if ($heroSubtitle)
                            <p class="font-source text-[clamp(1.35rem,2vw,1.8rem)] leading-tight text-primary">{{ $heroSubtitle }}</p>
                        @endif
                        @if ($heroDescription)
                            <p class="font-body text-base leading-relaxed text-gray">{{ $heroDescription }}</p>
                        @endif
                    </div>

                    @if (! empty($heroIndicators))
                        <ul class="hero-reveal mt-8 flex flex-wrap gap-x-8 gap-y-3">
                            @foreach ($heroIndicators as $item)
                                <li class="font-body text-sm text-gray"><span class="mr-2 text-accent" aria-hidden="true">—</span>{{ $item['label'] }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($ctaPrimary || $ctaSecondary || $ctaTertiary)
                        <div class="hero-reveal mt-10 flex flex-wrap items-center gap-4">
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

                <div class="order-2 border-t border-gray-2 bg-[var(--color-surface-ivory)] lg:col-span-5 lg:border-l lg:border-t-0">
                    @if ($profilePhoto)
                        <div class="hero-image-mask h-full min-h-[28rem] overflow-hidden">
                            <img src="{{ $profilePhoto }}" alt="{{ $heroImageAlt }}" class="hero-photo h-full w-full object-cover object-center transition-transform duration-700 hover:scale-[1.025]" fetchpriority="high">
                        </div>
                    @else
                        <div class="flex min-h-28 items-end p-6 lg:min-h-[28rem] lg:p-12" aria-hidden="true">
                            <span class="font-source text-7xl text-primary/20">MB</span>
                        </div>
                    @endif
                </div>
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
                                @if (! empty($heroIndicators))
                                    <ul class="mt-7 grid gap-3">
                                        @foreach ($heroIndicators as $item)
                                            <li class="border-t border-white/30 pt-3 font-body text-sm text-gray-3">{{ $item['label'] }}</li>
                                        @endforeach
                                    </ul>
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
                                <img src="{{ $profilePhoto }}" alt="{{ $heroImageAlt }}" class="hero-photo h-full w-full object-cover transition-transform duration-700 hover:scale-[1.025]" fetchpriority="high">
                            </div>
                        @elseif (! empty($heroIndicators))
                            <ul class="flex h-full min-h-[24rem] flex-col justify-end gap-0 p-8 lg:p-10">
                                @foreach ($heroIndicators as $item)
                                    <li class="border-t border-white/30 py-5 font-source text-xl text-white">{{ $item['label'] }}</li>
                                @endforeach
                            </ul>
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
                            @if (! empty($heroIndicators))
                                <ul class="hero-reveal mt-12 grid gap-3 border-t border-gray-2 pt-6 sm:grid-cols-2">
                                    @foreach ($heroIndicators as $item)
                                        <li class="font-body text-sm leading-snug text-gray"><span class="mr-2 text-accent" aria-hidden="true">—</span>{{ $item['label'] }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="hero-image-mask order-1 min-h-[28rem] overflow-hidden bg-gray-3 lg:order-2 lg:col-span-7 lg:min-h-[42rem]">
                            @if ($profilePhoto)
                                <img src="{{ $profilePhoto }}" alt="{{ $heroImageAlt }}" class="hero-photo h-full w-full object-cover object-center transition-transform duration-700 hover:scale-[1.025]" fetchpriority="high">
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
    </div>

    @if ($heroPositions->isNotEmpty())
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
            .block-Hero .hero-reveal,
            .block-Hero .hero-image-mask { opacity: 0; transform: translateY(20px); }
            .block-Hero .hero-image-mask { transform: translateY(0); clip-path: inset(0 0 100% 0); }
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
                    window.gsap.to(masks, { opacity: 1, clipPath: 'inset(0 0 0% 0)', duration: 1.1, ease: 'power3.inOut', delay: 0.15 });
                }
            };

            document.readyState === 'loading'
                ? document.addEventListener('DOMContentLoaded', initHero, { once: true })
                : initHero();
            document.addEventListener('livewire:navigated', initHero);
        })();
    </script>
</x-block>
