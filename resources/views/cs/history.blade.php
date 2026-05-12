@extends('layouts.dashboard')

@section('dashboard-content')
<div class="mb-6">
    <h2 class="page-title">Order history</h2>
    <p class="mt-1 text-sm text-accent-600">Search, filter and paginate</p>
</div>

<div class="mb-4 grid gap-3 md:grid-cols-4">
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-medium text-accent-600">Search</label>
        <input id="cs-history-search" type="text" class="input" placeholder="Order code or customer name" />
    </div>

    <div>
        <label class="mb-1 block text-xs font-medium text-accent-600">Status</label>
        <select id="cs-history-status" class="input">
            <option value="">All</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
            <option value="pending">Pending</option>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-xs font-medium text-accent-600">Sort</label>
        <select id="cs-history-sort" class="input">
            <option value="newest">Newest</option>
            <option value="oldest">Oldest</option>
        </select>
    </div>
</div>

<div class="mb-3 flex items-center justify-between gap-3">
    <div class="text-sm text-accent-600" id="cs-history-pagination-meta"></div>
    <div class="text-sm text-accent-700" id="cs-history-page-indicator"></div>
    <div class="flex items-center gap-2">
        <button id="cs-history-page-prev" class="btn btn-secondary text-sm">Prev</button>
        <button id="cs-history-page-next" class="btn btn-secondary text-sm">Next</button>
    </div>
</div>

<x-data-table id="cs-history">
    <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
        <tr>
            <th class="px-4 py-3">Code</th>
            <th class="px-4 py-3">Customer</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Updated</th>
        </tr>
    </thead>
    <tbody></tbody>
</x-data-table>
@endsection