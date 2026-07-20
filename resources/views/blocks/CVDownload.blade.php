<x-block class="py-12 md:py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-lg mx-auto text-center bg-gray-3 rounded-2xl p-8 md:p-12 border border-gray-2">
            <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-primary/10 flex items-center justify-center">
                <x-lucide-file-text class="w-7 h-7 text-primary" />
            </div>

            @if ($title ?? null)
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 font-sans">{{ $title }}</h2>
            @endif

            @if ($description ?? null)
                <p class="text-base text-gray-600 mb-8 leading-relaxed">{{ $description }}</p>
            @endif

            <a href="#"
               class="inline-flex items-center gap-2 bg-primary text-white px-8 py-4 font-bold rounded-sm hover:bg-primary-hover transition-colors group">
                <x-lucide-download class="w-4 h-4" />
                {{ $button_text ?? 'Descargar' }}
                <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
            </a>
        </div>
    </div>
</x-block>
