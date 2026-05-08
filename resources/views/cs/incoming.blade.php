@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6">
        <h2 class="page-title">Incoming orders</h2>
        <p class="mt-1 text-sm text-accent-600">Refreshes every 15 seconds</p>
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
