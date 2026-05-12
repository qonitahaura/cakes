import api from '../api';
import { guard } from '../auth';

function toast(m, t = 'success') {
    window.CakesAuth.toast(m, t);
}

let state = {
    page: 1,
    lastPage: 1,
    perPage: 10,
    total: 0,
    loading: false,
    debounceTimer: null,
};

function getParams(page) {
    const elSearch = document.getElementById('bk-orders-search');
    const elSort = document.getElementById('bk-orders-sort');
    const elStatus = document.getElementById('bk-orders-status');
    const elDeadline = document.getElementById('bk-orders-deadline');

    return {
        page,
        per_page: state.perPage,
        search: elSearch?.value || '',
        sort: elSort?.value || 'newest',
        status: elStatus?.value || '',
        pickup_deadline: elDeadline?.value || '',
    };
}

function renderPagination() {
    const indicator = document.getElementById('bk-orders-page-indicator');
    const meta = document.getElementById('bk-orders-pagination-meta');
    const prev = document.getElementById('bk-orders-page-prev');
    const next = document.getElementById('bk-orders-page-next');

    if (meta) meta.textContent = `${state.total} total`;
    if (indicator) indicator.textContent = `Page ${state.page} / ${state.lastPage}`;
    if (prev) prev.disabled = state.page <= 1 || state.loading;
    if (next) next.disabled = state.page >= state.lastPage || state.loading;
}

function renderRows(rows) {
    const tbody = document.querySelector('#bk-orders tbody');
    if (!tbody) return;

    tbody.innerHTML = rows
        .map(
            (o) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2 font-mono text-xs">${o.code}</td>
      <td class="px-4 py-2">${o.user?.name || '—'}</td>
      <td class="px-4 py-2 text-sm">${o.note || '—'}</td>
      <td class="px-4 py-2">${o.status}</td>
      <td class="px-4 py-2 text-right space-x-2">
        <button data-start="${o.id}" class="text-sm text-primary-600 hover:underline">Start</button>
        <button data-done="${o.id}" class="text-sm text-green-600 hover:underline">Complete</button>
      </td>
    </tr>`
        )
        .join('');
}

async function fetchOrders(page = 1) {
    if (state.loading) return;
    state.loading = true;

    try {
        const res = await api.get('/baker/orders', { params: getParams(page) });
        const rows = res.data?.data || res.data || [];

        renderRows(rows);
        state.page = res.data?.meta?.current_page || page;
        state.lastPage = res.data?.meta?.last_page || 1;
        state.total = res.data?.meta?.total || 0;
        renderPagination();
    } finally {
        state.loading = false;
        renderPagination();
    }
}

function scheduleSearch() {
    if (state.debounceTimer) clearTimeout(state.debounceTimer);
    state.debounceTimer = setTimeout(() => fetchOrders(1), 350);
}

export default async function init() {
    await guard('baker');

    const tbody = document.querySelector('#bk-orders tbody');
    tbody?.addEventListener('click', async (e) => {
        const t = e.target;
        try {
            if (t.matches('[data-start]')) {
                await api.put(`/baker/orders/${t.dataset.start}/production-status`, { status: 'processing' });
                toast('Marked in progress');
                await fetchOrders(state.page);
            }
            if (t.matches('[data-done]')) {
                await api.put(`/baker/orders/${t.dataset.done}/production-status`, { status: 'completed' });
                toast('Completed');
                await fetchOrders(state.page);
            }
        } catch (err) {
            toast(err.response?.data?.message || 'Failed', 'error');
        }
    });

    document.getElementById('bk-orders-search')?.addEventListener('input', () => scheduleSearch());
    document.getElementById('bk-orders-sort')?.addEventListener('change', () => fetchOrders(1));
    document.getElementById('bk-orders-status')?.addEventListener('change', () => fetchOrders(1));
    document.getElementById('bk-orders-deadline')?.addEventListener('change', () => fetchOrders(1));

    document.getElementById('bk-orders-page-prev')?.addEventListener('click', () => {
        if (state.page > 1) fetchOrders(state.page - 1);
    });
    document.getElementById('bk-orders-page-next')?.addEventListener('click', () => {
        if (state.page < state.lastPage) fetchOrders(state.page + 1);
    });

    renderPagination();
    await fetchOrders(1);
}

