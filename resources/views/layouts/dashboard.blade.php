@extends('layouts.app', ['title' => $title ?? 'Dashboard', 'page' => $page ?? null])

@section('content')
    <div class="flex min-h-screen">
        <div
            x-data
            x-show="$store.layout.mobileOpen"
            x-transition.opacity
            @click="$store.layout.mobileOpen = false"
            class="fixed inset-0 z-40 bg-accent-900/40 lg:hidden"
            style="display: none;"
        ></div>

        <aside
            x-data
            class="fixed inset-y-0 left-0 z-50 w-64 transform border-r border-accent-100 bg-white/95 shadow-card backdrop-blur transition lg:static lg:translate-x-0"
            :class="$store.layout.mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <x-sidebar :role="$role" :active="$active ?? ''" />
        </aside>

        <div class="flex min-w-0 flex-1 flex-col lg:pl-0">
            <x-navbar :title="$title ?? 'Dashboard'" :role="$role" />
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('dashboard-content')
            </main>
        </div>
    </div>
@endsection
