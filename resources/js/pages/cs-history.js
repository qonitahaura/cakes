import api from '../api';
import { guard } from '../auth';

let state = {
    orders: [],
    page: 1,
    lastPage: 1,
    perPage: 10,
    total: 0,
    loading: false,
};

function renderTable() {
    const tbody = document.querySelector('#cs-history tbody');
    if (!tbody) return;

    tbody.innerHTML = state.orders
        .map(
            (o) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2 font-mono text-xs">${o.code}</td>
      <td class="px-4 py-2">${o.user?.name || '—'}</td>
      <td class="px-4 py-2">${o.status}</td>
      <td class="px-4 py-2 text-sm">${o.updated_at}</td>
    </tr>`
        )
        .join('');
}

function renderPagination() {
    const indicator = document.getElementById('cs-history-page-indicator');
    const meta = document.getElementById('cs-history-pagination-meta');
    const prev = document.getElementById('cs-history-page-prev');
    const next = document.getElementById('cs-history-page-next');

    if (meta) meta.textContent = `${state.total} total`;
    if (indicator) indicator.textContent = `Page ${state.page} / ${state.lastPage}`;
    if (prev) prev.disabled = state.page <= 1 || state.loading;
    if (next) next.disabled = state.page >= state.lastPage || state.loading;
}

function getParams({ page }) {
    const elSearch = document.getElementById('cs-history-search');
    const elStatus = document.getElementById('cs-history-status');
    const elSort = document.getElementById('cs-history-sort');

    return {
        search: elSearch?.value || '',
        status: elStatus?.value || '',
        sort: elSort?.value || 'newest',
        page,
        per_page: state.perPage,
    };
}

async function fetchHistory(page = 1) {
    if (state.loading) return;
    state.loading = true;
    try {
        const res = await api.get('/cs/orders/history', { params: getParams({ page }) });
        state.orders = res.data?.data || [];
        state.page = res.data?.meta?.current_page || page;
        state.lastPage = res.data?.meta?.last_page || 1;
        state.total = res.data?.meta?.total || 0;
        renderTable();
        renderPagination();
    } finally {
        state.loading = false;
    }
}

export default async function init() {
    await guard('customer_service');

    await fetchHistory(1);

    const debouncer = { t: null };
    document.getElementById('cs-history-search')?.addEventListener('input', () => {
        if (debouncer.t) clearTimeout(debouncer.t);
        debouncer.t = setTimeout(() => fetchHistory(1), 350);
    });

    document.getElementById('cs-history-status')?.addEventListener('change', () => fetchHistory(1));
    document.getElementById('cs-history-sort')?.addEventListener('change', () => fetchHistory(1));

    document.getElementById('cs-history-page-prev')?.addEventListener('click', () => {
        if (state.page > 1) fetchHistory(state.page - 1);
    });

    document.getElementById('cs-history-page-next')?.addEventListener('click', () => {
        if (state.page < state.lastPage) fetchHistory(state.page + 1);
    });
}

