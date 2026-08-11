@props([
    'title' => null,
    'description' => null,
    'message' => 'Completá los campos del bloque para generar la vista previa.',
])

<div class="border-y border-gray-2 bg-gray-3 px-5 py-10 sm:px-8">
    <div class="mx-auto max-w-[1440px]">
        <p class="font-source text-sm text-gray">Vista previa del bloque</p>
        @if (filled($title))
            <h2 class="mt-3 max-w-[18ch] font-sans text-3xl font-normal leading-tight text-primary">
                {{ $title }}
            </h2>
        @endif
        @if (filled($description))
            <p class="mt-3 max-w-[52ch] font-source text-lg leading-relaxed text-gray">
                {{ $description }}
            </p>
        @endif
        <p class="mt-5 max-w-[58ch] border-l border-accent pl-4 font-body text-sm leading-relaxed text-gray">
            {{ $message }}
        </p>
    </div>
</div>
