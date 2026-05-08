@props(['label', 'value' => '—', 'hint' => null, 'valueId' => null])

<div {{ $attributes->merge(['class' => 'card']) }}>
    <p class="text-sm font-medium text-accent-500">{{ $label }}</p>
    <p
        @if ($valueId) id="{{ $valueId }}" @endif
        class="mt-2 text-3xl font-bold tracking-tight text-accent-900"
    >{{ $value }}</p>
    @if ($hint)
        <p class="mt-1 text-xs text-accent-500">{{ $hint }}</p>
    @endif
</div>
