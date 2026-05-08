@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6">
        <h2 class="page-title">Baker dashboard</h2>
        <p class="mt-1 text-sm text-accent-600">Production overview</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <x-stat-card label="Active production" value="—" value-id="bk-active" />
        <x-stat-card label="Awaiting start (paid)" value="—" value-id="bk-pending" />
        <x-stat-card label="Completed (this list)" value="—" value-id="bk-done" />
    </div>

    <div class="mt-8">
        <h3 class="mb-3 text-lg font-semibold text-accent-900">Next on schedule</h3>
        <div id="bk-urgent" class="grid gap-3 md:grid-cols-2"></div>
    </div>
@endsection
