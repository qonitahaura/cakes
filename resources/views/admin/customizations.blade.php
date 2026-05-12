@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="page-title">Cake customizations</h2>
            <p class="mt-1 text-sm text-accent-600">Flavors, sizes, toppings, text, etc.</p>
        </div>
        <button type="button" id="btn-cz-add" class="btn-primary">Add customization</button>
    </div>

    <x-data-table id="tbl-customizations">
        <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Options</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </x-data-table>

    <div id="modal-cz" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-accent-900/50 p-4">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold">Customization</h3>
                <button type="button" id="modal-cz-close" class="btn-ghost text-xl">&times;</button>
            </div>
            <form id="cz-form" class="space-y-3">
                <input type="hidden" id="cz-id" />
                <div>
                    <label class="label" for="cz-name">Name</label>
                    <input class="input" id="cz-name" required />
                </div>
                <div>
                    <label class="label" for="cz-type">Type</label>
                    <select class="input" id="cz-type">
                        <option value="select">select</option>
                        <option value="multi_select">multi_select</option>
                        <option value="text">text</option>
                    </select>
                </div>
                <div>
                    <label class="label" for="cz-options">Options (one per line: name|extra_price)</label>
                    <textarea class="input font-mono text-sm" id="cz-options" rows="6" placeholder="Chocolate|5000&#10;Vanilla|0"></textarea>
                </div>
                <button type="submit" class="btn-primary w-full">Save</button>
            </form>
        </div>
    </div>
@endsection
