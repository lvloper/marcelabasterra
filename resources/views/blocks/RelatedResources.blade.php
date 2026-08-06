@php
    $typeMap = [
        'libro' => App\Models\Libro::class,
        'articulo' => App\Models\ArticuloAcademico::class,
        'entrevista' => App\Models\Entrevista::class,
    ];

    $selectedTypes = $resource_types ?? ['libro', 'articulo', 'entrevista'];
    $max = $max_items ?? 4;

    $relatedItems = [];
    foreach ($selectedTypes as $type) {
        if (!isset($typeMap[$type])) continue;
        $modelClass = $typeMap[$type];
        if (!\Illuminate\Support\Facades\Schema::hasTable((new $modelClass)->getTable())) continue;

        $routeStatuses = $type === 'articulo' ? ['published', 'hidden'] : ['published'];
        $models = $modelClass::whereHas('route', fn($q) => $q->whereIn('status', $routeStatuses))
            ->with('route')->limit($max)->get();
        foreach ($models as $model) {
            $relatedItems[] = [
                'type' => $type,
                'title' => $model->title,
                'description' => $model->descripcion ?? $model->resumen ?? $model->description ?? '',
                'url' => $type === 'articulo' ? $model->document_url : $model->url,
                'external' => $type === 'articulo',
                'image' => $model->portada ?? $model->route?->image ?? null,
            ];
        }
    }
    $relatedItems = array_slice($relatedItems, 0, (int) $max);
@endphp

@if (!empty($relatedItems))
<x-block class="py-12 md:py-16">
    <div class="container mx-auto px-4">
        @if ($title ?? null)
            <h2 class="mb-4 max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary">{{ $title }}</h2>
        @endif
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($relatedItems as $item)
                <a href="{{ $item['url'] }}" @if($item['external']) target="_blank" rel="noopener" @endif class="group rounded-xl border border-gray-200 bg-white overflow-hidden hover:shadow-lg transition-shadow">
                    @if ($item['image'])
                        <div class="aspect-[4/3] overflow-hidden bg-gray-100">
                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['title'] }}"
                                 class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center">
                            <x-lucide-file-text class="w-10 h-10 text-gray-300" />
                        </div>
                    @endif
                    <div class="p-4">
                        <span class="text-xs font-semibold tracking-widest uppercase text-secondary mb-2 block font-sans">
                            {{ match($item['type']) { 'libro' => 'Libro', 'articulo' => 'Articulo', 'entrevista' => 'Entrevista', default => '' } }}
                        </span>
                        <h3 class="text-base font-bold text-gray-900 mb-1 font-sans group-hover:text-primary transition-colors line-clamp-2">{{ $item['title'] }}</h3>
                        @if ($item['description'])
                            <p class="text-sm text-gray-600 line-clamp-2 mt-1 leading-relaxed">{{ $item['description'] }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-block>
@endif
