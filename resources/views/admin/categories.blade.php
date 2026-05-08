@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="page-title">Categories</h2>
            <p class="mt-1 text-sm text-accent-600">Manage cake categories</p>
        </div>
        <button type="button" id="btn-cat-add" class="btn-primary">Add category</button>
    </div>

    <x-data-table id="tbl-categories">
        <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Slug</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </x-data-table>

    <div id="modal-cat" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-accent-900/50 p-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold">Category</h3>
                <button type="button" id="modal-cat-close" class="btn-ghost text-xl">&times;</button>
            </div>
            <form id="cat-form" class="space-y-3">
                <input type="hidden" id="cat-id" />
                <div>
                    <label class="label" for="cat-name">Name</label>
                    <input class="input" id="cat-name" required />
                </div>
                <div>
                    <label class="label" for="cat-slug">Slug (optional)</label>
                    <input class="input" id="cat-slug" />
                </div>
                <button type="submit" class="btn-primary w-full">Save</button>
            </form>
        </div>
    </div>
@endsection
