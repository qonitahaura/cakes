import api from '../api';
import { guard } from '../auth';

let state = {
    users: [],
    page: 1,
    lastPage: 1,
    perPage: 10,
    total: 0,
    loading: false,
};

let debounceTimer = null;

function toast(msg, type = 'success') {
    window.CakesAuth.toast(msg, type);
}

function getSort() {
    const sortUi = document.getElementById('user-sort')?.value || 'newest';
    return sortUi === 'oldest' ? 'oldest' : 'newest';
}

function getParams({ page }) {
    return {
        search: document.getElementById('user-search')?.value || '',
        role: document.getElementById('user-role-filter')?.value || '',
        sort: getSort(),
        page: page ?? 1,
        per_page: state.perPage,
    };
}

function renderTable() {
    const tbody = document.querySelector('#tbl-users tbody');
    if (!tbody) return;

    tbody.innerHTML = state.users
        .map(
            (u) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2">${u.id}</td>
      <td class="px-4 py-2">${u.name}</td>
      <td class="px-4 py-2">${u.email}</td>
      <td class="px-4 py-2">${(u.roles || [])
          .map((r) => (typeof r === 'string' ? r : r?.name))
          .filter(Boolean)
          .join(', ')}</td>
      <td class="px-4 py-2 text-right space-x-2">
        <button data-edit="${u.id}" class="text-primary-600 hover:underline text-sm">Edit</button>
        <button data-role="${u.id}" class="text-accent-600 hover:underline text-sm">Role</button>
        <button data-del="${u.id}" class="text-red-600 hover:underline text-sm">Delete</button>
      </td>
    </tr>`
        )
        .join('');

    const meta = document.getElementById('user-pagination-meta');
    const indicator = document.getElementById('user-page-indicator');
    if (meta) meta.textContent = `${state.total} total`;
    if (indicator) indicator.textContent = `Page ${state.page} / ${state.lastPage}`;

    const prevBtn = document.getElementById('user-page-prev');
    const nextBtn = document.getElementById('user-page-next');
    if (prevBtn) prevBtn.disabled = state.page <= 1;
    if (nextBtn) nextBtn.disabled = state.page >= state.lastPage;
}

async function fetchUsers(page = 1) {
    if (state.loading) return;
    state.loading = true;
    try {
        const params = getParams({ page });
        const res = await api.get('/admin/users', { params });

        state.users = res.data?.data || [];
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
    debounceTimer = setTimeout(() => fetchUsers(1), 350);
}

export default async function init() {
    await guard('admin');

    await fetchUsers(1);

    document.getElementById('user-search')?.addEventListener('input', scheduleFetch);
    document.getElementById('user-role-filter')?.addEventListener('change', () => fetchUsers(1));
    document.getElementById('user-sort')?.addEventListener('change', () => fetchUsers(1));

    document.getElementById('user-page-prev')?.addEventListener('click', () => {
        if (state.page > 1) fetchUsers(state.page - 1);
    });

    document.getElementById('user-page-next')?.addEventListener('click', () => {
        if (state.page < state.lastPage) fetchUsers(state.page + 1);
    });

    document.getElementById('btn-user-create')?.addEventListener('click', () => {
        const m = document.getElementById('modal-user');
        m?.classList.remove('hidden');
        m?.classList.add('flex');
        document.getElementById('user-form-id').value = '';
        document.getElementById('uf-role-wrap')?.classList.remove('hidden');
    });

    document.getElementById('modal-user-close')?.addEventListener('click', () => {
        const m = document.getElementById('modal-user');
        m?.classList.add('hidden');
        m?.classList.remove('flex');
    });

    document.getElementById('user-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('user-form-id').value;
        const payload = {
            name: document.getElementById('uf-name').value,
            email: document.getElementById('uf-email').value,
            phone: document.getElementById('uf-phone').value,
            address: document.getElementById('uf-address').value,
        };

        const pw = document.getElementById('uf-password').value;
        try {
            if (id) {
                if (pw) payload.password = pw;
                await api.put(`/admin/users/${id}`, payload);
                toast('User updated');
            } else {
                payload.password = pw;
                payload.role = document.getElementById('uf-role').value;
                await api.post('/admin/users', payload);
                toast('User created');
            }

            const m = document.getElementById('modal-user');
            m?.classList.add('hidden');
            m?.classList.remove('flex');

            await fetchUsers(1);
        } catch (err) {
            toast(err.response?.data?.message || 'Save failed', 'error');
        }
    });

    document.querySelector('#tbl-users')?.addEventListener('click', async (e) => {
        const t = e.target;
        if (t.matches('[data-del]')) {
            if (!confirm('Delete user?')) return;
            await api.delete(`/admin/users/${t.dataset.del}`);
            toast('Deleted');
            await fetchUsers(1);
        }

        if (t.matches('[data-edit]')) {
            const u = state.users.find((x) => String(x.id) === String(t.dataset.edit));
            if (!u) return;

            const m = document.getElementById('modal-user');
            m?.classList.remove('hidden');
            m?.classList.add('flex');

            document.getElementById('user-form-id').value = u.id;
            document.getElementById('uf-name').value = u.name;
            document.getElementById('uf-email').value = u.email;
            document.getElementById('uf-phone').value = u.phone || '';
            document.getElementById('uf-address').value = u.address || '';
            document.getElementById('uf-password').value = '';
            document.getElementById('uf-role-wrap').classList.add('hidden');
        }

        if (t.matches('[data-role]')) {
            const rid = t.dataset.role;
            const role = prompt('Role: admin, baker, customer_service, customer');
            if (!role) return;
            await api.post(`/admin/users/${rid}/role`, { role });
            toast('Role updated');
            await fetchUsers(1);
        }
    });
}

