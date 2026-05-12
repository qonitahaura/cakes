import api from '../api';
import { guard } from '../auth';

let state = {
    reviews: [],
    page: 1,
    lastPage: 1,
    perPage: 10,
    total: 0,
    loading: false,
};

let debounceTimer = null;

function getParams({ page }) {
    return {
        search: document.getElementById('rev-search')?.value || '',
        rating: document.getElementById('rev-rating')?.value || '',
        product_id: document.getElementById('rev-product')?.value || '',
        sort: document.getElementById('rev-sort')?.value || 'newest',
        page,
        per_page: state.perPage,
    };
}

function renderTable() {
    const tbody = document.querySelector('#tbl-reviews tbody');
    if (!tbody) return;

    tbody.innerHTML = state.reviews
        .map(
            (r) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2">${r.id}</td>
      <td class="px-4 py-2">${r.product?.name || r.product_id}</td>
      <td class="px-4 py-2">${r.user?.name || '—'}</td>
      <td class="px-4 py-2">${r.rating} ★</td>
      <td class="px-4 py-2 max-w-md truncate">${r.comment || ''}</td>
      <td class="px-4 py-2 text-right">
        <button data-del="${r.id}" class="text-red-600 hover:underline text-sm">Remove</button>
      </td>
    </tr>`
        )
        .join('');

    const meta = document.getElementById('reviews-pagination-meta');
    const indicator = document.getElementById('reviews-page-indicator');
    if (meta) meta.textContent = `${state.total} total`;
    if (indicator) indicator.textContent = `Page ${state.page} / ${state.lastPage}`;

    const prevBtn = document.getElementById('reviews-page-prev');
    const nextBtn = document.getElementById('reviews-page-next');
    if (prevBtn) prevBtn.disabled = state.page <= 1;
    if (nextBtn) nextBtn.disabled = state.page >= state.lastPage;
}

async function fetchReviews(page = 1) {
    if (state.loading) return;
    state.loading = true;
    try {
        const res = await api.get('/admin/reviews', { params: getParams({ page }) });
        state.reviews = res.data?.data || [];
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
    debounceTimer = setTimeout(() => fetchReviews(1), 350);
}

export default async function init() {
    await guard('admin');

    await fetchReviews(1);

    document.getElementById('rev-search')?.addEventListener('input', () => scheduleFetch());
    document.getElementById('rev-rating')?.addEventListener('change', () => fetchReviews(1));
    document.getElementById('rev-product')?.addEventListener('input', () => scheduleFetch());
    document.getElementById('rev-sort')?.addEventListener('change', () => fetchReviews(1));

    document.getElementById('reviews-page-prev')?.addEventListener('click', () => {
        if (state.page > 1) fetchReviews(state.page - 1);
    });

    document.getElementById('reviews-page-next')?.addEventListener('click', () => {
        if (state.page < state.lastPage) fetchReviews(state.page + 1);
    });

    document.querySelector('#tbl-reviews')?.addEventListener('click', async (e) => {
        const t = e.target;
        if (t.matches('[data-del]')) {
            if (!confirm('Remove this review?')) return;
            await api.delete(`/admin/reviews/${t.dataset.del}`);
            window.CakesAuth.toast('Review removed', 'success');
            await fetchReviews(state.page);
        }
    });
}

