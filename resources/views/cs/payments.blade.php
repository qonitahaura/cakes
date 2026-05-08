@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6">
        <h2 class="page-title">Payments</h2>
        <p class="mt-1 text-sm text-accent-600">Confirm DP or full payment</p>
    </div>

    <x-data-table id="cs-payments">
        <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Order</th>
                <th class="px-4 py-3">Amount</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </x-data-table>
@endsection
