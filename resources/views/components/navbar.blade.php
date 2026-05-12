@props(['title' => 'Dashboard', 'role' => 'admin'])

<header class="sticky top-0 z-30 flex h-16 items-center justify-between gap-4 border-b border-accent-100 bg-white/80 px-4 backdrop-blur sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <button
            type="button"
            class="btn-ghost lg:hidden"
            x-data
            @click="$store.layout.mobileOpen = true"
        >
            <span class="sr-only">Open menu</span>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h1 class="truncate text-lg font-semibold text-accent-900 sm:text-xl">{{ $title }}</h1>
    </div>
    <div class="flex items-center gap-2 sm:gap-4" x-data="{ open: false }">
        <button type="button" class="relative rounded-xl p-2 text-accent-600 hover:bg-primary-50" title="Notifications">
            <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-primary-500 ring-2 ring-white"></span>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </button>
        <div class="relative">
            <button
                type="button"
                class="flex items-center gap-2 rounded-xl border border-accent-100 bg-white px-3 py-1.5 text-sm font-medium text-accent-800 shadow-sm hover:bg-accent-50"
                @click="open = !open"
            >
                <span class="hidden max-w-[120px] truncate sm:inline" data-profile-name>Account</span>
                <svg class="h-4 w-4 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div
                x-show="open"
                @click.outside="open = false"
                x-transition
                class="absolute right-0 mt-2 w-48 origin-top-right rounded-xl border border-accent-100 bg-white py-1 shadow-lg"
                style="display: none;"
            >
                <span class="block px-4 py-2 text-xs text-accent-500" data-profile-email></span>
                <button type="button" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50" id="cakes-logout-btn">Logout</button>
            </div>
        </div>
    </div>
</header>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                try {
                    const u = JSON.parse(localStorage.getItem('cakes_user') || '{}');
                    const n = document.querySelector('[data-profile-name]');
                    const e = document.querySelector('[data-profile-email]');
                    if (n && u.name) n.textContent = u.name;
                    if (e && u.email) e.textContent = u.email;
                } catch (err) {}
                const btn = document.getElementById('cakes-logout-btn');
                if (btn && window.CakesAuth?.logout) {
                    btn.addEventListener('click', () => window.CakesAuth.logout());
                }
            });
        </script>
    @endpush
@endonce
