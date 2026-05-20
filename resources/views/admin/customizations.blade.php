@extends('layouts.dashboard')

@section('dashboard-content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="page-title">Cake customizations</h2>
            <p class="mt-1 text-sm text-accent-600">
                Flavors, sizes, toppings, text, etc.
            </p>
        </div>

        <button
            type="button"
            id="btn-cz-add"
            class="btn-primary"
        >
            Add customization
        </button>
    </div>

    {{-- SEARCH & SORT --}}
    <div class="mb-6 flex flex-wrap items-end gap-4">

        {{-- SEARCH --}}
        <div>
            <label
                for="custom-search"
                class="mb-2 block text-sm font-medium text-accent-700"
            >
                Search
            </label>

            <input
                type="text"
                id="custom-search"
                placeholder="Search customization..."
                class="input min-w-[240px]"
            />
        </div>

        {{-- TYPE FILTER --}}
        <div>
            <label
                for="custom-type-filter"
                class="mb-2 block text-sm font-medium text-accent-700"
            >
                Type
            </label>

            <select
                id="custom-type-filter"
                class="input min-w-[200px]"
            >
                <option value="">All</option>
                <option value="select">Select</option>
                <option value="multi_select">Multi Select</option>
                <option value="text">Text</option>
            </select>
        </div>

        {{-- SORT --}}
        <div>
            <label
                for="custom-sort"
                class="mb-2 block text-sm font-medium text-accent-700"
            >
                Sort
            </label>

            <select
                id="custom-sort"
                class="input min-w-[180px]"
            >
                <option value="newest">Newest</option>
                <option value="oldest">Oldest</option>
                <option value="az">A - Z</option>
                <option value="za">Z - A</option>
            </select>
        </div>

    </div>

    {{-- TABLE --}}
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

        <tbody>

            {{-- EXAMPLE DATA --}}
            <tr>
                <td class="px-4 py-3">1</td>
                <td class="px-4 py-3">Cake Flavor</td>
                <td class="px-4 py-3">select</td>
                <td class="px-4 py-3">Chocolate, Vanilla</td>
                <td class="px-4 py-3 text-right">
                    <button class="text-orange-500">Edit</button>
                    <button class="ml-2 text-red-500">Delete</button>
                </td>
            </tr>

            <tr>
                <td class="px-4 py-3">2</td>
                <td class="px-4 py-3">Extra Topping</td>
                <td class="px-4 py-3">multi_select</td>
                <td class="px-4 py-3">Oreo, Cheese, Choco Chip</td>
                <td class="px-4 py-3 text-right">
                    <button class="text-orange-500">Edit</button>
                    <button class="ml-2 text-red-500">Delete</button>
                </td>
            </tr>

        </tbody>

    </x-data-table>

    {{-- MODAL --}}
    <div
        id="modal-cz"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-accent-900/50 p-4"
    >

        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl">

            <div class="mb-4 flex items-center justify-between">

                <h3 class="text-lg font-semibold">
                    Customization
                </h3>

                <button
                    type="button"
                    id="modal-cz-close"
                    class="btn-ghost text-xl"
                >
                    &times;
                </button>

            </div>

            <form id="cz-form" class="space-y-3">

                <input type="hidden" id="cz-id" />

                <div>
                    <label class="label" for="cz-name">
                        Name
                    </label>

                    <input
                        class="input"
                        id="cz-name"
                        required
                    />
                </div>

                <div>
                    <label class="label" for="cz-type">
                        Type
                    </label>

                    <select
                        class="input"
                        id="cz-type"
                    >
                        <option value="select">select</option>
                        <option value="multi_select">multi_select</option>
                        <option value="text">text</option>
                    </select>
                </div>

                <div>
                    <label class="label" for="cz-options">
                        Options (one per line: name|extra_price)
                    </label>

                    <textarea
                        class="input font-mono text-sm"
                        id="cz-options"
                        rows="6"
                        placeholder="Chocolate|5000&#10;Vanilla|0"
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="btn-primary w-full"
                >
                    Save
                </button>

            </form>

        </div>
    </div>

    {{-- SEARCH SCRIPT --}}
    <script>

        const searchInput = document.getElementById('custom-search');

        const tableBody = document.querySelector('#tbl-customizations tbody');

        searchInput.addEventListener('keyup', function () {

            const keyword = this.value.toLowerCase();

            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(row => {

                const text = row.innerText.toLowerCase();

                if (text.includes(keyword)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }

            });

        });

    </script>

@endsection