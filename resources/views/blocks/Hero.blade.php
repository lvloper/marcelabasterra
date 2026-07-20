@php
<<<<<<< HEAD
$id = $id ?? 'hero-' . uniqid();

$profilePhoto = null;
$profilePhotoValue = is_array($profile_photo ?? null) ? ($profile_photo[0] ?? null) : ($profile_photo ?? null);
if ($profilePhotoValue) {
    $profilePhoto = \Illuminate\Support\Facades\Storage::url($profilePhotoValue);
}

$heroBadge = $badge ?? '';
$heroName = $name ?? '';
$heroSubtitle = $subtitle ?? '';
$heroDescription = $description ?? '';
$heroIndicators = $indicators ?? [];

$resolveRoute = function ($data) {
    if (!isset($data) || !$data) return null;
    $url = null;
    $label = $data['btn_label'] ?? '';
    $newWindow = false;
    if (($data['route_id'] ?? null) === '0' && ($data['external_url'] ?? null)) {
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
    return $url ? compact('url', 'label', 'newWindow') : null;
};

$ctaPrimary = $resolveRoute($cta_primary ?? null);
$ctaSecondary = $resolveRoute($cta_secondary ?? null);
$ctaTertiary = $resolveRoute($cta_tertiary ?? null);
@endphp

<x-block class="h-screen relative overflow-hidden bg-primary">
    {{-- Architectural line pattern --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="arch-lines-{{ $id }}" x="0" y="0" width="120" height="120" patternUnits="userSpaceOnUse">
                    <line x1="0" y1="120" x2="120" y2="120" stroke="#ffffff" stroke-width="0.3" stroke-opacity="0.04" />
                    <line x1="120" y1="0" x2="120" y2="120" stroke="#ffffff" stroke-width="0.3" stroke-opacity="0.04" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#arch-lines-{{ $id }})" />
        </svg>
    </div>

    <div class="relative z-[1] h-full grid grid-cols-1 lg:grid-cols-[45%_55%]">
        {{-- Left Column: Content --}}
        <div class="flex flex-col justify-center px-6 sm:px-10 md:px-16 lg:px-20 py-16 lg:py-0 hero-left">
            @if ($heroBadge)
                <span class="hero-badge inline-flex items-center gap-2 text-[11px] md:text-xs tracking-[0.22em] uppercase text-secondary font-medium mb-6 md:mb-8 font-sans">
                    <span class="w-8 h-[1px] bg-secondary"></span>
                    {{ $heroBadge }}
                </span>
            @endif

            @if ($heroName)
                <h1 class="hero-name font-serif text-4xl sm:text-5xl lg:text-6xl font-normal text-white leading-[1.08] mb-4 md:mb-6 tracking-tight">
                    {!! nl2br($heroName) !!}
                </h1>
            @endif

            @if ($heroSubtitle)
                <p class="hero-subtitle font-sans text-base md:text-lg text-secondary font-medium mb-4 md:mb-5">
                    {{ $heroSubtitle }}
                </p>
            @endif

            @if ($heroDescription)
                <p class="hero-description font-sans text-sm md:text-base text-white leading-relaxed max-w-md mb-6 md:mb-8">
                    {{ $heroDescription }}
                </p>
            @endif

            @if (!empty($heroIndicators))
                <div class="hero-indicators flex flex-wrap items-center gap-x-6 gap-y-3 mb-8 md:mb-10">
                    @foreach ($heroIndicators as $item)
                        <div class="flex items-center gap-2 text-xs md:text-sm text-white/50 font-sans group cursor-default">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary/60 group-hover:bg-secondary transition-colors duration-300 shrink-0"></span>
                            <span class="group-hover:text-white transition-colors duration-300">{{ $item['label'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($ctaPrimary || $ctaSecondary || $ctaTertiary)
                <div class="hero-ctas flex flex-wrap items-center gap-3 md:gap-4">
                    @if ($ctaPrimary)
                        <a
                            href="{{ $ctaPrimary['url'] }}"
                            @if ($ctaPrimary['newWindow']) target="_blank" rel="noopener noreferrer" @endif
                            class="hero-btn inline-flex items-center gap-2 px-7 py-3.5 bg-secondary text-primary text-sm md:text-base font-semibold tracking-wide hover:bg-secondary-hover hover:-translate-y-0.5 transition-all duration-300 font-sans"
                        >
                            <span>{{ $ctaPrimary['label'] }}</span>
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                                <path d="M3.333 8h9.334M10 5.333 12.667 8 10 10.667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    @endif

                    @if ($ctaSecondary)
                        <a
                            href="{{ $ctaSecondary['url'] }}"
                            @if ($ctaSecondary['newWindow']) target="_blank" rel="noopener noreferrer" @endif
                            class="hero-btn inline-flex items-center gap-2 px-7 py-3.5 border border-white/20 text-white text-sm md:text-base font-medium tracking-wide hover:border-white/40 hover:bg-white/5 hover:-translate-y-0.5 transition-all duration-300 font-sans"
                        >
                            <span>{{ $ctaSecondary['label'] }}</span>
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                                <path d="M3.333 8h9.334M10 5.333 12.667 8 10 10.667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    @endif

                    @if ($ctaTertiary)
                        <a
                            href="{{ $ctaTertiary['url'] }}"
                            @if ($ctaTertiary['newWindow']) target="_blank" rel="noopener noreferrer" @endif
                            class="hero-btn inline-flex items-center gap-1.5 text-sm md:text-base text-secondary font-medium tracking-wide hover:text-secondary-hover transition-colors duration-300 font-sans group"
                        >
                            <span>{{ $ctaTertiary['label'] }}</span>
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 transition-transform duration-300 group-hover:translate-x-1">
                                <path d="M3.333 8h9.334M10 5.333 12.667 8 10 10.667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    @endif
                </div>
            @endif
        </div>

        {{-- Right Column: Photo --}}
        <div class="relative h-64 sm:h-80 lg:h-full overflow-hidden hero-photo-col">
            <div class="absolute inset-0 lg:-left-10 lg:right-0">
                @if ($profilePhoto)
                    <img
                        src="{{ $profilePhoto }}"
                        alt="{{ $heroName }}"
                        class="hero-photo w-full h-full object-cover object-center"
                    >
                @else
                    <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                        <span class="text-slate-500 font-sans text-sm">Foto de perfil</span>
                    </div>
                @endif
                {{-- Subtle overlay gradient for depth --}}
                <div class="absolute inset-0 bg-gradient-to-t from-primary/10 via-transparent to-transparent lg:bg-gradient-to-l lg:from-primary/30 lg:via-transparent lg:to-transparent"></div>
            </div>

            {{-- Decorative vertical line --}}
            <div class="absolute right-8 top-1/4 bottom-1/4 w-[1px] bg-secondary/10 hidden lg:block"></div>
        </div>
    </div>

    {{-- Decorative bottom line --}}
    <div class="absolute bottom-0 left-0 right-0 z-[2]">
        <div class="w-full h-[1px] bg-gradient-to-r from-transparent via-secondary/30 to-transparent"></div>
    </div>

    @pushOnce('scripts', 'block-hero')
    <style>
        .hero-left > * {
            opacity: 0;
            transform: translateY(30px);
        }
        .hero-photo {
            opacity: 0;
            transform: scale(1.05);
        }
    </style>
    @endpushOnce

    <script>
        document.addEventListener('livewire:navigated', function() {
            const BLOCK = document.getElementById('{{ $id }}');
            if (!BLOCK) return;
            if (typeof gsap === 'undefined') return;

            const photo = BLOCK.querySelector('.hero-photo');
            if (photo) {
                gsap.to(photo, {
                    scale: 1,
                    opacity: 1,
                    duration: 1.6,
                    ease: 'power3.out',
                    delay: 0.2,
                });
            }

            const children = BLOCK.querySelectorAll('.hero-left > *');
            gsap.to(children, {
                y: 0,
                opacity: 1,
                duration: 1,
                stagger: 0.12,
                ease: 'power3.out',
                delay: 0.4,
            });
        });
    </script>
=======
    $heroUrl = '';
    $heroLabel = $cta_label ?? null;
    $heroRoute = $cta_route ?? [];
    if ($heroLabel && ($heroRoute['route_id'] ?? null)) {
        $r = \App\Models\Route::find($heroRoute['route_id']);
        if ($r) {
            $heroUrl = url($r->full_slug);
            if ($heroRoute['anchor'] ?? null) $heroUrl .= '#' . $heroRoute['anchor'];
        }
    } elseif ($heroLabel && ($heroRoute['external_url'] ?? null)) {
        $heroUrl = $heroRoute['external_url'];
    }
@endphp

<x-block class="relative overflow-hidden py-24 md:py-32 bg-gray-3">
    <div class="absolute inset-0 pointer-events-none z-0">
        <div class="absolute top-0 left-0 w-full h-full bg-grid"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4">
        <div class="flex flex-col {{ ($image ?? null) ? 'md:flex-row gap-12' : '' }} items-center">
            <div class="{{ ($image ?? null) ? 'w-full md:w-3/5' : 'w-full text-center' }}">
                @if ($subtitle ?? null)
                    <p class="text-sm font-semibold tracking-widest uppercase text-secondary mb-4 font-sans">{{ $subtitle }}</p>
                @endif

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-primary leading-[1.08] font-sans">
                    {{ $title ?? '' }}
                </h1>

                @if ($heroUrl)
                    <a href="{{ $heroUrl }}"
                       @if ($heroRoute['new_window'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                       class="inline-flex items-center gap-2 bg-primary text-white px-8 py-4 font-bold rounded-sm mt-10 hover:bg-primary-hover transition-colors group">
                        {{ $heroLabel }}
                        <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                    </a>
                @endif
            </div>

            @if ($image ?? null)
                <div class="w-full md:w-2/5 mt-8 md:mt-0">
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::url($image) }}"
                        alt="{{ $title ?? '' }}"
                        class="w-full h-auto object-cover rounded-2xl"
                        loading="eager"
                    >
                </div>
            @endif
        </div>
    </div>
>>>>>>> 85ac118f44c5d2c1cda44c274bef3d05c175fc3c
</x-block>
