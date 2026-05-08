import api from '../api';
import { guard } from '../auth';

function toast(m, t = 'success') {
    window.CakesAuth.toast(m, t);
}

export default async function init() {
    await guard('customer_service');
    const { data } = await api.get('/cs/payments');
    const tbody = document.querySelector('#cs-payments tbody');
    tbody.innerHTML = data
        .map(
            (p) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2">${p.id}</td>
      <td class="px-4 py-2 font-mono text-xs">${p.order?.code || ''}</td>
      <td class="px-4 py-2">${Number(p.amount).toLocaleString()}</td>
      <td class="px-4 py-2">${p.payment_status}</td>
      <td class="px-4 py-2 text-right space-x-2">
        <button data-dp="${p.id}" class="text-sm text-primary-600 hover:underline">Confirm DP</button>
        <button data-full="${p.id}" class="text-sm text-green-600 hover:underline">Confirm full</button>
      </td>
    </tr>`
        )
        .join('');

    tbody.addEventListener('click', async (e) => {
        const t = e.target;
        try {
            if (t.matches('[data-dp]')) {
                await api.post(`/cs/payments/${t.dataset.dp}/confirm-dp`);
                toast('DP confirmed');
                window.location.reload();
            }
            if (t.matches('[data-full]')) {
                await api.post(`/cs/payments/${t.dataset.full}/confirm-full`);
                toast('Full payment confirmed');
                window.location.reload();
            }
        } catch (err) {
            toast(err.response?.data?.message || 'Failed', 'error');
        }
    });
}
