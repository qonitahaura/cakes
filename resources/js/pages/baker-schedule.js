import api from '../api';
import { guard } from '../auth';

function toast(m, t = 'success') {
    window.CakesAuth.toast(m, t);
}

let state = {
    page: 1,
    lastPage: 1,
    perPage: 9,
    total: 0,
    loading: false,
    debounceTimer: null,
};

function getParams(page) {
    const elSearch = document.getElementById('bk-schedule-search');

    const elDate = document.getElementById('bk-schedule-date');

    return {
        page,
        per_page: state.perPage,
        search: elSearch?.value || '',
        // completion_status tidak dipakai untuk halaman schedule (completed disaring di backend).

        pickup_date: elDate?.value || '',
        fulfillment_type: document.getElementById('bk-schedule-fulfillment')?.value || ''


    };
}

function renderPagination() {
    const indicator = document.getElementById('bk-schedule-page-indicator');
    const meta = document.getElementById('bk-schedule-pagination-meta');
    const prev = document.getElementById('bk-schedule-page-prev');
    const next = document.getElementById('bk-schedule-page-next');

    if (meta) meta.textContent = `${state.total} total`;
    if (indicator) indicator.textContent = `Page ${state.page} / ${state.lastPage}`;
    if (prev) prev.disabled = state.page <= 1 || state.loading;
    if (next) next.disabled = state.page >= state.lastPage || state.loading;
}

function renderCards(data) {
    const root = document.getElementById('bk-schedule');
    if (!root) return;

    const now = Date.now();
    root.innerHTML = data
        .map((o) => {
            const dt = o.fulfillment_type === 'pickup'
                ? `${o.pickup_date} ${o.pickup_time || ''}`
                : `${o.delivery_date} ${o.delivery_time || ''}`;
            const ts = Date.parse(dt);
            const urgent = !Number.isNaN(ts) && ts - now < 48 * 3600 * 1000;
            return `<div class="rounded-xl border ${urgent ? 'border-red-200 bg-red-50/50' : 'border-accent-100 bg-white'} p-4">
        <div class="flex items-center justify-between gap-2">
          <p class="font-mono text-xs">${o.code}</p>
          ${urgent ? '<span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-800">Urgent</span>' : ''}
        </div>
        <p class="mt-1 font-medium">${o.user?.name || '—'}</p>
        <p class="text-sm text-accent-600">${o.fulfillment_type} · ${dt}</p>
        <p class="mt-2 text-xs text-accent-500">${o.status}</p>
      </div>`;
        })
        .join('');
}

async function fetchSchedule(page = 1) {
    if (state.loading) return;
    state.loading = true;

    try {
        const res = await api.get('/baker/orders/schedule', { params: getParams(page) });
        const rows = res.data?.data || res.data || [];
        const meta = res.data?.meta || {};

        renderCards(rows);
        state.page = meta.current_page || page;
        state.lastPage = meta.last_page || 1;
        state.total = meta.total || 0;
        renderPagination();
    } finally {
        state.loading = false;
        renderPagination();
    }
}

function scheduleSearch() {
    if (state.debounceTimer) clearTimeout(state.debounceTimer);
    state.debounceTimer = setTimeout(() => fetchSchedule(1), 350);
}

export default async function init() {
    await guard('baker');

    document.getElementById('bk-schedule-search')?.addEventListener('input', () => scheduleSearch());
    document.getElementById('bk-schedule-fulfillment')?.addEventListener('change', () => fetchSchedule(1));
    document.getElementById('bk-schedule-date')?.addEventListener('change', () => fetchSchedule(1));

    document.getElementById('bk-schedule-page-prev')?.addEventListener('click', () => {
        if (state.page > 1) fetchSchedule(state.page - 1);
    });
    document.getElementById('bk-schedule-page-next')?.addEventListener('click', () => {
        if (state.page < state.lastPage) fetchSchedule(state.page + 1);
    });

    renderPagination();
    await fetchSchedule(1);
}

