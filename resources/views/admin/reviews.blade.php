@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6">
        <h2 class="page-title">Reviews</h2>
        <p class="mt-1 text-sm text-accent-600">Moderate customer reviews</p>
    </div>

    <x-data-table id="tbl-reviews">
        <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Product</th>
                <th class="px-4 py-3">User</th>
                <th class="px-4 py-3">Rating</th>
                <th class="px-4 py-3">Comment</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </x-data-table>
@endsection
