import api from '../api';
import { guard } from '../auth';

let list = [];

function toast(m, t = 'success') {
    window.CakesAuth.toast(m, t);
}

function render() {
    const tbody = document.querySelector('#tbl-customizations tbody');
    tbody.innerHTML = list
        .map(
            (c) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2">${c.id}</td>
      <td class="px-4 py-2 font-medium">${c.name}</td>
      <td class="px-4 py-2"><span class="rounded-full bg-accent-100 px-2 py-0.5 text-xs">${c.type}</span></td>
      <td class="px-4 py-2">${(c.options || []).length} options</td>
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
    list = (await api.get('/admin/customizations')).data;
    render();

    document.getElementById('btn-cz-add')?.addEventListener('click', () => openModal(null));
    document.getElementById('modal-cz-close')?.addEventListener('click', closeModal);

    function openModal(row) {
        const m = document.getElementById('modal-cz');
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.getElementById('cz-id').value = row?.id || '';
        document.getElementById('cz-name').value = row?.name || '';
        document.getElementById('cz-type').value = row?.type || 'select';
        const opts = (row?.options || []).map((o) => `${o.option_name}|${o.additional_price ?? 0}`).join('\n');
        document.getElementById('cz-options').value = opts;
    }

    function closeModal() {
        const m = document.getElementById('modal-cz');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    document.getElementById('cz-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('cz-id').value;
        const name = document.getElementById('cz-name').value;
        const type = document.getElementById('cz-type').value;
        const lines = document.getElementById('cz-options').value.split('\n').map((l) => l.trim()).filter(Boolean);
        const options = lines.map((line) => {
            const [option_name, price] = line.split('|');
            return { option_name: option_name.trim(), additional_price: parseFloat(price) || 0 };
        });
        try {
            if (id) {
                await api.put(`/admin/customizations/${id}`, { name, type, options });
            } else {
                await api.post('/admin/customizations', { name, type, options });
            }
            toast('Saved');
            closeModal();
            list = (await api.get('/admin/customizations')).data;
            render();
        } catch (err) {
            toast(err.response?.data?.message || 'Error', 'error');
        }
    });

    document.querySelector('#tbl-customizations')?.addEventListener('click', async (e) => {
        const t = e.target;
        if (t.matches('[data-del]')) {
            if (!confirm('Delete customization?')) return;
            await api.delete(`/admin/customizations/${t.dataset.del}`);
            toast('Deleted');
            list = (await api.get('/admin/customizations')).data;
            render();
        }
        if (t.matches('[data-edit]')) {
            const row = list.find((x) => String(x.id) === String(t.dataset.edit));
            openModal(row);
        }
    });
}
