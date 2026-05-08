import api from '../api';
import { guard } from '../auth';

let users = [];

function toast(msg, type = 'success') {
    window.CakesAuth.toast(msg, type);
}

function renderTable() {
    const tbody = document.querySelector('#tbl-users tbody');
    const q = (document.getElementById('user-search')?.value || '').toLowerCase();
    const rows = users.filter(
        (u) => !q || u.name?.toLowerCase().includes(q) || u.email?.toLowerCase().includes(q)
    );
    tbody.innerHTML = rows
        .map(
            (u) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2">${u.id}</td>
      <td class="px-4 py-2">${u.name}</td>
      <td class="px-4 py-2">${u.email}</td>
      <td class="px-4 py-2">${(u.roles || []).map((r) => (typeof r === 'string' ? r : r?.name)).filter(Boolean).join(', ')}</td>
      <td class="px-4 py-2 text-right space-x-2">
        <button data-edit="${u.id}" class="text-primary-600 hover:underline text-sm">Edit</button>
        <button data-role="${u.id}" class="text-accent-600 hover:underline text-sm">Role</button>
        <button data-del="${u.id}" class="text-red-600 hover:underline text-sm">Delete</button>
      </td>
    </tr>`
        )
        .join('');
}

export default async function init() {
    await guard('admin');
    const { data } = await api.get('/admin/users');
    users = data;
    renderTable();

    document.getElementById('user-search')?.addEventListener('input', renderTable);

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
            const res = await api.get('/admin/users');
            users = res.data;
            renderTable();
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
            users = (await api.get('/admin/users')).data;
            renderTable();
        }
        if (t.matches('[data-edit]')) {
            const u = users.find((x) => String(x.id) === String(t.dataset.edit));
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
            users = (await api.get('/admin/users')).data;
            renderTable();
        }
    });
}
