import api from '../api';
import { guard } from '../auth';

function render(rows) {
    const tbody = document.querySelector('#cs-incoming tbody');
    tbody.innerHTML = rows
        .map(
            (o) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2 font-mono text-xs">${o.code}</td>
      <td class="px-4 py-2">${o.user?.name || '—'}</td>
      <td class="px-4 py-2">${Number(o.total_price).toLocaleString()}</td>
      <td class="px-4 py-2 text-sm">${o.created_at}</td>
    </tr>`
        )
        .join('');
}

let state = {
    data: [],
    page: 1,
    lastPage: 1,
    perPage: 10,
    total: 0,
    loading: false,
};

let debounceTimer = null;

function getParams({ page }) {
    const elSearch = document.getElementById('cs-incoming-search');
    const elSort = document.getElementById('cs-incoming-sort');
    const elStatus = document.getElementById('cs-incoming-status');
    const elPickup = document.getElementById('cs-incoming-pickup-date');
    const elPaymentStatus = document.getElementById('cs-incoming-payment-status');

    return {
        search: elSearch?.value || '',
        sort: elSort?.value || 'newest',
        // Note: backend currently hard-filters pending in incoming endpoint;
        // these UI filters will be wired to search-only params for now if backend supports later.
        status: elStatus?.value || '',
        pickup_date: elPickup?.value || '',
        payment_status: elPaymentStatus?.value || '',
        page,
        per_page: state.perPage,
    };
}

function renderPagination() {
    const indicator = document.getElementById('cs-incoming-page-indicator');
    const meta = document.getElementById('cs-incoming-pagination-meta');
    const prev = document.getElementById('cs-incoming-page-prev');
    const next = document.getElementById('cs-incoming-page-next');

    if (meta) meta.textContent = `${state.total} total`;
    if (indicator) indicator.textContent = `Page ${state.page} / ${state.lastPage}`;
    if (prev) prev.disabled = state.page <= 1 || state.loading;
    if (next) next.disabled = state.page >= state.lastPage || state.loading;
}

function renderRows(rows) {
    state.data = rows;
    render(rows);
    renderPagination();
}

async function fetchIncoming(page = 1) {
    if (state.loading) return;
    state.loading = true;
    try {
        const res = await api.get('/cs/orders/incoming', { params: getParams({ page }) });
        renderRows(res.data?.data || []);
        state.page = res.data?.meta?.current_page || page;
        state.lastPage = res.data?.meta?.last_page || 1;
        state.total = res.data?.meta?.total || 0;
    } finally {
        state.loading = false;
    }
}

function scheduleSearch() {
    if (debounceTimer) clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => fetchIncoming(1), 350);
}

export default async function init() {
    await guard('customer_service');
    await fetchIncoming(1);

    document.getElementById('cs-incoming-search')?.addEventListener('input', () => scheduleSearch());
    document.getElementById('cs-incoming-sort')?.addEventListener('change', () => fetchIncoming(1));
    document.getElementById('cs-incoming-status')?.addEventListener('change', () => fetchIncoming(1));
    document.getElementById('cs-incoming-pickup-date')?.addEventListener('change', () => fetchIncoming(1));
    document.getElementById('cs-incoming-payment-status')?.addEventListener('change', () => fetchIncoming(1));

    document.getElementById('cs-incoming-page-prev')?.addEventListener('click', () => {
        if (state.page > 1) fetchIncoming(state.page - 1);
    });
    document.getElementById('cs-incoming-page-next')?.addEventListener('click', () => {
        if (state.page < state.lastPage) fetchIncoming(state.page + 1);
    });
}

