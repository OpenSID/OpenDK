@props(['loading' => false, 'color' => 'success', 'size' => 'sm'])

@php
    $baseClass = "btn btn-$color btn-$size";
@endphp

<button {{ $attributes->merge(['type' => 'submit', 'class' => $baseClass]) }}>
    @if ($loading && ($target = $attributes->wire('click')->value()))
        <span wire:loading.remove wire:target="{{ $target }}">{{ $slot }}</span>
        <span wire:loading wire:target="{{ $target }}">
            <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
            {{ $loading }}
        </span>
    @else
        {{ $slot }}
    @endif
</button>
