<x-layout :notLayout="false">
    <article class="bg-white py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-[1100px] px-5 sm:px-8">
            <p class="font-source text-sm text-primary">{{ ucfirst($conferencia->tipo) }}@if($conferencia->institucion) · {{ $conferencia->institucion }}@endif</p>
            <h1 class="mt-4 max-w-[18ch] font-sans text-[clamp(2.75rem,6vw,5.5rem)] font-normal leading-[.96] tracking-[-.035em] text-primary">{{ $conferencia->title }}</h1>
            @if ($conferencia->fecha)<time datetime="{{ $conferencia->fecha->toDateString() }}" class="mt-5 block font-source text-lg text-gray">{{ $conferencia->fecha->locale('es')->translatedFormat('d \d\e F \d\e Y') }}</time>@endif
            @if ($conferencia->imagen)<img src="{{ Storage::url($conferencia->imagen) }}" alt="" class="mt-10 aspect-video w-full object-cover">@endif
            @if ($conferencia->descripcion)<div class="mt-10 max-w-[70ch] font-source text-xl leading-relaxed text-gray [&_p]:mb-5">{!! $conferencia->descripcion !!}</div>@endif
            <a href="{{ $conferencia->external_url }}" target="_blank" rel="noopener noreferrer" class="mt-9 inline-flex min-h-12 items-center border border-primary bg-primary px-6 font-body text-base text-white hover:bg-white hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">{{ $conferencia->link_label }} ↗</a>
        </div>
    </article>
</x-layout>
