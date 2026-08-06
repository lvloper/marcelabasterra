<section class="bg-white py-12 lg:py-16">
    <div class="container mx-auto">
        <div class="mb-10 grid grid-cols-1 items-end gap-5 border-b border-gray-2 pb-8 lg:grid-cols-12">
            <h2 class="font-sans text-3xl font-bold leading-none text-primary lg:col-span-7 lg:text-4xl">Dos formas de recorrer la obra</h2>
            <p class="max-w-2xl font-source text-base leading-relaxed text-gray lg:col-span-5">Explorá el catálogo bibliográfico o ingresá al archivo académico por año y tema.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <a href="{{ route('publications.books') }}" class="group relative flex flex-col border border-primary bg-primary text-white transition-colors duration-300 hover:bg-white hover:text-primary focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-4 lg:col-span-7">
                @if (isset($recentBooks[0]))
                    <div class="absolute right-4 top-4 w-16 border border-white/40 bg-white p-1 transition-transform duration-300 group-hover:-translate-y-1">
                        <img src="{{ Storage::url($recentBooks[0]->portada) }}" alt="Portada de {{ $recentBooks[0]->title }}" class="aspect-[2/3] w-full object-contain">
                    </div>
                @endif
                <div class="p-6 lg:p-8 lg:pr-28">
                    <span class="font-source text-sm text-gray-3 group-hover:text-gray">01 · Catálogo editorial</span>
                    <h3 class="mt-3 font-sans text-2xl font-bold leading-tight lg:text-3xl">Libros</h3>
                    <p class="mt-2 max-w-md font-source text-sm leading-relaxed text-gray-3 group-hover:text-gray">Portadas, datos editoriales, ISBN y enlaces de consulta.</p>
                    <span class="mt-5 inline-flex min-h-9 items-center gap-2 border-t border-current pt-3 font-body text-sm">Explorar libros <span class="transition-transform group-hover:translate-x-2">→</span></span>
                </div>
            </a>

            <a href="{{ route('publications.articles') }}" class="group flex flex-col justify-between border border-primary bg-gray-3 p-6 text-primary transition-colors duration-300 hover:bg-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-4 lg:col-span-5">
                <div class="flex items-start justify-between gap-4">
                    <span class="font-source text-sm text-gray">02 · Archivo de investigación</span>
                    <span class="border border-primary px-3 py-1 font-sans text-xs font-bold" aria-hidden="true">{{ $articlesCount }} PDF</span>
                </div>
                <div class="mt-6">
                    <h3 class="font-sans text-2xl font-bold leading-tight">Artículos académicos</h3>
                    <p class="mt-2 font-source text-sm leading-relaxed text-gray group-hover:text-gray-3">Documentos completos, organizados por fecha y temática.</p>
                </div>
                <span class="mt-5 inline-flex min-h-9 items-center gap-2 border-t border-current pt-3 font-body text-sm">Consultar archivo <span class="transition-transform group-hover:translate-x-2">→</span></span>
            </a>
        </div>
    </div>
</section>
