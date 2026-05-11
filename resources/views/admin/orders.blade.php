@extends('layouts.dashboard')

@section('dashboard-content')
<div class="mb-6">
    <h2 class="page-title">Orders</h2>
    <p class="mt-1 text-sm text-accent-600">View and filter all orders</p>
</div>

<div class="mb-4 flex flex-wrap items-end gap-4">
    <div>
        <label class="label" for="order-search">Search</label>
        <input id="order-search" class="input max-w-md" type="search" placeholder="Search code or customer…" />
    </div>

    <div>
        <label class="label" for="order-filter">Order status</label>
        <select id="order-filter" class="input max-w-xs">
            <option value="">All</option>
            <option value="pending">pending</option>
            <option value="waiting_payment">waiting_payment</option>
            <option value="paid">paid</option>
            <option value="processing">processing</option>
            <option value="shipped">shipped</option>
            <option value="completed">completed</option>
            <option value="cancelled">cancelled</option>
            <option value="refunded">refunded</option>
        </select>
    </div>

    <div>
        <label class="label" for="order-payment-filter">Payment status</label>
        <select id="order-payment-filter" class="input max-w-xs">
            <option value="">All</option>
            <option value="paid">paid</option>
            <option value="unpaid">unpaid</option>
        </select>
    </div>

    <div>
        <label class="label" for="order-pickup-date">Pickup date</label>
        <input id="order-pickup-date" class="input" type="date" />
    </div>

    <div>
        <label class="label" for="order-sort">Sort</label>
        <select id="order-sort" class="input max-w-xs">
            <option value="newest" selected>Latest</option>
            <option value="oldest">Oldest</option>
        </select>
    </div>
</div>

<div class="mb-2 flex items-center justify-between gap-3">
    <div id="orders-pagination-meta" class="text-sm text-accent-600"></div>
    <div class="flex items-center gap-2">
        <button type="button" id="orders-page-prev" class="btn-ghost">Prev</button>
        <div class="text-sm text-accent-700" id="orders-page-indicator">—</div>
        <button type="button" id="orders-page-next" class="btn-ghost">Next</button>
    </div>
</div>

<x-data-table id="tbl-orders">

    <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
        <tr>
            <th class="px-4 py-3">Code</th>
            <th class="px-4 py-3">Customer</th>
            <th class="px-4 py-3">Total</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3 text-right">Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</x-data-table>
@endsection