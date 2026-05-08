import api from '../api';
import { guard } from '../auth';

function toast(m, t = 'success') {
    window.CakesAuth.toast(m, t);
}

export default async function init() {
    await guard('customer_service');
    const { data } = await api.get('/cs/orders/incoming');
    const root = document.getElementById('cs-validate');
    root.innerHTML = data
        .map(
            (o) => `<div class="card space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-2">
          <p class="font-mono text-sm">${o.code}</p>
          <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">${o.status}</span>
        </div>
        <p class="text-sm text-accent-700">${o.user?.name || '—'} · ${Number(o.total_price).toLocaleString()}</p>
        <textarea id="msg-${o.id}" class="input text-sm" rows="2" placeholder="Message for revisions (optional)"></textarea>
        <div class="flex flex-wrap gap-2">
          <button data-approve="${o.id}" class="btn-primary text-sm">Approve</button>
          <button data-rev="${o.id}" class="btn-secondary text-sm">Request revisions</button>
        </div>
      </div>`
        )
        .join('');

    root.addEventListener('click', async (e) => {
        const t = e.target;
        if (t.matches('[data-approve]')) {
            await api.post(`/cs/orders/${t.dataset.approve}/validate`, { action: 'approve' });
            toast('Order approved');
            window.location.reload();
        }
        if (t.matches('[data-rev]')) {
            const id = t.dataset.rev;
            const message = document.getElementById(`msg-${id}`)?.value || '';
            await api.post(`/cs/orders/${id}/validate`, { action: 'request_revisions', message });
            toast('Revision requested');
            window.location.reload();
        }
    });
}
