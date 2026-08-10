@php
    $menu = App\Models\Menu::query()->where('slug', 'header')->first();
    $routeIds = collect($menu?->items ?? [])
        ->flatMap(function (array $item): array {
            return array_merge(
                [data_get($item, 'route.route_id')],
                collect($item['children'] ?? [])->pluck('route.route_id')->all(),
            );
        })
        ->filter()
        ->map(fn ($id): int => (int) $id)
        ->unique();
    $menuRoutes = App\Models\Route::query()->whereIn('id', $routeIds)->get()->keyBy('id');
    $hydrateItem = function (array $item) use ($menuRoutes): ?array {
        $attrs = is_array($item['route'] ?? null) ? $item['route'] : [];
        $linkedRoute = filled($attrs['route_id'] ?? null) ? $menuRoutes->get((int) $attrs['route_id']) : null;
        if (! $linkedRoute && blank($attrs['external_url'] ?? null)) {
            return null;
        }

        return [
            'token' => $item['_token'] ?? null,
            'label' => $item['label'] ?? $linkedRoute?->title ?? 'Enlace',
            'route' => $linkedRoute,
            'attrs' => $attrs,
            'children' => collect($item['children'] ?? [])->map(function (array $child) use ($menuRoutes): ?array {
                $childAttrs = is_array($child['route'] ?? null) ? $child['route'] : [];
                $childRoute = filled($childAttrs['route_id'] ?? null) ? $menuRoutes->get((int) $childAttrs['route_id']) : null;

                return ($childRoute || filled($childAttrs['external_url'] ?? null)) ? [
                    'token' => $child['_token'] ?? null,
                    'label' => $child['label'] ?? $childRoute?->title ?? 'Enlace',
                    'route' => $childRoute,
                    'attrs' => $childAttrs,
                ] : null;
            })->filter()->values(),
        ];
    };
    $navigation = collect($menu?->items ?? [])->sortBy('order')->map($hydrateItem)->filter()->values();
    $featuredNavigation = $navigation
        ->whereIn('token', ['menu-sobre-mi', 'menu-publicaciones', 'menu-actividad'])
        ->values();
    $cvNavigation = $navigation->firstWhere('token', 'menu-cv');
    $siteMap = $navigation->reject(fn (array $item): bool => $item['token'] === 'menu-home')->values();

    $latestBooks = App\Models\Libro::query()
        ->with('route')
        ->isPublished()
        ->orderByDesc('fecha_publicacion')
        ->orderByDesc('id')
        ->limit(3)
        ->get();

    $latestBlog = App\Models\Blog::query()
        ->with('route')
        ->isPublished()
        ->orderByDesc('published_at')
        ->orderByDesc('id')
        ->first();

    $latestMedia = App\Models\PublicacionMedio::query()
        ->with('route')
        ->isPublished()
        ->orderByDesc('fecha')
        ->orderByDesc('id')
        ->first();

    $latestConference = App\Models\Conferencia::query()
        ->with('route')
        ->isPublished()
        ->orderByDesc('fecha')
        ->orderByDesc('id')
        ->first();

    $liveItems = collect([
        ['label' => 'Novedad', 'record' => $latestBlog, 'date' => $latestBlog?->published_at],
        ['label' => 'En medios', 'record' => $latestMedia, 'date' => $latestMedia?->fecha],
        ['label' => 'Jornada', 'record' => $latestConference, 'date' => $latestConference?->fecha],
    ])->filter(fn (array $item): bool => $item['record']?->route instanceof App\Models\Route)->values();

    $firstBlock = data_get($route ?? null, 'routable.blocks.0');
    $heroFused = is_array($firstBlock)
        && ($firstBlock['type'] ?? null) === 'Hero'
        && data_get($firstBlock, 'data.variant') === 'editorial';
@endphp

<header
    id="mainHeader"
    class="fixed inset-x-0 top-0 z-50"
    @mouseenter="window.matchMedia('(min-width: 1280px)').matches && openMenu(false)"
    @mouseleave="menuCloseTimer = window.setTimeout(() => closeMenu(), 250)"
    :class="fused && overHero ? 'bg-transparent text-primary' : 'bg-primary text-white'"
    x-data="{
        menuOpen: false,
        menuCloseTimer: null,
        scrolled: false,
        logoReady: false,
        logoRevealed: false,
        fused: @js($heroFused),
        overHero: @js($heroFused),
        heroEl: null,
        init() {
            this.scrolled = window.scrollY > 24;
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 24;
                if (this.fused) this.updateOverHero();
            }, { passive: true });

            const logoImg = this.$refs.headerLogo;
            if (logoImg) {
                if (logoImg.complete && logoImg.naturalWidth > 0) {
                    this.logoReady = true;
                } else {
                    logoImg.addEventListener('load', () => { this.logoReady = true; }, { once: true });
                }
            }

            if (this.fused) {
                const detectHero = () => {
                    this.heroEl = document.querySelector('#main .block-Hero [data-hero-variant]');
                    this.logoRevealed = false;
                    this.updateOverHero();
                };
                detectHero();
                document.addEventListener('livewire:navigated', detectHero);
            }
        },
        updateOverHero() {
            const headerHeight = document.getElementById('mainHeader')?.offsetHeight ?? 56;
            this.overHero = window.scrollY <= 0;
            if (!this.heroEl) return;
            const heroLogo = this.heroEl.querySelector('.hero-logo');
            if (heroLogo) {
                if (heroLogo.getBoundingClientRect().bottom <= headerHeight) {
                    this.logoRevealed = true;
                }
            } else {
                this.logoRevealed = true;
            }
        },
        openMenu(lockScroll = true) {
            this.menuOpen = true;
            if (!lockScroll) return;
            document.documentElement.style.overflow = 'hidden';
            this.$nextTick(() => this.$refs.closeButton?.focus());
        },
        closeMenu() {
            this.menuOpen = false;
            document.documentElement.style.overflow = '';
            this.$nextTick(() => this.$refs.menuButton?.focus());
        }
    }"
    x-init="init()"
    @keydown.escape.window="menuOpen && closeMenu()"
    wire:ignore
