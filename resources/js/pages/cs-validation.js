import api from '../api';
import { guard } from '../auth';

function toast(m, t = 'success') {
    window.CakesAuth.toast(m, t);
}

export default async function init() {

    await guard('customer_service');

    const { data } = await api.get('/cs/orders/incoming');

    const root = document.getElementById('cs-validate');

    const rows = data?.data ?? data ?? [];

    root.innerHTML = rows.map((o) => `

        <div class="rounded-3xl border border-accent-100 bg-white p-6 shadow-sm space-y-4">

            {{-- HEADER --}}
            <div class="flex flex-wrap items-start justify-between gap-2">

                <div>
                    <p class="font-mono text-sm font-semibold">
                        ${o.code}
                    </p>

                    <p class="mt-1 text-sm text-accent-600">
                        Customer : ${o.user?.name || '—'}
                    </p>
                </div>

                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">
                    ${o.status}
                </span>

            </div>

            {{-- PRICE --}}
            <div class="text-sm text-accent-700">
                Total :
                <span class="font-semibold">
                    Rp ${Number(o.total_price).toLocaleString()}
                </span>
            </div>

            {{-- MESSAGE --}}
            <textarea
                id="msg-${o.id}"
                class="input text-sm"
                rows="2"
                placeholder="Message for revisions (optional)"
            ></textarea>

            {{-- BUTTON --}}
            <div class="flex flex-wrap gap-2">

                <button
                    data-approve="${o.id}"
                    class="btn-primary text-sm"
                >
                    Approve
                </button>

                <button
                    data-rev="${o.id}"
                    class="btn-secondary text-sm"
                >
                    Request revisions
                </button>

                <button
                    data-detail="${o.id}"
                    class="rounded-2xl border border-blue-200 px-4 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-50"
                >
                    Detail
                </button>

            </div>

        </div>

    `).join('');

    // =========================
    // CLICK EVENT
    // =========================
    root.addEventListener('click', async (e) => {

        const t = e.target;

        // APPROVE
        if (t.matches('[data-approve]')) {

            await api.post(
                `/cs/orders/${t.dataset.approve}/validate`,
                { action: 'approve' }
            );

            toast('Order approved');

            window.location.reload();
        }

        // REVISIONS
        if (t.matches('[data-rev]')) {

            const id = t.dataset.rev;

            const message =
                document.getElementById(`msg-${id}`)?.value || '';

            await api.post(
                `/cs/orders/${id}/validate`,
                {
                    action: 'request_revisions',
                    message
                }
            );

            toast('Revision requested');

            window.location.reload();
        }

        // DETAIL
        if (t.matches('[data-detail]')) {

            const id = t.dataset.detail;

            const order = rows.find(r => r.id == id);

            if (!order) return;

            openDetailModal(order);
        }

    });

}

// =========================
// DETAIL MODAL
// =========================
function openDetailModal(order) {

    // REMOVE OLD MODAL
    const oldModal = document.getElementById('order-detail-modal');

    if (oldModal) {
        oldModal.remove();
    }

    const modal = document.createElement('div');

    modal.id = 'order-detail-modal';

    modal.className =
        'fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4';

    modal.innerHTML = `

        <div class="w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl overflow-y-auto max-h-[90vh]">

            {{-- HEADER --}}
            <div class="mb-6 flex items-center justify-between">

                <div>
                    <h3 class="text-2xl font-bold">
                        Order Detail
                    </h3>

                    <p class="mt-1 text-sm text-accent-600">
                        ${order.code}
                    </p>
                </div>

                <button
                    id="close-detail-modal"
                    class="text-2xl text-accent-500"
                >
                    &times;
                </button>

            </div>

            {{-- CUSTOMER --}}
            <div class="mb-6 rounded-2xl border border-accent-100 p-5">

                <h4 class="mb-4 text-lg font-semibold">
                    Customer Information
                </h4>

                <div class="grid gap-4 md:grid-cols-2">

                    <div>
                        <p class="text-sm text-accent-500">
                            Name
                        </p>

                        <p class="font-medium">
                            ${order.user?.name || '-'}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-accent-500">
                            Status
                        </p>

                        <p class="font-medium">
                            ${order.status}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-accent-500">
                            Total Payment
                        </p>

                        <p class="font-medium text-orange-600">
                            Rp ${Number(order.total_price).toLocaleString()}
                        </p>
                    </div>

                </div>

            </div>

            {{-- ORDER ITEMS --}}
            <div class="rounded-2xl border border-accent-100 p-5">

                <h4 class="mb-4 text-lg font-semibold">
                    Order Items
                </h4>

                <div class="space-y-4">

                    ${(order.items || []).map(item => `

                        <div class="rounded-2xl bg-accent-50 p-4">

                            <div class="flex items-center justify-between">

                                <h5 class="font-semibold">
                                    ${item.product?.name || '-'}
                                </h5>

                                <span class="text-sm font-medium">
                                    Rp ${Number(item.price || 0).toLocaleString()}
                                </span>

                            </div>

                            <div class="mt-2 text-sm text-accent-600 space-y-1">

                                <p>
                                    Quantity : ${item.quantity || 1}
                                </p>

                                <p>
                                    Notes : ${item.notes || '-'}
                                </p>

                            </div>

                        </div>

                    `).join('')}

                </div>

            </div>

        </div>

    `;

    document.body.appendChild(modal);

    // CLOSE MODAL
    document
        .getElementById('close-detail-modal')
        .addEventListener('click', () => {
            modal.remove();
        });

    // CLICK OUTSIDE
    modal.addEventListener('click', (e) => {

        if (e.target === modal) {
            modal.remove();
        }

    });

}