@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6">
        <h2 class="page-title">Production schedule</h2>
        <p class="mt-1 text-sm text-accent-600">Sorted by pickup / delivery deadline</p>
    </div>

    <div id="bk-schedule" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3"></div>
@endsection
