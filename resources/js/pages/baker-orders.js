import api from '../api';
import { guard } from '../auth';

function toast(m, t = 'success') {
    window.CakesAuth.toast(m, t);
}

export default async function init() {
    await guard('baker');
    const { data } = await api.get('/baker/orders');
    const tbody = document.querySelector('#bk-orders tbody');
    tbody.innerHTML = data
        .map(
            (o) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2 font-mono text-xs">${o.code}</td>
      <td class="px-4 py-2">${o.user?.name || '—'}</td>
      <td class="px-4 py-2 text-sm">${o.note || '—'}</td>
      <td class="px-4 py-2">${o.status}</td>
      <td class="px-4 py-2 text-right space-x-2">
        <button data-start="${o.id}" class="text-sm text-primary-600 hover:underline">Start</button>
        <button data-done="${o.id}" class="text-sm text-green-600 hover:underline">Complete</button>
      </td>
    </tr>`
        )
        .join('');

    tbody.addEventListener('click', async (e) => {
        const t = e.target;
        if (t.matches('[data-start]')) {
            await api.put(`/baker/orders/${t.dataset.start}/production-status`, { status: 'processing' });
            toast('Marked in progress');
            window.location.reload();
        }
        if (t.matches('[data-done]')) {
            await api.put(`/baker/orders/${t.dataset.done}/production-status`, { status: 'completed' });
            toast('Completed');
            window.location.reload();
        }
    });
}
