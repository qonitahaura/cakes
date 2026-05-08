@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="page-title">Products</h2>
            <p class="mt-1 text-sm text-accent-600">Manage cakes and assign customizations</p>
        </div>
        <button type="button" id="btn-prod-add" class="btn-primary">Add product</button>
    </div>

    <x-data-table id="tbl-products">
        <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3">Price</th>
                <th class="px-4 py-3">Available</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </x-data-table>

    <div id="modal-prod" class="fixed inset-0 z-50 hidden items-center justify-center bg-accent-900/50 p-4">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold">Product</h3>
                <button type="button" id="modal-prod-close" class="btn-ghost text-xl">&times;</button>
            </div>
            <form id="prod-form" class="space-y-3">
                <input type="hidden" id="prod-id" />
                <div>
                    <label class="label" for="pf-name">Name</label>
                    <input class="input" id="pf-name" required />
                </div>
                <div>
                    <label class="label" for="pf-slug">Slug</label>
                    <input class="input" id="pf-slug" />
                </div>
                <div>
                    <label class="label" for="pf-desc">Description</label>
                    <textarea class="input" id="pf-desc" rows="2"></textarea>
                </div>
                <div>
                    <label class="label" for="pf-price">Base price</label>
                    <input class="input" id="pf-price" type="number" step="0.01" required />
                </div>
                <div>
                    <label class="label" for="pf-category">Category</label>
                    <select class="input" id="pf-category"></select>
                </div>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" id="pf-available" checked /> Available</label>
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" id="pf-custom" /> Custom cake</label>
                </div>
                <div>
                    <label class="label" for="pf-image">Image</label>
                    <input class="input" id="pf-image" type="file" accept="image/*" />
                </div>
                <button type="submit" class="btn-primary w-full">Save</button>
            </form>
        </div>
    </div>

    <div id="modal-cust" class="fixed inset-0 z-50 hidden items-center justify-center bg-accent-900/50 p-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold">Product customizations</h3>
                <button type="button" id="modal-cust-close" class="btn-ghost text-xl">&times;</button>
            </div>
            <input type="hidden" id="cust-prod-id" />
            <div id="cust-checkboxes" class="max-h-64 space-y-1 overflow-y-auto border border-accent-100 rounded-xl p-3"></div>
            <button type="button" id="cust-save" class="btn-primary mt-4 w-full">Save links</button>
        </div>
    </div>
@endsection
