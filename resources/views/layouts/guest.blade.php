@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen flex-col justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-md">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-500 text-2xl text-white shadow-lg shadow-primary-500/30">🎂</div>
                <h1 class="text-2xl font-bold text-accent-900">Cakes</h1>
                <p class="mt-1 text-sm text-accent-600">Sign in to your dashboard</p>
            </div>
            <div class="card">
                {{ $slot }}
            </div>
        </div>
    </div>
@endsection
