{{-- Variante 1: Marfil editorial — superficie cálida, titular grande con punto de acento, cifra con borde superior --}}
<section class="border-b border-primary bg-white">
    <div class="container mx-auto grid grid-cols-1 gap-12 py-20 lg:grid-cols-12 lg:gap-8 lg:py-28">
        <div class="lg:col-span-8">
            <x-breadcrumbs :items="[
                ['label' => 'Publicaciones', 'url' => route('publications.index')],
                ['label' => 'Libros'],
            ]" class="mb-10" />
            <h1 class="mt-10 font-sans text-[clamp(3rem,6.5vw,6.5rem)] font-normal leading-[0.94] tracking-[-0.035em] text-primary">
                Libros<span class="text-accent">.</span>
            </h1>
        </div>
        <div class="lg:col-span-4 lg:col-start-9 lg:self-end">
            <p class="max-w-[38ch] font-source text-2xl leading-relaxed text-gray">Obras de autoría, coautoría y dirección. Un recorrido por la producción bibliográfica.</p>
            <p class="mt-10 border-t border-primary pt-6 font-source text-5xl text-primary">{{ $totalLibros }} <span class="font-body text-base text-gray">títulos</span></p>
        </div>
    </div>
</section>
