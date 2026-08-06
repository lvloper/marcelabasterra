{{-- TEMPORAL: comparador de variantes. Elegir y pedir "me quedo con la opción N". --}}
@php
    use Illuminate\Support\Arr;
    $base = 'blocks.PublicationsHighlight';
    $forward = fn (): array => Arr::except(get_defined_vars(), ['__env', '__data', '__path', 'forward']);
@endphp
<style>
    .variants-lab {
        position: sticky; top: 0; z-index: 40;
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        padding: 0.5rem 1rem; background: rgba(15,23,42,.92); color: #fff;
        font: 600 .8rem/1 var(--font-body, sans-serif); letter-spacing: .04em;
    }
    .variants-lab span { opacity: .7; font-weight: 400; }
</style>

<div class="variants-lab">Original · {{ $base }} <span>{{ $base }}</span></div>
@include($base . '-orig', $forward())

@for ($i = 1; $i <= 4; $i++)
    @php $labView = $base . '-v' . $i; @endphp
    @if (view()->exists($labView))
        <div class="variants-lab">Opción {{ $i }} · {{ $labView }} <span>{{ $labView }}</span></div>
        @include($labView, $forward())
    @endif
@endfor
