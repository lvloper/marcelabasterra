@php
    $ctaUrl = '';
    $ctaRoute = $button_route ?? [];
    if ($ctaRoute['route_id'] ?? null) {
        $r = \App\Models\Route::find($ctaRoute['route_id']);
        if ($r) {
            $ctaUrl = url($r->full_slug);
            if ($ctaRoute['anchor'] ?? null) $ctaUrl .= '#' . $ctaRoute['anchor'];
        }
    } elseif ($ctaRoute['external_url'] ?? null) {
        $ctaUrl = $ctaRoute['external_url'];
    }
@endphp

<x-block class="py-16 md:py-24 bg-primary relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none z-0 opacity-10">
        <div class="bg-grid [mask-image:radial-gradient(circle_at_center,black_30%,transparent_80%)] w-full h-full"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 text-center">
        @if ($title ?? null)
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 font-sans max-w-2xl mx-auto">
                {{ $title }}
            </h2>
        @endif

        @if ($text ?? null)
            <p class="text-lg text-white/80 max-w-xl mx-auto mb-10 leading-relaxed">
                {{ $text }}
            </p>
        @endif

        @if ($ctaUrl && ($button_label ?? null))
            <a href="{{ $ctaUrl }}"
               @if ($ctaRoute['new_window'] ?? false) target="_blank" rel="noopener noreferrer" @endif
               class="inline-flex items-center gap-2 bg-accent text-white px-8 py-4 font-bold rounded-sm hover:bg-accent-hover transition-colors group">
                {{ $button_label }}
                <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
            </a>
        @endif
    </div>
</x-block>
