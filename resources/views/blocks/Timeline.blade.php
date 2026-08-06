<x-block class="py-12 md:py-20">
    <div class="container mx-auto px-4">
        @if ($title ?? null)
            <h2 class="mb-12 max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary">{{ $title }}</h2>
        @endif

        @if (!empty($items))
            <div class="relative max-w-3xl mx-auto">
                <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-px bg-gray-2 -translate-x-1/2"></div>

                @foreach ($items as $index => $item)
                    <div class="relative flex flex-col md:flex-row items-start mb-12 last:mb-0 group
                                {{ $loop->index % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse' }}">
                        <div class="flex-1 {{ $loop->index % 2 === 0 ? 'md:pr-12 md:text-right' : 'md:pl-12' }} w-full md:w-1/2">
                            <div class="bg-white rounded-xl border border-gray-200 p-5 md:p-6 hover:border-gray-300 transition-colors">
                                <span class="inline-block text-sm font-bold text-primary bg-primary/5 px-3 py-1 rounded-sm mb-3 font-sans">
                                    {{ $item['year'] ?? '' }}
                                </span>
                                <h3 class="text-lg font-bold text-gray-900 mb-2 font-sans">{{ $item['title'] ?? '' }}</h3>
                                @if ($item['description'] ?? null)
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $item['description'] }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="hidden md:flex absolute left-1/2 top-6 w-3 h-3 bg-primary rounded-full -translate-x-1/2 border-2 border-white z-10
                                    group-hover:scale-125 transition-transform"></div>

                        <div class="flex-1 w-full md:w-1/2 {{ $loop->index % 2 === 0 ? 'md:pl-12' : 'md:pr-12 md:text-right' }}"></div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-block>
