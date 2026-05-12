@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6">
        <h2 class="page-title">Customer service</h2>
        <p class="mt-1 text-sm text-accent-600">Incoming orders and payments snapshot</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <x-stat-card label="Incoming (pending)" value="—" value-id="cs-incoming-count" />
        <x-stat-card label="Unpaid payments" value="—" value-id="cs-unpaid" />
        <x-stat-card label="Paid payments" value="—" value-id="cs-paid" />
    </div>
@endsection
