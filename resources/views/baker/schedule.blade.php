@extends('layouts.dashboard')

@section('dashboard-content')
<div class="mb-6">
    <h2 class="page-title">Production schedule</h2>
    <p class="mt-1 text-sm text-accent-600">Sorted by pickup / delivery deadline</p>
</div>

<div class="mb-4 grid gap-3 md:grid-cols-5">
    <div class="md:col-span-3">
        <label class="mb-1 block text-xs font-medium text-accent-600">Search</label>
        <input id="bk-schedule-search" type="text" class="input" placeholder="Order code or customer name" />
    </div>

    <div>
        <label class="mb-1 block text-xs font-medium text-accent-600">Fulfillment</label>
        <select id="bk-schedule-fulfillment" class="input">
            <option value="">All</option>
            <option value="pickup">Pickup</option>
            <option value="delivery">Delivery</option>
        </select>
    </div>


    <div>
        <label class="mb-1 block text-xs font-medium text-accent-600">Date</label>
        <input id="bk-schedule-date" type="date" class="input" />
    </div>
</div>

<div class="mb-3 flex items-center justify-between gap-3">
    <div class="text-sm text-accent-600" id="bk-schedule-pagination-meta"></div>
    <div class="text-sm text-accent-700" id="bk-schedule-page-indicator"></div>
    <div class="flex items-center gap-2">
        <button id="bk-schedule-page-prev" class="btn btn-secondary text-sm">Prev</button>
        <button id="bk-schedule-page-next" class="btn btn-secondary text-sm">Next</button>
    </div>
</div>

<div id="bk-schedule" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3"></div>
@endsection