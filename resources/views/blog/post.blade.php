@php
    $contentHtml = $blog->content ?? '';

    if ($blog->id < 812) {
        $contentHtml = preg_replace(
            '/<p[^>]*>\s*La entrada .*? se publicó primero en .*?<\/p>/si',
            '',
            $contentHtml,
        );
    }

    $contentHtml = \Filament\Forms\Components\RichEditor\RichContentRenderer::make($contentHtml)
        ->customBlocks([
            \App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\MediaBlock::class,
        ])
        ->fileAttachmentsDisk(config('admin.contentMedia.disk'))
        ->fileAttachmentsVisibility(config('admin.contentMedia.visibility'))
        ->toHtml();

    $primaryTag = $blog->tags->first();
    $previous = $blog->previous();
    $next = $blog->next();
    $shareUrl = urlencode($blog->url);
    $shareTitle = urlencode($blog->title);
@endphp

<x-layout>
    <article id="post-container" class="bg-white text-gray">
        <header class="border-b border-gray-2 bg-gray-3 pt-16 md:pt-24">
            <div class="container pb-12 md:pb-20">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:gap-8">
                    <div class="lg:col-span-9">
                        <x-breadcrumbs :items="array_merge(
                            [['label' => 'Novedades', 'url' => url('/novedades')]],
                            $primaryTag ? [['label' => $primaryTag->name, 'url' => $blog->tagRoute($primaryTag)]] : [],
                        )" :current="$blog->title" class="mb-8" />

                        <h1 class="max-w-[18ch] text-balance font-sans text-[clamp(2.75rem,6.2vw,6rem)] font-bold leading-[0.98] tracking-[-0.035em] text-primary">
                            {{ Str::of($blog->title)->trim() }}
                        </h1>

                        @if ($blog->description)
                            <div class="mt-10 max-w-[68ch] font-source text-[1.35rem] leading-[1.45] text-gray md:text-[1.75rem]">
                                {!! $blog->description !!}
                            </div>
                        @endif
                    </div>

                    <aside class="border-t border-primary pt-5 lg:col-span-3 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0"
                        aria-label="Datos de la publicación">
                        <p class="font-body text-sm text-gray">Publicado</p>
                        <time datetime="{{ $blog->published_at?->toDateString() }}"
                            class="mt-2 block font-source text-2xl leading-tight text-primary md:text-3xl">
                            {{ $blog->formattedPublishedDate() }}
                        </time>
                        <p class="mt-5 font-body text-sm text-gray">
                            {{ $blog->published_at ? $blog->published_at->diffForHumans() : 'Fecha no disponible' }}
                        </p>
                    </aside>
                </div>
            </div>
        </header>

        @if ($blog->image)
            <div class="container py-8 md:py-12">
                <x-image :image="$blog->image" :alt="$blog->title"
                    imageClass="w-full max-h-[76vh] object-cover object-center"
                    class="w-full overflow-hidden bg-gray-3" />
            </div>
        @endif

        <div class="container border-t border-gray-2 py-14 md:py-20">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-8">
                <aside class="order-2 lg:order-1 lg:col-span-2" aria-label="Compartir publicación">
                    <div class="border-t border-primary pt-5 lg:sticky lg:top-32">
                        <p class="font-body text-sm text-primary">Compartir</p>
                        <nav class="mt-4 flex flex-wrap gap-x-5 gap-y-3 font-body text-sm lg:flex-col" aria-label="Redes sociales">
                            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
                                target="_blank" rel="noopener noreferrer"
                                class="w-fit border-b border-gray-2 py-1 text-gray transition-colors hover:border-primary hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                X / Twitter ↗
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                                target="_blank" rel="noopener noreferrer"
                                class="w-fit border-b border-gray-2 py-1 text-gray transition-colors hover:border-primary hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                Facebook ↗
                            </a>
                            <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}"
                                target="_blank" rel="noopener noreferrer"
                                class="w-fit border-b border-gray-2 py-1 text-gray transition-colors hover:border-primary hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                WhatsApp ↗
                            </a>
                            <a href="mailto:?subject={{ $shareTitle }}&body={{ $shareUrl }}"
                                class="w-fit border-b border-gray-2 py-1 text-gray transition-colors hover:border-primary hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
                                Correo ↗
                            </a>
                        </nav>
                    </div>
                </aside>

                <div class="order-1 lg:order-2 lg:col-span-8 lg:col-start-4">
                    <div class="article-content text-wysiwyg max-w-[68ch] font-source text-[1.125rem] leading-[1.7] text-gray md:text-[1.25rem]">
                        {!! $contentHtml !!}
                    </div>

                    @if ($blog->tags->isNotEmpty())
                        <div class="mt-14 border-t border-gray-2 pt-7">
                            <x-blog.tags :blog="$blog" />
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($previous || $next)
            <nav class="border-y border-gray-2 bg-gray-3" aria-label="Navegación entre publicaciones">
                <div class="container grid grid-cols-1 md:grid-cols-2">
                    @if ($previous)
                        <a wire:navigate href="{{ $previous->url }}"
                            class="group border-b border-gray-2 py-10 pr-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-inset focus-visible:outline-accent md:border-b-0 md:border-r md:py-14">
                            <span class="font-body text-sm text-gray">← Publicación anterior</span>
                            <span class="mt-4 block max-w-[28ch] font-source text-xl leading-tight text-primary transition-colors group-hover:text-accent md:text-2xl">
                                {{ $previous->title }}
                            </span>
                        </a>
                    @else
                        <div class="hidden md:block"></div>
                    @endif

                    @if ($next)
                        <a wire:navigate href="{{ $next->url }}"
                            class="group py-10 md:py-14 md:pl-8 focus-visible:outline focus-visible:outline-2 focus-visible:outline-inset focus-visible:outline-accent">
                            <span class="font-body text-sm text-gray">Publicación siguiente →</span>
                            <span class="mt-4 block max-w-[28ch] font-source text-xl leading-tight text-primary transition-colors group-hover:text-accent md:text-2xl">
                                {{ $next->title }}
                            </span>
                        </a>
                    @endif
                </div>
            </nav>
        @endif
    </article>

    <x-blog.related-posts :blog="$blog" />

    <div id="article-progress" aria-hidden="true"
        class="fixed bottom-0 left-0 z-50 h-1 w-full origin-left scale-x-0 bg-accent"></div>
</x-layout>

<script>
    (() => {
        const initializeArticleProgress = () => {
            const article = document.getElementById('post-container');
            const progress = document.getElementById('article-progress');

            if (!article || !progress) return;

            const update = () => {
                const rect = article.getBoundingClientRect();
                const distance = Math.max(article.offsetHeight - window.innerHeight, 1);
                const value = Math.min(Math.max(-rect.top / distance, 0), 1);
                progress.style.transform = `scaleX(${value})`;
            };

            if (window.__articleProgressHandler) {
                window.removeEventListener('scroll', window.__articleProgressHandler);
            }

            window.__articleProgressHandler = update;
            window.addEventListener('scroll', update, { passive: true });
            update();
        };

        document.addEventListener('livewire:navigated', initializeArticleProgress);
        initializeArticleProgress();
    })();
</script>
