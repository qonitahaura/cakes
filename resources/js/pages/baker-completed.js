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
};

function getParams(page) {
    const elSearch = document.getElementById('bk-completed-search');
    const elSort = document.getElementById('bk-completed-sort');
    const elDate = document.getElementById('bk-completed-date');

    return {
        page,
        per_page: state.perPage,
        search: elSearch?.value || '',
        // index endpoint supports sort=deadline|newest only.
        sort: 'newest',
        completion_status: 'completed',
        pickup_date: elDate?.value || '',

    };
}

function renderPagination() {
    const indicator = document.getElementById('bk-completed-page-indicator');
    const meta = document.getElementById('bk-completed-pagination-meta');
    const prev = document.getElementById('bk-completed-page-prev');
    const next = document.getElementById('bk-completed-page-next');

    if (meta) meta.textContent = `${state.total} total`;
    if (indicator) indicator.textContent = `Page ${state.page} / ${state.lastPage}`;
    if (prev) prev.disabled = state.page <= 1 || state.loading;
    if (next) next.disabled = state.page >= state.lastPage || state.loading;
}

function renderRows(rows) {
    const tbody = document.querySelector('#bk-done tbody');
    if (!tbody) return;

    tbody.innerHTML = rows
        .map(
            (o) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2 font-mono text-xs">${o.code}</td>
      <td class="px-4 py-2">${o.user?.name || '—'}</td>
      <td class="px-4 py-2">${Number(o.total_price).toLocaleString()}</td>
      <td class="px-4 py-2 text-sm text-accent-600">${o.updated_at}</td>
    </tr>`
        )
        .join('');
}

async function fetchCompleted(page = 1) {
    if (state.loading) return;
    state.loading = true;

    try {
        const res = await api.get('/baker/orders/schedule', { params: getParams(page) });
        const rows = res.data?.data || res.data || [];
        const meta = res.data?.meta || {};

        renderRows(rows);
        state.page = meta.current_page || page;
        state.lastPage = meta.last_page || 1;
        state.total = meta.total || 0;
        renderPagination();
    } finally {
        state.loading = false;
        renderPagination();
    }
}

export default async function init() {
    await guard('baker');

    document.getElementById('bk-completed-search')?.addEventListener('input', () => fetchCompleted(1));
    document.getElementById('bk-completed-sort')?.addEventListener('change', () => fetchCompleted(1));
    document.getElementById('bk-completed-date')?.addEventListener('change', () => fetchCompleted(1));

    document.getElementById('bk-completed-page-prev')?.addEventListener('click', () => {
        if (state.page > 1) fetchCompleted(state.page - 1);
    });

    document.getElementById('bk-completed-page-next')?.addEventListener('click', () => {
        if (state.page < state.lastPage) fetchCompleted(state.page + 1);
    });

    renderPagination();
    await fetchCompleted(1);
}

