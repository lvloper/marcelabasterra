@php
    $pageRoutes = App\Models\Route::query()
        ->where('routable_type', App\Models\Page::class)
        ->get();

    $routeForPath = function (string $path) use ($pageRoutes): ?App\Models\Route {
        return $pageRoutes->first(fn (App\Models\Route $route): bool => $route->getFullPath() === trim($path, '/'));
    };

    $makeLink = function (string $label, string $path, ?string $anchor = null) use ($routeForPath): ?array {
        $route = $routeForPath($path);

        return $route ? [
            'label' => $label,
            'route' => $route,
            'anchor' => $anchor,
        ] : null;
    };

    $featuredNavigation = collect([
        $makeLink('Trayectoria', 'trayectoria'),
        $makeLink('Publicaciones', 'publicaciones'),
        $makeLink('Actividad académica', 'actividad-academica'),
        $makeLink('Jornadas y Congresos', 'jornadas-y-congresos'),
    ])->filter()->values();

    $siteMap = collect([
        [
            'label' => 'Sobre mí',
            'route' => $routeForPath('sobre-mi'),
            'children' => collect([
                $makeLink('Biografía', 'sobre-mi', 'biografia'),
                $makeLink('Cargos institucionales', 'sobre-mi', 'cargos'),
                $makeLink('CV', 'sobre-mi', 'cv'),
            ])->filter()->values(),
        ],
        [
            'label' => 'Trayectoria',
            'route' => $routeForPath('trayectoria'),
            'children' => collect(),
        ],
        [
            'label' => 'Actividad académica',
            'route' => $routeForPath('actividad-academica'),
            'children' => collect([
                $makeLink('Programas', 'programas'),
                $makeLink('Jornadas y Congresos', 'jornadas-y-congresos'),
            ])->filter()->values(),
        ],
        [
            'label' => 'Publicaciones',
            'route' => $routeForPath('publicaciones'),
            'children' => collect([
                $makeLink('Libros', 'publicaciones/libros'),
                $makeLink('Artículos académicos', 'publicaciones/articulos-academicos'),
                $makeLink('Actualidad y producción académica', 'actualidad-y-produccion-academica'),
            ])->filter()->values(),
        ],
        [
            'label' => 'Novedades',
            'route' => $routeForPath('novedades'),
            'children' => collect(),
        ],
        [
            'label' => 'Actualidad',
            'route' => $routeForPath('actualidad-y-produccion-academica'),
            'children' => collect(),
        ],
        [
            'label' => 'Contacto',
            'route' => $routeForPath('contacto'),
            'children' => collect(),
        ],
    ])->filter(fn (array $item): bool => $item['route'] instanceof App\Models\Route)->values();

    $latestBook = App\Models\Libro::query()
        ->with('route')
        ->isPublished()
        ->orderByDesc('fecha_publicacion')
        ->orderByDesc('id')
        ->first();

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
@endphp

