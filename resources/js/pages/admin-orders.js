import api from '../api';
import { guard } from '../auth';

let orders = [];

function render() {
    const st = document.getElementById('order-filter')?.value || '';
    const tbody = document.querySelector('#tbl-orders tbody');
    const rows = st ? orders.filter((o) => o.status === st) : orders;
    tbody.innerHTML = rows
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
}

export default async function init() {
    await guard('admin');
    orders = (await api.get('/admin/orders')).data;
    render();
    document.getElementById('order-filter')?.addEventListener('change', render);
}
