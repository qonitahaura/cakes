@extends('layouts.dashboard')

@section('dashboard-content')
<div class="mb-6">
    <h2 class="page-title">Incoming orders</h2>
    <p class="mt-1 text-sm text-accent-600">Search, filter and paginate</p>
</div>

<div class="mb-4 grid gap-3 md:grid-cols-5">
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-medium text-accent-600">Search</label>
        <input id="cs-incoming-search" type="text" class="input" placeholder="Order code or customer name" />
    </div>

    <div>
        <label class="mb-1 block text-xs font-medium text-accent-600">Sort</label>
        <select id="cs-incoming-sort" class="input">
            <option value="newest">Newest</option>
            <option value="oldest">Oldest</option>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-xs font-medium text-accent-600">Order status</label>
        <select id="cs-incoming-status" class="input">
            <option value="">All</option>
            <option value="pending">Pending</option>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-xs font-medium text-accent-600">Pickup date</label>
        <input id="cs-incoming-pickup-date" type="date" class="input" />
    </div>

    <div>
        <label class="mb-1 block text-xs font-medium text-accent-600">Payment status</label>
        <select id="cs-incoming-payment-status" class="input">
            <option value="">All</option>
            <option value="paid">Paid</option>
            <option value="unpaid">Unpaid</option>
        </select>
    </div>
</div>

<div class="mb-3 flex items-center justify-between gap-3">
    <div class="text-sm text-accent-600" id="cs-incoming-pagination-meta"></div>
    <div class="text-sm text-accent-700" id="cs-incoming-page-indicator"></div>
    <div class="flex items-center gap-2">
        <button id="cs-incoming-page-prev" class="btn btn-secondary text-sm">Prev</button>
        <button id="cs-incoming-page-next" class="btn btn-secondary text-sm">Next</button>
    </div>
</div>

<x-data-table id="cs-incoming">
    <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
        <tr>
            <th class="px-4 py-3">Code</th>
            <th class="px-4 py-3">Customer</th>
            <th class="px-4 py-3">Total</th>
            <th class="px-4 py-3">Created</th>
        </tr>
    </thead>
    <tbody></tbody>
</x-data-table>
@endsection