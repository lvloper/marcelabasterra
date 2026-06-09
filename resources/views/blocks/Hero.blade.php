@php
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
</x-block>
