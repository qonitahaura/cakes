@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6">
        <h2 class="page-title">Dashboard</h2>
        <p class="mt-1 text-sm text-accent-600">Overview of your bakery</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-stat-card label="Total users" value="—" value-id="stat-users" />
        <x-stat-card label="Total products" value="—" value-id="stat-products" />
        <x-stat-card label="Total orders" value="—" value-id="stat-orders" />
        <x-stat-card label="Total revenue" value="—" value-id="stat-revenue" />
        <x-stat-card label="Pending orders" value="—" value-id="stat-pending" />
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <x-chart title="Revenue (30 days)" id="chart-revenue" />
        <x-chart title="Orders by status" id="chart-status" />
    </div>

    <div class="mt-8">
        <h3 class="mb-3 text-lg font-semibold text-accent-900">Recent orders</h3>
        <x-data-table id="tbl-recent-orders">
            <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
                <tr>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-accent-100 bg-white"></tbody>
        </x-data-table>
    </div>
@endsection
