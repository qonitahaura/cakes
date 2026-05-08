@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6">
        <h2 class="page-title">Pickup schedule</h2>
        <p class="mt-1 text-sm text-accent-600">Orders grouped by pickup date</p>
    </div>

    <div id="cs-pickup" class="grid gap-4 md:grid-cols-2"></div>
@endsection
