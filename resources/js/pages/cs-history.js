import api from '../api';
import { guard } from '../auth';

export default async function init() {
    await guard('customer_service');
    const { data } = await api.get('/cs/orders/history');
    const tbody = document.querySelector('#cs-history tbody');
    tbody.innerHTML = data
        .map(
            (o) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2 font-mono text-xs">${o.code}</td>
      <td class="px-4 py-2">${o.user?.name || '—'}</td>
      <td class="px-4 py-2">${o.status}</td>
      <td class="px-4 py-2 text-sm">${o.updated_at}</td>
    </tr>`
        )
        .join('');
}
