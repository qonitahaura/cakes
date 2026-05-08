@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6">
        <h2 class="page-title">Order history</h2>
        <p class="mt-1 text-sm text-accent-600">Completed and closed orders</p>
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
