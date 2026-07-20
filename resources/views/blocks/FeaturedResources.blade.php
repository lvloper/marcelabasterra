@php
    $typeMap = [
        'libro' => \App\Models\Libro::class,
        'articulo' => \App\Models\ArticuloAcademico::class,
        'entrevista' => \App\Models\Entrevista::class,
    ];

    $resources = [];
    foreach (($items ?? []) as $item) {
        $type = $item['resource_type'] ?? null;
        $id = $item['resource_id'] ?? null;
        if (!$type || !$id || !isset($typeMap[$type])) continue;

        $model = $typeMap[$type]::find($id);
        if (!$model || !$model->route) continue;
        if ($model->route->status->value !== 'published') continue;

        $img = $model->portada ?? $model->route?->image ?? null;
        $resources[] = [
            'type' => $type,
            'title' => $model->title,
            'description' => $model->descripcion ?? $model->resumen ?? $model->description ?? '',
            'url' => $model->url,
            'image' => $img,
        ];
    }
@endphp

@if (!empty($resources))
<x-block class="py-12 md:py-16">
    <div class="container mx-auto px-4">
        @if ($title ?? null)
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 text-center font-sans">{{ $title }}</h2>
        @endif

        @if ($description ?? null)
            <p class="text-base text-gray-600 mb-10 text-center max-w-2xl mx-auto">{{ $description }}</p>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($resources as $resource)
                <a href="{{ $resource['url'] }}" class="group rounded-xl border border-gray-200 bg-white overflow-hidden hover:shadow-lg transition-shadow">
                    @if ($resource['image'])
                        <div class="aspect-[4/3] overflow-hidden bg-gray-100">
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($resource['image']) }}"
                                alt="{{ $resource['title'] }}"
                                class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                        </div>
                    @else
                        <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center">
                            <x-lucide-book-open class="w-10 h-10 text-gray-300" />
                        </div>
                    @endif

                    <div class="p-5">
                        <span class="text-xs font-semibold tracking-widest uppercase text-secondary mb-2 block font-sans">
                            {{ match($resource['type']) { 'libro' => 'Libro', 'articulo' => 'Articulo', 'entrevista' => 'Entrevista', default => '' } }}
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 font-sans group-hover:text-primary transition-colors">
                            {{ $resource['title'] }}
                        </h3>
                        @if ($resource['description'])
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $resource['description'] }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-block>
@endif
