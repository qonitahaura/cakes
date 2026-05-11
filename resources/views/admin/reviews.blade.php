@extends('layouts.dashboard')

@section('dashboard-content')
<div class="mb-6">
    <h2 class="page-title">Reviews</h2>
    <p class="mt-1 text-sm text-accent-600">Moderate customer reviews</p>
</div>

<div class="mb-4 flex flex-wrap items-end gap-4">
    <div>
        <label class="label" for="rev-search">Search</label>
        <input id="rev-search" class="input max-w-md" type="search" placeholder="Customer name…" />
    </div>

    <div>
        <label class="label" for="rev-rating">Rating</label>
        <select id="rev-rating" class="input max-w-xs">
            <option value="">All</option>
            <option value="5">5 ★</option>
            <option value="4">4 ★</option>
            <option value="3">3 ★</option>
            <option value="2">2 ★</option>
            <option value="1">1 ★</option>
        </select>
    </div>

    <div>
        <label class="label" for="rev-product">Product</label>
        <input id="rev-product" class="input max-w-xs" type="number" placeholder="Product ID" />
    </div>

    <div>
        <label class="label" for="rev-sort">Sort</label>
        <select id="rev-sort" class="input max-w-xs">
            <option value="newest" selected>Newest</option>
            <option value="oldest">Oldest</option>
        </select>
    </div>
</div>

<div class="mb-2 flex items-center justify-between gap-3">
    <div id="reviews-pagination-meta" class="text-sm text-accent-600"></div>
    <div class="flex items-center gap-2">
        <button type="button" id="reviews-page-prev" class="btn-ghost">Prev</button>
        <div class="text-sm text-accent-700" id="reviews-page-indicator">—</div>
        <button type="button" id="reviews-page-next" class="btn-ghost">Next</button>
    </div>
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