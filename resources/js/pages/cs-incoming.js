import api from '../api';
import { guard } from '../auth';

function render(rows) {
    const tbody = document.querySelector('#cs-incoming tbody');
    tbody.innerHTML = rows
        .map(
            (o) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2 font-mono text-xs">${o.code}</td>
      <td class="px-4 py-2">${o.user?.name || '—'}</td>
      <td class="px-4 py-2">${Number(o.total_price).toLocaleString()}</td>
      <td class="px-4 py-2 text-sm">${o.created_at}</td>
    </tr>`
        )
        .join('');
}

export default async function init() {
    await guard('customer_service');
    const load = async () => {
        const { data } = await api.get('/cs/orders/incoming');
        render(data);
    };
    await load();
    setInterval(load, 15000);
}
