import api from '../api';
import { guard } from '../auth';

function badge(status) {
    const cls =
        status === 'paid'
            ? 'bg-emerald-100 text-emerald-800'
            : status === 'unpaid'
              ? 'bg-amber-100 text-amber-800'
              : 'bg-red-100 text-red-800';
    return `<span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold ${cls}">${status}</span>`;
}

export default async function init() {
    await guard('admin');
    const { data } = await api.get('/admin/payments');
    const tbody = document.querySelector('#tbl-payments tbody');
    tbody.innerHTML = data
        .map(
            (p) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2">${p.id}</td>
      <td class="px-4 py-2 font-mono text-xs">${p.order?.code || p.order_id}</td>
      <td class="px-4 py-2">${Number(p.amount).toLocaleString()}</td>
      <td class="px-4 py-2">${badge(p.payment_status)}</td>
      <td class="px-4 py-2 text-sm text-accent-600">${p.paid_at || '—'}</td>
    </tr>`
        )
        .join('');
}
