@props(['title' => ''])

<div
    x-data="{ open: false }"
    x-on:cakes-open-modal.window="if ($event.detail === '{{ $attributes->get('data-modal') }}') open = true"
    x-on:cakes-close-modal.window="open = false"
    x-on:keydown.escape.window="open = false"
    {{ $attributes->class(['relative z-50']) }}
>
    <template x-teleport="body">
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 bg-accent-900/50 p-4" style="display: none;">
            <div
                class="mx-auto mt-10 max-h-[90vh] max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl"
                @click.outside="open = false"
                x-show="open"
                x-transition
            >
                <div class="mb-4 flex items-center justify-between gap-2">
                    <h3 class="text-lg font-semibold text-accent-900">{{ $title }}</h3>
                    <button type="button" class="btn-ghost text-accent-500" @click="open = false">&times;</button>
                </div>
                {{ $slot }}
            </div>
        </div>
    </template>
</div>
