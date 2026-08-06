@php
    $documentsByType = collect($documents ?? [])
        ->filter(fn ($document) => is_array($document) && in_array($document['type'] ?? null, ['full', 'short'], true))
        ->keyBy('type');

    $cvVersions = [
        'full' => 'CV completo',
        'short' => 'CV reducido',
    ];

    $resolveFileUrl = static function ($file): ?string {
        if (blank($file)) {
            return null;
        }

        return filter_var($file, FILTER_VALIDATE_URL)
            ? $file
            : \Illuminate\Support\Facades\Storage::url($file);
    };

    $formatUpdatedAt = static function ($date): ?string {
        if (blank($date)) {
            return null;
        }

        try {
            return \Illuminate\Support\Carbon::parse($date)
                ->locale(app()->getLocale())
                ->translatedFormat('d \\d\\e F \\d\\e Y');
        } catch (\Throwable) {
            return null;
        }
    };

    $primaryButton = 'group inline-flex min-h-12 items-center justify-center gap-3 border border-primary bg-primary px-6 py-3 font-body text-base font-medium text-white transition-colors duration-300 hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent';
    $secondaryButton = 'group inline-flex min-h-12 items-center justify-center gap-3 border border-primary bg-transparent px-6 py-3 font-body text-base font-medium text-primary transition-colors duration-300 hover:bg-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent';
@endphp

<x-block class="bg-[var(--color-surface-ivory)] py-16 md:py-20">
    <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 gap-10 border-t border-primary pt-6 lg:grid-cols-12 lg:gap-12 lg:pt-8">
            <header class="lg:col-span-4">
                @if (filled($title ?? null))
                    <h2 class="max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary">
                        {{ $title }}
                    </h2>
                @endif

                @if (filled($description ?? null))
                    <p class="mt-6 max-w-[34ch] font-source text-xl leading-relaxed text-gray md:text-2xl">
                        {{ $description }}
                    </p>
                @endif
            </header>

            <div class="grid gap-6 lg:col-span-8 lg:grid-cols-2">
                @foreach ($cvVersions as $type => $typeLabel)
                    @php
                        $document = $documentsByType->get($type, []);
                        $fileUrl = $resolveFileUrl($document['file'] ?? null);
                        $updatedAt = $formatUpdatedAt($document['updated_at'] ?? null);
                        $downloadName = $type === 'full'
                            ? 'marcela-basterra-cv-completo.pdf'
                            : 'marcela-basterra-cv-reducido.pdf';
                    @endphp

                    <article class="flex min-h-full flex-col border border-gray-2 bg-white p-6 sm:p-8">
                        <div class="flex items-start justify-between gap-6 border-b border-gray-2 pb-5">
                            <p class="font-source text-lg leading-tight text-primary">{{ $typeLabel }}</p>
                            <span class="shrink-0 font-body text-sm {{ $fileUrl ? 'text-primary' : 'text-gray' }}">
                                {{ $fileUrl ? 'Archivo disponible' : 'Archivo pendiente' }}
                            </span>
                        </div>

                        <div class="pt-8">
                            @if (filled($document['title'] ?? null))
                                <h3 class="font-sans text-3xl font-normal leading-[1.04] tracking-[-0.02em] text-primary">
                                    {{ $document['title'] }}
                                </h3>
                            @endif

                            @if (filled($document['description'] ?? null))
                                <p class="mt-4 max-w-[38ch] font-body text-base leading-relaxed text-gray">
                                    {{ $document['description'] }}
                                </p>
                            @endif
                        </div>

                        <div class="mt-10 border-t border-gray-2 pt-5">
                            @if ($updatedAt)
                                <p class="font-source text-base text-gray">
                                    Actualizado el <time datetime="{{ $document['updated_at'] }}">{{ $updatedAt }}</time>
                                </p>
                            @endif

                            @if ($fileUrl)
                                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                                    <a href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer" class="{{ $primaryButton }}">
                                        {{ $document['view_label'] ?? 'Ver CV' }}
                                        <span class="transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">↗</span>
                                    </a>
                                    <a href="{{ $fileUrl }}" download="{{ $downloadName }}" class="{{ $secondaryButton }}">
                                        {{ $document['download_label'] ?? 'Descargar PDF' }}
                                        <x-lucide-download class="h-4 w-4" aria-hidden="true" />
                                    </a>
                                </div>
                            @else
                                <p class="mt-6 font-body text-base leading-relaxed text-gray" role="status">
                                    El archivo de esta versión todavía no está disponible.
                                </p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</x-block>
