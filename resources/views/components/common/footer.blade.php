@php
    $menu = App\Models\Menu::query()->where('slug', 'header')->first();
    $items = collect($menu?->items ?? [])->sortBy('order')->values();
    $contactItem = $items->firstWhere('_token', 'menu-contacto');
@endphp

<footer class="relative z-20 border-t border-white/30 bg-primary text-white">
    <div class="container mx-auto py-16 lg:py-20">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-8">
            <div class="lg:col-span-7">
                <p class="font-source text-[15px] text-gray-3">Marcela Basterra</p>
                <p class="mt-5 max-w-[11ch] font-sans text-[clamp(2.75rem,6vw,5.5rem)] font-normal leading-[0.94] text-white">
                    Pensamiento jurídico para el presente.
                </p>

                @if ($contactItem)
                    <x-link
                        allowWireNavitage
                        class="font-body group mt-10 inline-flex min-h-12 items-center border border-white px-6 text-[16px] text-white transition-colors duration-200 hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                        :attrs="$contactItem['route']"
                    >
                        Contacto
                        <span class="ml-4 transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span>
                    </x-link>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-10 border-t border-white/30 pt-8 sm:grid-cols-2 lg:col-span-5 lg:border-l lg:border-t-0 lg:pl-12 lg:pt-0">
                @if ($menu)
                    <nav aria-label="Navegación del pie">
                        <p class="font-source mb-4 text-[15px] text-gray-3">Explorar</p>
                        <ul class="space-y-1">
                            @foreach ($items as $item)
                                <li>
                                    <x-link
                                        allowWireNavitage
                                        class="font-body inline-flex min-h-11 items-center text-[16px] text-white transition-colors duration-200 hover:text-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                                        :attrs="$item['route']"
                                    >
                                        {{ $item['label'] }}
                                    </x-link>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                @endif

                <div>
                    <p class="font-source mb-4 text-[15px] text-gray-3">Redes</p>
                    <ul class="space-y-1">
                        @foreach (config('social-media.networks', []) as $network => $data)
                            <li>
                                <a
                                    href="{{ $data['url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="font-body inline-flex min-h-11 items-center text-[16px] capitalize text-white transition-colors duration-200 hover:text-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                                >
                                    {{ $network }} <span class="ml-2" aria-hidden="true">↗</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-16 flex flex-col gap-8 border-t border-white/30 pt-8 sm:flex-row sm:items-end sm:justify-between lg:mt-20">
            <a
                href="/"
                wire:navigate.hover
                class="block w-fit focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                aria-label="{{ config_text('site-name', 'Marcela Basterra') }} — Inicio"
            >
                <img
                    src="{{ asset('img/Logo-blanco.svg') }}"
                    alt="{{ config_text('site-name', 'Marcela Basterra') }}"
                    class="h-12 w-auto sm:h-14"
                >
            </a>

            <div class="font-body text-[14px] leading-relaxed text-gray-3 sm:text-right">
                <p>© {{ now()->year }} {{ config_text('site-name', 'Marcela Basterra') }}</p>
                <p>Sitio institucional · Todos los derechos reservados</p>
            </div>
        </div>
    </div>
</footer>
