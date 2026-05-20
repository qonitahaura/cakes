@extends('layouts.dashboard')

@section('dashboard-content')

    <div class="mb-6">
        <h2 class="page-title">Payments</h2>
        <p class="mt-1 text-sm text-accent-600">
            Confirm DP or full payment
        </p>
    </div>

    {{-- SEARCH & FILTER --}}
    <div class="mb-6 flex flex-wrap items-end gap-4">

        {{-- SEARCH --}}
        <div>
            <label
                for="payment-search"
                class="mb-2 block text-sm font-medium text-accent-700"
            >
                Search
            </label>

            <input
                type="text"
                id="payment-search"
                placeholder="Search payment..."
                class="input min-w-[240px]"
            />
        </div>

        {{-- STATUS FILTER --}}
        <div>
            <label
                for="payment-status"
                class="mb-2 block text-sm font-medium text-accent-700"
            >
                Status
            </label>

            <select
                id="payment-status"
                class="input min-w-[200px]"
            >
                <option value="">All Status</option>
                <option value="paid">Paid</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
            </select>
        </div>

        {{-- SORT --}}
        <div>
            <label
                for="payment-sort"
                class="mb-2 block text-sm font-medium text-accent-700"
            >
                Sort
            </label>

            <select
                id="payment-sort"
                class="input min-w-[180px]"
            >
                <option value="newest">Newest</option>
                <option value="oldest">Oldest</option>
                <option value="high">Highest Amount</option>
                <option value="low">Lowest Amount</option>
            </select>
        </div>

    </div>

    {{-- TABLE --}}
    <x-data-table id="cs-payments">

        <thead class="bg-accent-50/80 text-left text-xs font-semibold uppercase tracking-wide text-accent-500">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Order</th>
                <th class="px-4 py-3">Amount</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>

        <tbody>

            {{-- EXAMPLE DATA --}}
            <tr>
                <td class="px-4 py-3">1</td>
                <td class="px-4 py-3">ORD-001</td>
                <td class="px-4 py-3">Rp 150.000</td>
                <td class="px-4 py-3">
                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">
                        Paid
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <button class="text-blue-500">
                        Detail
                    </button>

                    <button class="ml-2 text-green-500">
                        Confirm
                    </button>
                </td>
            </tr>

            <tr>
                <td class="px-4 py-3">2</td>
                <td class="px-4 py-3">ORD-002</td>
                <td class="px-4 py-3">Rp 80.000</td>
                <td class="px-4 py-3">
                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs text-yellow-700">
                        Pending
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <button class="text-blue-500">
                        Detail
                    </button>

                    <button class="ml-2 text-green-500">
                        Confirm
                    </button>
                </td>
            </tr>

        </tbody>

    </x-data-table>

    {{-- SEARCH SCRIPT --}}
    <script>

        const searchInput = document.getElementById('payment-search');

        const tableBody = document.querySelector('#cs-payments tbody');

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