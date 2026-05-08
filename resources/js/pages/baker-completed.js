import api from '../api';
import { guard } from '../auth';

export default async function init() {
    await guard('baker');
    const { data } = await api.get('/baker/orders', { params: { status: 'completed' } });
    const tbody = document.querySelector('#bk-done tbody');
    tbody.innerHTML = data
        .map(
            (o) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2 font-mono text-xs">${o.code}</td>
      <td class="px-4 py-2">${o.user?.name || '—'}</td>
      <td class="px-4 py-2">${Number(o.total_price).toLocaleString()}</td>
      <td class="px-4 py-2 text-sm text-accent-600">${o.updated_at}</td>
    </tr>`
        )
        .join('');
}
