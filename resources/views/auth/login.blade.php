@extends('layouts.app', ['title' => 'Login', 'page' => 'login'])

@section('content')
<div class="flex min-h-screen flex-col justify-center px-4 py-12 sm:px-6 lg:px-8">
    <div class="mx-auto w-full max-w-md">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-500 text-2xl text-white shadow-lg shadow-primary-500/30">🎂</div>
            <h1 class="text-2xl font-bold text-accent-900">Cika</h1>
            <p class="mt-1 text-sm text-accent-600">Sign in to your dashboard</p>
        </div>
        <div class="card">
            <form id="cakes-login-form" class="space-y-4">
                @csrf
                <div>
                    <label class="label" for="email">Email</label>
                    <input class="input" id="email" name="email" type="email" autocomplete="username" required />
                </div>
                <div>
                    <label class="label" for="password">Password</label>
                    <input class="input" id="password" name="password" type="password" autocomplete="current-password" required />
                </div>
                <p id="cakes-login-error" class="hidden text-sm text-red-600"></p>
                <button type="submit" class="btn-primary w-full">Sign in</button>
            </form>
        </div>
    </div>
</div>

@vite(['resources/js/login.js'])
@endsection