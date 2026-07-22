<div>
    <ol id="academic-articles-list" class="mt-12 border-b border-primary/25" aria-label="Archivo cronológico de publicaciones académicas">
        @foreach ($this->items as $item)
            <li wire:key="academic-publication-{{ $item['key'] }}" class="grid gap-4 border-t border-primary/25 py-7 sm:grid-cols-12 sm:gap-8 md:py-9">
                <div class="sm:col-span-2">
                    @if ($item['year'])
                        <time @if($item['date']) datetime="{{ $item['date'] }}" @endif class="font-[var(--font-editorial)] text-[clamp(2rem,4vw,3.75rem)] leading-none text-primary">
                            {{ $item['year'] }}
                        </time>
                    @endif
                    <p class="mt-2 font-[var(--font-body)] text-sm text-gray">{{ $item['category_label'] }}</p>
                </div>

                <div class="sm:col-span-7 lg:col-span-8">
                    <h3 class="max-w-[42ch] font-[var(--font-editorial)] text-xl leading-snug text-primary md:text-2xl">
                        {{ $item['title'] }}
                    </h3>
                    @if ($item['summary'] || $item['topic'])
                        <p class="mt-3 max-w-[68ch] font-[var(--font-body)] text-base leading-[1.6] text-gray">
                            {{ $item['summary'] ?: $item['topic'] }}
                        </p>
                    @endif
                </div>

                @if ($item['url'])
                    <div class="sm:col-span-3 sm:text-right lg:col-span-2">
                        <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener noreferrer" @endif class="group inline-flex min-h-12 items-center gap-2 border border-primary px-4 font-[var(--font-body)] text-sm font-semibold text-primary transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transition-none" aria-label="{{ $item['action_label'] }}: {{ $item['title'] }}{{ $item['external'] ? ' (se abre en una pestaña nueva)' : '' }}">
                            <span>{{ $item['action_label'] }}</span>
                            <span class="transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true">{{ $item['external'] ? '↗' : '→' }}</span>
                        </a>
                    </div>
                @endif
            </li>
        @endforeach
    </ol>

    <div class="mt-8 flex flex-col items-start gap-4 border-t border-primary pt-6 sm:flex-row sm:items-center sm:justify-between">
        <p class="font-[var(--font-body)] text-sm text-gray" aria-live="polite" aria-atomic="true">
            {{ $this->items->count() }} de {{ $this->total }} {{ $source === 'publications' ? 'publicaciones' : 'artículos' }} visibles
        </p>

        @if ($this->items->count() < $this->total)
            <button
                type="button"
                wire:click="loadMore"
                wire:loading.attr="disabled"
                wire:target="loadMore"
                aria-controls="academic-articles-list"
                class="group inline-flex min-h-14 items-center gap-3 border border-primary px-6 font-[var(--font-body)] text-sm font-semibold text-primary transition-colors duration-200 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent disabled:cursor-wait disabled:opacity-60 motion-reduce:transition-none"
            >
                <span wire:loading.remove wire:target="loadMore">Ver más</span>
                <span wire:loading wire:target="loadMore">Cargando…</span>
                <span wire:loading.remove wire:target="loadMore" class="transition-transform duration-200 group-hover:translate-y-1 motion-reduce:transition-none" aria-hidden="true">↓</span>
            </button>
        @else
            <p class="font-[var(--font-body)] text-sm font-semibold text-primary">Archivo completo</p>
        @endif
    </div>
</div>
