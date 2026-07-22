@if(isset($notLayout) && $notLayout)
{{ $slot }}
@else
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="frontend">
@include('components.common.top')

<body class="font-sans text-base tracking-normal leading-normal text-gray-800 ">

  <a href="#main" class="sr-only fixed left-4 top-4 z-[100] min-h-12 items-center border border-primary bg-white px-4 font-body text-[16px] font-semibold text-primary focus:not-sr-only focus:inline-flex focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent">
    Saltar al contenido principal
  </a>

  <x-common.header />

  @php
  $index = isset($index) ? $index : false;
  @endphp

  <main id="main" class="{{ $index ? 'has-sidebar' : '' }}" tabindex="-1">

    @if($index)
    <x-common.sidebar />
    @endif

    @if($isModal ?? false)
    <div class="z-10 bg-white main-content">
      {!! $parentView !!}
    </div>
    @else
    <div class="z-10 bg-white main-content">
      {{ $slot }}
    </div>
    @endif


  </main>

  <x-common.footer />



  @if($isModal ?? false)
  <x-modal size="xl" open ref="page-modal" :backUrl="$parentUrl ?? null">
    {{ $slot }}
  </x-modal>
  @endif
  @include('components.common.bottom')
  <x-common.edit-resource-button />
</body>

</html>

@endisset
