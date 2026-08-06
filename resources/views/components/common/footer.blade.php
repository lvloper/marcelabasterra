@php
    $menu = App\Models\Menu::query()->where('slug', 'header')->first();
    $items = collect($menu?->items ?? [])->sortBy('order')->values();
    $contactItem = $items->firstWhere('_token', 'menu-contacto');
@endphp

<footer class="relative z-20 bg-white">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 border-y border-gray-2 lg:grid-cols-12">
            <div class="flex items-center justify-between gap-6 px-0 py-7 lg:col-span-4 lg:border-r lg:border-gray-2 lg:pr-12">
                <div>
                    <p class="font-sans text-xl leading-tight text-gray">{{ config_text('site-name', 'Marcela Basterra') }}</p>
                    <p class="mt-1 font-source text-sm text-gray">Pensamiento jurídico para el presente</p>
                </div>
                <a
                    href="/"
                    wire:navigate.hover
                    class="block w-fit shrink-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                    aria-label="{{ config_text('site-name', 'Marcela Basterra') }} — Inicio"
                >
                    <img
                        src="{{ asset('img/Logos/monograma-azul.svg') }}"
                        alt=""
                        class="h-11 w-auto"
                    >
                </a>
            </div>

            @if ($menu)
                <nav aria-label="Navegación del pie" class="border-t border-gray-2 py-7 lg:col-span-4 lg:border-r lg:border-t-0 lg:px-12">
                    <ul class="grid grid-cols-2 gap-x-6 gap-y-1">
                        @foreach ($items->reject(fn ($item) => ($item['_token'] ?? null) === 'menu-contacto') as $item)
                            <li>
                                <x-link
                                    allowWireNavitage
                                    class="font-body inline-flex min-h-10 items-center text-[15px] text-gray transition-colors duration-200 hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                                    :attrs="$item['route']"
                                >
                                    {{ $item['label'] }}
                                </x-link>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            <div class="border-t border-gray-2 py-7 lg:col-span-4 lg:border-t-0 lg:pl-12">
                <ul class="flex flex-wrap gap-x-6 gap-y-1">
                    @foreach (config('social-media.networks', []) as $network => $data)
                        <li>
                            <a
                                href="{{ $data['url'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="font-body inline-flex min-h-10 items-center text-[15px] capitalize text-gray transition-colors duration-200 hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                            >
                                {{ $network }} <span class="ml-1.5" aria-hidden="true">↗</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                @if ($contactItem)
                    <x-link
                        allowWireNavitage
                        class="font-body group mt-4 inline-flex min-h-10 items-center gap-3 border-b border-gray text-[15px] font-medium text-gray transition-colors duration-200 hover:border-primary hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                        :attrs="$contactItem['route']"
                    >
                        Contacto <span class="transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">→</span>
                    </x-link>
                @endif
            </div>
        </div>

        <p class="py-5 font-body text-[14px] text-gray">
            © {{ now()->year }} {{ config_text('site-name', 'Marcela Basterra') }} · Todos los derechos reservados
        </p>
    </div>
</footer>
