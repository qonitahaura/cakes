import api from '../api';
import { guard } from '../auth';

export default async function init() {
    await guard('baker');
    const { data } = await api.get('/baker/orders/schedule');
    const root = document.getElementById('bk-schedule');
    const now = Date.now();
    root.innerHTML = data
        .map((o) => {
            const dt = o.fulfillment_type === 'pickup' ? `${o.pickup_date} ${o.pickup_time || ''}` : `${o.delivery_date} ${o.delivery_time || ''}`;
            const ts = Date.parse(dt);
            const urgent = !Number.isNaN(ts) && ts - now < 48 * 3600 * 1000;
            return `<div class="rounded-xl border ${urgent ? 'border-red-200 bg-red-50/50' : 'border-accent-100 bg-white'} p-4">
        <div class="flex items-center justify-between gap-2">
          <p class="font-mono text-xs">${o.code}</p>
          ${urgent ? '<span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-800">Urgent</span>' : ''}
        </div>
        <p class="mt-1 font-medium">${o.user?.name || '—'}</p>
        <p class="text-sm text-accent-600">${o.fulfillment_type} · ${dt}</p>
        <p class="mt-2 text-xs text-accent-500">${o.status}</p>
      </div>`;
        })
        .join('');
}
