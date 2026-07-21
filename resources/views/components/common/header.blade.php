@php
    $menu = App\Models\Menu::query()->where('slug', 'header')->first();
    $items = collect($menu?->items ?? [])->sortBy('order')->values();
    $priorityItems = $items
        ->reject(fn (array $item): bool => in_array($item['_token'] ?? null, ['menu-home', 'menu-contacto'], true))
        ->take(3);
    $contactItem = $items->firstWhere('_token', 'menu-contacto');
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
        class="container mx-auto flex h-20 items-center justify-between transition-[height] duration-200 ease-out motion-reduce:transition-none lg:h-24"
        :class="scrolled ? '!h-16' : ''"
    >
        <a
            href="/"
            wire:navigate.hover
            class="flex min-h-11 items-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
            aria-label="{{ config_text('site-name', 'Marcela Basterra') }} — Inicio"
        >
            <span
                class="block h-10 overflow-hidden [clip-path:inset(0)] transition-[width,clip-path] duration-[400ms] ease-[cubic-bezier(0.22,1,0.36,1)] motion-reduce:transition-none lg:h-12"
                :class="scrolled ? 'w-[68px]' : 'w-[11rem] lg:w-[12rem]'"
                :aria-label="scrolled ? 'Monograma de Marcela Basterra' : null"
            >
                <img
                    src="{{ asset('img/Logos/logo-sin-tagline.svg') }}"
                    alt="{{ config_text('site-name', 'Marcela Basterra') }}"
                    class="h-10 w-auto max-w-none lg:h-12"
                >
            </span>
        </a>

        <div class="flex items-center gap-4 lg:gap-8">
            @if ($menu)
                <nav class="hidden items-center gap-7 lg:flex" aria-label="Accesos principales">
                    @foreach ($priorityItems as $item)
                        @php
                            $route = App\Models\Route::find($item['route']['route_id'] ?? null);
                            $hasAnchor = $item['route']['anchor'] ?? false;
                        @endphp
                        <x-link
                            allowWireNavitage
                            class="font-body relative flex min-h-11 items-center text-[15px] leading-none text-primary transition-colors duration-200 after:absolute after:inset-x-0 after:bottom-1 after:h-px after:origin-left after:scale-x-0 after:bg-accent after:transition-transform hover:after:scale-x-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent {{ $route && $route->isActive() && !$hasAnchor ? 'after:scale-x-100' : '' }}"
                            :attrs="$item['route']"
                        >
                            {{ $item['label'] }}
                        </x-link>
                    @endforeach
                </nav>
            @endif

            @if ($contactItem)
                <x-link
                    allowWireNavitage
                    class="font-body hidden min-h-12 items-center border border-primary px-5 text-[15px] text-primary transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent lg:inline-flex"
                    :attrs="$contactItem['route']"
                >
                    {{ $contactItem['label'] }}
                    <span class="ml-3" aria-hidden="true">→</span>
                </x-link>
            @endif

            <button
                type="button"
                x-ref="menuButton"
                @click="menuOpen ? closeMenu() : openMenu()"
                class="font-body group inline-flex min-h-11 min-w-11 items-center justify-center gap-3 border-0 text-[15px] text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                :aria-expanded="menuOpen.toString()"
                aria-controls="site-menu-panel"
            >
                <span class="hidden sm:inline">Menú</span>
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
        x-transition:enter="transition duration-300 ease-out motion-reduce:transition-none"
        x-transition:enter-start="-translate-y-4 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition duration-200 ease-in motion-reduce:transition-none"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="-translate-y-2 opacity-0"
        class="fixed inset-x-0 bottom-0 overflow-y-auto border-t border-primary/30 bg-[var(--color-surface-ivory)]"
        :class="scrolled ? 'top-16' : 'top-20 lg:top-24'"
        role="dialog"
        aria-modal="true"
        aria-label="Navegación principal"
        @click.self="closeMenu()"
    >
        <div class="container mx-auto grid min-h-full grid-cols-1 gap-12 py-10 lg:grid-cols-12 lg:gap-8 lg:py-16">
            <div class="border-b border-primary/30 pb-8 lg:col-span-4 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-12">
                <button
                    type="button"
                    x-ref="closeButton"
                    @click="closeMenu()"
                    class="font-body mb-12 inline-flex min-h-11 items-center gap-3 text-[15px] text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent lg:hidden"
                >
                    Cerrar <span aria-hidden="true">×</span>
                </button>

                <p class="font-source text-[15px] text-gray">Navegación</p>
                <p class="mt-5 max-w-[12ch] font-sans text-[clamp(2.5rem,5vw,4.75rem)] font-normal leading-[0.96] text-primary">
                    Marcela Basterra
                </p>
                <div class="mt-8 h-1 w-16 bg-accent" aria-hidden="true"></div>
                <p class="font-body mt-8 max-w-[30ch] text-[16px] leading-relaxed text-gray">
                    Derecho constitucional, pensamiento académico e intervención pública.
                </p>
            </div>

            @if ($menu)
                <nav class="lg:col-span-8 lg:pl-8" aria-label="Mapa del sitio">
                    <ul class="grid grid-cols-1 gap-x-10 gap-y-9 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($items as $item)
                            @php
                                $route = App\Models\Route::find($item['route']['route_id'] ?? null);
                                $children = collect($item['children'] ?? [])->sortBy('order');
                            @endphp
                            <li class="border-t border-primary/30 pt-4">
                                <div @click="closeMenu()">
                                    <x-link
                                        allowWireNavitage
                                        class="font-sans inline-flex min-h-11 items-center text-[24px] leading-tight text-primary transition-colors duration-200 hover:text-gray focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent {{ $route && $route->isActive() ? 'font-bold' : '' }}"
                                        :attrs="$item['route']"
                                    >
                                        {{ $item['label'] }}
                                    </x-link>
                                </div>

                                @if ($children->isNotEmpty())
                                    <ul class="mt-3 space-y-1">
                                        @foreach ($children as $child)
                                            <li>
                                                <div @click="closeMenu()">
                                                    <x-link
                                                        allowWireNavitage
                                                        class="font-body inline-flex min-h-11 items-center text-[15px] leading-snug text-gray transition-colors duration-200 hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                                                        :attrs="$child['route']"
                                                    >
                                                        {{ $child['label'] }} <span class="ml-2" aria-hidden="true">→</span>
                                                    </x-link>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif
        </div>
    </div>
</header>
