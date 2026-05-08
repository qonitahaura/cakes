@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6">
        <h2 class="page-title">Reports</h2>
        <p class="mt-1 text-sm text-accent-600">Revenue overview and CSV export</p>
    </div>

    <div id="rep-summary" class="card mb-6 text-sm text-accent-800"></div>

    <x-chart title="Revenue (90 days)" id="chart-reports" />

    <div class="card mt-8">
        <h3 class="mb-3 font-semibold text-accent-900">Export orders (CSV)</h3>
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label class="label" for="exp-start">Start</label>
                <input class="input" type="date" id="exp-start" value="{{ now()->subDays(30)->format('Y-m-d') }}" />
            </div>
            <div>
                <label class="label" for="exp-end">End</label>
                <input class="input" type="date" id="exp-end" value="{{ now()->format('Y-m-d') }}" />
            </div>
            <button type="button" id="btn-export-csv" class="btn-primary">Download CSV</button>
        </div>
    </div>
@endsection
