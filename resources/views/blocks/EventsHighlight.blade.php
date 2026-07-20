@php
    $eventItems = [];
    foreach (($eventos ?? []) as $eventoId) {
        $evento = App\Models\Evento::find($eventoId);
        if (!$evento || !$evento->route) continue;
        $now = now();
        $showPast = $show_past ?? false;
        if (!$showPast && $evento->fecha_inicio && $evento->fecha_inicio < $now) continue;
        $eventItems[] = [
            'title' => $evento->title,
            'description' => $evento->descripcion ?? '',
            'url' => $evento->url,
            'image' => $evento->route?->image,
            'fecha_inicio' => $evento->fecha_inicio,
            'fecha_fin' => $evento->fecha_fin,
            'ubicacion' => $evento->ubicacion,
            'tipo' => $evento->tipo,
            'enlace_inscripcion' => $evento->enlace_inscripcion,
        ];
    }
    $max = $max_items ?? 6;
    $eventItems = array_slice($eventItems, 0, (int) $max);
@endphp

@if (!empty($eventItems))
<x-block class="py-12 md:py-16">
    <div class="container mx-auto px-4">
        @if ($title ?? null)
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 text-center font-sans">{{ $title }}</h2>
        @endif
        @if ($description ?? null)
            <p class="text-base text-gray-600 mb-10 text-center max-w-2xl mx-auto">{{ $description }}</p>
        @endif
        <div class="max-w-3xl mx-auto space-y-4">
            @foreach ($eventItems as $item)
                <a href="{{ $item['url'] }}" class="group flex flex-col sm:flex-row gap-4 bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md hover:border-gray-300 transition-all">
                    <div class="shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-lg bg-primary/5 flex flex-col items-center justify-center text-center">
                        @if ($item['fecha_inicio'])
                            <span class="text-xs font-bold text-primary uppercase leading-tight font-sans">
                                {{ $item['fecha_inicio'] instanceof Carbon\Carbon ? $item['fecha_inicio']->shortLocaleMonth : '' }}
                            </span>
                            <span class="text-xl font-bold text-primary leading-none font-sans">
                                {{ $item['fecha_inicio'] instanceof Carbon\Carbon ? $item['fecha_inicio']->day : '' }}
                            </span>
                        @else
                            <x-lucide-calendar class="w-6 h-6 text-primary" />
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold text-gray-900 mb-1 font-sans group-hover:text-primary transition-colors">{{ $item['title'] }}</h3>
                        <div class="flex flex-wrap gap-2 text-xs text-gray-500 mb-2">
                            @if ($item['tipo'])
                                <span class="bg-gray-100 px-2 py-0.5 rounded-sm font-sans">{{ $item['tipo'] }}</span>
                            @endif
                            @if ($item['ubicacion'])
                                <span class="flex items-center gap-1">
                                    <x-lucide-map-pin class="w-3 h-3" />
                                    {{ $item['ubicacion'] }}
                                </span>
                            @endif
                        </div>
                        @if ($item['description'])
                            <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">{{ $item['description'] }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-block>
@endif
