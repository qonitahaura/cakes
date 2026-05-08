@props(['id' => 'chart-' . uniqid()])

<div class="card">
    @isset($title)
        <h3 class="mb-4 text-sm font-semibold text-accent-800">{{ $title }}</h3>
    @endisset
    <div class="relative h-64 w-full">
        <canvas id="{{ $id }}" {{ $attributes->except(['id']) }}></canvas>
    </div>
</div>
