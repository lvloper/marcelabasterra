@php
    $id = 'book-presentation-' . uniqid();

    $items = $items ?? [];
    $itemCount = count($items);
@endphp

<x-block class="bg-primary py-32 px-8 md:px-12" id="{{ $id }}">
    <div class="mx-auto max-w-7xl">
        @if ($intro_title ?? null)
            <div class="grid grid-cols-12 gap-12 mb-24">
                <div class="col-span-12 md:col-span-7">
                    <h2 class="anim-heading text-5xl md:text-6xl font-medium leading-[1.05] text-white">
                        {!! $intro_title !!}
                    </h2>
                </div>
                @if ($intro_description ?? null)
                    <div class="col-span-12 md:col-span-4 md:col-start-9 md:pt-4">
                        <p class="anim-text text-base text-white leading-relaxed">
                            {{ $intro_description }}
                        </p>
                    </div>
                @endif
            </div>
        @endif

        @if (count($items) > 0)
            <div class="relative grid grid-cols-1 md:grid-cols-3"
                style="background-image: linear-gradient(to right, rgba(255,255,255,0.45) 1px, transparent 1px), linear-gradient(to right, rgba(255,255,255,0.45) 1px, transparent 1px); background-size: 1px 100%, 1px 100%; background-position: 33.3333% 0, 66.6666% 0; background-repeat: no-repeat;">
                <span class="pointer-events-none absolute left-0 right-0 top-0 h-px"
                    style="background: linear-gradient(to right, transparent 0%, rgba(255,255,255,0.45) 15%, rgba(255,255,255,0.45) 85%, transparent 100%);" aria-hidden="true"></span>
                <span class="pointer-events-none absolute left-0 right-0 bottom-0 h-px"
                    style="background: linear-gradient(to right, transparent 0%, rgba(255,255,255,0.45) 15%, rgba(255,255,255,0.45) 85%, transparent 100%);" aria-hidden="true"></span>

                @foreach ($items as $i => $item)
                    @php
                        $itemImage = $item['image'] ?? null;
                        $itemImage = is_array($itemImage) ? ($itemImage[0] ?? null) : $itemImage;
                        $imageUrl = $itemImage ? \Illuminate\Support\Facades\Storage::url($itemImage) : null;
                        $num = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                        $isReverse = $i === 1;
                    @endphp

                    <div class="anim-card p-10 flex flex-col gap-8 {{ $isReverse ? '' : '' }}">
                        @if ($isReverse && $imageUrl)
                            <div class="anim-image aspect-square overflow-hidden">
                                <img src="{{ $imageUrl }}" alt="{{ $item['title'] ?? '' }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <div class="{{ $isReverse ? 'mt-auto' : '' }}">
                            <div class="mb-4">
                                <h3 class="anim-card-title text-3xl font-medium text-white">{{ $item['title'] ?? '' }}</h3>
                            </div>
                            <p class="anim-card-desc text-sm text-white leading-relaxed max-w-sm">{{ $item['description'] ?? '' }}</p>
                        </div>

                        @if (!$isReverse && $imageUrl)
                            <div class="anim-image aspect-square overflow-hidden mt-auto">
                                <img src="{{ $imageUrl }}" alt="{{ $item['title'] ?? '' }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-block>

@pushOnce('scripts', 'block-book-presentation')
<style>
    #{{ $id }} .anim-heading {
        opacity: 0;
        transform: translateY(40px);
    }
    #{{ $id }} .anim-text {
        opacity: 0;
        transform: translateY(30px);
    }
    #{{ $id }} .anim-card {
        opacity: 0;
        transform: translateY(40px);
    }
    #{{ $id }} .anim-card-title {
        opacity: 0;
        transform: translateY(20px);
    }
    #{{ $id }} .anim-card-desc {
        opacity: 0;
        transform: translateY(15px);
    }
    #{{ $id }} .anim-image {
        opacity: 0;
        transform: translateY(20px);
    }
</style>
<script>
    document.addEventListener('livewire:navigated', function() {
        const BLOCK = document.getElementById('{{ $id }}');
        if (!BLOCK) return;
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: BLOCK,
                start: 'top 80%',
                toggleActions: 'play none none none',
            },
            defaults: { ease: 'power3.out' },
        });

        tl.to('.anim-heading', { y: 0, opacity: 1, duration: 0.9 }, 0)
          .to('.anim-text', { y: 0, opacity: 1, duration: 0.7 }, 0.2)
          .to('.anim-card', { y: 0, opacity: 1, duration: 0.8, stagger: 0.15 }, 0.3)
          .to('.anim-card-title', { y: 0, opacity: 1, duration: 0.6, stagger: 0.15 }, 0.4)
          .to('.anim-card-desc', { y: 0, opacity: 1, duration: 0.5, stagger: 0.15 }, 0.5)
          .to('.anim-image', { y: 0, opacity: 1, duration: 0.7, stagger: 0.12 }, 0.45);
    });
</script>
@endPushOnce
