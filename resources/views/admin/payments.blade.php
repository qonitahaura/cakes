@extends('layouts.dashboard')

@section('dashboard-content')
<div class="mb-6">
    <h2 class="page-title">Payments</h2>
    <p class="mt-1 text-sm text-accent-600">DP and full payment history</p>
</div>

<div class="mb-4 flex flex-wrap items-end gap-4">
    <div>
        <label class="label" for="pay-search">Search</label>
        <input id="pay-search" class="input max-w-md" type="search" placeholder="Order id or customer…" />
    </div>

    <div>
        <label class="label" for="pay-kind">Payment</label>
        <select id="pay-kind" class="input max-w-xs">
            <option value="">All</option>
            <option value="dp">DP</option>
            <option value="full">Full</option>
        </select>
    </div>

    <div>
        <label class="label" for="pay-status">Paid status</label>
        <select id="pay-status" class="input max-w-xs">
            <option value="">All</option>
            <option value="paid">Paid</option>
            <option value="unpaid">Unpaid</option>
        </select>
    </div>

    <div>
        <label class="label" for="pay-sort">Sort</label>
        <select id="pay-sort" class="input max-w-xs">
            <option value="newest" selected>Newest</option>
            <option value="oldest">Oldest</option>
        </select>
    </div>
</div>

<div class="mb-2 flex items-center justify-between gap-3">
    <div id="payments-pagination-meta" class="text-sm text-accent-600"></div>
    <div class="flex items-center gap-2">
        <button type="button" id="payments-page-prev" class="btn-ghost">Prev</button>
        <div class="text-sm text-accent-700" id="payments-page-indicator">—</div>
        <button type="button" id="payments-page-next" class="btn-ghost">Next</button>
    </div>
</div>

<x-data-table id="tbl-payments">

    <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
        <tr>
            <th class="px-4 py-3">ID</th>
            <th class="px-4 py-3">Order</th>
            <th class="px-4 py-3">Amount</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Paid at</th>
        </tr>
    </thead>
    <tbody></tbody>
</x-data-table>
@endsection