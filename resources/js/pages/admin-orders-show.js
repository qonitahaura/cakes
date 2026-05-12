import api from '../api';
import { guard } from '../auth';

function toast(m, t = 'success') {
    window.CakesAuth.toast(m, t);
}

export default async function init() {
    await guard('admin');
    const id = document.getElementById('order-root')?.dataset?.orderId;
    if (!id) return;
    const { data: o } = await api.get(`/admin/orders/${id}`);

    document.getElementById('order-title').textContent = `Order ${o.code}`;
    document.getElementById('order-meta').innerHTML = `
      <p><span class="text-accent-500">Customer:</span> ${o.user?.name || '—'} (${o.user?.email || ''})</p>
      <p><span class="text-accent-500">Total:</span> ${Number(o.total_price).toLocaleString()}</p>
      <p><span class="text-accent-500">Status:</span> ${o.status}</p>
      <p><span class="text-accent-500">Fulfillment:</span> ${o.fulfillment_type}</p>
      <p><span class="text-accent-500">Note:</span> ${o.note || '—'}</p>
    `;

    const items = document.getElementById('order-items');
    items.innerHTML = (o.items || [])
        .map(
            (it) => `<div class="rounded-xl border border-accent-100 bg-white p-4">
        <p class="font-semibold">${it.product_name} × ${it.quantity}</p>
        <p class="text-sm text-accent-600">Final: ${Number(it.final_price).toLocaleString()}</p>
        <div class="mt-2 text-sm">${(it.customizations || [])
            .map(
                (c) => `<div class="text-accent-700">Customization #${c.customization_id}: option ${c.customization_option_id || '—'} — ${JSON.stringify(c.custom_values || {})}</div>`
            )
            .join('')}</div>
        <div class="mt-2 flex flex-wrap gap-2">${(it.designs || [])
            .map((d) => (d.image_url ? `<a href="${d.image_url}" target="_blank" class="text-primary-600 text-sm hover:underline">Design image</a>` : ''))
            .join('')}</div>
      </div>`
        )
        .join('');

    document.getElementById('status-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const status = document.getElementById('order-new-status').value;
        try {
            await api.put(`/admin/orders/${id}/status`, { status });
            toast('Status updated');
            window.location.reload();
        } catch (err) {
            toast(err.response?.data?.message || 'Failed', 'error');
        }
    });
}
