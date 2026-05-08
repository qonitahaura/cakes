@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6">
        <h2 class="page-title">Orders</h2>
        <p class="mt-1 text-sm text-accent-600">View and filter all orders</p>
    </div>

    <div class="mb-4 flex flex-wrap items-center gap-3">
        <label class="text-sm font-medium text-accent-700" for="order-filter">Status</label>
        <select id="order-filter" class="input max-w-xs">
            <option value="">All</option>
            <option value="pending">pending</option>
            <option value="waiting_payment">waiting_payment</option>
            <option value="paid">paid</option>
            <option value="processing">processing</option>
            <option value="shipped">shipped</option>
            <option value="completed">completed</option>
            <option value="cancelled">cancelled</option>
        </select>
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
