@extends('layouts.dashboard')

@section('dashboard-content')
<div class="mb-6">
    <h2 class="page-title">Production orders</h2>
    <p class="mt-1 text-sm text-accent-600">Search, filter, sort and paginate</p>
</div>

<div class="mb-4 grid gap-3 md:grid-cols-5">
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-medium text-accent-600">Search</label>
        <input id="bk-orders-search" type="text" class="input" placeholder="Order code or customer name" />
    </div>

    <div>
        <label class="mb-1 block text-xs font-medium text-accent-600">Sort</label>
        <select id="bk-orders-sort" class="input">
            <option value="newest">Newest</option>
            <option value="deadline">Nearest deadline</option>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-xs font-medium text-accent-600">Order status</label>
        <select id="bk-orders-status" class="input">
            <option value="">All</option>
            <option value="paid">Paid</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="completed">Completed</option>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-xs font-medium text-accent-600">Pickup deadline</label>
        <input id="bk-orders-deadline" type="date" class="input" />
    </div>
</div>

<div class="mb-3 flex items-center justify-between gap-3">
    <div class="text-sm text-accent-600" id="bk-orders-pagination-meta"></div>
    <div class="text-sm text-accent-700" id="bk-orders-page-indicator"></div>
    <div class="flex items-center gap-2">
        <button id="bk-orders-page-prev" class="btn btn-secondary text-sm">Prev</button>
        <button id="bk-orders-page-next" class="btn btn-secondary text-sm">Next</button>
    </div>
</div>

<x-data-table id="bk-orders">
    <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
        <tr>
            <th class="px-4 py-3">Code</th>
            <th class="px-4 py-3">Customer</th>
            <th class="px-4 py-3">Notes</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3 text-right">Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</x-data-table>
@endsection