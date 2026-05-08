import api from '../api';
import { guard } from '../auth';

let products = [];
let categories = [];
let customizations = [];

function toast(m, t = 'success') {
    window.CakesAuth.toast(m, t);
}

function render() {
    const tbody = document.querySelector('#tbl-products tbody');
    tbody.innerHTML = products
        .map(
            (p) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2">${p.id}</td>
      <td class="px-4 py-2">${p.name}</td>
      <td class="px-4 py-2">${p.category?.name || p.category_id}</td>
      <td class="px-4 py-2">${Number(p.base_price).toLocaleString()}</td>
      <td class="px-4 py-2">${p.is_available ? 'Yes' : 'No'}</td>
      <td class="px-4 py-2 text-right space-x-2">
        <button data-cust="${p.id}" class="text-accent-600 hover:underline text-sm">Customizations</button>
        <button data-edit="${p.id}" class="text-primary-600 hover:underline text-sm">Edit</button>
        <button data-del="${p.id}" class="text-red-600 hover:underline text-sm">Delete</button>
      </td>
    </tr>`
        )
        .join('');
}

export default async function init() {
    await guard('admin');
    [products, categories, customizations] = await Promise.all([
        api.get('/products').then((r) => r.data),
        api.get('/admin/categories').then((r) => r.data),
        api.get('/admin/customizations').then((r) => r.data),
    ]);

    const catSel = document.getElementById('pf-category');
    catSel.innerHTML = categories.map((c) => `<option value="${c.id}">${c.name}</option>`).join('');

    render();

    document.getElementById('btn-prod-add')?.addEventListener('click', () => {
        document.getElementById('modal-prod').classList.remove('hidden');
        document.getElementById('modal-prod').classList.add('flex');
        document.getElementById('prod-id').value = '';
        document.getElementById('pf-name').value = '';
        document.getElementById('pf-slug').value = '';
        document.getElementById('pf-desc').value = '';
        document.getElementById('pf-price').value = '';
        document.getElementById('pf-available').checked = true;
        document.getElementById('pf-custom').checked = false;
        document.getElementById('pf-image').value = '';
    });
    document.getElementById('modal-prod-close')?.addEventListener('click', () => {
        document.getElementById('modal-prod').classList.add('hidden');
        document.getElementById('modal-prod').classList.remove('flex');
    });

    document.getElementById('prod-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('prod-id').value;
        const fd = new FormData();
        fd.append('name', document.getElementById('pf-name').value);
        fd.append('slug', document.getElementById('pf-slug').value);
        fd.append('description', document.getElementById('pf-desc').value);
        fd.append('base_price', document.getElementById('pf-price').value);
        fd.append('category_id', document.getElementById('pf-category').value);
        fd.append('is_available', document.getElementById('pf-available').checked ? '1' : '0');
        fd.append('is_custom', document.getElementById('pf-custom').checked ? '1' : '0');
        const img = document.getElementById('pf-image').files[0];
        if (img) fd.append('image', img);
        try {
            if (id) {
                await api.put(`/admin/products/${id}`, fd, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                });
            } else {
                await api.post('/admin/products', fd, { headers: { 'Content-Type': 'multipart/form-data' } });
            }
            toast('Saved');
            document.getElementById('modal-prod').classList.add('hidden');
            document.getElementById('modal-prod').classList.remove('flex');
            products = (await api.get('/products')).data;
            render();
        } catch (err) {
            toast(err.response?.data?.message || 'Error', 'error');
        }
    });

    document.querySelector('#tbl-products')?.addEventListener('click', async (e) => {
        const t = e.target;
        if (t.matches('[data-del]')) {
            if (!confirm('Delete product?')) return;
            await api.delete(`/admin/products/${t.dataset.del}`);
            toast('Deleted');
            products = (await api.get('/products')).data;
            render();
        }
        if (t.matches('[data-edit]')) {
            const p = products.find((x) => String(x.id) === String(t.dataset.edit));
            document.getElementById('modal-prod').classList.remove('hidden');
            document.getElementById('modal-prod').classList.add('flex');
            document.getElementById('prod-id').value = p.id;
            document.getElementById('pf-name').value = p.name;
            document.getElementById('pf-slug').value = p.slug;
            document.getElementById('pf-desc').value = p.description || '';
            document.getElementById('pf-price').value = p.base_price;
            document.getElementById('pf-category').value = p.category_id;
            document.getElementById('pf-available').checked = !!p.is_available;
            document.getElementById('pf-custom').checked = !!p.is_custom;
            document.getElementById('pf-image').value = '';
        }
        if (t.matches('[data-cust]')) {
            const pid = t.dataset.cust;
            const p = products.find((x) => String(x.id) === String(pid));
            document.getElementById('modal-cust').classList.remove('hidden');
            document.getElementById('modal-cust').classList.add('flex');
            document.getElementById('cust-prod-id').value = pid;
            const box = document.getElementById('cust-checkboxes');
            box.innerHTML = customizations
                .map((c) => {
                    const attached = (p.customizations || []).find((x) => String(x.id) === String(c.id));
                    const checked = attached ? 'checked' : '';
                    return `<label class="flex items-center gap-2 py-1 text-sm">
              <input type="checkbox" value="${c.id}" class="rounded border-accent-300 text-primary-600" ${checked} />
              <span>${c.name} <span class="text-accent-400">(${c.type})</span></span>
            </label>`;
                })
                .join('');
        }
    });

    document.getElementById('modal-cust-close')?.addEventListener('click', () => {
        document.getElementById('modal-cust').classList.add('hidden');
        document.getElementById('modal-cust').classList.remove('flex');
    });

    document.getElementById('cust-save')?.addEventListener('click', async () => {
        const pid = document.getElementById('cust-prod-id').value;
        const selected = [...document.querySelectorAll('#cust-checkboxes input:checked')].map((el) => ({
            id: Number(el.value),
            is_required: false,
            max_select: null,
            sort_order: 0,
        }));
        try {
            await api.post(`/admin/products/${pid}/customizations`, { customizations: selected });
            toast('Customizations saved');
            document.getElementById('modal-cust').classList.add('hidden');
            document.getElementById('modal-cust').classList.remove('flex');
            products = (await api.get('/products')).data;
            render();
        } catch (err) {
            toast(err.response?.data?.message || 'Error', 'error');
        }
    });
}
