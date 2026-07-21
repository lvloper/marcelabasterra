<div class="flex flex-wrap items-baseline gap-x-5 gap-y-3">
    <span class="font-body text-sm text-gray">Temas</span>
    @foreach ($blog->tags as $tag)
        <a href="{{ $blog->tagRoute($tag) }}"
            class="border-b border-primary pb-1 font-source text-md text-primary transition-colors hover:border-accent hover:text-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
            {{ $tag->name }}
        </a>
    @endforeach
</div>
