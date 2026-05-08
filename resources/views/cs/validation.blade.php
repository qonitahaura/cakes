@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6">
        <h2 class="page-title">Order validation</h2>
        <p class="mt-1 text-sm text-accent-600">Approve or request changes</p>
    </div>

    <div id="cs-validate" class="grid gap-4 md:grid-cols-2"></div>
@endsection
