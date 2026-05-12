import api from '../api';
import { guard } from '../auth';

function badge(status) {
    const cls =
        status === 'paid'
            ? 'bg-emerald-100 text-emerald-800'
            : status === 'unpaid'
              ? 'bg-amber-100 text-amber-800'
              : 'bg-red-100 text-red-800';
    return `<span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold ${cls}">${status}</span>`;
}

let state = {
    payments: [],
    page: 1,
    lastPage: 1,
    perPage: 10,
    total: 0,
    loading: false,
};

let debounceTimer = null;

function getParams({ page }) {
    return {
        search: document.getElementById('pay-search')?.value || '',
        payment_kind: document.getElementById('pay-kind')?.value || '',
        paid_status: document.getElementById('pay-status')?.value || '',
        sort: document.getElementById('pay-sort')?.value || 'newest',
        page,
        per_page: state.perPage,
    };
}

function renderTable() {
    const tbody = document.querySelector('#tbl-payments tbody');
    if (!tbody) return;

    tbody.innerHTML = state.payments
        .map(
            (p) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2">${p.id}</td>
      <td class="px-4 py-2 font-mono text-xs">${p.order?.code || p.order_id}</td>
      <td class="px-4 py-2">${Number(p.amount).toLocaleString()}</td>
      <td class="px-4 py-2">${badge(p.payment_status)}</td>
      <td class="px-4 py-2 text-sm text-accent-600">${p.paid_at || '—'}</td>
    </tr>`
        )
        .join('');

    const meta = document.getElementById('payments-pagination-meta');
    const indicator = document.getElementById('payments-page-indicator');
    if (meta) meta.textContent = `${state.total} total`;
    if (indicator) indicator.textContent = `Page ${state.page} / ${state.lastPage}`;

    const prevBtn = document.getElementById('payments-page-prev');
    const nextBtn = document.getElementById('payments-page-next');
    if (prevBtn) prevBtn.disabled = state.page <= 1;
    if (nextBtn) nextBtn.disabled = state.page >= state.lastPage;
}

async function fetchPayments(page = 1) {
    if (state.loading) return;
    state.loading = true;
    try {
        const res = await api.get('/admin/payments', { params: getParams({ page }) });
        state.payments = res.data?.data || [];
        state.page = res.data?.meta?.current_page || page;
        state.lastPage = res.data?.meta?.last_page || 1;
        state.total = res.data?.meta?.total || 0;
        renderTable();
    } finally {
        state.loading = false;
    }
}

function scheduleFetch() {
    if (debounceTimer) clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => fetchPayments(1), 350);
}

export default async function init() {
    await guard('admin');

    await fetchPayments(1);

    document.getElementById('pay-search')?.addEventListener('input', () => scheduleFetch());
    document.getElementById('pay-kind')?.addEventListener('change', () => fetchPayments(1));
    document.getElementById('pay-status')?.addEventListener('change', () => fetchPayments(1));
    document.getElementById('pay-sort')?.addEventListener('change', () => fetchPayments(1));

    document.getElementById('payments-page-prev')?.addEventListener('click', () => {
        if (state.page > 1) fetchPayments(state.page - 1);
    });

    document.getElementById('payments-page-next')?.addEventListener('click', () => {
        if (state.page < state.lastPage) fetchPayments(state.page + 1);
    });
}
