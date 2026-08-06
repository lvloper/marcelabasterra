@php
    $document = collect($documents ?? [])
        ->first(fn ($document) => is_array($document) && ($document['type'] ?? null) === 'full');

    $resolveFileUrl = static function ($file): ?string {
        if (blank($file)) {
            return null;
        }

        return filter_var($file, FILTER_VALIDATE_URL)
            ? $file
            : \Illuminate\Support\Facades\Storage::url($file);
    };

    $fileUrl = $resolveFileUrl($document['file'] ?? null);
    $identity = filled($document['title'] ?? null) ? $document['title'] : 'CV completo';
    $downloadName = filled($document['file'] ?? null) && ! filter_var($document['file'], FILTER_VALIDATE_URL)
        ? basename($document['file'])
        : 'marcela-basterra-cv.pdf';
    $downloadLabel = $document['download_label'] ?? 'Descargar PDF';
@endphp

{{-- Variante 4 · Banner compacto con icono: archivo prominente, título y descripción en fila, CTA a la derecha, formato estrecho con borde completo --}}
<x-block class="bg-white py-12 md:py-16">
    <div class="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-16">
        @if ($fileUrl)
            <a href="{{ $fileUrl }}" download="{{ $downloadName }}" aria-label="Ver CV · {{ $identity }}"
               class="group flex flex-col gap-6 border border-primary p-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent md:flex-row md:items-center md:gap-8 md:p-8">
                <x-lucide-file-text class="h-9 w-9 shrink-0 text-primary transition-colors duration-300 group-hover:text-accent motion-reduce:transition-none md:h-10 md:w-10" aria-hidden="true" />

                <div class="min-w-0 flex-1">
                    @if (filled($title ?? null))
                        <h2 class="font-sans text-[clamp(1.75rem,3vw,2.75rem)] font-normal leading-[1.04] tracking-[-0.02em] text-primary transition-colors duration-300 group-hover:text-primary/70 motion-reduce:transition-none">
                            {{ $title }}
                        </h2>
                    @endif

                    @if (filled($description ?? null))
                        <p class="mt-2 max-w-[52ch] font-body text-base leading-relaxed text-gray">
                            {{ $description }}
                        </p>
                    @endif

                    <p class="mt-3 font-body text-sm text-gray">{{ $identity }} · PDF</p>
                </div>

                <span aria-hidden="true"
                      class="inline-flex min-h-12 w-full shrink-0 items-center justify-center gap-3 border border-primary bg-transparent px-6 py-3 font-body text-base font-medium text-primary transition-colors duration-200 group-hover:bg-primary group-hover:text-white motion-reduce:transition-none md:w-auto">
                    {{ $document['view_label'] ?? 'Ver CV' }}
                    <x-lucide-download class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true" />
                </span>
            </a>
        @else
            <div class="flex flex-col gap-6 border border-primary p-6 md:flex-row md:items-center md:gap-8 md:p-8">
                <x-lucide-file-text class="h-9 w-9 shrink-0 text-primary md:h-10 md:w-10" aria-hidden="true" />

                <div class="min-w-0 flex-1">
                    @if (filled($title ?? null))
                        <h2 class="font-sans text-[clamp(1.75rem,3vw,2.75rem)] font-normal leading-[1.04] tracking-[-0.02em] text-primary">
                            {{ $title }}
                        </h2>
                    @endif

                    @if (filled($description ?? null))
                        <p class="mt-2 max-w-[52ch] font-body text-base leading-relaxed text-gray">
                            {{ $description }}
                        </p>
                    @endif

                    <p class="mt-3 font-body text-base leading-relaxed text-gray" role="status">
                        El CV todavía no está disponible. Volvé pronto.
                    </p>
                </div>
            </div>
        @endif
    </div>
</x-block>