<header
    id="mainHeader"
    class="fixed inset-x-0 top-0 z-50 border-b border-primary/20 bg-white text-primary"
    x-data="{
        menuOpen: false,
        scrolled: false,
        init() {
            this.scrolled = window.scrollY > 24;
            window.addEventListener('scroll', () => this.scrolled = window.scrollY > 24, { passive: true });
        },
        openMenu() {
            this.menuOpen = true;
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
        class="container mx-auto flex h-20 items-center justify-between transition-[height] duration-[400ms] ease-[cubic-bezier(0.22,1,0.36,1)] motion-reduce:transition-none lg:h-24"
        :class="scrolled ? '!h-16' : ''"
    >
        <a
            href="/"
            wire:navigate.hover
            class="flex min-h-11 items-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
            aria-label="{{ config_text('site-name', 'Marcela Basterra') }} — Inicio"
        >
            <span class="relative block h-10 w-44 transition-[width] duration-[400ms] ease-[cubic-bezier(0.22,1,0.36,1)] motion-reduce:transition-none lg:h-12 lg:w-48" :class="scrolled ? '!w-10 lg:!w-12' : ''">
                <img
                    src="{{ asset('img/Logos/logo-sin-tagline.svg') }}"
                    alt="{{ config_text('site-name', 'Marcela Basterra') }}"
                    class="absolute left-0 top-0 h-10 w-auto max-w-none transition-opacity duration-300 motion-reduce:transition-none lg:h-12"
                    :class="scrolled ? 'opacity-0' : 'opacity-100'"
                >
                <img
                    src="{{ asset('img/Logos/monograma.svg') }}"
                    alt=""
                    aria-hidden="true"
                    class="absolute left-0 top-0 h-10 w-10 object-contain transition-opacity duration-300 motion-reduce:transition-none lg:h-12 lg:w-12"
                    :class="scrolled ? 'opacity-100' : 'opacity-0'"
                >
            </span>
        </a>

        <div class="flex items-center gap-4 lg:gap-8">
            <nav class="hidden items-center gap-6 xl:flex" aria-label="Accesos principales">
                @foreach ($featuredNavigation as $index => $item)
                    <x-link
                        allowWireNavitage
                        class="font-body relative flex min-h-11 items-center text-[15px] leading-none text-primary transition-colors duration-200 after:absolute after:inset-x-0 after:bottom-1 after:h-px after:origin-left after:scale-x-0 after:bg-accent after:transition-transform hover:after:scale-x-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent {{ $item['route']->isActive() ? 'after:scale-x-100' : '' }}"
                        :attrs="['route_id' => $item['route']->id]"
                    >
                        {{ $item['label'] }}
                    </x-link>
                @endforeach
            </nav>

            <button
                type="button"
                x-ref="menuButton"
                @click="menuOpen ? closeMenu() : openMenu()"
                class="font-body group inline-flex min-h-11 items-center justify-center gap-3 border-0 text-[15px] text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
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
        class="archive-menu-panel fixed inset-x-0 overflow-y-auto border-y border-primary/30 bg-[var(--color-surface-ivory)]"
        :class="scrolled ? 'top-16' : 'top-20 lg:top-24'"
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
                        <h2 id="site-menu-title" class="mt-1 font-sans text-[1.65rem] font-normal leading-none text-primary">Último libro</h2>
                    </div>
                    @if ($latestBook?->fecha_publicacion)
                        <span class="font-source text-sm text-gray">{{ $latestBook->fecha_publicacion->year }}</span>
                    @endif
                </div>

                @if ($latestBook)
                    <a
                        href="{{ $latestBook->route->url }}"
                        wire:navigate.hover
                        class="group mt-5 grid grid-cols-[4.75rem_1fr] gap-4 border-y border-primary/40 py-4 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                    >
                        <div class="flex aspect-[4/5] items-center justify-center border border-primary bg-white p-1.5">
                            @if ($latestBook->portada)
                                <img src="{{ Storage::url($latestBook->portada) }}" alt="Portada de {{ $latestBook->title }}" class="h-full w-full object-contain transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-[1.03] motion-reduce:transition-none">
                            @else
                                <span class="flex h-full w-full flex-col justify-between bg-primary p-2.5 text-white">
                                    <span class="font-source text-[9px] text-gray-3">Obra reciente</span>
                                    <span class="font-source text-2xl leading-none">MB</span>
                                    <span class="h-px w-5 bg-accent" aria-hidden="true"></span>
                                </span>
                            @endif
                        </div>
                        <div class="self-center">
                            <h3 class="line-clamp-3 font-sans text-[17px] font-bold leading-[1.15] text-primary transition-colors group-hover:text-accent">{{ $latestBook->title }}</h3>
                            <span class="mt-3 inline-flex items-center gap-2 font-body text-[13px] text-primary">Ver ficha <span aria-hidden="true" class="transition-transform duration-200 group-hover:translate-x-1">→</span></span>
                        </div>
                    </a>
                @endif
            </aside>

            <nav class="lg:col-span-5 lg:px-1" aria-label="Mapa del sitio">
                <p class="mb-3 font-source text-[13px] text-gray">Explorar</p>
                <ul class="grid grid-cols-1 gap-x-7 gap-y-2 sm:grid-cols-2">
                    @foreach ($siteMap as $index => $section)
                        <li class="archive-menu-item border-t border-primary/30 pt-2" style="--menu-index: {{ $index }}">
                            <x-link
                                allowWireNavitage
                                class="font-sans inline-flex min-h-9 items-center text-[19px] leading-tight text-primary transition-colors duration-200 hover:text-gray focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent {{ $section['route']->isActive() ? 'font-bold' : '' }}"
                                :attrs="['route_id' => $section['route']->id]"
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
                                                :attrs="['route_id' => $child['route']->id, 'anchor' => $child['anchor']]"
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