>
    <div
        class="container mx-auto flex h-14 items-center justify-between transition-[height] duration-[400ms] ease-[cubic-bezier(0.22,1,0.36,1)] motion-reduce:transition-none lg:h-16"
        :class="scrolled ? '!h-12 lg:!h-14' : ''"
    >
        <a
            href="/"
            wire:navigate.hover
            class="flex min-h-11 items-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
            aria-label="{{ config_text('site-name', 'Marcela Basterra') }} — Inicio"
        >
            <span class="relative block" x-cloak x-show="logoReady && !overHero && (!fused || logoRevealed)">
                <img
                    x-ref="headerLogo"
                    src="{{ asset('img/Logos/logo-sin-tagline.svg') }}"
                    alt="{{ config_text('site-name', 'Marcela Basterra') }}"
                    class="h-9 w-auto brightness-0 invert lg:h-10"
                >
            </span>
        </a>

        <div class="flex items-center gap-4 lg:gap-8">
            <nav class="hidden items-center gap-6 xl:flex" aria-label="Accesos principales">
                @foreach ($featuredNavigation as $index => $item)
                    <x-link
                        allowWireNavitage
                        class="font-body relative flex min-h-11 items-center text-[15px] leading-none text-current transition-colors duration-200 after:absolute after:inset-x-0 after:bottom-1 after:h-px after:origin-left after:scale-x-0 after:bg-accent after:transition-transform hover:after:scale-x-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent {{ $item['route']->isActive() ? 'after:scale-x-100' : '' }}"
                        :attrs="$item['attrs']"
                    >
                        {{ $item['label'] }}
                    </x-link>
                @endforeach
            </nav>

            @if ($cvNavigation)
                <x-link
                    allowWireNavitage
                    class="font-body inline-flex min-h-11 items-center justify-center border border-current px-4 text-[14px] font-medium text-current transition-colors duration-200 hover:border-accent hover:bg-accent hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                    :attrs="$cvNavigation['attrs']"
                >
                    CV
                </x-link>
            @endif

            <button
                type="button"
                x-ref="menuButton"
                @click="menuOpen ? closeMenu() : openMenu()"
                class="font-body group inline-flex min-h-11 items-center justify-center gap-3 border-0 text-[15px] text-current focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                :aria-expanded="menuOpen.toString()"
                :aria-label="menuOpen ? 'Cerrar menú' : 'Abrir menú Ver más'"
                aria-controls="site-menu-panel"
            >
                <span class="hidden sm:inline">Ver más</span>
                <span class="relative block h-4 w-6" aria-hidden="true">
                    <span class="absolute left-0 top-0 h-px w-6 bg-current transition-transform duration-200 motion-reduce:transition-none" :class="menuOpen ? 'translate-y-[7px] rotate-45' : ''"></span>
                    <span class="absolute left-0 top-[7px] h-px w-6 bg-current transition-opacity duration-200 motion-reduce:transition-none" :class="menuOpen ? 'opacity-0' : ''"></span>
                    <span class="absolute bottom-0 left-0 h-px w-6 bg-current transition-transform duration-200 motion-reduce:transition-none" :class="menuOpen ? '-translate-y-[8px] -rotate-45' : ''"></span>
                </span>
            </button>
        </div>
    </div>

    <div
        id="site-menu-panel"
        x-show="menuOpen"
        x-cloak
        x-transition:enter="transition duration-[400ms] ease-[cubic-bezier(0.22,1,0.36,1)] motion-reduce:transition-none"
        x-transition:enter-start="-translate-y-4 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition duration-[250ms] ease-in motion-reduce:transition-none"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="-translate-y-2 opacity-0"
        class="archive-menu-panel fixed inset-x-0 overflow-y-auto border-y border-primary/30 bg-gray-3"
        :class="scrolled ? 'top-12 lg:top-14' : 'top-14 lg:top-16'"
        role="dialog"
        aria-modal="true"
        aria-labelledby="site-menu-title"
        @click.self="closeMenu()"
    >
        <div class="container mx-auto grid grid-cols-1 gap-8 py-5 sm:py-6 lg:grid-cols-12 lg:gap-8 lg:py-7">
            <aside class="border-b border-primary/30 pb-6 lg:col-span-3 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-8">
                <button
                    type="button"
                    x-ref="closeButton"
                    @click="closeMenu()"
                    class="font-body mb-4 inline-flex min-h-11 items-center gap-3 text-[14px] text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent lg:hidden"
                >
                    Cerrar <span aria-hidden="true">×</span>
                </button>

                <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="font-source text-[13px] text-gray">Archivo vivo</p>
                    <h2 id="site-menu-title" class="mt-1 font-sans text-[1.65rem] font-normal leading-none text-primary">Últimos libros</h2>
                </div>
                @if ($latestBooks->isNotEmpty() && $latestBooks->first()->fecha_publicacion)
                    <span class="font-source text-sm text-gray">{{ $latestBooks->first()->fecha_publicacion->year }}</span>
                @endif
            </div>

            @if ($latestBooks->isNotEmpty())
                <ul class="mt-5 divide-y divide-primary/40 border-y border-primary/40">
                    @foreach ($latestBooks as $book)
                        <li>
                            <a
                                href="{{ $book->route->url }}"
                                wire:navigate.hover
                                class="group grid min-h-[5.25rem] grid-cols-[4.5rem_1fr_auto] items-center gap-4 py-3 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                            >
                                <div class="flex aspect-[4/5] items-center justify-center border border-primary bg-white p-1">
                                    @if ($book->portada)
                                        <img src="{{ Storage::url($book->portada) }}" alt="Portada de {{ $book->title }}" class="h-full w-full object-contain transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-[1.03] motion-reduce:transition-none">
                                    @else
                                        <span class="flex h-full w-full flex-col justify-between bg-primary p-2 text-white">
                                            <span class="font-source text-[8px] text-gray-3">Obra reciente</span>
                                            <span class="font-source text-lg leading-none">MB</span>
                                            <span class="h-px w-4 bg-accent" aria-hidden="true"></span>
                                        </span>
                                    @endif
                                </div>
                                <span class="min-w-0 self-center">
                                    <span class="line-clamp-3 block font-sans text-[15px] font-bold leading-[1.2] text-primary transition-colors group-hover:text-accent">{{ $book->title }}</span>
                                    @if ($book->fecha_publicacion)
                                        <span class="mt-1.5 block font-source text-[12px] text-gray">{{ $book->fecha_publicacion->year }}</span>
                                    @endif
                                </span>
                                <span class="font-body text-[13px] text-primary transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">→</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </aside>

            <nav class="lg:col-span-5 lg:px-1" aria-label="Mapa del sitio">
                <p class="mb-3 font-source text-[13px] text-gray">Explorar</p>
                <ul class="grid grid-cols-1 gap-x-7 gap-y-2 sm:grid-cols-2">
                    @foreach ($siteMap as $index => $section)
                        <li class="archive-menu-item border-t border-primary/30 pt-2" style="--menu-index: {{ $index }}">
                            <x-link
                                allowWireNavitage
                                class="font-sans inline-flex min-h-9 items-center text-[19px] leading-tight text-primary transition-colors duration-200 hover:text-gray focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent {{ $section['route']?->isActive() ? 'font-bold' : '' }}"
                                :attrs="$section['attrs']"
                            >
                                {{ $section['label'] }}
                            </x-link>

                            @if ($section['children']->isNotEmpty())
                                <ul class="space-y-0">
                                    @foreach ($section['children'] as $child)
                                        <li>
                                            <x-link
                                                allowWireNavitage
                                                class="font-body inline-flex min-h-8 items-center text-[13px] leading-snug text-gray transition-colors duration-200 hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                                :attrs="$child['attrs']"
                                            >
                                                {{ $child['label'] }} <span class="ml-1.5" aria-hidden="true">→</span>
                                            </x-link>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>

            @if ($liveItems->isNotEmpty())
                <section class="border-t border-primary/30 pt-5 lg:col-span-4 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0" aria-labelledby="live-items-title">
                    <div class="flex items-baseline justify-between gap-4">
                        <h2 id="live-items-title" class="font-source text-[13px] text-gray">En foco</h2>
                        <span class="font-body text-[11px] text-gray" aria-hidden="true">Reciente</span>
                    </div>
                    <ul class="mt-3 divide-y divide-primary/30 border-y border-primary/30">
                        @foreach ($liveItems as $index => $liveItem)
                            @php
                                $record = $liveItem['record'];
                                $recordRoute = $record->route;
                            @endphp
                            <li>
                                <a
                                    href="{{ $recordRoute->url }}"
                                    wire:navigate.hover
                                    class="group grid min-h-[5.25rem] grid-cols-[4.5rem_1fr_auto] items-center gap-3 py-3 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                >
                                    <span class="font-source text-[12px] text-primary">{{ $liveItem['label'] }}</span>
                                    <span class="line-clamp-2 font-sans text-[16px] leading-[1.15] text-primary transition-colors group-hover:text-accent">{{ $recordRoute->title }}</span>
                                    <span class="font-body text-[12px] text-primary transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">↗</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </div>
</header>
