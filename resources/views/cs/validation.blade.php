@extends('layouts.dashboard')

@section('dashboard-content')

    <div class="mb-6">
        <h2 class="page-title">Order validation</h2>

        <p class="mt-1 text-sm text-accent-600">
            Approve or request changes
        </p>
    </div>

    {{-- SEARCH --}}
    <div class="mb-6">
        <input
            type="text"
            id="order-search"
            placeholder="Search order..."
            class="input max-w-xs"
        />
    </div>

    {{-- ORDER LIST --}}
    <div
        id="cs-validate"
        class="grid gap-4 md:grid-cols-2"
    >

        {{-- CARD --}}
        <div class="rounded-3xl border border-accent-100 bg-white p-6 shadow-sm">

            {{-- TOP --}}
            <div class="mb-5 flex items-start justify-between">

                <div>
                    <h3 class="font-mono text-lg font-semibold">
                        CAKE-PENDING-1
                    </h3>

                    <p class="mt-2 text-sm text-accent-600">
                        Customer : Tira
                    </p>
                </div>

                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                    Pending
                </span>

            </div>

            {{-- TOTAL --}}
            <div class="mb-5">
                <p class="text-sm text-accent-700">
                    Total :
                    <span class="font-semibold">
                        Rp 192.000
                    </span>
                </p>
            </div>

            {{-- MESSAGE --}}
            <textarea
                class="input w-full"
                rows="3"
                placeholder="Message for revisions (optional)"
            ></textarea>

            {{-- BUTTON --}}
            <div class="mt-5 flex flex-wrap gap-3">

                <button class="btn-primary">
                    Approve
                </button>

                <button class="btn-secondary">
                    Request revisions
                </button>

                <button
                    type="button"
                    id="open-detail"
                    class="rounded-2xl border border-blue-200 px-5 py-3 text-sm font-medium text-blue-600 transition hover:bg-blue-50"
                >
                    Detail
                </button>

            </div>

        </div>

    </div>

    {{-- DETAIL MODAL --}}
    <div
        id="detail-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4"
    >

        <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl">

            {{-- TOP --}}
            <div class="mb-6 flex items-center justify-between">

                <div>
                    <h3 class="text-2xl font-bold">
                        CAKE-PENDING-1
                    </h3>

                    <p class="mt-1 text-sm text-accent-500">
                        Order detail
                    </p>
                </div>

                <button
                    id="close-detail"
                    class="text-3xl leading-none text-accent-400 hover:text-accent-700"
                >
                    &times;
                </button>

            </div>

            {{-- CUSTOMER --}}
            <div class="mb-6 rounded-2xl border border-accent-100 p-5">

                <div class="grid gap-4 md:grid-cols-2">

                    <div>
                        <p class="text-sm text-accent-500">
                            Customer
                        </p>

                        <p class="font-semibold">
                            Tira
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-accent-500">
                            Status
                        </p>

                        <p class="font-semibold text-yellow-600">
                            Pending
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-accent-500">
                            Payment
                        </p>

                        <p class="font-semibold text-green-600">
                            DP Paid
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-accent-500">
                            Pickup Date
                        </p>

                        <p class="font-semibold">
                            20 Mei 2026
                        </p>
                    </div>

                </div>

            </div>

            {{-- ITEM --}}
            <div class="mb-6 rounded-2xl border border-accent-100 p-5">

                <div class="mb-4 flex items-center justify-between">

                    <h4 class="text-lg font-semibold">
                        Chocolate Birthday Cake
                    </h4>

                    <span class="font-semibold">
                        Rp 150.000
                    </span>

                </div>

                <div class="space-y-2 text-sm text-accent-600">

                    <p>
                        Size : Medium
                    </p>

                    <p>
                        Flavor : Chocolate
                    </p>

                    <p>
                        Topping : Oreo + Kitkat
                    </p>

                    <p>
                        Custom text : Happy Birthday Tira
                    </p>

                </div>

            </div>

            {{-- TOTAL --}}
            <div class="mb-6 flex items-center justify-between rounded-2xl bg-orange-50 p-5">

                <span class="text-lg font-semibold">
                    Total Payment
                </span>

                <span class="text-2xl font-bold text-orange-600">
                    Rp 192.000
                </span>

            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-3">

                <button
                    id="close-detail-btn"
                    class="btn-secondary"
                >
                    Close
                </button>

                <button class="btn-primary">
                    Approve Order
                </button>

            </div>

        </div>

    </div>

    {{-- SCRIPT --}}
    <script>

        const openBtn = document.getElementById('open-detail');

        const modal = document.getElementById('detail-modal');

        const closeBtn = document.getElementById('close-detail');

        const closeBtn2 = document.getElementById('close-detail-btn');

        openBtn.addEventListener('click', () => {

            modal.classList.remove('hidden');

            modal.classList.add('flex');

        });

        function closeModal() {

            modal.classList.add('hidden');

            modal.classList.remove('flex');

        }

        closeBtn.addEventListener('click', closeModal);

        closeBtn2.addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {

            if (e.target === modal) {

                closeModal();

            }

        });

    </script>

@endsection