import api from '../api';
import { guard } from '../auth';

export default async function init() {
    await guard('baker');
    const [activeRes, completedRes, scheduleRes] = await Promise.all([
        api.get('/baker/orders'),
        api.get('/baker/orders', { params: { status: 'completed' } }),
        api.get('/baker/orders/schedule'),
    ]);
    const active = activeRes.data.filter((o) => o.status !== 'completed');
    const completed = completedRes.data;
    const urgent = scheduleRes.data.slice(0, 5);

    document.getElementById('bk-active').textContent = active.length;
    document.getElementById('bk-pending').textContent = active.filter((o) => o.status === 'paid').length;
    document.getElementById('bk-done').textContent = completed.length;

    const el = document.getElementById('bk-urgent');
    if (el) {
        el.innerHTML = urgent
            .map(
                (o) => `<div class="flex items-center justify-between rounded-xl border border-accent-100 bg-white px-4 py-3">
          <div>
            <p class="font-mono text-xs text-accent-500">${o.code}</p>
            <p class="font-medium">${o.user?.name || '—'}</p>
            <p class="text-xs text-accent-600">${o.fulfillment_type} · ${o.pickup_date || o.delivery_date || '—'}</p>
          </div>
          <span class="rounded-full bg-primary-100 px-2 py-0.5 text-xs font-medium text-primary-800">${o.status}</span>
        </div>`
            )
            .join('');
    }
}
