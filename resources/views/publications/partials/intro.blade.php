<section class="border-b border-primary bg-gray-3">
    <div class="container mx-auto grid min-h-[72vh] grid-cols-1 items-end gap-12 py-20 lg:grid-cols-12 lg:py-28">
        <div class="lg:col-span-8">
            <x-breadcrumbs :items="[['label' => 'Publicaciones']]" class="mb-8" />
            <p class="mb-8 font-source text-base text-primary">Archivo editorial · Libros y pensamiento jurídico</p>
            <h1 class="max-w-5xl font-sans text-5xl font-bold leading-[0.96] tracking-[-0.035em] text-primary sm:text-6xl lg:text-[6.5rem]">
                Publicaciones
            </h1>
            <p class="mt-10 max-w-3xl font-source text-2xl leading-relaxed text-gray lg:text-3xl">
                Una biblioteca de libros y artículos que reúne décadas de investigación constitucional, producción académica y debate público.
            </p>
        </div>

        <dl class="border-t border-primary lg:col-span-4">
            <div class="flex items-baseline gap-6 border-b border-gray-2 py-6">
                <dd class="font-source text-[clamp(2.5rem,4vw,4rem)] leading-none text-primary">{{ $booksCount }}</dd>
                <dt class="font-body text-base text-gray">Libros</dt>
            </div>
            <div class="flex items-baseline gap-6 border-b border-gray-2 py-6">
                <dd class="font-source text-[clamp(2.5rem,4vw,4rem)] leading-none text-primary">{{ $articlesCount }}</dd>
                <dt class="font-body text-base text-gray">Artículos</dt>
            </div>
            <div class="flex items-baseline gap-6 py-6">
                <dd class="font-source text-[clamp(2.5rem,4vw,4rem)] leading-none text-primary">{{ $topicsCount }}</dd>
                <dt class="font-body text-base text-gray">Áreas temáticas</dt>
            </div>
        </dl>
    </div>
</section>
