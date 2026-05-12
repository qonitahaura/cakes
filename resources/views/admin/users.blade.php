@extends('layouts.dashboard')

@section('dashboard-content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="page-title">User management</h2>
        <p class="mt-1 text-sm text-accent-600">CRUD users and assign roles</p>
    </div>
    <button type="button" id="btn-user-create" class="btn-primary">Add user</button>
</div>

<div class="mb-4 flex flex-wrap items-end gap-4">
    <div>
        <label class="label" for="user-search">Search</label>
        <input id="user-search" class="input max-w-md" type="search" placeholder="Search name or email…" />
    </div>
    <div>
        <label class="label" for="user-role-filter">Role</label>
        <select id="user-role-filter" class="input max-w-xs">
            <option value="">All</option>
            <option value="customer">customer</option>
            <option value="admin">admin</option>
            <option value="baker">baker</option>
            <option value="customer_service">customer_service</option>
        </select>
    </div>
    <div>
        <label class="label" for="user-sort">Sort</label>
        <select id="user-sort" class="input max-w-xs">
            <option value="newest" selected>Newest</option>
            <option value="oldest">Oldest</option>
        </select>
    </div>
</div>

<div class="mb-2 flex items-center justify-between gap-3">
    <div id="user-pagination-meta" class="text-sm text-accent-600"></div>
    <div class="flex items-center gap-2">
        <button type="button" id="user-page-prev" class="btn-ghost">Prev</button>
        <div class="text-sm text-accent-700" id="user-page-indicator">—</div>
        <button type="button" id="user-page-next" class="btn-ghost">Next</button>
    </div>
</div>

<x-data-table id="tbl-users">
    <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
        <tr>
            <th class="px-4 py-3">ID</th>
            <th class="px-4 py-3">Name</th>
            <th class="px-4 py-3">Email</th>
            <th class="px-4 py-3">Roles</th>
            <th class="px-4 py-3 text-right">Actions</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-accent-100 bg-white"></tbody>
</x-data-table>


<div id="modal-user" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-accent-900/50 p-4">
    <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold">User</h3>
            <button type="button" id="modal-user-close" class="btn-ghost text-xl leading-none">&times;</button>
        </div>
        <form id="user-form" class="space-y-3">
            <input type="hidden" id="user-form-id" />
            <div>
                <label class="label" for="uf-name">Name</label>
                <input class="input" id="uf-name" required />
            </div>
            <div>
                <label class="label" for="uf-email">Email</label>
                <input class="input" id="uf-email" type="email" required />
            </div>
            <div>
                <label class="label" for="uf-phone">Phone</label>
                <input class="input" id="uf-phone" />
            </div>
            <div>
                <label class="label" for="uf-address">Address</label>
                <textarea class="input" id="uf-address" rows="2"></textarea>
            </div>
            <div id="uf-role-wrap">
                <label class="label" for="uf-role">Role (new user)</label>
                <select class="input" id="uf-role">
                    <option value="customer">customer</option>
                    <option value="admin">admin</option>
                    <option value="baker">baker</option>
                    <option value="customer_service">customer_service</option>
                </select>
            </div>
            <div>
                <label class="label" for="uf-password">Password</label>
                <input class="input" id="uf-password" type="password" />
                <p class="mt-1 text-xs text-accent-500">Leave blank on edit to keep current password.</p>
            </div>
            <button type="submit" class="btn-primary w-full">Save</button>
        </form>
    </div>
</div>
@endsection