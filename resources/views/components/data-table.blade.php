@props(['id' => null])

<div {{ $attributes->merge(['class' => 'card overflow-hidden p-0']) }}>
    @isset($toolbar)
        <div class="border-b border-accent-100 bg-accent-50/50 px-4 py-3">
            {{ $toolbar }}
        </div>
    @endisset
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-accent-100 text-sm" @if($id) id="{{ $id }}" @endif>
            {{ $slot }}
        </table>
    </div>
</div>
