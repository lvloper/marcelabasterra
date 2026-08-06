<x-layout :notLayout="isset($notLayout) && $notLayout">
    @unless (($isModal ?? false) || ($notLayout ?? false))
        @unless (($route->full_slug ?? '') === 'home' || ($route->slug ?? '') === 'home')
            <div class="border-b border-gray-2 bg-white">
                <div class="container py-4 md:py-5">
                    <x-breadcrumbs :route="$route" />
                </div>
            </div>
        @endunless
    @endunless

    <x-blocks :blocks="$blocks" />
</x-layout>