@php
    $pubItems = [];
    foreach (($libros ?? []) as $libroId) {
        $libro = App\Models\Libro::find($libroId);
        if (!$libro || !$libro->route) continue;
        $pubItems[] = [
            'type' => 'libro',
            'typeLabel' => 'Libro',
            'title' => $libro->title,
            'subtitle' => $libro->subtitulo ?? $libro->editorial ?? '',
            'description' => $libro->descripcion ?? '',
            'url' => $libro->url,
            'image' => $libro->portada,
            'date' => $libro->fecha_publicacion,
        ];
    }
    foreach (($articulos ?? []) as $articuloId) {
        $articulo = App\Models\ArticuloAcademico::find($articuloId);
        if (!$articulo || !$articulo->route) continue;
        $pubItems[] = [
            'type' => 'articulo',
            'typeLabel' => 'Articulo academico',
            'title' => $articulo->title,
            'subtitle' => $articulo->tematica ?? '',
            'description' => $articulo->resumen ?? '',
            'url' => $articulo->url,
            'image' => $articulo->route?->image,
            'date' => $articulo->fecha_publicacion,
        ];
    }
    $max = $max_items ?? 6;
    $pubItems = array_slice($pubItems, 0, (int) $max);
    $showTypeLabel = $show_type_label ?? true;
@endphp

@if (!empty($pubItems))
<x-block class="py-12 md:py-16">
    <div class="container mx-auto px-4">
        @if ($title ?? null)
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 text-center font-sans">{{ $title }}</h2>
        @endif
        @if ($description ?? null)
            <p class="text-base text-gray-600 mb-10 text-center max-w-2xl mx-auto">{{ $description }}</p>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($pubItems as $item)
                <a href="{{ $item['url'] }}" class="group rounded-xl border border-gray-200 bg-white overflow-hidden hover:shadow-lg transition-shadow">
                    @if ($item['image'])
                        <div class="aspect-[4/3] overflow-hidden bg-gray-100">
                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['title'] }}"
                                 class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center">
                            <x-lucide-book-open class="w-10 h-10 text-gray-300" />
                        </div>
                    @endif
                    <div class="p-5">
                        @if ($showTypeLabel)
                            <span class="text-xs font-semibold tracking-widest uppercase text-secondary mb-2 block font-sans">{{ $item['typeLabel'] }}</span>
                        @endif
                        <h3 class="text-lg font-bold text-gray-900 mb-2 font-sans group-hover:text-primary transition-colors">{{ $item['title'] }}</h3>
                        @if ($item['subtitle'])
                            <p class="text-sm text-gray-500 mb-2 font-sans">{{ $item['subtitle'] }}</p>
                        @endif
                        @if ($item['description'])
                            <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">{{ $item['description'] }}</p>
                        @endif
                        @if ($item['date'])
                            <span class="text-xs text-gray-400 mt-3 block">{{ $item['date'] instanceof Carbon\Carbon ? $item['date']->year : $item['date'] }}</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-block>
@endif
