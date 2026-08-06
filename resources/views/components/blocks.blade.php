<div class="bg-white blocks-container fade-in" >
    @foreach ($blocks as $block)
        @php
            $componentPath = 'blocks.' . $block['type'];
        @endphp

        @if ( !($block['data']['hidden'] ?? false) && view()->exists($componentPath))
        @php
        
            $uid = filled($block['data']['blockAnchor'] ?? null)
                ? \Illuminate\Support\Str::slug($block['data']['blockAnchor'])
                : (filled($block['data']['blockTitle'] ?? null) ? \Illuminate\Support\Str::slug($block['data']['blockTitle']) : 'block-' . uniqid());
            
            $mb = $block['data']['mb'] ?? "";
            $mdMb = $block['data']['mdMb'] ?? "";
            $clases = $block['data']['clases'] ?? [];
            $styles = $block['data']['styles'] ?? [];
            $stylesMd = $block['data']['stylesMd'] ?? [];

            $allClasses = implode(' ', array_merge([$mb, $mdMb], $clases));
        @endphp
        <div id="{{ $uid }}" class="block block-{{ $block['type'] }} {{ $allClasses }}">

            @component($componentPath, [...$block['data'], 'id' => $uid])
            @endcomponent

            @if ($styles) <style>@foreach ($styles as $key => $value)#{{ $uid }} { {{ $key }}: {{ $value }}; } @endforeach</style> @endif
            @if ($stylesMd) <style> @media (min-width: 768px) { @foreach ($stylesMd as $key => $value)#{{ $uid }} { {{ $key }}: {{ $value }}; } @endforeach } </style> @endif
            
        </div>

        @endif
    @endforeach
</div>
