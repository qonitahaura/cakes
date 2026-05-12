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

let debounceTimer = null;

function getSort() {
    const sortUi = document.getElementById('order-sort')?.value || 'newest';
    return sortUi === 'oldest' ? 'oldest' : 'newest';
}

function getParams({ page }) {
    return {
        search: document.getElementById('order-search')?.value || '',
        status: document.getElementById('order-filter')?.value || '',
        payment_status: document.getElementById('order-payment-filter')?.value || '',
        pickup_date: document.getElementById('order-pickup-date')?.value || '',
        sort: getSort(),
        page,
        per_page: state.perPage,
    };
}

function renderTable() {
    const tbody = document.querySelector('#tbl-orders tbody');
    if (!tbody) return;

    tbody.innerHTML = state.orders
        .map(
            (o) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2 font-mono text-xs">${o.code}</td>
      <td class="px-4 py-2">${o.user?.name || '—'}</td>
      <td class="px-4 py-2">${Number(o.total_price).toLocaleString()}</td>
      <td class="px-4 py-2"><span class="rounded-full bg-primary-100 px-2 py-0.5 text-xs font-medium text-primary-800">${o.status}</span></td>
      <td class="px-4 py-2 text-right"><a class="text-primary-600 hover:underline" href="/admin/orders/${o.id}">Details</a></td>
    </tr>`
        )
        .join('');

    const meta = document.getElementById('orders-pagination-meta');
    const indicator = document.getElementById('orders-page-indicator');
    if (meta) meta.textContent = `${state.total} total`;
    if (indicator) indicator.textContent = `Page ${state.page} / ${state.lastPage}`;

    const prevBtn = document.getElementById('orders-page-prev');
    const nextBtn = document.getElementById('orders-page-next');
    if (prevBtn) prevBtn.disabled = state.page <= 1;
    if (nextBtn) nextBtn.disabled = state.page >= state.lastPage;
}

async function fetchOrders(page = 1) {
    if (state.loading) return;
    state.loading = true;
    try {
        const res = await api.get('/admin/orders', { params: getParams({ page }) });
        state.orders = res.data?.data || [];
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
    debounceTimer = setTimeout(() => fetchOrders(1), 350);
}

export default async function init() {
    await guard('admin');

    await fetchOrders(1);

    // Search debounce
    document.getElementById('order-search')?.addEventListener('input', () => scheduleFetch());

    // Filters/sort
    document.getElementById('order-filter')?.addEventListener('change', () => fetchOrders(1));
    document.getElementById('order-payment-filter')?.addEventListener('change', () => fetchOrders(1));
    document.getElementById('order-pickup-date')?.addEventListener('change', () => fetchOrders(1));
    document.getElementById('order-sort')?.addEventListener('change', () => fetchOrders(1));

    // Pagination
    document.getElementById('orders-page-prev')?.addEventListener('click', () => {
        if (state.page > 1) fetchOrders(state.page - 1);
    });

    document.getElementById('orders-page-next')?.addEventListener('click', () => {
        if (state.page < state.lastPage) fetchOrders(state.page + 1);
    });
}

