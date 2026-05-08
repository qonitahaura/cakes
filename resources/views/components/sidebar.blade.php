@props(['role' => 'admin', 'active' => ''])

@php
    $link = fn ($key, $href, $label, $icon = '•') => [
        'key' => $key,
        'href' => $href,
        'label' => $label,
        'icon' => $icon,
    ];
    $adminLinks = [
        $link('dashboard', '/admin/dashboard', 'Dashboard', '📊'),
        $link('users', '/admin/users', 'User Management', '👥'),
        $link('categories', '/admin/categories', 'Categories', '📁'),
        $link('products', '/admin/products', 'Products', '🍰'),
        $link('customizations', '/admin/customizations', 'Cake Customizations', '✨'),
        $link('orders', '/admin/orders', 'Orders', '📦'),
        $link('payments', '/admin/payments', 'Payments', '💳'),
        $link('reports', '/admin/reports', 'Reports', '📈'),
        $link('reviews', '/admin/reviews', 'Reviews', '⭐'),
    ];
    $bakerLinks = [
        $link('dashboard', '/baker/dashboard', 'Dashboard', '📊'),
        $link('orders', '/baker/orders', 'Production Orders', '🏭'),
        $link('schedule', '/baker/schedule', 'Production Schedule', '📅'),
        $link('completed', '/baker/completed', 'Completed Orders', '✅'),
    ];
    $csLinks = [
        $link('dashboard', '/cs/dashboard', 'Dashboard', '📊'),
        $link('incoming', '/cs/incoming', 'Incoming Orders', '📥'),
        $link('validation', '/cs/validation', 'Order Validation', '✔️'),
        $link('payments', '/cs/payments', 'Payments', '💳'),
        $link('pickup', '/cs/pickup', 'Pickup Schedule', '🚗'),
        $link('history', '/cs/history', 'Order History', '📜'),
    ];
    $links = match ($role) {
        'baker' => $bakerLinks,
        'customer_service' => $csLinks,
        default => $adminLinks,
    };
@endphp

<div class="flex h-full flex-col">
    <div class="flex h-16 items-center gap-2 border-b border-accent-100 px-4">
        <span class="text-2xl">🎂</span>
        <div>
            <p class="text-sm font-bold text-accent-900">Cakes</p>
            <p class="text-xs text-accent-500 capitalize">{{ str_replace('_', ' ', $role) }}</p>
        </div>
    </div>
    <nav class="flex-1 space-y-1 overflow-y-auto p-3">
        @foreach ($links as $item)
            <a
                href="{{ $item['href'] }}"
                class="sidebar-link {{ $active === $item['key'] ? 'sidebar-link-active' : '' }}"
            >
                <span class="text-lg">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
</div>
