import api from '../api';
import { guard } from '../auth';

let rows = [];

function toast(m, t = 'success') {
    window.CakesAuth.toast(m, t);
}

function render() {
    const tbody = document.querySelector('#tbl-categories tbody');
    tbody.innerHTML = rows
        .map(
            (c) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2">${c.id}</td>
      <td class="px-4 py-2">${c.name}</td>
      <td class="px-4 py-2 font-mono text-xs">${c.slug}</td>
      <td class="px-4 py-2 text-right space-x-2">
        <button data-edit="${c.id}" class="text-primary-600 hover:underline text-sm">Edit</button>
        <button data-del="${c.id}" class="text-red-600 hover:underline text-sm">Delete</button>
      </td>
    </tr>`
        )
        .join('');
}

export default async function init() {
    await guard('admin');
    rows = (await api.get('/admin/categories')).data;
    render();

    document.getElementById('btn-cat-add')?.addEventListener('click', () => {
        document.getElementById('modal-cat').classList.remove('hidden');
        document.getElementById('modal-cat').classList.add('flex');
        document.getElementById('cat-id').value = '';
        document.getElementById('cat-name').value = '';
        document.getElementById('cat-slug').value = '';
    });
    document.getElementById('modal-cat-close')?.addEventListener('click', () => {
        document.getElementById('modal-cat').classList.add('hidden');
        document.getElementById('modal-cat').classList.remove('flex');
    });

    document.getElementById('cat-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('cat-id').value;
        const body = {
            name: document.getElementById('cat-name').value,
            slug: document.getElementById('cat-slug').value || null,
        };
        try {
            if (id) await api.put(`/admin/categories/${id}`, body);
            else await api.post('/admin/categories', body);
            toast('Saved');
            document.getElementById('modal-cat').classList.add('hidden');
            document.getElementById('modal-cat').classList.remove('flex');
            rows = (await api.get('/admin/categories')).data;
            render();
        } catch (err) {
            toast(err.response?.data?.message || 'Error', 'error');
        }
    });

    document.querySelector('#tbl-categories')?.addEventListener('click', async (e) => {
        const t = e.target;
        if (t.matches('[data-del]')) {
            if (!confirm('Delete?')) return;
            await api.delete(`/admin/categories/${t.dataset.del}`);
            toast('Deleted');
            rows = (await api.get('/admin/categories')).data;
            render();
        }
        if (t.matches('[data-edit]')) {
            const c = rows.find((x) => String(x.id) === String(t.dataset.edit));
            document.getElementById('modal-cat').classList.remove('hidden');
            document.getElementById('modal-cat').classList.add('flex');
            document.getElementById('cat-id').value = c.id;
            document.getElementById('cat-name').value = c.name;
            document.getElementById('cat-slug').value = c.slug;
        }
    });
}
