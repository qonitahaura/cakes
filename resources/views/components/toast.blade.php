<div
    x-data
    class="pointer-events-none fixed inset-x-0 bottom-0 z-[100] flex flex-col items-end gap-2 p-4 sm:p-6"
    aria-live="polite"
>
    <template x-for="item in $store.toast.items" :key="item.id">
        <div
            class="pointer-events-auto w-full max-w-sm rounded-xl border px-4 py-3 text-sm font-medium shadow-lg sm:w-auto"
            :class="{
                'border-emerald-200 bg-emerald-50 text-emerald-900': item.type === 'success',
                'border-red-200 bg-red-50 text-red-900': item.type === 'error',
                'border-sky-200 bg-sky-50 text-sky-900': item.type === 'info',
            }"
            x-text="item.message"
        ></div>
    </template>
</div>
