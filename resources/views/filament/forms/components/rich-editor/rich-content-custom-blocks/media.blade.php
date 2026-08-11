<figure @class(['rich-content-media', 'rich-content-media--preview' => $isPreview])>
    @if ($type === 'video')
        <video controls playsinline preload="metadata" @if ($posterUrl) poster="{{ $posterUrl }}" @endif>
            <source src="{{ $mediaUrl }}" type="{{ $videoMimeType }}">
            Tu navegador no puede reproducir este video.
        </video>
    @else
        <img src="{{ $mediaUrl }}" alt="{{ $alt }}" loading="lazy" decoding="async">
    @endif

    @if (filled($caption))
        <figcaption>{{ $caption }}</figcaption>
    @endif
</figure>
